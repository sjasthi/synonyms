<!DOCTYPE html>
<?php

$puzzleID = $_POST['puzzleId'];
$puzzleWord = $_POST['puzzleWord'];
//$puzzleSynWords = $_POST['puzzleSynWords']
//$indexes = $_POST['indexes'];

?>
<html>
	
  <head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
   <title>Edit Puzzle Page</title>
  </head>
  <body>
	<div class="container">
		<div class="row">    
			<?php
				include 'header.php';
			?>		
				
		</div>
		
		<?php
		include 'db_configuration.php';
		include 'puzzleGenerator.php';
	
        //This PHP code block will be to get the data from the database to create the puzzle.
            //It handles both situations where the puzzle already exists or if it doesn't.
        $id = htmlspecialchars($_POST['puzzleId']);
		$inputWord = htmlspecialchars($_POST['puzzleWord']);
        //echo 'For debugging: <br>';
        //echo $inputWord . "<br>";
		/*
        $query = "SELECT * FROM puzzle WHERE PuzzleWord = '".$inputWord."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        $stmt->bind_result($id, $word, $synWords, $indexes);
    */
        //These 3 arrays are what is used to create the puzzle
        //intialization:
		
		$query = "SELECT * FROM puzzle WHERE PuzzleID = '".$id."'";
		$stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        $stmt->bind_result($id, $word, $synWords, $indexes);
		if($numOfRows == 0) {
			include 'generate_puzzle_failed.htm';
			return;
		}
		
        $synArray = array();
        $indexArray = array();
        $clueArray = array();       
			
        
        $charArray = utf8Split($inputWord);			
        $arraySize = count($charArray);
		
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
		
		
    ?>
	
		<div class="row">
			<h1>Edit Puzzle Page</h1>	
			<hr />
			
			<div class="" style="margin-top: 30px">
				<div class="Please enter the following settings">
					<form data-name="<?php echo $inputWord;?>" data-id="<?php echo $id;?>">
					<table>
					<tr><th>No</th><th>Character</th><th>Synonyms</th><th>Clues</th></tr>
						<?php
						for($i = 0; $i < telugu_strlen($inputWord, 'UTF-8'); $i++){
							echo '<tr class="pairs"><td>'.($i+1)."</td><td class='char'>".$charArray[$i]."</td><td>".makeInputs($synArray[$i]).'</td><td data-answer="'.$synArray[$i].'">'.makeInputs($clueArray[$i]).'</td></tr>';
							//echo '<tr class="pairs"><td>'.($i+1)."</td><td class='char'>".$charArray[$i]."</td><td>".'<input class="inputs" type="text" name="" size="20" maxlength="20" data-char="'.'" value="'.'"/>'."\n".'</td><td data-answer="'.'">'.'<input class="inputs" type="text" name="" size="20" maxlength="20" data-char="'.'" value="'.'"/>'."\n";'</td></tr>';
							echo "\n";
							//echo $indexArray[$i];
						}
						//echo '<tr><td>second word</td><td>first synonym</td></tr>';
						//echo '<tr><td>third word</td><td>first synonym</td></tr>';
						//echo '<tr><td>fourth word</td><td>first synonym</td></tr>';
						//echo '<tr><td>fifth word</td><td>first synonym</td></tr>';
						?>
					
					</table>
					<br />
					<br />
					<br />
					</form>	
					<div class="text-center">
						<button class='sub' onclick="updatePuzzle()">Update Puzzle</button>
						<a class='sub' id='' href="index.php">Return Home</a>
					</div>
						
				</div>			
			</div>		
		</div>
	</div>	
    
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/puzzle.js"></script>
  </body>
</html>