<?php 

    include_once 'EngineContainer.php';

	class RMPaper extends EngineContainer
	{
		private $title;
		private $meta;
		
		function __construct($a)
		{
			$this->meta['engine']='rmpaper';
			$this->connect();
			$this->title=trim($a[0]);
		}
		
		public function run()
		{	
			$this->loadsess();
   			$query ="delete from lit_". $this->userid ." where title='". $this->title ."'"; 
   			$result=mysql_query($query);
   			if (!$result) 
   			{
   				$this->meta['status']='failed';
   				die ("Query Failed.");
   			}	
   			else
   			{
   				$this->meta['status']='passed';
   			}
   			return $this->buildjson(null,$this->meta);
		}	
	}
?>