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
		$db = new DB();
		$this->ID = $db->insert($data, 'times');
		return true;
	}
	
	
	//Update time in the database where time ids match.
	public function update($data, $tID) {
		$db = new DB();
		foreach ($data as $row) {
			$this->ID = $db->update($data, "times", 'id = ' . $tID);
		}
		return true;
	}
	
	
	//Delete time from the database.
	public function delete($tID) {
		$db = new DB();
        $db->delete("times", 'id = '. $tID);

		return true;
	}	
		
		
	//Get the time ID record from the database.
	public function get($tID) {
		$db = new DB();
		$result = $db->select("*", "times", "id = $tID");
		
		return new TimeClass($result);
	}
   
   
	//Show a list group of all the times for a user using links.
    	public function showTimesList($whereTo = "#", $userid) {
		$db = new DB();
		
		//Select all the fields for a user and order by Distance, Stroke, then Time.
		 $rows = $db->select("*", "times", "userid = $userid", "Distance, Stroke, Times"); 
            
		//If they have no records, display error message.
		 if ($db->numRows == 0) {
        	 	echo '<h4 style="text-align: center;">There are no swim times yet.</h4><br>';
		 }
		//If they have only one record, print with list group.
		elseif ($db->numRows == 1) {
			echo '<a href="#" class="list-group-item active"> Name &emsp;&emsp; Gender &emsp;&emsp;' .
				'Age Range &emsp;&emsp; Distance &emsp;&emsp; Stroke &emsp;&emsp; Time </a>';
            		echo '<a href="' . $whereTo . '?tID=' . $rows["ID"] . '" class="list-group-item">' .
				$rows["Name"] . "&emsp;&emsp;&emsp;" . $rows["Gender"] . "&emsp;&emsp;&emsp;" . $rows["Age_Range"] . 
				"&emsp;&emsp;&emsp;" . $rows["Distance"] . "&emsp;&emsp;&emsp;" . $rows["Stroke"] . 
				"&emsp;&emsp;&emsp;" . $rows["Times"] . "</a>";
        	}
		//If they have more than one record, print list group using an array.
		  else {
			echo '<a href="#" class="list-group-item active"> Name &emsp;&emsp; Gender &emsp;&emsp;' .
				'Age Range &emsp;&emsp; Distance &emsp;&emsp; Stroke &emsp;&emsp; Time </a>';
            		foreach($rows as $row) {
        		 echo '<a href="' . $whereTo . '?tID=' . $row["ID"] . '" class="list-group-item">' .
				$row["Name"] . "&emsp;&emsp;&emsp;" . $row["Gender"] . "&emsp;&emsp;&emsp;" . $row["Age_Range"] . 
				"&emsp;&emsp;&emsp;" . $row["Distance"] . "&emsp;&emsp;&emsp;" . $row["Stroke"] . 
				"&emsp;&emsp;&emsp;" . $row["Times"] . "</a>";
            }
        }
    }	
}
?>
