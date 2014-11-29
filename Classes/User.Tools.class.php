<?php
require_once "global.inc.php";
//
// UserTools (class)
//
// Utility functions for interacting with the User table.
//
// Adapted from https://github.com/revdrbrown/StoryTrees/blob/master/classes/UserTools.class.php
//

class UserTools {
	protected $db = null;
	//
	// __construct (constructor)
	//
	function __construct($database) {
		$this->db = $database;
	 }  
	//
	// login
	// 
	// If the username and hashed password match
	// against those found in the database, log
	// the user in by creating the session variables.
	//
	public function login($username, $password)
	{
		// Check the username and password.
		$db = $this->db;
		$hashedPassword = md5($password);
		$result = $db->select("*","users", 
            "username = '$username' AND password = '$hashedPassword'");
		
		// See if the credentials were found.
		if($db->numRows == 1) {
			$loggedUser = new User($result);
			$_SESSION["user"] = serialize($loggedUser);
			$_SESSION["id"] = $loggedUser->userid;
			$_SESSION["login_time"] = time();
			$_SESSION["logged_in"] = 1;
			return(true);
		}
		else
			return false;
	}
	//
	// logout
	//
	// Log the user out by destroying the session variables.
	//
	public function logout() {
		unset($_SESSION["user"]);
		unset($_SESSION["id"]);
		unset($_SESSION["login_time"]);
		unset($_SESSION["logged_in"]);
		session_destroy();
	}
	//
	// checkUsernameExists
	//
	//	Returns true if the given user name exists and false
	// otherwise.
	//
	public function userNameExists($username) {
		$db = $this->db;
		$result = $db->select("*","users","username = '$username'");
		return($db->numRows != 0);
	}
	//
	// getUsers
	//
	// Return an associative array of users keyed by their IDs. The contents
	// of each array entry is a two-element array containing the user's firstName 
	// and lastName.
	//
	public function getUsers($showAll = false) {
        $db = $this->db;
		if ($showAll)
			$rows = $db->select("*","users","lastName");
		else
			$rows = $db->select("*","users","lastName");
		
		// Process results
     	if ($db->numRows == 0)
			return(null);
		else {
			foreach($rows as $row) {
				$users[$row['userid']] = array($row['firstName'], $row['lastName']);
			}
		}
		return($users);
	}
	// 
	// get
	//
	//	Returns a User object for the given id
	// or null on error.
	//
	public function get($userid)
	{
		$db = $this->db;
 		$result = $db->select("*",'users',"userid = $userid");
		
		// Check for errors and return result
		if ($db->errorCode)
			return(null);
		else
			return new User($result, $db);
	}
}

?>
