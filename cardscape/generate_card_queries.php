<?

//CREATE TABLE FUNCTION
function generate_card_create_table_query(){
	include('../connect.php'); // the ../ is required because this function is called from ./install/install.php
	$prefix = $db['prefix'];
	$main_tables = file('../card_definition.txt'); // the ../ is required because this function is called from ./install/install.php
	$query = "";
	$query .= "CREATE TABLE " . $prefix . "cards (";
	foreach( $main_tables as $table ) {
		if($table[0]<>'#'){//edit out comments
			$query .= $table . " , ";
		}
	}
	$query = substr($query, 0, strlen($query) - 2); //remove the ', ' from the last value
	$query .= ")";
	return $query;
	//echo $query . "<br><br>";
}
//CREATE TABLE FUNCTION
function generate_history_create_table_query(){
	include('../connect.php'); // the ../ is required because this function is called from ./install/install.php
	$prefix = $db['prefix'];
	$main_tables = file('../card_definition.txt'); // the ../ is required because this function is called from ./install/install.php
	$query = "";
	$query .= "CREATE TABLE " . $prefix . "history (";
	foreach( $main_tables as $table ) {
		if($table[0]<>'#'){//edit out comments
			$table = str_replace("PRIMARY KEY AUTO_INCREMENT", "", $table);
			$query .= "$table , ";
		}
	}
	$query = substr($query, 0, strlen($query) - 2); //remove the ', ' from the last value
	$query .= ")";
	return $query;
	//echo $query . "<br><br>";
}
//UPDATE FUNCTION
function generate_card_update_query($id, $new){
	include('connect.php');
	$database = $db['database'];
	$prefix = $db['prefix'];
	$main_tables = file('card_definition.txt');

	$query = "UPDATE $database.$prefix" . "cards SET " ;//begin the QUERY
	foreach( $main_tables as $table ) {
		if($table[0]<>'#'){//edit out comments
			$fieldname = substr($table, 0, strpos($table, " "));
			if($new["$fieldname"]<>null){ //only add updated values to the QUERY.
				$table = "$fieldname = '" . $new["$fieldname"] . "', ";
				$query .= $table;
			}
		}
	}
	$query = substr($query, 0, strlen($query) - 2); //remove the ', ' from the last value
	$query .= " WHERE " . $prefix . "cards.id ='$id'"; //finish the query
	return $query;
	//echo $query . "<br><br>";
}
//INSERT CARD INTO DATABASE QUERY
function generate_card_insert_query($new){
	include('connect.php');
	$prefix = $db['prefix'];
	$main_tables = file('card_definition.txt');

	$query = "INSERT INTO " . $prefix . "cards ( " ;//begin the QUERY
	foreach( $main_tables as $table ) {
		if($table[0]<>'#'){//edit out comments
			$fieldname = substr($table, 0, strpos($table, " "));
			if($new["$fieldname"]<>null){ //only add defined values to the QUERY.
				$table = "$fieldname, ";
				$query .= $table;
			}
		}
	}
	$query = substr($query, 0, strlen($query) - 2); //remove the ', ' from the last value
	$query .= " ) VALUES ( ";

	foreach( $main_tables as $table ) {
		if($table[0]<>'#'){//edit out comments
			$fieldname = substr($table, 0, strpos($table, " "));
			if($new["$fieldname"]<>null){ //only add defined values to the QUERY.
				$table = "'" . $new["$fieldname"] . "', ";
				$query .= $table;
			}
		}
	}
	$query = substr($query, 0, strlen($query) - 2); //remove the ', ' from the last value
	$query .= " )"; //finish the query
	return $query;
	//echo $query . "<br><br>";
}

/* GENERATES HISTORY INSERT QUERY FROM A $row ARGUMENT */
function generate_history_insert_query($new){
	include('connect.php');
	$prefix = $db['prefix'];
	$main_tables = file('card_definition.txt');

	$query = "INSERT INTO " . $prefix . "history ( " ;//begin the QUERY
	foreach( $main_tables as $table ) {
		if($table[0]<>'#'){//edit out comments
			$fieldname = substr($table, 0, strpos($table, " "));
			if($new["$fieldname"]<>null){ //only add defined values to the QUERY.
				$table = "$fieldname, ";
				$query .= $table;
			}
		}
	}
	$query = substr($query, 0, strlen($query) - 2); //remove the ', ' from the last value
	$query .= " ) VALUES ( ";

	foreach( $main_tables as $table ) {
		if($table[0]<>'#'){//edit out comments
			$fieldname = substr($table, 0, strpos($table, " "));
			if($new["$fieldname"]<>null){ //only add defined values to the QUERY.
				$table = "'" . addslashes($new["$fieldname"]) . "', ";
				$query .= $table;
			}
		}
	}
	$query = substr($query, 0, strlen($query) - 2); //remove the ', ' from the last value
	$query .= " )"; //finish the query
	return $query;
	//echo $query . "<br><br>";
}
?>
