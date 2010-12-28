<?php
require('connect.php');
require('generate_card_queries.php');

//if not logged in, or logged in as only a user, don't allow
if(($_SESSION['role']==null) || ($_SESSION['role']=='user')){
  die('You do not have permission to edit cards. <a href="login.php">Login</a>');
}

//get the old revision number
$id = $_GET['id'];
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

//UPDATE THE CARD
$query = generate_card_update_query($id, $_POST);
if(mysql_query($query)){
	//echo "Card updated successfully!";
	}
else { die("There was an error. Card could not be updated.");}

//TODO: move the old imagefile to the new cardname (NYI)

//add a comment to the card saying that it was edited
$user = $_SESSION['username'];
$sql = "INSERT INTO " . $db['prefix'] . "comments ( card, user, text, date )
VALUES ( '$id', 'cardscape', 'Card updated by $user." . date(" (H:i:s d M y)",time()) . "', CURRENT_TIMESTAMP )";
mysql_query($sql,$con);

//redirect back to the card page
header( "Location: card.php?id=$id" );
?> 
