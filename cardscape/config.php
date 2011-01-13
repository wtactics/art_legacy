<?php //TODO: create sample config file for distribution
$conf = array(
	'accept' => true, //you accept that this software is licensed under the AGPL3 or later
/* SETS THE USER DATABASE OPTIONS */
	'Database' => array(
		'host' => 'localhost',
		'user' => 'root',
		'pass' => 'password',
		'database' => 'cardscape',
		'prefix' => 'wt_' ), //we recommend something like 'wt_' (short for WTactics)
/* SETS THE OPTIONS SPECIFIC TO HOW CARDSCAPE FUNCTIONS FOR YOUR PROJECT */
	'Game' => array(
		'name' => 'Localhost', //your project's name (preferrably short)
		'meta_des' => 'WTactics is a free/libre, open source collectible card game based on the Battle for Wesnoth universe. Join up and help us develop the first truly free CCG!', //sets the meta description of the pages
		'meta_key' => 'WTactics, free mtg, free, open source, card game, Magic the Gathering, Wesnoth', //sets the meta keywords of the pages
		'links' => array( //the links you want to show up on the project nav bar
			'1' => '<a href="http://chaosrealm.net/wtactics/">Blog</a>',
			'2' => '<a href="http://chaosrealm.net/wtactics/wiki">Wiki</a>',
			'3' => '<a href="http://chaosrealm.net/wtactics/forum">Forums</a>'),
		'comment_desc' => false, //print the comments newest first (in descending order)
		'registration' => true ) //allow new users to register without being manually added by an administrator (NYI)
	);
?>
