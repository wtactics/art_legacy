<? 
$pagename = "Login | ";
include('header.php'); ?>
<div style="margin:15px;">
<form method="POST" action="login_submit.php">
Username: <input type="text" name="username" size="20"><br>
Password: <input type="password" name="password" size="20">
<input type="submit" value="Submit" name="login">
</form>
</div>
<? include('footer.php'); ?>
