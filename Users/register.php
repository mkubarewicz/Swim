<?php 
	// register.php  
	// Screen to add usernames to the users database
	// for loggin in later.
	//
	// Adapted from https://github.com/revdrbrown/Commentz/blob/master/register.php
	//
	
	require_once 'global.inc.php';
	
	//initialize php variables used in the form
	$username = "";
	$password = "";
	$password_confirm = "";
	$error = "";
	$email = "";
	$firstName = "";
	$lastName = "";
	
	//check to see that the form has been submitted
	if(isset($_POST['submit-form'])) { 
	
		//retrieve the $_POST variables
		$username = $_POST['username'];
		$password = $_POST['password'];
		$password_confirm = $_POST['password-confirm'];
		$email = $_POST['email'];
		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
	
		//initialize variables for form validation
		$success = true;
		$userTools = new UserTools();
		
		//validate that the form was filled out correctly
		//check to see if user name already exists
		if($userTools->checkUsernameExists($username))
		{
			$error .= "That username is already taken.<br/> \n\r";
			$success = false;
		}
	
		//check to see if twice entered passwords match
		if($password != $password_confirm) {
			$error .= "Passwords do not match.<br/> \n\r";
			$success = false;
		}
	
		if($success)
		{
			//prep the data for saving in a new user object if successful username creation.
			$data['username'] = $username;
			$data['password'] = md5($password); //encrypt the password for storage
			$data['email'] = $email;
			$data['firstName'] = $firstName;
			$data['lastName'] = $lastName;
		
			//create the new user object
			$newUser = new User($data);
		
			//save the new user to the database
			$newUser->save(true);
		
			//log them in
			$userTools->login($username, $password);
		
			//redirect them to the competition page they started at.
			header("Location: compete.php");
			
		}
	
	}
	
	//If the form wasn't submitted, or didn't validate
	//then we show the registration form again so they can try again.
?>


<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Register a User." content="">
    <meta name="Placeholder Name" content="">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>User Registration</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">

 
  </head>
<body>
   <div class="container">
        <h2 class="form-signin-heading" style="text-align: center">Create an Account</h2>

	<form class="form-signin" action="register.php" method="post">	
	<input type="text" class="form-control" placeholder="*User Name" value="<?php echo $username; ?>" autofocus name="username" pattern="^\w{3,20}$" required/><br>
	<input type="text" class="form-control" placeholder="First Name" value="<?php echo $firstName; ?>" name="firstName" pattern="^[A-Z a-z]{2,20}$"><br>
	<input type="text" class="form-control" placeholder="Last Name" value="<?php echo $lastName; ?>" name="lastName" pattern="^[A-Z a-z]{2,20}$"><br>
	<input type="password" class="form-control" placeholder="*Password (8-20 length, at least 1 #)" value="<?php echo $password; ?>" name="password" pattern="^(?=.*\d).{8,20}$" required/>
	<input type="password" class="form-control" placeholder="*Password Again" value="<?php echo $password; ?>" name="password-confirm" pattern="^(?=.*\d).{8,20}$" required/>
	<input type="text" class="form-control" placeholder="Email" value="<?php echo $email; ?>" name="email" pattern="^[\w-\.]+@([\w-]+\.)+[A-Za-z-]{2,4}$"><br>
	<button type="submit" class="btn btn-lg btn-primary btn-block" value="Register" name="submit-form" />Register</button>
	</form>
	<h3 class="form-signin-heading" style="text-align:center;">Rather Not?</h3>
	<form class="form-signin" action="index.html" method="post">	
	<button type="submit" class="btn btn-lg btn-primary btn-block" value="New" name="create-new"/>Go Back to Main Page</button>
	</form>
	<?php print $error; ?>
</body>
</html>
