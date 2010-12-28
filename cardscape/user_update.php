<?php
require("connect.php");
include("util/cardscape_functions.php");

$name = $_GET["name"];
$password = $_POST["password"];
$role = $_POST["role"];
$email = $_POST["email"];

//this handles no card in url
if($name==null){die("No user specified.");}
//this handles an invalid username in url
if(!mysql_fetch_array(mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE name='" . $name . "';"))){die("No such user exists.");}

//get old user info
$result = mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE name='" . $name . "';");
$row = mysql_fetch_array($result);

//blocks people without permission
if($_SESSION['role'] < get_role_number($row['role'])){ //TODO: this might be malfunctioning
  die('You do not have permission to edit users.');
}

//only update role if changed
if($role == null){
$role = $row['role'];
}
//only update email if changed
if($email == null){
$email = $row['email'];
}

//null password changes become old password, new passwords are encrypted
if($password == ''){
$password = $row['password'];}
else{
$password = md5($password);}

//build query
$sql = "UPDATE " . $db['database'] . "." . $db['prefix'] . "users SET role = '$role', password = '$password', email = '$email'  
WHERE " . $db['prefix'] . "users.name = '$name';";

//perform the QUERY
if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }
//echo "User " . $name . " edited successfully.";

header("Location: " . $_SERVER['HTTP_REFERER']);

?>
