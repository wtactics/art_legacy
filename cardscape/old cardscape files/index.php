<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<title></title>
		<link rel="stylesheet" href="layout.css" />
		<script type="text/javascript">/* <![CDATA[ *//* ]]> */</script>
	</head>
	<body>
<?php
error_reporting( E_ALL );
function errror_handler() {
	//TODO
}
session_start();

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
	msg( 'Card search not implemented yet!' );

};

$act[ 'login' ] = function () {
	msg( 'not implemented yet!' );
	echo 'klabatz!';

};

$act[ 'logoff' ] = function () {

};

//output navigation and decide which link is active
$category = (isset( $_GET[ 'dev' ] ) )? 'dev': 'official';
$category = (isset( $_GET[ 'settings' ] ) )? 'settings': $category;
$categories = array( 'official' => 'Official Cards',
	'dev' => 'Card Development Area',
	'settings' => 'User control panel' );

echo '<div id="nav">';
while( list( $key, $value ) = each( $categories ) ) {
	echo '<a href="index.php?'.$key.'" class="'.
		( ($key == $category)? 'active': 'inactive' ).
		'">'.$value.'</a>';
}
echo '</div>';

/* Decide what to do */
foreach( $_GET as $key => $value ) {
	if( isset( $act[ $key ] ) ) {
		$act[ $key ]();
	}
	msg( 'Using GET parameter ['.$key.']' );
}

echo '<form name="search" method="get" action="index.php">
	<fieldset><legend>Card search</legend>';
	inputField( $category, '', 'hidden' );
	inputField( 'search_card', '', 'hidden' );
	inputField( 'srch_name', 'Name of card' ); #TODO implement complex search editor in JavaScript with OR AND and NOT combinations
	inputField( 'srch_rule', 'Part of ruletext' );
echo '</fieldset></form>';

?>

</body></html>
