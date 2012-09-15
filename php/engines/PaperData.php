<?php 

    include_once 'EngineContainer.php';    

	class PaperData extends EngineContainer
	{
		private $meta;

		function __construct()
		{
			$this->meta['engine']='paperdata';			
		}
		
		public function run()
		{
			
			$this->connect();
			$this->loadsess();
			
			$query = "lock table lit_". $this->userid ." read";
			mysql_query($query);
			
			$query = "select doi, author, title, date, month, publisher, volume, issue, startpage, lastpage from lit_".$this->userid;
			
			$result=mysql_query($query);
			if(!$result)
				$this->meta['status']='failed';				
			else
				$this->meta['status']='passed';
			
			$data = array();
			while($row = mysql_fetch_assoc($result))
				$data[] = $row;			
			
			$query = "unlock table";
			mysql_query($query);
			
			return $this->buildjson($data,$this->meta);
		}
	}
?>