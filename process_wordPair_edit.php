<?php
    include 'db_configuration.php';

    $cluesSize = $_POST['cluesSize'];
    //$synIDs = $_POST['synonymIDs'];
    //echo $_POST['clueWord'];
    $synIDs = array();
    $clueWords = array();
    $clueIDs = array();
    for($i = 0; $i < $cluesSize; $i++){
        array_push($synIDs, $_POST['synonymID'.$i]);
        array_push($clueWords, $_POST['clueWord'.$i]);
    }
    //populate clueIDs
    for($i = 0; $i < $cluesSize; $i++){
        $result = checkSynIDExists($clueWords[$i], $db);
        //echo 'result = '.$result;
        if($result != 0){
            //syn id for that clue exists
            //echo $clueWords[$i].' exists <br>';
            array_push($clueIDs, $result);
        }else{
            //echo $clueWords[$i].' doesn\'t exist, making entry <br>';
            //syn id for that clue doesn't exist
            //need to make a new entry, then store that new PK to the clueIDs
            //uses $synIDs[0] because it's earliest entry of the main word.
            
            addSynonymEntry($clueWords[$i], $synIDs[0], $db);
            
            //try again now that it's added
            
            
            $result = checkSynIDExists($clueWords[$i], $db);
            if($result != false){
                array_push($clueIDs, $result);
            }else{
                echo 'not sure how we got here<br>';
            }
            
            
        }
    }

    
    for($i = 0; $i < $cluesSize; $i++){
       // echo 'Updating SynID = '.$synIDs[$i].' entry to now reference '.$clueWords[$i].' which has ID = '.$clueIDs[$i].'<br>';
        //check if exists first
        if(checkWordPairExists($synIDs, $clueIDs[$i], $db) == false){
            //echo 'Pair does not exist in DB already<br>';
            updateRow($synIDs[$i], $clueIDs[$i], $db);
        }
    }

    echo 'successfully added all<br>';
    header('Location: ./wordPair_edit_success.html');



    function checkSynIDExists($word, $db){
        $query = "SELECT SynID FROM synonyms WHERE SynonymWord = '".$word."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        $stmt->bind_result($thisSynID);
        $stmt->fetch();
        if($numOfRows > 0){
            $stmt->close();
            return $thisSynID;
        }else{
            $stmt->close();
            return 0;
        }
    }

    function updateRow($synID, $clueID, $db){
        //check if association with synID word and clueID word already exists in db. if so, skip the update.
        $query = "UPDATE synonyms SET ClueID = ".$clueID." WHERE SynID = ".$synID;
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->close();
        //echo 'updated row';
    }


    function addSynonymEntry($synonymWord, $FK, $db){
        //add to Synonym table
        //use createCharactersEntry to update character table accordingly.
        
        //uncomment after debugging
        
        //$query = "INSERT INTO synonyms(SynID, SynonymWord, ClueID) VALUES(DEFAULT, '".$synonymWord."', NULL)";
        $query = "INSERT INTO synonyms(SynID, SynonymWord, ClueID) VALUES(DEFAULT, '".$synonymWord."', ".$FK.")";
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
        
    }
    //create functions for checking. return id number or null. this will separate query calls
    //helper to above function
    function insertIntoCharacters($synonymWord, $db){
        $characters = str_split($synonymWord, 1);
        
        $query = "INSERT INTO characters(PuzzleWord, IndexNumber, letter) VALUES('".$synonymWord."', 1, '".$characters[0]."'),";
                
        //build query
        for($i = 2; $i < strlen($synonymWord)+1; $i++){
                
            $query = $query . "('".$synonymWord."', ".$i .", '".$characters[$i-1]."'), ";
                
                
        }
        $query = rtrim($query, ', ');
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->close();
    }

    function checkWordPairExists($synIDs, $clueID, $db){
        for($i = 0; $i < sizeof($synIDs); $i++){
            echo 'Comparing '.$synIDs[$i]. ' to '.$clueID.'<br>';
            if(checkExistsRow($synIDs[$i], $clueID, $db) == true){
                echo 'row existed for '.$synIDs[$i].' and synID '.$clueID.'<br>';
                return true;
            }
            echo 'row did not exist for '.$synIDs[$i].' and synID '.$clueID.'<br>';
        }
        
        return false;
    }
    //helper to above function
    function checkExistsRow($synID, $clueID, $db){
        $query = "SELECT ClueID FROM synonyms WHERE SynID = '".$synID."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($thisClueID);
        $stmt->fetch();
        
        //echo '$thisClueID = '.$thisClueID.'<br>';
        if($thisClueID == $clueID){
            return true;
        }else{
            return false;
        }
    }

?>