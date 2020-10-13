<?php

  $nav_selected = "ADMIN"; 
  $left_buttons = "YES"; 
  $left_selected = "LIST SYNONYMS"; 

  include("./nav.php");
  global $db;

  ?>


<?php
    include 'db_configuration.php';
	include 'puzzleGenerator.php';

	
    if($_POST['synonym'] == ''){		
        //echo 'You left one of the two fields blank.<br>';
       // echo '<a href="./wordPairs.php"><button>Return to add synonyms</button></a>';
       // echo '<a href="./index.php"><button>Return Home</button></a>';
		//header('Location: failed_page.php?x='.'You left one the field blank.');
		//return;
    }         
       
        $inputSynonym = $_POST['synonym']; //this will be a CSV string, needs to become String array
		$oldSynonym = explode(', ', $_POST['old']);
		$ID = explode(', ', $_POST['id']);
        
        $allSynonymsTemp = explode(',', $inputSynonym);
		$allSynonyms = cleanSpaces($allSynonymsTemp);
		/*
		if (sizeof($allSynonyms) < 2){
			//echo 'You typed only one word, should be minimum 2.<br>';
			//echo '<a href="./wordPairs.php"><button>Return to add synonyms</button></a>';
			//echo '<a href="./index.php"><button>Return Home</button></a>';
			header('Location: failed_page.php?x='.'You typed only one word, should be minimum 2.');
			return;
		}
		*/
        $inputWord = $allSynonyms[0];
        //echo "'".$allSynonyms[0]."' and '".$allSynonyms[1]."'<br>";
		
		for($x = 0; $x < sizeof($ID); $x++) {			
			
			deleteSyn($ID[$x], $db);
			deleteChar($oldSynonym[$x], $db);
		}
		if($_POST['synonym'] == ''){		
        //echo 'You left one of the two fields blank.<br>';
       // echo '<a href="./wordPairs.php"><button>Return to add synonyms</button></a>';
       // echo '<a href="./index.php"><button>Return Home</button></a>';
		header('Location: failed_page.php?x='.'Synonyms were deleted.');
		return;
    }   
		
		$wordExists = checkFKExistence($inputWord, $db);
        //echo $wordExists;
		if ($wordExists > 0) {
			updateWord($db, $wordExists, $allSynonyms);
		} else {
			addWord($allSynonyms, $db);
		}	
	
        
        //header('Location: ./index.php');
        //header('Location: ./wordPair_success.html');
        include 'add_pair_success.htm';
        //end of this page
        
        
        
    

    

?>