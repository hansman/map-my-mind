<?php 

    include 'SQLcontainer.php';

	class PaperData extends SQLcontainer
	{
		
		
		
		public function run()
		{
			
			session_start();
			
			if(isset($_SESSION['activeID']))
				$userid=$_SESSION['activeID'];
			else
				$userid="guest";
			
			$query = "lock table lit_". $userid ." read";
			mysql_query($query);
			
			$query = "select doi, author, title, date, month, publisher, volume, issue, startpage, lastpage from lit_".$userid;
			
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