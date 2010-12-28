/*          Ajax start here */

    var http_rightpanel = createRequestObject();
	var objectIdright = 'ajaxOutput';
		
	function sendReqRightPanel(serverFileName) 
	{
		http_rightpanel.open('post', serverFileName);
		http_rightpanel.onreadystatechange = handleResponseRightPanel;
		http_rightpanel.send(null);
	}
	
	function handleResponseRightPanel() 
	{
		if(http_rightpanel.readyState == 4)
		{
			var response = http_rightpanel.responseText;
		
			document.getElementById( "contentLoad" ).innerHTML = response ;
			document.getElementById( "contentLoad" ).style.display = "" ;
		}
	}
	

/*          ajax ends here */