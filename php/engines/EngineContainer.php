<?php 

	class EngineContainer
	{	
	   protected $conn;	
	   protected $userid;
	   protected $usernm;
	      
	   function connect() 
	   {	   	
	   	    require("config.php");        
	   		$this->conn = mysql_connect($dbhost, $dbuser, $dbpass);
			if (!$this->conn)
			{
				die('Could not connect: ' . mysql_error());
			}
			mysql_select_db("DM");			
   	   }
   	   
   	   function loadsess()
   	   {
   	   		session_start();   	   	
   	   		if(isset($_SESSION['activeuser']))
   	   			$this->usernm=$_SESSION['activeuser'];
   	   		else
   	   			$this->usernm="guest";
   	   		
   	   		if(isset($_SESSION['activeID']))
   	   			$this->userid=$_SESSION['activeID'];
   	   		else
   	   			$this->userid="guest";
   	   }
   	   
	
	   function __destruct() 
	   {
	   		if($this->conn)
       			mysql_close($this->conn);
       		//echo "disconnected";
   	   }	
	}
	
?>