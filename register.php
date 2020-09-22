<?php
	require('db_configuration.php');

/*

    // If the values are posted, insert them into the database.
    if (isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
	    $email = $_POST['email'];
        $password = $_POST['password'];
 
        $query = "INSERT INTO `user` (username, password, email) VALUES ('$username', '$password', '$email')";
        $result = mysqli_query($connection, $query);
        if($result){
            $smsg = "User Created Successfully.";
        }else{
            $fmsg ="User Registration Failed";
        }
    }
*/


    ?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration</title>
	
<!-- Latest compiled and minified CSS -->
<link href="css/style.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
   
<div class="container">
<?php
include 'header.htm';
?> 
<div class="row">    

			</div>

      <form class="form-signin" action="register_process.php" method="POST">
      
      <?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
      <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
        <h2 class="form-signin-heading">Register</h2>
        <div class="input-group">
	 
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            
        </div>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <div>
		<button class='sub' id='homesub1' type="submit">Register</button>
		
            <div>
                <a class='sub' id='homesub' href="index.php">Return Home</a>
            </div>
        
        </form>
	  
	  
</div>
</div>


</body>

</html>