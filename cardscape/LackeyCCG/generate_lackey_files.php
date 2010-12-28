<?
require('../connect.php');
echo $db['prefix'];

//open up file to write
$myFile = "testFile.txt";
$fh = fopen($myFile, 'w');

?>
