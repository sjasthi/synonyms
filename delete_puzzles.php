<?php
	$id = $_GET['id'];
	echo $id;
	if (strlen ($id) == 0) {
		echo 'The puzzle id should not be empty';
		exit;
	}
	include 'db_configuration.php'; 
	
	
	$query = "DELETE FROM `puzzles` WHERE `puzzle_id`='".$id."'";	
    
	$result = mysqli_query($db, $query);
	
	if($result){
		echo 'record successfully deleted'; 
		
	}else{
		echo 'failed to de	lete record';
	}

	header("Location: list_puzzles.php"); /* Redirect browser */
	exit();
?>