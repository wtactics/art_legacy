<?php
require('connect.php');
if($_SESSION['role']==null){
  die('You do not have permission to create new cards. <a href="login.php">Login</a>');
}

$pagename = "New Card | ";
include('header.php');
include('card_definition.php');

print_new_card();

include('footer.php');
?>
