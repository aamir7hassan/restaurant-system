<?php 


	ini_set('max_input_time','400');
	ini_set('max_execution_time', '3600');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	// Get real path for our folder
	$rootPath = realpath('./');
	// $rootPath = realpath('./'); // for current directory

	// Initialize archive object
	$zip = new ZipArchive();
	$zip->open('w8r-'.date('d-m-Y H:i:s A').'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

	// Create recursive directory iterator
	// @var SplFileInfo[] $files v
	$files = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($rootPath),
		RecursiveIteratorIterator::LEAVES_ONLY
	);

	foreach ($files as $name => $file)
	{
		// Skip directories (they would be added automatically)
		if (!$file->isDir())
		{
			// Get real and relative path for current file
			$filePath = $file->getRealPath();
			$relativePath = substr($filePath, strlen($rootPath) + 1);

			// Add current file to archive
			$zip->addFile($filePath, $relativePath);
		}
	}

	// Zip archive will be created only after closing object
	$zip->close();
	
	/*
	
	$zip = new ZipArchive; 
	$zip->open('vendor.zip'); // zip file name
	  
	// Extracts to current directory 
	$zip->extractTo('./'); //for current directory
	//$zip->extractTo('roletai-mini-diena-naktis'); 
	  
	$zip->close();
	*/
?>