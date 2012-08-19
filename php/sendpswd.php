<?php 

  $email = trim($_GET['email']);
  //$email="steinbrecher.johann@gmail.com";
  $header = "Map My Mind -- Your new password";
  $body =  "eine nette email";

  $test = mail($email,$header,$body);
  if ($test)
  	echo "Mail Sent..";
  else
  	echo "Mail failed";

?>