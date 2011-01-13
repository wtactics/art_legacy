<?
/*//////////////////////////////
// get necessary dependencies //
//////////////////////////////*/
require('../connect.php');
require('../generate_card_queries.php');

mysql_query("DROP TABLE " . $db['prefix'] . "cards");
mysql_query("DROP TABLE " . $db['prefix'] . "history");
mysql_query("DROP TABLE " . $db['prefix'] . "comments");

$prefix = $db['prefix'];

/*////////////////////////////////
// create the default databases //
////////////////////////////////*/

// users database
$query['users'] = "CREATE TABLE $prefix" . "users (
id INT PRIMARY KEY AUTO_INCREMENT , 
name VARCHAR(16) , 
password CHAR(32) , 
email CHAR(64) , 
role ENUM('User','Moderator','Developer','Lead Developer','Administrator','Banned') DEFAULT 'user' , 
date TIMESTAMP DEFAULT CURRENT_TIMESTAMP )";
//echo $query['users'] . "<br><br>";

// comments database
$query['comments'] = "CREATE TABLE $prefix" . "comments (
id INT PRIMARY KEY AUTO_INCREMENT , 
user VARCHAR(16) , 
card INT , 
text TEXT , 
date TIMESTAMP DEFAULT CURRENT_TIMESTAMP )";
//echo $query['comments'] . "<br><br>";

// cards database
$query['cards'] = generate_card_create_table_query();
//echo $query['cards'] . "<br><br>";

// card history database
$query['history'] = generate_history_create_table_query();
//echo $query['cards'] . "<br><br>";

//run the queries
mysql_query($query['comments']) or die("Error creating comments database.<br>Query used: " . $query['comments']);
echo "Database $prefix"."comments created successfully.<br>";
mysql_query($query['cards']) or die("Error creating cards database.<br>Query used: " . $query['cards']);
echo "Database $prefix"."cards created successfully.<br>";
mysql_query($query['history']) or die("Error creating history database.<br>Query used: " . $query['history']);
echo "Database $prefix"."history created successfully.<br>";

//congrats
echo "Congratulations! You have successfully upgraded cardscape!<br>
Pat yourself on the back, give a stranger a high-five, and praise yourself loudly in public.<br>
Once you've done that, click <a href='../index.php?act=login'>here</a> to log in as administrator and change the admin password.
<br><b>username: admin</b>
<br><b>password: password</b>";
?>
