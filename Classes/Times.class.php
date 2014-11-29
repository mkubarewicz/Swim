<?php
require_once 'DB.class.php';
//
// Times Class
// DB support class for the Times table.
//
//Adapted from https://github.com/revdrbrown/StoryTrees/blob/master/classes/User.class.php
//and https://github.com/revdrbrown/StoryTrees/blob/master/classes/UserTools.class.php
//

class TimeClass {

	// Times Table Class Properties
	public $ID;
	public $Name;
	public $Gender;
	public $Age_Range;
	public $Distance;
	public $Stroke;
	public $Times;
	public $userid;
	public $db;

	
	//Constructor is called whenever a new object is created.
	//Takes an associative array with the DB row as an argument.
	function __construct($data, $db = null) {
		$this->ID = (isset($data['ID'])) ? $data['ID'] : "";
		$this->Name = stripslashes((isset($data['Name'])) ? $data['Name'] : "");
		$this->Gender = stripslashes((isset($data['Gender'])) ? $data['Gender'] : "");
		$this->Age_Range = stripslashes((isset($data['Age_Range'])) ? $data['Age_Range'] : "");
		$this->Distance = stripslashes((isset($data['Distance'])) ? $data['Distance'] : "");
		$this->Stroke = stripslashes((isset($data['Stroke'])) ? $data['Stroke'] : "");
		$this->Times = stripslashes((isset($data['Times'])) ? $data['Times'] : "");
		$this->userid = (isset($data['userid'])) ? $data['userid'] : "";
		
	}
		
	//Insert new time into the database.
	public function insert($data) {
		$db = $this->db;
		$this->ID = $db->insert($data, 'times');
		return true;
	}
	
	//Update time in the database where time ids match.
	public function update(TimeClass $data, $tID) {
		$db = $this->db;
		foreach ($tID as $row) {
			$this->ID = $db->update($data, "times", 'id = ' . $row);
		}
		return true;
	}
	
	//Delete time from the database.
	public function delete($tID) {
		$db = $this->db;
        $db->delete("times", 'id = '. $tID);

		return true;
	}	
	}

?>
