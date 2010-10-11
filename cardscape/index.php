<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<title></title>
		<link rel="stylesheet" href="layout.css" />
		<script type="text/javascript">/* <![CDATA[ *//* ]]> */</script>
	</head>
	<body>
		<h1>WTactics Card Database</h1>
<?php
error_reporting( E_ALL );
function errror_handler() {
	//TODO
}
//session_start();

require_once 'util.php';
require_once 'config.php';
if( !$conf[ 'accept' ] ) {
	die( 'Please follow the <a href="install.php">installation instructions</a></body></html>' );
}
require_once 'Card.php';

$db = $conf[ 'Database' ];
mysql_connect( $db[ 'host' ], $db[ 'user' ], $db[ 'pass' ] );
mysql_query( 'USE '.$db[ 'database' ] );

/* Callback functions */
$act = array();

$act[ 'new_card' ] = function () {

};

$act[ 'edit_card' ] = function () {

};

$act[ 'search_card' ] = function () {

};

$act[ 'login' ] = function () {
	msg( 'not implemented yet!' );
	echo 'klabatz!';

};

$act[ 'logoff' ] = function () {

};

/* Decide what to do */
foreach( $_GET as $key => $value ) {
	if( in_array( $key, array_keys( $act ) ) ) {
		$act[ $key ]();
	}
}

?>

</body></html>
