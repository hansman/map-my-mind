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
		$header = "Map My Mind -- Your new password";
		$body =  "eine nette email";
				
		
		if ( mail($this->address,$header,$body) )
			echo "Mail got sent to "+$this->address;
		else
			echo "Mail failed "+$this->address; 
		
	}
	
	
}




?>