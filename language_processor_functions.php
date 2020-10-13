<?php
	require('./IndicTextAnalyzer/word_processor.php');
	function getWordChars($word)
	{
		$letters = new wordProcessor($word,"");
		$logicalChars = $letters->getLogicalChars();
		return $logicalChars;
	}
?>