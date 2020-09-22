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
    <title>Puzzles</title>
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
	<div class="container">
	<div class="row">    
		<?php
			include 'header.php';
		?>		
			
    </div>
    <div class="row">

        
    
    
    <?php
        //This PHP code block will be to get the data from the database to create the puzzle.
            //It handles both situations where the puzzle already exists or if it doesn't.
        $puzzle_id = htmlspecialchars($_GET['title']);
		$no = htmlspecialchars($_GET['id']);
		//$inputWord = preg_replace('/\x20/', '', $inputWord);
        //echo 'For debugging: <br>';
        //echo $inputWord . "<br>";
		//$generate = false;
		
		
		if ($no == 'Generate') {
			//$query = "SELECT * FROM puzzle WHERE PuzzleWord = '".$inputWord."'";
		} else {
			$query = "SELECT * FROM puzzles WHERE puzzle_id = '".$puzzle_id."'";
			$stmt = $db->prepare($query);
			$stmt->execute();
			$stmt->store_result();
			$numOfRows = $stmt->num_rows;
			$stmt->bind_result($no, $puzzleId, $name, $owner,$left,$right,$postion,$left_word,$character,$right_word,$submitted_by);
			$generate = false;
		}
        
    /*
        //These 3 arrays are what is used to create the puzzle
        //intialization:
        $synArray = array();
        $indexArray = array();
        $clueArray = array();
    
        //if it exists in DB already.
        if($generate){
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
            
               
            $clueArray = getClues($db, $synArray);
            //echo $synArray[0] . ' clue is: ' . $clueArray[0].'<br>';
            //echo $synArray[1] . ' clue is: ' . $clueArray[1].'<br>';
            //echo $synArray[2] . ' clue is: ' . $clueArray[2].'<br>';
            //echo $synArray[3] . ' clue is: ' . $clueArray[3].'<br>';
			
			$stmt->close();
            
			
        }else{//if it doesn't already exist.
            //echo "doesn't exist in DB";
            //initialization:
                //create $input which equals user input string
                //create $synWords
                //create $indexes
            $charArray = utf8Split($inputWord);
            $arraySize = count($charArray);
			$wordExists = true;
			$words = array();
            
            for($i = 0; $i < $arraySize; $i++){
                $tempWordArray = array();
                $tempIndexArray = array();
                $wordQuery = "SELECT PuzzleWord, IndexNumber FROM characters WHERE letter = '".$charArray[$i]."' ORDER BY RAND();"; //might need to get clues too
                $wordStmt = $db->prepare($wordQuery);
                $wordStmt->execute();
                $wordStmt->store_result();
                $wordStmt->bind_result($selectedWord, $indexOfWord);
				//echo $wordQuery;
				//echo '<br />';
				//Check if a character exists in DB
				/*
				if ($wordStmt->num_rows == 0) {
					$wordExists = false;
					include 'generate_puzzle_failed.htm';
					return;
				}
				*/
				
                /*$numberOfRows = 0;
                if ($wordStmt->num_rows > 0) {
					while($wordStmt->fetch()){
						//echo $selectedWord.', ';
						if (in_array($selectedWord, $words)) {
							//echo '111111111111111';
						} else {
							array_push($tempWordArray, $selectedWord);
							array_push($tempIndexArray, $indexOfWord);							
							$numberOfRows++;
						}
					}
					if ($numberOfRows == 0) {
						array_push($tempWordArray, $charArray[$i]);
						array_push($tempIndexArray, 0);
						$numberOfRows++;
					}
				}				
				else {
					array_push($tempWordArray, $charArray[$i]);
					array_push($tempIndexArray, 0);
					$numberOfRows++;
				}
				//echo '<br />';
                //echo 'Number of rows = '.$numberOfRows.'<br>';
                $rand = rand(0, $numberOfRows-1); //probably not working
                array_push($synArray, $tempWordArray[$rand]);
                array_push($indexArray, $tempIndexArray[$rand]);
				array_push($words, $tempWordArray[$rand]);
                //echo 'Selected word associated with letter "'.$charArray[$i].'" is "'.$synArray[$i].'" at index '.$indexArray[$i].'<br>';
                
                $wordStmt->free_result();
            }
            
            $clueArray = getClues($db, $synArray);
            
            //create puzzle database entry
            //make CSV strings out of indexes from $synArray and $indexArray
            $inputSynString = rtrim(implode(',', $synArray), ',');
            $inputIndexesString = rtrim(implode(',', $indexArray), ',');
            //echo $inputWord.'<br>';
            //echo $inputSynString.'<br>';
            //echo $inputIndexesString.'<br>';
            //query
            
            $puzzleInputQuery = "INSERT INTO puzzle(PuzzleID, PuzzleWord, PuzzleSynWords, IndexesToReveal) VALUES(DEFAULT, 
            '".$inputWord."', '".$inputSynString."', '".$inputIndexesString."')";
            $puzzleInputStmt = $db->prepare($puzzleInputQuery);
            $puzzleInputStmt->execute();
            $puzzleInputStmt->close();
                     
        }
        */
	
    ?>

    <div class="ToughPanel" style="margin-top: 30px">
        <div> <h3>Tough Version</h3>
			
            <table>
			<tr><th>Left Word</th><th>Character</th><th>Right Word</th></tr>
               <?php
				$output = '';
				
                while($stmt->fetch()){
                    $output .= '<tr><td>'.$left_word.'</td><td>'.''.'</td><td>'.''.'</td></tr>';
					$output .= "\n";
                    //echo $indexArray[$i];
                }
				
				echo $output;
                //echo '<tr><td>second word</td><td>first synonym</td></tr>';
                //echo '<tr><td>third word</td><td>first synonym</td></tr>';
                //echo '<tr><td>fourth word</td><td>first synonym</td></tr>';
                //echo '<tr><td>fifth word</td><td>first synonym</td></tr>';
               
                ?>
			
			</table>
            <!--input type='image' name='id'src='..\\img\\metro.gif' style='width:520px;height:520px;'-->;
				
						
        </div>
		
		
    </div>

    <div class="EasyPanel" style="margin-top: 30px">
        <div> <h3>Easy Version</h3>
			
        <table>
			<tr><th>Left Word</th><th>Character</th><th>Right Word</th></tr>
               <?php
				$output = '';
				$stmt->data_seek(0);
                while($stmt->fetch()){
                    $output .= '<tr><td>'.$left_word.'</td><td>'.''.'</td><td>'.$right_word.'</td></tr>';
					$output .= "\n";
                    //echo $indexArray[$i];
                }
				
				echo $output;
                //echo '<tr><td>second word</td><td>first synonym</td></tr>';
                //echo '<tr><td>third word</td><td>first synonym</td></tr>';
                //echo '<tr><td>fourth word</td><td>first synonym</td></tr>';
                //echo '<tr><td>fifth word</td><td>first synonym</td></tr>';
               
                ?>
			
			</table>				
					
        </div>
		
		
    </div>

    <div class="Solution" style="margin-top: 30px">
        <div> <h3>Solution</h3>
			
        <table>
			<tr><th>Left Word</th><th>Character</th><th>Right Word</th></tr>
               <?php
				$output = '';
				$stmt->data_seek(0);
                while($stmt->fetch()){
                    $output .= '<tr><td>'.$left_word.'</td><td>'.$character.'</td><td>'.$right_word.'</td></tr>';
					$output .= "\n";
                    //echo $indexArray[$i];
                }
				
				echo $output;
                //echo '<tr><td>second word</td><td>first synonym</td></tr>';
                //echo '<tr><td>third word</td><td>first synonym</td></tr>';
                //echo '<tr><td>fourth word</td><td>first synonym</td></tr>';
                //echo '<tr><td>fifth word</td><td>first synonym</td></tr>';
               
                ?>
			
			</table>
						
        </div>
		
		
    </div>
	<button class='sub' onclick="submitSolution()">Submit Solution</button>
	<button class='sub' onclick="showSolution()">Show Solution</button>
	<button class='sub' onclick="changeInput()">Change Input Mode</button>
	<a class='sub' id='homesub' href="index.php">Return Home</a>
	<br />
	<br />
	
	</div>
</div>
</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/puzzle.js"></script>
</body>
</html>