<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Synonym</title>


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
		<?php
			include 'header.php';
			include 'db_configuration.php';
		?>
    </div> 
    
	<div class="row">

    <div class="panel panel-primary" style="margin-top: 30px">
        <div class="Please enter the following settings">

            <form class="contact" action="editSyn2.php" target="" method="get" role="form">
                <div class="row">
                    <div class="col-lg-12" style="margin-top: 20px" align="center">						
							
                            <div class="form-group">
								<label for="comment">Enter An Existing Puzzle Word</label>
								
                                <input type="text" name="synonym" value="" class="form-control" rows="2" id="comment" style="margin:0%"/>

                                <div style="margin-top: 10px">
                                    <button onclick="eraseText()" type="button" class="btn btn-default" id="clrbutton">Clear</button>
									<input type="submit" value="Submit" class="btn btn-primary"/>
                                </div>
                            </div>




                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6" style="margin-bottom: 10px; margin-left: 15px">
                        <!--<button onclick="eraseText()" type="button" class="btn btn-default" id="clrbutton">Clear</button>-->
						
                    </div>

                </div>

            </form>
			
        </div>
		
    </div>
	
</div>
<a class='sub' id='homesub' href="index.php">Return Home</a>
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