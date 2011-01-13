<?
/* SHOWS THE REVISION HISTORY TABLE OF CARD ID */
function show_history($id){
	//get data
	require("connect.php");
	$result = mysql_query("SELECT * FROM " . $db["prefix"] . "history WHERE id='$id' ORDER BY date DESC");
	$card = mysql_fetch_array(mysql_query("SELECT * FROM " . $db["prefix"] . "cards WHERE id='$id'"));
	//print header
	echo "Revision History for " . get_card_name($id) . ":";
	echo "<table>";
	//print rows
	while($row = mysql_fetch_array($result)){
		if($row['date'] == $card['date']){
			$arrow = "<img src='images/arrow.png' title='current version'></img>";}
		else{
			if($_SESSION['role'] > 3){//lead devs and up
				$arrow = "<a href='index.php?act=revert_card&id=" . $row['id'] . "&date=" . $row['date'] . "'><img src='images/revert.png' title='revert to this version'></img></a>";}
			else{ $arrow = null; }
		}
		$line = "<tr><td>$arrow</td>"; //shows which revision is currently being used.
		$line .= "<td>" . $row['date'] . "</td>";
		$line .= "<td>" . $row['cardname'] . " v." . $row["id"] . ":" . $row['revision'] . "</td>";
		$line .= "<td>" . $row['status'] . "</td>";
		$line .= "</tr>";
		echo $line;
	}
	echo "</table>";
}

/* SETS CARD WITH ID BACK TO PREVIOUS REVISION NUMBER */
function revert_card_to_revision($id, $date){
	require("connect.php");
	require("generate_card_queries.php");
	//security
	if($_SESSION['role'] < 4){ //only lead devs and up can rollback
		die("You don't have permission to revert a card to a previous version.");}
	//get the old version data
	$result = mysql_query("SELECT * FROM " . $db["prefix"] . "history WHERE id=$id AND date='$date'");
	$row = mysql_fetch_array($result);
	//addslashes to all data
        foreach ($row as &$val){
		$val=addslashes($val);}
	//fix return of null values
        foreach ($row as &$val){
		if($val == null){
			$val = " ";}
	}
	//PERFORM THE REVERT VERSION
	$query = generate_card_update_query($id, $row);
	mysql_query($query) or die("There was an error. Card could not be updated.");
	//redirect
	header("Location: index.php?act=show_card&id=$id");
	
}
?>
