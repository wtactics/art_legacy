<?php
require('connect.php');

//if not logged in, don't allow
if($_SESSION['role']==null){
  die('You do not have permission to post a reply. <a href="login.php">Login</a>');
}

$card = addslashes($_GET['card']);
$user = addslashes($_SESSION['username']);
$text = addslashes($_POST['text']);

//rejects cards with no name
if($text == ''){die('Cannot post a reply with no text.');}

//build the QUERY
$sql = "INSERT INTO " . $db['prefix'] . "comments ( card , user , text, date )
VALUES ( '$card', '$user', '$text', CURRENT_TIMESTAMP )";

//perform the QUERY
if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }
//echo $cardname . " comment added to database successfully.";
header( "Location: card.php?id=" . $card );

?> 
