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
//error_reporting(E_ERROR);

?>

<?php

$nav_selected  = "ADMIN";
$left_buttons  = "YES";
$left_selected = "CREATENiN";

include("./nav.php");
global $db;

?>

<?php
include 'db_configuration.php';
//include 'puzzleGenerator.php';

?>

<?php


ini_set("log_errors", 1);
ini_set("error_log", "synonyms_errors.log");



if (isset($_GET['puzzleWord']))
   // $word = htmlspecialchars($_POST['puzzleWord']);
    $word = validate_input($_GET['puzzleWord']);
   
error_log("The input word is : " . $word);


$loop = 4; //puzzle loop************************************************************************************************8

for ($a = 0; $a <= $loop; $a++) { 


$xx = $a+1;


$y = "puzzle ";
$z = "#";


for ($x = 0; $x < $xx; $x++) {
    $r= $x+1;
    
  
$htmlTable       = '<div class="container"><h1 style="color:red;">"'. $y . $word . $z .  $r . '"</h1><table class="table table-condensed main-tables" id="puzzle_table" ><thead><tr><th>Clue</th><th>Word</th></tr></thead><tbody>';

$htmlTableResult = '<div class="container"><h1 style="color:red;">"'. $y . $word . $z .  $r . '"</h1><table class="table table-condensed main-tables" id="puzzle_table" ><thead><tr><th>Clue</th><th>Word</th></tr></thead><tbody>';

}
// get the logical characters (Spliting the input into logical characters)
$wordCharacters    = getWordChars($word);
$wordCharacterSize = count($wordCharacters);

error_log("It contains " . $wordCharacterSize . " characters");

// this is to keep track of the used words for all the characters
// CAT
$usedWords = array();

// final_clue_words (which are displayed in the puzzle on the right)
$final_clue_words = array();

foreach ($wordCharacters as $char) {
    
    $pos   = array_search($char, $wordCharacters) + 1;
    $query = "SELECT SynonymWord FROM synonyms WHERE SynonymWord LIKE '%$char%'";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    
    $stmt->bind_result($wordsWithChar);
    
    // $arrayWord is list of words containing our logical character
    $arrayWord = array();
       
    while ($stmt->fetch()) {
        
        $db_word_chars = getWordChars($wordsWithChar);
        if (in_array($char, $db_word_chars)) {
            array_push($arrayWord, $wordsWithChar);
        }
        
    } // end while
    $stmt->close();

    
    error_log(" This is the trimmed collection for ". $char);
    // error_log(implode($arrayWord, ","));

    // Keep on visiting the our db word collection until we identify a unique word
    $word_found = false;
    $k=0;
    foreach($arrayWord as $visited_word)
    {
        if (!in_array($visited_word, $usedWords, true)) {
            $word_found = true;
            $tempselectedWord = $visited_word;
           // break;
            
        }
        $k++;
        if ($k == $xx) {
            break;
          }
          
   }
   

   if ($word_found == true)
   {
      // now you add the visitd_word to the used word
      array_push($usedWords, $tempselectedWord);
   } else {
    // else, the tempselected word above has no master word so, a new
    // random tempselectedWord most be chosen.
    
    $query = "SELECT SynonymWord FROM synonyms WHERE SynonymWord LIKE '%$char%'";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    //$numOfRows = $stmt->num_rows;
    $stmt->bind_result($wordsWithChar);
    $arrayWord = array();
    
    //echo "Words containing the letter ", $char, " in our database are:" ."<br>";
    
    
    while ($stmt->fetch()) {
        
        echo " This are the words with the char", $wordsWithChar . "<br>";
        $db_word_chars = getWordChars($wordsWithChar);
        if (in_array($char, $db_word_chars)) {
            array_push($arrayWord, $wordsWithChar);
            
            //echo $wordsWithChar. "<br>";
        }
        
    }
    echo " This is the trimmed collection";
    var_dump($arrayWord);
    $stmt->close();

    
    
    // echo "Out of these, the word randomly selected is: ";
    
    $arrayWordLen = count($arrayWord);
    
    
     $tempselectedWord = null;
    while (true) {
        if ($arrayWordLen < 1) {
            break;
        } elseif ($arrayWordLen > 1) {
            $random = rand(0, $arrayWordLen - 1);
        } elseif ($arrayWordLen === 1) {
            $random = 0;
        }
        try {
            $tempWord = $arrayWord[$random];
            if (!in_array($tempWord, $usedWords, true)) {
                $tempselectedWord = $arrayWord[$random];
                unset($arrayWord[$random]);
                break;
            } else {
                $arrayWordLen--;
                array_splice($arrayWord, $random, 1);
            }
        }
        catch (Exception $e) {
            // try again
            $arrayWordLen--;
        }
         array_push($usedWords, $tempselectedWord);
    }
    
}


   error_log("The words selected for each character are: ");
   error_log(implode($usedWords, ","));

    
    // Get the ClueID (which is the iD of the master word) for the selected word
    $clueIDQuery = "SELECT ClueID FROM synonyms WHERE SynonymWord Like '$tempselectedWord'";
    $stmt        = $db->prepare($clueIDQuery);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($clueID);
    $stmt->fetch();
    $stmt->close();

    
    // this is to keep track of the CLUES we display for the user
    $clue_word_collection_for_selected_word = array();

    // Fetch all the words that are synonyms of the ClueID
    $masterWordQuerySet = "SELECT SynonymWord FROM synonyms WHERE ClueID = $clueID";
    $stmt               = $db->prepare($masterWordQuerySet);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($masterWordSynonyms);
    $stmt->fetch();
    while ($stmt->fetch()) {
        array_push($clue_word_collection_for_selected_word, $masterWordSynonyms);
    };
    

    // Keep on visiting the our master word collection until we identify a unique final clue word
    $clue_word_found = false;
     foreach($clue_word_collection_for_selected_word as $visited_clue_word)
     {
         if (!in_array($visited_clue_word, $final_clue_words, true)) {
             $clue_word_found = true;
             $temp_selected_clue_word = $visited_clue_word;
             $masterWord = $visited_clue_word; // in order to move the Word characters into the Word Table we must add the $masterWord (Changes) 6/29/21
             break;
         };
    };

   if ($clue_word_found == true)
   {
      // now you add the visitd_word to the used word
      array_push($final_clue_words, $temp_selected_clue_word, $masterWord); // $masterWord variable must be added in the array parameter (Changes) 6/29/21

    } else
   {
       // this is an error because we don't have enough words in the database
       // then we show just the letter as-is
       // TODO: WOrry about the special condition later on
       
   }



   $tempSelectedWordchars = getWordChars($tempselectedWord);
   $pos                   = array_search($char, $tempSelectedWordchars) + 1;
   $len                   = count($tempSelectedWordchars);
   $newpos                = $pos - 1;
   
   // storing value for the clue;
   $stmt->close();

   $masterWordQuery = "SELECT SynonymWord FROM synonyms WHERE SynID = '$clueID' AND ClueID = '$clueID'";
   $stmt            = $db->prepare($masterWordQuery);
   $stmt->execute();
   $stmt->store_result();
   $stmt->bind_result($masterWord);
   $stmt->fetch();
   
   array_push($usedWords, $masterWord);
   //echo $masterWord;



       // $htmlTable .= "<tr><td align='center' style='vertical-align: middle;'>" . $temp_selected_clue_word.  "</td>";
       // $htmlTableResult .= "<tr><td align='center' style='vertical-align: middle;'>" . $temp_selected_clue_word . "</td>";
        
        //master word represents the synonym in the table
        //$htmlTable .= "<td align='center' style='vertical-align: middle;'>" . $masterWord . "</td>"; // uncomment this table to pull char data from the db (Changes) 6/29/21
        
       //$htmlTableResult .= "<td align='center' style='vertical-align: middle;'>" . $masterWord . "</td>";
        
       $htmlTable .= "<tr><td align='center' style='vertical-align: middle;'>" . $masterWord.  "</td>";
       $htmlTableResult .= "<tr><td align='center' style='vertical-align: middle;'>" . $masterWord . "</td>";
        /**
         * Now, printing out the the letter to be revealed in the word 
         * plus empty spaces to be filled by the user. 
         */
        $htmlTable .= "<td style='vertical-align: middle;'>";
        
        $htmlTableResult .= "<td style='vertical-align: middle;'>";
        $flag = false;
        for ($j = 0; $j < $len; $j++) {
            $htmlTableResult .= '<input class="puzzleInput word_char active" type="text" maxLength="7" value="' . $tempSelectedWordchars[$j] . '" style="display:inline" readonly/>';
            if (($j === $newpos) && !$flag) {
                $htmlTable .= '<input class="puzzleInput word_char active" type="text" maxLength="7" value="' . $tempSelectedWordchars[$j] . '" style="display:inline" readonly/>';
                
                $flag = true;
            } else {
                $htmlTable .= '<input class="puzzleInput word_char" type="text" maxLength="7" value="" style="display:inline"/>';
                
                //$htmlTableResult .= '<input class="puzzleInput word_char" type="text" maxLength="7" value="" style="display:inline"/>';
            }
        }
        $htmlTable .= '</td>';
        $htmlTableResult .= '</td>';}
        

    //array_push($usedWords, $masterWord);
    
 // end of for loop

$htmlTable .= "</div>";
$htmlTable .= '</tbody></table><img id="success_photo" class="success" src="pic/thumbs_up.png" alt="Success!" style="display:none"></div>';

$htmlTableResult .= "</div>";
$htmlTableResult .= '</tbody></table><img id="success_photo" class="success" src="pic/thumbs_up.png" alt="Success!" style="display:none"></div>';

echo $htmlTable;


//echo $masterWord;
//echo $htmlTableResult;

//createTableFooter();
var_dump($usedWords);
    }


?>

<?php

    if(isset($_POST['result'])){
        echo $htmlTableResult;

    }
?>


<form  method="post">
<div class="container" ><button class='main-buttons' input type="submit" name="result" value="HTML"> Answer </button>
</div>
</form>
<div class="container" ><button class='main-buttons' onclick="window.location.href='index.php';">Return Home</button>


</div>
</br>
</br>

</body>

</html>
Download Form