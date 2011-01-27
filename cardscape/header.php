<?
require('config.php');
require_once('util/util.php');
require_once('util/cardscape_functions.php');
$settings = $conf['Game'];
?>
<html>
<head>
<? 
echo "<title>" . $pagename . "Cardscape | " . $settings['name'] . "</title>\n";
echo "<meta name='description' content='" . $settings['meta_des'] . "'>\n";
echo "<meta name='keywords' content='" . $settings['meta_key'] . "'>\n";
?>
<link rel="stylesheet"type="text/css"href="template.css">
<link rel="stylesheet"type="text/css"href="dropdown.css">
</head>
<script type="text/javascript" src="dropdown.js"></script>
<script type="text/javascript" src="upload.js"></script>
<body>
<!--CONTAINER FOR EVERYTHING-->
<div style="position:absolute; min-width:900px; top:0; left:0; right:0">

<!--HEADER-->
<div style="position:relative; background-image:url('images/header-center.jpg'); height:125; width:100%;">
	<div style="position:relative; height:100px;">
		<div class="chunk" style="position:absolute; font-size:30px; top:50%; margin-top:-15px; margin-left:15px; color:white;"><?echo $settings['name'];?> - Cardscape</div>
	</div>
<!--CARDSCAPE NAVIGATION-->
<!--Container for the Magic Link Dropdown Boxes-->
<div style="position:relative; top:0; height:25; width:100%">
<!--Game Navigation-->
<? dropdown_links( $conf['Game']['name'] . " Links", $settings['links'], "150px"); ?>
<!--Cardscape Navigation-->
<div style="position:absolute; left:150;">
<?
$nav = array(	1=>"<a href='index.php?act=browse'>Browse Cards</a>",
		2=>"<a href='index.php?act=progress'>Progress Report</a>",
		3=>"<a href='index.php?act=recent_activity'>Recent Activity</a>");
if($_SESSION['role']>0){
	$nav[4] = "<a href='index.php?act=new_card'>Suggest a New Card</a>";}
dropdown_links("Cardscape Links", $nav,"150px;");
?>	
</div>
	<div style="position:absolute; right:0; top:0;">

<?
// THE USER CONTROL LINKS //

if($_SESSION['username']==null){
	$user = "Guest (login)";
	$nav = array(	1=>"<a href='index.php?act=login'>Log In</a>",
			2=>"<a href='index.php?act=register'>Register</a>");
}
else{
	$user = $_SESSION['username'] . " (" . get_role_name($_SESSION['role']) . ")";
	$nav = array(	1=>"<a href='index.php?act=usercp'>User CP</a>",
			2=>"<a href='index.php?act=logout'>Log Out</a>");
}

dropdown_links($user, $nav,"200px;");
?>

	</div>
</div>

</div>

<!--main wrapper-->
<div style="position:relative; min-width: 750px; width:80%; left:10%; right:10%;">
