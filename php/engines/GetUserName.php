<?php 

include_once 'EngineContainer.php';

class GetUserName extends EngineContainer
{
	public function run()
	{
			$this->loadsess();
			echo $this->usernm;		
	}
}

?>