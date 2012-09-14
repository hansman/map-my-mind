<?php 

class GetUserName
{
	public function run()
	{
		session_start();
		
		if(isset($_SESSION['activeuser']))
			echo $_SESSION['activeuser'];
		else
			echo "guest";		
	}
}

?>