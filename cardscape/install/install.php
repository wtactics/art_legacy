<?
/*//////////////////////////////
// get necessary dependencies //
//////////////////////////////*/
require('../connect.php');
require('../generate_card_queries.php');

if(!$conf['accept']){
	die('You must accept the license agreement to install cardscape.');}

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
lastvisit TIMESTAMP NULL , 
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
//echo $query['history'] . "<br><br>";

// activity database
$query['activity'] = "CREATE TABLE $prefix" . "activity (
id INT PRIMARY KEY AUTO_INCREMENT ,
user VARCHAR(16) ,
type ENUM('new','promote','edit','comment','delete') ,
card INT ,
message TEXT ,
date TIMESTAMP DEFAULT CURRENT_TIMESTAMP )";
//echo $query['activity'] . "<br><br>";

//run the queries
mysql_query($query['users']) or die("Error creating users database.<br>Query used: " . $query['users']);
echo "Database $prefix"."users created successfully.<br>";
mysql_query($query['comments']) or die("Error creating comments database.<br>Query used: " . $query['comments']);
echo "Database $prefix"."comments created successfully.<br>";
mysql_query($query['cards']) or die("Error creating cards database.<br>Query used: " . $query['cards']);
echo "Database $prefix"."cards created successfully.<br>";
mysql_query($query['history']) or die("Error creating history database.<br>Query used: " . $query['history']);
echo "Database $prefix"."history created successfully.<br>";
mysql_query($query['activity']) or die("Error creating activity database.<br>Query used: " . $query['activity']);
echo "Database $prefix"."activity created successfully.<br>";

/*////////////////////////
// create default login //
////////////////////////*/
// username: admin
// password: password
$query['default_login'] = "INSERT INTO $prefix" . "users ( name , password , role )
VALUES ( 'admin' , MD5( 'password' ) , 'Administrator' )";

if(!mysql_query($query['default_login'])){
	die("Error creating default administrator account.");}
else{
	echo "Added default administrator account to $prefix"."users successfully.<br><br>";}

echo "Congratulations! You have successfully installed cardscape!<br>
Pat yourself on the back, give a stranger a high-five, and praise yourself loudly in public.<br>
Once you've done that, click <a href='../index.php?act=login'>here</a> to log in as administrator and change the admin password.
<br><b>username: admin</b>
<br><b>password: password</b>";
?>
