<!DOCTYPE html>
<html lang="en">

<!-- **********The Compete Page**********
Here users can add, edit, and delete their swimming times
to the MySQL database.

***As of 11/29/2014 still has bugs and only works 
if user has one or less times submitted already.
Know how to get around that using get, but is less
secure. If I have time, I hope to find out how to use
post for this. Otherwise, I will add that in
later after the approaching due date.--> 

<?php
	//Use to access the Times table.
	require_once 'global.inc.php';

	//Create new database object.
	$db = new DB();
	
	//Hide errors.
	//error_reporting(0);
?>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="Maggie" content="">

    <title>Swim</title>

	<!-- Custom bootstrap css -->
	<style type = "text/css">
		@import url(swim.css)	
	</style> 

    <!-- Custom stylesheet for navbar -->
    <link href="justified-nav.css" rel="stylesheet">

  </head>

  <?php 
	//-------------------------------------------Log In--------------------------------------------
	//Creates new user object.
	$userTools = new UserTools($db);
	
	//Check to see if the user is logged in, otherwise take them to the login page.
	if(!isset($_SESSION['logged_in'])) {
		header("Location: login.php");
	}
	
	//------------------------------------------Swim Times-----------------------------------------
	
	//To store the the time id for current user.
	$tUserID = "";
	$tName = "";
	$tGender = "";
	$tAge = "";
	$tDistance = "";
	$tStroke = "";
	$tTime = "";
	$userid = "";
	
	if(isset($_SESSION['id'])) {
		$userid = $_SESSION['id'];
	}
	
	//Select all the times for the logged in user only---------------------------
	$timesUser = $db->select("Name, Gender, Age_Range, Distance, Stroke, Times", "times", 
		"userid = $userid", "Distance, Stroke, Times");

	//Select same query as above, but only the unique ID for the time in the database
	$timesUserID = $db->select("ID", "times", "userid = $userid", "Distance, Stroke, Times"); 
	
	if ($db->numRows == 0) {
		$timesUser = null;
	}

	if(!(isset($_POST['submit']))) {
				
		if ($db->numRows == 1) { //If user has only one time submitted already.
					
			$tUserID = $timesUserID["ID"]; //Since one result, set to that time id.
			$tName = $timesUser["Name"];
			$tGender = $timesUser["Gender"];
			$tAge = $timesUser["Age_Range"];
			$tDistance = $timesUser["Distance"];
			$tStroke = $timesUser["Stroke"];
			$tTime = $timesUser["Times"];
		}
				
		else { //If user has more than one time submitted already.
			
			}  
		} //End for form not submitted.
	
	//------------------------Modify User Time------------------------------------------
	if ($db->numRows == 1) { //If user has only one time submitted already.
					
			$tUserID = $timesUserID["ID"]; //Since one result, set to that time id.
			$tName = $timesUser["Name"];
			$tGender = $timesUser["Gender"];
			$tAge = $timesUser["Age_Range"];
			$tDistance = $timesUser["Distance"];
			$tStroke = $timesUser["Stroke"];
			$tTime = $timesUser["Times"];
		}
	
	//Retrieve the post variables from the form.
	if (isset($_POST['modify']) || isset($_POST['delete'])) {
		$nameUp = $_POST['name'];
		$sexUp = $_POST['sex'];
		$ageUp = $_POST['age'];
		$distanceUp = $_POST['distance'];
		$strokeUp = $_POST['stroke'];
		$timeUp = $_POST['time'];
		 
		//Prep the data for saving in a new times object.   (associative array?)
		$dataUp['Name'] = $nameUp;
		$dataUp['Gender'] = $sexUp; 
		$dataUp['Age_Range'] = $ageUp;
		$dataUp['Distance'] = $distanceUp;
		$dataUp['Stroke'] = $strokeUp;
		$dataUp['Times'] = $timeUp;
		$dataUp['userid'] = $userid;
		
		//Create the new times object.
		$timesNew = new TimeClass($dataUp, "times", $db);
	
	}
	
	if (isset($_POST['modify'])) {
		
		//Update the Record
		foreach ($timesUserID as $row) {
			$db->update($dataUp, "times", "id = " . $row);	//$dataUp
		}
	}
	
	if (isset($_POST['delete'])) {
	
		//Delete each selected time.
		foreach ($timesUserID as $row) {
			$db->delete("times", "id = " . $row);
		}
	}
	
	?>
	 
  <body>
	<!-- Main navigation, same for every page.-->
    
	<div class="container">
     <div class="masthead">
        <ul class="nav nav-justified">
          <li><a href="http://ps11.pstcc.edu/~c2230a14/swim/index.html">Home</a></li>
		  <li><a href="http://ps11.pstcc.edu/~c2230a14/swim/blog.html">Blog</a></li>
          <li><a id="active" href="http://ps11.pstcc.edu/~c2230a14/swim/compete.php">Compete</a></li>
		  <li><a href="http://ps11.pstcc.edu/~c2230a14/swim/times.php">Times</a></li>
          <li><a href="http://ps11.pstcc.edu/~c2230a14/swim/training.html">Training</a></li>
          <li><a href="http://ps11.pstcc.edu/~c2230a14/swim/resources.html">Resources</a></li>
        </ul>
      </div>

	<!-- Provide a logout button that links to the logout page, 
	which destroys the session and returns them to the main page. -->
	<br><br>
	<form action="logout.php" method="post">	
		<button class="nav-justified"  style="float:right; width:10%;" 
		type="submit" class="btn btn-lg btn-primary btn-block" value="New" name="create-new"/>Log Out</button>
	</form>
	  
	<!-- Main header image with a link around it to the main page. -->
    <div class="jumbotron">
      <a href="http://ps11.pstcc.edu/~c2230a14/swim/index.html"><img src="swim3.jpg"></a>
      <p class="lead">All About Swimming</p>
    </div>

    <!-- Form for inputting swim times. -->
    <div class="row">
        <div class="col-lg-6" style="background-color: #CC99FF;">
			<h2> Add, Modify, or Delete Your Swim Times </h2>
			<form method="post" action="compete.php" id="myForm">
				Name: <input type="text" name="name" pattern="^[A-Z .a-z]{2,20}$" value=<?php echo $tName; ?>><br>
				Gender: <input type="radio" name="sex" value="M" <?php if ($tGender == "M") echo 'checked'; ?> required/>Male  
					<input type="radio" name="sex" value="F" <?php if ($tGender == "F") echo 'checked'; ?> >Female  
					<input type="radio" name="sex" value="N" <?php if ($tGender == "N") echo 'checked'; ?> >Neither<br>
				Age Range: <select id="select" name="age" required/>
					<option <?php if ($tAge == "10 and Under") echo 'selected'; ?>value="10 and Under">10 and Under</option>
					<option <?php if ($tAge == "11-12") echo 'selected'; ?> value="11-12">11-12</option>
					<option <?php if ($tAge == "13-14") echo 'selected'; ?> value="13-14">13-14</option>
					<option <?php if ($tAge == "15-16") echo 'selected'; ?> value="15-16">15-16</option>
					<option <?php if ($tAge == "17-18") echo 'selected'; ?> value="17-18">17-18</option>
					<option <?php if ($tAge == "19-20") echo 'selected'; ?> value="19-20">19-20</option>
					<option <?php if ($tAge == "21-25") echo 'selected'; ?> value="21-25">21-25</option>
					<option <?php if ($tAge == "26-30") echo 'selected'; ?> value="26-30">26-30</option>
					<option <?php if ($tAge == "31-40") echo 'selected'; ?> value="31-40">31-40</option>
					<option <?php if ($tAge == "41-50") echo 'selected'; ?> value="41-50">41-50</option>
					<option <?php if ($tAge == "51-60") echo 'selected'; ?> value="51-60">51-60</option>
					<option <?php if ($tAge == "61-70") echo 'selected'; ?> value="61-70">61-70</option>
					<option <?php if ($tAge == "71 and Up") echo 'selected'; ?> value="71 and Up">71 and Up</option>
				</select><br>
				Event Distance in Meters: <select id="select" name="distance" required/>
					<option <?php if ($tDistance == "50") echo 'selected'; ?> value="50">50</option>
					<option <?php if ($tDistance == "100") echo 'selected'; ?> value="100">100</option>
					<option <?php if ($tDistance == "200") echo 'selected'; ?> value="200">200</option>
					<option <?php if ($tDistance == "400") echo 'selected'; ?> value="400">400</option>
					<option <?php if ($tDistance == "500") echo 'selected'; ?> value="500">500</option>
					<option <?php if ($tDistance == "800") echo 'selected'; ?> value="800">800</option>
					<option <?php if ($tDistance == "1000") echo 'selected'; ?> value="1000">1000</option>
					<option <?php if ($tDistance == "1500") echo 'selected'; ?> value="1500">1500</option>
					<option <?php if ($tDistance == "1650") echo 'selected'; ?> value="1650">1650</option>
				</select><br>
				Event Stroke: <select id="select" name="stroke" required/>
					<option <?php if ($tStroke == "Freestyle") echo 'selected'; ?> value="Freestyle">Freestyle</option>
					<option <?php if ($tStroke == "Backstroke") echo 'selected'; ?> value="Backstroke">Backstroke</option>
					<option <?php if ($tStroke == "Breaststroke") echo 'selected'; ?> value="Breaststroke">Breaststroke</option>
					<option <?php if ($tStroke == "Butterfly") echo 'selected'; ?> value="Butterfly">Butterfly</option>
					<option <?php if ($tStroke == "Individual Medley") echo 'selected'; ?> value="Individual Medley">IM</option>
				</select><br>
			Time: <input type="text" name="time" pattern="[0-5][0-9]\:[0-5][0-9]\.[0-9][0-9]" placeholder="00:00.00" required/ value=<?php echo $tTime; ?>><br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(e.g. 00:43.02 for 0 min., 43 sec., 2 millisec.)<br><br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button style="display:inline;" type="submit" style="margin: 0 auto;" name="submit">Add</button>&nbsp;&nbsp;&nbsp;&nbsp;
			<button style="display:inline;" type="submit" style="margin: 0 auto;" name="modify">Modify</button>&nbsp;&nbsp;&nbsp;&nbsp;
			<button style="display:inline;" type="submit" style="margin: 0 auto;" name="delete">Delete</button><br>
			</form>
			<br><br>
		</div>
	
		<!-- Results column with results from the database. -->
		<div class="col-lg-6">
          <h2 style="text-align: center;">Results</h2>
          <?php

			//Print success or error messages for modification and deletion.
			if (isset($_POST['delete']) || isset($_POST['modify'])) { 
				
				//Select all of user's times from the database to determine how many already in there.
				$timesTest = $db->select("ID", "times", "userid = $userid"); 
				
				//Print success messages to confirm deletion or modification as long as they have times in the database.
				if (isset($_POST['delete']) && ($db->numRows != 0)) {
					echo '<h4 style="text-align: center;">Your time was successfully deleted.</h4><br>';
				}
				if (isset($_POST['modify']) && ($db->numRows != 0)) {
					echo '<h4 style="text-align: center;">Your time was successfully modified.</h4><br>';
				}
				//Print error message if user tries to modify or delete with no times in the database.
				if ((isset($_POST['modify']) || isset($_POST['delete'])) && $db->numRows == 0) {
					echo '<h4 style="text-align: center;">Please submit at least one time before you modify or delete.</h4><br>';
					
				}
			}
		
			//Declare swim times variables.
			$name = "";
			$sex = "";
			$age = "";
			$distance = "";
			$stroke = "";
			$time = "";
			
			if(isset($_SESSION['id'])) {
				$userid = $_SESSION['id'];
			}
	
			//Check to see if swim times form is submitted.		
			if(isset($_POST['submit']) || isset($_POST['modify'])) { 
		  
				//Retrieve the post variables from the form.
				$name = $_POST['name'];
				$sex = $_POST['sex'];
				$age = $_POST['age'];
				$distance = $_POST['distance'];
				$stroke = $_POST['stroke'];
				$time = $_POST['time'];
		 
		
				//Insert New Times---------------------------------------
				//Prep the data for saving in a new times object.
				$data['Name'] = $name;
				$data['Gender'] = $sex; 
				$data['Age_Range'] = $age;
				$data['Distance'] = $distance;
				$data['Stroke'] = $stroke;
				$data['Times'] = $time;
				$data['userid'] = $userid;
		
				//Create the new times object.
				$times = new TimeClass($data, $db);
			
				//Insert into database if add button selected.
			    if(isset($_POST['submit'])) {
					$db->insert($data, "times");
				}	
				
				//Select Matching Times-----------------------------------		
				$times = $db->select("Name, Times", "times", "Age_Range = '$age' and Gender = '$sex' and 
					Distance = $distance and Stroke = '$stroke'", "Times");

				if ($db->numRows == 0) { //If submission fails, times is null.
					$times = null;
				}

			}

			//Print selected times from query.
			if(isset($_POST['submit']) || isset($_POST['modify'])) {
			
				if ($db->numRows == 0) { //Error message for failed times submission.
					echo '<h4 style="text-align: center;">Sorry, something went wrong with your submission. Please try again.</h4><br>';
				}
				elseif ($db->numRows == 1) { //For only one result, don't use array to display.
					echo '<table class="table table-bordered table-striped">';
					echo '<tr><th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Name</th>';
					echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Time</th></tr>';
					echo '<tr><td>' . $times["Name"] . '</td><td>' . $times["Times"] . '</td></tr>';
					echo '</table>';
				}	
				else { //More than one result from database, use array.
					echo '<table class="table table-bordered table-striped">';
					echo '<tr><th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Name</th>';
					echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Time</th></tr>';
					
					foreach($times as $row) { //Problem with Name and Times.
						echo '<tr><td>' . $row["Name"] . '</td><td>' . $row["Times"] . '</td></tr>';
					}	
					echo '</table>';
				} 
			}
			elseif (isset($_POST['delete'])) {
				
				//If user tries to delete when they have no times to begin with.
				if ($db->numRows == 0) {
					echo ''; //Gets rid of errors, only shows error message from above.
				}
				//If user deletes their only time in the database, reminds them to enter more times. Prevents errors.
				elseif (($db->numRows < 2) && ($db->numRows > 0)) {
					echo '<h4 style="text-align: center;">You have no remaining times. Add more to your left!</h4><br>';
				}
				//If user has only one time remaining in the database after deletion, print that time.
				elseif ($db->numRows == 2) {
					echo '<table class="table table-bordered table-striped">';
					echo '<tr><th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Name</th>';
					echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Time</th></tr>';
					echo '<tr><td>' . $times["Name"] . '</td><td>' . $times["Times"] . '</td></tr>';
					echo '</table>';
				}
				//If user has more than one time left after deletion, print leftover times in an array.
				else { //ADD LATER TIMES ARRAY?
					echo '<table class="table table-bordered table-striped">';
					echo '<tr><th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Name</th>';
					echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Time</th></tr>';
					
					foreach($times as $row) { 
						echo '<tr><td>' . $row["Name"] . '</td><td>' . $row["Times"] . '</td></tr>';
					}	
					echo '</table>';
				}
			}
			else { //If the user hasn't submitted the form yet.
			
				//Display all the logged in user's times to them. First thing seen in results column.
				if ($db->numRows == 0) { //If the user hasn't submitted any times yet.
					echo '<h4 style="text-align: center;">Submit the form to see how you rank to your peers!</h4><br>';
				}
				
				elseif ($db->numRows == 1) { //If user has only one time submitted already.
					echo '<table width="100%" class="table table-bordered table-striped">';
					echo '<tr><th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Name</th>';
					echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Gender</th>';
					echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Age Range</th>';
					echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Distance</th>';
					echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Stroke</th>';
					echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Time</th></tr>';
					echo '<tr><td>' . $timesUser["Name"] . '</td><td>' . $timesUser["Gender"] . '</td><td>' . $timesUser["Age_Range"] . 
						 '</td><td>' . $timesUser["Distance"] . '</td><td>' . $timesUser["Stroke"] . '</td><td>' . $timesUser["Times"] . '</td></tr>';
					echo '</table>'; //End table.
				}
				
				else { //If user has more than one time submitted already.
				
					echo '<table width="100%" class="table table-bordered table-striped">';
					echo '<tr><th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Name</th>';
					echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Gender</th>';
					echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Age Range</th>';
					echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Distance</th>';
					echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Stroke</th>';
					echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Time</th></tr>';
				
					//if (isset($timesUser["Name"])) {
					foreach($timesUser as $row) { //Times object passed to database class and retrieved from database via array.
						echo '<tr><td>' . $row["Name"] . '</td><td>' . $row["Gender"] . '</td><td>' . $row["Age_Range"] . 
						'</td><td>' . $row["Distance"] . '</td><td>' . $row["Stroke"] . '</td><td>' . $row["Times"] . '</td></tr>';
					}
					//}
						echo '</table>'; //End table.
				}  
				
			} //End for form not submitted.
	
		   ?>
		</div> <!--End of results column -->
    </div> <!-- End of row -->

    <!-- Footer -->
		<div class="footer">
			<p>&copy; Maggie Kubarewicz and Karen Cheng 2014</p>
		</div>

    </div> <!-- End of container. -->

    <script> 
	/*!
	* IE10 viewport hack for Surface/desktop Windows 8 bug
	* Copyright 2014 Twitter, Inc.
	* Licensed under the Creative Commons Attribution 3.0 Unported License. For
	* details, see http://creativecommons.org/licenses/by/3.0/.
	*/
	// See the Getting Started docs for more information:
	// http://getbootstrap.com/getting-started/#support-ie10-width

	(function () {
		'use strict';
	if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
		var msViewportStyle = document.createElement('style')
		msViewportStyle.appendChild(
			document.createTextNode(
			'@-ms-viewport{width:auto!important}'
			)
		)
		document.querySelector('head').appendChild(msViewportStyle)
	}
	})();
	</script>
  </body>
</html>
