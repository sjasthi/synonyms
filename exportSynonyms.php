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
	$query = "SELECT * FROM `synonyms`";
	
	// Run the query
	$result = true;
	
	$masters = getmasters($db);
	$synonyms = getsynonyms($db, $masters);
	
	//print_r ($synonyms);
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
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'No' );
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, 'Synonym' );
		
			
		// Increment row 
		$rowCount++;
		
		// Output data of each row
		for ($i = 0;$i < count($synonyms); $i++) 
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $i);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, getstring($db, $synonyms[$i]) );
			//print_r ($synonyms[$i]);
	
			// Increment the Excel row counter
			$rowCount++; 
		}
		// Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
		$excel_file = new PHPExcel_Writer_Excel2007($objPHPExcel); 
		
		// Write and save the file
		$excel_file->save('downloads/synonyms.xlsx'); 
		
		echo "<h2>The word list was successfully exported.</h2>";
		echo '<form method="GET" action="downloads/synonyms.xlsx">';
		echo '<button type="submit">Download</button>';
		echo '</form>';
	}
	// Else if not successful
	else
	{
		echo "<h2>Failed</h2>";
	}
	
	function getmasters($db) {
		$masters = array();
		// Create the query
		$query = "SELECT * FROM `synonyms` WHERE SynID = ClueID";
	
		// Run the query
		$result = mysqli_query($db, $query);
		
		while($row = $result->fetch_assoc()) 
		{
			//echo $row['SynonymWord'];
			//echo '<br />';
			$masters[] = $row['SynID'];
		}
		
		return $masters;
	}
	
	function getsynonyms($db, $masters) {
		$words = array();
		
		
		for ($i = 0;$i < count($masters); $i++) {
			$temp = getrow($db, $masters[$i]);
			//print_r ($temp);
			//echo '<br>';
			$words[] = $temp;			
		}
		return $words;
	}
	
	function getrow($db, $id) {
		$temp = array();
		// Create the query
			$query = "SELECT * FROM `synonyms` WHERE ClueID = ".$id;
			//echo $query;
			// Run the query
			$result = mysqli_query($db, $query);
			while($row = $result->fetch_assoc()) 
			{
				//echo $row['SynID'];
				//echo '<br />';
				$temp[] = $row['SynonymWord'];
			}
			return $temp;
	}
	function getstring($db, $masters) {
		$string = '';
		for ($i = 0;$i < count($masters); $i++) {
			
			$string .= $masters[$i].', ';
			
		}
		return rtrim($string, ', ');
	}
?>
<a class='sub' id='homesub' href="index.php">Return Home</a>
</div>
</div>
</body>

</html>