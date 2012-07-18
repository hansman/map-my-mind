<?php 
session_start(); 
?>

<!doctype html>  
<html lang="en" >
<head>

    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]--> 
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>MapMyMind</title>
	
	<link href="css/dm.css" rel="stylesheet" />	
	
	<div id="nav">
	<ul >
	    <div id="logo">
	    <span id="name">Map My Mind</span>
	    </div>
	    <div id="username">
	    <span id="shownname"></span>
	    </div>
	    
		<li><a href="#" id="loginbutton">Login</a></li>
		<li><a href="#">Manage Mindmaps</a></li>
	</ul>
	</div>
	
	
	<form id="login">
    <div id="inputs">        
        <input id="loginusername" type="text" name="loginusername" placeholder="Email" required/>   
        <input id="loginpassword" type="password" name="loginpassword" placeholder="Password" required/>        
    </div>
    <div id="actions">
        <div class="loginspacer" id="warningtext"></div>        
        <div id="loginoptions">
        <a href="#" id="submitlogin" onclick="login()">Log in</a>
        <a href="signup.php" id="signup">Sign up</a>
		<a href="forgotpswd.php">Forgot password</a>
		<a href="#">Manage account</a>
		<a id="closelogin" href="#">Close</a>
        </div>
        <div class="loginspacer"></div>
    </div>
	</form>
	
	
	<script src="js/painter.js"></script>
	<script src="js/jquery-1.7.2.min.js"></script>
	<script src="js/jquery-ui-1.8.21.custom.min.js"></script>

	
    <script type="text/javascript">

        $("#login").hide();

        
		var mindmap = null;
		var sm = null;
		var jsonPapers = null;
		
		function document_load()
		{
			$("signup").click(function(event){
		        event.preventDefault();
		        linkLocation = this.href;
		        $("body").fadeOut(1000, redirectPage);     
		    });

			 
		  
		  $('#loginbutton').click(function() {
			  if($('#loginbutton').text() == "Login")
			  {
			  	$('#login').slideDown('slow', function() {});
			  }
			  else
			  {
                 logout();
			  }
			 });

		  $('#closelogin').click(function() {
			  $('#login').slideUp('slow', function() {				  
			  });
			  $('#warningtext').text("");
			 });

		  $("#loginusername").focus(function()
		  {
			  $('#warningtext').text("");
			  $("#loginusername").val("");
			  $("#loginpassword").val("");
		  });

		  $("#getpaperbtn").click(function()
				  {
					  $('#newpaperwarning').text("");
				  });

		  $("#addlibrary").click(function()
				  {
					  $('#newpaperwarning').text("");
				  });
		  


		  

		  $("#loginpassword").focus(function()
		  {
			  $('#warningtext').text("");
			  $("#loginpassword").val("");
		  });

		  showusername();
		  updateDatalist();
		  	
		  sm = new StateMachine(states);
		  mindmap = new MindMap(document.getElementById("canvas"));
		  mindmap.performState();
		  
		  // Expand Panel
		  $("#open").click(function()
		  {
			$("div#panel").slideDown("slow");
		  });
			 
		  // Collapse Panel
		  $("#close").click(function()
		  {
			$("div#panel").slideUp("slow");
		  });
		  		  
		}
		
		function getdoi()
		{
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
		      var myArray = eval( xmlhttp.responseText );
		      document.getElementById("author").value=myArray[0];
		      document.getElementById("title").value=myArray[1];
		      document.getElementById("date").value=myArray[2];
		      document.getElementById("publisher").value=myArray[3];
		      document.getElementById("month").value=myArray[4];
		      document.getElementById("volume").value=myArray[5];
		      document.getElementById("issue").value=myArray[6];
		      document.getElementById("startpage").value=myArray[7];
		      document.getElementById("lastpage").value=myArray[8];
		      
		  }
		 }
		  
		 var doifield = document.getElementById("doifield").value;  
		 var doiparser = doifield.split(".org/");
			if (doiparser[1])
			{
				doifield=doiparser[1];
			}
	  
		 xmlhttp.open("GET","php/doihandler.php?doi="+doifield,true);
		 xmlhttp.send();
       }
		
		
		
		function submitnewpaper()
		{
			var xmlhttp;
			
			var doi=document.getElementById("doifield").value;
			var author=document.getElementById("author").value;
			var title=document.getElementById("title").value;
			var publisher=document.getElementById("publisher").value;
			var date=document.getElementById("date").value;
			var month=document.getElementById("month").value;
			var volume=document.getElementById("volume").value;
			var issue=document.getElementById("issue").value;
			var startpage=document.getElementById("startpage").value;
			var lastpage=document.getElementById("lastpage").value;

			if( (!author) || (!title) || (!date))
			{
				document.getElementById("newpaperwarning").innerHTML="Please fill out all required fields";
			}	
			else
			{
			
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
				  updateDatalist();
			     if (xmlhttp.responseText)
			     {
			    	 document.getElementById("newpaperwarning").innerHTML=xmlhttp.responseText;
				 }
			  }
			 }
			 xmlhttp.open("GET","php/newpaper.php?doi="+doi+"&author="+author+"&title="+title+"&publisher="+publisher+"&date="+date+"&month="+month+"&volume="+volume+"&issue="+issue+"&startpage="+startpage+"&lastpage="+lastpage,true);
			 xmlhttp.send(); 
			 
				document.getElementById("publisher").value="";
				document.getElementById("title").value="";
				document.getElementById("author").value="";
				document.getElementById("date").value="";
				document.getElementById("month").value="";
				document.getElementById("issue").value="";
				document.getElementById("volume").value="";
				document.getElementById("startpage").value="";
				document.getElementById("lastpage").value="";
				
				updateDatalist();
			}
		}
		
		
		function removepaper()
		{
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
			     //alert (xmlhttp.responseText);
			     document.getElementById('deletepaper').value="";
			     document.getElementById('paper').value="";
				 updateDatalist();
			  }
			 }
			 xmlhttp.open("GET","php/removepaper.php?title="+document.getElementById('deletepaper').value.split(".  ")[2],true);
			 xmlhttp.send(); 			 
		}
		
		
		
		function updateDatalist()
		{
			
			document.getElementById('bibliothek').innerHTML = "";
			document.getElementById('bibliothek2').innerHTML = "";
			
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
				jsonPapers = eval( '(' + xmlhttp.responseText + ')' );
				var tag;
				
                 console.log(jsonPapers.length);
                 //console.log(jsonPapers[0].author);
				
				for (var i=0; i<jsonPapers.length;i++) 
				{ 
					tag = '<option label="'+ jsonPapers[i].title +'" value="' + jsonPapers[i].author + '.  ' + jsonPapers[i].date + '.  ' + jsonPapers[i].title + '" />';
					//console.log(tag);
					document.getElementById('bibliothek').innerHTML += tag;
					document.getElementById('bibliothek2').innerHTML += tag;
				}
				
			  }
			 }
			 xmlhttp.open("GET","php/getpapers.php",true);
			 xmlhttp.send(); 
		}
		
		function logout()
		{

			sm = new StateMachine(states);
			mindmap = new MindMap(document.getElementById("canvas"));
			mindmap.performState();
			document.getElementById('bibliothek').innerHTML = "";
			document.getElementById('bibliothek2').innerHTML = "";
			document.getElementById('paper').value = "";
			document.getElementById('deletepaper').value = "";
			document.getElementById('thelist').value = "";
			
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
				  updateDatalist();
				  showusername();
			  }
			 }
			 xmlhttp.open("GET","php/logout.php",true);
			 xmlhttp.send();
						
		}


		function login()
		{				
			var username=document.getElementById("loginusername").value;
			var password=document.getElementById("loginpassword").value;
			
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
				  //console.log(xmlhttp.responseText);
				  if(xmlhttp.responseText == "failed")
				  {
					  document.getElementById('warningtext').innerHTML = "Your login data is incorrect ...";
					  //alert("authentification failed");
			  	  }
				  else
				  {
					  //alert(xmlhttp.responseText);
					  updateDatalist();
					  showusername();
					  
					  $('#login').slideUp('slow', function() {window.location.reload();});
					  document.getElementById('warningtext').innerHTML = "";
					  document.getElementById('paper').value = "";
					  document.getElementById('deletepaper').value = "";
					  document.getElementById('thelist').value = "";
					  sm = new StateMachine(states);
					  mindmap = new MindMap(document.getElementById("canvas"));
					  mindmap.performState();
					  
				  } 
				
			  }
			 }
			 xmlhttp.open("GET","php/login.php?name="+username+"&pass="+password,true);
			 xmlhttp.send(); 
		}


		function showusername()
		{				
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
				  document.getElementById('shownname').innerHTML =  xmlhttp.responseText ;
				  				  
				  if (xmlhttp.responseText=="guest")
					  document.getElementById('loginbutton').innerHTML = "Login";
				  else
					  document.getElementById('loginbutton').innerHTML = "Logout";
			  }
			 }
			 xmlhttp.open("GET","php/getuser.php",true);
			 xmlhttp.send();
		}
		


		
		$(function() {
			$( "#accordion" ).accordion({
				autoHeight: false,
				navigation: true
			});
		});
		
		$(function() {
			$( "#accordionResizer" ).resizable({
				minHeight: 140,
				resize: function() {
					$( "#accordion" ).accordion( "resize" );
				}
			});
		}); 
				
		
			
	</script>
	
	
	
</head>


  <body id="thebody" onload="document_load()">
  
  <canvas id="canvas" width="1000" height= "400" tabindex="0"></canvas>
  
  <input class="form" id="paper" autocomplete list="bibliothek" type="text" name="bibliothek" placeholder="please select from your library here ..." />
  <datalist id="bibliothek">
  </datalist>
  <p>
  
  <article>
  
  <div id="accordionResizer" style=" width:1000px; " class="ui-widget-content">
  <div id="accordion" class="accordion">
  
  
  <h3><a>Bibliography</a></h3>
  <div>
    <textarea readonly rows="10" id="thelist" class="form">
      Your bibliography will be generated here once you got something in your mind ...
    </textarea>     
  </div>
  
  <h3 id="addlibrary" ><a>Add To Library</a></h3>
	<div>

   <form id="newpaperform">
		<fieldset>
    
      <label id="doilabel" for="doifield">DOI</label>
      <input class="refform" id="doifield" name="doifield" type="text" placeholder="Enter doi, i.e. 'http://dx.doi.org/10.1023/A:1015460304860' OR '10.1023/A:1015460304860' OR '___.org/10.1023/A:1015460304860' " />
      <input id="getpaperbtn" class="buttons" type="button" value="Get Paper" onclick="getdoi();" />
	
			
		<ul>	
		    <div>
        	<label for="author">Author *</label>
        	<input class="refform" id="author" name="author" type="text" placeholder="Set the author names ..." />	
			</div>
        	<br>
			<div>
        	<label for="title">Title *</label>
        	<input class="refform" id="title" name="title "type="text" placeholder="Set the title ..." />  
        	</div>
        	<br>
			<div>
        	<label for="date">Year *</label>
        	<input class="refform" id="date" name="date" type="text" placeholder="Set the year ..." /> 	 		
			</div>
        	<br>
			<div>
        	<label for="month">Month</label>
        	<input class="refform" id="month" name="month" type="text" placeholder="Set the month ..." /> 			
			</div>
        	<br>
			<div>
        	<label for="publisher">Publisher</label>
        	<input class="refform" id="publisher" name="publisher" type="text" placeholder="Set the publisher ..." />
			</div>
        	<br>
			<div>
        	<label for="volume">Volume</label>
        	<input class="refform" id="volume" name="volume" type="text" placeholder="Set the volume ..." />
			</div>
        	<br>
			<div>
        	<label for="issue">Issue</label>
        	<input class="refform" id="issue" name="issue" type="text" placeholder="Set the issue ..." />
			</div>
        	<br>
			<div>
        	<label for="startpage">Start page</label>
        	<input class="refform" id="startpage" name="startpage" type="text" placeholder="Set the start page ..." />
			</div>
        	<br>
			<div>
        	<label for="lastpage">End page</label>
        	<input class="refform" id="lastpage" name="lastpage" type="text" placeholder="Set the end page ..." />
			</div>
				    
		</ul>
		<br>
		<span id="newpaperwarning">  </span>
		<input class="buttons" type="button" value="Submit" onclick="submitnewpaper()" />
       </fieldset>
     </form>
    
    
  </div>
  
  
  <h3><a>Remove From Library</a></h3>
  <div>
   <input class="form" id="deletepaper" list="bibliothek" type="text" name="bibliothek" placeholder="please select which entry to delete from your library ..." />  
   <datalist id="bibliothek2"> 
   </datalist>  
   <input class="buttons" type="button" value="Remove" onclick="removepaper()" /> 
  </div>
   
 </div>
 </article>
  
  <footer>
  	The content of these web pages is not generated by and does not represent the views of Santa Clara University or any of its departments or organizations.
  </footer>

</body>
</html>
