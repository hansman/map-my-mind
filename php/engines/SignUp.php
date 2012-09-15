<?php 

    include_once 'EngineContainer.php';

	class SignUp extends EngineContainer
	{
		private $usrn;
		private $pswd;
		
		function __construct($a)
		{
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
	   			die ("Query Failed.");
			else if (mysql_num_rows($result) == 0  )
			{	
				$query = "insert into accounts(username, pswd) values ('" . $this->usern . "', MD5('" . $this->pswd . "'))";
				mysql_query($query);
				
				$query = "select `id` from accounts where username='" . $this->usern . "'";
				$userid=mysql_query($query);
				$userid = mysql_result($userid, 0, 'id');
	
				$query = "create table lit_". $userid ." like lit_testuser";
				mysql_query($query);
	
				session_start();
    			if (!isset($_SESSION['activeuser']))
   	  				$_SESSION['activeuser'] = $this->usern;
    			if (!isset($_SESSION['activeID']))
    				$_SESSION['activeID'] = $userid;
			}
			else    
				echo "exists";
		}	
	}
?>