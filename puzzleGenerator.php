<?php
	//Gets clues for puzzle.php
	include("word_processor.php");
	
	function getSingleMode($synonym, $index) {	
		$answer = '';
		$language = 'telugu';
		$processed = new wordProcessor($synonym, $language);
		$characters = $processed->getLogicalChars();
		$answer .= $characters[$index].'('.($index+1).'/'.telugu_strlen($synonym).')';
		return $answer;
	}
	
	function utf8Split($str, $len = 1) {
				  
        //$characters = str_split($synonymWord, 1);
		$language = 'telugu';
		$processed = new wordProcessor($str, $language);
		$characters = $processed->getLogicalChars();		  
		  
		return $characters;
	}
	
	function telugu_strlen($str, $len = 1) {
		$language = 'telugu';
		$processed = new wordProcessor($str, $language);
		$characters = $processed->getLogicalChars();
		
		return count($characters);
	}
	
	function cleanSpaces($array) {
		$answer = array();
		for ($i = 0; $i < count($array); $i++) {
			//$answer[] = preg_replace('/\s+/', '', $array[$i]);
			$answer[] = preg_replace('/\x20/', '', $array[$i]);
		}
		return $answer;
	}
	
	function getClues ($db, $synArray) {
			$tempClueArray = array();
			
			//now we create the clue array from the given $synArray
            for($i = 0; $i < count($synArray); $i++){
                /*
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
                */
				$id = checkFKExistence($synArray[$i], $db);
				if ($id > 0) {
					$clueCandidates = getCluesArray($db, $id);
					array_push($tempClueArray, matchPair($synArray[$i], $clueCandidates));
				}
				else {
					array_push($tempClueArray, $synArray[$i]);
				}
            }
			return $tempClueArray;
	}
	
	//Finds the best pair, try to avoid the same pair of words
	function matchPair($word, $clueCandidates) {
		if (count($clueCandidates) > 1) {
			for($i = 0; $i < count($clueCandidates); $i++){
				if ($word != $clueCandidates[$i]) {
					return $clueCandidates[$i];
				}				
			}
		}else {
			return $clueCandidates[0];
		}
	}
	
	//Gets all synonyms for a word
	function getCluesArray ($db, $id) {
		$query = "SELECT SynonymWord FROM synonyms WHERE ClueID = ".$id;
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        $stmt->bind_result($thisSynID);
		$array = array();
        
		while($stmt->fetch()){
			//echo 'Current iteration ID: '.$thisSynID.'<br>';
			array_push($array, $thisSynID);
			//echo $thisSynID;
		}	
		
        $stmt->close();
        return $array;
        
	}
	
	function splitSynonym($synonym, $index) {
		$answer = '<input class="inputs hide answer" type="text" name="single" size="30" maxlength="30" />'."\n";
		$charArray = utf8Split($synonym);
        $arraySize = count($charArray);
		
		for($i = 0; $i < $arraySize; $i++){
			if ($i == $index) {
				$answer .= '<input class="inputs multi text-center blue" type="text" data-char="'.$charArray[$i].'" name="multi" size="1" maxlength="1" value="'.$charArray[$i].'" readonly/>'."\n";
			}
			else {				
				$answer .= '<input class="inputs multi text-center" type="text" name="multi" size="1" maxlength="1" data-char="'.$charArray[$i].'"/>'."\n";
			}
		}
		return $answer;
	}
	
	function splitSynonymAdmin($synonym, $index) {
		$answer = '<input class="inputs hide answer" type="text" name="single" size="30" maxlength="30" />'."\n";
		$charArray = utf8Split($synonym);
        $arraySize = count($charArray);
		
		for($i = 0; $i < $arraySize; $i++){
			if ($i == $index) {
				$answer .= '<input class="inputs multi text-center blue" type="text" data-char="'.$charArray[$i].'" name="multi" size="1" maxlength="1" value="'.$charArray[$i].'" readonly/>'."\n";
			}
			else {				
				$answer .= '<input class="inputs multi text-center" type="text" name="multi" size="1" maxlength="1" data-char="'.$charArray[$i].'" value="'.$charArray[$i].'"/>'."\n";
			}
		}
		return $answer;
	}
	
	function makeInputs ($name) {
		return '<input class="inputs" type="text" name="" size="20" maxlength="20" data-char="'.$name.'" value="'.$name.'"/>'."\n";
	}
	
	//helper functions
	function addWord ($allSynonyms, $db) {
		createSynonymEntry($allSynonyms[0], $db, 'NULL');
		$id = testSynonym($allSynonyms[0], $db);		
		updateSynonymEntry($db, $id, $id);		
		for ($x = 1; $x < sizeof($allSynonyms); $x++) {
			$exists = testSynonym($allSynonyms[$x], $db);
			if ($exists == null) {
				createSynonymEntry($allSynonyms[$x], $db, $id);
			}
		}
	}
	
	function updateWord ($db, $id, $words) {
		for ($x = 1; $x < sizeof($words); $x++) {
			$exists = testSynonym($words[$x], $db);
			if ($exists == null) {
				createSynonymEntry($words[$x], $db, $id);
			}
		}
	}
	
    function testSynonym($synonymWord, $db){
        
        //echo $synonymWord . ' ---- this is passed variable<br>';
        $query = "SELECT SynID FROM synonyms WHERE SynonymWord = '".$synonymWord."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        $stmt->bind_result($thisSynID);
        //$savedID = $thisSynID;
        //If synonym already exists as SynonymWord in DB
        //return the last entry if multiple words exist. loop through this
        if($numOfRows > 0){           
			
			//echo 'entered if statement<br>';
			while($stmt->fetch()){
				//echo 'Current iteration ID: '.$thisSynID.'<br>';
				$savedID = $thisSynID;
			}
			
			
            $stmt->close();
            return $savedID;
        }else{
            $stmt->close();
            return null;
        }
        
        
    }
    
    //this will be used to get first entry of a word in the database for a future entry that is associated with that word to use as it's ClueID
    function getFirstSynonym($synonymWord, $db){
        $query = "SELECT SynID FROM synonyms WHERE SynonymWord = '".$synonymWord."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        $stmt->bind_result($thisSynID);
        //$savedID = $thisSynID;
        $stmt->fetch();
        //If synonym already exists as SynonymWord in DB
        //return the last entry if multiple words exist. loop through this
        if($numOfRows > 0){
            $savedID = $thisSynID;
            
            $stmt->close();
            return $savedID;
        }else{
            $stmt->close();
            return null;
        }
    }

    //check if ClueID exists for the existing row so we don't rewrite over existing row with a ClueID already.
    function checkFKExistence($synonymWord, $db){
        //echo 'in checkFKExistence: '.$synonymWord.'<br>';
        $query = "SELECT ClueID FROM synonyms WHERE SynonymWord = '".$synonymWord."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($thisSynID);
        $stmt->fetch();
        $stmt->free_result();
        $stmt->close();
        $savedID = $thisSynID;
        
        if($savedID != null){			
            return $savedID;
        }else{			
            return -1;
        }
    }

    function updateSynonymEntry($db, $SynID, $ClueID){
        //just updates the ClueID column
        $query = "UPDATE synonyms SET ClueID = ".$ClueID." WHERE SynID = ".$SynID;
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->close();
        //echo 'updated row';
    }

    function createSynonymEntry($synonymWord, $db, $id){
        //add to Synonym table
        //use createCharactersEntry to update character table accordingly.
        
        //uncomment after debugging
		if($synonymWord == ''){
            return;
        }
        
        $query = "INSERT INTO synonyms(SynID, SynonymWord, ClueID) VALUES(DEFAULT, '".$synonymWord."', ".$id.")";
		//echo $query;
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->close();
        
        //will skip adding any characters if this function finds that $synonymWord is already in that table. If not it'll update characters.
        checkCharactersEntry($synonymWord, $db);
        //echo 'Created synonyms table entry<br>';
        
    }
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
    //create functions for checking. return id number or null. this will separate query calls

    function insertIntoCharacters($synonymWord, $db){
		
        //$characters = str_split($synonymWord, 1);
		$language = 'telugu';
		$processed = new wordProcessor($synonymWord, $language);
		$characters = $processed->getLogicalChars();
		//$array = explode(',', $characters);		
		
        //echo $characters[0];
        $query = "INSERT INTO characters(PuzzleWord, IndexNumber, letter) VALUES('".$synonymWord."', 0, '".$characters[0]."'),";
                
        //build query
        for($i = 1; $i <  count($characters); $i++){
                
            $query = $query . "('".$synonymWord."', ".$i .", '".$characters[$i]."'), ";
                //echo $characters[$i];
                
        }
        $query = rtrim($query, ', ');
        //echo $query;
		//echo "&#13;&#10;";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->close();
    }


    //tests if this word pair association already exists
	function testAlreadyExists($synonym, $word, $db){
		$synonymToWordExists = false;
		$wordToSynonymExists = false;
		
		$synonymPrimaryKeys = getSynonymsPrimaryKeys($synonym, $db);        
		$wordPrimaryKeys = getSynonymsPrimaryKeys($word, $db);
        if($synonymPrimaryKeys == 0 || $wordPrimaryKeys == 0){
            echo 'either synonym or word had no entries<br>';
            return false;
        }
        
		$synonymForeignKeys = getSynonymsForeignKeys($synonym, $db);
		$wordForeignKeys = getSynonymsForeignKeys($word, $db);
		
        
        
		//test if synonym word is associated with any word rows
		for($i = 0; $i < sizeof($synonymPrimaryKeys); $i++){
			for($j = 0; $j < sizeof($wordForeignKeys); $j++){
				if($synonymPrimaryKeys[$i] == $wordForeignKeys[$j]){
					$synonymToWordExists = true;
				}
			}
		}
		//test if word rows is associated with any synonym rows.
		for($i = 0; $i < sizeof($wordPrimaryKeys); $i++){
			for($j = 0; $j < sizeof($synonymForeignKeys); $j++){
				if($wordPrimaryKeys[$i] == $synonymForeignKeys[$j]){
					$wordToSynonymExists = true;
				}
			}
		}
		//if both are true, then return true
		if($synonymToWordExists == true && $wordToSynonymExists == true){
			return true;
		}else{
			return false;
		}
	}
	
	function getSynonymsPrimaryKeys($word, $db){
		$query = "SELECT SynID FROM synonyms WHERE SynonymWord = '".$word."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        $stmt->bind_result($thisSynID);
        $savedID = $thisSynID;
		
		$keys = array();
        //If synonym already exists as SynonymWord in DB
        //return the last entry if multiple words exist. loop through this
        echo 'number of rows for '.$word.' is '.$numOfRows.'<br>';
        if($numOfRows > 0){
            
                
                while($stmt->fetch()){
                    array_push($keys, $thisSynID);
                }
				
        }else{
            $stmt->close();
            return 0;
        }
		$stmt->close();
		return $keys;
	}
	
	function getSynonymsForeignKeys($word, $db){
		$query = "SELECT ClueID FROM synonyms WHERE SynonymWord = '".$word."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        $stmt->bind_result($thisSynID);
        $savedID = $thisSynID;
		
		$keys = array();
        //If synonym already exists as SynonymWord in DB
        //return the last entry if multiple words exist. loop through this
        echo 'number of rows for '.$word.' is '.$numOfRows.'<br>';
        if($numOfRows > 0){
            
                
                while($stmt->fetch()){
                    array_push($keys, $thisSynID);
                }
				
        }else{
            $stmt->close();
            return 0;
        }
		$stmt->close();
		return $keys;
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////
	function getClueWords($synonymID, $clueIDs, $db){
        $clueWordsArray = '';
        for($i = 0; $i < sizeof($clueIDs); $i++){
            $clueWordsArray .= getClueRow($clueIDs[$i], $db).', ';
        }
        
        return rtrim($clueWordsArray, ", ");
    }
    //returns the synonym word
	function deleteSyn($ID, $db) {
		//echo $ID;
		$query = "DELETE FROM synonyms WHERE SynID = ".$ID."";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
		$stmt->close();
	}
	
	function deleteChar($oldSynonym, $db) {
		//echo $ID;
		$query = "DELETE FROM characters WHERE PuzzleWord = '".$oldSynonym."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
		$stmt->close();
	}
	
	function printID($id) {		
		$string = '';
		for($x = 0; $x < sizeof($id); $x++) {			
			$string .= $id[$x].', ';
		}
		return rtrim($string, ", ");
	}
	
    function getClueRow($clueID, $db){
        $query = "SELECT SynonymWord FROM synonyms WHERE SynID = ".$clueID."";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        $stmt->bind_result($thisClueWord);
        $stmt->fetch();
        
        //echo 'number of rows: '.$numOfRows;
        if($numOfRows > 0){
            //echo 'clue word: '.$thisClueWord.'<br>';
            $stmt->close();
            return $thisClueWord;
        }else{
            //echo 'Entry for SynID '.$clueID.' doesn\'t exist<br>';
            $stmt->close();
            return false;
        }
    }
    
    function getClueIDs($synonymWord, $db){
        //echo $synonymWord;
        $query = "SELECT ClueID FROM synonyms WHERE SynonymWord = '".$synonymWord."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        $stmt->bind_result($thisClueID);
        $IDs = array();
        
        if($numOfRows > 0){
            while($stmt->fetch()){
                //echo "This clue ID: ".$thisClueID."<br>";
                array_push($IDs, $thisClueID);
            }
        }else{
            //echo 'empty query';
            $stmt->close();
            return false;
        }
        $stmt->close();
        return $IDs;
    }
    
    function getSynIDs($synonymId, $db){
        $query = "SELECT SynID FROM synonyms WHERE ClueID = '".$synonymId."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        $stmt->bind_result($thisSynID);
        $IDs = array();
        
        if($numOfRows > 0){			
            while($stmt->fetch()){				
                array_push($IDs, $thisSynID);
            }
        }else{
            //echo 'empty query';
            $stmt->close();
            return false;
        }
        $stmt->close();
        return $IDs;
    }
?>