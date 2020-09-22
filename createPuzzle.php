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
    <title>Add Puzzle</title>
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

       <h1>Adding a new puzzle</h1>
		<hr />
    
    <?php
	
        //This PHP code block will be to get the data from the database to create the puzzle.
            //It handles both situations where the puzzle already exists or if it doesn't.
        $inputWord = htmlspecialchars($_GET['name']);
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
        $synArray = array();
        $indexArray = array();
        $clueArray = array();       
			
        
            $charArray = utf8Split($inputWord);			
            $arraySize = count($charArray);
            
           
		
		
    ?>

    <div class="" style="margin-top: 30px">
        <div class="Please enter the following settings">
			<form data-name="<?php echo $inputWord?>">
            <table>
			<tr><th>No</th><th>Character</th><th>Synonyms</th><th>Clues</th></tr>
                <?php
                for($i = 0; $i < telugu_strlen($inputWord, 'UTF-8'); $i++){
                    //echo '<tr class="pairs"><td>'.($i+1)."</td><td class='char'>".$charArray[$i]."</td><td>".makeInputs($synArray[$i]).'</td><td data-answer="'.$synArray[$i].'">'.makeInputs($clueArray[$i]).'</td></tr>';
					echo '<tr class="pairs"><td>'.($i+1)."</td><td class='char'>".$charArray[$i]."</td><td>".'<input class="inputs" type="text" name="" size="20" maxlength="20" data-char="'.'" value="'.'"/>'."\n".'</td><td data-answer="'.'">'.'<input class="inputs" type="text" name="" size="20" maxlength="20" data-char="'.'" value="'.'"/>'."\n";'</td></tr>';
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
			<div class="text-center">
				
			</div>
			</form>			
        </div>
		
		
    </div>
	
	<button class='sub' onclick="addPuzzle()">Add Puzzle</button>
				<a class='sub' id='' href="index.php">Return Home</a>
				
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