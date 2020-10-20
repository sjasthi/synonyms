<?php

  $nav_selected = "ADMIN"; 
  $left_buttons = "YES"; 
  $left_selected = "IMPORT"; 

  include("./nav.php");
 

  //include 'PHPExcel/PHPExcel/IOFactory.php';
  global $db;

  ?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>IMPORT</title>
	
<!-- Latest compiled and minified CSS -->
<link href="css/style.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

    
<div class="container">
<div class="row">
		<?php
			//include 'header.php';
		?>
    </div> 

<div class="row">  
<h1>Import words into the database.</h1>
<textarea rows="12" cols="150">
<?php

 $target_dir = "uploads/";
$target_file = $target_dir.basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
if($imageFileType != "xls" && $imageFileType != "xlsx") {
    echo "Sorry, only xls & xlsx files are allowed.";
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
		echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
	}
}


//  Include PHPExcel_IOFactory
include 'PHPExcel/PHPExcel/IOFactory.php';

$inputFileName = $target_dir . basename($_FILES["file"]["name"]);

//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();

//Check # of columns
if ($highestColumn != "B") {
	//echo "Sorry, the file is not supported. The number of columns should be 2.";	
	//exit();
}

//connect to DB
include 'db_configuration.php';
include 'puzzleGenerator.php';

//Trancate the DB
$queryT = "DELETE FROM characters";
$stmt = $db->prepare($queryT);
$stmt->execute();
$stmt->free_result();

$query = "DELETE FROM synonyms";
$result = mysqli_query($db, $query);
echo "&#13;&#10;";
//  Loop through each row of the worksheet in turn
for ($row = 2; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE,
									FALSE); 
	
	
	if ($rowData[0][1] == ''){
			//echo 'You typed only one word, should be minimum 2.<br>';
			//echo '<a href="./wordPairs.php"><button>Return to add synonyms</button></a>';
			//echo '<a href="./index.php"><button>Return Home</button></a>';
			//echo 'There is no word.';
			continue;
	}
	//print_r ($rowData);
	echo $rowData[0][1];	
	
	$allSynonymsTemp = explode(',', $rowData[0][1]);
	//echo "  '".$allSynonymsTemp[0]."' and '".$allSynonymsTemp[1]."'<br>";
	$allSynonyms = cleanSpaces($allSynonymsTemp);
		if (sizeof($allSynonyms) < 1){
			//echo 'You typed only one word, should be minimum 2.<br>';
			//echo '<a href="./wordPairs.php"><button>Return to add synonyms</button></a>';
			//echo '<a href="./index.php"><button>Return Home</button></a>';
			echo 'There is no word.';
			continue;
		}
		
        $inputWord = $allSynonyms[0];
        //echo "  '".$allSynonyms[0]."' and '".$allSynonyms[1]."'<br>";
		
		$wordExists = checkFKExistence($inputWord, $db);
        //echo $wordExists;
		if ($wordExists > 0) {
			echo " update ".$allSynonyms[0];
			updateWord($db, $wordExists, $allSynonyms);
			
		} else {
			echo " add ".$allSynonyms[0];
			addWord($allSynonyms, $db);
			
		}

		echo "&#13;&#10;";		
}

$db->close();
//header("Location: index.php"); /* Redirect browser */
echo "&#13;&#10; The file was imported.";
?>
</textarea>
<BR />
<a class='sub' id='homesub' href="index.php">Return Home</a>
</div>
</div>
</body>

</html>