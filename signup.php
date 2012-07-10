<!doctype html>  
<html lang="en" >
<head>

    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]--> 
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
	
	
	<form id="signupform">
    <ul>	
		    <div>
        	<label for="newemail">Email *</label>
        	<input class="signform" id="newemail" name="newemail" type=text placeholder="Email" required />	
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
			<div class="spacer"  > </div>
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
			
			var password = document.getElementById('newpassword').value;
			var reppassword = document.getElementById('repeatpassword').value;
			var newemail = document.getElementById('newemail').value;

			if( (newemail=="") || (reppassword=="") || (password=="")  )
				{ document.getElementById('warningtext').innerHTML="Please fill out all required fields"   }
			else if ( reppassword!=password  )
			    { document.getElementById('warningtext').innerHTML="Your passwords don't match"   }
			else
			{
 				signupuser();
   
			}
		};

		function signupuser()
		{

			var password = document.getElementById('newpassword').value;
			var reppassword = document.getElementById('repeatpassword').value;
			var newemail = document.getElementById('newemail').value;
			
			var xmlhttp;
		
		 if (window.XMLHttpRequest)
		  {
		    xmlhttp=new XMLHttpRequest();
		  }
		 else
		  {
		    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		
		 xmlhttp.onreadystatechange=function()
		 {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200 )				  
		  {
			  
		   	  if( xmlhttp.responseText == "exists")
		   	  {
		   		document.getElementById('warningtext').innerHTML="This username is already gone.";
			  }  
		   	  else
		   	  {
		   	   console.log( "signedup" );
		   	   location.href="index.php";
			  }		  }
		 }
		 xmlhttp.open("GET","php/newuser.php?email="+newemail+"&pass="+password,true);
		 xmlhttp.send();
       };
		
		

</script>
	
</body>
</html>
