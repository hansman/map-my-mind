<?php 

  session_start();

  if(isset($_SESSION['activeuser']))
	$username=$_SESSION['activeuser'];
  else
	$username="guest";

  $title = $_GET["title"];
  include 'config.php';
  
  $conn = mysql_connect("$dbhost", "$dbuser", "$dbpass");
  if (!$conn)
  {
  	die('Could not connect: ' . mysql_error());
  }
  
   mysql_select_db("DM");
   $query ="delete from lit_". $username ." where title='". $title ."'";
   
   $result=mysql_query($query);
   if (!$result) die ("Query Failed.");
  
   mysql_close($conn);   

?>

