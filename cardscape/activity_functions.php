<?
/* SHOW ALL RECENT ACTIVITY */
function recent_activity(){
	require("connect.php");
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "activity ORDER BY date DESC") or die("Error querying database.");

	echo "<table>";
	while($row = mysql_fetch_array($result)){
		// if( (IT'S NEW SINCE LAST VISIT) and (USER HASN'T VISITED IT THIS SESSION) and (IT'S NOT BY THE USER) )
		if( ($row['date'] > $_SESSION['lastvisit']) and (!session_visited($row['card'])) and ($_SESSION['username'] <> $row['user']) ){
			$new = "<a href='index.php?act=show_card&id=" . $row['card'] . "'><img src='images/new_activity.png'></a>";}
		else{
			$new = "<img src='images/no_activity.png'>";}
		echo "<tr><td>$new</td><td>" . $row['date'] . "</td><td>" . $row['message'] . "</td></tr>";
	}
	echo "</table>";
}

/* AN EASY WAY TO ADD AN ACTIVITY NOTIFICATION TO THE DB */
function post_activity($user, $type, $card, $msg){
	require('connect.php');
	$msg = addslashes($msg);
	$query = "INSERT INTO " . $db['prefix'] . "activity (user, type, card, message) VALUES ('$user','$type','$card','$msg')";
	mysql_query($query) or die("Error! Query: $query");
}

/* True if the card has new notifications for the user */
function has_new_activity($id){
	require('connect.php');
	$query = "SELECT * FROM " . $db['prefix'] . "activity WHERE card=$id ORDER BY date DESC";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	// if( (IT'S NEW SINCE LAST VISIT) and (USER HASN'T VISITED IT THIS SESSION) and (IT'S NOT BY THE USER) )
	if( ($row['date'] > $_SESSION['lastvisit']) and (!session_visited($row['card'])) and ($_SESSION['username'] <> $row['user']) ){
		return true;}
	else{ return false;}
}

/* returns true if the card of ID has been visited since login */
function session_visited($id){
	require('connect.php');
	$index = strpos($_SESSION['visited'],"|$id|");
	if($index <> null){
		return true;}
	else{
		return false;}
}
?>
