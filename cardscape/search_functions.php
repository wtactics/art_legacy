<?
/* SHOWS ALL CARDS */
function browse(){
	include("connect.php");
	
	//defines the function
	function printlist($status){
	require('connect.php');
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "cards WHERE status='" . $status . "' ORDER BY cardname;");
	echo "<div style='position:relative; min-height:70px'><div class='chunk' style='padding-top:15px; font-size:30px;'>$status</div><div class='$status card-status' title='$status'></div></div>";
	echo "<table style='width:100%'>
	<tr><th></th>
	<th width='33%'><span class='cardname'>Card Name</span> (Version)</th>
	<th width='33%'>Submitted</th>
	<th width='33%'>Author</th>
	</tr>";
	
	while($row = mysql_fetch_array($result)){
		echo "<tr>";
		if(has_new_activity($row['id'])){
			$new = "<a href='index.php?act=show_card&id=" . $row['id'] . "'><img src='images/new_activity.png' title='New activity!'></a>";}
		else{
			$new = "<img src='images/no_activity.png' title='No new activity'>";}
		echo "<td>$new</td>";
		echo "<td><a class='cardname' href='index.php?act=show_card&id=" . $row['id'] . "'>" . $row['cardname'] . "</a> (" . $row['id'] . ':' . $row['revision'] . ")</td>";
		echo "<td>" . $row['date'] . "</td>";

		//fetch user data
		$user_row = mysql_fetch_array(mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE name='" . $row['author'] . "'"));
		$uid = $user_row['id'];

		echo "<td><a class='username' style='font-weight:normal' href='index.php?act=show_user&id=$uid'>" . $row['author'] . "</a></td>";
		echo "</tr>";
		}
	echo "</table>";
	}

	//these actually print the tables
	$status = get_status_list(); //this function is in "util/cardscape_functions.php" which is called in the header

	foreach($status as $s){
		printlist($s);}
}

function progress(){
	include('progress.php');
}
?>
