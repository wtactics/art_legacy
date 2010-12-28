<?php
require("connect.php");
//blocks people without permission
if($_SESSION['role'] < 3){
  die('You do not have permission to edit existing cards. Login as a developer or an admin if you want to edit cards.');
}

$id = $_GET["id"];

//this handles no card in url
if($id==null){die("No card specified.");}
//this handles an invalid card name in url
if(!mysql_fetch_array(mysql_query("SELECT * FROM " . $db['prefix'] . "cards WHERE id='" . $id . "';"))){die("No such card exists.");}

$result = mysql_query("SELECT * FROM " . $db['prefix'] . "cards WHERE id='" . $id . "';");
$row = mysql_fetch_array($result);

$pagename = 'Editing ' . $row['cardname'] . ' | ';
include('header.php');
include('card_definition.php');

echo '<div style="margin:15px;">';
print_edit_card($row);
echo "</div>";

include('footer.php') ?>
