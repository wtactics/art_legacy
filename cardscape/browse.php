<?php
require("connect.php");
$pagename = "Database | ";
include('header.php');
include('card_definition.php');
//get the page to look nice
echo "<!-- Main Wrapper -->";
echo '<div style="position:relative; min-width: 750px; width:90%; left:5%; right:5%;">';

//defines the function
function printlist($status)
{
require('connect.php');
$result = mysql_query("SELECT * FROM " . $db['prefix'] . "cards WHERE status='" . $status . "' ORDER BY cardname;");
echo "<h2 class='$status statusheader'>$status</h2>";
echo "<table style='width:100%'>
<tr>
<th width='33%'><span class='cardname'>Card Name</span> (Version)</th>
<th width='33%'>Submitted</th>
<th width='33%'>Author</th>
</tr>";

while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td><a class='cardname " . $row['status'] . "' href='card.php?id=" . $row['id'] . "'>" . $row['cardname'] . "</a> (" . $row['id'] . ':' . $row['revision'] . ")</td>";
  echo "<td>" . $row['date'] . "</td>";
  echo "<td>" . $row['author'] . "</td>";
  echo "</tr>";
  }
echo "</table>";
}

//these actually print the tables
$status = get_status_list(); //this function is in "util/cardscape_functions.php" which is called in the header
//echo "Find the bug.";
foreach($status as $s){
	printlist($s);}

//finish up the page
echo "</div>";
include('footer.php');
?>
