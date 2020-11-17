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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles/custom_nav.css" type="text/css">
    <title>Play Synonyms</title>
</head>

<body>

<?php
require_once('myFunctions.php');
require_once('language_processor_functions.php');
require('create_a_puzzle.php');

?>

<?php

  $nav_selected = "ADMIN"; 
  $left_buttons = "YES"; 
  $left_selected = "CREATENiN"; 

  include("./nav.php");
  global $db;

  ?>

<?php
   // include 'db_configuration.php';
	//include 'puzzleGenerator.php';
?>

<?php 

if (isset($_POST['puzzle'])) {
    $input = preg_replace("/\r\n/", ",", validate_input($_POST['puzzle']));
    // Verify the input value provided meets our requirements
    if ($input == '') {
      // If input is empty, go back to one_to_many page
      echo '<script type="text/javascript">alert("You did not enter any words. Please try again!"); ';
    } if (count(explode(",", trim($input))) > 1) {
      // If input contains more than one words, go back to previous page
      echo '<script type="text/javascript">alert("You can only enter one word. Please try again"); ';
    } else {
      // Display preferences
      echo '<div id="optionContainer" class="optionDiv" style="display: block;" align="center">';
      echo '<div id="displayPreferences">';
      echo '<lable><b style="font-size: 20px;">Image Display Preference: </b></lable>';
      echo '<input type="radio" name="showSynonym" value="Show Images" checked onclick="toggleImage()" /><label>Show Images</label>';
      echo '<input type="radio" style="margin-left:15px;" name="showSynonym" value="Mask Images" onclick="toggleImage()" /><label>Mask Images</label>';
      echo '<input type="radio" style="margin-left:15px;" name="showSynonym" value="Show Numbers Only" onclick="toggleImage()" /><label>Show Numbers Only</label>';
      echo '<input type="radio" style="margin-left:15px;" name="showSynonym" value="Mask Letters Only" onclick="toggleImage()" /><label>Show Letters Only</label>';
      echo '</div>';

      echo '<div id="answerPreferences">';
      echo '<lable><b style="font-size: 20px;">Answer Display Preference: </b></lable>';
      echo '<input type="radio" name="showAnswers" value="Do Not Show Answers" checked onclick="toggleAnswer()" /><label>Do Not Show Answers</label>';
      echo '<input type="radio" style="margin-left:15px;" name="showAnswers" value="Show Answers Below the Image" onclick="toggleAnswer()" /><label>Show Answers Below the Image</label>';
      echo '<input type="radio" style="margin-left:15px;" name="showAnswers" value="Show Answers At the end of the page" onclick="toggleAnswer()" /><label>Show Answers At the end of the page</label>';
      echo '</div>';

      echo '<div id="rowSizePreference">';
      echo '<lable><b style="font-size: 20px;">Number of images per row: </b></lable>';
      echo '<input type="radio" name="radioBtn" id="sizeOne" onclick="changeTableRow(id)" value="1" /><label> &nbsp 1 &nbsp &nbsp</label>';
      echo '<input type="radio" name="radioBtn" id="sizeTwo" onclick="changeTableRow(id)" value="2" /><label> &nbsp 2 &nbsp &nbsp</label>';
      echo '<input type="radio" name="radioBtn" id="sizeThree" onclick="changeTableRow(id)" value="3" /><label> &nbsp 3 &nbsp &nbsp</label>';
      echo '<input type="radio" name="radioBtn" id="sizeFour" checked onclick="changeTableRow(id)" value="4" /><label>&nbsp 4 &nbsp &nbsp</label>';
      echo '<input type="radio" name="radioBtn" id="sizeFive" onclick="changeTableRow(id)" value="5" /><label> &nbsp 5 &nbsp &nbsp</label>';
      echo '<input type="radio" name="radioBtn" id="sizeSix" onclick="changeTableRow(id)" value="6" /><label>&nbsp 6 &nbsp &nbsp</label>';
      echo '<input type="radio" name="radioBtn" id="sizeSeven" onclick="changeTableRow(id)" value="7" /><label>&nbsp 7 &nbsp &nbsp</label>';
      echo '<input type="radio" name="radioBtn" id="sizeEight" onclick="changeTableRow(id)" value="8" /><label>&nbsp 8 &nbsp &nbsp</label>';
      echo '<input type="radio" name="radioBtn" id="sizeNine" onclick="changeTableRow(id)" value="9" /><label>&nbsp 9 &nbsp &nbsp</label>';
      echo '<input type="radio" name="radioBtn" id="sizeTen" onclick="changeTableRow(id)" value="10" /><label>&nbsp 10 &nbsp &nbsp</label>';
      echo '</div>';
      echo '</div>';

      //echo '<h3 style="color:green;"><input type="checkbox" name="answer" onclick="toggleAnswer()">Show Answer</h3>';

      // Display the puzzles generated for given word
      $puzzles = explode(",", trim($input));
      $wordList = array(); // we will use this to keep track of words being used so no repetition occurs
      $allAnswers = "";
      foreach ($puzzles as $puzzleWord) {
        echo '<div class="container"><h1 style="color:red;">Find the words for "' . $puzzleWord . '"</h1>';
        $puzzleChars = getWordChars($puzzleWord);
        $generate = true;
        $counter = 0;
        //$allAnswers .= "<h1>Answers for ".$puzzleWord.": </h1>";
        $allAnswers .="<h2 style='color: green;'> Answer for Puzzle: \"".$puzzleWord."\"</h2>";
        while ($generate) {
          $word_array = array();
          $synonym_array = array();
          //$image_array = array();
          for ($i = 0; $i < count($puzzleChars); $i++) {
            $word = getRandomWord($puzzleChars[$i], $wordList);
            if (!empty($word)) {
              array_push($word_array, $word['word']);
              array_push($wordList, $word['word']);
              array_push($synonym_array, $word['synonym']);
              //array_push($image_array, $word['image']);
            } else {
              array_push($word_array, $puzzleChars[$i]);
              array_push($synonym_array, "");
              //array_push($image_array, "");
            }
          }

          //if ($generate) {
          $counter++;
          $allAnswers .="<h2 style='color: green;'>Puzzle #".$counter."</h2>";
          echo '<h1>Puzzle #' . $counter . '</h1>';
          echo '<table class="table" id="print_table" border="0" style="width: auto">';
          for ($i = 0; $i < count($puzzleChars); $i++) {
            $word_chars = getWordChars($word_array[$i]);
            $pos = array_search($puzzleChars[$i], $word_chars) + 1;
            $len = count($word_chars);
            $synonym = $synonym_array[$i];
            //$image = getImageIfExists($image_array[$i]);
            $word = $word_array[$i];
            if ($i === 0) {
              echo '<tr >';
            } else if ($i % 4 === 0) {
              echo '</tr border="0"><tr>';
            }
            echo "<td align='center' style='border-top: none; vertical-align: bottom;'>
            <h1 class='letters' style='display:none;'> $puzzleChars[$i] </h1>
            <div align='center' class='answerDiv'><h3>" . $word . "</h3></div></div>
            <figcaption class=\"print-figCaption\">" . $pos . '/' . $len . "</figcaption>
            <div align='center' class='answerDiv'><h3>" . $word . "</h3></div></td>";
            
            $allAnswers .= "<h5>".$word."</h5>";
          }
          echo '</tr>';
          echo '</table>';
          //  }
        }

      }
      //  $allAnswers = preg_replace("</td>","",$allAnswers);
      // echo $allAnswers;
      echo '<div name="allAnswers" style="display:none"><h3>'.$allAnswers.'</h3></div>';
    }
  }
  ?>

  <script>
  //function toggleImage() {
  function toggleSynonym(){
    var show = document.getElementsByName('showSynonym');
    //var images = document.getElementsByClassName('print-img');
    var letters = document.getElementsByClassName('letters');
    var chars = document.getElementsByClassName('chars');
    var masks = document.getElementsByClassName('maskSynonym');

    if (show[1].checked) {
      for (i = 0; i < synonyms.length; i++) {
        synonyms[i].style.display = 'none';
      }
      for (i = 0; i < masks.length; i++) {
        masks[i].style.display = 'block';
      }
      for (i = 0; i < chars.length; i++) {
        chars[i].style.display = 'none';
      }
      for (i = 0; i < letters.length; i++) {
        letters[i].style.display = 'none';
      }
    } else if (show[2].checked) {
      for (i = 0; i < synonyms.length; i++) {
        synonyms[i].style.display = 'none';
      }
      for (i = 0; i < masks.length; i++) {
        masks[i].style.display = 'none';
      }
      for (i = 0; i < chars.length; i++) {
        chars[i].style.display = 'none';
      }
      for (i = 0; i < letters.length; i++) {
        letters[i].style.display = 'none';
      }
    } else if (show[3].checked) {
      for (i = 0; i < synonyms.length; i++) {
        synonyms[i].style.display = 'none';
      }
      for (i = 0; i < masks.length; i++) {
        masks[i].style.display = 'none';
      }
      for (i = 0; i < chars.length; i++) {
        chars[i].style.display = 'block';
      }
      for (i = 0; i < letters.length; i++) {
        letters[i].style.display = 'block';
      }
    } else {
      for (i = 0; i < images.length; i++) {
        images[i].style.display = 'block';
      }
      for (i = 0; i < masks.length; i++) {
        masks[i].style.display = 'block';
      }
      for (i = 0; i < chars.length; i++) {
        chars[i].style.display = 'block';
      }
      for (i = 0; i < letters.length; i++) {
        letters[i].style.display = 'none';
      }
    }
  }

  function toggleAnswer() {
    var options = document.getElementsByName('showAnswers');
    var x = document.getElementsByClassName('answerDiv');
    var allAnswers = document.getElementsByName("allAnswers");
    if (options[1].checked) {
      for (i = 0; i < x.length; i++) {
        x[i].style.display = 'block';
      }
      allAnswers[0].style.display = 'none';
    } else if(options[2].checked) {
      for (i = 0; i < x.length; i++) {
        x[i].style.display = 'none';
      }
      allAnswers[0].style.display = 'block';
    } else {
      for (i = 0; i < x.length; i++) {
        x[i].style.display = 'none';
      }
      allAnswers[0].style.display = 'none';
    }
  }


  function showHideOptions(){
    var options = document.getElementById('optionContainer');
    if(options.style.display === 'none'){
      options.style.display = 'block';
    }
    else{
      options.style.display = 'none';
    }
  }

  function changeTableRow(id){
    var size = document.getElementById(id).value;
    var tables = document.getElementsByClassName("table");

    for(i = 0; i < tables.length; i++){
      var table = tables[i];

      var j = 0;
      var done = 0;
      var rowObject = table.rows[j];
      var diff  = rowObject.cells.length - size;

      //Making rows larger
      if(diff < 0){
        while(done === 0){
          while(table.rows[j].cells.length < size){
            table.rows[j].insertCell(table.rows[j].cells.length).innerHTML = table.rows[j+1].cells[0].innerHTML;

            table.rows[j+1].deleteCell(0);
            if(table.rows[j+1].cells.length === 0){
              table.deleteRow(j+1);
            }
          }
          j++;
          try{ var next = table.rows[j].cells.length;
            if (next < size && table.rows[j+1].cells.length < size){
            }
          }catch{done = 1;}
        }
      }
      //Make rows smaller
      else {
        while(done === 0){
          //  alert("here");
          while(table.rows[j].cells.length > size ){
            //alert(table.rows[j+1].cells.length);
            try{
              if(table.rows[j+1].cells.length !== 0 ){}
            }catch{table.insertRow(j+1);}
            table.rows[j+1].insertCell(0).innerHTML = table.rows[j].cells[table.rows[j].cells.length-1].innerHTML;
            table.rows[j].deleteCell(table.rows[j].cells.length-1);
          }
          j++;
          try{ var next = table.rows[j].cells.length;

            if (table.rows[j].cells.length !== 0){

            }else{done = 1;}
          }catch{done = 1;}
        }
      }
    }
  }



  </script>

  <style>
    .table tbody tr td {
      vertical-align: bottom;
      border-top: none;
      text-align: center;
    }
    .print-figCaption {
      margin-left: 25%;
    }
  </style>

</div>
</body>
</html>
