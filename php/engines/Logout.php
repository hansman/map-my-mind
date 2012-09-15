<?php 

	include_once 'EngineContainer.php';

	class Logout extends EngineContainer
	{
		private $meta;
		
		function __construct()
		{
			$this->meta['engine']='logout';			
		}
	
		public function run()
		{
				
			session_start();
			if (isset($_SESSION['activeuser']))
				unset($_SESSION['activeuser']);

			if (isset($_SESSION['activeID']))
				unset($_SESSION['activeID']);
			
			$this->meta['status']='passed';
			return $this->buildjson(null,$this->meta);
		}	
	}

?>