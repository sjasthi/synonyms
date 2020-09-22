<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Puzzle</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">    
	<style>
    </style>
    


</head>
<body>

<div class="container">
	

    <div class="row">
		<?php
            include 'header.php';
            include 'db_configuration.php'; 
            include 'config.php';
            include 'puzzleGenerator.php';
            
            $Error = '';
            $ErrorDB = false;
            
            //get all params when page is redirected
            $word="";$owner="";$submittedBy="";$addTable="";$positionTable="";
            
            if(isset($_GET['word']))
                $word = htmlspecialchars($_GET['word']);    
            if(isset($_GET['owner']))
                $owner = htmlspecialchars($_GET['owner']);
            if(isset($_GET['submittedBy']))  
                $submittedBy = htmlspecialchars($_GET['submittedBy']);
            if(isset($_GET['addTable']))
                $addTable = htmlspecialchars($_GET['addTable']);
            if(isset($_GET['positionTable']))
                $positionTable = htmlspecialchars($_GET['positionTable']);
		?>	
			
    </div>
    
    <div class="row1">
		
		<div class="panel panel-primary" style="margin-top: 30px">
			<div class="Please enter the following settings">
				<!--form class="contact" action="createPuzzle.php" target="" method="get" role="form"-->
                <div class="row">
                    <div class="col-lg-12" style="margin-top: 10px;margin-left:10px" align="left">						
						    <div class="form-group">
                                <h3>
                                    Word*:<input type="text" style="margin-top: 10px;margin-left:90px" name="word" value= <?php echo $word?>><br>
                                    Owner: <input type="text" style="margin-top: 10px;margin-left:80px" name="owner" value= <?php echo $owner?>><br>
                                    Submitted By: <input type="text" style="margin-top: 10px;margin-left:8px" name="submittedBy" value= <?php echo $submittedBy?>><br>
                                </h3>
                                <div style="margin-top: 10px">                                    
									<input type="button" class="btn btn-primary" onclick='showAddPuzzleForm()' style="margin-top: 10px;margin-left:170px" value="Next">
                                </div>
                            </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6" style="margin-bottom: 10px; margin-left: 15px">
                        <!--<button onclick="eraseText()" type="button" class="btn btn-default" id="clrbutton">Clear</button>-->
                    </div>

                </div>

            </div>
		</div>
    </div> 
        <div class="row2">
            <div class="col-sm-10" align="left">
            <?php
                
                if(strlen($addTable) > 0 ) //means this page is rediected and echo all table else just create table node and it will be created when Next button is clicked
                {
        
                    //echo ($addTable);
                    echo('<table class="addTable">');
                    echo('<tr><th>No</th><th>Character</th><th>Clue(word_1)</th><th>Synonym(word_2)</th></tr>');
                    $addTableArr = explode(',',$addTable);
                        $count = count($addTableArr);
                        for($i = 0; $i < $count; $i = $i+4)
                        {
                            //Validation 2: if entered word1 and word2 already exist in db then those text should be shown in yellow
                            $query = 'SELECT COUNT(*) AS C FROM puzzles WHERE left_word=\''.$addTableArr[$i+2].'\' || right_word=\''.$addTableArr[$i+3].'\'';
                            $result = mysqli_query($db, $query);
                            $recordCount = $result->fetch_object()->C;
                                                        
                            if($recordCount <= 0)
                                echo '<tr><td>'.$addTableArr[$i].'</td><td>'.$addTableArr[$i+1].'</td><td><input type=\'text\' value='.$addTableArr[$i+2].'></td><td><input type=\'text\' value='.$addTableArr[$i+3].'></td></tr>';
                            else
                            {
                                echo '<tr><td>'.$addTableArr[$i].'</td><td>'.$addTableArr[$i+1].'</td><td><input type=\'text\' style="background-color:yellow" value='.$addTableArr[$i+2].'></td><td><input type=\'text\' style="background-color:yellow" value='.$addTableArr[$i+3].'></td></tr>';
                                $Error = 'Please try to change the words that are highlighted in yellow color as these already exist in the system';
                                $ErrorDB=true;
                            }
                        }
                    echo "</table>";

                }  
                else
                    echo('<table class="addTable"></table>');
                ?>
            </div>
        </div>

        <div class="row3">
            <div class="col-sm-2" align="left">
                <?php
                    if(strlen($positionTable) > 0 ) //means this page is rediected and echo all table else just create table node and it will be created when Next button is clicked
                    {  //$positionTable = "<table><tbody><tr><td>Left</td><td>Right</td><td>Pos</td></tr><tr><td>0</td><td>0</td><td>0</td></tr></tbody></table>";
                        //$pos = strpos($positionTable,"\"");
                        //echo('here i am '.$pos.' great');
                        //$myvar= str_replace("class=\"positionTable\"","",$positionTable);//"<table class=\"positionTable\"><tbody><tr><td>Left</td><td>Right</td><td>Pos</td></tr><tr><td>0</td><td>0</td><td>0</td></tr><tr><td>0</td><td>0</td><td>0</td></tr><tr><td>0</td><td>0</td><td>0</td></tr><tr><td>0</td><td>0</td><td>0</td></tr><tr><td>0</td><td>0</td><td>0</td></tr><tr><td>0</td><td>0</td><td>0</td></tr></tbody></table>";
                        //echo($myvar);
                        ////echo"<table class=\"positionTable\">";
                        ////echo $positionTable;
                        ////echo "</table>";
                        ////echo($positionTable);
                       
                        echo"<table class='positionTable'>";
                        echo"<tr><th>Left</th><th>Right</th><th>Pos</th></tr>";
                        $posTableArr = explode(',',$positionTable);
                        $count = count($posTableArr);
                        for($i = 0; $i < $count; $i = $i+3)
                            echo '<tr><td>'.$posTableArr[$i].'</td><td>'.$posTableArr[$i+1].'</td><td>'.$posTableArr[$i+2].'</td></tr>';
                        echo "</table>";
                        //echo($positionTable);
                    }  
                    else
                        echo("<table class='positionTable'></table>");
                ?>
            </div>
        </div>
                     
            <div class="row4">
                <div class="col-sm-10" align="left">
                    <div style="margin-top: 10px"> 
                    <?php
                        $display='display:none';
                        $status="disabled";
                        if(strlen($addTable) > 0) //means page is redirecte and display buttons
                        {
                            $display='';
                        
                        if($ErrorDB==false)
                            $status="enabled";
                        else
                            $status="disabled";
                        }
                        echo('<h4 name="Error" style="margin-top: 40px;margin-left:170px;display:\'\'">'.$Error.'</h4>  </br>');                               
			            echo('<input type="button" name="btnValidate" class="btn btn-primary" onclick=\'onValidate()\' style="margin-top: 40px;margin-left:170px;'.$display.'" value="Validate">');
                        echo('<input type="button" name="btnAddPuzzle" class="btn btn-primary" onclick=\'addPuzzleToDB()\' style="margin-top: 40px;margin-left:10px;'.$display.'" value="Add Puzzle" '.$status.'>');
                
                    ?>
            </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/puzzle.js"></script>

<script>
    function showAddPuzzleForm()
    {
       var addTable = document.getElementsByClassName("addTable");
       var posTable = document.getElementsByClassName("positionTable");
       
       //Header row of Add Table and Position Table
       var AddTableHTML = "<table class=\"addTable\"><tbody><tr><th>No</th><th>Character</th><th>Clue(word_1)</th><th>Synonym(word_2)</th></tr>";
       var PosTableHTML = "<table class=\"positionTable\"><tbody><tr><th>Left</th><th>Right</th><th>Pos</th></tr>";

       var word = document.getElementsByName('word')[0].value;
       var wordLen = word.length;
    
       var charArray = $.post("callFunction.php",
        {
            inputWord: word
        },
        function(data,status){
            
            charArray = data;
            
            alert(status);
    });
    
       
       
       alert(charArray);
      
       if (wordLen <= 0)
            alert("A word needs to be added first");
       else{
            //add record to Add table
            var row;
            for(var i=0;i < wordLen;i++)
            {
                row = row +"<tr>";
                row = row + "<td>" + (i+1) + 
                    "</td>"+"<td>" + word[i]+ 
                    "</td>"+"<td>" + "<input type=\'text\'>"+ 
                    "</td>"+"<td>" + "<input type=\'text\'>"+
                    "</td>";

                row= row + "</tr>";
                //alert(row);
           }
           addTable[0].outerHTML = AddTableHTML+row+"</tbody></table>";
           
           // record to pos table
           row="";
           for(var i=0;i < wordLen;i++)
            {
                row = row +"<tr>";
                row = row + "<td>0</td><td>0</td><td>0</td>";

                row= row + "</tr>";
                //alert(row);
           }
           posTable[0].outerHTML = PosTableHTML+row+"</tbody></table>";
           
           //show the two validate and add puzzle buttons which were created and hidden in first section
           document.getElementsByName("btnValidate")[0].style.display="";
           document.getElementsByName("btnAddPuzzle")[0].style.display="";
           document.getElementsByName("btnAddPuzzle")[0].disabled=true; //disable this button and show after validate success
       }
    }

   
    function onValidate(){
        //alert("from validate");
        var addTable = document.getElementsByClassName("addTable");
        //Validation 1, iterate each row to verify if cell 2 character is in cell 4 word. Start from row 2
        var validate=true; //make it false if any validation fails
        var rows = addTable[0].rows;
        for(var i=1; i < rows.length ; i++)
        {
            
            var strCharacter = rows[i].cells[1].innerHTML;
            var strRightWord= rows[i].cells[3].querySelector('input').value;
            
            if(strRightWord.indexOf(strCharacter) <= -1)
            {
                validate=false;
                rows[i].cells[3].style.backgroundColor="red";
            }
            else
            rows[i].cells[3].style.backgroundColor="black";
        }

        if(!validate)
        {
            $Error= ("Please try to change the word_2 that is highlighted in RED color as this word does not contain the required character");
            document.getElementsByName("Error")[0].style.display="";
            document.getElementsByName("Error")[0].innerHTML=$Error;
            document.getElementsByName("btnAddPuzzle")[0].disabled=true; 
            return;
        }

        //Validation 3: check whether all the word pairs are entered or not. If any rows are missing, highlight those in BLUE background and show the following message.
        validate = validateWordPair();
        if(!validate)
            return;
        
        //Populate Postion table
        populatePositionTable();        

        //Validation 2: ) If any of the word_1 or word_2 already exist in the database, then those cells will be highlighted in YELLOW background. And a message appears at the bottom of the table
        validateDB();
        
        
    }

    function populatePositionTable()
    {
        //read values from add table and put in the positoin table
        var positionTable=document.getElementsByClassName("positionTable")[0];
        var addTable = document.getElementsByClassName("addTable")[0];
        
        for(var j=1;j < positionTable.rows.length ; j++)
        {
            for(var k=0; k < positionTable.rows[j].cells.length; k=k + 3)
            {
                positionTable.rows[j].cells[k].innerHTML = addTable.rows[j].cells[2].querySelector('input').value.length;
                positionTable.rows[j].cells[k+1].innerHTML = addTable.rows[j].cells[3].querySelector('input').value.length;
                
                var char = addTable.rows[j].cells[1].innerHTML;
                var word2 =  addTable.rows[j].cells[3].querySelector('input').value;
                var pos = word2.indexOf(char);

                positionTable.rows[j].cells[k+2].innerHTML=pos+1;

            }
        }

    }
    function validateDB()
    {
        //For db validation redirect to same page and pass values of all fields data in parameter. 
        
        var word = document.getElementsByName("word")[0].value;
        var owner = document.getElementsByName("owner")[0].value;
        var submittedBy=document.getElementsByName("submittedBy")[0].value;
        //var addTable= document.getElementsByClassName("addTable")[0].outerHTML;
        var addTable = addAddTableContentToArray();
        //var positionTable=document.getElementsByClassName("positionTable")[0].innerHTML;
        var positionTable= addPositionTableContentToArray();
        //add escape character \ with " for add and position tables
        
        //positionTable = positionTable.replace(/"/g, '\\\"');
        //$positionTable="<tbody><tr><td>Left</td><td>Right</td><td>Pos</td></tr><tr><td>0</td><td>0</td><td>0</td></tr></tbody>";
        //alert(positionTable);
        window.location = "addPuzzle.php?word="+word+"&owner="+owner+"&submittedBy="+submittedBy+"&addTable="+addTable+"&positionTable="+positionTable;
        
    }

    function addAddTableContentToArray()
    {
        var addTable1=document.getElementsByClassName("addTable")[0];
        var output = new Array();
        var i=0;

        for(var j=1;j < addTable1.rows.length ; j++)
        {
            for(var k=0; k < addTable1.rows[j].cells.length; k++)
            {
                //alert(k);
                if((k % 4 == 0) || (k % 4 == 1))
                    output[i] = addTable1.rows[j].cells[k].innerHTML;
                else
                    output[i] = addTable1.rows[j].cells[k].querySelector('input').value;
                i++;
            }
        }

        return output;
    }
    
    function addPositionTableContentToArray()
    {
        var positionTable1=document.getElementsByClassName("positionTable")[0];
        var output = new Array();
        var i=0;

        for(var j=1;j < positionTable1.rows.length ; j++)
        {
            for(var k=0; k < positionTable1.rows[j].cells.length; k++)
            {
                output[i] = positionTable1.rows[j].cells[k].innerHTML;
                i++;
            }
        }

        return output;
    }


    function validateWordPair()
    {
        var addTable = document.getElementsByClassName("addTable");
        var rows = addTable[0].rows;
        var validate=true;
        for(var i=1; i < rows.length ; i++)
        {
            
            var strLeftWord = rows[i].cells[2].querySelector('input').value;
            var strRightWord= rows[i].cells[3].querySelector('input').value;
            
            if(strRightWord.length <=0 || strLeftWord.length <=0)
            {
                validate=false;
                rows[i].cells[2].style.backgroundColor="blue";
                rows[i].cells[3].style.backgroundColor="blue";
            }
            else
            {
                rows[i].cells[2].style.backgroundColor="black"
                rows[i].cells[3].style.backgroundColor="black";
                document.getElementsByName("Error")[0].style.display="none";
            }
        }

        if(!validate)
        {
            $Error = 'Some word pairs (synonyms) are missing. Please add those';
            document.getElementsByName("Error")[0].style.display="";
            document.getElementsByName("btnAddPuzzle")[0].disabled=true; 
            
            document.getElementsByName("Error")[0].innerHTML=$Error;
            return false;
        }
        
        else
            return true;
        //on validations success
        document.getElementsByName("btnAddPuzzle")[0].disabled=false; //enable add puzzle button on successful validation

    }
    function addPuzzleToDB(){
        
        alert("from addPuzzle");
        var word = document.getElementsByName("word")[0].value;
        var owner = document.getElementsByName("owner")[0].value;
        var submittedBy=document.getElementsByName("submittedBy")[0].value;

        var addTable = document.getElementsByClassName("addTable");
        var addTablerows = addTable[0].rows;
       
        var positionTable=document.getElementsByClassName("positionTable");
        var positionTablerows = positionTable[0].rows;

        var query="";
        for(var i=1; i < positionTablerows.length ; i++)
        {
            //create insert statments for each row using both tables. Note 0 in Puzzle_id will be replaced with max puzzle_id+1 in add_puzzlesDB.php
            var singleQuery = "INSERT INTO `name_and_synonym`.`puzzles` (`puzzle_id`, `name`, `owner`, `left`, `right`, `position`, `left_word`, `character`, `right_word`,`submitted_by`) VALUES ("
                                +"0"+","+ 
                               "'"+word+"',"+ 
                               "'"+owner+"',"+ 
                               "'"+positionTablerows[i].cells[0].innerHTML+"',"+
                               "'"+positionTablerows[i].cells[1].innerHTML+"',"+
                               "'"+positionTablerows[i].cells[2].innerHTML+"',"+
                               "'"+addTablerows[i].cells[2].querySelector('input').value+"',"+
                               "'"+addTablerows[i].cells[1].innerHTML+"',"+ 
                               "'"+addTablerows[i].cells[3].querySelector('input').value+"',"+ 
                               "'"+submittedBy+"')";

            if(<?php echo ($IS_DEBUG);?>)
                alert(singleQuery);
            var strLeftWord = addTablerows[i].cells[2].querySelector('input').value;
            query=query + singleQuery+"|";
        }
        
        //del last added | from the end of $query. otherwise explode will add additional empty string in its array in update_puzzleDB
        query = query.substr(0,query.length-1);
        //redirect to addpuzzlesdb page.
        window.location = "add_puzzlesDB.php?query=" + query;

    }

    

    
</script>

</body>
</html>