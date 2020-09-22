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
  
</head>
<body>

<div class="container">
	
    <div class="row">
        
        <?php
           
           $mainQuery = "SELECT DISTINCT puzzle_id FROM puzzles";// LIMIT 10";
           $mainStmt = $db->prepare($mainQuery);
           $mainStmt->execute();
           $mainStmt->store_result();
       
           $mainStmt->bind_result($puzzle_id);
           
           //$puzzle_id = 143;
           while($mainStmt->fetch())
            {

                //execute query for every puzzle_id
                $query = "SELECT * FROM puzzles WHERE puzzle_id = '".$puzzle_id."'";
            
                $stmt = $db->prepare($query);
                $stmt->execute();
                $stmt->store_result();
                $numOfRows = $stmt->num_rows;
                $stmt->bind_result($no, $puzzleId, $name, $owner,$left,$right,$position,$left_word,$character,$right_word,$submitted_by);
                
                //All records of that puzzle id has same values for left, right and position. So just getting the first record from the recordset should be enough.
                //$stmt will be reset to start pos after this read.
                $stmt->fetch();

                //Write puzzle_id, Title and Owner
                $output = '<div class="ToughPanel" style="margin-top: 30px"><h1>Puzzle ID:'.$puzzle_id.'   Title:'.$name.'    Owner:'.$owner.'</h1>';
                //Creating the main Clue Text boxes
                $output .= ' <h3>
                    <div class="MainClueDiv" >
                        <input class="MainClueTextBoxLeft" type="text" value='.$left.' disabled/>
                        <input class="MainClueTextBoxRight" type="text" value ='.$right.' disabled/>
                        <input class="MainClueTextBoxPosition" type="text" value ='.$position.' disabled/>
                        
                    </div>

                    <br>
                    1. Tough Version
                    </h3>';
                
                    //resetting $stmt to start 
                    $stmt->data_seek(0);              
                    //creating table and its header
                    $output .= '<table class="ToughTable">
                    <tr><th>Left Word</th><th>Character</th><th>Right Word</th></tr>';
                    
                    while($stmt->fetch()){

                        $clueTextBoxesRight='';
                        
                        switch($right)
                        {
                            case "X":
                            case "x":
                            {
                            if($position == "X" || $position == "x")
                            {
                                $clueTextBoxesRight = $clueTextBoxesRight .'<input class="clueTextBox" type="text" disabled/>'.' ';
                                $clueTextBoxesRight = $clueTextBoxesRight .'<input type="image" src="yellowArrow.jpg" style="height: 25px; vertical-align: middle;"/>'.' ';
                                break;
                            }  

                            else
                            {
                                for($i = 0; $i < $position; $i++)
                                {
                                    //if this is character position then create it with different color and add yellow arrow and skip
                                    if($i == $position-1)
                                    {
                                        $clueTextBoxesRight = $clueTextBoxesRight .'<input class="cluePositionTextBox" type="text" disabled/>'.' ';
                                        $clueTextBoxesRight = $clueTextBoxesRight .'<input type="image" src="yellowArrow.jpg" style="height: 25px; vertical-align: middle;"/>'.' ';
                                        break;
                                    }
                                    else
                                        $clueTextBoxesRight = $clueTextBoxesRight .'<input class="clueTextBox" type="text" disabled/>'.' ';
                                }
                            }
                            }
                            break;
                            default: //for valid int value
                            {
                                if($position == "X" || $position == "x")
                                {
                                    for($i = 0; $i < $right; $i++)
                                    {
                                        $clueTextBoxesRight = $clueTextBoxesRight .'<input class="clueTextBox" type="text" disabled/>'.' ';
                                    }
                                }  

                            else
                            {
                                for($i = 0; $i < $right; $i++)
                                {
                                    //if this is character position then create it with different color 
                                    if($i == $position-1)
                                    {
                                        $clueTextBoxesRight = $clueTextBoxesRight .'<input class="cluePositionTextBox" type="text" disabled/>'.' ';
                                        
                                    }
                                    else
                                        $clueTextBoxesRight = $clueTextBoxesRight .'<input class="clueTextBox" type="text" disabled/>'.' ';
                                }
                            }
                            break;
                            }

                        }

                       $clueTextBoxesLeft='';
                        
                        if($left == 'X' || $left == 'x')
                        {
                            $clueTextBoxesLeft = $clueTextBoxesLeft .'<input class="clueTextBox" type="text" disabled/>'.' ';
                            $clueTextBoxesLeft = $clueTextBoxesLeft .'<input type="image" src="yellowArrow.jpg" style="height: 25px; vertical-align: middle;"/>'.' ';
                        }
                        else
                        {
                            for($i = 0; $i < $left; $i++)
                            {
                                $clueTextBoxesLeft = $clueTextBoxesLeft .'<input class="clueTextBox" type="text" disabled/>'.' ';
                            }
                        }
                        
                        
                        $output .= '<tr><td>'.$clueTextBoxesLeft.'</td><td>'.$character.'</td><td>'.$clueTextBoxesRight.'</td></tr>';
                        $output .= "\n";
                        //echo $indexArray[$i];
                    }
                    
                    echo $output;
                    echo('</table> </div>');
                
                //All records of that puzzle id has same values for left, right and position. So just getting the first value from the record should be enough.
                //$stmt will be reset to start pos after this read.
                $stmt->fetch();
                //Creating the main Clue Text boxes
                $output = ' <div class="EasyPanel" style="margin-top: 30px">
                    <h3>
                    <div class="MainClueDiv" >
                        <input class="MainClueTextBoxLeft" type="text" value='.$left.' disabled/>
                        <input class="MainClueTextBoxRight" type="text" value ='.$right.' disabled/>
                        <input class="MainClueTextBoxPosition" type="text" value ='.$position.' disabled/>
                        
                    </div>

                    <br>
                    2. Easy Version
                        </h3>';
                
                    //resetting $stmt to start 
                    $stmt->data_seek(0);              
                    //creating table and its header
                    $output .= '<table class="EasyTable">
                    <tr><th>Left Word</th><th>Character</th><th>Right Word</th></tr>';
                    while($stmt->fetch()){
                        $output .= '<tr><td>'.$left_word.'</td><td>'.$character.'</td><td>'.$clueTextBoxesRight.'</td></tr>';
                        $output .= "\n";
                        //echo $indexArray[$i];
                    }
                    
                    echo $output;
                    echo ('</table></div>');
               
                    $output = ' <div class="Solution" style="margin-top: 30px">
                    <div> <h3>3. Solution</h3>
                
                    <table class="SolutionTable">
                    <tr><th>Left Word</th><th>Character</th><th>Right Word</th></tr>';
                    $stmt->data_seek(0);
                    while($stmt->fetch()){
                        $output .= '<tr><td>'.$left_word.'</td><td>'.$character.'</td><td>'.$right_word.'</td></tr>';
                        $output .= "\n";
                        //echo $indexArray[$i];
                    }
                    echo $output;
                    echo(' </table></div><br>');
                }
        ?>
    </div>
	
    
    <a class='sub' id='homesub' href="index.php">Return Home</a>
		
	
    </div>
</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/puzzle.js"></script>
</body>
</html>