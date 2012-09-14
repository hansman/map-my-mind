<?php 

include_once 'SQLcontainer.php';

class Login extends SQLcontainer
{
	
	private $pswd;
	private $usrn;
	
	function __construct($a)
	{
		parent:: __construct();
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
			die ("Query Failed.");
		else if (mysql_num_rows($result) == 0  )
			echo "Wrong login";
		else
		{
			session_start();
			if(!isset($_SESSION['activeuser']))
			{
				$_SESSION['activeuser'] = $this->usrn;
				$_SESSION['activeID'] = mysql_result($result, 0, 'id');
			}
		}
	}
}

?>