<?php 
session_start(); 

function auto_version($file)
{
  $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
  return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
}

?>

<!doctype html>  
<html lang="en" >
<head>

    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>MapMyMind</title>
	
	<link href="<?=auto_version('/map-my-mind/css/mmm.css')?>" rel="stylesheet"/>	
	<script src="js/jquery-1.7.2.min.js"></script>
	<script src="js/painter.js"></script>		
	<script src="js/handlers.js"></script>
	
	<div id="nav">
	  <ul>
	    <div id="logo">
	    	<span id="name">MapMyMind</span>
	    </div>
	    <span id="shownname"></span>
	    <span id="shownmap"></span>
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
		  ajaxcall('getsession','usernm');
		  ajaxcall('getsession','mapnm');
		  ajaxcall('paperdata',null);
		  handlers();
		  $(".bodies").hide();
		  $("#thelist").val('');	  	
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
												jsonPapers=jsonResponse.data;                 
												for (var i=0; i<jsonResponse.data.length;i++) 
													$(".dlbib").append('<option label="'+ jsonResponse.data[i].title +'" value="' + jsonResponse.data[i].author.split(",")[1] + '.  ' + jsonResponse.data[i].date + '.  ' + jsonResponse.data[i].title + '" />');
												break;
							case "getdoi":      $(".npform").val('');
												$(".authorsform").val('');
												while(jsonResponse.data[0].length > authors)
													addauthors();
												for(var key in jsonResponse.data[0])
  													$("#authorl"+key).val(jsonResponse.data[0][key][0]);
  												for(var key in jsonResponse.data[1])
  													$("#authorf"+key).val(jsonResponse.data[1][key][0]);					      							
											    $("#title").val(jsonResponse.data[2][0]);
				      							$("#date").val(jsonResponse.data[3][0]);
				      							if(jsonResponse.data[4])
				      								$("#publisher").val(jsonResponse.data[4][0]);
				      							if(jsonResponse.data[5])
				      								$("#month").val(jsonResponse.data[5][0]);
				      							if(jsonResponse.data[6])
				      								$("#volume").val(jsonResponse.data[6][0]);
				      							if(jsonResponse.data[7])
				      								$("#issue").val(jsonResponse.data[7][0]);
				      							if(jsonResponse.data[8])
				      								$("#startpage").val(jsonResponse.data[8][0]);
				      							if(jsonResponse.data[9])
					      							$("#lastpage").val(jsonResponse.data[9][0]);
				      							break;
				      							
							case "getsession":	switch(jsonResponse.meta['option'])
												{
													case "usernm":	$('#shownname').html(jsonResponse.data[0]) ;
			  				  										if (jsonResponse.data[0]=="guest")
								  										$('#loginbutton').text("Login");
							  										else
								  										$('#loginbutton').text("Logout");
							  										break;
													case "mapnm":	if(jsonResponse.data[0])
																	{
																		$('#shownmap').html(jsonResponse.data[0]);
																		$('#currmapname').html(jsonResponse.data[0]);																		
																	}				
						  											break;
							  						default:		alert('ajaxcall - getsession');
												} 
												break;
							case "login":		if(jsonResponse.meta['status'] == "wrong login")
									  				$('#warningtext').text("Your login data is incorrect ...");
								  				else
								  				{
									  				ajaxcall("paperdata",null);
									  				ajaxcall('getsession','usernm');									  
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
												ajaxcall('getsession','usernm');
												break;
							case "newpaper":	if (jsonResponse.meta['status']!='passed')
				     	 							$("#newpaperwarning").html(jsonResponse.meta['status']);
												else
												{
													ajaxcall("paperdata",null);
													$(".npform").val('');
													$(".authorsform").val('');
												}				     							
				     							break;
							case "rmpaper":		$('#deletepaper').val("");
						     					$('#paper').val("");
						     					ajaxcall("paperdata",null);
						     					break;
							case "managemap":   switch(jsonResponse.meta['option'])
												{
													case "0": 	if(jsonResponse.meta['status']=='exists')
																	$("#mapswarning").text('This map already exists');
																else
																	$("#mapswarning").text('');
															  	$('#savemap').val('');
															  	ajaxcall('managemap',[1,'','','']);
															 	break;
													case "1":	$("#maps").text('');
																for (var i=0; i<jsonResponse.data.length;i++) 
																	$("#maps").append('<option label="'+ jsonResponse.data[i].name +'" value="' + jsonResponse.data[i].name +'" />');
																break;
													case "2":	ajaxcall('managemap',[1,'','','']);
																$('#delmap').val('');
																$("#mapswarning").text('');
																break;
													//load map
													case "3":	if(jsonResponse.meta['status']=='passed')
																{
																	$("#mapswarning").text('');
																	$('#loadmap').val('');
																	console.log('passed');
																	sm = new StateMachine();
													  				mindmap = new MindMap(document.getElementById("canvas"));
													  				mindmap.load(jsonResponse.data);
													  				mindmap.performState();
																}
																else
																	$("#mapswarning").text(jsonResponse.meta['status']);								
																																
																break;
												}
												ajaxcall('getsession','mapnm');												
												break;						     					
						    default:			alert("Problem selecting the ajax type in index.php");
							}
				  }
			}

			if(type=="getdoi")
			{
				args = args.split(".org/");
				if (args[1])
					args=args[1];
			}

			xmlhttp.open("GET","php/ajax.php?type="+type+"&args="+args,true);
			xmlhttp.send();
			
		}


		function newpaper()
		{
			if( $('#title').val() && $('#date').val() &&  $('#authorl0').val()  )
			{
				var args=[$('#doifield').val(),$('#title').val(),$('#publisher').val(),$('#date').val(),$('#month').val(),$('#volume').val(),$('#issue').val(),$('#startpage').val(),$('#lastpage').val() ];
				for( var i=0;$('#authorl'+i).val(); i++ )
				{
					args.push($('#authorf'+i).val());
					args.push($('#authorl'+i).val());
				}				
				ajaxcall('newpaper',args);
			}
			else
				$("#newpaperwarning").html("Please fill out all required fields");
		}

		function addauthors()
		{		
		  if(!authors)
			  $('#theauthors').html('');
		  $('#theauthors').append("<div class='authorrows'><input id='authorf"+authors+"' class='authorsform' placeholder='First name' /><input id='authorf"+(authors+1)+"' class='authorsform' placeholder='First name' /><input id='authorf"+(authors+2)+"' class='authorsform' placeholder='First name' /><input id='authorf"+(authors+3)+"' class='authorsform' placeholder='First name' /></div><br><div class='authorrows'><input id='authorl"+authors+"' class='authorsform' placeholder='Last name' /><input id='authorl"+(authors+1)+"' class='authorsform' placeholder='Last name' /><input id='authorl"+(authors+2)+"' class='authorsform' placeholder='Last name' /><input id='authorl"+(authors+3)+"' class='authorsform' placeholder='Last name' /></div><br>");
		  authors+=4;
		}		
					
	</script>    
</head>

<body id="thebody" onload="document_load()">
  
  <canvas id="canvas" width="1000" height="400" tabindex="0"></canvas>  
  <div class="full-length-elem">
  <input id="paper" autocomplete list="bibliothek0" placeholder="Select a reference from your library" />
  <span id="selectinput">Reference</span> 
  <datalist id="bibliothek0" class="dlbib"></datalist> 
  
  </div>
  <br><br>
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
      	<input class="refform" id="doifield" placeholder="Enter doi number or url" />
      	<input id="getpaperbtn" class="buttons" type="button" value="Get Paper" onclick="ajaxcall('getdoi',$('#doifield').val());" />
			
		<ul>	
		    <div id='authors'>
		    <label for="theauthors">Authors *</label>
		      <div id="theauthors">
		      
        	  </div>       	  
        	  <span id="moreauthors" >more</span>  
        	  <br>  	  
			</div>
			<div>
        		<label for="title">Title *</label>
        		<input id="title" class="npform" placeholder="Set the title" />  
        	</div>
        	<br>
			<div>
        		<label for="date">Year *</label>
        		<input id="date" class="npform" placeholder="Set the year" /> 	 		
			</div>
        	<br>
			<div>
        		<label for="month">Month</label>
        		<input id="month" class="npform" placeholder="Set the month" /> 			
			</div>
        	<br>
			<div>
        		<label for="publisher">Publisher</label>
        		<input id="publisher" class="npform" placeholder="Set the publisher" />
			</div>
        	<br>
			<div>
        		<label for="volume">Volume</label>
        		<input id="volume" class="npform" placeholder="Set the volume" />
			</div>
        	<br>
			<div>
        		<label for="issue">Issue</label>
        		<input id="issue" class="npform" placeholder="Set the issue" />
			</div>
        	<br>
			<div>
        		<label for="startpage">Start page</label>
        		<input id="startpage" class="npform" placeholder="Set the start page" />
			</div>
        	<br>
			<div>
        		<label for="lastpage">End page</label>
        		<input id="lastpage" class="npform" placeholder="Set the end page" />
			</div>				    
		</ul>
		<br>
		<span id="newpaperwarning">  </span>
		<input class="buttons" type="button" value="Submit" onclick="newpaper()" />
	</fieldset>
  </form>
  </div>
  	
  <div class="panel"><a id="removHeader" class="headers">Remove From Library</a>
   <div class="bodies">
   	<input class="semi-length-elem" id="deletepaper" list="bibliothek1" placeholder="Please select which entry to delete from your library ..." />  
   	<input class="buttons" type="button" value="Remove" onclick="ajaxcall('rmpaper',document.getElementById('deletepaper').value.split('.  ')[2])" />
   	<datalist id="bibliothek1" class="dlbib"></datalist> 
   	<br/>
   </div>
  </div>

  <div class="panel"><a id="manageHeader" class="headers">Manage Mind Maps</a>
   <div class="bodies">
   
    <span id="currmapname" class="half-length-elem"></span>
   	<input class="mapbuttons" type="button" value="Save Changes" onclick="ajaxcall('managemap',[0,$('#currmapname').text(),mindmap.getZoom(),mindmap.getMap()])" />
   	<br>
   	<input class="half-length-elem" id="loadmap" list="maps" placeholder="Select which Mind Map to load" />  
   	<input class="mapbuttons" type="button" value="Load" onclick="ajaxcall('managemap',[3,$('#loadmap').val(),'',''])" />
   	<br>
   	<input class="half-length-elem" id="savemap" placeholder="Save current Mind Map as" />  
   	<input class="mapbuttons" type="button" value="Save" onclick="ajaxcall('managemap',[0,$('#savemap').val(),mindmap.getZoom(),mindmap.getMap()])" />
   	<br>
   	<input class="half-length-elem" id="delmap" list="maps" placeholder="Select Mind Map to delete" />  
   	<input class="mapbuttons" type="button" value="Delete" onclick="ajaxcall('managemap',[2,$('#delmap').val(),'',''])" />
   	<datalist id="maps" class="maps"></datalist> 
   	<br>
   	<br>
   	<span id="mapswarning"></span>
   	<br>
   </div>
  </div>  
  
 </div>
 </article>
</body>
</html>
