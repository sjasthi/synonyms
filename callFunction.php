<?php
	//Gets clues for puzzle.php
	include("puzzleGenerator.php");

    $inputWord = $_REQUEST['inputWord'];
    
    //if(isset($_GET['a']))
      //  $inputWord = htmlspecialchars($_GET['a']);

    $inputWord = preg_replace('/\x20/', '', $inputWord);
    
    $charArray = utf8Split($inputWord);
    $charArray = $inputWord;
    
    return $charArray[0];
    ?>
	