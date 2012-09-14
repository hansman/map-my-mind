<?php 

	class SQLcontainer
	{
	
	   protected $conn;
	   
	   function __construct() 
	   {
	   	
	   	    require("config.php");
        
	   		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
			if (!$conn)
			{
				die('Could not connect: ' . mysql_error());
			}
			mysql_select_db("DM");
			
   	   }
	
	   function __destruct() 
	   {
       		mysql_close($conn);
       		//echo "disconnected";
   	   }
	
	
	}




?>