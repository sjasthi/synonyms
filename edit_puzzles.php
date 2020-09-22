<?php
    include 'db_configuration.php';
	include 'puzzleGenerator.php';
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Puzzles</title>
    <!--link href="css/bootstrap.min.css" rel="stylesheet"-->
	<link href="css/fp3style.css" rel="stylesheet">
	
	<style>
		
		
		
</style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via  -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container">
	<div class="container">
	<!--div class="row">    
		<?php
			//include 'header.php';
		?>		
			
    </div-->
    <div class="row">

        
    
    
    <?php
        //This PHP code block will be to get the data from the database to create the puzzle.
        //If puzzle is edited successfully then this page will receive a msg that needs to be display first
        $puzzle_id = htmlspecialchars($_GET['id']);
        
        if(isset($_GET['addSuccessMessage']))
	    {
			echo("<h3>Congratulations: Puzzle Id, ".$puzzle_id." is edited successfully.</h3>");
		
	    }
		
		
		$query = "SELECT * FROM puzzles WHERE puzzle_id = '".$puzzle_id."'";
		$stmt = $db->prepare($query);
		$stmt->execute();
		$stmt->store_result();
		$numOfRows = $stmt->num_rows;
		$stmt->bind_result($no, $puzzleId, $name, $owner,$left,$right,$postion,$left_word,$character,$right_word,$submitted_by);
		
        
    ?>

    <div class="EditPanel" style="margin-top: 30px">
        <div > 
            <h3>Edit Puzzle</h3>
			
            <table class="EditTable">
			    <tr>
                    <th>no</th><th>puzzle_id</th><th>name</th><th>owner</th>
                    <th>left</th><th>right</th><th>position</th>
                    <th>left_word</th><th>character</th><th>right_word</th><th>submitted_by</th>
                </tr>
               <?php
				$output = '';
				
                while($stmt->fetch()){
                   
                    
                    $output .= '<tr>
                                    <td>'.$no.'</td>
                                    <td>'.$puzzle_id.'</td>
                                    <td><input type=\'text\' value='.$name.'></td>
                                    <td><input type=\'text\' value='.$owner.'></td>
                                    <td><input type=\'text\' value='.$left.'></td>
                                    <td><input type=\'text\' value='.$right.'></td>
                                    <td><input type=\'text\' value='.$postion.'></td>
                                    <td><input type=\'text\' value='.$left_word.'></td>
                                    <td><input type=\'text\' value='.$character.'></td>
                                    <td><input type=\'text\' value='.$right_word.'></td>
                                    <td><input type=\'text\' value='.$submitted_by.'></td>
                                </tr>';
					$output .= "\n";
                    //echo $indexArray[$i];
                }
				
				echo $output;
                ?>
			
			</table>			
        </div>
		
		
    </div>

    <!--div class="EasyPanel" style="margin-top: 30px">
        <div> 
            <h3>
                <div class="MainClueDiv" >
                    <input class="MainClueTextBoxLeft" type="text" value = "4" disabled/>
                    <input class="MainClueTextBoxCenter" type="text" value = "4" disabled/>
                    <input class="MainClueTextBoxRight" type="text" value = "x" disabled/>
                    <script>
                           document.getElementsByClassName("MainClueTextBoxLeft").value = 4;
                    </script>
                </div>

                <br>
                2. Easy Version
            </h3>
			
        <table class="EasyTable">
			<tr><th>Left Word</th><th>Character</th><th>Right Word</th></tr>
               <!?php
				$output = '';
				$stmt->data_seek(0);
                while($stmt->fetch()){
                    $output .= '<tr><td>'.$left_word.'</td><td>'.$character.'</td><td>'.$clueTextBoxesRight.'</td></tr>';
					$output .= "\n";
                    //echo $indexArray[$i];
                }
				
				echo $output;
                //echo '<tr><td>second word</td><td>first synonym</td></tr>';
                //echo '<tr><td>third word</td><td>first synonym</td></tr>';
                //echo '<tr><td>fourth word</td><td>first synonym</td></tr>';
                //echo '<tr><td>fifth word</td><td>first synonym</td></tr>';
               
                ?>
			
			</table>				
					
        </div>
		
		
    </div>

    <div class="Solution" style="margin-top: 30px">
        <div> <h3>3. Solution</h3>
			
        <table class="SolutionTable">
			<tr><th>Left Word</th><th>Character</th><th>Right Word</th></tr>
               <!?php
				$output = '';
				$stmt->data_seek(0);
                while($stmt->fetch()){
                    $output .= '<tr><td>'.$left_word.'</td><td>'.$character.'</td><td>'.$right_word.'</td></tr>';
					$output .= "\n";
                    //echo $indexArray[$i];
                }
				
				echo $output;
                //echo '<tr><td>second word</td><td>first synonym</td></tr>';
                //echo '<tr><td>third word</td><td>first synonym</td></tr>';
                //echo '<tr><td>fourth word</td><td>first synonym</td></tr>';
                //echo '<tr><td>fifth word</td><td>first synonym</td></tr>';
               
                ?>
			
			</table>
						
        </div>
		
		
    </div-->

	<button class='sub' onclick="savePuzzle()">Save Puzzle</button>
	<!--button class='sub' onclick="showSolution()">Show Solution</button>
	<button class='sub' onclick="changeInput()">Change Input Mode</button-->
	<a class='sub' id='homesub' href="list_puzzles.php">Return Home</a>
	<br />
	<br />
	
	</div>
</div>
</div>

<script>
    	function savePuzzle() {	
			//alert("from saveSln");

             var tableList = document.getElementsByClassName('EditTable');
            //iterate thru each table and update... skipping first row as it has table col names only which wont go to db for update
            $query="";
            for(var i=1;i<tableList[0].rows.length;i++)
            
            {
               var $singleQuery = "UPDATE puzzles SET " 
                            +"name='"+tableList[0].rows[i].cells[2].querySelector('input').value+"'," 
                            +"owner='"+tableList[0].rows[i].cells[3].querySelector('input').value+"'," 
                            +"`left`="+tableList[0].rows[i].cells[4].querySelector('input').value+"," 
                            +"`right`="+tableList[0].rows[i].cells[5].querySelector('input').value+"," 
                            +"position="+tableList[0].rows[i].cells[6].querySelector('input').value+","  
                            +"left_word='"+tableList[0].rows[i].cells[7].querySelector('input').value+"',"  
                            +"`character`='"+tableList[0].rows[i].cells[8].querySelector('input').value+"',"  
                            +"right_word='"+tableList[0].rows[i].cells[9].querySelector('input').value+"',"  
                            +"submitted_by='"+tableList[0].rows[i].cells[10].querySelector('input').value+"' "        
                            +"WHERE no=" + tableList[0].rows[i].cells[0].innerHTML;	
                
                //alert($singleQuery);
	            
                /*$result = mysqli_query($db, $query);
	
                if($result){
                    alert("record successfully updates"); 
                    
                }else{
                    alert("failed to update record");
                } */
               //adding | to each single query that will help in explode method in update_puzzleDB.php
              $query=$query+$singleQuery+"|";
               //tableList[0].rows[i].cells[4].querySelector('input').value;
            }
            //del last added | from the end of $query. otherwise explode will add additional empty string in its array in update_puzzleDB
            $query = $query.substr(0,$query.length-1);
            //alert($query);
            //the id added in URL will help navigating back to same page afater updates are successfully done
             window.location = "update_puzzlesDB.php?query=" + $query+"&id="+tableList[0].rows[1].cells[1].innerHTML;
           // window.location="puzzle.php?id=" + puzzle.value + "&title=" + puzzle.name;
        
    }
</script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/puzzle.js"></script>
</body>
</html>