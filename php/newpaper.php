<?php 


  $doi = $_GET["doi"];  
  $date=$_GET["date"];
  $month=$_GET["month"];
  $publisher=$_GET["publisher"];
  $author=$_GET["author"];
  $title=$_GET["title"];
  $volume=$_GET["volume"];
  $issue=$_GET["issue"];
  $startpage=$_GET["startpage"];
  $lastpage=$_GET["lastpage"];
  
  include 'config.php';
  
  
  $conn = mysql_connect("$dbhost", "$dbuser", "$dbpass");  
  if (!$conn)
  {
  	die('Could not connect: ' . mysql_error());
  }
  
   mysql_select_db("DM");
   $query  = "insert into lit_testuser(doi, author, title, date, month, publisher, volume, issue, startpage, lastpage) values ('". $doi ."','". $author ."','". $title ."','". $date ."','". $month ."','". $publisher ."','". $volume ."','". $issue ."','". $startpage ."','". $lastpage  ."' );";
   //print "$query";
 
   $result=mysql_query($query);
   if (!$result) die ("Query Failed.");
  
   mysql_close($conn); 

?>

