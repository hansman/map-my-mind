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
	<script src="js/handlers.js"></script>
	
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
		  ajaxcall("getusername",null);
		  ajaxcall("paperdata",null);
		  handlers();		  	
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
						//console.log(xmlhttp.responseText);

						switch(type)
						{
							case "paperdata":	$(".dlbib").text('');							  
												jsonPapers = eval( '(' + xmlhttp.responseText + ')' );
												var tag;			                 
												for (var i=0; i<jsonPapers.length;i++) 
												{ 
													tag = '<option label="'+ jsonPapers[i].title +'" value="' + jsonPapers[i].author + '.  ' + jsonPapers[i].date + '.  ' + jsonPapers[i].title + '" />';
													$(".dlbib").append(tag);
												}
												break;
							case "getdoi":      var paperMeta = eval( xmlhttp.responseText );
				      							document.getElementById("author").value=paperMeta[0].replace(/,/g,' and');
				      							$("#title").val(paperMeta[1]);
				      							$("#date").val(paperMeta[2]);
				      							$("#publisher").val(paperMeta[3]);
				      							$("#month").val(paperMeta[4]);
				      							$("#volume").val(paperMeta[5]);
				      							$("#issue").val(paperMeta[6]);
				      							$("#startpage").val(paperMeta[7]);
				      							$("#lastpage").val(paperMeta[8]);
												break;
							case "getusername":	document.getElementById('shownname').innerHTML =  xmlhttp.responseText ;
			  				  					if (xmlhttp.responseText=="guest")
								  					$('#loginbutton').text("Login");
							  					else
								  					$('#loginbutton').text("Logout");
							  					break; 
							case "login":		//console.log(xmlhttp.responseText);
								  				if(xmlhttp.responseText == "Wrong login")
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
							case "newpaper":	if (xmlhttp.responseText)
				     	 							document.getElementById("newpaperwarning").innerHTML=xmlhttp.responseText;
												else
												{
													ajaxcall("paperdata",null);
													$(".npform").val('');
												}				     							
				     							break;
							case "rmpaper":		$('#deletepaper').val("");
						     					$('paper').val("");
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
  <datalist id="bibliothek" class="dlbib"></datalist>
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
      <input id="getpaperbtn" class="buttons" type="button" value="Get Paper" onclick="ajaxcall('getdoi',$('#doifield').val());" />
			
		<ul>	
		    <div>
        		<label for="author">Author *</label>
        		<input id="author" class="npform" name="author" type="text" placeholder="Set the author names ..." />	
			</div>
        	<br>
			<div>
        		<label for="title">Title *</label>
        		<input id="title" class="npform" name="title "type="text" placeholder="Set the title ..." />  
        	</div>
        	<br>
			<div>
        		<label for="date">Year *</label>
        		<input id="date" class="npform" name="date" type="text" placeholder="Set the year ..." /> 	 		
			</div>
        	<br>
			<div>
        		<label for="month">Month</label>
        		<input id="month" class="npform" name="month" type="text" placeholder="Set the month ..." /> 			
			</div>
        	<br>
			<div>
        		<label for="publisher">Publisher</label>
        		<input id="publisher" class="npform" name="publisher" type="text" placeholder="Set the publisher ..." />
			</div>
        	<br>
			<div>
        		<label for="volume">Volume</label>
        		<input id="volume" class="npform" name="volume" type="text" placeholder="Set the volume ..." />
			</div>
        	<br>
			<div>
        		<label for="issue">Issue</label>
        		<input id="issue" class="npform" name="issue" type="text" placeholder="Set the issue ..." />
			</div>
        	<br>
			<div>
        		<label for="startpage">Start page</label>
        		<input id="startpage" class="npform" name="startpage" type="text" placeholder="Set the start page ..." />
			</div>
        	<br>
			<div>
        		<label for="lastpage">End page</label>
        		<input id="lastpage" class="npform" name="lastpage" type="text" placeholder="Set the end page ..." />
			</div>
				    
		</ul>
		<br>
		<span id="newpaperwarning">  </span>
		<input class="buttons" type="button" value="Submit" onclick="ajaxcall('newpaper',[$('#doifield').val(),$('#author').val(),$('#title').val(),$('#publisher').val(),$('#date').val(),$('#month').val(),$('#volume').val(),$('#issue').val(),$('#startpage').val(),$('#lastpage').val() ])" />
       </fieldset>
     </form>
  </div>	
  
  
  <h3><a>Remove From Library</a></h3>
  <div>
   <input class="form" id="deletepaper" list="bibliothek" type="text" name="bibliothek" placeholder="please select which entry to delete from your library ..." />  
   <datalist id="bibliothek2" class="dlbib"></datalist>  
   <input class="buttons" type="button" value="Remove" onclick="ajaxcall('rmpaper',document.getElementById('deletepaper').value.split('.  ')[2])" /> 
  </div>
   
 </div>
 </article>
  

</body>
</html>
