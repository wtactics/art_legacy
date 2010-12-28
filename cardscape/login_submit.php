<?php
//check that the user is calling the page from the login form and not accessing it directly
//and redirect back to the login form if necessary
if (!isset($_POST['username']) || !isset($_POST['password'])) {
header( "Location: login.php" );
}
//check that the form fields are not empty, and redirect back to the login page if they are
elseif (empty($_POST['username']) || empty($_POST['password'])) {
header( "Location: login.php" );
}
else{

//add slashes to the username and md5() the password
$user = $_POST['username'];//addslashes($_POST['username']);
$pass = md5($_POST['password']);

require('connect.php');
$query = "SELECT * FROM " . $db['prefix'] . "users WHERE name='$user' AND password='$pass'";
$result = mysql_query($query, $con);

//check that at least one row was returned
$rowCheck = mysql_num_rows($result);
if($rowCheck > 0){
while($row = mysql_fetch_array($result)){
  //start the session and register variables
  session_start();
  session_register('name');
  $_SESSION['username'] = $row['name'];
if($row['role'] == 'Administrator'){
	$_SESSION['role'] = 5;}
elseif($row['role'] == 'Lead Developer'){
	$_SESSION['role'] = 4;}
elseif($row['role'] == 'Developer'){
	$_SESSION['role'] = 3;}
elseif($row['role'] == 'Moderator'){
	$_SESSION['role'] = 2;}
elseif($row['role'] == 'User'){
	$_SESSION['role'] = 1;}
elseif($row['role'] == 'Banned'){
	$_SESSION = null;
	die("You have been banned, and therefore may not login.");}
else{
	$_SESSION['role'] = 0;}

//successful login...
//echo 'You have been successfully logged in!'; //can't print this before redirect
//redirect
header( "Location: index.php" );
}

}
else {
//if nothing is returned by the query, unsuccessful login...
echo 'Incorrect login name or password. Please try again. <a href="login.php">Login</a>.';
}
}
?> 
