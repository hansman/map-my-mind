<!doctype html>  
<html lang="en" >
<head>

    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>MapMyMind</title>
	
	<link href="css/dm.css" rel="stylesheet" />
	<script src="js/jquery-1.7.2.min.js"></script>	
	
	<div id="nav">
	<ul >
	    <div id="logo">
	    <span id="name">Map My Mind</span>
	    </div>
	</ul>
	</div>
	
	<form id="userform">
    <ul>	
		<div>
        	<label for="newemail">Your email</label>
        	<input class="signform" id="newemail" name="newemail" type="text" placeholder="Email" required />	
		</div>
        <br>		
	</ul>
    <div id="actions">
        <div class="loginspacer""></div>        
        <div id="signupoptions">
        	<a href="#" onClick="sendpswd()">Send new password</a>
			<a id="backtomain" href="index.php">Back</a>
        </div>
        <div class="loginspacer"></div>
    </div>
	</form>
</head>

<body>

	<script type="text/javascript">

		
		function document_load()
		{
			$("backtomain").click(function(event){
		        event.preventDefault();
		        linkLocation = this.href;
		        $("body").fadeOut(1000, redirectPage);     
		    });
		};

		function sendpswd()
		{
			 var email = $('#newemail').val();
			 var xmlhttp=new XMLHttpRequest();			  
			 xmlhttp.open("GET","php/ajax.php?type=sendpswd&args="+email,true);
			 xmlhttp.send();
		};

	</script>
	
</body>
</html>
