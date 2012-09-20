<?php 

include_once 'EngineContainer.php';

class ManageMap extends EngineContainer
{
	private $meta;
	private $data;
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
		$this->data=null;
	}

	public function run()
	{
		
		switch ($this->option)
		{ 
			//save map
			case 0:	$query="select * from map_". $this->userid ."_". $this->mapName;
					
					if(mysql_query($query))
						$this->meta['status']="exists";
					else
					{
						$query = "lock table maps_". $this->userid ." write";
						mysql_query($query);
						$query="insert into maps_". $this->userid ."(name,timestamp,zoom) values ('". $this->mapName ."',NOW(),'". $this->zoom ."')";
						if(!mysql_query($query))
						{
							$this->meta['status']='failed';
							die ("Query Failed.");
						}

						$query = "unlock table";
						mysql_query($query);
												
						$query="create table map_". $this->userid ."_". $this->mapName . " like map_testuser_mapname";
						if(!mysql_query($query))
						{
							$this->meta['status']='failed';
							die ("Query Failed.");
						}
						else
						{
							$query = "lock table map_". $this->userid ."_". $this->mapName ." write";
							mysql_query($query);							
							for($i=0;$i<count($this->jsonMap);$i++)
							{
								$query="insert into map_". $this->userid ."_". $this->mapName."(type,x,y,id,startref,endref) values ('".$this->jsonMap[$i]['type']."','".$this->jsonMap[$i]['x']."','".$this->jsonMap[$i]['y']."','".$this->jsonMap[$i]['id']."','".$this->jsonMap[$i]['startRef']."','".$this->jsonMap[$i]['endRef']."')";
								if(!mysql_query($query))
								{
									$this->meta['status']='failed';
									die ("Query Failed.");
								}
							}
							$query = "unlock table";
							mysql_query($query);							
							$this->meta['status']='passed';
						}
					}
					break;
			//get maps
			case 1:	$query = "lock table maps_". $this->userid ." read";
					mysql_query($query);
			
					$query = "select name from maps_".$this->userid;
			
					$result=mysql_query($query);
					if(!$result)
						$this->meta['status']='failed';				
					else
						$this->meta['status']='passed';
			
					$this->data = array();
					while($name = mysql_fetch_assoc($result))
						$this->data[] = $name;			
			
					$query = "unlock table";
					mysql_query($query);
					break;
			
			default:   
			
		}
		
		
		//to test I am returning the decoded json data
		return $this->buildjson($this->data,$this->meta);
	}
}








?>