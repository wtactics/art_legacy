<?
require('connect.php');
//can't register if disabled
if(!$conf['Game']['registration']){
	die("Registration is disabled for this cardscape installation. Ask an administrator for help.");}

//can't register if already logged in
if($_SESSION['role']<>null){
	die("You cannot register if you're already logged in.");}

require('header.php');
echo "<div style='margin:15px;'>";
//show the form
echo "<h2>Register:</h2>\n<form action='user_insert.php' method='post'><table>\n";
echo "<tr><td>Username: </td><td><input type='text' name='name'></td><tr>\n";
echo "<tr><td>Password: </td><td><input type='text' name='password'></td></tr>\n";
echo "<tr><td>e-Mail: </td><td><input type='text' name='email'></td></tr>\n";
echo "</table>";
echo "Anti-Spam Question:<br>What is the first letter in the word 'Wesnoth'?<br><input type='text' name='captcha'>\n";
echo "<input type='submit' value='Submit'></form>\n";
echo "</div>";
require('footer.php');
?>
