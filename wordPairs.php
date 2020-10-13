<?php

  $nav_selected = "ADMIN"; 
  $left_buttons = "YES"; 
  $left_selected = "ADD SYNONYMS"; 

  include("./nav.php");
  global $db;

  ?>

<?php
    include 'db_configuration.php';
	include 'puzzleGenerator.php';
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WORD PAIRS</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	
	<style>
		
		
		
</style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via  -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container">
	
	<div class="row">    
		<?php
			//include 'header.php';
		?>		
			
    </div>
   
   <div class="row">

       
   
    
    
    <?php
        //shows a form 
    
    
        /*
        //This PHP code block will be to get the data from the database to create the puzzle.
            //It handles both situations where the puzzle already exists or if it doesn't.
        $inputWord = htmlspecialchars($_GET['title']);
        //echo 'For debugging: <br>';
        //echo $inputWord . "<br>";
        $query = "SELECT * FROM puzzle WHERE PuzzleWord = '".$inputWord."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        $stmt->bind_result($id, $word, $synWords, $indexes);
    
        //These 3 arrays are what is used to create the puzzle
        //intialization:
        $synArray = array();
        $indexArray = array();
        $clueArray = array();
    
        //if it exists in DB already.
        if($numOfRows > 0){
            //echo 'exists in DB <br>';
            $tempWordsArray = array();
            $tempIndexArray = array();
            $tempClueArray = array();
            
            while($stmt->fetch()){
                array_push($tempWordsArray, $synWords);
                //echo 'Current index for word "'.$synWords.'" is: '.$indexes.'<br>';
                array_push($tempIndexArray, $indexes);
            }
            //echo 'num of rows = '.$numOfRows.'<br>';
            $rand = rand(0, $numOfRows-1);
            //echo $rand.' = $rand<br>';
            
            $selectedWordArray = $tempWordsArray[$rand];
            //echo 'Selected Index: '.$selectedWordArray.'<br>';
            $selectedIndexArray = $tempIndexArray[$rand];
            //echo 'Selected Index: '.$selectedIndexArray.'<br>';
            
            $synArray = array_map('trim', explode(',', $selectedWordArray));
            $indexArray = array_map('trim', explode(',', $selectedIndexArray));
            
            //now we create the clue array from the given $synArray
            for($i = 0; $i < count($synArray); $i++){
                
                $clueQuery = "SELECT SynonymWord FROM synonyms WHERE SynID = (SELECT ClueID FROM synonyms WHERE SynonymWord = '".$synArray[$i]."')";
                //$clueQuery = "SELECT SynonymWord FROM synonyms WHERE SynID = ".$thisClueID;
                
                $clueStmt = $db->prepare($clueQuery);
                $clueStmt->execute();
                $clueStmt->store_result();
                $clueStmt->bind_result($thisClueWord);
                //echo 'Clue value "'.$thisClueWord. '" associated with '.$synArray[$i].'<br>';
                while($clueStmt->fetch()){
                    array_push($tempClueArray, $thisClueWord);
                }
                //echo $thisClueWord;
                //echo $synArray[$i].' clue is: '.$thisClueWord.'<br>';
                $clueStmt->free_result();
                
            }            
            $clueArray = $tempClueArray;
            //echo $synArray[0] . ' clue is: ' . $clueArray[0].'<br>';
            //echo $synArray[1] . ' clue is: ' . $clueArray[1].'<br>';
            //echo $synArray[2] . ' clue is: ' . $clueArray[2].'<br>';
            //echo $synArray[3] . ' clue is: ' . $clueArray[3].'<br>';
            
			
        }else{//if it doesn't already exist.
            //echo "doesn't exist in DB";
            //initialization:
                //create $input which equals user input string
                //create $synWords
                //create $indexes
            $charArray = str_split($inputWord);
            $arraySize = count($charArray);
            
            for($i = 0; $i < $arraySize; $i++){
                $tempWordArray = array();
                $tempIndexArray = array();
                $wordQuery = "SELECT PuzzleWord, IndexNumber FROM characters WHERE letter = '".$charArray[$i]."'"; //might need to get clues too
                $wordStmt = $db->prepare($wordQuery);
                $wordStmt->execute();
                $wordStmt->store_result();
                $wordStmt->bind_result($selectedWord, $indexOfWord);
                $numberOfRows = 0;
                while($wordStmt->fetch()){
                    array_push($tempWordArray, $selectedWord);
                    array_push($tempIndexArray, $indexOfWord);
                    $numberOfRows++;
                }
                //echo 'Number of rows = '.$numberOfRows.'<br>';
                $rand = rand(0, $numberOfRows-1); //probably not working
                array_push($synArray, $tempWordArray[$rand]);
                array_push($indexArray, $tempIndexArray[$rand]);
                //echo 'Selected word associated with letter "'.$charArray[$i].'" is "'.$synArray[$i].'" at index '.$indexArray[$i].'<br>';
                
                $wordStmt->free_result();
            }
            
            //build clue word array down here.
            for($i = 0; $i < count($synArray); $i++){
                //$tempClueArray = array();
                //$clueQuery = "SELECT ClueWord FROM synonyms WHERE SynonymWord = '".$synArray[$i]."'";
                $clueQuery = "SELECT SynonymWord FROM synonyms WHERE SynID = (SELECT ClueID FROM synonyms WHERE SynonymWord = '".$synArray[$i]."')";
                $clueStmt = $db->prepare($clueQuery);
                $clueStmt->execute();
                $clueStmt->store_result();
                $clueStmt->bind_result($thisClueWord);
                //echo 'Clue value "'.$thisClueWord. '" associated with '.$synArray[$i].'<br>';
                //this would give bad results if multiple synonym words are allowed to have multiple clues.
                while($clueStmt->fetch()){
                    array_push($clueArray, $thisClueWord);
                }
                //echo $thisClueWord;
                //echo $synArray[$i].' clue is: '.$thisClueWord.'<br>';
                $clueStmt->free_result();
            }
            
            //create puzzle database entry
            //make CSV strings out of indexes from $synArray and $indexArray
            $inputSynString = rtrim(implode(',', $synArray), ',');
            $inputIndexesString = rtrim(implode(',', $indexArray), ',');
            echo $inputWord.'<br>';
            echo $inputSynString.'<br>';
            echo $inputIndexesString.'<br>';
            //query
            
            $puzzleInputQuery = "INSERT INTO puzzle(PuzzleID, PuzzleWord, PuzzleSynWords, IndexesToReveal) VALUES(DEFAULT, 
            '".$inputWord."', '".$inputSynString."', '".$inputIndexesString."')";
            $puzzleInputStmt = $db->prepare($puzzleInputQuery);
            $puzzleInputStmt->execute();
            $puzzleInputStmt->close();
                     
        }
        $stmt->close();
		
		*/
    ?>

    <div class="panel panel-primary" style="margin-top: 30px">
        <div class="Please enter the following settings">
			<form id="word-pair-form" method="get" action="process_wordPairs.php">
            <table>
			<tr><th>Synonyms</th></tr>
                <?php
                /*
                for($i = 0; $i < strlen($inputWord); $i++){
                    echo '<tr><td>'.$clueArray[$i].'</td><td data-answer="'.$synArray[$i].'">'.splitSynonym($synArray[$i], $indexArray[$i]).'</td></tr>';
					echo "\n";
                    //echo $indexArray[$i];
                }
                */
                
                /*
                for($i = 0; $i < 5; $i++){
                    echo '<tr><td><input type="text" name="words['.$i.']"></td><td><input type="text" name="synonyms"></td></tr>';
                    
					echo "\n";
                    //echo $indexArray[$i];
                }
                */
                
                echo '<tr><td><input style="width: 100%" type="text" name="synonym"></td></tr>';
                
                /*
                echo '<tr><td><input type="text" name="word1"></td><td><input type="text" name="synonym1"></td></tr>';
                echo '<tr><td><input type="text" name="word2"></td><td><input type="text" name="synonym2"></td></tr>';
                echo '<tr><td><input type="text" name="word3"></td><td><input type="text" name="synonym3"></td></tr>';
                echo '<tr><td><input type="text" name="word4"></td><td><input type="text" name="synonym4"></td></tr>';
                echo '<tr><td><input type="text" name="word5"></td><td><input type="text" name="synonym5"></td></tr>';
                   */ 
				echo "\n";
                
                //echo '<tr><td>second word</td><td>first synonym</td></tr>';
                //echo '<tr><td>third word</td><td>first synonym</td></tr>';
                //echo '<tr><td>fourth word</td><td>first synonym</td></tr>';
                //echo '<tr><td>fifth word</td><td>first synonym</td></tr>';
                ?>
			
			</table>
				
			</form>			
        </div>
		
		
    </div>
	<button class='sub' form="word-pair-form" type="submit">Submit Pairs</button>
	<a class='sub' id='homesub' href="index.php">Return Home</a>
	<br />
	<br />
	
	</div>
</div>

<script type="text/javascript">
    function submitPairs(){
        var words = document.getElementsByName('words');
        var synonyms = document.getElementsByName('synonyms');
        //alert(words[1].value);
        
        for(i = 0; i < words.length; i++){
            if(words[i].value != "" && synonyms[i].value != ""){
                //database insert
            }
        }
        
        
        
    }
</script>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/puzzle.js"></script>
</body>
</html>