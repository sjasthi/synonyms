<?php
	require('db_configuration.php');
    // If the values are posted, insert them into the database.

    ?>
<!DOCTYPE html>
<html>
<head>
	<title>logout</title>
	
<!-- Latest compiled and minified CSS -->
<link href="css/style.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
   
<div class="container">

<div class="row">
<?php
    session_start();
	$old_user = $_SESSION['isAdmin'];

	// store  to test if they *were* logged in
	unset($_SESSION['isAdmin']);
	$result_dest = session_destroy();

	// start output html
	include 'header.php';

	if (!empty($old_user)) {
	  if ($result_dest)  {
		// if they were logged in and are now logged out
		echo '<h2>Logged out.</h2>';
		
	  } else {
	   // they were logged in and could not be logged out
		echo '<h2>Could not log you out.</h2>';
	  }
	} else {
	  // if they weren't logged in but came to this page somehow
	  echo '<h2>You were not logged in, and so have not been logged out.</h2>';
	  
	}
	
?>

    
    <a class='sub' id='homesub' href="index.php">Return Home</a>
    </div>
	
</div>
    
    </body>
</html>