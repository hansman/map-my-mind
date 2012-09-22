<?php 

include_once 'EngineContainer.php';

class GetSession extends EngineContainer
{
	
	private $meta;
	private $key;
	
	function __construct($a)
	{
		$this->meta['engine']='getsession';
		$this->meta['option'] = $a[0];
	}
	
	public function run()
	{
			$this->loadsess();
			$this->meta['status']='passed';
			
			switch($this->meta['option'])
			{
				case 'usernm': 	$data=array($this->usernm);
								break;
				case 'userid': 	$data=array($this->userid);
								break;
				case 'mapnm': 	$data=array($this->mapnm);
								break;
				default: 		$data=null;
			}			
			return $this->buildjson($data,$this->meta);
	}
}

?>