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
	
	$query = "insert into accounts(username, pswd) values ('" . $username . "', MD5('" . $password . "'))";
	mysql_query($query);
	
	$query = "select * from accounts";
	$result=mysql_query($query);
	print("result:  ". $result . "  ");
	
	$query = "select `id` from accounts where username='" . $username . "'";
	$userid=mysql_query($query);
	$userid = mysql_result($userid, 0, 'id');
	
	$query = "create table lit_". $userid ." like lit_testuser";
	mysql_query($query);
	
	session_start();
    if (!isset($_SESSION['activeuser']))
    {
   	  	$_SESSION['activeuser'] = $username;
    }
    if (!isset($_SESSION['activeID']))
    {
    	$_SESSION['activeID'] = $userid;
    }	
}
else    
{	
	echo "exists";
}
   
mysql_close($conn);
?>