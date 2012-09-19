<?php 

include_once 'EngineContainer.php';

class ManageMap extends EngineContainer
{
	private $meta;
	private $jsonMap;
	private $mapName;
	private $zoom;
	private $option;

	function __construct($a)
	{
		$temp=split ( ',' , $a , 4 );
		$this->meta['engine']='managemap';
		$this->connect();		
		$this->loadsess();
		$this->jsonMap=json_decode($temp[3],true);
		$this->zoom=$temp[2];
		$this->mapName=$temp[1];
		$this->option=$temp[0];
		$this->meta['option']=$this->option;
	}

	public function run()
	{
		
		switch ($this->option)
		{
			case 0:	$query="select * from map_". $this->userid ."_". $this->mapName;
					
					if(mysql_query($query))
						$this->meta['status']="exists";
					else
					{
						$query="create table map_". $this->userid ."_". $this->mapName . " like map_testuser_mapname";
						if(!mysql_query($query))
						{
							$this->meta['status']='failed';
							die ("Query Failed.");
						}
						else
						{
							for($i=0;$i<count($this->jsonMap);$i++)
							{
								$query="insert into map_". $this->userid ."_". $this->mapName."(type,x,y,id,startref,endref) values ('".$this->jsonMap[$i]['type']."','".$this->jsonMap[$i]['x']."','".$this->jsonMap[$i]['y']."','".$this->jsonMap[$i]['id']."','".$this->jsonMap[$i]['startRef']."','".$this->jsonMap[$i]['endRef']."')";
								if(!mysql_query($query))
								{
									$this->meta['status']='failed';
									die ("Query Failed.");
								}
							}							
							$this->meta['status']='passed';
						}
					}
					break;
			
			default:   
			
		}
		
		
		//to test I am returning the decoded json data
		return $this->buildjson($this->jsonMap,$this->meta);
	}
}








?>