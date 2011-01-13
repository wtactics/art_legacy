<?
function show_login(){
	echo '<div style="margin:15px;">';
	echo '<form method="POST" action="index.php?act=login_submit">';
	echo 'Username: <input type="text" name="username" size="20"><br>';
	echo 'Password: <input type="password" name="password" size="20">';
	echo '<input type="submit" value="Submit" name="login">';
	echo '</form>';
	echo '</div>';
}
function login_submit(){
	//don't allow direct access
	if (!isset($_POST['username']) || !isset($_POST['password'])) {
		header("Location: index.php?act=login");}
	//don't allow empty fields
	elseif (empty($_POST['username']) || empty($_POST['password'])) {
		header("Location: index.php?act=login");}
	else{
		//add slashes to the username and md5() the password
		$user = $_POST['username'];
		//addslashes($_POST['username']); //not sure why I commented this out... TODO: evaluate why.
		$pass = md5($_POST['password']);
	
		require('connect.php');
		$query = "SELECT * FROM " . $db['prefix'] . "users WHERE name='$user' AND password='$pass'";
		$result = mysql_query($query, $con);

		//check that at least one row was returned
		$rowCheck = mysql_num_rows($result);
		if($rowCheck > 0){
			while($row = mysql_fetch_array($result)){
				//start the session and register variables
				session_start();
				session_register('name');
				$_SESSION['username'] = $row['name'];
				if($row['role'] == 'Administrator'){
					$_SESSION['role'] = 5;}
				elseif($row['role'] == 'Lead Developer'){
					$_SESSION['role'] = 4;}
				elseif($row['role'] == 'Developer'){
					$_SESSION['role'] = 3;}
				elseif($row['role'] == 'Moderator'){
					$_SESSION['role'] = 2;}
				elseif($row['role'] == 'User'){
					$_SESSION['role'] = 1;}
				elseif($row['role'] == 'Banned'){
					$_SESSION = null;
					die("You have been banned, and therefore may not login.");}
				else{
					$_SESSION['role'] = 0;}
	
				//successful login... redirect
				header("Location: index.php");}
	}
	else {
		//if nothing is returned by the query, unsuccessful login...
		echo 'Incorrect login name or password. Please try again. <a href="index.php?act=login">Login</a>.';}
	}
}

/* DESTROYS THE USER SESSION */
function logout(){
	session_unset();
	session_destroy();
	header("Location: " . $_SERVER['HTTP_REFERER']);
}

/**/
function show_register_form() {
	require('connect.php');
	//can't register if disabled
	if(!$conf['Game']['registration']){
		die("Registration is disabled for this cardscape installation. Ask an administrator for help.");}

	//can't register if already logged in
	if($_SESSION['role']<>null){
		die("You cannot register if you're already logged in.");}

	//show the form
	echo "<div style='margin:15px;'>";
	echo "<h2>Register:</h2>\n<form action='index.php?act=insert_user' method='post'><table>\n";
	echo "<tr><td>Username: </td><td><input type='text' name='name'></td><tr>\n";
	echo "<tr><td>Password: </td><td><input type='text' name='password'></td></tr>\n";
	echo "<tr><td>e-Mail: </td><td><input type='text' name='email'></td></tr>\n";
	echo "</table>";
	echo "Anti-Spam Question:<br>What is the first letter in the word 'Wesnoth'?<br><input type='text' name='captcha'>\n";
	echo "<input type='submit' value='Submit'></form>\n";
	echo "</div>";
}

function insert_user(){
	if(preg_match("/register/",$_SERVER['HTTP_REFERER']) && (strtolower($_POST['captcha']) <> 'w')){
		die("You did not answer the security question correctly.");}
	include('connect.php');
	//won't add if no name or password
	if (($_POST['name'] == null) || ($_POST['password'] == null)) {die("Could not process request: Incomplete information.");}
	//won't add a username that already exists
	$count = mysql_num_rows(mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE name='" . $_POST['name'] . "'"));
	if($count <> 0){
		die('Username already exists.');}
	//won't add 'cardscape' as a username
	if($_POST['name'] == 'cardscape'){
		die("Can't add 'cardscape' because that would break the system terribly.");}
	//if no role is specified, set to user
	if($_POST['role']==null){
		$_POST['role'] = "User";}
	//build and execute the query.
	$query = "INSERT INTO " . $db['prefix'] . "users (name, password, role, email) VALUES ('" . $_POST['name'] . "', '" . md5($_POST['password']) . "', '" . $_POST['role'] . "', '" . $_POST['email'] ."')";
	if(mysql_query($query)){
		if(preg_match("/register/",$_SERVER['HTTP_REFERER'])){//if they just registered, send them to login
			header("Location: index.php?act=login");
			echo "You have successfully registered. Now redirecting to the <a href='index.php?act=login'>login</a> page...";}
		else{
			header("Location: " . $_SERVER['HTTP_REFERER']);}//otherwise, send them back to the previous page
	}
	else{
		die("Error inserting user into database.");}
}

/* DISPLAY THE ADMIN USER CP */
function display_usercp_admin(){
	include('connect.php');
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "users ORDER BY id");
	echo "<table>\n";
	echo "<th>Username</th><th>Change Password To</th><th>Role</th><th>email</th><th>Submit Changes</th><th>Delete</th>\n";
	while($row = mysql_fetch_array($result)){
		echo "<tr><td>" . $row['name'] . "</td><form action='index.php?act=update_user&name=" . $row['name'] . "' method='post'><td><input type='text' name='password'></td>\n<td>";
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
		if ($row['name']<>'admin') {echo "<td><a href='index.php?act=delete_user&name=" . $row['name'] . "'><img src='images/delete.png' title='Delete this User'></img></a></td>";}
		echo "</tr>\n";
	}
		echo "</table><br>\n";
	//THE ADD NEW USER LINES
	echo "Add new user:\n<form action='index.php?act=insert_user' method='post'><table>\n";
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

/* DISPLAY THE NORMAL USER CP */
function display_usercp(){
	require('connect.php');
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE name='" . $_SESSION['username'] . "';");
	$row = mysql_fetch_array($result);
	echo "<table>\n";
	echo "<tr><td>Username</td><td>" . $row['name'] . "</td></tr>";
	echo "<tr><td>Change Password</td><form action='index.php?act=update_user&name=" . $row['name'] . "' method='post'><td><input type='text' name='password'><input type='submit' value='Update'></td></form></tr>";
	echo "<tr><td>Email Address</td><form action='index.php?act=update_user&name=" . $row['name'] . "' method='post'><td><input type='text' name='email' value='" . $row['email'] . "'><input type='submit' value='Update'></td></form></tr>";
	echo "<tr><td>Role</td><td>" . $row['role'] . "</td></tr>";
	echo "<tr><td>Joined</td><td>" . $row['date'] . "</td></tr>";
	echo "</table>";
}

/* CHOOSES THE APPROPRIATE USER CP TO DISPLAY */
function show_usercp(){
	require('connect.php');
	echo "<div style='margin:15px;'>";
	
	if($_SESSION['role'] < 1){
		die('You are not logged in.');}
	elseif($_SESSION['role']== 5){ //admins only
		display_usercp_admin();}
	else{
		display_usercp();}
	echo "</div>";
}

/* UPDATES THE USER BY NAME */
function update_user($name){
	require("connect.php");
	//include("util/cardscape_functions.php");
	
	$password = $_POST["password"];
	$newrole = $_POST["role"];
	$email = $_POST["email"];
	
	//this handles no card in url
	if($name==null){die("No user specified.");}
	//this handles an invalid username in url
	if(!mysql_fetch_array(mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE name='" . $name . "';"))){die("No such user exists.");}
	
	//get old user info
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE name='" . $name . "';");
	$row = mysql_fetch_array($result);
	
	//blocks people without permission
	if($_SESSION['role'] < get_role_number($row['role'])){ //TODO: this might be malfunctioning
	  die('You do not have permission to edit users.');
	}
	
	//only update role if changed
	if($newrole == null){
	$newrole = $row['role'];
	}
	//only update email if changed
	if($email == null){
	$email = $row['email'];
	}
	
	//null password changes become old password, new passwords are encrypted
	if($password == ''){
	$password = $row['password'];}
	else{
	$password = md5($password);}
	
	//build query
	$sql = "UPDATE " . $db['database'] . "." . $db['prefix'] . "users SET role = '$newrole', password = '$password', email = '$email'  
	WHERE " . $db['prefix'] . "users.name = '$name';";
	
	//perform the QUERY
	mysql_query($sql) or die('Error: ' . mysql_error());

	//redirect
	header("Location: " . $_SERVER['HTTP_REFERER']);
}

/* DELETES USER BY NAME */
function delete_user($name){
	require('connect.php');
	if($_SESSION['role']<>5)//admins only
		{die("You do not have permission to delete users.");}
	//$row = mysql_fetch_array(mysql_query("SELECT * FROM " . $db['prefix'] . "comments WHERE name='" . $_GET['name'] . "'"));
	//$card = $row['card'];
	$query = "DELETE FROM " . $db['prefix'] . "users WHERE name = '$name'";
	mysql_query($query);
	header("Location: " . $_SERVER['HTTP_REFERER']);
}

/* SHOWS THE USER INFORMATION BY ID */
function show_user($id){
	require('connect.php');
	//don't let guests view users
	if($_SESSION['role']==null){
		die("<span class='error'>You must be logged in to view user profiles.</span>");}
	
	if($id == null){
		die("No user selected.");}
	$result = mysql_query("SELECT * FROM " . $db['prefix'] . "users WHERE id=$id");
	$row = mysql_fetch_array($result);
	
	echo "<div style='margin:15px;'>";
	
	display_user_profile($id);
	display_admin_control($id);
	
	echo "</div>";
}
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
		echo "<tr><td>e-mail: " . str_replace("@", " (a) ", str_replace(".", " (dot) ", $row['email'])) . "</td>"; //the str_replace are to thwart spambots
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
		echo "<form action='index.php?act=update_user&name=" . $row['name'] . "' method='post'><table>";
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
