<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Puzzle</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
</head>
<body>

<div class="container">

	<div class="row">    
		<?php
			include 'header.php';
		?>		
			
    </div>
	

    
    <div class="row">        
    
    <div id="demo">
	<?php  
	include 'db_configuration.php';
	$query = "SELECT * FROM puzzle";
	$stmt = $db->prepare($query);
	//$stmt->bind_param('s', $searchterm);  
	$stmt->execute();
	$stmt->store_result();

	$stmt->bind_result($puzzleId, $puzzleWord, $puzzleSynWords, $indexes);
	
			echo "<table class='table table-striped'>\n";
			echo "<tr>\n";				
			echo "\t<th>Puzzle ID</th>\n";
			echo "\t<th>Puzzle</th>\n";
			echo "\t<th>Actions</th>\n";
			echo "</tr>\n";
   
		while($stmt->fetch()) {				
					
					echo "<tr>\n";
						echo "<form action='edit_puzzle.php' method='post'>\n";
						echo "\t<td><a href='puzzle.php?id=".$puzzleId."&title=".$puzzleWord."' onclick=''>".$puzzleId."<input type='hidden' id='$puzzleId' name='puzzleId' value='".$puzzleId."' size='0';'/></a></td>\n";
						echo "\t<td><a href='puzzle.php?id=".$puzzleId."&title=".$puzzleWord."' onclick=''>".$puzzleWord."</a><input type='hidden' id='$puzzleId' name='puzzleWord' value='".$puzzleWord."' size='0';'/></td>\n";
						echo "\t<td>";
						if(isset($_SESSION["isAdmin"])){
												if($_SESSION["isAdmin"] == 1){
						echo "<input type='image' src='edit.jpg' name='id' value='".$puzzleId."' alt='submit' style='width: 22px;'>";
												}
						}
						echo "\t</form>";
						if(isset($_SESSION["isAdmin"])){
												if($_SESSION["isAdmin"] == 1){
						echo "<input onclick='deletePuzzle(this)' type='image' name='id' value='".$puzzleId."'src='delete.jpg' style='width: 22px;'>";
												}
						}
						echo "\t<input onclick='playPuzzle(this)' type='image' name='".$puzzleWord."' value='".$puzzleId."' src='play.jpg' style='width: 22px;'></td>\n";
						echo "";
						
				echo "</tr>\n"; 
			}
				
			echo "</table>\n";
	
		
    ?>
	</div>
	</div>
	<a class='sub' id='homesub' href="index.php">Return Home</a>
</div>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript">

		function deletePuzzle(puzzle) {
			//alert(puzzle.value);
			var r = confirm("Would like to delete " + puzzle.value + "?");
			if (r == true) {
				window.location="delete_puzzle.php?id=" + puzzle.value;
			} else {
				
			}
		}
		
		function playPuzzleLink(object) {
			
				window.location="puzzle.php?id=" + object + "&title=" + puzzle.name;
			
		}
		
		
		function playPuzzle(puzzle) {	
			
				window.location="puzzle.php?id=" + puzzle.value + "&title=" + puzzle.name;
			
		}
	</script>
	</body>
</html>