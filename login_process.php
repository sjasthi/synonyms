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

<div class="row">
<?php
    require('db_configuration.php');
	
	
    

    $userName = $_POST['username'];
    $password = $_POST['password'];
    //echo $userName.", ".$password;

    //query the database to see if user exists and if password matches given password
    //if doesn't exist then goes back to login.php
    //if exists go to home page, now with session set to have user information
    $passwordCorrect = checkUserPassword($userName, $password, $db);
    $userExists = checkUserExistence($userName, $db);
    if($userExists){
        //echo 'user exists<br';
        //$passwordCorrect = checkUserPassword($userName, $password, $db);
        if($passwordCorrect){
			//session_start();
           
            //add to Session object
            //session_start();
            $userStatus = checkIsAdmin($userName, $db);
            // start output html
			include 'header.php';
            $_SESSION["isAdmin"] = $userStatus;
			/*
			echo 'Log in successful<br>';
			//echo 'User Status is = '.$userStatus.'<br>';
            //echo 'session : '.$_SESSION["isAdmin"]."<br>";
            if($_SESSION["isAdmin"] == 1){
                echo 'this is admin<br>';
            }else{
                echo 'this is regular user<br>';
            }
            echo '<a href="index.php" class="sub">Return to Home Page</a>';
			*/
			header('Location: index.php');
			exit;
        }else{
			// start output html
			include 'header.php';
            echo '<h2>Wrong password</h2>';
            echo '<a href="login.php" class="sub">Return to Login Page</a>';
        }
    }else{
		// start output html
		include 'header.php';
        echo '<h2>user does not exist</h2>';
		echo '<a href="login.php" class="sub">Return to Login Page</a>';
    }
?>
	<a class='sub' id='homesub' href="index.php">Return Home</a>
    </div>
	
</div>
    
    </body>
</html>
<?php
    function checkUserExistence($userName, $db){
        $query = "SELECT * FROM user WHERE userName = '".$userName."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        if($numOfRows > 0){
            $stmt->free_result();
            $stmt->close();
            return true;
        }else{
            $stmt->free_result();
            $stmt->close();
            return false;
        }
    }

    function checkUserPassword($userName, $password, $db){
        $query = "SELECT password FROM user WHERE userName = '".$userName."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        //$numOfRows = $stmt->num_rows;
        $stmt->bind_result($dbPassword);
        $stmt->fetch();
        if($dbPassword == $password){
            $stmt->free_result();
            $stmt->close();
            return true;
        }else{
            $stmt->free_result();
            $stmt->close();
            return false;
        }
    }

    function checkIsAdmin($userName, $db){
        $query = "SELECT isAdmin FROM user WHERE userName = '".$userName."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($adminStatus);
        $stmt->fetch();
        
        return $adminStatus;
    }
?>