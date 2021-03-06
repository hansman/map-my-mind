<?php 

	include_once 'engines/PaperData.php';
	include_once 'engines/DOIparser.php';
	include_once 'engines/GetSession.php';
	include_once 'engines/Login.php';
	include_once 'engines/Logout.php';
	include_once 'engines/NewPaper.php';
	include_once 'engines/RMPaper.php';
	include_once 'engines/SignUp.php';
	include_once 'engines/NewPswd.php';
	include_once 'engines/ManageMap.php';

	$type = $_GET['type'];		
	$args=explode(',',$_GET['args']);
		
	switch($type)
	{
		case "paperdata":   $engine = new PaperData();
							break;
		case "getdoi":		$engine = new DOIparser($args[0]);
							break;
		case "getsession":	$engine = new GetSession($args);
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
		case "sendpswd":	$engine = new NewPswd($args);
							break;
		case "managemap":	$engine = new ManageMap($_GET['args']);
							break;
		default:			echo "Problem selecting ajax type in ajax.php";
	}
		
	print $engine->run();
	
?>