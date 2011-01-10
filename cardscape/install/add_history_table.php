<?
/*////////////////////////////////////
// ONLY RUN IF IT IS AN OLD VERSION //
////////////////////////////////////*/

require('../connect.php');
require('../generate_card_queries.php');

/*///////////////////////////////
// create the history database //
///////////////////////////////*/

// card history database
$query['history'] = generate_history_create_table_query();
//echo $query['cards'] . "<br><br>";

//run the queries
mysql_query($query['history']) or die("Error creating history database.<br>Query used: " . $query['history']);
echo "Database $prefix"."history created successfully.<br>";

//congrats
echo "Congratulations! You have successfully updated cardscape!<br>
Pat yourself on the back, give a stranger a high-five, and praise yourself loudly in public.<br>
Your functionality should start immediately.";
?>
