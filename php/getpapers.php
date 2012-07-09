<?php 
  
session_start();

if(isset($_SESSION['activeuser']))
	$username=$_SESSION['activeuser'];
else
	$username="guest";



include 'config.php';

$conn = mysql_connect("$dbhost", "$dbuser", "$dbpass");

if (!$conn)
{
	die('Could not connect: ' . mysql_error());
}

mysql_select_db("DM");

$query = "lock table lit_". $username ." read";
mysql_query($query);


$query = "select doi, author, title, date, month, publisher, volume, issue, startpage, lastpage from lit_".$username;

	
$result=mysql_query($query);
if (!$result) 
{ 
	echo "failed";	
	die ("Query Failed.");
}

   
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