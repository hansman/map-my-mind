<?php 


session_start();

if(isset($_SESSION['activeID']))
	$userid=$_SESSION['activeID'];
else
	$userid="guest";

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
   $query = "lock table lit_". $userid ." write";
   mysql_query($query);
   
   $query  = "select * from lit_". $userid ." where title='". $title ."'";
   $result=mysql_query($query);
   if(mysql_num_rows($result))
   {
   	echo "This paper is already in the data base";
   }
   else 
   {   
     $query  = "insert into lit_". $userid ."(doi, author, title, date, month, publisher, volume, issue, startpage, lastpage) values ('". $doi ."','". $author ."','". $title ."','". $date ."','". $month ."','". $publisher ."','". $volume ."','". $issue ."','". $startpage ."','". $lastpage  ."' );";
 
     $result=mysql_query($query);
     if (!$result) die ("Query Failed.");
   }
   $query = "unlock table";
   mysql_query($query);
   
   mysql_close($conn); 

?>

