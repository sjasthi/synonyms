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
	<div class="container">

    <div class="row">
		<?php
            include 'header.php';
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

            <!--/form-->
			</div>
		</div>
    </div> 
        <div class="row2">
            <div class="col-sm-10" align="left">
            <?php
                if(strlen($addTable) > 0 ) //means this page is rediected and echo all table else just create table node and it will be created when Next button is clicked
                    echo ($addTable);    
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
                        echo"<table class=\"positionTable\">";
                        echo $positionTable;
                        echo "</table>";
                        echo($positionTable);
                     }   
             else
                    echo('<table class="positionTable"></table>');
            ?>
            </div>
        </div>

         <div style="margin-top: 10px"> 
            <?php
                $display=';display:none';
                if(strlen($addTable) > 0) //means page is redirecte and display buttons
                    $display='';
                echo('<h4 name="Error" style="margin-top: 40px;margin-left:170px'.$display.'">Some word pairs (synonyms) are missing. Please add those</h4>  </br>');                               
			    echo('<input type="button" name="btnValidate" class="btn btn-primary" onclick=\'validate()\' style="margin-top: 40px;margin-left:170px;'.$display.'" value="Validate">');
                echo('<input type="button" name="btnAddPuzzle" class="btn btn-primary" onclick=\'addPuzzle()\' style="margin-top: 40px;margin-left:10px;'.$display.'" value="AddPuzzle">');
                
            ?>
        </div>
                
</div>
<script>
    function showAddPuzzleForm()
    {
       var addTable = document.getElementsByClassName("addTable");
       var posTable = document.getElementsByClassName("positionTable");
       
       //Header row of Add Table and Position Table
       var AddTableHTML = "<table class=\"addTable\"><tbody><tr><td>No</td><td>Character</td><td>Clue(word_1)</td><td>Synonym(word_2)</td></tr>";
       var PosTableHTML = "<table class=\"positionTable\"><tbody><tr><td>Left</td><td>Right</td><td>Pos</td></tr>";

       var word = document.getElementsByName('word')[0].value;
       var wordLen = word.length;
      
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

   
    function validate(){
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
            alert("Please try to change the word_2 that is highlighted in RED color as this word does not contain the required character");
            document.getElementsByName("btnAddPuzzle")[0].disabled=true; 
            return;
        }

        //Validation 2: ) If any of the word_1 or word_2 already exist in the database, then those cells will be highlighted in YELLOW background. And a message appears at the bottom of the table
        validateDB();
        //Validation 3: check whether all the word pairs are entered or not. If any rows are missing, highlight those in BLUE background and show the following message.
        //3rd validation will be done by php code when page is rediected. following method will not be called 
        validateWordPair();
        
    }

    function validateDB()
    {
        //For db validation redirect to same page and pass values of all fields data in parameter. 
        
        var word = document.getElementsByName("word")[0].value;
        var owner = document.getElementsByName("owner")[0].value;
        var submittedBy=document.getElementsByName("submittedBy")[0].value;
        var addTable= document.getElementsByClassName("addTable")[0].outerHTML;
        var positionTable=document.getElementsByClassName("positionTable")[0].innerHTML;

        //add escape character \ with " for add and position tables

        //positionTable = positionTable.replace(/"/g, '\\\"');
        //$positionTable="<tbody><tr><td>Left</td><td>Right</td><td>Pos</td></tr><tr><td>0</td><td>0</td><td>0</td></tr></tbody>";
        //alert(positionTable);
        window.location = "addPuzzle.php?word="+word+"&owner="+owner+"&submittedBy="+submittedBy+"&addTable="+addTable+"&positionTable="+positionTable;
        
    }

    function validateWordPair()
    {
        var addTable = document.getElementsByClassName("addTable");
        var rows = addTable[0].rows;
        
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
            document.getElementsByName("Error")[0].style.display="";
            document.getElementsByName("btnAddPuzzle")[0].disabled=true; 
            return;
        }
        
        //on validations success
        document.getElementsByName("btnAddPuzzle")[0].disabled=false; //enable add puzzle button on successful validation

    }
    function addPuzzle(){
        alert("from addPuzzle");
    }

    

    
</script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/puzzle.js"></script>
</body>
</html>