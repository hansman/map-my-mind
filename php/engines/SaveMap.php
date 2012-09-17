<?php 

include_once 'EngineContainer.php';

class SaveMap extends EngineContainer
{
	private $meta;
	private $jsonMap;
	private $mapName;

	function __construct($a)
	{
		$temp=split ( ',' , $a , 2 );
		$this->meta['engine']='savemap';
		$this->connect();		
		$this->jsonMap=json_decode($temp[1]);
		$this->mapName=$temp[0];
	}

	public function run()
	{
		
		//to test I am returning the decoded json data
		return $this->buildjson($this->jsonMap,$this->meta);
	}
}








?>