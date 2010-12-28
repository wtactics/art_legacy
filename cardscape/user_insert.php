<?
if(preg_match("/register/",$_SERVER['HTTP_REFERER']) && (strtolower($_POST['captcha']) <> 'w')){
	die("You did not answer the security question correctly.");}
include('connect.php');
//won't add if no name or password
if (($_POST['name'] == null) || ($_POST['password'] == null)) {die("Could not process request: Incomplete information.");}
//won't add a username that already exists
$count = mysql_num_rows(mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE name='" . $_POST['name'] . "'"));
if($count <> 0){
	die('Username already exists.');}
//won't add 'cardscape' as a username
if($_POST['name'] == 'cardscape'){
	die("Can't add 'cardscape' because that would break the system terribly.");}
//if no role is specified, set to user
if($_POST['role']==null){
	$_POST['role'] = "User";}
//build and execute the query.
$query = "INSERT INTO " . $db['prefix'] . "users (name, password, role, email) VALUES ('" . $_POST['name'] . "', '" . md5($_POST['password']) . "', '" . $_POST['role'] . "', '" . $_POST['email'] ."')";
if(mysql_query($query)){
	if(preg_match("/register/",$_SERVER['HTTP_REFERER'])){//if they just registered, send them to login
		header("Location: login.php");
		echo "You have successfully registered. Now redirecting to the <a href='login.php'>login</a> page...";}
	else{
		//echo "User successfully added.";
		header("Location: " . $_SERVER['HTTP_REFERER']);}//otherwise, send them back to the previous page
}
else{
	die("Error inserting user into database.");}
?>
