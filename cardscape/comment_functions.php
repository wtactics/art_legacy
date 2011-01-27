<?
/* SHOWS THE COMMENTS OF THE SPECIFIED CARD */
function show_comments($id)
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
			echo "<a class='username' href='index.php?act=show_user&id=$uid'>" . $row['user'] . "</a>";}
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
			echo "<td width='10px'><a href='index.php?act=delete_comment&id=" . $row['id'] . "'><img src='images/delete.png' title='Delete this Post'></img></a></td>";}
		echo"</tr></table>";
		$header = null; //clear it so it doesn't show up on cardscape daemon's posts
	}
}

/* SHOWS THE FORM TO ADD A NEW COMMENT */
function show_new_comment_form($id)
{
	require_once("connect.php");
	if($_SESSION['role'] > 0){//anyone logged in and not banned
		echo "Post a new reply:
		<table style='width:100%; margin-bottom:5px;'>
		<th>" . $_SESSION['username'] . "</th>
		<tr><td><form action='index.php?act=insert_comment&id=" . $id . "' method='post'>
		<textarea rows='4' style='width:100%' name='text'></textarea>
		<input type='submit' value='Post Reply'>
		</form></td></tr>
		</table>";
	}
}

/* DELETES COMMENT BY COMMENT ID */
function delete_comment($id){
	require('connect.php');
	if($_SESSION['role'] < 2){
		die("You do not have permission to delete comments.");}
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "comments WHERE id='$id'");
	$row = mysql_fetch_array($result);
	$card = $row['card'];
	mysql_query("DELETE FROM " . $db['prefix'] . "comments WHERE id='$id'");
	header( "Location: index.php?act=show_card&id=$card" );
}

/* INSERTS COMMENT BY CARD ID */
function insert_comment($id){
	require('connect.php');
	
	//if not logged in, don't allow
	if($_SESSION['role'] < 1){
		die('You do not have permission to post a reply. <a href="index.php?act=login">Login</a>');}
	
	$card = addslashes($id);
	$user = addslashes($_SESSION['username']);
	$text = addslashes($_POST['text']);
	
	//rejects cards with no name
	if($text == ''){die('Cannot post a reply with no text.');}
	
	//build the QUERY
	$sql = "INSERT INTO " . $db['prefix'] . "comments ( card , user , text, date )
	VALUES ( '$card', '$user', '$text', CURRENT_TIMESTAMP )";
	
	//perform the QUERY
	mysql_query($sql) or die('Error: ' . mysql_error());

	//add notification to activity table
	$msg = $_SESSION['username'] . " commented on " . get_card_name($id) . ".";
	post_activity($_SESSION['username'], "comment", $id, $msg);

	//redirect
	header( "Location: index.php?act=show_card&id=" . $card );
}
?>
