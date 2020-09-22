<!DOCTYPE html>
<html>
<head>
	<title>EXPORT</title>
	
<!-- Latest compiled and minified CSS -->
<link href="css/style.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

    
<div class="container">

<div class="row">    
		<?php
		
			include 'header.php';
		?>		
</div>
<div class="row">
<?php
	// Establish a database connection
	require "db_configuration.php";
	//require "local_config.php";
	
	// Create the query
	$query = "SELECT * FROM `characters`";
	
	// Run the query
	$result = mysqli_query($db, $query);
	
	// If successful
	if($result)
	{
		// Require the PHPExcel Library
		require "PHPExcel/PHPExcel.php";
		
		// Instantiate a new PHPExcel object
		$objPHPExcel = new PHPExcel(); 
		
		// Set the active Excel worksheet to sheet 0
		$objPHPExcel->setActiveSheetIndex(0);

		// Row count
		$rowCount = 1;
		
		// Set the headers
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'PuzzleWord' );
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, 'IndexNumber' );
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'letter');
			
		// Increment row 
		$rowCount++;
		
		// Output data of each row
		while($row = $result->fetch_assoc()) 
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['PuzzleWord'] );
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['IndexNumber'] );
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['letter']);
	
			// Increment the Excel row counter
			$rowCount++; 
		}
		// Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
		$excel_file = new PHPExcel_Writer_Excel2007($objPHPExcel); 
		
		// Write and save the file
		$excel_file->save('downloads/characters.xlsx'); 
		
		echo "<h2>The logical charactres were successfully exported.</h2>";
		echo '<form method="GET" action="downloads/characters.xlsx">';
		echo '<button type="submit">Download</button>';
		echo '</form>';
	}
	// Else if not successful
	else
	{
		echo "<h2>Failed</h2>";
	}
?>
<a class='sub' id='homesub' href="index.php">Return Home</a>
</div>
</div>
</body>

</html>