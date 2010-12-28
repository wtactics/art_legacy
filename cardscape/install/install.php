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

//run the queries
if(!mysql_query($query['users'])){
	die("Error creating users database.<br>Query used: " . $query['users']);}
else{
	echo "Database $prefix"."users created successfully.<br>";}
if(!mysql_query($query['comments'])){
	die("Error creating comments database.<br>Query used: " . $query['comments']);}
else{
	echo "Database $prefix"."comments created successfully.<br>";}
if(!mysql_query($query['cards'])){
	die("Error creating cards database.<br>Query used: " . $query['cards']);}
else{
	echo "Database $prefix"."cards created successfully.<br>";}

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

echo "Congratulations! You have successfully installed cardscape!
Pat yourself on the back, give a stranger a high-five, and praise yourself loudly in public.
Once you've done that, click <a href='../login.php'>here</a> to log in as administrator and change the admin password.
<br><b>username: admin</b>
<br><b>password: password</b>";
?>
