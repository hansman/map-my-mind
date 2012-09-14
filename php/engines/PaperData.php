<?php 

    include_once 'EngineContainer.php';

	class PaperData extends EngineContainer
	{
		
		public function run()
		{
			$this->connect();
			$this->loadsess();
			
			$query = "lock table lit_". $this->userid ." read";
			mysql_query($query);
			
			$query = "select doi, author, title, date, month, publisher, volume, issue, startpage, lastpage from lit_".$this->userid;
			
			$result=mysql_query($query);		
			
			$jsonrows = array();
			while($row = mysql_fetch_assoc($result))
			{
				$jsonrows[] = $row;
			}		 
			
			$query = "unlock table";
			mysql_query($query);
			
			return json_encode($jsonrows);
		}
	}
?>