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
	include 'puzzleGenerator.php';
	$query = "SELECT SynID,SynonymWord FROM synonyms where SynID = ClueID";
	$stmt = $db->prepare($query);
	//$stmt->bind_param('s', $searchterm);  
	$stmt->execute();
	$stmt->store_result();

	$stmt->bind_result($SynID,$SynonymWord);
	
			echo "<table id='listTable' class='datatable table table-striped table-bordered' >\n";
			echo "<thead>\n";
			echo "<tr>\n";				
			echo "\t<th>MasterWord</th>\n";
            echo "\t<th>Synonym_Word</th>\n";
            echo "\t<th>Actions</th>\n";
			echo "</tr>\n";
			echo "</thead>\n";
			echo "<tbody>\n";
		while($stmt->fetch()) {				
					
					echo "<tr>\n";
                        echo "\t<td>".$SynonymWord."</td>\n";	
						
						//call function of puzzleGenerator to get list of child words from ClueID

						$clueWordArray = getCluesArray($db,$SynID);

						echo "\t<td style='word-break: break-all;'>".implode(",",$clueWordArray)."</td>\n";
						
						if(isset($_SESSION["isAdmin"])){
							if($_SESSION["isAdmin"] == 1){
						
						echo "\t<td>";
						
						
						echo "<input onclick='editSynonym(this)' type='image' src='edit.jpg' name='id' value='".$SynonymWord."' style='width: 22px;'>     ";
							
						
						echo "<input onclick='deleteSynonym(this)' type='image' name='id' value='".$SynID."'src='delete.jpg' style='width: 22px;'>";
												}
						}
						
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

		function deleteSynonym(synonym) {
			//alert(puzzle.value);
			var r = confirm("Would like to delete " + synonym.value + "?");
			if (r == true) {
				window.location="delete_synonyms.php?id=" + synonym.value;
			} else {
				
			}
		}
		
		
		
		
		function editSynonym(synonym) {	
			//alert("from puzzle");
			window.location="editSyn2.php?synonym="+ synonym.value;
			
		}
	</script>
	</body>
</html>