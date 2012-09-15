<?php 

class Logout
{
	
	public function run()
	{
				
		session_start();
			if (isset($_SESSION['activeuser']))
				unset($_SESSION['activeuser']);

			if (isset($_SESSION['activeID']))
				unset($_SESSION['activeID']);
	}	
}

?>