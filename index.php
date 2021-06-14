<?php

  // set the current page to one of the main buttons
  $nav_selected = "PLAY";

  // make the left menu buttons visible; options: YES, NO
  $left_buttons = "NO";

  // set the left menu button selected; options will change based on the main selection
  $left_selected = "";

  include("./nav.php");
?>

<html>

<head>
<style>
table.center {
    margin-left:auto; 
    margin-right:auto;
  }
</style>
</head>

<body>
<h2 style = "color: #01B0F1;">Welcome to SILC </h3>
</body>

</html>

<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Name in Synonym</title>


    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via  -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->



     <style>
     table.center {
         margin-left:auto; 
         margin-right:auto;
         }
     </style>
</head>
<body>

<div class="container">

	
	<div class="row">
		<?php
		  //include 'header.php';

		?>
    </div> 
    
	<div class="row">

    <div class="panel panel-primary" style="margin-top: 30px">
        <div class="Please enter the following settings">

            <form class="contact" action="puzzle1.php" target="" method="get" role="form">
                <div class="row">
                    <div class="col-lg-12" style="margin-top: 20px" align="center">						
							
                            <div class="form-group">
								<label for="comment">Enter A Word</label>
								
                                <input type="text" name="puzzleWord" class="form-control" rows="2" id="comment" style="margin:0%" placeholder="మీ పేరు టైప్ చేసి Generate బటన్ నొక్కండి."></input>

                                <div style="margin-top: 10px">
                                    <button onclick="eraseText()" type="button" class="btn btn-default" id="clrbutton">Clear</button>
									<input type="submit" class="btn btn-primary" name="id" value="Generate">
									<?php
            
										if(isset($_SESSION["isAdmin"])){
												if($_SESSION["isAdmin"] == 1){													
													echo '<input type="submit" class="btn btn-primary" name="id" formaction="design_puzzle.php" value="Design My Own">';													
												}
										}
										?>
                                    
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