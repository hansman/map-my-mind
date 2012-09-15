<?php 

include_once 'EngineContainer.php';

class NewPswd extends EngineContainer
{	
	private $address;
	
	function __construct($a)
	{
		$this->connect();
		$this->address = $a[0];
	}
	
	function run()
	{
		
		$newpswd = $this->randstring(15);
		
		$header = "Map My Mind -- Your new password";
		$body =  "Your new password is:". $newpswd;
				
		$query = "update accounts set pswd=MD5('". mysql_real_escape_string($newpswd) ."') where username='". $this->address ."'";
		
		
		if ( mail($this->address,$header,$body) )
			echo "Mail got sent to "+$this->address;
		else
			echo "Mail failed "+$this->address;  
		
	}
	
	function randstring($s)
	{	
		$randstring;
		$chars="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+-!#$%^&*()=";
		srand();
		for ($i=0; $i<$s;$i++)
			$randstring=$randstring . substr($chars,  rand() % strlen($chars),1 );
		return $randstring;
	}
	
	
}




?>