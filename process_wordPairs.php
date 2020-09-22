<?php
    include 'db_configuration.php';
	include 'puzzleGenerator.php';


    if($_GET['synonym'] == ''){		
        //echo 'You left one of the two fields blank.<br>';
       // echo '<a href="./wordPairs.php"><button>Return to add synonyms</button></a>';
       // echo '<a href="./index.php"><button>Return Home</button></a>';
		header('Location: failed_page.php?x='.'You left one of the two fields blank.');
		return;
    }         
        
        $inputSynonym = $_GET['synonym']; //this will be a CSV string, needs to become String array
        
        $allSynonymsTemp = explode(',', $inputSynonym);
		$allSynonyms = cleanSpaces($allSynonymsTemp);
		if (sizeof($allSynonyms) < 1){
			//echo 'You typed only one word, should be minimum 2.<br>';
			//echo '<a href="./wordPairs.php"><button>Return to add synonyms</button></a>';
			//echo '<a href="./index.php"><button>Return Home</button></a>';
			header('Location: failed_page.php?x='.'You did not typed any word.');
			return;
		}
		
        $inputWord = preg_replace('/\s+/', '', $allSynonyms[0]);
        //echo "'".$allSynonyms[0]."' and '".$allSynonyms[1]."'<br>";
		
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