<!DOCTYPE html>
<html lang="en">

<!-- **********The Compete Page**********
Here users can add, edit, and delete their swimming times
to the MySQL database. -->

<?php
	//Use to access the Times table.
	require_once 'global.inc.php';

	//Create new database object.
	$db = new DB();
	
	//Hide errors.
	error_reporting(0);
?>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="Maggie" content="">

    <title>Swimmer's World</title>

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
	//Version using List Group and get method, to change later back to table and post.
	
	//Declare variables.
	$tUser = null; //Instance of TimeClass
	$data = null; //Data to store in tUser object.
        $tID = ""; //ID of the Time record, hidden field in form.
	
	//For the tUser object.
	$name = "";
	$sex = "";
	$age = "";
	$distance = "";
	$stroke = "";
	$time = "";
	
	//Empty data array to pass to tUser object.
	$data['ID'] = $tID;
        $data['Name'] = $name;
	$data['Gender'] = $sex; 
	$data['Age_Range'] = $age;
	$data['Distance'] = $distance;
	$data['Stroke'] = $stroke;
	$data['Times'] = $time;
	$data['userid'] = $userid;
		
	//Create tUser object as instance of Time Class from Time.class.php
	//Pass it the null data array and a connection to the database.
        $tUser = new TimeClass($data, $db);
			
	//If userid is set (user logged in), pass it $userid to write to object.
	if(isset($_SESSION['id'])) {
		$userid = $_SESSION['id'];
	}

	//If form has been submitted in any way (hidden field written to),
	//Assign real data from the form to the data array.
        if (isset($_POST['tID'])) {
                $tID = $_POST['tID'];
		$data['ID'] = $tID;
	        $data['Name'] = $_POST['name'];
		$data['Gender'] = $_POST['sex']; 
		$data['Age_Range'] = $_POST['age'];
		$data['Distance'] = $_POST['distance'];
		$data['Stroke'] = $_POST['stroke'];
		$data['Times'] = $_POST['time'];
		$data['userid'] = $userid;
		
		//Write the data and the db connection to the tUser object.
                 $tUser = new TimeClass($data, $db);
	
	} //Check for get parameters of tID, too.
	elseif (isset($_GET['tID'])) {
                 $tID = $_GET['tID'];
		 $tUserGet = new TimeClass($data, $db); //Create instance to access get method in Time class.
		 $tUser = $tUserGet->get($tID); //Get ID from database.
	}

	
	//--------------Handle each mode of the form submission.
	//
	//For adding a record to the form.
	if(isset($_POST['submit'])) {
              $tUser->insert($data);	// insert the record
	}
	//For modifying a record in the form.
        elseif (isset($_POST['modify'])) {
	       $tUser->update($data, $tID);	// update the record
	 }
	//For deleting a record from the form.
	elseif (isset($_POST['delete'])) {
		$tUser->delete($tID);	// delete the record
	}
	//Else empty object.
    	else {
		$tUser = new TimeClass($data, $db);	 
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
	
	<?php 
	//If hidden field is set, use get to pass all the object information to this part of the code.
	if (isset($_GET['tID'])) {
                 $tID = $_GET['tID'];
		 $tUserGet = new TimeClass($data, $db);
		 $tUser = $tUserGet->get($tID);
	}
	?>
        <div class="col-lg-6" style="background-color: #CC99FF;">
			<h2> Add, Modify, or Delete Your Swim Times </h2>
			<form method="post" action="compete.php" id="myForm">
				<input type="hidden" name="tID" value="<?php echo $tUser->ID; ?>"> <!--<?//php echo $tUser->id; ?>-->
				Name: <input type="text" name="name" pattern="^[A-Z .a-z]{2,20}$" value="<?php echo $tUser->Name; ?>"><br>
				Gender: <input type="radio" name="sex" value="M" <?php if ($tUser->Gender == "M") echo 'checked'; ?> required/>Male  
					<input type="radio" name="sex" value="F" <?php if ($tUser->Gender == "F") echo 'checked'; ?> >Female  
					<input type="radio" name="sex" value="N" <?php if ($tUser->Gender == "N") echo 'checked'; ?> >Neither<br>
				Age Range: <select id="select" name="age" required/>
					<option <?php if ($tUser->Age_Range == "10 and Under") echo 'selected'; ?>value="10 and Under">10 and Under</option>
					<option <?php if ($tUser->Age_Range == "11-12") echo 'selected'; ?> value="11-12">11-12</option>
					<option <?php if ($tUser->Age_Range == "13-14") echo 'selected'; ?> value="13-14">13-14</option>
					<option <?php if ($tUser->Age_Range == "15-16") echo 'selected'; ?> value="15-16">15-16</option>
					<option <?php if ($tUser->Age_Range == "17-18") echo 'selected'; ?> value="17-18">17-18</option>
					<option <?php if ($tUser->Age_Range == "19-20") echo 'selected'; ?> value="19-20">19-20</option>
					<option <?php if ($tUser->Age_Range == "21-25") echo 'selected'; ?> value="21-25">21-25</option>
					<option <?php if ($tUser->Age_Range == "26-30") echo 'selected'; ?> value="26-30">26-30</option>
					<option <?php if ($tUser->Age_Range == "31-40") echo 'selected'; ?> value="31-40">31-40</option>
					<option <?php if ($tUser->Age_Range == "41-50") echo 'selected'; ?> value="41-50">41-50</option>
					<option <?php if ($tUser->Age_Range == "51-60") echo 'selected'; ?> value="51-60">51-60</option>
					<option <?php if ($tUser->Age_Range == "61-70") echo 'selected'; ?> value="61-70">61-70</option>
					<option <?php if ($tUser->Age_Range == "71 and Up") echo 'selected'; ?> value="71 and Up">71 and Up</option>
				</select><br>
				Event Distance in Meters: <select id="select" name="distance" required/>
					<option <?php if ($tUser->Distance == "50") echo 'selected'; ?> value="50">50</option>
					<option <?php if ($tUser->Distance == "100") echo 'selected'; ?> value="100">100</option>
					<option <?php if ($tUser->Distance == "200") echo 'selected'; ?> value="200">200</option>
					<option <?php if ($tUser->Distance == "400") echo 'selected'; ?> value="400">400</option>
					<option <?php if ($tUser->Distance == "500") echo 'selected'; ?> value="500">500</option>
					<option <?php if ($tUser->Distance == "800") echo 'selected'; ?> value="800">800</option>
					<option <?php if ($tUser->Distance == "1000") echo 'selected'; ?> value="1000">1000</option>
					<option <?php if ($tUser->Distance == "1500") echo 'selected'; ?> value="1500">1500</option>
					<option <?php if ($tUser->Distance == "1650") echo 'selected'; ?> value="1650">1650</option>
				</select><br>
				Event Stroke: <select id="select" name="stroke" required/>
					<option <?php if ($tUser->Stroke == "Freestyle") echo 'selected'; ?> value="Freestyle">Freestyle</option>
					<option <?php if ($tUser->Stroke == "Backstroke") echo 'selected'; ?> value="Backstroke">Backstroke</option>
					<option <?php if ($tUser->Stroke == "Breaststroke") echo 'selected'; ?> value="Breaststroke">Breaststroke</option>
					<option <?php if ($tUser->Stroke == "Butterfly") echo 'selected'; ?> value="Butterfly">Butterfly</option>
					<option <?php if ($tUser->Stroke == "Individual Medley") echo 'selected'; ?> value="Individual Medley">IM</option>
				</select><br>
			Time: <input type="text" name="time" pattern="[0-5][0-9]\:[0-5][0-9]\.[0-9][0-9]" placeholder="00:00.00" required/ value=<?php echo $tUser->Times; ?>><br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(e.g. 00:43.02 for 0 min., 43 sec., 2 millisec.)<br><br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button style="display:inline;" type="submit" style="margin: 0 auto;" name="submit">Add</button>&nbsp;&nbsp;&nbsp;&nbsp;
			<button style="display:inline;" type="submit" style="margin: 0 auto;" name="modify">Modify</button>&nbsp;&nbsp;&nbsp;&nbsp;
			<button style="display:inline;" type="submit" style="margin: 0 auto;" name="delete">Delete</button><br>
			</form>
			<br><br>
		</div> <!--End of div to contain the form -->
	
	
	<!-- Results column with results from the database. -->
	<div class="col-lg-6">
          <h2 style="text-align: center;">Results</h2>
	        <div class="list-group">
			
        	 <?php
		  
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
	
	
		//---------------------------Print success or error messages.-------------------------------
			
		//Select all of user's times from the database to determine how many already in there.
		$timesTest = $db->select("ID", "times", "userid = $userid"); 
			
		if (isset($_POST['delete']) || isset($_POST['modify']) || isset($_POST['submit'])) { 
			//Print success messages to confirm deletion or modification as long as they have times in the database.
			if (isset($_POST['submit']) && ($db->numRows != 0)) {
				echo '<h4 style="text-align: center;">Your time was successfully added.</h4><br>';
			}
			elseif (isset($_POST['delete']) && ($db->numRows != 0)) {
				echo '<h4 style="text-align: center;">Your time was successfully deleted.</h4><br>';
			}
			elseif (isset($_POST['modify']) && ($db->numRows != 0)) {
				echo '<h4 style="text-align: center;">Your time was successfully modified.</h4><br>';
			}
			//Print error message if user tries to modify or delete with no times in the database.
			elseif ((isset($_POST['modify'])) && $db->numRows == 0) {
				echo '<h4 style="text-align: center;">Please submit at least one time before you modify.</h4><br>';	
			}
			else {
				echo '<h4 style="text-align: center;">There are no swim times yet.</h4><br>';
			}
				
		}
		//Else if they have no times yet and haven't submitted the form.
		elseif ($db->numRows == 0) {
			echo '<h4 style="text-align: center;">There are no swim times yet.</h4><br>';
		}
	
	
		//------------------------------------Display Results to User----------------------------------
		//Create empty data array to pass to instance of Time Class.
		$data['Name'] = $name;
		$data['Gender'] = $sex; 
		$data['Age_Range'] = $age;		
		$data['Distance'] = $distance;
		$data['Stroke'] = $stroke;
		$data['Times'] = $time;
		$data['userid'] = $userid; 

		//Check to see if swim times form is submitted in any way.	
		if(isset($_POST['submit']) || isset($_POST['modify']) || isset($_POST['delete'])) { 
		  
			//Prep the data for saving in a new times object and retrieve post variables from form.
			$data['Name'] = $_POST['name'];
			$data['Gender'] = $_POST['sex'];
			$data['Age_Range'] = $_POST['age'];
			$data['Distance'] = $_POST['distance'];
			$data['Stroke'] = $_POST['stroke'];
			$data['Times'] = $_POST['time'];
			$data['userid'] = $userid;
		  
			//New instance of Time Class and show list group of all times after form submission.
			$timUser = new TimeClass($data, $db);
			$timUser->showTimesList("compete.php", $userid);
		} 
	
		//Else if form not submitted yet, show list of results.
		else {
			//Create new instance of Time Class to access showTimesList method.
			$timUser = new TimeClass($data, $db);
				
			//Show a list group with links of all the times for the logged in user.
			$timUser->showTimesList("compete.php", $userid);
		}
	?>
		   </div> <!--End of List Group -->
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
