<?
require('connect.php');
if($_SESSION['role']<>5)//admins only
{die("You do not have permission to delete users.");}
//$row = mysql_fetch_array(mysql_query("SELECT * FROM " . $db['prefix'] . "comments WHERE name='" . $_GET['name'] . "'"));
//$card = $row['card'];
$query = "DELETE FROM " . $db['prefix'] . "users WHERE name = '" . $_GET['name'] . "'";
mysql_query($query, $con);
header("Location: " . $_SERVER['HTTP_REFERER']);
?>
