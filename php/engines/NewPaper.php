<?php 

    include_once 'EngineContainer.php';

	class NewPaper extends EngineContainer
	{
		private $doi;
		private $date;
		private $month;
		private $publisher;
		private $author='';
		private $title;
		private $volume;
		private $issue;
		private $startpage;
		private $lastpage;
		
		private $meta;
		
		function __construct($a)
		{
			$this->meta['engine']='newpaper';			
			$this->connect();
			$this->doi=trim($a[0]);			
			$this->title=trim($a[1]);
			$this->publisher=trim($a[2]);
			$this->date=trim($a[3]);
			$this->month=trim($a[4]);
			$this->volume=trim($a[5]);
			$this->issue=trim($a[6]);
			$this->startpage=trim($a[7]);
			$this->lastpage=trim($a[8]);
			$i=9;
			while(isset($a[$i]))
				$this->author = $this->author . $a[$i++] .',';
		}
		
		public function run()
		{
			
			$this->loadsess();  
   			$query = "lock table lit_". $this->userid ." write";
   			mysql_query($query);
   
   			$query  = "select * from lit_". $this->userid ." where title='". $this->title ."'";
   			$result=mysql_query($query);
   			if(!$result)
   			{
   				$this->meta['status']='failed';
   				die ("Query Failed.");
   			}
   			else if(mysql_num_rows($result))
   			{
   				$this->meta['status']="This paper is already in your data base";
   			}
   			else 
   			{   
     			$query  = "insert into lit_". $this->userid ."(doi, author, title, date, month, publisher, volume, issue, startpage, lastpage) values ('". $this->doi ."','". $this->author ."','". $this->title ."','". $this->date ."','". $this->month ."','". $this->publisher ."','". $this->volume ."','". $this->issue ."','". $this->startpage ."','". $this->lastpage  ."' );";
 
     			$result=mysql_query($query);
     			if (!$result) 
     			{
     				$this->meta['status']='failed';
     				die ("Query Failed.");
     			}
     			else
     				$this->meta['status']='passed';
   			}
   			$query = "unlock table";
   			mysql_query($query);

   			return $this->buildjson(null,$this->meta);			
		}
	}

?>