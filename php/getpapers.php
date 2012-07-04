<?php 
  
include 'config.php';

$conn = mysql_connect("$dbhost", "$dbuser", "$dbpass");

if (!$conn)
{
	die('Could not connect: ' . mysql_error());
}

mysql_select_db("DM");

$query = "select doi, author, title, date, month, publisher, volume, issue, startpage, lastpage from lit_testuser";

	
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
   
   
mysql_close($conn);


?>