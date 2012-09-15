<?php 

	include_once 'engines/PaperData.php';
	include_once 'engines/DOIparser.php';
	include_once 'engines/GetUserName.php';
	include_once 'engines/Login.php';
	include_once 'engines/Logout.php';
	include_once 'engines/NewPaper.php';
	include_once 'engines/RMPaper.php';
	include_once 'engines/SignUp.php';

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
		case "login":		$engine = new Login($args);
							break;
		case "logout":		$engine = new Logout();
							break;
		case "newpaper":	$engine = new NewPaper($args);
							break;
		case "rmpaper":		$engine = new RMPaper($args);
							break;		
		case "signup":		$engine = new SignUp($args);
							break;	
	}

	
	print $engine->run();
	
?>