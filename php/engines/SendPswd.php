<?php 

include_once 'EngineContainer.php'';

class SendPswd extends EngineContainer
{
	private $email
	function __construct($a)
	{
		$this->connect();
		$this->$mail=$a[0];
	}

	
	function run()
	{	
		$header = "Map My Mind -- Your new password";
		$body =  "eine nette email";
		if (mail($this->email,$header,$body))
			echo "Mail got sent to "+$this->mail;
		else
			echo "Mail failed"+$this->mail;
	}
}
?>