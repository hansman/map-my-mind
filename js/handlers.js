function handlers()
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