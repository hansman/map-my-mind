<?php 

	include_once 'engines/PaperData.php';
	include_once 'engines/DOIparser.php';
	include_once 'engines/GetUserName.php';
	include_once 'engines/Login.php';

	$type = $_GET['type'];		
	$args=explode(',',$_GET['args']);
		
	switch($type)
	{
		case "paperdata":   $engine = new PaperData();
							break;
		case "getdoi":		$engine = new DOIparser($args[0]);
							break;
		case "getusername":	$engine = new GetUserName();
							break;
		case "login":		$engine = new Login($args[0],$args[1]);
							break;
		
		
	}

	
	print $engine->run();
	
?>