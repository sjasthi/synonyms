<?php
  $nav_selected = "LOGIN";
  $left_buttons = "NO";
  $left_selected = "";

  include("./nav.php");
  
 ?>

<!DOCTYPE html>
<html>

<head>
  <?PHP
    require('session_validation.php');
    require_once('db_configuration.php');
    require_once("utility_functions.php");
    ?>
    <style>
      .divContainer {
        position: relative;
        top: 20px;
        height: 30px;
      }
      
      .text {
        font-size: 30px;
      }
      
      .textbox {
        outline: none;
        margin-left: 15px;
        margin-top: 10px;
        height: 100px;
        width: 480px;
        font-size: 40px;
        border-style: outset;
      }
      
      .loginbutton {
        position: relative;
        margin-top: 30px;
      }

      .addButton {
        background-color: #70baeb;
        border: 2px solid black;
        color: black;
        width: 180px;
        height: 60px;
          vertical-align: middle;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 30px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 12px;
        margin-top: 40px;
      }
      
      .message {
        position: relative;
        margin-top: 30px;
        height: 100px;
        width: 480px;
      }
      
      .messageText {
        font-size: 20px;
      }
      
      a {
        color: red;
        font-weight: bold;
        text-decoration: none;
      }

    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles/custom_nav.css" type="text/css">
</head>
<title>Synonyms Login</title>

<body>
  <?PHP 
    //session_start();
    echo getTopNav(); 
  ?>
  <br><br><br>
  <div class='divContainer' align="center">
    <form method='POST' action='login1.php'>
      <?php
        if (isset($_GET['puzzleName'])) {
          $puzzleName = validate_input($_GET['puzzleName']);
          echo "<h1>Please login to see the solution</h1>";
          echo "<input type='hidden' name='puzzleName' value='".$puzzleName."' />";
        }
//        if (isset($_SESSION['valid_user'])){
//          echo "Valid User: ";
//          echo $_GET($_SESSION["valid_user"])."</br>";
//        }
//        else if (isset($_SESSION['valid_admin'])){
//          echo "Valid Admin: ";
//          echo $_GET($_SESSION['valid_admin']);
//        }
        else{
        }

        if(isset($_POST['submit']))
        {
          $user = validate_input($_POST['user']);
          $pass = validate_input($_POST['pass']);

          //Check connection
          if (!empty($user) & !empty($pass) & ($user != 'admin') & ($pass != 'admin'))
          {
            session_start();
            $result = run_sql("SELECT * FROM users where user_email='$user' and user_password='$pass'");
            $row = count($result);
            echo $row;

            if ($row >= 1){
              while($row = mysqli_fetch_array($result)){
                $expire = time()+60*60*24*30; //1 month
                //setcookie("uid", $row['id_varified'], $expire);
                $roleID = $row['role'];
                if ($roleID == 1){
                  $_SESSION['valid_user'] = $user;
                  //echo $_SESSION['valid_user']; 
                  echo "Login successful as" . $row['user_email'] . "";
                  if(isset($_POST['puzzleName'])) {
                    $puzzleName = validate_input($_POST['puzzleName']);
                    header("Location:puzzle.php?puzzleName=".$puzzleName);
                  }
                  echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";
                }
                else if($roleID == 0){
                  $_SESSION['valid_admin'] = $user;
                  //echo $_SESSION['valid_admin'];
                  if(isset($_POST['puzzleName'])) {
                    $puzzleName = validate_input($_POST['puzzleName']);
                    header("Location:puzzle.php?puzzleName=".$puzzleName);
                  }
                  echo "<meta http-equiv=\"refresh\" content=\"0;URL=admin.php\">";
                }
                else{
                }
              }
            }
            else {
              echo "Not a valid user account";
            } 
          }else if (($user == 'admin') && ($pass == 'admin')){
            session_start();
            $_SESSION['valid_admin'] = $user;
            $expire = time()+60*60*24*30; //1 month
            //setcookie("admin", $user, $expire);
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=admin.php\">";
          } else if (($user == null) && ($pass != null)){
            echo "Username field is blank";
          }else if (($user != null) && ($pass == null)){
            echo "Password field is blank";
          } else if (($user == null) && ($pass == null)){
            echo "Username & Password field is blank";
          }	else{
            //false info
            echo "<b>Username or Password is wrong.";
          }
        }
        echo "</br>";
        ?>
        <font class='text'>Email* </font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input class='textbox' type='text' name='user' id='user_email'><br><br>
        <font class='text'>Password* </font>
        <input class='textbox' type='password' name='pass' id='password'><br><br>
        <div class='loginbutton' align="center">
          <input class="addButton" id="addButton" type="submit" name="submit" value="Login">
        </div>
    </form>
    <div class='message' align="center">
      <div class='messageText' align="center">
        Don't have an account? <a href=''>Create One!</a><br> Forgot Password? <a href=''>Request a reset!</a></div>
    </div>
  </div>
</body>

</html>
