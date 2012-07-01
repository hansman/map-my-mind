<?php 


  $doi = $_GET["doi"];
  
  $date=$_GET["date"];
  $publisher=$_GET["publisher"];
  $author=$_GET["author"];
  $title=$_GET["title"];
  
  include 'config.php';
  
  
  $conn = mysql_connect("$dbhost", "$dbuser", "$dbpass");
  
  if (!$conn)
  {
  	print "test";
  	die('Could not connect: ' . mysql_error());
  }
  
   mysql_select_db("DM");
   $query  = "insert into gcclab.regressionmail (name) value ('". $address ."')";
   $query  = "insert into lit_testuser(doi, author, title, date, publisher) values ('". $doi ."','". $author ."','". $title ."','". $date ."','". $publisher ."' );";
   print "$query";
  //$query  = "insert into lit_testuser(doi) values ( $doi);";
   $result=mysql_query($query);
   if (!$result) die ("Query Failed.");
   //$result = $conn->get_results($query);
  //if ($result) die ("Submit Failed.");
  
  mysql_close($conn);
  
   

?>

