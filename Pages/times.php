<!DOCTYPE html>
<html lang="en">

<!-- **********The Times Page*************
Here users can search through the database
of swim times without having to log in or 
create an account. They can search by all
or specific age ranges, by all or either
gender, and for specific types of swim events.-->

<?php
//Use to access the Times table.
	require_once 'Times.class.php';
	require_once 'global.inc.php';
	
	//Hide errors if needed.
	//error_reporting(0);
?>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="Maggie" content="">
    
    <title>Swim</title>

	<!-- Bootstrap and custom styling for the whole site -->
	<style type = "text/css">
		@import url(swim.css)	
	</style> 

    <!-- Custom styles for the navbar -->
    <link href="justified-nav.css" rel="stylesheet">

  </head>

  <body>

    <div class="container">

	  <!-- Navigation Bar -->
      <div class="masthead">
        <ul class="nav nav-justified">
          <li><a href="http://ps11.pstcc.edu/~c2230a14/swim/index.html">Home</a></li>
		  <li><a href="http://ps11.pstcc.edu/~c2230a14/swim/blog.html">Blog</a></li>
          <li><a href="http://ps11.pstcc.edu/~c2230a14/swim/compete.php">Compete</a></li>
		  <li><a id="active" href="http://ps11.pstcc.edu/~c2230a14/swim/times.php">Times</a></li>
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
	  
     <!-- Jumbotron Heading -->
      <div class="jumbotron">
        <a href="http://ps11.pstcc.edu/~c2230a14/swim/index.html"><img src="swim3.jpg"></a>
        <p class="lead">All About Swimming</p>
      </div>
	  
	  <!-- Main Body -->
	  <div class="row">
		<div class ="row" style="background-color: #CC99FF;">
		<h2 style="text-align: center;"> Swim Time Search </h2>
		</div>
		
		<!-- Instructions to user column -->
		<div class="col-lg-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 style="text-align: center; font-size: 18px;" class="panel-title">Instructions</h3>
				</div>
				<div class="panel-body"> <!--Instructions to User -->
					<p>Search through the current database of swim times by any gender combination, by age ranges, 
					by common swim events, and the results will be sorted by the fastest times. On this page, 
					you do not have to have an account and you can see how you compare to different genders and 
					other age ranges. Note: all times are in short course (25 meter long pool).</p>
					<p>If you wish to add your own time to the database, you must go to the "Compete" link at 
					the top of the page where you will have to create an account. Then you can add, modify, 
					and delete your times from the database of all times. At that page,  you will also be 
					able to see your current ranking compared to your peers for the same event.</p>
				</div>
			</div> <!-- End of primary panel -->
		</div> <!-- End of Explanation Column -->
		
		<!-- Swim Time Search Results Column -->
		<div class="col-lg-8">
			<div class="input-group">
			<span style="text-align: left;" class="input-group-addon">
		
			<form width="100%" action="times.php" method="post">	
			
				<select name="sex"/>
					<option value="M">Male</option>
					<option value="F">Female</option>
					<option value="N">Both</option>
				</select>&nbsp;&nbsp;&nbsp;
			
				<select name="age"/>
					<option value="10 and Under">10 and Under</option>
					<option value="11-12">11-12</option>
					<option value="13-14">13-14</option>
					<option value="15-16">15-16</option>
					<option value="17-18">17-18</option>
					<option value="19-20">19-20</option>
					<option value="21-25">21-25</option>
					<option value="26-30">26-30</option>
					<option value="31-40">31-40</option>
					<option value="41-50">41-50</option>
					<option value="51-60">51-60</option>
					<option value="61-70">61-70</option>
					<option value="71 and Up">71 and Up</option>
					<option value="all">All</option>
				</select>&nbsp;&nbsp;&nbsp;
			
				<select name="dist">
					<option value="50">50</option>
					<option value="100">100</option>
					<option value="200">200</option>
					<option value="400">400</option>
					<option value="500">500</option>
					<option value="800">800</option>
					<option value="1000">1000</option>
					<option value="1500">1500</option>
					<option value="1650">1650</option>
				</select>&nbsp;&nbsp;&nbsp;
				
				<select name="stroke"/>
					<option value="Freestyle">Freestyle</option>
					<option value="Backstroke">Backstroke</option>
					<option value="Breaststroke">Breaststroke</option>
					<option value="Butterfly">Butterfly</option>
					<option value="Individual Medley">IM</option>
				</select>
					
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="submit" class="btn btn-default" value="search" name="search" />Search</button>	
			</form>
			</span>
			</div> <!-- End Form Input Group -->
			
	<?php
	//-------------------------------Swim Times Prep----------------------------------	
	//Declare swim times variables.
	$name = "";
	$sex = "";
	$age = "";
	$distance = "";
	$stroke = "";
	$time = "";
	
	//Get data from the selection narrowing form if form is submitted.
	if(isset($_POST['search'])) {
		$checkSex= $_POST['sex'];
		$checkStroke = $_POST['stroke'];
		$checkDist = $_POST['dist'];
		$checkAge = $_POST['age'];
	}
	
	//Prep the data for saving in a new times object.
	$data['Name'] = $name;
	$data['Gender'] = $sex; 
	$data['Age_Range'] = $age;
	$data['Distance'] = $distance;
	$data['Stroke'] = $stroke;
	$data['Times'] = $time;
		
	//Create the new times object.
	$times = new TimeClass($data);
			
	//-------------------------------Select Times from the times table based on user search criteria-------------------
	if (isset($_POST['search'])) { //If the form has been submitted
		
		if ($checkAge == "all" || $checkSex == "N") { //If the user chose all ages and both genders, edit SQL to reflect.
		
			if ($checkAge == "all" && $checkSex == "N") { //All ages, both genders
				$times = $db->select("Name, Gender, Age_Range, Distance, Stroke, Times", "times", 
					"Distance = $checkDist and Stroke = '$checkStroke'", "Times, Age_Range, Gender, Name"); 
			}
			else if ($checkAge != "all" && $checkSex == "N"){ //Both genders only
				$times = $db->select("Name, Gender, Age_Range, Distance, Stroke, Times", "times", 
				"Age_Range = '$checkAge' and Distance = $checkDist and Stroke = '$checkStroke'", "Times, Gender, Name"); 
			}
			else if ($checkAge == "all" && $checkSex != "N") { //All ages only
				$times = $db->select("Name, Gender, Age_Range, Distance, Stroke, Times", "times", 
					"Gender = '$checkSex' and Distance = $checkDist and Stroke = '$checkStroke'", "Times, Age_Range, Name"); 
			}	
		}
		else { //No all selections, search as normal.
			$times = $db->select("Name, Gender, Age_Range, Distance, Stroke, Times", "times", 
				"Gender = '$checkSex' and Distance = $checkDist and Stroke = '$checkStroke' and Age_Range = '$checkAge'", 
				"Times, Name"); 
		}
	}
	else { //A default search selection for 100 Free if the search form has not been submitted.
	$times = $db->select("Name, Gender, Age_Range, Distance, Stroke, Times", "times", 
		"Distance = 100 and Stroke = 'Freestyle'", "Times, Age_Range, Gender, Name"); 
	}
	
	//----------------------------------------------Display results--------------------------------------------
	if ($db->numRows == 0) { //If no times in the database, display an error message.
		echo 'Sorry, no results match your criteria. Search again or add your own times on the Compete page!';
	}
	
	//------------Create a table to display results.
	
	else if ($db->numRows == 1) { //If only one row result.
		echo '<table width="100%" class="table table-bordered table-striped">';
		echo '<tr><th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Name</th>';
		echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Gender</th>';
		echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Age Range</th>';
		echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Distance</th>';
		echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Stroke</th>';
		echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Time</th></tr>';
		echo '<tr><td>' . $times["Name"] . '</td><td>' . $times["Gender"] . '</td><td>' . $times["Age_Range"] . 
			'</td><td>' . $times["Distance"] . '</td><td>' . $times["Stroke"] . '</td><td>' . $times["Times"] . '</td></tr>';
		echo '</table>'; //End table.
	}
	else { //If more than one row result use an array.
		echo '<table width="100%" class="table table-bordered table-striped">';
		echo '<tr><th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Name</th>';
		echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Gender</th>';
		echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Age Range</th>';
		echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Distance</th>';
		echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Stroke</th>';
		echo '<th style="background-color: #0066FF; color: white; font-size: 18px; text-align: center;">Time</th></tr>';
				
		foreach($times as $row) { //Times object passed to database class and retrieved from database via array.
			echo '<tr><td>' . $row["Name"] . '</td><td>' . $row["Gender"] . '</td><td>' . $row["Age_Range"] . 
			'</td><td>' . $row["Distance"] . '</td><td>' . $row["Stroke"] . '</td><td>' . $row["Times"] . '</td></tr>';
		}
		
		echo '</table>'; //End table.
	}		
	?>
		</div> <!-- End of Search Table Column -->
		
		
	</div> <!-- End of row -->
	  

    <!-- Site footer -->
    <div class="footer">
		<p>&copy; Maggie Kubarewicz and Karen Cheng 2014</p>
    </div>

    </div> <!-- End of container -->


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
