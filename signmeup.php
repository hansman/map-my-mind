<!doctype html>  
<html lang="en" >
<head>

    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>MapMyMind</title>
	
	<link href="css/dm.css" rel="stylesheet" />
	<script src="js/jquery-1.7.2.min.js"></script>	
	
	<div id="nav">
	<ul>
	    <div id="logo">
	    <span id="name">Map My Mind</span>
	    </div>
	</ul>
	</div>
	
	<form id="userform">
    <ul>	
		    <div>
        		<label for="newemail">Email *</label>
        		<input class="signform" id="newemail" placeholder="Email" required />	
			</div>
        	<br>
			<div>
        		<label for="newpassword">Password *</label>
        		<input class="signform" id="newpassword" name="newpassword" type="password" placeholder="Password" required/> 
        	</div>
        	<br>
			<div>
        		<label for="repeatpassword">Repeat Password *</label>
        		<input class="signform" id="repeatpassword" name="repeatpassword" type="password" placeholder="Password" required/> 
        	</div>
        	<br>
			<div class="spacer" id="warningtext"> </div>
			<div class="spacer"> </div>
			<div>
        		<label for="firstname">First name</label>
        		<input class="signform" id="firstname" placeholder="First name" /> 		
			</div>
        	<br>
        	<div>
        		<label for="familyname">Family name</label>
        		<input class="signform" id="familyname" placeholder="Family name" /> 		
			</div>
        	<br>
        	<div>
        		<label for="degree">Degree</label>
        		<input class="signform" id="degree" placeholder="Degree" /> 		
			</div>
        	<br>
			<div>
        		<label for="institution">Institution</label>
        		<input class="signform" id="institution" placeholder="Institution" /> 		
			</div>
        	<br>
			<div>			
        		<label for="affiliation">Affiliation</label>
        		<input class="signform" id="affiliation" placeholder="Affiliation" /> 		
			</div>
        	<br>	    
		</ul>
    <div id="actions">
        <div class="loginspacer"></div>        
        <div id="signupoptions">
        <a href="#" onClick="inputvalidation()">Sign up</a>
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

		$("#repeatpassword").focus(function()
				  {
					  $('#warningtext').text("");
				  });

		$("#newpassword").focus(function()
				  {
					  $('#warningtext').text("");
				  });
		$("#newemail").focus(function()
				  {
					  $('#warningtext').text("");
					  $("#repeatpassword").val("");
					  $("#newpassword").val("");
					  $("#newemail").val("");
				  });

		 


		function inputvalidation()
		{
			
			var password = $('#newpassword').val();
			var reppassword = $('#repeatpassword').val();
			var newemail = $('#newemail').val();
			var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
			
			if( (newemail=="") || (reppassword=="") || (password=="")  )
				$('#warningtext').html("Please fill out all required fields");   
			else if ( reg.test(newemail) == false )
				$('#warningtext').html("This is not a valid email address");		
			else if ( reppassword!=password  )
			    $('#warningtext').html("Your passwords don't match");   
			else
 				signupuser();
		};

		function signupuser()
		{

			var password = $('#newpassword').val();
			var newemail = $('#newemail').val();
			
			var xmlhttp=new XMLHttpRequest();
		  		
		 	xmlhttp.onreadystatechange=function()
		 	{
		  		if (xmlhttp.readyState==4 && xmlhttp.status==200 )				  
		  		{
			  //alert(xmlhttp.responseText);
		   	  		if( xmlhttp.responseText == "exists")
		   	  		{
		   				$('#warningtext').html("This username is already gone.");
			  		}  
		   	  		else
		   	  		{
		   	   			console.log( "signedup" );
		   	   			location.href="index.php";
			  		}
				  }
		 	}
		 	xmlhttp.open("GET","php/ajax.php?type=signup&args="+[newemail,password],true);
		 	
		 	xmlhttp.send();
       	};

    </script>
	
</body>
</html>
