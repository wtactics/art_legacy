<?php
/*//////////////////////////////////////
// THIS PAGE IS SPECIFIC TO WTACTICS. //
// FUNCTION MAY BE IMPLEMENTED FOR    //
// ALL GAMES IN THE FUTURE.           //
//////////////////////////////////////*/
require("connect.php");

function get_type_list(){
	//include('util/util.php'); //because get_status_list() already included it...
	$main_tables = file('card_definition.txt');
	$list = null;
	foreach( $main_tables as $table ) {
		if($table[0]<>'#'){//edit out comments
			$fieldname = substr($table, 0, strpos($table, " "));
			$fieldtype = trim(substr($table, strpos($table, " ")));
			if($fieldname == 'type'){
				$list = enum_array($fieldtype); //this function is in util/util.php
			}
		}
	}
	return $list;
}

function print_progress_table(){
	/* SET GOALS. THESE SHOULD BE MODIFIED AS NEEDED */
	$goal = array(	'unit'=>24,
			'event'=>6,
			'spell'=>6,
			'enchantment'=>6,
			'equipment'=>6,
			'artifact'=>2);

	include('connect.php');
	include('card_definition.php');

	/* LOAD THE DATA */
	$status_list = get_status_list();
	$type_list = get_type_list();

	$data = null;
	foreach($type_list as $type){
		$status_data = null;
		foreach($status_list as $status){
			$status_data["$status"] = mysql_num_rows(mysql_query("SELECT * FROM " . $db['prefix'] . "cards WHERE status='$status' AND type='$type';"));
		}
		$data["$type"] = $status_data;
	}

	/* PRINT OUT THE TABLE */
	echo "<table>";
	echo "<th>type</th>";
	foreach($status_list as $status){
		echo "<th>$status</th>";
	}
	echo "<th>total in pool</th><th>goal</th><th>% completed</th>";
	$col_total = null;
	foreach($type_list as $type){
		$status_data = null;
		echo "<tr><td>$type</td>";
		$row_total = 0;
		foreach($status_list as $status){
			$d = $data["$type"]["$status"];
			$col_total["$status"] = $col_total["$status"] + $d; //add to the column total
			$row_total = $row_total + $d; //add to the row total
			echo "<td>$d</td>";
		}
		echo "<td>$row_total</td><td>" . $goal["$type"] . "</td><td>" . round($row_total / $goal["$type"] * 100) . "%</td></tr>";
	}
	echo "<tr><td>totals</td>";

	foreach($col_total as $t){
		echo "<td>$t</td>";
	}
	
	echo "<td>" . array_sum($col_total) . "</td><td>" . array_sum($goal) . "</td><td>" . round(array_sum($col_total) / array_sum($goal) * 100) . "%</td></tr>";
	echo "</table>";
}

//main body

echo "<h1>Progress for Gaian Core Release:</h1>";
print_progress_table();

?>
