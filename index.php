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
	<script src="js/handlers.js"></script>
	
	<div id="nav">
	  <ul>
	    <div id="logo">
	    	<span id="name">MapMyMind</span>
	    </div>
	    <span id="shownname"></span>
	    <li>
			<a href="#" id="loginbutton">Login</a>
		</li>
		<!--   <li><a href="#" id="managebutton">Manage Mindmaps</a></li> -->
	  </ul>
	</div>
	
	<form id="login" class="slidedowns">
    <div id="inputs">        
        <input id="loginusername" type="text" placeholder="Email" required/>   
        <input id="loginpassword" type="password" placeholder="Password" required/>        
    </div>
    <div class="formactions">
        <div class="slidespacer" id="warningtext"></div>        
        <div id="loginoptions">
        	<a href="#" id="submitlogin" onclick="ajaxcall('login',[$('#loginusername').val(),$('#loginpassword').val()])">Log in</a>
        	<a href="signmeup.php" id="signup">Sign up</a>
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
        <input id="SaveMap" type="text" placeholder="Save map as ..." required/>
        <span onclick="savemap()">Save Map</span>   
      </div>    
      <div class="formactions">
      	<div class="slidespacer" id="warningtext"></div>        
      </div>
	</form>
		
    <script type="text/javascript">

        $("#login").hide();
        $("#managemaps").hide();
		var mindmap = null;
		var sm = null;
		var jsonPapers = null;
		var authors=0;
								
		function document_load()
		{		
		  ajaxcall("getusername",null);
		  ajaxcall("paperdata",null);
		  handlers();
		  $(".bodies").hide();	  	
		  sm = new StateMachine();
		  mindmap = new MindMap(document.getElementById("canvas"));
		  mindmap.performState();
		}

				
		function ajaxcall(type, args)
		{
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200 )				  
				  { 
						var jsonResponse = eval( '(' + xmlhttp.responseText + ')' );						
						switch(jsonResponse.meta.engine)
						{
							case "paperdata":	$(".dlbib").text('');			                 
												for (var i=0; i<jsonResponse.data.length;i++) 
													$(".dlbib").append('<option label="'+ jsonResponse.data[i].title +'" value="' + jsonResponse.data[i].author + '.  ' + jsonResponse.data[i].date + '.  ' + jsonResponse.data[i].title + '" />');
												break;
							case "getdoi":      document.getElementById("author").value=jsonResponse.data[0].replace(/,/g,' and');
				      							$("#title").val(jsonResponse.data[1]);
				      							$("#date").val(jsonResponse.data[2]);
				      							$("#publisher").val(jsonResponse.data[3]);
				      							$("#month").val(jsonResponse.data[4]);
				      							$("#volume").val(jsonResponse.data[5]);
				      							$("#issue").val(jsonResponse.data[6]);
				      							$("#startpage").val(jsonResponse.data[7]);
				      							$("#lastpage").val(jsonResponse.data[8]);
												break;
							case "getusername":	$('#shownname').html(jsonResponse.data[0]) ;
			  				  					if (jsonResponse.data[0]=="guest")
								  					$('#loginbutton').text("Login");
							  					else
								  					$('#loginbutton').text("Logout");
							  					break; 
							case "login":		if(jsonResponse.meta['status'] == "wrong login")
								  				{
									  				$('#warningtext').text("Your login data is incorrect ...");
							  	  				}
								  				else
								  				{
									  				ajaxcall("paperdata",null);
									  				ajaxcall("getusername",null);									  
									  				$('#login').slideUp('slow', function() {window.location.reload();});
									  				$('#warningtext').text("");
									  				$('#paper').val("");
									  				$('#deletepaper').val("");
									  				$('#thelist').val("");
									  				sm = new StateMachine();
									  				mindmap = new MindMap(document.getElementById("canvas"));
									  				mindmap.performState();
									  			}
									  			break;  
							case "logout":		sm = new StateMachine();
												mindmap = new MindMap(document.getElementById("canvas"));
												mindmap.performState();
												$(".dlbib").text('');
												$('#paper').val("");
												$('#deletepaper').val("");
												$('#thelist').val("");
												ajaxcall("paperdata",null);
												ajaxcall("getusername",null);
												break;
							case "newpaper":	if (jsonResponse.meta['status']!='passed')
				     	 							$("#newpaperwarning").html(jsonResponse.meta['status']);
												else
												{
													ajaxcall("paperdata",null);
													$(".npform").val('');
												}				     							
				     							break;
							case "rmpaper":		$('#deletepaper').val("");
						     					$('#paper').val("");
						     					ajaxcall("paperdata",null);
						     					break;
						    default:			alert("Problem selecting the ajax type in index.php");
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

		function addauthors()
		{		
		  if(!authors)
			  $('#theauthors').html('');
		  $('#theauthors').append("<div class='authorrows'><input id='authorf"+authors+"' class='authorsform' placeholder='First name' /><input id='authorl"+authors+1+"' class='authorsform' placeholder='Last name' /><input id='authorf"+authors+2+"' class='authorsform' placeholder='First name' /><input id='authorf"+authors+3+"' class='authorsform' placeholder='First name' /></div><br><div class='authorrows'><input id='authorl"+authors+"' class='authorsform' placeholder='Last name' /><input id='authorl"+authors+1+"' class='authorsform' placeholder='Last name' /><input id='authorl"+authors+2+"' class='authorsform' placeholder='Last name' /><input id='authorl"+authors+3+"' class='authorsform' placeholder='Last name' /></div><br>");
		  authors+=4;
		}		
					
	</script>    
</head>

<body id="thebody" onload="document_load()">
  
  <canvas id="canvas" width="1000" height= "400" tabindex="0"></canvas>  
  <input class="full-length-elem" id="paper" autocomplete list="bibliothek" placeholder="please select from your library here ..." />
  <p>  
  <article>  
  <div>
  
  <div class="panel">
   <a id="bibHeader" class="headers">Bibliography</a>
   <div class="bodies">
    <textarea readonly rows="10" id="thelist" class="semi-length-elem">
      Your bibliography will be generated here once you got something on your mind ...
    </textarea>   
   </div>
  </div>
  
  <div class="panel">
   	<a id="addlibrary" class="headers">Add To Library</a>	
   	<form id="newpaperform" class="bodies">
   	<fieldset class="semi-length-elem">
      	<label id="doilabel" for="doifield">DOI</label>
      	<input class="refform" id="doifield" placeholder="Enter doi, i.e. 'http://dx.doi.org/10.1023/A:1015460304860' OR '10.1023/A:1015460304860' OR '___.org/10.1023/A:1015460304860' " />
      	<input id="getpaperbtn" class="buttons" type="button" value="Get Paper" onclick="ajaxcall('getdoi',$('#doifield').val());" />
			
		<ul>	
		    <div id='authors'>
		    <label for="theauthors">Authors *</label>
		      <div id="theauthors">
		      
        	  </div>       	  
        	  <span id="moreauthors" >more</span>     	  
			</div>
			<div>
        		<label for="title">Title *</label>
        		<input id="title" class="npform" placeholder="Set the title ..." />  
        	</div>
        	<br>
			<div>
        		<label for="date">Year *</label>
        		<input id="date" class="npform" placeholder="Set the year ..." /> 	 		
			</div>
        	<br>
			<div>
        		<label for="month">Month</label>
        		<input id="month" class="npform" placeholder="Set the month ..." /> 			
			</div>
        	<br>
			<div>
        		<label for="publisher">Publisher</label>
        		<input id="publisher" class="npform" placeholder="Set the publisher ..." />
			</div>
        	<br>
			<div>
        		<label for="volume">Volume</label>
        		<input id="volume" class="npform" placeholder="Set the volume ..." />
			</div>
        	<br>
			<div>
        		<label for="issue">Issue</label>
        		<input id="issue" class="npform" placeholder="Set the issue ..." />
			</div>
        	<br>
			<div>
        		<label for="startpage">Start page</label>
        		<input id="startpage" class="npform" placeholder="Set the start page ..." />
			</div>
        	<br>
			<div>
        		<label for="lastpage">End page</label>
        		<input id="lastpage" class="npform" placeholder="Set the end page ..." />
			</div>				    
		</ul>
		<br>
		<span id="newpaperwarning">  </span>
		<input class="buttons" type="button" value="Submit" onclick="ajaxcall('newpaper',[$('#doifield').val(),$('#author').val(),$('#title').val(),$('#publisher').val(),$('#date').val(),$('#month').val(),$('#volume').val(),$('#issue').val(),$('#startpage').val(),$('#lastpage').val() ])" />
	</fieldset>
  </form>
  </div>
  	
  <div class="panel"><a id="removHeader" class="headers">Remove From Library</a>
   <div class="bodies">
   	<input class="semi-length-elem" id="deletepaper" list="bibliothek" placeholder="Please select which entry to delete from your library ..." />  
   	<input class="buttons" type="button" value="Remove" onclick="ajaxcall('rmpaper',document.getElementById('deletepaper').value.split('.  ')[2])" />
   	<datalist id="bibliothek" class="dlbib"></datalist> 
   	<br/>
   </div>
  </div>
   
 </div>
 </article>
</body>
</html>
