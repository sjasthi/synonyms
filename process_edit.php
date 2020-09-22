<?php

$puzzleID = $_POST['puzzleId'];
$puzzleWord = $_POST['puzzleWord'];
$puzzleSynWords = $_POST['puzzleSynWords']
$indexes = $_POST['indexes'];
	
	if (strlen ($topic) == 0) {
		echo 'The puzzle should not be empty';
		exit;
	}
	
	include 'db_configuration.php'; 


	$query = "UPDATE puzzle SET `puzzleWord`='".$puzzleWord."', `puzzleSynWords`='".$puzzleSynWords."', `puzzleId`='".$puzzleId."', `indexes`='".$indexes."',WHERE `puzzleId`='".$puzzleId."'";	
	echo $query;
	
	$result = mysqli_query($db, $query);
	
	if($result){
		echo "Success!!";
		
	}else{
		echo "error";
	}
	
	$db->close();
	//header("Location: list.php");
	//exit();
?>