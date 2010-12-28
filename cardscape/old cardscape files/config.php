<?php //TODO create sample config file for distribution
$conf = array(
	'accept' => true, //you accept that this software is licensed under the AGPL3 or later
	'Database' => array(
		'host' => 'localhost',
		'user' => 'wtactics',
		'pass' => 'EmptyMoon',
		'database' => 'wtactics',
		'prefix' => 'wt_' ),
	'auth' => array(
		'salt' => 'somtehing more random will be used soon',
		'newdraft' => 1,
		'ratecard' => 4,
		'commentcard' => 1,
		'registration' => true )
	);
?>
