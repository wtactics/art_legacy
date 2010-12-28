<?
require('connect.php');
if($_SESSION['role']<>'admin')
{die("You do not have permission to delete cards.");}
$query = "DELETE FROM " . $db['prefix'] . "cards WHERE id = '" . $_GET['id'] . "'";
mysql_query($query, $con);
//echo "Card deleted.";
header( "Location: browse.php" );
?>
