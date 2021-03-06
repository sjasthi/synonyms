<?php
    include 'db_configuration.php';
    include 'puzzleGenerator.php';
    

    $nav_selected = "ADMIN"; 
    $left_buttons = "YES"; 
    $left_selected = "CREATENIN"; 
  
    include("./nav.php");

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Puzzle</title>
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
			//include 'header.php';
		?>		
			
    </div>
    <div class="row">

    <?php
        //define functions
        
    ?>
        

    <div class="panel panel-primary" style="margin-top: 30px">
        <div class="Please enter the following settings">
			<form>
            <table>
			<tr><th>Clues</th> <th>Synonyms</th></tr>
                <?php
                class puzzle{
                    
                    //These 3 arrays are what is used to create the puzzle
                    //intialization:
                    private $synArray = array();
                    private $indexArray = array();
                    private $clueArray = array();

                    //runs functions based on post info sent to itself
                    private $charArray = array();
                
                function generate($inputWord, $db, $min, $max){
                    echo $min.' -- '.$max.'<br>';
                    
                    $query = "SELECT * FROM puzzle WHERE PuzzleWord = '".$inputWord."'";
               
                    $stmt = $db->prepare($query);
                    $stmt->execute();
                    $stmt->store_result();
                    $numOfRows = $stmt->num_rows;
                    $stmt->bind_result($id, $word, $synWords, $indexes);
                    
                    $this->charArray = utf8Split($inputWord);
                    
                    $arraySize = count($this->charArray);
			        $wordExists = true;
			        $words = array();
            
                    for($i = 0; $i < $arraySize; $i++){
                        $tempWordArray = array();
                        $tempIndexArray = array();
                        $wordQuery = "SELECT PuzzleWord, IndexNumber FROM characters WHERE letter = '".$this->charArray[$i]."' AND LENGTH(PuzzleWord) > ".$min." AND LENGTH(PuzzleWord) < ".$max." ORDER BY RAND();"; 
                        $wordStmt = $db->prepare($wordQuery);
                        $wordStmt->execute();
                        $wordStmt->store_result();
                        $wordStmt->bind_result($selectedWord, $indexOfWord);


                        $numberOfRows = 0;
                        if ($wordStmt->num_rows > 0) {
                            while($wordStmt->fetch()){

                                if (in_array($selectedWord, $words)) {

                                } else {
                                    array_push($tempWordArray, $selectedWord);
                                    array_push($tempIndexArray, $indexOfWord);							
                                    $numberOfRows++;
                                }
                            }
                            if ($numberOfRows == 0) {
                                array_push($tempWordArray, $this->$charArray[$i]);
                                array_push($tempIndexArray, 0);
                                $numberOfRows++;
                            }
                        }				
                        else {
                            array_push($tempWordArray, $this->$charArray[$i]);
                            array_push($tempIndexArray, 0);
                            $numberOfRows++;
                        }

                        $rand = rand(0, $numberOfRows-1); //probably not working
                        array_push($this->synArray, $tempWordArray[$rand]);
                        array_push($this->indexArray, $tempIndexArray[$rand]);
                        array_push($words, $tempWordArray[$rand]);

                        $wordStmt->free_result();
                    }

                    $this->clueArray = getClues($db, $this->synArray);

                    $inputSynString = rtrim(implode(',', $this->synArray), ',');
                    $inputIndexesString = rtrim(implode(',', $this->indexArray), ',');
					/*
                    $puzzleInputQuery = "INSERT INTO puzzle(PuzzleID, PuzzleWord, PuzzleSynWords, IndexesToReveal) VALUES(DEFAULT, 
                    '".$inputWord."', '".$inputSynString."', '".$inputIndexesString."')";
                    $puzzleInputStmt = $db->prepare($puzzleInputQuery);
                    $puzzleInputStmt->execute();
                    $puzzleInputStmt->close();
					*/
                }
                    
                function getSynArray(){
                    return $this->synArray;
                }
                    
                function getIndexArray(){
                    return $this->indexArray;
                }
                
                function getClueArray(){
                    return $this->clueArray;
                }
                    
                function getCharArray(){
                    return $this->charArray;
                }
                
                
                }
                
                ?>
                
                
                
                <?php
                    //run functions here
                    $inputWord = htmlspecialchars($_GET['title']);
                    $id = htmlspecialchars($_GET['id']);
                    $inputWord = preg_replace('/\x20/', '', $inputWord);
                    //echo 'For debugging: <br>';
                    //echo $inputWord . "<br>";
                
                    $thisPuzzle = new puzzle;


                    $min = 1;
                    $max = 20;
                    if(isset($_GET['min'])){            
                        $min = $_GET['min'];
                    }
                    if(isset($_GET['max']) ){
                        $max = $_GET['max'];
                    }
                    
                
                    $thisPuzzle->generate($inputWord, $db, $min, $max);
                    
                    echo "Synonyms: ";
                    print_r($thisPuzzle->getSynArray());
                    echo "<br>";
                    echo "Clues: ";
                    print_r($thisPuzzle->getClueArray());
                    echo "<br>";
                    echo "Indexes: ";
                    print_r($thisPuzzle->getIndexArray());
                    echo "<br>";
                    echo "Characters: ";
                    print_r($thisPuzzle->getCharArray());
                    
                    $synArray = $thisPuzzle->getSynArray();
                    $clueArray = $thisPuzzle->getClueArray();
                    $indexArray = $thisPuzzle->getIndexArray();
                
                    $output = '';
				
                    for($i = 0; $i < telugu_strlen($inputWord, 'UTF-8'); $i++){
                        $output .= '<tr><td>'.$clueArray[$i].'</td><td data-answer="'.$synArray[$i].'">'.splitSynonym($synArray[$i], $indexArray[$i]).'</td></tr>';
					    $output .= "\n";
                    //echo $indexArray[$i];
                    }
				
				echo $output;
                
                ?>
			
			</table>
				
			</form>			
        </div>
		
		
    </div>
    <form action="design_puzzle.php" method="get">
    
    <?php
                
        echo 'set min = '.$min.'<br>';
        echo 'set min = '.$max.'<br>';
        
        
        echo 'Show synonyms between <input type="text" name="min">';
     
        echo 'and <input type="text" name="max">';
       
        echo 'characters';
        
        echo '<input type="hidden" name="title" value="'.$inputWord.'"><br>';
        echo '<input type="submit" class="btn btn-primary" name="id" value="Generate">';
        
    ?>
    </form>
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