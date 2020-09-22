<?php
			if($_GET['synonym'] == ''){				
				header('Location: failed_page.php?x='.'You left the text field blank.');
				return;
			}  
			
			include 'db_configuration.php';
			include 'puzzleGenerator.php';
			
			$synonym = preg_replace('/\x20/', '', $_GET['synonym']);
			$synonymId = checkFKExistence($synonym, $db);
			if ($synonymId < 0) {
				header('Location: failed_page.php?x='.$synonym.' does not exist in the database.');
				return;
			}
			
			//echo '$synonym = '.$synonym;
            $clueIDs = getClueIDs($synonym, $db);			
            $synonymIDs = getSynIDs($synonymId, $db);
            $clueWordArray = getClueWords($synonymId, $synonymIDs, $db);
			
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Synonyms</title>


    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via  -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container">

	
	<div class="row">
		<?php include 'header.php';?>
    </div> 
    
	<div class="row">
	<h1 class="text-center">Edit Synonyms</h1>
    <div class="panel panel-primary" style="margin-top: 30px">
        <div class="Please enter the following settings">

            <!--<form class="contact" action="editSyn2.php" target="" method="get" role="form">-->
            <form action="process_wordPair_edit2.php" method="post">
                <div class="row">
                    <div class="col-lg-12" style="margin-top: 20px" align="center">						
							
                            <div class="form-group">
								<label for="comment">Change Synonyms</label>
								<input type="text" name="synonym" value="<?php echo $clueWordArray;?>" class="form-control" rows="2" id="comment" style="margin:0%"/>
								<input type="hidden" name="old" value="<?php echo $clueWordArray;?>" />
								<input type="hidden" name="id" value="<?php echo printID($synonymIDs);?>" />
                                <div style="margin-top: 10px">
									
									<input type="submit" value="Update" class="btn btn-primary"/>
                                </div>
                            </div>
                    </div>
                </div>         

            </form>
        </div>
    </div>
	<a class='sub' id='homesub' href="index.php">Return Home</a>
</div>
</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>