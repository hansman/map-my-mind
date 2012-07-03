<?php 

  $title = $_GET["title"];
  include 'config.php';
  
  $conn = mysql_connect("$dbhost", "$dbuser", "$dbpass");
  if (!$conn)
  {
  	print "test";
  	die('Could not connect: ' . mysql_error());
  }
  
   mysql_select_db("DM");
   $query  = "delete from lit_testuser where title=  '". $title ."'";
   
   echo "$query";
   
   $result=mysql_query($query);
   if (!$result) die ("Query Failed.");
  
   mysql_close($conn);   

?>

