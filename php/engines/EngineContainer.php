<?php 

	class EngineContainer
	{	
	   protected $conn;	
	   protected $userid;
	   protected $usernm;
	   protected $mapnm;
	  
	      
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
   	   		
   	   		if(isset($_SESSION['activeMap']))
   	   			$this->mapnm=$_SESSION['activeMap'];
   	   		else
   	   			$this->mapnm=null;
   	   		
   	   }
   	   
   	   
   	   function buildjson($d,$m)
   	   {
   	   		$jsonobj['data']=$d;
   	   		$jsonobj['meta']=$m;
   	   		return json_encode($jsonobj);
   	   }
   	   
	
	   function __destruct() 
	   {
	   		if($this->conn)
       			mysql_close($this->conn);
       		//echo "disconnected";
   	   }	
	}
	
?>