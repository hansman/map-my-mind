<?php 
  
include 'config.php';

$username = $_GET["email"];
$password = $_GET["pass"];

$conn = mysql_connect("$dbhost", "$dbuser", "$dbpass");
if (!$conn)
{
	die('Could not connect: ' . mysql_error());
}

mysql_select_db("DM");
$query = "lock table accounts read";
mysql_query($query);

$query = "select username from accounts where username='". $username ."' ";
$result=mysql_query($query);

$query = "unlock table";
mysql_query($query);

if (!$result) 
{   
	die ("Query Failed.");
}
else if (mysql_num_rows($result) == 0  )
{	
	$query = "lock table accounts write";
	mysql_query($query);
	
	$query = "insert into accounts(username, pswd) values ('" . $username . "', MD5('" . $password . "'))";
	mysql_query($query);
	
	$query = "creat table lit_". $username ." like lit_testuser";
	mysql_query($query);
	
	$query = "unlock table";
	mysql_query($query);
	session_start();
    if (!isset($_SESSION['activeuser']))
    {
   	  $_SESSION['activeuser'] = $username;
    }
	
}
else    
{	
	echo "exists";
}
   
mysql_close($conn);
?>