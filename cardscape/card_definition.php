<?php
/*********************************************
* THIS FILE CONTAINS THE FUNCTIONS THAT CALL *
* UPON THE DATA IN card_definition.txt       *
*    (it shouldn't need changed at all)      *
*********************************************/


/* SHOW THE NEW CARD FORM */
function print_new_card() {
	//include('util/util.php'); //included in header
	//define the variables
	$role = $_SESSION['role'];
	$filename = str_replace(' ', '', $row['cardname']) . ".png";
	$main_tables = file('card_definition.txt');

	//output the form;
	echo "<form action='card_insert.php' method='post'>";

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
			/* SKIP THE CARDSCAPE PREDEFINED VALUES */
			if($fieldname == 'id'){}
			elseif($fieldname == 'revision'){}
			elseif($fieldname == 'date'){}
			elseif($fieldname == 'author'){}
			elseif($fieldname == 'status'){}
			elseif($fieldname == 'image'){}
			/* NOW HANDLE EACH DATA TYPE */
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

/** SHOW THE CARD DATA */
function print_card_data($row) {
	//include('util/util.php'); //included in header.php
	include('connect.php');

	//define the variables
	$role = $_SESSION['role'];
	$filename = str_replace(' ', '', $row['cardname']) . ".png";
	$main_tables = file('card_definition.txt');


	echo "<!-- CARD INFORMATION AND IMAGE -->\n<div style='position:relative; min-height:450px; width:100%;'>\n";	
	//print the card image on the left
	echo "<div style='position:absolute; width:310px; padding: 10px;'>";
	if(file_exists("cards/" . $filename)) {
		echo "<img style='width:100%;' src='cards/" . $filename . "'></img><br>";}
	else{
		echo "No image in database.";}
	echo "</div>\n";
	/*
	//print the card data on the right//
	*/

	//the status header and edit link
	echo "<div style='position:relative; margin-top:0; margin-right:0; margin-left:330px;'>\n";
	echo "<div class='" . $row['status'] . " statusheader' style='position:relative; margin:5px; text-align:center;'>" . $row['status'];
	if($role > 2){//developers and up can edit cards.
		echo " <a style='color:black; font-size:16px;' href='card_edit.php?id=". $row['id'] ."'>[Edit Card]</a>";}
	if($role == 5){//only admins can delete a card. Devs can assign it as Rejected, however.
		echo " <a style='color:black; font-size:16px;' href='card_delete.php?id=". $row['id'] ."'>[Delete]</a>";}
	echo "</div>";

	//the top box with the cardscape data (CARDSCAPE DATA)
	echo "<div class='cardinfo'>";
	foreach( $main_tables as $table ) {
		if($table[0]<>'#'){//edit out comments
			$fieldname = substr($table, 0, strpos($table, " "));
			$fieldtype = trim(substr($table, strpos($table, " ")));
			if($fieldname == 'id'){
				echo "<span class='cardname'>" . $row['cardname'] . "</span> (Version " . $row['id'] . ":" . $row['revision'].")<br>";}
			elseif($fieldname == 'revision'){}
			elseif($fieldname == 'date'){
				echo "Date Submitted: " . $row["$fieldname"] . "<br>";}
			elseif($fieldname == 'author'){	
				//fetch user data
				$user_row = mysql_fetch_array(mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE name='" . $row["$fieldname"] . "'"));
				$uid = $user_row['id'];
				//print the author (linked to user page)
				echo "Author: <a href='user.php?id=$uid' class='username'><span style='font-weight:normal'>" . $row["$fieldname"] . "</span></a><br>";}
			elseif($fieldname == 'status'){}
			elseif($fieldname == 'image'){}
			else{}
		}
	}
	echo "</div><br>\n";
	//the bottom box with the game data (GAME DATA)
	echo "<div class='cardinfo'><table class='cardtable'>";
	foreach( $main_tables as $table ) {
		if($table[0]<>'#'){//edit out comments
			$fieldname = substr($table, 0, strpos($table, " "));
			$fieldtype = trim(substr($table, strpos($table, " ")));
			if($fieldname == 'id'){}
			elseif($fieldname == 'revision'){}
			elseif($fieldname == 'date'){}
			elseif($fieldname == 'author'){}
			elseif($fieldname == 'status'){}
			elseif($fieldname == 'image'){}
			elseif($fieldname == 'cardname'){}
			else{
				$fieldname = substr($table, 0, strpos($table, " "));
				$insert = str_replace("\n","<br>",$row["$fieldname"]);
				tablerow($fieldname . ":&nbsp&nbsp","<span class='$fieldname'>$insert</span>");}
		}
	}
	echo "</table></div>";
	echo "</div>\n</div>\n";
}

/* SHOWS THE EDIT FORM for card. Depending on the card status, not all fields will be changeable. */
function print_edit_card($row) {
	//include('util/util.php'); //included in header.php
	//define the variables
	$role = $_SESSION['role'];
	$filename = str_replace(' ', '', $row['cardname']) . ".png";
	$main_tables = file('card_definition.txt');

	//output the form;
	echo "<form action='card_edit_update.php?id=" . $row['id'] . "' method='post'>";

	//non-game-specific outputs
	echo "<table>";
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
					$nextstatus = "refine";
					$select = "<input type='checkbox' name='status' value='$nextstatus'> Advance status to $nextstatus?<br>";
					tablerow("Status:", $select . "<input type='checkbox' name='status' value='rejected'> Set this card as rejected?");}
				elseif($role > 3){ //lead devs and up can advance the status of any card
					if($row['status'] == "refine"){
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
				tablerow("Imagefile:", $row["$fieldname"]);}
			else{}
		}
	}
	echo "</table><br>\n";

	//game-specific outputs (THESE CAN'T BE CHANGED IF THE CARD IS OFFICIAL)
	if($row['status'] <> "official"){
		echo "<table>";
		foreach( $main_tables as $table ) {
			if($table[0]<>'#'){//edit out comments
				$fieldname = substr($table, 0, strpos($table, " "));
				$fieldtype = trim(substr($table, strpos($table, " ")));
				/* SKIP THE CARDSCAPE PREDEFINED VALUES */
				if($fieldname == 'id'){}
				elseif($fieldname == 'revision'){}
				elseif($fieldname == 'date'){}
				elseif($fieldname == 'author'){}
				elseif($fieldname == 'status'){}
				elseif($fieldname == 'image'){}
				/* NOW HANDLE EACH DATA TYPE */
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
	echo "<input type='submit' value='Apply Changes'></form>";
}

/* SHOWS THE COMMENTS OF THE SPECIFIED CARD */
function printcomments($id)
{
	include('connect.php');
	//fetch card data
	$card_row = mysql_fetch_array(mysql_query("SELECT * FROM " . $db['prefix'] . "cards WHERE id='$id'"));
	$cardname = $card_row['cardname'];
	//fetch comments data
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "comments WHERE card='" . $id . "' ORDER BY date");
	if($conf['Game']['comment_desc']){
		$result = mysql_query("SELECT * FROM " . $db['prefix'] . "comments WHERE card='" . $id . "' ORDER BY date DESC");}
	/* PRINT ALL THE CURRENT COMMENTS */
	while($row = mysql_fetch_array($result)){
		//fetch user data
		$user_row = mysql_fetch_array(mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE name='" . $row['user'] . "'"));
		$uid = $user_row['id'];
		//print the table
		echo "<table class='comment'><tr>";
		echo "<td width='100px' style='padding:5px;'>";
		if($row['user'] <> 'cardscape'){
			echo "<a class='username' href='user.php?id=$uid'>" . $row['user'] . "</a>";}
		else{
			echo "<span class='username'>cardscape</span>";}
		//print avatar, role and header if it's not from the cardscape daemon
		if($row['user'] <> 'cardscape'){
			$gravatar = gravatar_hash($row['user']);
			echo "<br>$gravatar"; //insert the Gravatar
			$header = "<b>Re: $cardname</b><br><span style='font-size:12px;'>by " . $row['user'] . " > " . $row['date'] . "</span>";
			echo "<br>" . $user_row['role'];}
		echo "</td>\n<td style='padding:5px'>$header<p>" . str_replace("\n","<br>",$row['text']) . "</p></td>";
		//add the delete button if allowed
		if($_SESSION['role'] > 1){  //mods and up can delete comments
			echo "<td width='10px'><a href='comment_delete.php?id=" . $row['id'] . "'><img src='images/delete.png' title='Delete this Post'></img></a></td>";}
		echo"</tr></table>";
		$header = null; //clear it so it doesn't show up on cardscape daemon's posts
	}
	/* "ADD A NEW COMMENT" CODE */
	if($_SESSION['role'] > 0){//anyone logged in and not banned
		echo "Post a new reply:
		<table style='width:100%; margin-bottom:5px;'>
		<th>" . $_SESSION['username'] . "</th>
		<tr><td><form action='comment_post.php?card=" . $id . "' method='post'>
		<textarea rows='4' style='width:100%' name='text'></textarea>
		<input type='submit' value='Post Reply'>
		</form></td></tr>
		</table>";
	}
}
?>
