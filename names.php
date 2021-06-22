<?php

  $nav_selected = "ADMIN"; 
  $left_buttons = "YES"; 
  $left_selected = "NAMES"; 

  include("./nav.php");
  global $db;

  ?>

<?php
  //include 'db_configuration.php';
	//include 'puzzleGenerator.php';
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/main_style.css" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/custom_nav.css" type="text/css">
	

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via  -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<title>Synonyms One-To-Many</title>
<div class="container">	
<div class="divTitle" align="center">
      <font class="font">Name Puzzle (one_to_many)</font>
    </div>
    <div>
      <form id ="myform" action="generatePuzzles.php" method="GET">
        <div class="container">
        <div class="inputDiv"><input type="textbox" name="puzzle" id="name-textbox"
                                         placeholder="Enter a word to generate multiple puzzles"
                                         onclick="this.placeholder = ''"/>
          </div>
          <br>
          <br>
          <div style="text-align:center">
            <button type="submit" name="submit" class="main-buttons">Generate Puzzles</button>
        </div>
          <div style="text-align:center">
          </div>
        </div>
      </form>
    </div>
    </div>
</body>
</html>