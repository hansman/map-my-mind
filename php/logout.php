<?php 
session_start();
if (isset($_SESSION['activeuser']))
{
  unset($_SESSION['activeuser']);
}

if (isset($_SESSION['activeID']))
{
	unset($_SESSION['activeID']);
}

?>