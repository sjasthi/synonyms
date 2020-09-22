<?php
	$id = $_GET['id'];
	//echo $id;
	if (strlen ($id) == 0) {
		echo 'The Syn id should not be empty';
		exit;
	}
	include 'db_configuration.php'; 
	
	
	$query = "DELETE FROM `synonyms` WHERE `SynID`=".$id." or `ClueID`=".$id;	

	//echo($query);
    
	$result = mysqli_query($db, $query);
	
	if($result){
		echo 'record successfully deleted'; 
		
	}else{
		echo 'failed to delete record';
	}

	header("Location: list_synonyms.php"); /* Redirect browser */
	exit();
?>