<!DOCTYPE html>
<html>
<head>
	<title>Administrator Access</title>
	
<!-- Latest compiled and minified CSS -->
<!--link href="css/style.css" rel="stylesheet"-->
<link href="css/bootstrap.min.css" rel="stylesheet">

    <meta charset="utf-8">
   <!--link rel="stylesheet" href="css/main_style.css" type="text/css"-->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!--link rel="stylesheet" href="css/custom_nav.css" type="text/css"-->

</head>
<body>

    
<div class="container">

<div class="row">    
		<?php
		  include 'db_configuration.php';
			include 'header.php';
			if (!isset($_SESSION["isAdmin"])){
                echo '<h2>You did not login as admin</h2>';
				echo '<a href="login.php" class="sub">Return to Login Page</a>';
				exit();
            }
		?>		
</div>

      <form class="form-signin" method="POST">
      
      <?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
      <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
        <!--h2 class="form-signin-heading">Administrator</h2-->
        <div class="input-group">
		</form>
	 <div class="form-group">
	 
	 <div class="container">

	 <!---I tried to make it go to the editSyn2.php page after the clicking on the hyperlink 
	 I looked up on this website to help do this http://stackoverflow.com/questions/6799533/how-to-submit-a-form-with-javascript-by-clicking-a-link 
<!--<form class="contact" action="editSyn2.php" target="" method="get" role="form">	-->			
	<!-- FP10 starts-->
  <table align="center" class="adminTable">
    <tr>
        <td align="center">
            <a href="wordPairs.php"><img src="./pic/addAWord.png" class="adminThumbnailSize"></a>
        </td>
        <td align="center">
            <a href="list_synonyms.php"><img src="./pic/wordList.png" class="adminThumbnailSize">
        </td>
        <td align="center">
            <a href=""><img src="./pic/users.png" class="adminThumbnailSize"></a>
        </td>
        <td align="center">
            <a href="exportSynonyms.php"><img src="./pic/export.png" class="adminThumbnailSize"></a>
        </td>
         <td align="center">
            <a href="exportPuzzles.php"><img src="./pic/export.png" class="adminThumbnailSize"></a>
        </td>
        <td align="center">
            <a href="uploadPage.php"><img src="./pic/import.png" class="adminThumbnailSize">
        </td>
        <td align="center">
            <a href="batch_create.php"><img src="./pic/batch.jpg" class="adminThumbnailSize"></a>
        </td>
        <td align="center">
            <a href="configure.php"><img src="./pic/configure.png" class="adminThumbnailSize"></a>
        </td>
        
    </tr>
    <tr>
        <td align="center"><a href="wordPairs.php">Add Synonym</a></td>
        <td align="center"><a href="list_synonyms.php">Synonyms (Manage)</a></td>
        <td align="center"><a href="">Users</a></td>
        <td align="center"><a href="exportSynonyms.php">Export Words</a></td>
        <td align="center"><a href="exportPuzzles.php">Export Puzzle</a></td>
        <td align="center"><a href="uploadPage.php">Import</a></td>
        <td align="center"><a href="batch_create.php">Puzzles Batch Create</a></td>
        <td align="center"><a href="#">Configure</a></td>
        
    </tr>
    <tr class="separator">
        <td></td>
    </tr>
    <tr>
        <td align="center">
            <a href="" onclick="backUpMessage()"><img src="./pic/backUp.png" class="adminThumbnailSize"></a>
        </td>
        <td align="center">
            <a href=""><img src="./pic/report.png" class="adminThumbnailSize">
        </td>
        <td align="center">
            <a  href=""><img src="./pic/oneWordManyPuzzles.png"
                                                           class="adminThumbnailSize"></a>
        </td>
        <td align="center">
            <a  href=""><img src="./pic/manyWordsOnePuzzle.png"
                                                           class="adminThumbnailSize"></a>
        </td>
        <td align="center">
            <a  href=""><img src="./pic/oneWordManyPuzzlesPlus.png"
                                                                class="adminThumbnailSize">
        </td>
        <td align="center">
            <a href=""><img src="./pic/user_manual.png" class="adminThumbnailSize"></a>
        </td>
    </tr>
    <tr>
        <td align="center"><a href="" onclick="backUpMessage()">Backup</a></td>
        <td align="center"><a href="">Report</a></td>
        <td align="center"><a  href="">One Word <br> Many Puzzle</a></td>
        <td align="center"><a  href="">Many Words <br> One Puzzle</a></td>
        <td align="center"><a href="">One Word <br> Many Puzzle <br> Plus</a></td>
        <td align="center"><a href="">User <br> Manual</a></td>
    </tr>
    </table>

  <!-- FP10 Ends -->			 
  
  <br><br><br>
  <!--form class="contact" action="editSyn2.php" target="" method="get" role="form">
                
	
	
    <div class="form-group">
		    <a href="javascript:{}" onclick="document.getElementById('the_form').submit(); return false;">[1] Edit synonyms for the word  </a>

		
        <input type="text" name="synonym" value="" class="form-control" rows="1" id="" style="width: 27%;float: none;"/>
		<input type="submit" value="Submit" class="btn btn-primary" style="margin-left: 1%"/>
        
    </div>
   </form-->
  
  
   <!-- Hyperlink for  Export -->
  							<!--form id="my_form">
   
    <a href="exportSynonyms.php" >[2] Export the word list (Source: Database; Target: Excel file)  </a>
 <br><br> </form--> 
  
  
  <!-- Hyperlink import and box to find the csv files -->
  							
    
    <a href="javascript:{}" onclick="document.getElementById('the_form').submit(); return false;">[1] Import the word list (Source: Excel File; Target: Database)  </a>
  <form action="import.php" method="post" name="importFrom" onsubmit="return validateForm()" enctype="multipart/form-data">
						
							
							
		<input type="file" name="file" id="file" class="btn"  style="display: inline-block;">
							
		<input type="submit" value="Submit" class="btn btn-primary" style="margin-left: 1%"/>
	</form> <br>
 
  
  
  
     <!--Hyperlink for exporting the logincal char list -->
  							<form id="my_form">
    
    <a href="exportChars.php">[2] Export the logical_char list (Source: Database; Target: Excel file)  </a>
   <br><br> </form> 
  
  
  
  <!-- Hyperlink -->  
  							<!--form id="my_form">
    
    <a href="exportPuzzles.php">[5] Export the puzzle list (Source: Database; Target: Excel file)  </a>
   <br><br> </form--> 
  
  						<form id="my_form">
    <!-- More HTML -->
    <!--a href="javascript:{}" onclick="document.getElementById('the_form').submit(); return false;">[6] Manage Users (add, delete, update) (Extra Credit)  </a>
  <br><br> </form--> 

  <!--a href="list_synonyms.php" >[7] Manage Synonyms (edit, delete)  </a-->
  <br>

  <!--a href="batch_create.php" >[3] Batch Create (Puzzles) </a-->
  <br><br>
	<!-- 
	[1] Edit synonyms for the word  

[2] Export the word list (Source: Database; Target: Excel file)

[3] Import the word list (Source: Excel File; Target: Database)

[4] Export the logical_char list (Source: Database; Target: Excel file)

[5] Export the puzzle list (Source: Database; Target: Excel file)

[6] Manage Users (add, delete, update) (Extra Credit)

[7] Manage Synonyms (edit, delete) (Extra Credit)

[8] Batch Create (Puzzles).

-->
     

		
		 <div>
		<a class='sub' id='homesub' href="index.php">Return Home</a>
		 </div>
      </form> 
	  
	  
</div>
</div>


<script type="text/javascript">
    function eraseText() {
        document.getElementById("comment").value = "";
    }
</script>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>

</html>