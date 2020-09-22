<?php
    $table = $_GET['query'];
    $id = $_GET['id'];
	
	/*if (strlen ($id) == 0) {
		echo 'The puzzle id should not be empty';
		exit;
	}*/
	include 'db_configuration.php'; 
	
    $Queries = explode('|',$table);
    //update database table for every query
    foreach($Queries as $Query)
        
        $result = mysqli_query($db, $Query);
    
    if($result){
        //after supdate go back to edit puzzle page 
        header("Location: edit_puzzles.php?id=".$id."&addSuccessMessage=true");
        //window.location='edit_puzzles.php?id='.$id; /* Redirect browser */
        
    }else{
        echo("failed to update record".mysqli_error($db));
    } 
	/*for($i=1; $i < $tableList[0].rows.length; $i++)
            {
               $query = 'UPDATE puzzles SET '
                            .'name='.$tableList[0].rows[i].cells[2].querySelector('input').value.'\',' ;
                            /*+"owner='"+tableList[0].rows[i].cells[3].querySelector('input').value+"'," 
                            +"left="+tableList[0].rows[i].cells[4].querySelector('input').value+"," 
                            +"right="+tableList[0].rows[i].cells[5].querySelector('input').value+"," 
                            +"position="+tableList[0].rows[i].cells[6].querySelector('input').value+","  
                            +"left_word='"+tableList[0].rows[i].cells[7].querySelector('input').value+"',"  
                            +"character='"+tableList[0].rows[i].cells[8].querySelector('input').value+"',"  
                            +"right_word='"+tableList[0].rows[i].cells[9].querySelector('input').value+"',"  
                            +"submitted_by='"+tableList[0].rows[i].cells[10].querySelector('input').value+"' "        
                            +"WHERE no=" + tableList[0].rows[i].cells[0].innerHTML;	*/
                
               /* echo($query);
				
				$result = mysqli_query($db, $query);
				
				if($result){
                    echo("record successfully updates"); 
                    
                }else{
                    echo("failed to update record");
                } 
              
               //tableList[0].rows[i].cells[4].querySelector('input').value;
            }*/
			//col 2 of table has puzzle_id
		//window.location='edit_puzzles.php?id='.$tableList[0].rows[0].cells[1].innerHTML(); /* Redirect browser */
	exit();
?>