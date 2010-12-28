function hideAllObjectTags()
{
	if(document.getElementById("messenger-wrap")) {
	if( document.getElementById("messenger-wrap").style.display == "" ) {
	var elems = document.getElementsByTagName('embed') ;
	
	for(var xi=0 ; xi<elems.length ; xi++ ) {
		if( elems[xi] ) {
			elems[xi].style.display = "none" ;
		}
	}

	if( elems.length > 0 ) {
		document.onmousedown = showAllObjectTags ;
	}
	}}
}

function showAllObjectTags()
{
	var elems = document.getElementsByTagName('embed') ;
	
	for(var xi=0 ; xi<elems.length ; xi++ ) {
		if( elems[xi] ) {
			elems[xi].style.display = "" ;
		}
	}
}

function bookmarkPage()
{
	var favUrl = location.href ;
	var favtitle = "Events Listed - Exclusive Events Club" ;
	
	if (window.sidebar) { // Mozilla Firefox Bookmark
		window.sidebar.addPanel(favtitle, favUrl,"");
	} else if( window.external ) { // IE Favorite
		window.external.AddFavorite( favUrl, favtitle); }
}

////// ############## shopping cart ###################
function updateCart() 
{
	document.frmshoppingcart.cartaction.value = 'update' ;
	document.frmshoppingcart.action = "cart.php" ;
	document.frmshoppingcart.submit() ;
}


function checkoutCart() 
{
	document.frmshoppingcart.cartaction.value = 'checkout' ;
	document.frmshoppingcart.action = "checkout.php" ;
	document.frmshoppingcart.submit() ;
}

function continueShopping(val) 
{
	window.location = val ;
}

///// ############### End Shopping cart ##############


function screenWidthHeight()
{
	var bodyWidth = document.documentElement.clientWidth;
	var bodyHeight = document.documentElement.clientHeight;
	var bodyOffsetWidth = document.getElementById( "container" ).offsetWidth  ;
	var bodyOffsetHeight = document.getElementById( "container" ).offsetHeight + 140 ;
	
	var bodyWidth, bodyHeight; 
	if (self.innerHeight){ // all except Explorer 
	   bodyWidth = self.innerWidth; 
	   bodyHeight = self.innerHeight; 
	}  else if (document.documentElement && document.documentElement.clientHeight) {
	   // Explorer 6 Strict Mode 		 
	   bodyWidth = document.documentElement.clientWidth; 
	   bodyHeight = document.documentElement.clientHeight; 
	} else if (document.body) {// other Explorers 		 
	   bodyWidth = document.body.clientWidth; 
	   bodyHeight = document.body.clientHeight; 
	}

	return [bodyWidth,bodyHeight,bodyOffsetWidth,bodyOffsetHeight] ;
}

////// hightlight script

var currentlyActiveInputRef = false;
var currentlyActiveInputClassName = false;

function highlightActiveInput()
{
	if(currentlyActiveInputRef){
		currentlyActiveInputRef.className = currentlyActiveInputClassName;
	}
	
	currentlyActiveInputClassName = this.className;
	this.className = 'inputHighlighted';
	currentlyActiveInputRef = this;
	
	
}

function blurActiveInput()
{
	this.className = currentlyActiveInputClassName;
}


function initInputHighlightScript()
{
	var tags = ['INPUT','TEXTAREA','SELECT'];
	
	for(tagCounter=0;tagCounter<tags.length;tagCounter++){
		var inputs = document.getElementsByTagName(tags[tagCounter]);
		for(var no=0;no<inputs.length;no++){
			if(inputs[no].className && inputs[no].className=='doNotHighlightThisInput')continue;
			
			if(inputs[no].tagName.toLowerCase()=='textarea' || (inputs[no].tagName.toLowerCase()=='input' && inputs[no].type.toLowerCase()=='text') || inputs[no].tagName.toLowerCase()=='select'){
				inputs[no].onfocus = highlightActiveInput;
				inputs[no].onblur = blurActiveInput;
			}
		}
	}
}

////// end heightlight

///////////// date difference

function dateDifference( dateFirst , dateSecond ) 
{

/* ////////////
   date format for date first and date second
   
   dateFirst = mm/dd/yyyy
   dateSecond = mm/dd/yyyy
/////////// */

date1 = new Date();
date2 = new Date();
diff  = new Date();


/////// date 1
date1temp = new Date(dateFirst + " 00:00:00");
date1.setTime(date1temp.getTime());

/////// date 2
date2temp = new Date(dateSecond + " 00:00:00");
date2.setTime(date2temp.getTime());

// sets difference date to difference of first date and second date

if( date1.getTime() > date2.getTime() )
{
	alert( "Invalid Date. Second Date should be grater than First date." ) ;
	return false ;
}

diff.setTime(Math.abs(date1.getTime() - date2.getTime()));

timediff = diff.getTime();

weeks = Math.floor(timediff / (1000 * 60 * 60 * 24 * 7));
timediff -= weeks * (1000 * 60 * 60 * 24 * 7);

days = Math.floor(timediff / (1000 * 60 * 60 * 24)); 
timediff -= days * (1000 * 60 * 60 * 24);

hours = Math.floor(timediff / (1000 * 60 * 60)); 
timediff -= hours * (1000 * 60 * 60);

mins = Math.floor(timediff / (1000 * 60)); 
timediff -= mins * (1000 * 60);

secs = Math.floor(timediff / 1000); 
timediff -= secs * 1000;

////////// convert weeks into days
var convertWeekDays = ( 7 * weeks ) ;        


////////// total days
var totalDays = convertWeekDays + days ;

//////// return total date difference
return totalDays ; 

}

//////////// date difference

///////// hide and show the panels

function hideShowPanel(panelId , expandColapse)
{
	if( document.getElementById( panelId ).style.display == "" )
	{
		document.getElementById( panelId ).style.display = "none" ;
		document.getElementById( expandColapse ).innerHTML = "<b>[ + ]</b>" ;
	}
	else if( document.getElementById( panelId ).style.display == "none" )
	{
		document.getElementById( panelId ).style.display = "" ;
		document.getElementById( expandColapse ).innerHTML = "<b>[ - ]</b>" ;
	}
}

function hideShowPanelSeeAll(panelId , expandColapse)
{
	if( document.getElementById( panelId ).style.display == "" )
	{
		document.getElementById( panelId ).style.display = "none" ;
		document.getElementById( expandColapse ).innerHTML = "See All" ;
	}
	else if( document.getElementById( panelId ).style.display == "none" )
	{
		document.getElementById( panelId ).style.display = "" ;
		document.getElementById( expandColapse ).innerHTML = "Hide All" ;
	}
}

function hideShowPanelText(panelId , expandColapse)
{
	if( document.getElementById( panelId ).style.display == "" )
	{
		document.getElementById( panelId ).style.display = "none" ;
	}
	else if( document.getElementById( panelId ).style.display == "none" )
	{
		document.getElementById( panelId ).style.display = "" ;
	}
}

function hideShowPanelrev(panelId , expandColapse)
{
	if( document.getElementById( panelId ).style.display == "" )
	{
		document.getElementById( panelId ).style.display = "none" ;
		document.getElementById( expandColapse ).innerHTML = "<span style=color:#000000;font-weight:bold>[+]</span>" ;
	}
	else if( document.getElementById( panelId ).style.display == "none" )
	{
		document.getElementById( panelId ).style.display = "" ;
		document.getElementById( expandColapse ).innerHTML = "<span style=color:#000000;font-weight:bold>[-]</span>" ;
	}
}

function hideShowPanelauto(panelId , expandColapse)
{
	if( document.getElementById( panelId ).style.display == "" )
	{
		document.getElementById( panelId ).style.display = "none" ;
		document.getElementById( expandColapse ).innerHTML = "<span style=color:#2369A5;font-weight:bold>[+]</span>" ;
	}
	else if( document.getElementById( panelId ).style.display == "none" )
	{
		document.getElementById( panelId ).style.display = "" ;
		document.getElementById( expandColapse ).innerHTML = "<span style=color:#2369A5;font-weight:bold>[-]</span>" ;
	}
}

function collapseExpandPanel( idFlag ) {
	
	var id = "" ;
	ids = collapseExpand.split("_"); 
	
	if( idFlag == "collapse" )
	{
		document.getElementById( "expandCollapse" ).innerHTML = "<a href='javascript:;' onclick='collapseExpandPanel(\"expand\")'>[ + ] Expand</a>" ;
		for(i=0 ; i<=ids.length ; i++) {
			document.getElementById( ids[i] ).style.display = "none" ;	
		}

	}
	else
	{
		document.getElementById( "expandCollapse" ).innerHTML = "<a href='javascript:;' onclick='collapseExpandPanel(\"collapse\")'>[ - ] Collapse</a>" ;
		for(i=0 ; i<=ids.length ; i++) {
			document.getElementById( ids[i] ).style.display = "" ;	
		}

	}
}

/*function expandPanel(id) {
	ids = id.split("_"); 
	
	for(i=0 ; i<=ids.length ; i++) {
		document.getElementById( ids[i] ).style.display = "" ;	
	}
}*/

////////// end hide and show panels


///////////////// function for loading banners ///////////////

function loadBanners()
{
	if( document.getElementById( "loadbanner" ) )
	{
		document.getElementById( "loadbanner" ).innerHTML = "<center> <img src='banner_loading.gif' > &nbsp; Loading Banner......</center>" ;
		sendReq( 'ajax/banners.php' ) ;
	}
}

/////////////////// loading banners ends

//////////////////// multi select drop down box


///////////////
		function SelectMoveRows(SS1,SS2)
		{
			var SelID='';
			var SelText='';
			// Move rows from SS1 to SS2 from bottom to top
			for (i=SS1.options.length - 1; i>=0; i--)
			{
				if (SS1.options[i].selected == true)
				{
					SelID=SS1.options[i].value;
					SelText=SS1.options[i].text;
					var newRow = new Option(SelText,SelID);
					SS2.options[SS2.length]=newRow;
					SS1.options[i]=null;
				}
			}
			SelectSort(SS2);
		}

		function moveAllRows(SS1,SS2)
		{
			var SelID='';
			var SelText='';

			var obj = SS1 ;
			for(var no2=0;no2<obj.options.length;no2++)
			{
				obj.options[no2].selected = true;
			}

			// Move rows from SS1 to SS2 from bottom to top
			for (i=SS1.options.length - 1; i>=0; i--)
			{
				if (SS1.options[i].selected == true)
				{
					SelID=SS1.options[i].value;
					SelText=SS1.options[i].text;
					var newRow = new Option(SelText,SelID);
					SS2.options[SS2.length]=newRow;
					SS1.options[i]=null;
				}
			}
			SelectSort(SS2);
		}

		function removeAllRows(SS1,SS2)
		{
			var SelID='';
			var SelText='';

			var obj = SS1 ;
			for(var no2=0;no2<obj.options.length;no2++)
			{
				obj.options[no2].selected = true;
			}

			// Move rows from SS1 to SS2 from bottom to top
			for (i=SS1.options.length - 1; i>=0; i--)
			{
				if (SS1.options[i].selected == true)
				{
					SelID=SS1.options[i].value;
					SelText=SS1.options[i].text;
					var newRow = new Option(SelText,SelID);
					SS2.options[SS2.length]=newRow;
					SS1.options[i]=null;
				}
			}
			SelectSort(SS2);
		}

		function SelectSort(SelList)
		{
			var ID='';
			var Text='';
			for (x=0; x < SelList.length - 1; x++)
			{
				for (y=x + 1; y < SelList.length; y++)
				{
					if (SelList[x].text > SelList[y].text)
					{
						// Swap rows
						ID=SelList[x].value;
						Text=SelList[x].text;
						SelList[x].value=SelList[y].value;
						SelList[x].text=SelList[y].text;
						SelList[y].value=ID;
						SelList[y].text=Text;
					}
				}
			}
		}
			
////////////////

	var fromBoxArray = new Array();
	var toBoxArray = new Array();
	var selectBoxIndex = 0;
	var arrayOfItemsToSelect = new Array();
	
	
	function moveSingleElement()
	{
		var selectBoxIndex = this.parentNode.parentNode.id.replace(/[^\d]/g,'');
		var tmpFromBox;
		var tmpToBox;
		if(this.tagName.toLowerCase()=='select'){			
			tmpFromBox = this;
			if(tmpFromBox==fromBoxArray[selectBoxIndex])tmpToBox = toBoxArray[selectBoxIndex]; else tmpToBox = fromBoxArray[selectBoxIndex];
		}else{
		
			if(this.value.indexOf('>')>=0){
				tmpFromBox = fromBoxArray[selectBoxIndex];
				tmpToBox = toBoxArray[selectBoxIndex];			
			}else{
				tmpFromBox = toBoxArray[selectBoxIndex];
				tmpToBox = fromBoxArray[selectBoxIndex];	
			}
		}
		
		for(var no=0;no<tmpFromBox.options.length;no++){
			if(tmpFromBox.options[no].selected){
				tmpFromBox.options[no].selected = false;
				tmpToBox.options[tmpToBox.options.length] = new Option(tmpFromBox.options[no].text,tmpFromBox.options[no].value);
				
				for(var no2=no;no2<(tmpFromBox.options.length-1);no2++){
					tmpFromBox.options[no2].value = tmpFromBox.options[no2+1].value;
					tmpFromBox.options[no2].text = tmpFromBox.options[no2+1].text;
					tmpFromBox.options[no2].selected = tmpFromBox.options[no2+1].selected;
				}
				no = no -1;
				tmpFromBox.options.length = tmpFromBox.options.length-1;
											
			}			
		}
		
		
		var tmpTextArray = new Array();
		for(var no=0;no<tmpFromBox.options.length;no++){
			tmpTextArray.push(tmpFromBox.options[no].text + '___' + tmpFromBox.options[no].value);			
		}
		tmpTextArray.sort();
		var tmpTextArray2 = new Array();
		for(var no=0;no<tmpToBox.options.length;no++){
			tmpTextArray2.push(tmpToBox.options[no].text + '___' + tmpToBox.options[no].value);			
		}		
		tmpTextArray2.sort();
		
		for(var no=0;no<tmpTextArray.length;no++){
			var items = tmpTextArray[no].split('___');
			tmpFromBox.options[no] = new Option(items[0],items[1]);
			
		}		
		
		for(var no=0;no<tmpTextArray2.length;no++){
			var items = tmpTextArray2[no].split('___');
			tmpToBox.options[no] = new Option(items[0],items[1]);			
		}
	}
	
	function sortAllElement(boxRef)
	{
		var tmpTextArray2 = new Array();
		for(var no=0;no<boxRef.options.length;no++){
			tmpTextArray2.push(boxRef.options[no].text + '___' + boxRef.options[no].value);			
		}		
		tmpTextArray2.sort();		
		for(var no=0;no<tmpTextArray2.length;no++){
			var items = tmpTextArray2[no].split('___');
			boxRef.options[no] = new Option(items[0],items[1]);			
		}		
		
	}
	function moveAllElements()
	{
		var selectBoxIndex = this.parentNode.parentNode.id.replace(/[^\d]/g,'');
		var tmpFromBox;
		var tmpToBox;		
		if(this.value.indexOf('>')>=0){
			tmpFromBox = fromBoxArray[selectBoxIndex];
			tmpToBox = toBoxArray[selectBoxIndex];			
		}else{
			tmpFromBox = toBoxArray[selectBoxIndex];
			tmpToBox = fromBoxArray[selectBoxIndex];	
		}
		
		for(var no=0;no<tmpFromBox.options.length;no++){
			tmpToBox.options[tmpToBox.options.length] = new Option(tmpFromBox.options[no].text,tmpFromBox.options[no].value);			
		}	
		
		tmpFromBox.options.length=0;
		sortAllElement(tmpToBox);
		
	}
	
	
	/* This function highlights options in the "to-boxes". It is needed if the values should be remembered after submit. Call this function onsubmit for your form */
	function multipleSelectOnSubmit()
	{
		for(var no=0;no<arrayOfItemsToSelect.length;no++){
			var obj = arrayOfItemsToSelect[no];
			for(var no2=0;no2<obj.options.length;no2++){
				obj.options[no2].selected = true;
			}
		}
		
	}
	
	function createMovableOptions(fromBox,toBox,totalWidth,totalHeight,labelLeft,labelRight)
	{		
		fromObj = document.getElementById(fromBox);
		toObj = document.getElementById(toBox);
		
		arrayOfItemsToSelect[arrayOfItemsToSelect.length] = toObj;

		
		fromObj.ondblclick = moveSingleElement;
		toObj.ondblclick = moveSingleElement;

		
		fromBoxArray.push(fromObj);
		toBoxArray.push(toObj);
		
		var parentEl = fromObj.parentNode;
		
		var parentDiv = document.createElement('DIV');
		parentDiv.className='multipleSelectBoxControl';
		parentDiv.id = 'selectBoxGroup' + selectBoxIndex;
		//parentDiv.style.width = totalWidth + 'px';
		//parentDiv.style.height = totalHeight + 'px';
		parentEl.insertBefore(parentDiv,fromObj);
		
		
		var subDiv = document.createElement('DIV');
		subDiv.style.width = (Math.floor(totalWidth/2) - 15) + 'px';
		fromObj.style.width = (Math.floor(totalWidth/2) - 15) + 'px';

		/*var label = document.createElement('SPAN');
		label.innerHTML = labelLeft;
		subDiv.appendChild(label);*/
		
		subDiv.appendChild(fromObj);
		subDiv.className = 'multipleSelectBoxDiv';
		parentDiv.appendChild(subDiv);
		
		var heightDiv = document.createElement('DIV');
		heightDiv.style.height = '3px' ;
		parentDiv.appendChild(heightDiv);
		
		var buttonDiv = document.createElement('span');
		buttonDiv.style.verticalAlign = 'top';
		buttonDiv.style.paddingTop = (totalHeight/2) - 50 + 'px';
		buttonDiv.style.width = '30px';
		//buttonDiv.style.height = '40px';
		buttonDiv.style.textAlign = 'center';
		parentDiv.appendChild(buttonDiv);
		
		var buttonRight = document.createElement('INPUT');
		buttonRight.type='button';
		buttonRight.value = '>';
		buttonDiv.appendChild(buttonRight);	
		buttonRight.onclick = moveSingleElement;	
		
		var buttonAllRight = document.createElement('INPUT');
		buttonAllRight.type='button';
		buttonAllRight.value = '>>';
		buttonAllRight.onclick = moveAllElements;
		buttonDiv.appendChild(buttonAllRight);		
		
		var buttonLeft = document.createElement('INPUT');
		buttonLeft.style.marginTop='10px';
		buttonLeft.type='button';
		buttonLeft.value = '<';
		buttonLeft.onclick = moveSingleElement;
		buttonDiv.appendChild(buttonLeft);		
		
		var buttonAllLeft = document.createElement('INPUT');
		buttonAllLeft.type='button';
		buttonAllLeft.value = '<<';
		buttonAllLeft.onclick = moveAllElements;
		buttonDiv.appendChild(buttonAllLeft);
		
		/*var heightDiv = document.createElement('DIV');
		heightDiv.style.height = '3px' ;
		parentDiv.appendChild(heightDiv);	*/	

		var subDiv = document.createElement('DIV');
		subDiv.style.width = (Math.floor(totalWidth/2) - 15) + 'px';
		toObj.style.width = (Math.floor(totalWidth/2) - 15) + 'px';

		/*var label = document.createElement('SPAN');
		label.innerHTML = labelRight;
		subDiv.appendChild(label);*/
				
		subDiv.appendChild(toObj);
		parentDiv.appendChild(subDiv);		
		
		toObj.style.height = (totalHeight - label.offsetHeight) + 'px';
		fromObj.style.height = (totalHeight - label.offsetHeight) + 'px';

			
		selectBoxIndex++;
		
	}

//////////////////// multi select dropdown ends

////////////////// process message
function showProcessMessage( innerId , msg)
{
	document.getElementById( innerId ).innerHTML = msg ;
	document.getElementById( innerId ).style.display = '' ;
}

function processcomplete()
{
	if( document.getElementById( "messenger-wrap" ) )
		document.getElementById( "messenger-wrap" ).style.display = 'none' ;
}

////////////////////////// process message ends

function deleteRecord(delId)
{
	var url = location.href ;
	var urlString = url.split( "&delId=" ) ;
	
	var agree = confirm( "Are you sure, you want to delete this record?" ) ;

	if( agree )
	{
		var see = urlString[0] + "&delId=" + delId;
		window.location = urlString[0] + "&delId=" + delId ;
	}
}

function unsubscribe(delId)
{
	var url = location.href ;
	var urlString = url.split( "&unsub=" ) ;
	
	var agree = confirm( "Are you sure, you want to unsubscribe this group?" ) ;

	if( agree )
	{
		var see = urlString[0] + "&unsub=" + delId;
		window.location = urlString[0] + "&unsub=" + delId ;
	}
}


/*          Ajax start here */

    var http = createRequestObject();
	var http_response = createRequestObject();
	var http_account = createRequestObject();
	var objectId = 'ajaxOutput';
	var httpMap = createRequestObject();


	function createRequestObject(htmlObjectId)
	{
		var obj;
		var browser = navigator.appName;
		
		objectId = htmlObjectId;
		
		/*if(browser == "Microsoft Internet Explorer")
		{
			obj = new ActiveXObject("Microsoft.XMLHTTP");
		}
		else
		{
			obj = new XMLHttpRequest();
		}*/

		if (window.ActiveXObject) {
			obj = new ActiveXObject("Microsoft.XMLHTTP");
		}
		else if (window.XMLHttpRequest) {
			obj = new XMLHttpRequest();
		}

		return obj;    
	}
	
	var setValAjaxWin = 0 ;
	
	function sendReq(serverFileName, variableNames, variableValues) 
	{
		hideAllObjectTags() ;

		var paramString = '';
		
		variableNames = variableNames.split(',');
		variableValues = variableValues.split(',');
		
		for(i=0; i<variableNames.length; i++) 
		{
			paramString += variableNames[i]+'='+variableValues[i]+'&';
		}
		paramString = paramString.substring(0, (paramString.length-1));
		
				
		if (paramString.length == 0) 
		{
			http.open('post', serverFileName);
		}
		else 
		{
			http.open('post', serverFileName+'?'+paramString);
		}
		http.onreadystatechange = handleResponse;
		http.send(null);
	}
	
	function sendReq(serverFileName) 
	{
		hideAllObjectTags() ;

		setValAjaxWin++ ;
		http.open('post', serverFileName);
		http.onreadystatechange = handleResponse;
		http.send(null);
		
		if(document.getElementById('messenger'))
			document.getElementById('messenger').scrollIntoView() ;
	}
	
	function sendReqMap(serverFileName)
	{
		setValAjaxWin++ ;
		httpMap.open('post', serverFileName);
		httpMap.onreadystatechange = handleResponseMap;
		httpMap.send(null);
		
		if(document.getElementById('messenger'))
			document.getElementById('messenger').scrollIntoView() ;
	}
	
	function handleResponse()
	{
		if(http.readyState == 4)
		{
			responseText = http.responseText;
		
			ajaxProcessComplete(responseText);
		
			if( document.getElementById('messenClose') && removePanel != 'AddNewModule' ) {
			    document.getElementById('messenClose').style.height = 0+"px";
			}
			
			if( document.getElementById('im') && removePanel != 'AddNewModule' )
				document.getElementById( "im" ).style.height = 0+"px" ;
			
			if( location.href.indexOf("donepreview") == -1 ) 
			{
				if(setValAjaxWin > 0){
						setTimeout("callClose()",2000) ;
				}
			}
			else
			{
				if(location.href.indexOf("/event/donepreview") == -1) 
				{
				
					if(setValAjaxWin > 2 && removePanel != 'AddNewModule' ){
							setTimeout("callClose()",2000) ;
					}
				}
				else
				{
					if(setValAjaxWin > 1 ){
					
					setTimeout("callClose()",2000) ;
					}
				}
			}
			
			if( document.getElementById("messenger-wrap").style.display == "none" )
				showAllObjectTags() ;
		}
	}
	
	function callClose() {
		
		if( document.getElementById('messenClose') )
			initFade() ;
	}

	function initFade() {
		if(document.getElementById('messenger-p')) {
			var ht = document.getElementById('messenger-p').offsetLeft ;
		}
		
		document.getElementById('messenClose').style.opacity = "0";
		document.getElementById('messenClose').style.filter = 'alpha(opacity=0)';
		
		if( ht > 0 )
		{
			document.getElementById('messenClose').style.left = ht+"px" ;
			document.getElementById('messenClose').style.display = '' ;
		}
		else
		{
			document.getElementById('messenClose').style.display = 'none' ;
		}
		
		//document.getElementById('messenClose').style.opacity = "0";
		//document.getElementById('messenClose').style.filter = 'alpha(opacity=0)';
		//document.getElementById('messenClose').style.display = 'none' ;
		for (var i=0;i<11;i++) {
			setTimeout('setOpacity('+i+',2,6)',50*i);
		}
		return false;
	}

	function setOpacity(value,val,v)
	{
		document.getElementById('messenClose').style.opacity = value/10;
		document.getElementById('im').style.height = (v+value) + "px";
		document.getElementById('messenClose').style.filter = 'alpha(opacity=' + value*10 + ')';
		document.getElementById('messenClose').style.height = val*value+"px";
		//document.getElementById('messenClose').style.display = '' ;
	}
	
	function sendReqCal(serverFileName) 
	{
		http.open('post', serverFileName);
		http.onreadystatechange = handleResponseCal;
		http.send(null);
	}
	
	/*function sendReqCal(serverFileName) 
	{
		http_response.open('post', serverFileName);
		http_response.onreadystatechange = handleResponseCal_response;
		http_response.send(null);
	}
	
	function handleResponseCal_response() 
	{
		if(http_response.readyState == 4)
		{
			responseText = http_response.responseText;
		
			ajaxProcessCompleteCal_response(responseText);
		}
	}*/

	function handleResponseCal() 
	{
		if(http.readyState == 4)
		{
			responseText = http.responseText;
		
			ajaxProcessCompleteCal(responseText);
		}
	}
	

/*          ajax ends here */

function deleteRecord_a(delId)
{
	var url = location.href ;

	var urlString = url.split( "&delId_a=" ) ;

	var agree = confirm( "Are you sure, you want to delete this record?" ) ;

	if( agree )
	{
		window.location = urlString[0] + "&delId_a=" + delId ;
	}
}

//////////// pagination
function getpage(pageNumber)
{
	var url = location.href ;
	var urlString = url.split( "&page=" ) ;

	var ustring = urlString[0].split( "#" ) ;

	window.location = ustring[0] + "&page=" + pageNumber ;
}

function setRecordsPerPage( rpp )
{
	var url = location.href ;
	var urlString = url.split( "&recordPP=" ) ;

	var ustring = urlString[0].split( "#" ) ;
	var urlstr = ustring[0].split("page=") ;

	window.location = urlstr[0] + "&recordPP=" + rpp ;
}

function showAllRecord()
{
	var url = location.href ;
	var urlString = url.split( "&recordPP=" ) ;

	var ustring = urlString[0].split( "#" ) ;

	window.location = ustring[0] + "&recordPP=showall" ;
}

/////////////// pagination code
/*function pagination( pageNumber )
{
	var url = location.href ;
	var urlString = url.split( "&page=" ) ;

	var ustring = urlString[0].split( "#" ) ;
	var paramArr = ustring[0].split( "?" ) ;
	var parameters = paramArr[1].split( "&" ) ;

	var windowURL = paramArr[0] + "?photoId=1455" + photoId + "&" + parameters[1] + "&page=" + pageNumber + "#pagination" ;
	
	window.location = windowURL ;
}*/

////////////////// Collapse or Expand whole box
function ExpandCollapse( boxId , statusId )
{
	if( document.getElementById( boxId ).style.display == "" )
	{
		document.getElementById( boxId ).style.display = "none" ;
		document.getElementById( statusId ).innerHTML = "Expand All" ;
	}
	else
	{
		document.getElementById( boxId ).style.display = "" ;
		document.getElementById( statusId ).innerHTML = "Collapse All" ;
	}
}

function Validator(frmname)
{
  this.formobj=document.forms[frmname];
	if(!this.formobj)
	{
	  alert("BUG: couldnot get Form object "+frmname);
		return;
	}
	if(this.formobj.onsubmit)
	{
	 this.formobj.old_onsubmit = this.formobj.onsubmit;
	 this.formobj.onsubmit=null;
	}
	else
	{
	 this.formobj.old_onsubmit = null;
	}
	this.formobj.onsubmit=form_submit_handler;
	this.addValidation = add_validation;
	this.setAddnlValidationFunction=set_addnl_vfunction;
	this.clearAllValidations = clear_all_validations;
}
function set_addnl_vfunction(functionname)
{
  this.formobj.addnlvalidation = functionname;
}
function clear_all_validations()
{
	for(var itr=0;itr < this.formobj.elements.length;itr++)
	{
		this.formobj.elements[itr].validationset = null;
	}
}
function form_submit_handler()
{
	for(var itr=0;itr < this.elements.length;itr++)
	{
		if(this.elements[itr].validationset &&
	   !this.elements[itr].validationset.validate())
		{
		  return false;
		}
	}
	if(this.addnlvalidation)
	{
	  str =" var ret = "+this.addnlvalidation+"()";
	  eval(str);
    if(!ret) return ret;
	}
	return true;
}
function add_validation(itemname,descriptor,errstr)
{
  if(!this.formobj)
	{
	  alert("BUG: the form object is not set properly");
		return;
	}//if
	var itemobj = this.formobj[itemname];
  if(!itemobj)
	{
	  alert("BUG: Couldnot get the input object named: "+itemname);
		return;
	}
	if(!itemobj.validationset)
	{
	  itemobj.validationset = new ValidationSet(itemobj);
	}
  itemobj.validationset.add(descriptor,errstr);
}
function ValidationDesc(inputitem,desc,error)
{
  	this.desc=desc;
	this.error=error;
	this.itemobj = inputitem;
	this.validate=vdesc_validate;
}
function vdesc_validate()
{
 if(!V2validateData(this.desc,this.itemobj,this.error))
 {
    if(!this.itemobj.disabled)
	{
		if( this.itemobj )
			if( this.itemobj && this.desc != 'editor' )
				this.itemobj.focus();
	}
	return false;
 }
 return true;
}
function ValidationSet(inputitem)
{
    this.vSet=new Array();
	this.add= add_validationdesc;
	this.validate= vset_validate;
	this.itemobj = inputitem;
}
function add_validationdesc(desc,error)
{
  this.vSet[this.vSet.length]= 
	  new ValidationDesc(this.itemobj,desc,error);
}
function vset_validate()
{
   for(var itr=0;itr<this.vSet.length;itr++)
	 {
	   if(!this.vSet[itr].validate())
		 {
		   return false;
		 }
	 }
	 return true;
}
function validateEmailv2(email)
{
// a very simple email validation checking. 
// you can add more complex email checking if it helps 
    if(email.length <= 0)
	{
	  return true;
	}
    var splitted = email.match("^(.+)@(.+)$");
    if(splitted == null) return false;
    if(splitted[1] != null )
    {
      var regexp_user=/^\"?[\w-_\.]*\"?$/;
      if(splitted[1].match(regexp_user) == null) return false;
    }
    if(splitted[2] != null)
    {
      var regexp_domain=/^[\w-\.]*\.[A-Za-z]{2,4}$/;
      if(splitted[2].match(regexp_domain) == null) 
      {
	    var regexp_ip =/^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
	    if(splitted[2].match(regexp_ip) == null) return false;
      }// if
      return true;
    }
return false;
}

function trim(inputString) {
	
   // Removes leading and trailing spaces from the passed string. Also removes
   // consecutive spaces and replaces it with one space. If something besides
   // a string is passed in (null, custom object, etc.) then return the input.
   if (typeof inputString != "string") { return inputString; }
   var retValue = inputString;
   var ch = retValue.substring(0, 1);
   while (ch == " ") { // Check for spaces at the beginning of the string
      retValue = retValue.substring(1, retValue.length);
      ch = retValue.substring(0, 1);
   }
   ch = retValue.substring(retValue.length-1, retValue.length);
	while (ch == " ") { // Check for spaces at the end of the string
      retValue = retValue.substring(0, retValue.length-1);
      ch = retValue.substring(retValue.length-1, retValue.length);
   }
   while (retValue.indexOf("  ") != -1) { // Note that there are two spaces in the string - look for multiple spaces within the string
      retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length); // Again, there are two spaces in each of the strings
   }
   return retValue; // Return the trimmed string back to the user
} // Ends the "trim" function

function V2validateData(strValidateStr,objValue,strError) 
{ 
    var epos = strValidateStr.search("="); 
    var  command  = ""; 
    var  cmdvalue = "6"; 
    if(epos >= 0) 
    { 
     command  = strValidateStr.substring(0,epos); 
     cmdvalue = strValidateStr.substr(epos+1); 
    } 
    else 
    { 
     command = strValidateStr; 
    } 
    switch(command) 
    { 
        case "req": 
        case "required": 
         { 
           if(eval(trim(objValue.value.length)) == 0 || trim(objValue.value) == "") 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : Required Field"; 
              }//if 
              alert(strError); 
              return false; 
           }//if 
           break;             
         }//case required 
        case "maxlength": 
        case "maxlen": 
          { 
             if(eval(objValue.value.length) >  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : "+cmdvalue+" characters maximum "; 
               }//if 
               //alert(strError + "\n[Current length = " + objValue.value.length + " ]"); 
				alert(strError); 
               return false; 
             }//if 
             break; 
          }//case maxlen 
        case "minlength": 
        case "minlen": 
           { 
             if(eval(objValue.value.length) <  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : " + cmdvalue + " characters minimum  "; 
               }//if               
               //alert(strError + "\n[Current length = " + objValue.value.length + " ]"); 
			   alert(strError); 
               return false;                 
             }//if 
             break; 
            }//case minlen 
        case "alnum": 
        case "alphanumeric": 
           { 
              var charpos = objValue.value.search("[^A-Za-z0-9,.$& ]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
               if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name +": Only alpha-numeric characters allowed "; 
                }//if 
               // alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
				alert(strError); 
                return false; 
              }//if 
              break; 
           }//case alphanumeric 
		case "specialchar": 
           { 
              var charpos = objValue.value.search("[^A-Za-z0-9\-_?.]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
               if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name +": Only alpha-numeric characters allowed "; 
                }//if 
               // alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
				alert(strError); 
                return false; 
              }//if 
              break; 
           }//case alphanumeric 
        case "num": 
        case "numeric": 
           { 
              var charpos = objValue.value.search("[^0-9]");
			  var indexVal = objValue.value ;
			  
			  if(objValue.value == 0) {
				  alert(strError) ;
				  return false ;
			  }
              if( objValue.value.length > 0 &&  charpos >= 0 ) 
              { 
				if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name +": Only digits allowed "; 
                }//if               
               // alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
				//alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
				alert(strError); 
                return false; 
              }//if 
              break;               
           }//numeric 
        case "float": 
           { 
              var charpos = objValue.value.search("[^0-9.]"); 
			  
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name +": Only digits allowed "; 
                }//if               
               // alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
				//alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
				alert(strError);
                return false; 
              }//if 
              break;               
           }//numeric 
		 
        case "alphabetic": 
        case "alpha": 
           { 
              var charpos = objValue.value.search("[^A-Za-z. ]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only alphabetic characters allowed "; 
                }//if                             
               // alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
			    alert(strError); 
                return false; 
              }//if 
              break; 
           }//alpha 
		case "alnumhyphen":
			{
              var charpos = objValue.value.search("[^A-Za-z0-9\-_]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are A-Z,a-z,0-9,- and _"; 
                }//if                             
                //alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
				 alert(strError); 
                return false; 
              }//if 			
			break;
			}
			case "alhyphen":
			{
              var charpos = objValue.value.search("[^A-Za-z\,-_ ]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are A-Z,a-z,- and _"; 
                }//if                             
                //alert(strError + "\n [Error character position " + eval(charpos+1)+"]");
				alert(strError);
                return false; 
              }//if 			
			break;
			}
			case "numhyphen":
			{
              var charpos = objValue.value.search("[^0-9\-]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are 0-9 and -"; 
                }//if                             
                //alert(strError + "\n [Error character position " + eval(charpos+1)+"]");
				alert(strError);
                return false; 
              }//if 			
			break;
			}
      
	   case "email": 
          { 
              
			   if(!validateEmailv2(objValue.value)) 
               { 
                 if(!strError || strError.length ==0) 
                 { 
                    strError = objValue.name+": Enter a valid Email address "; 
                 }//if                                               
                 alert(strError); 
                 return false; 
               }//if 
           break; 
          }//case email 
        case "lt": 
        case "lessthan": 
         { 
            if(isNaN(objValue.value)) 
            { 
              alert(objValue.name+": Should be a number "); 
              return false; 
            }//if 
            if(eval(objValue.value) >=  eval(cmdvalue)) 
            { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : value should be less than "+ cmdvalue; 
              }//if               
              alert(strError); 
              return false;                 
             }//if             
            break; 
         }//case lessthan 
        case "gt": 
        case "greaterthan": 
         { 
            if(isNaN(objValue.value)) 
            { 
              alert(objValue.name+": Should be a number "); 
              return false; 
            }//if 
             if(eval(objValue.value) <=  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : value should be greater than "+ cmdvalue; 
               }//if               
               alert(strError);
               return false;                 
             }//if             
            break; 
         }//case greaterthan 
        case "regexp": 
         { 
		 	if(objValue.value.length > 0)
			{
	            if(!objValue.value.match(cmdvalue)) 
	            { 
	              if(!strError || strError.length ==0) 
	              { 
	                strError = objValue.name+": Invalid characters found "; 
	              }//if                                                               
	              alert(strError); 
	              return false;                   
	            }//if 
			}
           break; 
         }//case regexp 
        case "dontselect": 
         { 
            if(objValue.selectedIndex == null) 
            { 
              alert("BUG: dontselect command for non-select Item"); 
              return false; 
            } 
            if(objValue.selectedIndex == eval(cmdvalue)) 
            { 
             if(!strError || strError.length ==0) 
              { 
              strError = objValue.name+": Please Select one option "; 
              }//if                                                               
              alert(strError); 
              return false;                                   
             } 
             break; 
         }//case dontselect 
		case "area": 
         { 
           if(eval(objValue.value.length) == 0) 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : Required Field"; 
              }//if 
              alert(strError); 
              return false; 
           }//if 
           break;             
         }//case area 
		 
        case "lteqal": 
         { 
            if(isNaN(objValue.value)) 
            { 
              alert(objValue.name+": Should be a number "); 
              return false; 
            }//if 
            if(eval(objValue.value) >  eval(cmdvalue)) 
            { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : value should be less than "+ cmdvalue; 
              }//if               
              alert(strError); 
              return false;                 
             }//if             
            break; 
         }//case lessthan 
		 
	case "alnumhypdotqueund":
			{
              var charpos = objValue.value.search("[^A-Za-z0-9\-_?.]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are A-Z,a-z,0-9,-,_,?,."; 
                }//if                             
                //alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
				 alert(strError); 
                return false; 
              }//if 			
			break;
			}
			
			
	case "longlat":
			{
              var charpos = objValue.value.search("[^A-Za-z0-9.:]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are A-Z,a-z,0-9,.,:"; 
                }//if                             
                //alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
				 alert(strError); 
                return false; 
              }//if 			
			break;
			}	
	 
	 case "checked":
			{
              if( objValue.checked == false )
				{
				   if(!strError || strError.length ==0) 
                    {
					  strError = "Please select "+objValue.name+"."  ;
					}
				   
				   alert(strError) ;
				   return false ;
				}
			break;
			}
	   case "editor":
		    {
				var EditorInstance = FCKeditorAPI.GetInstance(objValue.name) ; 
				objValue.value = EditorInstance.GetXHTML(true) ;

				if( objValue.value == false )
				{
				   if(!strError || strError.length ==0) 
                    {
					  strError = "Please select "+objValue.name+"."  ;
					}
				   
				   alert(strError) ;
				   return false ;
				}
			break;
		    }
		 case "checkMailing":
			{
			    if( document.registration.chkmailingaddress.checked == true )
				{
					 if(eval(trim(objValue.value.length)) == 0 || trim(objValue.value) == "") 
					   { 
						  if(!strError || strError.length ==0) 
						  { 
							strError = objValue.name + " : Required Field"; 
						  }//if 
						  alert(strError); 
						  return false; 
					   }//if 
					   break; 
				}
			}
		case "checkSamevalue":
			{
				if( document.getElementById("password").value != trim(objValue.value) )
				{
						  if(!strError || strError.length ==0) 
						  { 
							strError = objValue.name + " : Required Field"; 
						  }//if 
						  alert(strError); 
						  return false; 
					   break; 
				}
			}
		/*case "chkState": 
         { 
			  if(objValue.value != "") {
				  var charpos = objValue.value.search("[^A-Za-z]"); 
				  if(objValue.value.length > 0 &&  charpos >= 0) 
				  { 
					alert(strError); 
					return false; 
				  }//if 
				  break; 
			  }
         }//case required  
		case "chkTelephone": 
         { 
			  if(objValue.value != "") {
				  var charpos = objValue.value.search("[^0-9]"); 
				  if(objValue.value.length > 0 &&  charpos >= 0) 
				  { 
					alert(strError); 
					return false; 
				  }//if 
				  break; 
			  }
         }//case required*/
    }//switch 
	
    return true; 
}
// JavaScript Document