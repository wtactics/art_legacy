<?php
require('config.php');
//connect to the database
$db = $conf[ 'Database' ];
$con = mysql_connect($db['host'],$db['user'],$db['pass']);
if (!$con)
	{
	  	die('Could not connect: ' . mysql_error());
	}
mysql_select_db($db['database'], $con);
//start the session
session_start();

?>
