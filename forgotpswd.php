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
			 var email = document.getElementById('newemail').value;

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
				  //alert(xmlhttp.responseText);
			   	  if( xmlhttp.responseText == "failed")
			   	  {
						alert("failed");
				  }
			   	  else
			   	  {
						alert("worked");
				  }
			   	  		  
			   }
			 }
			 xmlhttp.open("GET","php/sendpswd.php?email="+email,true);
			 xmlhttp.send();


		};
		
		
		/*
		function inputvalidation()
		{
			
			var password = document.getElementById('newpassword').value;
			var reppassword = document.getElementById('repeatpassword').value;
			var newemail = document.getElementById('newemail').value;
			var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
			
			if( (newemail=="") || (reppassword=="") || (password=="")  )
				{ document.getElementById('warningtext').innerHTML="Please fill out all required fields";   }
			else if ( reg.test(newemail) == false )
				{ document.getElementById('warningtext').innerHTML="This is not a valid email address";		}
			else if ( reppassword!=password  )
			    { document.getElementById('warningtext').innerHTML="Your passwords don't match";   }
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
       }; */
		
		

</script>
	
</body>
</html>
