<?php
	$id = $_GET['id'];
	echo $id;
	if (strlen ($id) == 0) {
		echo 'The puzzle should not be empty';
		exit;
	}
	include 'db_configuration.php'; 
	
	
	$query = "DELETE FROM `puzzle` WHERE `PuzzleID`='".$id."'";	
    
	$result = mysqli_query($db, $query);
	
	if($result){
		echo 'shit works'; 
		
	}else{
		echo 'shit dont work';
	}

	header("Location: list.php"); /* Redirect browser */
	exit();
?>