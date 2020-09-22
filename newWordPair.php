<?php
	
	include 'db_configuration.php';
	
	$synonym = htmlspecialchars($_GET['synonym']);
	$clue = htmlspecialchars($_GET['clue']);
	$synonymIndex = 0;
	$clueIndex = 0;
	$bothNew = false;
	//echo $synonym;
	
	$wordQuery = "SELECT SynID,SynonymWord, ClueID FROM synonyms WHERE SynonymWord = '".$synonym."'"; 
    $wordStmt = $db->prepare($wordQuery);
    $wordStmt->execute();
    $wordStmt->store_result();
    $wordStmt->bind_result($synID, $clueWord, $clueID);
	
	if ($wordStmt->num_rows > 0) {
		
		while($wordStmt->fetch()){
			$synonymIndex = $synID;
		}
		
		$clueQuery = "SELECT SynID,SynonymWord, ClueID FROM synonyms WHERE SynonymWord = '".$clue."'"; 
		$clueStmt = $db->prepare($clueQuery);
		$clueStmt->execute();
		$clueStmt->store_result();
		$clueStmt->bind_result($synID, $clueWord, $clueID);
		if ($clueStmt->num_rows > 0) {
					
		}else {
			
			$addQuery = "INSERT INTO `synonyms` VALUES (NULL, '".$clue."', ".$synonymIndex.")"; 
			$addClueStmt = $db->prepare($addQuery);			
			$addClueStmt->execute();
		}
	}
	else {
				
		
		$clueQuery = "SELECT SynID,SynonymWord, ClueID FROM synonyms WHERE SynonymWord = '".$clue."'"; 
		$clueStmt = $db->prepare($clueQuery);
		$clueStmt->execute();
		$clueStmt->store_result();
		$clueStmt->bind_result($synID, $clueWord, $clueID);
		
		if ($clueStmt->num_rows > 0) {
			while($clueStmt->fetch()){
				$clueIndex = $synID;
			}			
		}else {
			
			$addQuery = "INSERT INTO `synonyms` VALUES (NULL, '".$clue."', 2)"; 
			$addClueStmt = $db->prepare($addQuery);			
			$addClueStmt->execute();
			//get clueID
			$wordStmt->free_result();
			$wordQuery = "SELECT SynID,SynonymWord, ClueID FROM synonyms WHERE SynonymWord = '".$clue."'"; 
			$wordStmt = $db->prepare($wordQuery);
			$wordStmt->execute();
			$wordStmt->store_result();
			$wordStmt->bind_result($synID, $clueWord, $clueID);
			while($wordStmt->fetch()){
				$clueIndex = $synID;
			}
			$bothNew = true;
		}
		
		$addQuery = "INSERT INTO `synonyms` VALUES (NULL, '".$synonym."', ".$clueIndex.")"; 
		$addStmt = $db->prepare($addQuery);
		$addStmt->execute();
		
	}
	
	$wordStmt->free_result();
	
	if ($bothNew) {
		$wordStmt->free_result();
		$wordQuery = "SELECT SynID,SynonymWord, ClueID FROM synonyms WHERE SynonymWord = '".$synonym."'"; 
		$wordStmt = $db->prepare($wordQuery);
		$wordStmt->execute();
		$wordStmt->store_result();
		$wordStmt->bind_result($synID, $clueWord, $clueID);
		while($wordStmt->fetch()){
			$synonymIndex = $synID;
		}
		//update ClueID for a new synonym
		$wordStmt->free_result();
		$wordQuery = "UPDATE synonyms Set ClueID = ".$synonymIndex." WHERE SynonymWord = '".$clue."'"; 
		$wordStmt = $db->prepare($wordQuery);
		$wordStmt->execute();
	}
	
	checkCharactersEntry($synonym, $db);
	checkCharactersEntry($clue, $db);
	
	//echo $synonym;
	include 'add_pair_success.htm';
	
	function checkCharactersEntry($synonymWord, $db){
        //first check if the word isn't already in the characters table.
        $query = "SELECT PuzzleWord FROM characters WHERE PuzzleWord = '".$synonymWord."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        //$stmt->bind_result($thisSynID);
        //$stmt->fetch();
        if($numOfRows > 0){
            //characters already exist. return out of function
        }else{
                
                insertIntoCharacters($synonymWord, $db);
            
        }
        $stmt->free_result();
        $stmt->close();
        //get length of synonymWord
    }
	
	function insertIntoCharacters($synonymWord, $db){
        $characters = str_split($synonymWord, 1);
        
        $query = "INSERT INTO characters(PuzzleWord, IndexNumber, letter) VALUES('".$synonymWord."', 0, '".$characters[0]."'),";
                
        //build query
        for($i = 1; $i < strlen($synonymWord); $i++){
                
            $query = $query . "('".$synonymWord."', ".$i .", '".$characters[$i]."'), ";
                
                
        }
        $query = rtrim($query, ', ');
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->close();
    }
?>