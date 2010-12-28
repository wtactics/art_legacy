<?php
require('connect.php');
require('generate_card_queries.php');

//if not logged in, don't allow
if($_SESSION['role']==null){
  die('You do not have permission to create new cards. <a href="login.php">Login</a>');
}

//add slashes to everything
foreach ($_POST as $key => $value) {
	$_POST[$key] = addslashes($_POST[$key]);
}

//rejects cards with no name
if($_POST['cardname'] == ''){die('Cannot add a card with no name.');}

//workaround for a no-author submitted bug
if($_POST['author'] == null){
	$_POST['author'] = $_SESSION['username'];
}

//rejects cards with a name that already exists in the database
$result = mysql_query("SELECT * FROM cs_cards WHERE cardname='$cardname';");
$row = mysql_fetch_array($result);
if($row['cardname'] == $_POST['cardname']){die('A card with the suggested name already exists.');};

//build the QUERY
$sql = generate_card_insert_query($_POST);

//perform the QUERY
if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }
//echo $cardname . " added to database successfully.";
$row = mysql_fetch_array(mysql_query("SELECT * FROM " . $db['prefix'] . "cards WHERE cardname='" . $_POST['cardname'] . "'"));
header("Location: card.php?id=" . $row['id']);

?> 
