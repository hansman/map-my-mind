<?php 

    include_once 'EngineContainer.php';

	class RMPaper extends EngineContainer
	{
		private $title;
		
		function __construct($a)
		{
			$this->connect();
			$this->title=trim($a[0]);
		}
		
		public function run()
		{	
			$this->loadsess();
   			$query ="delete from lit_". $this->userid ." where title='". $this->title ."'"; 
   			$result=mysql_query($query);
   			if (!$result) 
   				die ("Query Failed.");	
		}	
	}
?>