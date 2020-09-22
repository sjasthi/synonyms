<?php
    $table = $_GET['query'];
    $word = $_GET['word'];   
    
	include 'db_configuration.php'; 
    //before inserting all in the table, first find the max puzzle_id in the table and then assign that to inserted records.
    $maxQuery = "select max(puzzle_id) as C from puzzles;";
    $result = mysqli_query($db, $maxQuery);
    $maxPuzzleId = $result->fetch_object()->C;
    $maxPuzzleId++;
    
    $Queries = explode('|',$table);
    //update database table for every query
    foreach($Queries as $Query)
    {
        $Query = str_replace("(0","(".$maxPuzzleId,$Query);
        $result = mysqli_query($db, $Query);
    }
    if($result){
     
     //   echo("<h3>record successfuly added to the database table.</h3>");
        header("Location: list_puzzles.php?addSuccessMessage=".$word);
        ////window.location='list_puzzles.php?addSuccessMessage='.$word; /* Redirect browser */
        
    }
    else{
        echo("<h3>failed to update record".mysqli_error($db)."</h3>");
    } 
    
    // add two buttons for Home ane return
	exit();
?>