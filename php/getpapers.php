<?php 
  
	session_start();

	if(isset($_SESSION['activeID']))
		$userid=$_SESSION['activeID'];
	else
		$userid="guest";

	include 'config.php';

	$conn = mysql_connect("$dbhost", "$dbuser", "$dbpass");

	if (!$conn)
	{
		die('Could not connect: ' . mysql_error());
	}

	mysql_select_db("DM");

	$query = "lock table lit_". $userid ." read";
	mysql_query($query);

	$query = "select doi, author, title, date, month, publisher, volume, issue, startpage, lastpage from lit_".$userid;
	
	$result=mysql_query($query);
	
	   
    $jsonrows = array();
    while($row = mysql_fetch_assoc($result)) 
    {
     $jsonrows[] = $row;
    }
   
    echo json_encode($jsonrows);
    $query = "unlock table";
    mysql_query($query);
    mysql_close($conn);
?>