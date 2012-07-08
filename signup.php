<!doctype html>  
<html lang="en" >
<head>

    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]--> 
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Digital Mindmapping</title>
	
	<link href="css/dm.css" rel="stylesheet" />
	<script src="js/jquery-1.7.2.min.js"></script>	
	
	
	<div id="nav">
	<ul >
	    <div id="logo">
	    <span id="name">Digital Mindmapping</span>
	    </div>
	</ul>
	</div>
	
	
	<form id="signupform">
    <ul>	
		    <div>
        	<label for="newemail">Email *</label>
        	<input class="signform" id="newemail" name="newemail" type="text" placeholder="Email" required />	
			</div>
        	<br>
			<div>
        	<label for="newpassword">Password *</label>
        	<input class="signform" id="newpassword" name="newpassword "type="text" placeholder="Password" required/> 
        	</div>
        	<br>
			<div>
        	<label for="repeatpassword">Repeat Password *</label>
        	<input class="signform" id="repeatpassword" name="repeatpassword "type="text" placeholder="Password" required/>   		
			
			<div class="spacer"> </div>
			<br>
			<div>
        	<label for="firstname">First name</label>
        	<input class="signform" id="firstname" name="firstname" type="text" placeholder="First name" /> 		
			</div>
        	<br>
        	<div>
        	<label for="familyname">Family name</label>
        	<input class="signform" id="familyname" name="familyname" type="text" placeholder="Family name" /> 		
			</div>
        	<br>
        	<div>
        	<label for="degree">Degree</label>
        	<input class="signform" id="degree" name="degree" type="text" placeholder="Degree" /> 		
			</div>
        	<br>
			<div>
        	<label for="institution">Institution</label>
        	<input class="signform" id="institution" name="institution" type="text" placeholder="Institution" /> 		
			</div>
        	<br>
			<div>			
        	<label for="affiliation">Affiliation</label>
        	<input class="signform" id="affiliation" name="affiliation" type="text" placeholder="Affiliation" /> 		
			</div>
        	<br>
			
				    
		</ul>
    <div id="actions">
        <div class="loginspacer""></div>        
        <div id="signupoptions">
        <a href="#">Sign up</a>
		<a id="keepplaying" href="index.php">Keep playing</a>
        </div>
        <div class="loginspacer"></div>
    </div>
	</form>
	
	
</head>

<body>

<script type="text/javascript">

		
		function document_load()
		{
			$("keepplaying").click(function(event){
		        event.preventDefault();
		        linkLocation = this.href;
		        $("body").fadeOut(1000, redirectPage);     
		    });
		};

</script>
	
</body>
</html>
