<?php
	// logout.php
	// Log a user out of the system utilizing the logout
	// method in UserTools.
	//
	// Adapted from https://github.com/revdrbrown/Commentz/blob/master/logout.php
	//
	
	require_once 'global.inc.php';

	$userTools = new UserTools();
	$userTools->logout();

	header("Location: index.html");

?>
