<?php 
  
include 'config.php';

$username = $_GET["name"];
$password = $_GET["pass"];

$conn = mysql_connect("$dbhost", "$dbuser", "$dbpass");

if (!$conn)
{
	die('Could not connect: ' . mysql_error());
}

mysql_select_db("DM");


$query = "lock table accounts read";
mysql_query($query);


$query = "select username from accounts where username='". $username ."' and pswd=MD5('". $password ."')";

	
$result=mysql_query($query);


$query = "unlock table";
mysql_query($query);

if (!$result) 
{   
	die ("Query Failed.");
}
else if (mysql_num_rows($result) == 0  )
{
	
	echo "failed";
	
}
else    
{
   session_start();
   if(!isset($_SESSION['activeuser']))
	$_SESSION['activeuser'] = $username;
   	
}


   
mysql_close($conn);


?>