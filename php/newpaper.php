<?php 


session_start();

if(isset($_SESSION['activeuser']))
	$username=$_SESSION['activeuser'];
else
	$username="guest";

  $doi = trim($_GET["doi"]);  
  $date= trim($_GET["date"]); 
  $month= trim($_GET["month"]); 
  $publisher= trim($_GET["publisher"]); 
  $author= trim($_GET["author"]); 
  $title= trim($_GET["title"]); 
  $volume= trim($_GET["volume"]); 
  $issue= trim($_GET["issue"]); 
  $startpage= trim($_GET["startpage"]); 
  $lastpage= trim($_GET["lastpage"]); 
  
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
   if(mysql_num_rows($result))
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

