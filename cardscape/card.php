<?php
require("connect.php");
require("card_definition.php");

$id = $_GET["id"];

//this handles no card in url
if($id==null){die("No card specified.");}
//this handles an invalid card name in url
if(!mysql_fetch_array(mysql_query("SELECT * FROM " . $db['prefix'] . "cards WHERE id='" . $id . "';"))){die("No such card exists.");}

$result = mysql_query("SELECT * FROM " . $db['prefix'] . "cards WHERE id='" . $id . "';");
$row = mysql_fetch_array($result);

//print the Cardscape Header
$pagename = $row['cardname'] . " | ";
include('header.php');
?>



<!-- Main Wrapper -->
<div style="position:relative; min-width: 750px; width:80%; left:10%; right:10%;">
<? print_card_data($row); ?>

<!--====== comments CODE STARTS HERE ======-->

	<div style="position:relative; width:100%;">
		<h2>User Comments:</h2>
		<?
		printcomments($id);
		?>
	</div>
</div>
<?
include('footer.php');
?>
