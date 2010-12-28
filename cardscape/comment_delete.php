<?
require('connect.php');
if($_SESSION['role'] < 2)
{die("You do not have permission to delete posts.");}
$row = mysql_fetch_array(mysql_query("SELECT * FROM " . $db['prefix'] . "comments WHERE id='" . $_GET['id'] . "'"));
$card = $row['card'];
$query = "DELETE FROM " . $db['prefix'] . "comments WHERE id = '" . $_GET['id'] . "'";
mysql_query($query, $con);
header( "Location: card.php?id=$card" );
?>
