<?php

//global.inc.php
//Include in every class to connect to the database
//and create a new user object
//
//Adapated from https://github.com/revdrbrown/StoryTrees/blob/master/includes/global.inc.php
//

//start the session
session_name("Login");
session_start();

require_once 'User.class.php';
require_once 'UserTools.class.php';
require_once 'Times.class.php';
require_once 'DB.class.php';
require_once 'utils.inc.php';

// Open the database connection
$db = new DB();

// Create UserTools Object
$userTools = new UserTools($db);

// If someone is logged in, set $userID
// and $user globals.
if(isset($_SESSION['logged_in'])) {
	$userid = $_SESSION['id']; 
	$user = $userTools->get($userid);
}
else {
	$userID = "";
	$user = null;
}
?>
