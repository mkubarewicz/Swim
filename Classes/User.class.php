<?php
require_once 'DB.class.php';
//
// User
// DB support class for the user table.
//
// Adapted from https://github.com/revdrbrown/StoryTrees/blob/master/classes/User.class.php
//

class User {

	// Properties
	public $userid;
	public $username;
	public $hashedPassword;
	public $email;
	public $joinDate;
	public $firstName;
	public $lastName;
	

	//Constructor is called whenever a new object is created.
	//Takes an associative array with the DB row as an argument.
	function __construct($data) {
		$this->userid = (isset($data['userid'])) ? $data['userid'] : "";
		$this->username = stripslashes((isset($data['username'])) ? $data['username'] : "");
		$this->hashedPassword = (isset($data['password'])) ? $data['password'] : "";
		$this->email = stripslashes((isset($data['email'])) ? $data['email'] : "");
		$this->joinDate = (isset($data['join_date'])) ? $data['join_date'] : "";
		$this->firstName = stripslashes((isset($data['firstName'])) ? $data['firstName'] : "");
		$this->lastName = stripslashes((isset($data['lastName'])) ? $data['lastName'] : "");
	}

	public function save($isNewUser = false) {
		//create a new database object.
		$db = new DB();
		$username = mysql_real_escape_string($this->username);
		$email = mysql_real_escape_string($this->email);
		$firstName = mysql_real_escape_string($this->firstName);
		$lastName = mysql_real_escape_string($this->lastName);
	
		//if the user is already registered and we're
		//just updating their info.
		if(!$isNewUser) {
			//set the data array
			$data = array(
				"username" => "'$username'",
				"password" => "'$this->hashedPassword'",
				"email" => "'$email'",
				"firstName" => "'$firstName'",
				"lastName" => "'$lastName'"
			);
			
			//update the row in the database
			$db->update($data, 'users', 'userid = '. $this->userid);
		}else {
		//if the user is being registered for the first time.
			$data = array(
				"username" => "'$username'",
				"password" => "'$this->hashedPassword'",
				"email" => "'$email'",
				"firstName" => "'$firstName'",
				"lastName" => "'$lastName'",
				"join_date" => "'".date("Y-m-d H:i:s",time())."'"
			);
			
			$this->userid = $db->insert($data, 'users');
			$this->joinDate = time();
		}

		return true;
	}
	
}

?>
