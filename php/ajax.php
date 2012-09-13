<?php 

	include 'engines/PaperData.php';
	include 'engines/DOIparser.php';

	$type = $_GET['type'];		
	$args=$_GET['args'];
	
	
	switch($type)
	{
		case "paperdata":   $engine = new PaperData();
							break;
		case "getdoi":		$engine = new DOIparser($args[0]);
							break; 
		
		
	}

	print $engine->run();
	
?>