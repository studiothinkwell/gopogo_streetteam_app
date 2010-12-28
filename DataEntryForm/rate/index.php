<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
<meta name="viewport" content="width=device-width" />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />

<?php
    
	session_start() ;
	$varn = session_id() ;
   
	require_once( "commonclass.php" ) ;
	///////////// creating object for the commonclass
	$classObj = new commonClass( '' , 'Home Page' ) ;
?>
<script src="step5.php_files/ga.js" type="text/javascript"></script>
<script>
function createInfoMarker(point, address) {
	var marker = new GMarker(point);
	GEvent.addListener(marker, "click",
	  function() {
		 marker.openInfoWindowHtml(address);
	  }
	);
	return marker;
}

function selectAllCheckbox(frmObj, chk_state) { 
	totalElem = document.myForm.length;
	for (i=0;i<totalElem;i++) {
		var e = frmObj.elements[i];
		type = document.myForm.elements[i].type;
		if ( (e.name == 'txtrange[]') && (type == "checkbox" ) ) {
			document.myForm.elements[i].checked = chk_state;
		}
	}
}

function uncheckOne(id)
{
	document.getElementById(id).checked = false ;
	/*var set = 1 ;
	alert(set) ;
	totalElem = document.myForm.length;
	for (i=0;i<totalElem;i++) {
		var e = frmObj.elements[i];
		type = document.myForm.elements[i].type;
		if ( (e.name == 'txtrange[]') && (type == "checkbox" ) ) {
			if(document.myForm.elements[i].checked == true)
				set = 1 ;
			else
			{
				set = 0 ;
				break ;
			}
		}
	}
	
	alert(set) ;*/
}

function selectAllCheckboxBestTimeToGo(frmObj, chk_state)
{ //alert( 'in selectAllCheckbox');
	totalElem = document.myForm.length; // txtrange
	//alert('totalElem= '+ totalElem);
	
	for (i=0;i<totalElem;i++)
	{
		var e = frmObj.elements[i];
	  // alert(e.name);
	
		type = document.myForm.elements[i].type;
		//alert("type=" + type);
		if ( (e.name == 'txtbtg[]') && (type == "checkbox" ) ) //
		//if ( type == "checkbox" )
		{
			document.myForm.elements[i].checked = chk_state;
		}
	}
}

function valid(f) {
!(/^[A-z��0-9]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z��0-9]/ig,' '):null;
}
function validtxtnbh(f) {
!(/^[A-z��0-9]*$/i).test(f.value)?f.value = f.value.replace(/[^A-z��0-9]/ig,' '):null;
}
function check_lengthtxtnbh(my_form)
{
	
	//txtnbh
maxLen = 20; // max number of characters allowed
if (my_form.txtnbh.value.length >= maxLen) {
// Alert message if maximum limit is reached.
// If required Alert can be removed.
var msg = "You have reached your maximum limit of characters allowed(which is 20)!";
//alert(msg);
// Reached the Maximum length so trim the textarea
    my_form.txtnbh.value = my_form.txtnbh.value.substring(0, maxLen);
}
else{ // Maximum length not reached so update the value of my_text counter
   // my_form.txtnbh.value = maxLen - my_form.txtbd.value.length;
}
}
function check_lengthtxtname(my_form)
{
	
	//txtname
maxLen = 20; // max number of characters allowed
if (my_form.txtbname.value.length >= maxLen) {
// Alert message if maximum limit is reached.
// If required Alert can be removed.
var msg = "You have reached your maximum limit of characters allowed(which is 20)!";
//alert(msg);
// Reached the Maximum length so trim the textarea
    my_form.txtbname.value = my_form.txtbname.value.substring(0, maxLen);
}
else{ // Maximum length not reached so update the value of my_text counter
   // my_form.text_num.value = maxLen - my_form.txtbd.value.length;
}
}
</script>
<!--
<script src="step5.php_files/mod_api_gc.js" charset="UTF-8" type="text/javascript"></script>
<script src="step5.php_files/maps" type="text/javascript"></script>
<script src="step5.php_files/main.js" type="text/javascript"></script>
<script language="Javascript" src="step5.php_files/findgoogleaddress.js"></script>-->
<title>Review Submission</title>
<link href="viewform_files/4176578971-published_form_compiled.css" type="text/css" rel="stylesheet">
<link href="viewform_files/4176578971-published_form_compiled.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="js/form-submit.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
<!--<script type="text/javascript"
            src="http://www.google.com/jsapi?key=ABQIAAAAlJFc1lrstqhgTl3ZYo38bBQcfCcww1WgMTxEFsdaTsnOXOVOUhTplLhHcmgnaY0u87hQyd-n-kiOqQ"></script>
<script type="text/javascript">
  google.load("maps", "2");
  google.load("search", "1");

  // Call this function when the page has been loaded
  function initialize() {
	var map = new google.maps.Map2(document.getElementById("map"));
	map.setCenter(new google.maps.LatLng(37.4419, -122.1419), 13);

	var searchControl = new google.search.SearchControl();
	searchControl.addSearcher(new google.search.WebSearch());
	//searchControl.addSearcher(new google.search.NewsSearch());
	searchControl.draw(document.getElementById("searchcontrol"));
  }
  google.setOnLoadCallback(initialize);
</script>-->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>-->
<script src="json.js"></script>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script language="Javascript" type="text/javascript">
    //<![CDATA[
    google.load('search', '1');
	var clips = new Array();
	var setFlag = 0 ;
	var count = 0 ;
    
    function OnLoad() {
		
      // Create a search control
      var searchControl = new google.search.SearchControl();

      // Add in a full set of searchers
      var localSearch = new google.search.LocalSearch();
      /*searchControl.addSearcher(localSearch);*/
	  
		options = new google.search.SearcherOptions();
		options.setExpandMode(google.search.SearchControl.EXPAND_MODE_OPEN);
		//searchControl.addSearcher(new google.search.LocalSearch(), options);
	  searchControl.addSearcher(localSearch, options);

      /*searchControl.addSearcher(new google.search.WebSearch());
      searchControl.addSearcher(new google.search.VideoSearch());
      searchControl.addSearcher(new google.search.BlogSearch());
      searchControl.addSearcher(new google.search.NewsSearch());
      searchControl.addSearcher(new google.search.ImageSearch());
      searchControl.addSearcher(new google.search.BookSearch());
      searchControl.addSearcher(new google.search.PatentSearch());*/
	  
	  // establish a keep callback
	  searchControl.setOnKeepCallback(this, MyKeepHandler , "<input type='button' value='Select'>");
	  
	   searchControl.setSearchCompleteCallback(this, OnSearchComplete);

      // Set the Local Search center point
      localSearch.setCenterPoint("Los Angeles,CA");
	 


		//searchControl.clearAllResults();
      // tell the searcher to draw itself and tell it where to attach
      searchControl.draw(document.getElementById("searchcontrol")) ;
	   
      // execute an inital search
      searchControl.execute();
    }
   // google.setOnLoadCallback(OnLoad);
	google.setOnLoadCallback(OnLoad, true);

	function OnSearchComplete(result) {
		 
		 setFlag = 1 ;
		 
		 count = -1;
		 searchcontrol1 = document.getElementById("searchcontrol").innerHTML;
		 count = searchcontrol1.indexOf("(0)");
		 if (count != -1) {
			//console.log("inside here");
			document.getElementById('bn').style.display = '';
			setFlag = 0 ;
		 } else {
			document.getElementById('parentId').style.display = "none"; 
			document.getElementById('bn').style.display = "none";
		 }
	}

	function MyKeepHandler(result) 
	{

		// clone the result html node
		var node = result.html.cloneNode(true);
		// attach it
		
		var savedResults = document.getElementById("saved_results");
		
		savedResults.appendChild(node);
		clips.push(result);
		
		//document.getElementById("post-button").disabled=false; 
		//document.getElementById("post-button").style.display='';
		
		var text = document.getElementById('saved_results').innerHTML ;
	    runAjax(text) ;
	  
	}
	
	
	/*function postResults() 
	{ 
	  var text = document.getElementById('saved_results').innerHTML ;
	  runAjax(text) ;
	  return false ;
	}*/
	
	var request;
	function runAjax(text)
	{
		// function returns "AJAX" object, depending on web browser
		// this is not native JS function!
		//request = getHTTPObject();
		
		request = new XMLHttpRequest() ;
		request.onreadystatechange = sendData ;
		request.open("POST", "process.php?sessid=<?=$varn?>&data="+encodeURIComponent(text) , true) ;
		request.send(null) ;
	}
	
	// function is executed when var request state changes
	function sendData()
	{
		// if request object received response
		if(request.readyState == 4)
		{
			var returnedData = request.responseText ;
			// parser.php response
			document.getElementById('resulthide').style.display = 'none' ;
			document.getElementById('parentId').style.display = '' ;
			document.getElementById('saved_results').innerHTML = "" ;
			document.getElementById('resultshow').innerHTML = "" ;
			document.getElementById('resultshow').innerHTML = returnedData ;
		}
	}
	
	function resetDom()
	{
		document.getElementById('saved_results').style.display = 'none' ;
		document.getElementById('resulthide').style.display = '' ;
		document.getElementById('parentId').style.display = 'none' ;
	}
	
    //]]>
</script>

<script type="text/javascript" src="js/jquery-latest.js" language="javascript"></script>
<script type="text/javascript" language="javascript">
	$( function() {
		$( '.checkAll' ).live( 'change', function() {
			$( '.cb-element' ).attr( 'checked', $( this ).is( ':checked' ) ? 'checked' : '' );
			$( this ).next().text( $( this ).is( ':checked' ) ? 'Uncheck All' : 'Check All' );
		});
		$( '.invertSelection' ).live( 'click', function() {
			$( '.cb-element' ).each( function() {
				$( this ).attr( 'checked', $( this ).is( ':checked' ) ? '' : 'checked' );
			}).trigger( 'change' );

		});
		$( '.cb-element' ).live( 'change', function() {
			$( '.cb-element' ).length == $( '.cb-element:checked' ).length ? $( '.checkAll' ).attr( 'checked', 'checked' ).next().text( 'Uncheck All' ) : $( '.checkAll' ).attr( 'checked', '' ).next().text( 'Check All' );

		});
	});
</script>

<script type="text/javascript" language="javascript">
	$( function() {
		$( '.checkAll_1' ).live( 'change', function() {
			$( '.cb-element_1' ).attr( 'checked', $( this ).is( ':checked' ) ? 'checked' : '' );
			$( this ).next().text( $( this ).is( ':checked' ) ? 'Uncheck All' : 'Check All' );
		});
		$( '.invertSelection' ).live( 'click', function() {
			$( '.cb-element_1' ).each( function() {
				$( this ).attr( 'checked', $( this ).is( ':checked' ) ? '' : 'checked' );
			}).trigger( 'change' );

		});
		$( '.cb-element_1' ).live( 'change', function() {
			$( '.cb-element_1' ).length == $( '.cb-element_1:checked' ).length ? $( '.checkAll_1' ).attr( 'checked', 'checked' ).next().text( 'Uncheck All' ) : $( '.checkAll_1' ).attr( 'checked', '' ).next().text( 'Check All' );

		});
	});
</script>


<style type="text/css">
body {
	margin:0px;
	font-size:0.7em;
	font-family:Trebuchet, Arial;
}
#mainContainer {
	width:280px;
	margin:0px;
	border:0px solid red;
}
table, tr, td {
	vertical-align:top;
}
.textInput {
	width:300px;
}
html {
	margin:0px;
}
.formButton {
	width:75px;
}
textarea, select {
	font-family:Trebuchet MS;
}
i {
	font-size:0.9em;
}
li {
	list-style:none;
}
.gsc-branding-text {
	display:none;
}
.gsc-branding-img {
	display:none;
}
.gsc-input {
	margin:0;
	height:21px;
	width:100px;
	border:0px solid red;
}
.loginboxdiv {
	margin:0;
	height:21px;
	width:146px;
	background:url(images/login_bg.gif) no-repeat bottom;
}
.loginbox {
	background:none;
	border:none;
	width:134px;
	height:15px;
	margin:0;
	padding: 0px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:11px;
}
input.gsc-input {
	/*border:0px solid #BCCDF0;
	padding-left:2px;*/
	width:80%;
	/*background:url(images/login_bg.gif) no-repeat bottom;
	background-repeat:no-repeat;
	margin:0;
	padding: 0px;*/
}
.gsc-clear-button
{
	display:none;	
}
.gsc-title
{
	display:none !important;
}
.gsc-configLabelCell
{
	display:none !important;
}
.gs-directions
{
	display:none !important;
}
.gsc-results-selector
{
	display:none !important;
}

.gsc-twiddle div
{
	display:none;
}


.checkbox, .radio {
	width: 19px;
	height: 25px;
	padding: 0 5px 0 0;
	background: url(checkbox.gif) no-repeat;
	display: block;
	clear: left;
	float: left;
}

body {
	background: #FFF;
}

#navigation {
	/*background: #AFD5E0 url("bg-nav.gif") repeat-x;*/
	border-bottom: 2px solid #979797;
	border-width: 1px 0;
	font-size: 1.1em;
	margin-top: 1em;
	padding-top: .6em;
}

#navigation ul, #navigation ul li {
	list-style: none;
	margin: 0;
	padding: 0;
}

#navigation ul {
	padding: 5px 0;
	text-align: left;
}

#navigation ul li {
	display: inline;
	margin-right: .75em;
}

#navigation ul li.last {
	margin-right: 0;
}

#navigation ul li a {
	background: url("tab-right.gif") no-repeat 100% 0;
	height:28px;
	color: #06C;
	padding: 5px 0;
	text-decoration: none;
}

#navigation ul li a span {
	background: url("tab-left.gif") no-repeat;
	padding: 5px 1em;
}

#navigation ul li a:hover span {
	color: #69C;
	text-decoration: underline;
}

</style>


<?php
	if( ereg("rate" , $_SERVER['PHP_SELF'] ) )
	{
		echo "
		       <style type='text/css'>
			   	  .tab { color:blue;font-weight:bold; }
				  .tab2 { color:black;}
			   </style>
			 " ;	
	}
?>

</head>
<body>
<div id="mainContainer" style="margin:0px;">

<form action="someplace.html" method="post" name="myForm">
  <div id="formResponse">
  
  <div id="navigation">
	<ul>
		<li><a href="/rate"><span class="tab">Review a place</span></a></li>
		<li><a href="/playlist"><span class="tab2">Create a playlist</span></a></li>
	</ul>
</div>

<div style="height:20px;"> </div>

<div>
<h2>Review A Place</h2>
</div>
  
  <!--<div class="errorbox-good">
    <div class="ss-item ss-item-required ss-text">
      <div class="ss-form-entry">
        <label class="ss-q-title" for="entry_4">Your Name <span class="ss-required-asterisk">*</span></label>
        <label class="ss-q-help" for="entry_4"></label>
        <input name="txtname" class="ss-q-short" id="entry_4" type="text">
      </div>
    </div>
  </div>
  <br>-->
  <div id="parentId" style="display:none;">
    <div style="width:100px; height:20px; background-color:#999; color:#000; text-align:center; padding-top:10px;padding-bottom:10px;" onClick="resetDom();" class="gsc-clear-button" >Edit Business&nbsp;&nbsp;</div>
    <div style="height:20px;"> </div>
    <div id="resultshow"> </div>
  </div>
  <div id="resulthide">
    <div class="errorbox-good">
      <div class="ss-item ss-item-required ss-text">
        <table border="0" cellpadding="2" cellspacing="2">
          <tr>
            <td><strong>What business are you reviewing?</strong></td>
          </tr>
          <tr>
            <td><div id="map" style="width: 200px; height: 200px; display:none;"></div>
              <div id="searchcontrol">Loading</div></td>
            <td><div id="saved_results" style="padding-top:40px;" ></div>
              <input id="post-button" type="button" value="Post Results to Server" onclick="postResults()" style="position:absolute;display:none;"  /></td>
          </tr>
        </table>
        <!--<div id="searchcontrol"></div>-->
      </div>
    </div>
    
    <div id="bn" style="display:none;">
        <div style="height:50px;"></div>
        <div ><span><strong>Business Name</strong> <span class="ss-required-asterisk">*</span> </div>
        <div class="">
          <label class="ss-q-help" for="entry_0"></label>
          <input name="txtbname" class="" id="entry_0" type="text" onKeyPress="check_lengthtxtname(this.form);" onKeyDown="check_lengthtxtname(this.form);" onkeyup="valid(this)" onblur="valid(this)">
        </div>
        
        <br>
        <div>
          <table align="left" border="0" cellpadding="2" cellspacing="2" width="100%">
            <tbody>
              <tr>
                <td><span class="ss-q-title">Address Line 1 <span class="ss-required-asterisk">*</span></span></td>
              </tr>
              <tr>
                <td><div class="">
                    <input name="txtadd1" size=42 class="" type="text" id='eventlocation'>
                  </div></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>      
  </div>
  <br>
  <div class="errorbox-good">
    <div class="ss-item ss-item-required ss-text">
      <div> <strong>Your Name</strong> <span class="ss-required-asterisk">*</span> </div>
      <div class="">
        <label class="ss-q-help" for="entry_6"></label>
        <input name="txtnbh" class="" id="entry_6" type="text" onKeyPress="check_lengthtxtnbh(this.form);" onKeyDown="check_lengthtxtnbh(this.form);" onkeyup="validtxtnbh(this)" onblur="validtxtnbh(this)">
      </div>
    </div>
  </div>
  <br>
  <!--<div class="errorbox-good">
    <div class="ss-item ss-item-required ss-paragraph-text">
      <div class="ss-form-entry">
        <label class="ss-q-title" for="entry_3">Business Description <span class="ss-required-asterisk">*</span></label>
        <label class="ss-q-help" for="entry_3">How would you describe this business to your friends in a sentence or two?</label>
        <textarea name="txtbd" rows="8" cols="75" class="ss-q-long" id="entry_3"></textarea>
      </div>
    </div>
  </div>-->
  <div class="errorbox-good">
    <div class="ss-item ss-item-required ss-checkbox">
      <div class="ss-form-entry">
      <div class="ss-form-entry">
        <label class="ss-q-title" for="entry_18">Did you like this place? <span class="ss-required-asterisk">*</span></label>
        <label class="ss-q-help" for="entry_18"></label>
        <ul class="ss-choices">
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtld[]" value="Liked" class="ss-q-checkbox" id="group_18_1" type="radio" >
              Liked it</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtld[]" value="Disliked" class="ss-q-checkbox" id="group_18_2" type="radio">
             Disliked it</label>
          </li>
        </ul>
      <br>
      </label>
        <label class="ss-q-title" for="entry_2">How would you describe the "mood" ?<span class="ss-required-asterisk">*</span></label>
        <label class="ss-q-help" for="entry_2"></label>
        <ul class="ss-choices">
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtmood[]" value="Romance" class="ss-q-checkbox" id="group_2_1" type="checkbox" >
              Romance</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtmood[]" value="Adventure" class="ss-q-checkbox" id="group_2_2" type="checkbox">
              Adventure</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtmood[]" value="Laughs" class="ss-q-checkbox" id="group_2_3" type="checkbox">
              Laughs</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtmood[]" value="Nightlife" class="ss-q-checkbox" id="group_2_4" type="checkbox">
              Nightlife</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtmood[]" value="Just Chillin" class="ss-q-checkbox" id="group_2_5" type="checkbox">
              Just Chillin</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtmood[]" value="Family Fun" class="ss-q-checkbox" id="group_2_6" type="checkbox">
              Family Fun</label>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="errorbox-good">
    <div class="ss-item ss-item-required ss-checkbox">
      <div class="ss-form-entry">
        <label class="ss-q-title" for="entry_18">Crowd Type <span class="ss-required-asterisk">*</span></label>
        <label class="ss-q-help" for="entry_18"></label>
        <ul class="ss-choices">
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtcrowd[]" value="Touristy" class="ss-q-checkbox" id="group_18_1" type="checkbox" >
              Touristy</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtcrowd[]" value="Local" class="ss-q-checkbox" id="group_18_2" type="checkbox">
              Local</label>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="errorbox-good">
    <div class="ss-item ss-item-required ss-select">
      <div class="ss-form-entry">
        <label class="ss-q-title" for="entry_8">Dress Code <span class="ss-required-asterisk">*</span></label>
        <label class="ss-q-help" for="entry_8"></label>
        <select name="txtdc" id="entry_8">
          <option selected="selected"  value="">Choose</option>
          <option value="Casual">Casual</option>
          <option value="Business">Business</option>
          <option value="Business Casual">Business Casual</option>
          <option value="Upscale">Upscale</option>
          <option value="Night Out">Night Out</option>
        </select>
      </div>
    </div>
  </div>
  <div class="errorbox-good">
    <div class="ss-item ss-item-required ss-checkbox">
      <div class="ss-form-entry">
        <label class="ss-q-title" for="entry_10">Age Range <span class="ss-required-asterisk">*</span></label>
        <label class="ss-q-help" for="entry_10"></label>
        <ul class="ss-choices">
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input class="checkAll" name="trange" value="All Ages" id="group_10_1" type="checkbox" >
              All Ages</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input class="cb-element" name="txtrange[]" value="Kid Friendly" id="group_10_2" type="checkbox" >
              Kid Friendly</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input class="cb-element" name="txtrange[]" value="21 &amp; Over" id="group_10_3" type="checkbox" >
              21 &amp; Over</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input class="cb-element" name="txtrange[]" value="Golden Years" id="group_10_4" type="checkbox" >
              Golden Years</label>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="errorbox-good">
    <div class="ss-item ss-item-required ss-checkbox">
      <div class="ss-form-entry">
        <label class="ss-q-title" for="entry_19">Good for? <span class="ss-required-asterisk">*</span></label>
        <label class="ss-q-help" for="entry_19"></label>
        <ul class="ss-choices">
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtgf[]" value="Singles" class="ss-q-checkbox" id="group_19_1" type="checkbox">
              Singles</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtgf[]" value="Couples" class="ss-q-checkbox" id="group_19_2" type="checkbox">
              Couples</label>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="errorbox-good">
    <div class="ss-item ss-item-required ss-checkbox">
      <div class="ss-form-entry">
        <label class="ss-q-title" for="entry_24">Best When? <span class="ss-required-asterisk">*</span></label>
        <label class="ss-q-help" for="entry_24"></label>
        <ul class="ss-choices">
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtbw[]" value="Drunk" class="ss-q-checkbox" id="group_24_1" type="checkbox" >
              Drunk</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtbw[]" value="Sober" class="ss-q-checkbox" id="group_24_2" type="checkbox">
              Sober</label>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="ss-item ss-item-required ss-grid">
    <div class="ss-form-entry">
      <!-- <label class="ss-q-title" for="entry_20">Rate Your Experience <span class="ss-required-asterisk">*</span></label>
      <label class="ss-q-help" for="entry_20"></label>
      <div class="errorbox-component errorbox-good">
        <div class="errorbox-good"></div>
        <div class="errorbox-good"></div>
        <div class="errorbox-good"></div>
        <table border="0" cellpadding="5" cellspacing="0">
          <thead>
            <tr>
              <td class="ss-gridnumbers" style="width: 24%;"></td>
              <td class="ss-gridnumbers" style="width: 6%;"></td>
              <td class="ss-gridnumbers" style="width: 12%;"><label class="ss-gridnumber">1 - Worst</label></td>
              <td class="ss-gridnumbers" style="width: 12%;"><label class="ss-gridnumber">2</label></td>
              <td class="ss-gridnumbers" style="width: 12%;"><label class="ss-gridnumber">3</label></td>
              <td class="ss-gridnumbers" style="width: 12%;"><label class="ss-gridnumber">4</label></td>
              <td class="ss-gridnumbers" style="width: 12%;"><label class="ss-gridnumber">5 - Best</label></td>
              <td class="ss-gridnumbers" style="width: 6%;"></td>
            </tr>
          </thead>
          <tbody>
            <tr class="ss-gridrow ss-grid-row-odd">
              <td class="ss-gridrow ss-leftlabel ss-gridrow-leftlabel" style="width: 24%;">Style</td>
              <td class="ss-gridrow" style="width: 6%;"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtstyle" value="1" class="ss-q-radio" id="group_21_1" type="radio"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtstyle" value="2" class="ss-q-radio" id="group_21_2" type="radio"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtstyle" value="3" class="ss-q-radio" id="group_21_3" type="radio"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtstyle" value="4" class="ss-q-radio" id="group_21_4" type="radio"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtstyle" value="5" class="ss-q-radio" id="group_21_5" type="radio" ></td>
              <td class="ss-gridrow" style="width: 6%;"></td>
            </tr>
            <tr class="ss-gridrow ss-grid-row-even">
              <td class="ss-gridrow ss-leftlabel ss-gridrow-leftlabel" style="width: 24%;">Service</td>
              <td class="ss-gridrow" style="width: 6%;"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtservice" value="1" class="ss-q-radio" id="group_22_1" type="radio"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtservice" value="2" class="ss-q-radio" id="group_22_2" type="radio"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtservice" value="3" class="ss-q-radio" id="group_22_3" type="radio"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtservice" value="4" class="ss-q-radio" id="group_22_4" type="radio"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtservice" value="5" class="ss-q-radio" id="group_22_5" type="radio" ></td>
              <td class="ss-gridrow" style="width: 6%;"></td>
            </tr>
            <tr class="ss-gridrow ss-grid-row-odd">
              <td class="ss-gridrow ss-leftlabel ss-gridrow-leftlabel" style="width: 24%;">Quality</td>
              <td class="ss-gridrow" style="width: 6%;"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtqty" value="1" class="ss-q-radio" id="group_23_1" type="radio"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtqty" value="2" class="ss-q-radio" id="group_23_2" type="radio"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtqty" value="3" class="ss-q-radio" id="group_23_3" type="radio"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtqty" value="4" class="ss-q-radio" id="group_23_4" type="radio"></td>
              <td class="ss-gridrow" style="width: 12%;"><input name="txtqty" value="5" class="ss-q-radio" id="group_23_5" type="radio" ></td>
              <td class="ss-gridrow" style="width: 6%;"></td>
            </tr>
          </tbody>
        </table> -->
      </div>
    </div>
  </div>
   <div class="errorbox-good">
    <div class="ss-item ss-item-required ss-checkbox">
      <div class="ss-form-entry">
        <label class="ss-q-title" for="entry_11">Best time to go? <span class="ss-required-asterisk">*</span></label>
        <label class="ss-q-help" for="entry_11"></label>
        <ul class="ss-choices">
          <li class="ss-choice-item">
            <label class="ss-choice-label">
<input name="group_10_" value="" class="checkAll_1" id="group_10_5" type="checkbox" onClick="selectAllCheckboxBestTimeToGo(this.form,this.checked);">
Anyime</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtbtg[]" value="Morning" class="cb-element_1" id="group_10_2" type="checkbox" onclick="uncheckOne('group_10_5');">
              Morning</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtbtg[]" value="Day" class="cb-element_1" id="group_10_3" type="checkbox" onclick="uncheckOne('group_10_5');">
              Day</label>
          </li>
          <li class="ss-choice-item">
            <label class="ss-choice-label">
              <input name="txtbtg[]" value="Night" class="cb-element_1" id="group_10_4" type="checkbox">
              Night</label>
          </li>
        </ul>
      </div>
    </div>
  </div> <!--
  <div class="errorbox-good">
    <div class="ss-item ss-item-required ss-select">
      <div class="ss-form-entry">
        <label class="ss-q-title" for="entry_11">Best time to go? <span class="ss-required-asterisk">*</span></label>
        <label class="ss-q-help" for="entry_11"></label>
        <select name="txtbtg" id="entry_11">
          <option selected="selected" value="">Choose</option>	
          <option value="Morning">Morning</option>
          <option value="Day">Day</option>
          <option value="Night">Night</option>
        </select>
      </div>
    </div>
  </div> -->
  <div class="errorbox-good">
    <div class="ss-item ss-item-required ss-text">
      <div > <strong>How much time spent?</strong> <span class="ss-required-asterisk">*</span></div>
      <div class="">
        <label class="ss-q-help" for="entry_17"></label>
        <input name="txthmt" id="txthmt" class="" type="text">&nbsp;<strong>Hours</strong></div>
    </div>
  </div>
  <br>
  <div class="errorbox-good">
    <div class="ss-item ss-item-required ss-text">
      <div class="ss-form-entry">
        <label class="ss-q-title" for="entry_7">Average price per person <span class="ss-required-asterisk">*</span></label>
      </div>
      <div class="">
        <label class="ss-q-help" for="entry_7"></label>
        <strong>$</strong>&nbsp;<input name="txtppp"  class="" id="entry_7" type="text">
      </div>
    </div>
  </div>
  <br>
  <div class="ss-item ss-navigate">
    <div class="ss-form-entry"></div>
  </div>
  <input type="button" id="mySubmit" class="formButton" value="Send" onclick="formObj.submit()">
  <input type="reset" class="formButton" value="Reset" onClick="location.reload(true);">
</form>
</div>

<script type="text/javascript">
	
	var formObj = new DHTMLSuite.form({ formRef:'myForm',action:'formSubmit.php',responseEl:'formResponse'});
</script>
</body>
</html>