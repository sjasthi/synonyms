<?php
    include 'db_configuration.php';        
	$obj = json_decode($_GET["x"], false);
	$positions = "";
	$synonyms = "";
		
		//print_r($obj);
		//echo $obj->synonyms;
	for ($x = 0; $x < sizeof($obj->synonyms); $x++) {
		if ($x == (sizeof($obj->synonyms) - 1)) {
			$synonyms .= $obj->synonyms[$x];
			$positions .= $obj->positions[$x];
		}else {
			$synonyms .= $obj->synonyms[$x].", ";
			$positions .= $obj->positions[$x].", ";
		}
	}
	//echo $synonyms;
	//echo $positions;
        
    $wordQuery = "INSERT INTO `puzzle` (`PuzzleID`, `PuzzleWord`, `PuzzleSynWords`, `IndexesToReveal`) VALUES
					(NULL, '".$obj->name."', '".$synonyms."', '".$positions."')";
					//echo $wordQuery;
    $wordStmt = $db->prepare($wordQuery);
    $wordStmt->execute();
    $wordStmt->store_result();
    
    include 'add_puzzle_success.htm';            
	$wordStmt->free_result();
	$db->close();
?>

    