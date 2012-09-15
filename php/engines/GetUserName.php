<?php 

include_once 'EngineContainer.php';

class GetUserName extends EngineContainer
{
	
	private $meta;
	
	function __construct()
	{
		$this->meta['engine']='getusername';
	}
	
	public function run()
	{
			$this->loadsess();
			$this->meta['status']='passed';
			$data=array($this->usernm);
			return $this->buildjson($data,$this->meta);
	}
}

?>