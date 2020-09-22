<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Puzzles List</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">

	<!--Two Links added for sorting and showing arrows-->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet"/>

	 
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
	
	if(isset($_GET['addSuccessMessage']))
	{
		$addSuccessMessage = htmlspecialchars($_GET['addSuccessMessage']); 
		echo("<h3>Congratulations: New puzzle, ".$addSuccessMessage." is added.</h3>");
		
	}
	

	$query = "SELECT * FROM puzzles group by puzzle_id";
	$stmt = $db->prepare($query);
	//$stmt->bind_param('s', $searchterm);  
	$stmt->execute();
	$stmt->store_result();

	$stmt->bind_result($no, $puzzleId, $name, $owner,$left,$right,$postion,$left_word,$character,$right_word,$submitted_by);
	
			echo "<table id='listTable' class='datatable table table-striped table-bordered' >\n";
			echo "<thead>\n";
			echo "<tr>\n";				
			//echo "\t<th>No</th>\n";
            echo "\t<th>PuzzleId</th>\n";
            echo "\t<th>Name</th>\n";
            echo "\t<th>Actions</th>\n";
			echo "</tr>\n";
			echo "</thead>\n";
			echo "<tbody>\n";
		while($stmt->fetch()) {				
					
					echo "<tr>\n";
                        //echo "<form action='puzzle_html.php' method='post'>\n";
                        //echo "\t<td><a href='puzzle_html.php?id=".$no."&title=".$puzzleId."' onclick=''>".$no."</a></td>\n";
						echo "\t<td><a href='puzzle_html.php?puzzleId=".$puzzleId."' onclick=''>".$puzzleId."<input type='hidden' id='$puzzleId' name='puzzleId' value='".$puzzleId."' size='0';'/></a></td>\n";
						echo "\t<td><a href='puzzle_html.php?title=".$name."' onclick=''>".$name."</a><input type='hidden' id='$name' name='puzzleWord' value='".$name."' size='0';'/></td>\n";
						echo "\t<td><a href='puzzle_html.php?puzzleId=".$puzzleId."' onclick=''><input type='image' src='htmlIcon.png' style='width: 22px;'/> </a>";
						
						if(isset($_SESSION["isAdmin"])){
												if($_SESSION["isAdmin"] == 1){ 
						echo "&emsp;"; //will add four spaces
						echo "<input onclick='playPuzzle(this)' type='image' src='edit.jpg' name='id' value='".$puzzleId."' alt='submit'  style='width: 22px;'>   	";
												}
						}
						echo "\t</form>";
						if(isset($_SESSION["isAdmin"])){
												if($_SESSION["isAdmin"] == 1){
						echo "&emsp;"; //will add four spaces
						echo "<input onclick='deletePuzzle(this)' type='image' name='id' value='".$puzzleId."'src='delete.jpg' style='width: 22px;'>";
												}
						}
						
						//echo "\t<input onclick='playPuzzle(this)' type='image' name='".$no."' value='".$puzzleId."' src='play.jpg' style='width: 22px;'></td>\n";
						echo "</td>\n";
						
				echo "</tr>\n"; 
			}
			echo "</tbody>\n";
			echo "</table>\n";
	
		
    ?>
	</div>
	</div>
	<a class='sub' id='homesub' href="index.php">Return Home</a>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/jquery.dataTables.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/dataTables.bootstrap.min.js"></script>


	<script type="text/javascript">
    	$(document).ready(function () {
        $('#listTable').DataTable();
    	});
	</script>
	
	
	<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script-->
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<!--script src="js/bootstrap.min.js"></script-->
	<script type="text/javascript">

		function deletePuzzle(puzzle) {
			alert(puzzle.value);
			var r = confirm("Would like to delete " + puzzle.value + "?");
			if (r == true) {
				window.location="delete_puzzles.php?id=" + puzzle.value;
			} else {
				
			}
		}
		
		
		
		
		function playPuzzle(puzzle) {	
			//alert("from puzzle");
			window.location="edit_puzzles.php?id="+ puzzle.value;
			
		}
	</script>
	</body>
</html>