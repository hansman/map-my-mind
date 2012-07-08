<?php 
session_start();
if (isset($_SESSION['activeuser']))
{
  unset($_SESSION['activeuser']);
}

?>