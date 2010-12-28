<head>
<link rel="stylesheet"type="text/css"href="template.css">
<link rel="stylesheet"type="text/css"href="dropdown.css">
</head>
<script type="text/javascript" src="dropdown.js"></script>
<body>
<?
include('util/util.php');
$array = array(
	1 => '<a href="browse.php">browse</a>', 
	2 => '<a href="browse.php">php</a>', 
	3 => '<a href="browse.php">shazbot</a>', 
	4 => '<a href="browse.php">foobar</a>');
dropdown_links('cardscape', $array);
?>
</body>
