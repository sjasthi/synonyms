<?php
  $nav_selected = "LOGIN";
  $left_buttons = "NO";
  $left_selected = "";

  include("./nav.php");
  
 ?>

<?php
	require('db_configuration.php');
    // If the values are posted, insert them into the database.

    ?>
<!DOCTYPE html>
<html>
<head>
	<title>login</title>
	
<!-- Latest compiled and minified CSS -->
<link href="css/style.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
   
<div class="container">
<?php
//include 'header.php';
?> 

<?php
    //session_start();
 
    //we can add this minor log in check and log out functionality later
 	//if (!isset($_SESSION['logged_in'])){
     
        echo '<h1>Login</h1>';
	    echo '<form method="POST" action="login_process.php"><p>';
        echo 'Username:<br />';
        echo '<input type="text" name="username" /><br /><br />';
        echo 'Password:<br />';
        echo '<input type="password" name="password" /><br /><br />';
        echo '<input type="submit" value="Login" /> <input type="reset" value="Empty fields" />';
        echo '</form>';
        
    //}else{
    //    echo 'You are already logged in.';
    //}
	
    ?>

    <h2>Not registered user?</h2>
    <a href="register.php">Register</a>
    </div>
    
    </body>
</html>