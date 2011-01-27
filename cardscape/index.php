<?
//get the dependencies
require("card_functions.php");			//this holds all actions/functions pertaining to cards
require("comment_functions.php");		//this holds all actions/functions pertaining to comments
require("user_functions.php");			//this holds all actions/functions pertaining to users
require("history_functions.php");		//this holds all actions/functions pertaining to history
require("image_functions.php");			//this holds all actions/functions pertaining to image management
require("search_functions.php");		//this holds all actions/functions pertaining to browsing the database
require("activity_functions.php");		//this holds all actions/functions pertaining to activity notifications
require("util/cardscape_functions.php");	//this holds various miscellaneous functions pertaining to cardscape
require("connect.php");				//this connects to the database and the user session

//build the action array
$action = array(
		/* CARD FUNCTIONS */
		'show_card' => function() {
			$pagename = get_card_name($_GET["id"]) . " | ";
			echo $db['prefix'];
			include('header.php');
			show_card($_GET["id"]);
			show_new_comment_form($_GET["id"]);
			include("footer.php");},
		'new_card' => function(){
			$pagename = "New Card | ";
			include('header.php');
			show_new_card_form();
			include('footer.php');},
		'insert_card' => function(){
			insert_card();},
		'edit_card' => function(){
			$pagename = "Editing " . get_card_name($_GET["id"]) . " | ";
			include('header.php');
			show_edit_card_form($_GET["id"]);
			include('footer.php');},
		'update_card' => function(){
			update_card($_GET["id"]);},
		'delete_card' => function(){
			delete_card($_GET["id"]);},
		/* HISTORY FUNCTIONS */
		'revert_card' => function(){
			revert_card_to_revision($_GET["id"], $_GET["date"]);},
		/* IMAGE FUNCTIONS */
		'upload_image' => function(){
			upload_image($_GET['id']);},
		'save_upload' => function(){
			save_image($_GET['id'], $_GET['filename']);},
		/* USER FUNCTIONS */
		'login' => function(){
			$pagename = "Login | ";
			include('header.php');
			show_login();
			include('footer.php');},
		'login_submit' => function(){
			login_submit();},
		'logout' => function(){
			logout();},
		'register' => function(){
			$pagename = "Register | ";
			include('header.php');
			show_register_form();
			include('footer.php');},
		'insert_user' => function(){
			insert_user();},
		'usercp' => function(){
			$pagename = "User CP | ";
			include('header.php');
			show_usercp();
			include('footer.php');},
		'update_user' => function(){
			update_user($_GET['name']);},
		'delete_user' => function(){
			delete_user($_GET['name']);},
		'show_user' => function(){
			$pagename = "USERNAME | "; // TODO: get username (I'm just being lazy)
			include('header.php');
			show_user($_GET['id']);
			include('footer.php');},
		/* COMMENT FUNCTIONS */
		'delete_comment' => function(){
			delete_comment($_GET['id']);},
		'insert_comment' => function(){
			insert_comment($_GET['id']);},
		/* OTHER FUNCTIONS */
		'browse' => function(){
			$pagename = "Browse | ";
			include('header.php');
			browse();
			include('footer.php');},
		'recent_activity' => function(){
			$pagename = "Recent Activity | ";
			include('header.php');
			recent_activity();
			include('footer.php');},
		'progress' => function(){
			$pagename = "Progress Report | ";
			include('header.php');
			progress();
			include('footer.php');},
		'default' => function(){
			$pagename = "Welcome | ";
			include('header.php');
			echo "<br>Welcome to Cardscape Alpha. Please click around and make some comments so we can test and revise this tool.";
			include('footer.php');}
		);

// get the URL instruction
$act = $_GET["act"];
if($act == null){
	$act = 'default';}

//perform the action
$action[$act](); // This is where the magic happens ;)
?>
