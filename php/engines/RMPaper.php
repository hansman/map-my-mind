<?php 

    include_once 'SQLcontainer.php';

	class RMPaper extends SQLcontainer
	{
		private $title;
		
		function __construct($a)
		{
			parent:: __construct();
			$this->title=trim($a[0]);
		}
		
		public function run()
		{	
			session_start();
			if(isset($_SESSION['activeID']))
				$userid=$_SESSION['activeID'];
			else
				$userid="guest";
  			
   			$query ="delete from lit_". $userid ." where title='". $this->title ."'"; 

   			
   			$result=mysql_query($query);
   			if (!$result) 
   				die ("Query Failed.");	
		}		
		
	}



?>