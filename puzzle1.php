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

?>

<?php

  $nav_selected = "ADMIN"; 
  $left_buttons = "YES"; 
  $left_selected = "CREATENiN"; 

  include("./nav.php");
  global $db;

  ?>

<?php
    include 'db_configuration.php';
	//include 'puzzleGenerator.php';
?>
<div id="pop_up_fail" class="pop_up" style="display:none">
    <div class="pop_up_background">

        <img class="pop_up_img_fail" alt="fail puzzle pop up" src="pic/info_circle.png">
        <div class="pop_up_text">Incorrect! <br>Try Again!</div>
        <button class="pop_up_button" onclick="change_display_none('pop_up_fail')">OK</button>
    </div>
</div>


<?php 

if(isset($_POST['puzzleWord']))

            //$word = htmlspecialchars($_POST['puzzleWord']);
            $word = validate_input($_POST['puzzleWord']);
            //$parsedWord = getWordChars($word);


            //echo "Input Word:", $word . "<br>";

            $htmlTable = '<div class="container"><h1 style="color:red;">"' . $word . '"</h1><table class="table table-condensed main-tables" id="puzzle_table" ><thead><tr><th>Clue</th><th>Synonym</th><th>Word</th></tr></thead><tbody>';
            $htmlTableResult = "<div class='container'><h1 style='color:red;'>'' . $word . ''</h1><table class='table table-condensed main-tables' id='puzzle_table' ><thead><tr><th>Clue</th><th>Synonym</th><th>Word</th></tr></thead><tbody>";
            //echo $htmlTable;
           // $wordCharacters = str_split($word);
            $wordCharacters = getWordChars($word);
            $wordCharacterSize = count($wordCharacters);
            //$showworsCharacters = implode(',', $wordCharacters);
          
            //echo "It contains ", $wordCharacterSize, " characters: ";
            //echo $showworsCharacters . "<br>";
            $usedWords = array();
            $myWords = array();// This will store the words ofr the solution
    foreach ($wordCharacters as $char){

              //$tempselectedWord = null;
              //$masterWord = null;
              $pos = array_search($char, $wordCharacters) + 1;
             
              $query = "SELECT SynonymWord FROM synonyms WHERE SynonymWord LIKE '%$char%'";
                
              $stmt = $db->prepare($query);
              $stmt->execute();
              $stmt->store_result();
              //$numOfRows = $stmt->num_rows;
              $stmt->bind_result($wordsWithChar);
              $arrayWord = array();

              //echo "Words containing the letter ", $char, " in our database are:" ."<br>";


              while($stmt->fetch()){

                //echo " This are the words with the char", $wordsWithChar. "<br>";
                $db_word_chars = getWordChars($wordsWithChar);
                if (in_array($char, $db_word_chars)){
                    array_push($arrayWord, $wordsWithChar);
                    
                    //echo $wordsWithChar. "<br>";
                }
            
            }
            //echo " This is the trimmed collection";
              //var_dump($arrayWord);
              $stmt->close();

           // echo "Out of these, the word randomly selected is: ";
            
            $arrayWordLen = count($arrayWord);
          

         while (true) {
                //echo 'Rows count: ' . $numRows . ' <br>';
                if ($arrayWordLen < 1) {
                    break;
                } elseif ($arrayWordLen > 1) {
                    $random = rand(0, $arrayWordLen - 1);
                } elseif ($arrayWordLen === 1) {
                    $random = 0;
                }
                //echo $random;
                try {
                    $tempWord = $arrayWord[$random];
                    if (!in_array($tempWord, $usedWords, true) And ($tempWord !== $word)) {
                        $tempselectedWord = $tempWord;//$arrayWord[$random];
                        
                        //unset($arraWord[$random]);
                        break;
                    } else {
                        $arrayWordLen--;
                        array_splice($arrayWord, $random, 1);
                    }
                } catch (Exception $e) {
                    // try again
                    $arrayWordLen--;
                }
         }
        
            //echo $tempselectedWord, 'This is the word selected';


            $clueIDQuery = "SELECT ClueID FROM synonyms WHERE SynonymWord Like '$tempselectedWord'"; 
            $stmt = $db->prepare($clueIDQuery);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($clueID);
            $stmt->fetch();
            //echo  $clueID. "<br>";
            $stmt->close();


            $masterWordQuery = "SELECT SynonymWord FROM synonyms WHERE SynID = '$clueID' AND ClueID = '$clueID'"; 
            $stmt = $db->prepare($masterWordQuery);
            $stmt->execute();
            $stmt->store_result();
            //$numOfRows = $stmt->num_rows;
            $stmt->bind_result($masterWord);
            $stmt->fetch();
            


            /**
             * If the master word is different from the temp selected word then,
             *  the word has a master word.
             */
            if ($masterWord != $tempselectedWord){
                // select all words that have the same clueID as the selected word,
                // and add them to usedWords so, they can't be reused. Because, they
                // will all have the same master word.

                $masterWordQuerySet = "SELECT SynonymWord FROM synonyms WHERE ClueID = $clueID"; 
                $stmt = $db->prepare($masterWordQuerySet);
                $stmt->execute();
                $stmt->store_result();
                //$numOfRows = $stmt->num_rows;
                $stmt->bind_result($masterWordSynonyms);
                $stmt->fetch();
                while($stmt->fetch()){
                    array_push($usedWords, $masterWordSynonyms);
                    //echo $masterWordSynonyms. "<br>";
                 }

                $tempSelectedWordchars = getWordChars($tempselectedWord);
                $pos = array_search($char, $tempSelectedWordchars) + 1;
                $len = count($tempSelectedWordchars);
                $newpos = $pos -1;

                // storing value for the clue;


              $htmlTable .= "<tr><td align='center' style='vertical-align: middle;'>" . $pos . '/' . $len . "</td>";
              $htmlTableResult .= "<tr><td align='center' style='vertical-align: middle;'>" . $pos . '/' . $len . "</td>";

              //master word represents the synonym in the table
              $htmlTable .= "<td align='center' style='vertical-align: middle;'>" . $masterWord. "</td>";

              $htmlTableResult .= "<td align='center' style='vertical-align: middle;'>" . $masterWord. "</td>";

              /**
               * Now, printing out the the letter to be revealed in the word 
               * plus empty spaces to be filled by the user. 
               */
              $htmlTable .= "<td style='vertical-align: middle;'>";

              $htmlTableResult .= "<td style='vertical-align: middle;'>";
                      $flag = false;
                      for ($j = 0; $j < $len; $j++) {
                        $htmlTableResult .= "<input class='puzzleInput word_char active' type='text' maxLength='7' value='' .$tempSelectedWordchars[$j] . '' style=''display:inline' readonly/>";
                          if (($j === $newpos) && !$flag) {
                              $htmlTable .= '<input class="puzzleInput word_char active" type="text" maxLength="7" value="' . $tempSelectedWordchars[$j] . '" style="display:inline" readonly/>';

                              $flag = true;
                          } else {
                              $htmlTable .= '<input class="puzzleInput word_char" type="text" maxLength="7" value="" style="display:inline"/>';

                              //$htmlTableResult .= '<input class="puzzleInput word_char" type="text" maxLength="7" value="" style="display:inline"/>';
                          }
                      }
                      $htmlTable .='</td>';
                      $htmlTableResult .="</td>";

                      array_push($myWords, $tempselectedWord); 
                      
            }
            else{
          // else, the tempselected word above has no master word so, a new
          // random tempselectedWord most be chosen.
        

          $query = "SELECT SynonymWord FROM synonyms WHERE SynonymWord LIKE '%$char%'";
                
          $stmt = $db->prepare($query);
          $stmt->execute();
          $stmt->store_result();
          //$numOfRows = $stmt->num_rows;
          $stmt->bind_result($wordsWithMyChar);
          $arrayWord2 = array();

          //echo "Words containing the letter ", $char, " in our database are:" ."<br>";


          while($stmt->fetch()){

            //echo " This are the words with the char", $wordsWithChar. "<br>";
            $db_word_chars2 = getWordChars($wordsWithMyChar);
            if (in_array($char, $db_word_chars2)){
                array_push($arrayWord2, $wordsWithMyChar);
                
                //echo $wordsWithChar. "<br>";
            }
        
        }
        //echo " This is the trimmed collection";
          //var_dump($arrayWord);
          $stmt->close();

       // echo "Out of these, the word randomly selected is: ";
        
        $arrayWordLen = count($arrayWord2);


               // $tempselectedWord = null;
                while (true) {
                    if ($arrayWordLen < 1) {
                        break;
                    } elseif ($arrayWordLen > 1) {
                        $random = rand(0, $arrayWordLen - 1);
                    } elseif ($arrayWordLen === 1) {
                        $random = 0;
                    }
                    try {
                        $tempWord2 = $arrayWord[$random];
                        if (!in_array($tempWord2, $usedWords, true) And ($tempWord2 !== $word)) {
                            $tempselectedWord = $arrayWord2[$random];
                            //unset($arrayWord[$random]);
                            break;
                        } else {
                            $arrayWordLen--;
                            array_splice($arrayWord2, $random, 1);
                        }
                    } catch (Exception $e) {
                        // try again
                        $arrayWordLen--;
                    }
                   // array_push($usedWords, $tempselectedWord);
                }
            
                array_push($myWords, $tempselectedWord);
                // gettin the clueID for the new tempselected word

                $clueIDQuery = "SELECT ClueID FROM synonyms WHERE SynonymWord Like '$tempselectedWord'"; 
                $stmt = $db->prepare($clueIDQuery);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($clueID);
                $stmt->fetch();
                $stmt->close();
              
                $masterWordQuerySet = "SELECT SynonymWord FROM synonyms WHERE ClueID = $clueID"; 
                $stmt = $db->prepare($masterWordQuerySet);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($masterWordSynon);
                $stmt->fetch();
                while($stmt->fetch()){
                    array_push($usedWords, $masterWordSynon);
                    //echo $masterWordSynon. "<br>";
                 }
                
                $tempSelectedWordchars = getWordChars($tempselectedWord);
                $pos = array_search($char, $tempSelectedWordchars) + 1;
                $len = count($tempSelectedWordchars);
                $newpos = $pos -1;

                $stmt->close();


               $masterWordQuery = "SELECT SynonymWord FROM synonyms WHERE SynID = '$clueID' AND ClueID = '$clueID'"; 
               $stmt = $db->prepare($masterWordQuery);
               $stmt->execute();
               $stmt->store_result();
               $stmt->bind_result($masterWord);
               $stmt->fetch();

               //array_push($usedWords, $masterWord);
               //echo $masterWord;

              $htmlTable .= "<tr><td align='center' style='vertical-align: middle;'>" . $pos . '/' . $len . "</td>";
              $htmlTableResult .= "<tr><td align='center' style='vertical-align: middle;'>" . $pos . '/' . $len . "</td>";
          
              $htmlTable .= "<td align='center' style='vertical-align: middle;'>" . $masterWord. "</td>";
              $htmlTable .= "<td style='vertical-align: middle;'>";

              $htmlTableResult .= "<td align='center' style='vertical-align: middle;'>" . $masterWord. "</td>";
              $htmlTableResult .= "<td style='vertical-align: middle;'>";

                      $flag = false;
                      for ($j = 0; $j < $len; $j++) {
                      // $htmlTableResult .= "<input class='puzzleInput word_char' type='text' maxLength='7' value='' style='display:inline'/>";
                         $htmlTableResult .= "<input class='puzzleInput word_char active' type='text' maxLength='7' value='' . $tempSelectedWordchars[$j] . '' style='display:inline' readonly/>";
                          if (($j === $newpos) && !$flag) {
                              $htmlTable .= '<input class="puzzleInput word_char active" type="text" maxLength="7" value="' . $tempSelectedWordchars[$j] . '" style="display:inline" readonly/>';
                              $flag = true;
                          } else {
                              $htmlTable .= '<input class="puzzleInput word_char" type="text" maxLength="7" value="" style="display:inline"/>';

                              //$htmlTableResult .= '<input class="puzzleInput word_char" type="text" maxLength="7" value="" style="display:inline"/>';
                          }
                      }
                      $htmlTable .='</td>';
                      $htmlTableResult .="</td>";

            }  

        //array_push($usedWords, $masterWord);

       }

          $htmlTable .= "</div>";
          $htmlTable .= '</tbody></table><img id="success_photo" class="success" src="pic/thumbs_up.png" alt="Success!" style="display:none"></div>';

          $htmlTableResult .= "</div>";
          $htmlTableResult .= "</tbody></table><img id='success_photo' class='success' src='pic/thumbs_up.png' alt='Success!' style='display:none'></div>";


          $words = "";
          $string = "";
          $first = true;
          foreach ($myWords as $word){
              $word_chars = getWordChars($word);
      
              // this is for building a comma separate string of the words for the puzzle. For later use in javascript.
              if ($first) {
                 $wordLng = count($word_chars);
                 for ($i = 0; $i < $wordLng; ++$i) {
                     if ($i == 0) {
                       $string .= $word_chars[$i];
                    } else {
                       $string .= "-" . $word_chars[$i];
                     }
                 }
                 $words .= $string;
                 $first = false;
                 
              } else {
                $string = "";
                $wordLng = count($word_chars);
                 for ($i = 0; $i < $wordLng; ++$i) {
                     if ($i == 0) {
                       $string .= $word_chars[$i];
                    } else {
                       $string .= "-" . $word_chars[$i];
                     }
                 }
                  $words .= "," . $string;
              }
             
          }

          // the array $words contains the actual solution to the puzzle
          var_dump($words);

          var_dump($myWords);
          echo $htmlTable;
          //echo $masterWord;
        // echo $htmlTableResult;


createTableFooter();

function createTableFooter()
    {
        $buttons = '<div class="container" ><input class="main-buttons" type="button" name="submit_solution" 
                value="Submit Solution" onclick="main_buttons(\'submit\');">
                <input class="main-buttons" type="button" name="show_solution" 
                value="Show Solution" onclick="main_buttons(\'show\')";>
                <input class="main-buttons" type="button" name="changeInputMode" 
                value="Change Input Mode" onclick="change_puzzle_input()"> </div>';
        echo $buttons;
    }

?>
<script type="text/javascript">
    /**
     *     main function for the buttons when they're clicked.
     */

    function main_buttons(button_name) {
        // the words should be seperated by commas and the characters of the words by '-'.
        var words = "<?php echo $words//echo json_encode($words); ?>";
        var wordsArray = words.split(",");
        // get the table and it's length.
        var table = document.getElementById("puzzle_table");
        var tableLength = table.rows.length;
        // helper variables.
        var words_correct = true;
        var childrenLength = 0;

        // start at 1 because top row for the table is the header of the table.
        for (var i = 1; i < tableLength; i++) {
            // for submit_solution
            if (button_name == "submit") {
                // call submit_validation handler method for the submit solution button
                words_correct = submit_validation(table, wordsArray[i - 1], i);

                // break out of loop. If the next words is the last words and the user guessed it right,
                // then the words_correct would end up as true, even if one words was false.
                if (words_correct === false) {
                    break;
                }
            } else if (button_name == "show") { // for show solution
                // call show_solution handler method for the show solution button
                show_solution(table, wordsArray[i - 1], i);
            }
        }

        if (button_name == "submit") {
            // checks if the words are correct by passing in words_correct boolean flag.
            checkCorrect(words_correct);
        }
    }


    function show_solution(table, word, i) {
        var childrenLength = 0;
        var word_array = null;
        var nWord = word;

        word_array = nWord.split("-");
        childrenLength = table.rows[i].cells[2].children.length;
        if (table.rows[i].cells[2].children[1].value.length > 0) {
            clear_puzzle();
        }
        for (var j = 0; j < word_array.length; j++) {
            table.rows[i].cells[2].children[1].value += word_array[j];
        }
        for (var j = 0; j < childrenLength - 2; j++) {
            var k = j + 2;
            table.rows[i].cells[2].children[k].value = word_array[j];
        }
    }

    function clear_puzzle() {
        var table = document.getElementById("puzzle_table");
        var tableLength = table.rows.length;
        var childrenLength = 0;

        for (var i = 1; i < tableLength; i++) {
            childrenLength = table.rows[i].cells[2].children.length;
            for (var j = 0; j < childrenLength; j++) {
                if (!(table.rows[i].cells[2].children[j].className.includes("active"))) {
                    table.rows[i].cells[2].children[j].value = "";
                }
            }
        }
    }

    function checkCorrect(words_correct) {
        if (words_correct) { // success case
            //alert("Sucess!");
            var el = document.getElementById("success_photo");
            el.style.display = "inline";
        } else { // failure case
            var el = document.getElementById("pop_up_fail");
            el.style.display = "block";
            clear_puzzle();
        }
    }

 //validation the word
 function submit_validation(table, word, i) {
        var input_word = "";
        var alt_input_word = "";
        var theWord = rebuildWord(word);
        var childrenLength = table.rows[i].cells[2].children.length;

        alt_input_word += table.rows[i].cells[2].children[1].value;
        for (var j = 0; j < childrenLength - 2; j++) {
            var k = j + 2;
            input_word += table.rows[i].cells[2].children[k].value;
        }

        if (theWord != input_word && theWord != alt_input_word) {
            return false;
        } else {
            return true;
        }
    }

    // rebuild the words whose charactes are seperated by "-".
    function rebuildWord(word) {
        var built_word = "";
        var word_characters = word.split("-");
        var array_length = word_characters.length;

        for (var i = 0; i < array_length; ++i) {
            built_word += word_characters[i];
        }
        return built_word;
    }

    function change_display_none(o) {
        var el = document.getElementById(o);
        el.style.display = "none";
    }

    function toggle_display(el) {
        if (el.style.display == "inline") {
            el.style.display = "none";
        } else {
            el.style.display = "inline";
        }
    }

    function change_puzzle_input() {
        var alt = document.getElementsByClassName("altPuzzleInput");
        var i;
        for (i = 0; i < alt.length; i++) {
            toggle_display(alt[i]);
        }
        var norm = document.getElementsByClassName("puzzleInput");
        var i;
        for (i = 0; i < norm.length; i++) {
            toggle_display(norm[i]);
        }
    }


</script>

</body>

</html>
