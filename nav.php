<?php
  require_once('initialize.php');
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles/main_style.css" type="text/css">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- jQuery library -->
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="styles/custom_nav.css" type="text/css">
        <title>Synonyms</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="./mainStyleSheet.css">
    </head>

<body class="body_background">
<div id="wrap">
    <div id="nav">
        <ul>
            <a href="index.php">
              <li class="horozontal-li-logo">
              <img src ="./images/silcHeader.png">
              <br/>Name in Synonyms</li>
            </a>

            <a href="index.php">
              <li <?php if($nav_selected == "PLAY"){ echo 'class="current-page"'; } ?>>
              <img src="./images/play.png">
              <br/>Play</li>
            </a>

            <a href="list.php">
              <li <?php if($nav_selected == "SUBMIT PUZZLE"){ echo 'class="current-page"'; } ?>>
                <img src="./images/submit puzzle.png">
                <br/>Submit Puzzle</li>
            </a>

            <a href="admin.php">
              <li <?php if($nav_selected == "ADMIN"){ echo 'class="current-page"'; } ?>>
              <img src="./images/admin.png">
              <br/>Admin</li>
            </a>

            <a href="login.php">
              <li <?php if($nav_selected == "LOGIN"){ echo 'class="current-page"'; } ?>>
                <img src="./images/login.png">
                <br/>Login</li>
            </a>

        <a href="about.php">
          <li <?php if($nav_selected == "ABOUT"){ echo 'class="current-page"'; } ?>>
            <img src="./images/about.png">
            <br/>About</li>
        </a>

      </ul>
      <br />
    </div>


    <table style="width:1250px">
      <tr>
        <?php
            if ($left_buttons == "YES") {
        ?>

        <td style="width: 120px;" valign="top">
        <?php
            if ($nav_selected == "PLAY") {
                include("./index.php");
            } elseif ($nav_selected == "SUBMIT PUZZLE") {
                include("./left_menu_list.php");
            } elseif ($nav_selected == "ADMIN") {
                include("./left_menu_admin.php");
            } elseif ($nav_selected == "ABOUT") {
                include("./left_menu_reports.php");
            } elseif ($nav_selected == "LOGIN") {
                include("./login.php");
            } elseif ($nav_selected == "ABOUT") {
          include("./left_menu_about.php");
          }else {
                include("./left_menu.php");
            }
        ?>
        </td>
        <td style="width: 1100px;" valign="top">
        <?php
          } else {
        ?>
        <td style="width: 100%;" valign="top">
        <?php
          }
        ?>
