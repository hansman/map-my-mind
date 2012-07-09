<?php 


session_start();

if(isset($_SESSION['activeuser']))
	$username=$_SESSION['activeuser'];
else
	$username="guest";

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
   $query = "lock table lit_". $username ." write";
   mysql_query($query);
   
   $query  = "select * from lit_". $username ." where title='". $title ."'";
   $result=mysql_query($query);
   if($result)
   {
   	echo "This paper is already in the data base";
   }
   else 
   {   
     $query  = "insert into lit_". $username ."(doi, author, title, date, month, publisher, volume, issue, startpage, lastpage) values ('". $doi ."','". $author ."','". $title ."','". $date ."','". $month ."','". $publisher ."','". $volume ."','". $issue ."','". $startpage ."','". $lastpage  ."' );";
     //print "$query";
     //echo $query;
 
     $result=mysql_query($query);
     if (!$result) die ("Query Failed.");
   }
   $query = "unlock table";
   mysql_query($query);
   
   mysql_close($conn); 

?>

