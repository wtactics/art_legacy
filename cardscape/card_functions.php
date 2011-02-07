<?
/********************************************
* THIS FILE CONTAINS THE FUNCTIONS THAT ARE *
* FOR CONTROLLING CARD DATA IN THE DATABASE *
*    (it shouldn't need changed at all)     *
********************************************/

function show_card($id){
	require('connect.php');

	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "cards WHERE id='$id'");
	$row = mysql_fetch_array($result);

	//add card page to the 'visited' variable
	if($_SESSION['visited'] <> null){
		$_SESSION['visited'] .= $_GET['id'] . "|";}

	//make newline characters into html-compatible "<br>"
	foreach($row as $key => $value){
		$row[$key] = str_replace("\n","<br>",$row[$key]);}

	$file = file("card.phtml");
	foreach($file as $line){
		preg_match_all("|{{[^{}]+}}|",$line,$fields);
		foreach($fields[0] as $f){
			$fieldname = str_replace("{{","",str_replace("}}","",$f));

			switch($fieldname){ //HERE'S WHERE SPECIAL REPLACES GO
				case "SUBTITLE":
					$replace = "Card Statistics, Information and History";
					break;
				case "ADMIN": //this controls display of admin elements
					if($_SESSION['role'] == 5){ //admin
						$replace = "block";}
					else{
						$replace = "none";}
					break;
				case "LEAD_DEVELOPER": //this controls display of lead dev elements
					if($_SESSION['role'] > 3){ //lead dev or admin
						$replace = "block";}
					else{
						$replace = "none";}
					break;
				case "DEVELOPER": //this controls display of developer elements
					if($_SESSION['role'] > 2){ //dev or up
						$replace = "block";}
					else{
						$replace = "none";}
					break;
				case "HISTORY":
					show_history($id);
					$replace = null;
					break;
				case "COMMENTS":
					show_comments($id);
					$replace = "";
					break;
				case "ALL_FIELDS":
					$replace = "<table class='noborder'>\n";
					$data = get_game_data_fields();
					foreach($data as $d){
						$replace .= "<tr><td>" . $d . ":</td><td class='$d'>" . $row[$d] . "</td></tr>\n";}
					$replace .= "</table>";
					break;
				case "IMAGE_FILENAME":
					$filename = $row["image"];
					$replace = $filename;
					if(!file_exists($filename)){
						$replace = "cards/not_found.png";}
					break;
				default:
					$replace = $row[$fieldname]; //normally, just replace with the field in the data
					break;
			}
			$line = str_replace($f,$replace,$line);
		}
		echo $line;
	}
}

/** SHOW THE NEW CARD FORM */
function show_new_card_form() {
	require("connect.php");
	//define the variables
	$role = $_SESSION['role'];
	$filename = str_replace(' ', '', $row['cardname']) . ".png";
	$main_tables = file('card_definition.txt');

	//output the form;
	echo "<form action='index.php?act=insert_card' method='post'>";

	//non-game-specific outputs
	echo "<table>";
	foreach( $main_tables as $table ) {
		if($table[0]<>'#'){//edit out comments
			$fieldname = substr($table, 0, strpos($table, " "));
			$fieldtype = trim(substr($table, strpos($table, " ")));
			if($fieldname == 'id'){}
			elseif($fieldname == 'revision'){}
			elseif($fieldname == 'date'){}
			elseif($fieldname == 'author'){
				if($role == 5) { //admins only
					$disabled = '';}
				else {
					$disabled = 'disabled="disabled"';}
				tablerow("Author:","<input type='text' name='$fieldname' value='" . $_SESSION['username'] . "' $disabled>");}
			elseif($fieldname == 'status'){}
			elseif($fieldname == 'image'){}
			else{}
		}
	}
	echo "</table><br>\n";

	//game-specific outputs
	echo "<table>";
	foreach( $main_tables as $table ) {
		if($table[0]<>'#'){//edit out comments
			$fieldname = substr($table, 0, strpos($table, " "));
			$fieldtype = trim(substr($table, strpos($table, " ")));
			// SKIP THE CARDSCAPE PREDEFINED VALUES
			if($fieldname == 'id'){}
			elseif($fieldname == 'revision'){}
			elseif($fieldname == 'date'){}
			elseif($fieldname == 'author'){}
			elseif($fieldname == 'status'){}
			elseif($fieldname == 'image'){}
			// NOW HANDLE EACH DATA TYPE
			elseif(!(strpos($fieldtype, "INT") === false)){
				tablerow($fieldname,"<input class='fieldtype_int' type='text' maxlength='10' name='$fieldname'>");
			}
			elseif(!(strpos($fieldtype, "TEXT") === false)){
				tablerow($fieldname,textarea($fieldname, '', 4));
			}
			elseif(!(strpos($fieldtype, "VARCHAR") === false)){
				tablerow($fieldname,"<input type=\"text\" name=\"$fieldname\">");
			}
			elseif(!(strpos($fieldtype, "ENUM") === false)){
				$list = enum_array($fieldtype); //this function is in util/util.php
				$select = "<select name='$fieldname'>";
				foreach($list as $l){
					$l = str_replace("'","",$l);
					$select .= "<option value='$l'>" . $l . "</option>";
				}
				$select .= "</select>";
				tablerow($fieldname, $select);
			}
			else{
				tablerow($fieldname . " (Datatype NYI)", $row["$fieldname"] . " - " . $fieldtype);
			}
		}
	}
	echo "</table><input type='submit' value='Submit New Card'></form>";
	
}

/* INSERT A CARD INTO THE DATABASE */
function insert_card(){
	require('connect.php');
	require('generate_card_queries.php');

	//if not logged in, don't allow
	if($_SESSION['role']==null){
		die('<span class="error">You do not have permission to create new cards. <a href="login.php">Login</a></span>');}

	//add slashes to everything
	foreach ($_POST as $key => $value) {
		$_POST[$key] = addslashes($_POST[$key]);}

	//rejects cards with no name
	if($_POST['cardname'] == ''){die('Cannot add a card with no name.');}

	//workaround for a no-author submitted bug
	if($_POST['author'] == null){
		$_POST['author'] = $_SESSION['username'];
	}
	
	//rejects cards with a name that already exists in the database
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "cards WHERE cardname='". $_POST['cardname'] . "';");
	$row = mysql_fetch_array($result);
	if($row['cardname'] == $_POST['cardname']){
		die('A card with the suggested name already exists.');}
	
	//build the INSERT
	$sql = generate_card_insert_query($_POST);
	
	//perform the INSERT
	mysql_query($sql) or die('Error: ' . mysql_error());
	
	//get the new id #
	$id = get_id_from_cardname($_POST['cardname']);

	//COPY NEW CARD TO HISTORY DB
	copy_card_to_history($id);

	//add notification to activity table
	$msg = get_card_name($id) . " added by " . $_POST['author'] . ".";
	post_activity($_POST['author'], "new", $id, $msg);

	header("Location: index.php?act=show_card&id=$id");
}

/* SHOWS THE EDIT FORM for card. Depending on the card status, not all fields will be changeable. */
function show_edit_card_form($id) {

	//blocks people without permission
	if($_SESSION['role'] < 3){die('You do not have permission to edit existing cards. Login as a developer or an admin if you want to edit cards.');}

	//this handles no card in url
	if($id==null){die("<span class='error'>No card specified.</span>");}

	//fetch card data
	require('connect.php');	
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "cards WHERE id='$id'");
	$row = mysql_fetch_array($result);

	//this handles an invalid card id
	if($row == null){die("<span class='error'>Invalid card id in url.</span>");}

	//define the variables	
	$role = $_SESSION['role'];
	$filename = str_replace(' ', '', $row['cardname']) . ".png";
	$main_tables = file('card_definition.txt');
	
	//IMAGE OUTPUT
	echo '<div style="position:absolute; right:51%">';
	echo '<div id="preview-container" style="height:430px; width:310px; border: 1px solid black;"><img src="' . $row['image'] . '" style="width:100%"></img></div>';
	echo '<form action="index.php?act=upload_image" method="post" enctype="multipart/form-data" target="upload_target">';
	echo '<input name="file" type="file"><br>';
	echo '<div id="preview-action-container" style="width:310px;">';
	echo '<input type="submit" name="submitBtn" value="Upload">';
	echo '<input type="button" name="saveBtn" onClick="saveUploadedImage()" value="Save">';
	echo '</div></form>';
	echo '<div id="preview-message"></div>';
	echo '<iframe id="upload_target" name="upload_target" onLoad="stopUpload()" style="position:absolute;width:0;height:0;border:none;"></iframe>';
	echo '</div>';

	//TABLE OUTPUT

	//output the form;
	echo "<form action='index.php?act=update_card&id=" . $row['id'] . "' method='post'>";

	//non-game-specific outputs
	echo "<table style='position:relative; left:51%'>";
	foreach( $main_tables as $table ) {
		if($table[0]<>'#'){//edit out comments
			$fieldname = substr($table, 0, strpos($table, " "));
			$fieldtype = trim(substr($table, strpos($table, " ")));
			if($fieldname == 'id'){
				tablerow("Version:", $row['id'] . ":" . $row['revision']);}
			elseif($fieldname == 'revision'){}
			elseif($fieldname == 'date'){
				tablerow("Date Submitted:", $row["$fieldname"]);}
			elseif($fieldname == 'author'){
				if($role == 5) { //admins can change the listed author
					tablerow("Author:","<input type='text' name='$fieldname' value='" . $row["$fieldname"] . "'>");}
				else {
					tablerow("Author:", $row["$fieldname"]);}}
			elseif($fieldname == 'status'){ 
				if($role == 5){ //admins can change the status to whatever
					$list = enum_array($fieldtype); //this function is in util/util.php
					$select = "<select name='$fieldname'>";
					foreach($list as $l){
						$l = str_replace("'","",$l);
						$isselected = '';
						if($row["$fieldname"] == $l){
							$isselected = "selected='selected'";}
						$select .= "<option value='$l' $isselected>" . $l . "</option>";
					}
					$select .= "</select>";
					tablerow("Status:", $select);}
				elseif(($role > 2) && ($row['status'] == 'concept')){ //devs and up can advance the status of a concept card
					$nextstatus = "discuss";
					$select = "<input type='checkbox' name='status' value='$nextstatus'> Advance status to $nextstatus?<br>";
					tablerow("Status:", $select . "<input type='checkbox' name='status' value='rejected'> Set this card as rejected?");}
				elseif($role > 3){ //lead devs and up can advance the status of any card
					if($row['status'] == "discuss"){
						$nextstatus = "playtest";}
					elseif($row['status'] == "playtest"){
						$nextstatus = "approved";}
					elseif($row['status'] == "approved"){
						$nextstatus = "official";}
					elseif($row['status'] == "rejected"){
						$nextstatus = "playtest";}
					elseif($row['status'] == "restricted"){
						$nextstatus = "playtest";}
					if($nextstatus <> null){$select = "<input type='checkbox' name='status' value='$nextstatus'> Advance status to $nextstatus?<br>";}
					if($row['status'] == 'official'){$demote = "<input type='checkbox' name='status' value='restricted'> Set this card as restricted?<br>";}
					tablerow("Status:", $select . $demote . "<input type='checkbox' name='status' value='rejected'> Set this card as rejected?");}
				else{ //everyone else just sees the status
					tablerow("Status:", $row["$fieldname"]);}}
			elseif($fieldname == 'image'){
				tablerow("Image Path:", "<input id='image' readonly='true' type=\"text\" name=\"$fieldname\" value=\"" . $row[$fieldname] . "\">");}
			else{}
		}
	}
	echo "</table><br>\n";

	//game-specific outputs (THESE CAN'T BE CHANGED IF THE CARD IS OFFICIAL)
	if($row['status'] <> "official"){
		echo "<table style='position:relative; left:51%'>";
		foreach( $main_tables as $table ) {
			if($table[0]<>'#'){//edit out comments
				$fieldname = substr($table, 0, strpos($table, " "));
				$fieldtype = trim(substr($table, strpos($table, " ")));
				// SKIP THE CARDSCAPE PREDEFINED VALUES
				if($fieldname == 'id'){}
				elseif($fieldname == 'revision'){}
				elseif($fieldname == 'date'){}
				elseif($fieldname == 'author'){}
				elseif($fieldname == 'status'){}
				elseif($fieldname == 'image'){}
				// NOW HANDLE EACH DATA TYPE
				elseif(!(strpos($fieldtype, "INT") === false)){
					tablerow($fieldname,"<input class='fieldtype_int' type='text' maxlength='10' name='$fieldname' value='" . $row["$fieldname"] . "'>");
				}
				elseif(!(strpos($fieldtype, "TEXT") === false)){
					tablerow($fieldname,textarea($fieldname, $row["$fieldname"], 4));
				}
				elseif(!(strpos($fieldtype, "VARCHAR") === false)){
					tablerow($fieldname,"<input type=\"text\" name=\"$fieldname\" value=\"" . $row["$fieldname"] . "\">");
				}
				elseif(!(strpos($fieldtype, "ENUM") === false)){
					$list = enum_array($fieldtype); //this function is in util/util.php
					$select = "<select name='$fieldname'>";
					foreach($list as $l){
						$l = str_replace("'","",$l);
						$isselected = '';
						if($row["$fieldname"] == $l){
							$isselected = "selected='selected'";}
						$select .= "<option value='$l' $isselected>" . $l . "</option>";
					}
					$select .= "</select>";
					tablerow($fieldname, $select);
				}
				else{
					tablerow($fieldname . " (Datatype NYI)", $row["$fieldname"] . " - " . $fieldtype);
				}
			}
		}
		echo "</table>";
	}
	echo "<input type='submit' value='Apply Changes' style='position: relative; left:50%'></form>";
}

/* UPDATES CARD INFO IN THE DATABASE */
function update_card($id){
	require('connect.php');
	require('generate_card_queries.php');

	//if not logged in, or logged in as only a user, don't allow
	if(($_SESSION['role']==null) || ($_SESSION['role']=='user')){
	  die('You do not have permission to edit cards. <a href="login.php">Login</a>');
	}
	
	//get the old revision number
	$query = "SELECT * FROM " . $db['prefix'] . "cards WHERE id=$id";
	$row = mysql_fetch_array(mysql_query($query));
	$_POST['revision'] = $row['revision'];
	
	//update the revision number
	if($_POST['status']=='official'){
		$_POST['revision'] = ceil($_POST['revision']);}
	elseif(($_POST['status'] == null) && ($row['status'] == 'official')){} //this line is a bug workaround
	else{
		$_POST['revision'] = $_POST['revision'] + '.01';}
	
	//don't allow change of name to nothing
	if(trim($_POST['cardname']) == ''){
		$_POST['cardname'] = $row['cardname'];}
	
	//add slashes to everything so it doesn't break the query
	foreach ($_POST as $key => $value) {
		$_POST[$key] = addslashes($_POST[$key]);}

	//fix return of null values
        foreach ($_POST as &$val){
		if($val == null){
			$val = " ";}
	}

	//UPDATE THE CARD
	$query = generate_card_update_query($id, $_POST);
	if(mysql_query($query)){
		//echo "Card updated successfully!";
		}
	else { die("There was an error. Card could not be updated.");}
	
	//COPY NEW VERSION TO HISTORY DB
	copy_card_to_history($id);
	
	//add a comment to the card saying that it was edited
	$user = $_SESSION['username'];
	$sql = "INSERT INTO " . $db['prefix'] . "comments ( card, user, text, date )
	VALUES ( '$id', 'cardscape', 'Card updated by $user." . date(" (H:i:s d M y)",time()) . "', CURRENT_TIMESTAMP )";
	mysql_query($sql);
	
	//add notification to activity table
	$msg = get_card_name($id) . " edited by " . $_SESSION['username'] . ".";
	post_activity($_SESSION['username'], "edit", $id, $msg);

	//redirect back to the card page
	header( "Location: index.php?act=show_card&id=$id" );
}

/* DELETES CARD WITH SELECTED ID */
//TODO: DELETE ALL COMMENTS AND HISTORY ASSOCIATED WITH THAT CARD TOO
function delete_card($id){
	require('connect.php');
	if($_SESSION['role']<>5){ //only admins can delete cards
		die("You do not have permission to delete cards.");}
	$query = "DELETE FROM " . $db['prefix'] . "cards WHERE id = '" . $_GET['id'] . "'";
	mysql_query($query) or die(mysql_error());
	//echo "Card deleted.";

	//add notification to activity table
	$msg = get_card_name($id) . "deleted by" . $_SESSION['username'] . ".";
	post_activity($_SESSION['username'], "delete", $id, $msg);

	header("Location: index.php?act=browse");
}

/* COPIES CARD BY ID TO HISTORY DB */
function copy_card_to_history($id){
	require("connect.php");
	require_once("generate_card_queries.php");
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "cards WHERE id='$id'");
	$row = mysql_fetch_array($result);
	$sql = generate_history_insert_query($row);
	mysql_query($sql) or die(mysql_error());
	
}




?>
