<?php
    session_start();
?>

    
	<div class="jumbotron" align="center" style="background-color:#64E464">
            
			<a href="index.php"><img src='silcHeader.png' id='header'/></a>
			<H1 id='shout' style="font-size: 300%">Name in Synonyms</H1>
			
			<div class='admin' style="background-color:yellow">
			<?php
				if(isset($_SESSION["isAdmin"])){
                    if($_SESSION["isAdmin"] == 1){
                        echo "<a class='links' href='logout.php'>Logout</a>";                        
                    }
				} else {
					echo "<a class='links' href='login.php'>Login</a>";
				}
			?>
			</div>

        <?php
			if(isset($_SESSION["isAdmin"])){
                    if($_SESSION["isAdmin"] == 1){
                        echo '<div class="admin" style="background-color:pink">';
                        echo '<a class="links" align="center" href="wordPairs.php">Add Synonyms</a>';
                        echo '</div>';
                    }
            }

		
		   if(isset($_SESSION["isAdmin"])){
                    if($_SESSION["isAdmin"] == 1){
                        echo '<div class="admin" style="background-color:#6495ED">';
                        echo '<a class="links" align="center" href="addPuzzle.php">Add A Puzzle</a>';
                        echo '</div>';
                    }
            }
		?>
		
			<div class='admin' style="background-color:#089e9e">
				<a class='links' href="list_puzzles.php">List</a>
			</div>
            <?php
            
            if(isset($_SESSION["isAdmin"])){
                    if($_SESSION["isAdmin"] == 1){
                        echo '<div class="admin" style="background-color:#00fff6">';
                        echo '<a class="links" align="center" href="admin.php">Admin</a>';
                        echo '</div>';
                    }
            }
            ?>
            
		</div>
	
	
