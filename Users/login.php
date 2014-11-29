<?php
	// login.php
	// Provides a form for users to attempt to login to
	// the system. It uses UserTools to check credentials.
	//
	// Adapted from https://github.com/revdrbrown/Commentz/blob/master/login.php
	// 
	
	require_once 'global.inc.php';
	
	$error = "";
	$username = "";
	$password = "";

	//check to see if they've submitted the login form
	if(isset($_POST['submit-login'])) { 
	
		$username = $_POST['username'];
		$password = $_POST['password'];
	
		//successful login, redirect them to a page
		if($userTools->login($username, $password))
			header("Location: compete.php");
		else
			$error = "<h2 class=\"text-danger\" style=\"text-align: center\">Incorrect username or password. Please try again.</h2>";
	}
?>

<html>
	<head>
	   <meta charset="utf-8">
	   <meta name="viewport" content="width=device-width, initial-scale=1.0">
	   <meta name="User login." content="">
	   <meta name="Placeholder Name" content="">
	   <link rel="shortcut icon" href="images/favicon.png">
       
	   <title>Login</title>
       
	   <!-- Bootstrap core CSS -->
	   <link href="bootstrap.css" rel="stylesheet">
       
	   <!-- Custom styles for this template -->
	   <link href="signin.css" rel="stylesheet">
     
	 </head>
	<body>
    <h3 class="form-signin-heading" style="text-align: center">Login</h3>
	<h5 class="form-signin-heading" style="text-align: center">Please Login to Submit and See Swim Event Times</h5>
	<form class="form-signin" action="login.php" method="post">	
	<input type="text" class="form-control" placeholder="User Name" value="<?php echo $username; ?>" autofocus name="username" /><br>
	<input type="password" class="form-control" placeholder="Password" value="<?php echo $password; ?>" name="password" />
	<button type="submit" class="btn btn-lg btn-primary btn-block" value="Register" name="submit-login" />Login</button>	
	</form>
	<br><br>
	<h3 class="form-signin-heading" style="text-align:center;">First Time Here?</h3>
	<h5 class="form-signin-heading" style="text-align:center;">Create an account to enter your swim times and compare your times to your peers!</h5>
	<form class="form-signin" action="register.php" method="post">	
	<button type="submit" class="btn btn-lg btn-primary btn-block" value="New" name="create-new"/>Create an Account</button>
	</form>
	<h3 class="form-signin-heading" style="text-align:center;">Rather Not?</h3>
	<form class="form-signin" action="index.html" method="post">	
	<button type="submit" class="btn btn-lg btn-primary btn-block" value="New" name="create-new"/>Go Back to Main Page</button>
	</form>
	
	
	
	<?php
		echo $error."<br/>";
	?>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//code.jquery.com/jquery-latest.min.js"></script>
    <script src="bootstrap.js"></script>
</body>
</html>



