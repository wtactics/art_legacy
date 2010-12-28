<?
require('connect.php');
//don't let guests view users
if($_SESSION['role']==null){
	include('header.php');
	echo "<span class='error'>You must be logged in to view user profiles.</span>";
	include('footer.php');
	die();}

$id = $_GET['id'];
if($id == null){
	die("No user selected.");}
$result = mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE id=$id");
$row = mysql_fetch_array($result);

$pagename = $row['name'] . " | ";
include('header.php');
echo "<div style='margin:15px;'>";

display_user_profile($id);
display_admin_control($id);

echo "</div>";

include('footer.php');

//THE FUNCTIONS THAT MAKE IT WORK
function display_user_profile($id){
	require('connect.php');
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE id=$id");
	$row = mysql_fetch_array($result);
	echo "<table>\n";
	$gravatar = gravatar_hash($row['name']);
	echo "<tr><td style='padding:3px; padding-top:0px;'>$gravatar</td>";
	echo "<td><table class='userdata'>";
		echo "<tr><td class='username'>" . $row['name'] . "</td></tr>";
		echo "<tr><td class='role'>" . $row['role'] . "</td></tr>";
		echo "<tr><td>Joined: " . $row['date'] . "</td>";
		echo "<tr><td>e-mail: " . $row['email'] . "</td>";
	/* For now, this is all. In the future, other metrics would be nice, for example:
	//  * number of posts
	//  * number of suggested cards
	//  * personal information
	//    * age
	//    * location
	//  *etc.
	*/
	echo "</table></td></tr>";
	echo "</table>";
}
function display_admin_control($id){
	require('connect.php');
	//require('util/cardscape_functions.php'); //already included
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE id=$id");
	$row = mysql_fetch_array($result);
	//if permission, print the extra buttons
	if(($_SESSION['role'] > get_role_number($row['role'])) && ($_SESSION['role'] > 1)){ //if you are higher than the person you are viewing and are a leader
		echo get_role_name($_SESSION['role']) . " Options";
		echo "<form action='user_update.php?name=" . $row['name'] . "' method='post'><table>";
		//print the permissions dropdown box.
		echo "<tr><td>Set Role as:</td><td>";
		echo "<select name='role'>\n";
		$isselected = "";
		if($row['role']=='User'){$isselected = "selected='selected'";}else{$isselected = '';}
		if($_SESSION['role']>1){//moderator and up can set a person back to user
			echo "<option value='User' $isselected>User</option>\n";}
		if($row['role']=='Moderator'){$isselected = "selected='selected'";}else{$isselected = '';}
		if($_SESSION['role']>1){//moderator and up can appoint other mods
			echo "<option value='Moderator' $isselected>Moderator</option>\n";}
		if($row['role']=='Developer'){$isselected = "selected='selected'";}else{$isselected = '';}
		if($_SESSION['role']>2){//developer and up can appoint other devs
			echo "<option value='Developer' $isselected>Developer</option>\n";}
		if($row['role']=='Lead Developer'){$isselected = "selected='selected'";}else{$isselected = '';}
		if($_SESSION['role']>3){//lead devs and up can appoint lead devs
			echo "<option value='Lead Developer' $isselected>Lead Developer</option>\n";}
		if($row['role']=='Administrator'){$isselected = "selected='selected'";}else{$isselected = '';}
		if($_SESSION['role']==5){//only admins can appoint or revoke admins
			echo "<option value='Administrator' $isselected>Administrator</option>\n";}
		if($row['role']=='Banned'){$isselected = "selected='selected'";}else{$isselected = '';}
		if($_SESSION['role']>1){//moderator and up can ban people
			echo "<option value='Banned' $isselected>Banned</option>\n";}
		echo "</select>";
		echo "</td></tr>";
		//submit button
		echo "</table><input type='submit' value='Go'></form>";
	}


}

?>
