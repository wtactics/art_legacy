<?
//YEAH, I'M SICK OF THIS CARDSCAPE THING TOO. UNINSTALL IT.
//What? You're just going to reinstall it afterward? Okay, sweet. But you're gonna lose all you data.

require("../connect.php");

if($_SESSION['role'] < 5){ //not an admin
	die("Well, duh! You think a non-admin is allowed to delete everything? <a href='../index.php?act=login'>Login</a>");}
mysql_query("DROP TABLE " . $db['prefix'] . "cards");
mysql_query("DROP TABLE " . $db['prefix'] . "history");
mysql_query("DROP TABLE " . $db['prefix'] . "comments");
mysql_query("DROP TABLE " . $db['prefix'] . "users");
mysql_query("DROP TABLE " . $db['prefix'] . "activity");

echo "Uninstall successful.";
// (I was obviously in a sardonic mood when I wrote the code in this file. Please ignore my bad attitude.)
?>
