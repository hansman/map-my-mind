<?php 

    include_once 'EngineContainer.php';

	class SignUp extends EngineContainer
	{
		private $usrn;
		private $pswd;
		private $meta;
		
		function __construct($a)
		{
			$this->meta['engine']='signup';
			$this->connect();
			$this->usern=trim($a[0]);
			$this->pswd=trim($a[1]);
		}
		
		public function run()
		{	
			  
			$query = "lock table accounts read";
			mysql_query($query);

			$query = "select username from accounts where username='". $this->usern ."' ";
			$result=mysql_query($query);

			$query = "unlock table";
			mysql_query($query);

			if (!$result) 
			{
				$this->meta['status']='failed';
	   			die ("Query Failed.");
			}
			else if (mysql_num_rows($result) == 0  )
			{	
				$query = "insert into accounts(username, pswd) values ('" . $this->usern . "', MD5('" . mysql_real_escape_string($this->pswd) . "'))";
				if(!mysql_query($query))
					$this->meta['status']='failed';
				
				$query = "select `id` from accounts where username='" . $this->usern . "'";
				$userid=mysql_query($query);
				$userid = mysql_result($userid, 0, 'id');
	
				$query = "create table lit_". $userid ." like lit_testuser";
				if(!mysql_query($query))
					$this->meta['status']='failed';
				else
					$this->meta['status']='passed';
				
				$query = "create table maps_". $userid ." like maps_testuser";
				if(!mysql_query($query))
					$this->meta['status']='failed';
				else
					$this->meta['status']='passed';
	
				session_start();
    			if (!isset($_SESSION['activeuser']))
   	  				$_SESSION['activeuser'] = $this->usern;
    			if (!isset($_SESSION['activeID']))
    				$_SESSION['activeID'] = $userid;
			}
			else    
				$this->meta['status']='exists';
			
			return $this->buildjson(null,$this->meta);
		}	
	}
?>