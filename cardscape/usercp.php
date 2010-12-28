<?

//==the functions that make it work==//

//this one runs if user is an admin... shows all users' information
function display_usercp_admin(){
	include('connect.php');
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "users ORDER BY id");
	echo "<table>\n";
	echo "<th>Username</th><th>Change Password To</th><th>Role</th><th>email</th><th>Submit Changes</th><th>Delete</th>\n";
	while($row = mysql_fetch_array($result)){
		echo "<tr><td>" . $row['name'] . "</td><form action='user_update.php?name=" . $row['name'] . "' method='post'><td><input type='text' name='password'></td>\n<td>";
		if ($row['name']<>'admin')
		{
			echo "<select name='role'>\n";
			
			$isselected = "";
			if($row['role']=='User'){$isselected = "selected='selected'";}else{$isselected = '';}
			echo "<option value='User' $isselected>User</option>\n";
			if($row['role']=='Moderator'){$isselected = "selected='selected'";}else{$isselected = '';}
			echo "<option value='Moderator' $isselected>Moderator</option>\n";
			if($row['role']=='Developer'){$isselected = "selected='selected'";}else{$isselected = '';}
			echo "<option value='Developer' $isselected>Developer</option>\n";
			if($row['role']=='Lead Developer'){$isselected = "selected='selected'";}else{$isselected = '';}
			echo "<option value='Lead Developer' $isselected>Lead Developer</option>\n";
			if($row['role']=='Administrator'){$isselected = "selected='selected'";}else{$isselected = '';}
			echo "<option value='Administrator' $isselected>Administrator</option>\n";
			if($row['role']=='Banned'){$isselected = "selected='selected'";}else{$isselected = '';}
			echo "<option value='Banned' $isselected>Banned</option>\n";
	
			echo "</select></td>";
		}
		else{
			echo $row['role'] . "</td>";}
		echo "<td><input type='text' name='email' value='" . $row['email'] . "'></td>";
		echo "<td><input type='submit' value='Go'></td></form>";
		if ($row['name']<>'admin') {echo "<td><a href='user_delete.php?name=" . $row['name'] . "'><img src='images/delete.png' title='Delete this Post'></img></a></td>";}
		echo "</tr>\n";
	}
		echo "</table><br>\n";
	//THE ADD NEW USER LINES
	echo "Add new user:\n<form action='user_insert.php' method='post'><table>\n";
	echo "<th>Username</th><th>Password</th><th>Role</th><th>email</th><th>Submit</th>\n";
	echo "<tr><td><input type='text' name='name'></td><td><input type='text' name='password'></td><td>";
	echo "<select name='role'>\n<option value='User'>User</option>\n";
	echo "<option value='Moderator'>Moderator</option>\n";
	echo "<option value='Developer'>Developer</option>\n";
	echo "<option value='Lead Developer'>Lead Developer</option>\n";
	echo "<option value='Administrator'>Administrator</option>\n</select>\n</td>";
	echo "<td><input type='text' name='email'></td>\n";
	echo "<td><input type='submit' value='Submit'></td><tr>\n";
	echo "</table></form>\n";
}

//this one runs if not an admin
function display_usercp(){
	require('connect.php');
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE name='" . $_SESSION['username'] . "';");
	$row = mysql_fetch_array($result);
	echo "<table>\n";
	echo "<tr><td>Username</td><td>" . $row['name'] . "</td></tr>";
	echo "<tr><td>Password</td><td>" . $row['password'] . "</td></tr>";
	echo "<tr><td>Change Password</td><form action='user_update.php?name=" . $row['name'] . "' method='post'><td><input type='text' name='password'><input type='submit' value='Update'></td></form></tr>";
	echo "<tr><td>Email Address</td><form action='user_update.php?name=" . $row['name'] . "' method='post'><td><input type='text' name='email' value='" . $row['email'] . "'><input type='submit' value='Update'></td></form></tr>";
	echo "<tr><td>Role</td><td>" . $row['role'] . "</td></tr>";
	echo "<tr><td>Joined</td><td>" . $row['date'] . "</td></tr>";
	echo "</table>";
}

//the actual page
require('connect.php');
$pagename = "User CP | ";
include('header.php');
echo "<div style='margin:15px;'>";

if($_SESSION['role'] < 1)
{
  die('You are not logged in.');
}
elseif($_SESSION['role']== 5)//admins only
{
  display_usercp_admin();
}
else
{
  display_usercp();
}

echo "</div>";
include('footer.php');
?>
