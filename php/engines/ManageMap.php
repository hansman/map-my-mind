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
			//save new map
			case 0:	$query="select * from maps_". $this->userid . " where name='".$this->mapName."'";
					$result = mysql_query($query); 
					if( !$result )
					{
						$this->meta['status']='failed';
						die ("Query Failed.");
					}	
					else if(mysql_num_rows($result) != 0  )
					{
						$this->meta['status']="exists";
					}
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
						$id = mysql_insert_id();					

						$query = "unlock table";
						mysql_query($query);
												
						$query="create table map_". $this->userid ."_". $id . " like map_testuser_mapname";
						if(!mysql_query($query))
						{
							$this->meta['status']='failed';
							die ("Query Failed.");
						}
						else
						{
							$query = "lock table map_". $this->userid ."_". $id ." write";
							mysql_query($query);							
							for($i=0;$i<count($this->jsonMap);$i++)
							{
								$query="insert into map_". $this->userid ."_". $id."(type,x,y,id,startref,endref,comment) values ('".$this->jsonMap[$i]['type']."','".$this->jsonMap[$i]['x']."','".$this->jsonMap[$i]['y']."','".$this->jsonMap[$i]['id']."','".$this->jsonMap[$i]['startRef']."','".$this->jsonMap[$i]['endRef']."','".$this->jsonMap[$i]['comment']."')";
								if(!mysql_query($query))
								{
									$this->meta['status']='failed';
									die ("Query Failed.");
								}
							}
							$query = "unlock table";
							mysql_query($query);							
							$this->meta['status']='passed';
							session_start();
							$_SESSION['activeMap']=$this->mapName;
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
					
			//delete map
			case 2:	$query = "lock table maps_". $this->userid ." write";
					mysql_query($query);						
					$query = "select id from maps_".$this->userid." where name='".$this->mapName."'";		
					$result=mysql_query($query);			
					if(!$result)
						$this->meta['status']='failed';
					else
					{
						$this->meta['status']='passed';
						while($res = mysql_fetch_assoc($result))
							$id=$res['id'];				
					}
					
					$query = "drop table map_". $this->userid ."_". $id;
					mysql_query($query);
					
					$query = "delete from maps_".$this->userid." where name='".$this->mapName."'";
					mysql_query($query);
					
					$query = "unlock table";
					mysql_query($query);
					break;
			//load map
			case 3:	$query = "lock table maps_". $this->userid ." read";
					mysql_query($query);
					$id=-1;
					
					$query = "select id from maps_".$this->userid." where name='".$this->mapName."'";
					$result=mysql_query($query);
					if(!$result)
						$this->meta['status']='failed';
					else
					{
						$this->meta['status']='passed';
						while($res = mysql_fetch_assoc($result))
							$id=$res['id'];	
					}
					
					$query = "unlock table";
					mysql_query($query);
					
					if($id>=0)
					{
						$query = "lock table map_".$this->userid."_".$id ." read";
						mysql_query($query);
						
						$query = "select tableid, type, x, y, id, startref, endref,comment from map_".$this->userid."_".$id;
						$result=mysql_query($query);
						if(!$result)
							$this->meta['status']=$query;
						else
						{
							$this->meta['status']='passed';
							session_start();
							$_SESSION['activeMap']=$this->mapName;							
						}
							
						$this->data = array();
						while($row = mysql_fetch_assoc($result))
							$this->data[] = $row;
							
						$query = "unlock table";
						mysql_query($query);
					}	
					else
					{
						$this->meta['status']='does not exist';						
					}
					
					break;
					
			//save changes to a map
			case 4:	$query="select * from maps_". $this->userid . " where name='".$this->mapName."'";
					$result = mysql_query($query);
					if( !$result )
					{
						$this->meta['status']='failed';
						die ("Query Failed.");
					}
					else if(mysql_num_rows($result) != 1  )
					{
						$this->meta['status']="does not exists";
					}
					else
					{
						$query = "lock table maps_". $this->userid ." write";
						mysql_query($query);
						$query="update maps_". $this->userid ." set timestamp=NOW(), zoom='". $this->zoom ."' where name='". $this->mapName ."'";
						if(!mysql_query($query))
						{
							$this->meta['status']=$query;
						}
						
						$query="select id from maps_". $this->userid ." where name='". $this->mapName ."'";
						$result = mysql_query($query);
						if(!$result)
						{
							$this->meta['status']=$query;
						}
						else
						{
							$row = mysql_fetch_row($result);
							$id = $row[0];	
						}
					
						$query = "unlock table";
						mysql_query($query);
					
						$query="delete from map_". $this->userid ."_". $id;
						if(!mysql_query($query))
							$this->meta['status']=$query;
						else
						{
							$query = "lock table map_". $this->userid ."_". $id ." write";
							mysql_query($query);
							for($i=0;$i<count($this->jsonMap);$i++)
							{
								$query="insert into map_". $this->userid ."_". $id."(type,x,y,id,startref,endref,comment) values ('".$this->jsonMap[$i]['type']."','".$this->jsonMap[$i]['x']."','".$this->jsonMap[$i]['y']."','".$this->jsonMap[$i]['id']."','".$this->jsonMap[$i]['startRef']."','".$this->jsonMap[$i]['endRef']."','".$this->jsonMap[$i]['comment']."')";
								if(!mysql_query($query))
									$this->meta['status']='failed';
							}
							$query = "unlock table";
							mysql_query($query);
							$this->meta['status']='passed';
							session_start();
							$_SESSION['activeMap']=$this->mapName;
						}
					}
					break;
					
			default:   
			
		}
		return $this->buildjson($this->data,$this->meta);
	}
}

?>