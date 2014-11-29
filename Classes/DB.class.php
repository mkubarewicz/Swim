<?php
//
// DB (class)
//
// Database support object with the following methods:
//		select
//		update
//		insert
//		delete
//		
// Adapted from https://github.com/revdrbrown/StoryTrees/blob/master/classes/DB.class.php
// 

class DB {

	// Public Properties
	public $numRows;				// number of rows returned from last select
	public $errorCode;			// error code from last statement
	public $errorMsg;				// error message from last statement
	public $connection = null;
	
	
	// Protected Properties
	//Deleted here for security reasons.
	protected $db_name = ''; //Add in own database name.
	protected $db_user = ''; //Add in own username.
	protected $db_pass = ''; //Add in own password.
	protected $db_host = 'localhost';
	
	//
	// __construct (constructor)
	//
	//	Open a connection and connect to the database.
	//
	public function __construct() {
		$this->connection = mysqli_connect($this->db_host, $this->db_user, 
                                $this->db_pass, $this->db_name);
		$this->saveStatus();
	} 
	
	//
	// select
	//
	// Select rows from the database and save results in an
	// associative array with column names as the keys. If more than
	// one result is returned, the result is an array of associative
	// arrays. Returns null on errors.
	//
	public function select($fields, $table, $where = "", $order = "") {
      $sql = "SELECT $fields FROM $table";
		// Append WHERE clause if one was passed in.
		if ($where != "") 
		    $sql = $sql . " WHERE $where";
		// Append ORDER BY clause if one was passed in.
		if ($order != "")
		    $sql = $sql . " ORDER BY $order";
		// Issue the query
		$result = mysqli_query($this->connection,$sql);
		// Save and check status after query.
		$this->saveStatus();
		if ($this->errorCode)
			return(null);
		else
			return $this->processRowSet($result);
	}
	
	//
	// update
	// 
	// updates a row in the database.
	// $data is formated as an associative array with column names fo keys.
	//
	public function update($data, $table, $where) {
        $sql = "update $table set ";
        $i = 0;
		foreach ($data as $column => $value) {
            if ($i != 0)
                $sql = $sql . ",";
            $sql = "$sql $column = '$value'";
            $i++;
        }
        $sql = "$sql where $where";
        mysqli_query($this->connection,$sql);
		$this->numRows = mysqli_affected_rows($this->connection);
        $this->saveStatus();    
	}
	
	//
	// insert
	//
	// inserts a record into the database.
	// $data is formated as an associative array with column names fo keys.
	//
	public function insert($data, $table) {
		$columns = "";
		$values = "";
		// Format the $columns and $values for the insert statement.
		foreach ($data as $column => $value) {
			$columns .= ($columns == "") ? "" : ", ";
			$columns .= $column;
			$values .= ($values == "") ? "" : ", ";
			$values .= "'$value'";
		}
		// Issue the insert statement
		$sql = "insert into $table ($columns) values ($values)";
		mysqli_query($this->connection,$sql);
		// Save the status from the insert.
		$this->numRows = 1;
		$this->saveStatus();
		
		//return the ID of the user in the database.
		return mysqli_insert_ID($this->connection);
	}
	
	//
	// delete
	//
	// delete rows from the database.
	//
   public function delete($table, $where) {
		// Issue the statement.
		$sql = "delete from $table WHERE $where";
		mysqli_query($this->connection,$sql);
		// Save the status.
		$this->numRows = mysqli_affected_rows($this->connection);
		$this->saveStatus();
	}
	
	//
	// processRowSet (protected)
	//
	// Low-level routine for processing the result set from a
	// select statement. For a single row result, returns
	// an associative array of the data with column names
	// for the keys. For multiple row results, returns an
	// array of associative arrays.
	//
	protected function processRowSet($rowSet) {
		$this->numRows = mysqli_num_rows($rowSet);
			
		$resultArray = array();
		while($row = mysqli_fetch_assoc($rowSet))
			array_push($resultArray, $row);	
		// Format the return set.
		if($this->numRows == 1) 
			return $resultArray[0];
		else
			return $resultArray;
	}	
	
	//
	// saveStatus
	//
	// Save error code and message on from the last database call.
	//
	protected function saveStatus() {
		$db = $this->connection;
		$this->errorCode = $db->errno;
		$this->errorMsg = $db->error; 
	}
}
?>
