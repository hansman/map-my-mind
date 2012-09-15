<?php 

include_once 'EngineContainer.php';

class Login extends EngineContainer
{
	
	private $pswd;
	private $usrn;
	private $meta;
	
	function __construct($a)
	{
		$this->meta['engine']='login';
		$this->connect();
		$this->usrn=$a[0];
		$this->pswd=$a[1];
	}
	
	public function run()
	{
				
		$query = "lock table accounts read";
		mysql_query($query);
		$query = "select id from accounts where username='". $this->usrn ."' and pswd=MD5('". mysql_real_escape_string($this->pswd) ."')";
		$result=mysql_query( $query);
		$query = "unlock table";
		mysql_query($query);
		
		if (!$result)
		{
			die ("Query Failed.");
			$this->meta['status']='failed';
		}
		else if (mysql_num_rows($result) == 0  )
			$this->meta['status']='wrong login';
		else
		{
			$this->meta['status']='passed';
			session_start();
			if(!isset($_SESSION['activeuser']))
			{
				$_SESSION['activeuser'] = $this->usrn;
				$_SESSION['activeID'] = mysql_result($result, 0, 'id');
			}
		}
		return $this->buildjson(null,$this->meta);
	}
}

?>