<!doctype html>  
<html lang="en" >
<head>

    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]--> 
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Digital Mindmapping</title>
	
	<link href="css/dm.css" rel="stylesheet" />	
	<link href="css/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
		
	<?php
	   $currentuser="guest";
	?>
	
	<div id="nav">
	<ul >
	    <div id="logo">
	    <span id="name">Digital Mindmapping</span>
	    </div>
	    <div id="username">
	    <span> <?php print "$currentuser";?> </span>
	    </div>
	    
		<li><a href="#" id="loginbutton">Login</a></li>
		<li><a href="#">Manage Mindmaps</a></li>
	</ul>
	</div>
	
	
	<form id="login" hide="true">
    <div id="inputs">
        
        <input id="username" type="text" placeholder="Email" required>   
        <input id="password" type="password" placeholder="Password" required>
    </div>
    <div id="actions">
        <div id="spacer"></div>
        <input type="submit" id="submitlogin" value="Log in">
        <a href="">Forgot your password?</a><a href="">Register</a>
        <div id="spacer"></div>
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
		  
		  
		  $('#loginbutton').click(function() {
			  $('#login').slideDown('slow', function() {
			  });
			 });
		  $('#submitlogin').click(function() {
			  $('#login').slideUp('slow', function() {
			  });
			 });
			
			
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
			 
		  // Switch buttons from "Log In | Register" to "Close Panel" on click
		  $("#toggle a").click(function () 
		  {
		    $("#toggle a").toggle();
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
					document.getElementById('bibliothek').innerHTML += tag;
					document.getElementById('bibliothek2').innerHTML += tag;
				}
				
			  }
			 }
			 xmlhttp.open("GET","php/getpapers.php",true);
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
  
  <div id="accordionResizer" style="padding:10px; width:980px; " class="ui-widget-content">
  <div id="accordion" class="accordion">
  
  
  <h3><a href="#">Bibliography</a></h3>
  <div>
    <textarea rows="10" id="thelist" class="form">
      Your bibliography will be generated here once you got something in your mind map ...
    </textarea>     
  </div>
  
  <h3><a href="#">Add To Library</a></h3>
	<div>

   <form >
		<fieldset>
    
      <label for="doifield">DOI</label>
      <input class="refform" id="doifield" name="doifield" type="text" placeholder="Enter your doi ..." />
      <input class="buttons" type="button" value="Get Paper" onclick="getdoi();" />
	
			
		<ul>	
		    <br>
        	<label for="author">Author</label>
        	<input class="refform" id="author" name="author" type="text" placeholder="Set the author names ..." />	
			</br>
			<br>
        	<label for="title">Title</label>
        	<input class="refform" id="title" name="title "type="text" placeholder="Set the title ..." />   		
			</br>
			<br>
        	<label for="month">Month</label>
        	<input class="refform" id="month" name="month" type="text" placeholder="Set the month ..." /> 		
			</br>
			<br>
        	<label for="date">Year</label>
        	<input class="refform" id="date" name="date" type="text" placeholder="Set the year ..." /> 		
			</br>
			<br>
        	<label for="publisher">Publisher</label>
        	<input class="refform" id="publisher" name="publisher" type="text" placeholder="Set the publisher ..." />
			</br>	
			<br>
        	<label for="volume">Volume</label>
        	<input class="refform" id="volume" name="volume" type="text" placeholder="Set the volume ..." />
			</br>	
			<br>
        	<label for="issue">Issue</label>
        	<input class="refform" id="issue" name="issue" type="text" placeholder="Set the issue ..." />
			</br>	
			<br>
        	<label for="startpage">Start page</label>
        	<input class="refform" id="startpage" name="startpage" type="text" placeholder="Set the start page ..." />
			</br>
			<br>
        	<label for="lastpage">End page</label>
        	<input class="refform" id="lastpage" name="lastpage" type="text" placeholder="Set the end page ..." />
			</br>
				    
		</ul>
		<input class="buttons" type="button" value="Submit" onclick="submitnewpaper()" />
       </fieldset>
     </form>
    
    
  </div>
  
  
  <h3><a href="#">Remove From Library</a></h3>
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
