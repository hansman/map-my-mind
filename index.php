<?php 
session_start(); 
?>

<!doctype html>  
<html lang="en" >
<head>

    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>MapMyMind</title>
	
	<link href="css/dm.css" rel="stylesheet" />	
	<script src="js/painter.js"></script>
	<script src="js/jquery-1.7.2.min.js"></script>
	<script src="js/jquery-ui-1.8.21.custom.min.js"></script>
	
	
	<div id="nav">
	  <ul>
	    <div id="logo">
	    	<span id="name">MapMyMind</span>
	    </div>
	    <div id="username">
	    	<span id="shownname"></span>
	    </div>
	    
		<li>
			<a href="#" id="loginbutton">Login</a>
		</li>
		<!--   <li><a href="#" id="managebutton">Manage Mindmaps</a></li> -->
	  </ul>
	</div>
	
	<form id="login" class="slidedowns">
    <div id="inputs">        
        <input id="loginusername" type="text" name="loginusername" placeholder="Email" required/>   
        <input id="loginpassword" type="password" name="loginpassword" placeholder="Password" required/>        
    </div>
    <div id="actions">
        <div class="slidespacer" id="warningtext"></div>        
        <div id="loginoptions">
        	<a href="#" id="submitlogin" onclick="ajaxcall('login',[document.getElementById('loginusername').value,document.getElementById('loginpassword').value])">Log in</a>
        	<a href="signup.php" id="signup">Sign up</a>
			<a href="forgotpswd.php">Forgot password</a>
			<a href="#">Manage account</a>
			<a id="closelogin" href="#">Close</a>
        </div>
        <div class="slidespacer"></div>
    </div>
	</form>
	 
	<form id="managemaps" class="slidedowns">
    <div id="inputs">      
          <label id="SaveMapLabel" for="SaveMap">Save this map as:</label>   
        <input id="SaveMap" type="text" name="SaveMap" placeholder="Save map as ..." required/>
        <span onclick="savemap()">Save Map</span>   
    </div>
    
    <div id="actions">
        <div class="slidespacer" id="warningtext"></div>        
    </div>
	</form>
		
    <script type="text/javascript">

        $("#login").hide();
        $("#managemaps").hide();
        
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
                 ajaxcall("logout",null);
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


		  $('#managebutton').click(function() {
			  $('#managemaps').slideDown('slow', function() {				  
			  });
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

		  ajaxcall("getusername",null);
		  ajaxcall("paperdata",null);
		  	
		  sm = new StateMachine();
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

		/* work in progress
        function savemap()
        {
        	var obj = mindmap.createJsonObject();
        	var jsonString = "jsonString=" + JSON.stringify(obj);
 
            
        	var xmlhttp;	
   		 	if (window.XMLHttpRequest)
   		    	xmlhttp=new XMLHttpRequest();
   		 	else
   		    	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

   		 	xmlhttp.onreadystatechange=function()
		 	{
		  		if (xmlhttp.readyState==4 && xmlhttp.status==200 )				  
		  		{
			  	  console.log(xmlhttp.responseText);
		      	  //alert( xmlhttp.responseText );
		  		}
		 	}
   		 	
        	xmlhttp.open("POST","php/savemap.php",true);
        	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        	xmlhttp.setRequestHeader("Content-Length",jsonString.length);
        	
        	xmlhttp.send(jsonString);
        } */
		
		function removepaper()
		{
			var xmlhttp = new XMLHttpRequest();
    		
			xmlhttp.onreadystatechange=function()
			 {
			  if (xmlhttp.readyState==4 && xmlhttp.status==200 )				  
			  {  
			     //console.log(xmlhttp.responseText);
			     document.getElementById('deletepaper').value="";
			     document.getElementById('paper').value="";
			     ajaxcall("paperdata",null);
			  }
			 }
			 xmlhttp.open("GET","php/removepaper.php?title="+document.getElementById('deletepaper').value.split(".  ")[2],true);
			 xmlhttp.send(); 			 
		}

		function ajaxcall(type, args)
		{
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200 )				  
				  { 
						//console.log(xmlhttp.responseText);

						switch(type)
						{
							case "paperdata":	document.getElementById('bibliothek').innerHTML = '';
												document.getElementById('bibliothek2').innerHTML = '';
							  
												jsonPapers = eval( '(' + xmlhttp.responseText + ')' );
												var tag;			                 
												for (var i=0; i<jsonPapers.length;i++) 
												{ 
													tag = '<option label="'+ jsonPapers[i].title +'" value="' + jsonPapers[i].author + '.  ' + jsonPapers[i].date + '.  ' + jsonPapers[i].title + '" />';
													document.getElementById('bibliothek').innerHTML += tag;
													document.getElementById('bibliothek2').innerHTML += tag;
												}
												break;
							case "getdoi":      var paperMeta = eval( xmlhttp.responseText );
				      							document.getElementById("author").value=paperMeta[0].replace(/,/g,' and');
				      							document.getElementById("title").value=paperMeta[1];
				      							document.getElementById("date").value=paperMeta[2];
				      							document.getElementById("publisher").value=paperMeta[3];
				      							document.getElementById("month").value=paperMeta[4];
				      							document.getElementById("volume").value=paperMeta[5];
				      							document.getElementById("issue").value=paperMeta[6];
				      							document.getElementById("startpage").value=paperMeta[7];
				      							document.getElementById("lastpage").value=paperMeta[8];
												break;
							case "getusername":	document.getElementById('shownname').innerHTML =  xmlhttp.responseText ;
			  				  					if (xmlhttp.responseText=="guest")
								  					document.getElementById('loginbutton').innerHTML = "Login";
							  					else
								  					document.getElementById('loginbutton').innerHTML = "Logout";
							  					break; 
							case "login":		//console.log(xmlhttp.responseText);
								  				if(xmlhttp.responseText == "Wrong login")
								  				{
									  				document.getElementById('warningtext').innerHTML = "Your login data is incorrect ...";
							  	  				}
								  				else
								  				{
									  				ajaxcall("paperdata",null);
									  				ajaxcall("getusername",null);									  
									  				$('#login').slideUp('slow', function() {window.location.reload();});
									  				document.getElementById('warningtext').innerHTML = "";
									  				document.getElementById('paper').value = "";
									  				document.getElementById('deletepaper').value = "";
									  				document.getElementById('thelist').value = "";
									  				sm = new StateMachine();
									  				mindmap = new MindMap(document.getElementById("canvas"));
									  				mindmap.performState();
									  			}
									  			break;  
							case "logout":		sm = new StateMachine();
												mindmap = new MindMap(document.getElementById("canvas"));
												mindmap.performState();
												document.getElementById('bibliothek').innerHTML = "";
												document.getElementById('bibliothek2').innerHTML = "";
												document.getElementById('paper').value = "";
												document.getElementById('deletepaper').value = "";
												document.getElementById('thelist').value = "";
												ajaxcall("paperdata",null);
												ajaxcall("getusername",null);
												break;
							case "newpaper":	if (xmlhttp.responseText)
				     	 							document.getElementById("newpaperwarning").innerHTML=xmlhttp.responseText;
												else
													ajaxcall("paperdata",null);
				     							document.getElementById("publisher").value="";
				     							document.getElementById("title").value="";
				     							document.getElementById("author").value="";
				     							document.getElementById("date").value="";
				     							document.getElementById("month").value="";
				     							document.getElementById("issue").value="";
				     							document.getElementById("volume").value="";
				     							document.getElementById("startpage").value="";
				     							document.getElementById("lastpage").value="";
				     							break;
							}
				  }
			}

			if(type=="getdoi")
			{
				var args = args.split(".org/");
				if (args[1])
				{
					args=args[1];
				}
			}

			xmlhttp.open("GET","php/ajax.php?type="+type+"&args="+args,true);
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
      Your bibliography will be generated here once you got something on your mind ...
    </textarea>     
  </div>
  
  <h3 id="addlibrary" ><a>Add To Library</a></h3>
	<div>

   <form id="newpaperform">
		<fieldset>
    
      <label id="doilabel" for="doifield">DOI</label>
      <input class="refform" id="doifield" name="doifield" type="text" placeholder="Enter doi, i.e. 'http://dx.doi.org/10.1023/A:1015460304860' OR '10.1023/A:1015460304860' OR '___.org/10.1023/A:1015460304860' " />
      <input id="getpaperbtn" class="buttons" type="button" value="Get Paper" onclick="ajaxcall('getdoi',document.getElementById('doifield').value);" />
			
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
		<input class="buttons" type="button" value="Submit" onclick="ajaxcall('newpaper',[document.getElementById('doifield').value,document.getElementById('author').value,document.getElementById('title').value,document.getElementById('publisher').value,document.getElementById('date').value,document.getElementById('month').value,document.getElementById('volume').value,document.getElementById('issue').value,document.getElementById('startpage').value,document.getElementById('lastpage').value ])" />
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
  

</body>
</html>
