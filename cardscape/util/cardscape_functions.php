<?
/* RETURNS THE LIST OF NON-GENERAL (GAME SPECIFIC) DATA FIELDS */
function get_game_data_fields(){
	$file = file('card_definition.txt');
	foreach($file as $line){
		if($line[0]<>'#'){//edit out comments
			$fieldname = substr($line, 0, strpos($line, " "));
			switch($fieldname){
				case 'id': break;
				case 'revision': break;
				case 'date': break;
				case 'author': break;
				case 'status': break;
				case 'image': break;
				case 'cardname': break;
				default:
					$fields[$fieldname] = $fieldname;
			}
		}
	}
	return $fields;
}

/* RETURNS NAME CORRESPONDING TO ROLE VALUE */
function get_role_name($int){
	if($int == -1){
		return "Banned";}
	elseif($int == 0){
		return "Guest";}
	elseif($int == 1){
		return "User";}
	elseif($int == 2){
		return "Moderator";}
	elseif($int == 3){
		return "Developer";}
	elseif($int == 4){
		return "Lead Developer";}
	elseif($int == 5){
		return "Administrator";}
}

/* RETURNS VALUE CORRESPONDING TO ROLE NAME */
function get_role_number($role){
	if($role == "Banned"){
		return -1;}
	elseif($role == "Guest"){
		return 0;}
	elseif($role == "User"){
		return 1;}
	elseif($role == "Moderator"){
		return 2;}
	elseif($role == "Developer"){
		return 3;}
	elseif($role == "Lead Developer"){
		return 4;}
	elseif($role == "Administrator"){
		return 5;}
}

/* RETURNS THE ARRAY OF STATUSES */
function get_status_list(){
	//include('util.php'); //called in the header
	$main_tables = file('card_definition.txt');
	$list = null;
	foreach( $main_tables as $table ) {
		if($table[0]<>'#'){//edit out comments
			$fieldname = substr($table, 0, strpos($table, " "));
			$fieldtype = trim(substr($table, strpos($table, " ")));
			if($fieldname == 'status'){
				$list = enum_array($fieldtype); //this function is in util/util.php
			}
		}
	}
	return $list;
}

/* RETURNS THE GRAVATAR IMAGE OBJECT */
function gravatar_hash($username){
	include('connect.php');
	$query = "SELECT * FROM " . $db['prefix'] . "users WHERE name='$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$gravatar = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($row['email']))) . "?s=100&d=mm&r=PG";
	return "<img src='$gravatar' class='avatar' title='$username'></img>";
}

/* RETURNS THE ID FROM THE CARDNAME */
function get_id_from_cardname($cardname){
	require('connect.php');
	$query = "SELECT * FROM " . $db['prefix'] . "cards WHERE cardname='$cardname'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	return $row["id"];
}

/* RETURNS THE CARDNAME FROM THE ID */
function get_card_name($id){
	require('connect.php');
	$query = "SELECT * FROM " . $db['prefix'] . "cards WHERE id='$id'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	return $row["cardname"];
}
?>
