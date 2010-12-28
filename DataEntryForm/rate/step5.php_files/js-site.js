//invite
var revert = new Array();
var inames = new Array('yahoo','hotmail','lycos','aol','gmail','rediff','facebook','bebo','indiatimes','Linkedin','myspace','orkut','el');


// Preload
if (document.images) {
  var flipped = new Array();
  for(i=0; i< inames.length; i++) {
    flipped[i] = new Image();
    flipped[i].src = websiteHTTPURL + "images/module/"+inames[i]+"_r.jpg";
  }
}

function over(num) {
  if(document.images) {
    revert[num] = document.images[inames[num]].src;
    document.images[inames[num]].src = flipped[num].src;
  }
}
function out(num) {
  if(document.images) document.images[inames[num]].src = revert[num];
}
//header radio
<!--
function MM_popupMsg(msg) { //v1.0
  alert(msg);
}
//-->
function site()
{
document.getElementById("site").style.display = "block";
document.getElementById("ticket").style.display = "none";


}
function ticket()
{
document.getElementById("site").style.display = "none";
document.getElementById("ticket").style.display = "block";


}

function toggle(element) {
if (document.getElementById(element).style.display == "none") {
document.getElementById(element).style.display = "";
} else {
document.getElementById(element).style.display = "none";
}
}
function inviter()
{
	document.getElementById("inviter").style.display = "block";
	document.getElementById("invited").style.display = "none";
	document.getElementById("eventInvitedList").style.display = "none" ;
	document.frmIMSimporter.button2.disabled = true ;
	
	//document.getElementById('importCsv').disabled = true ;
	//document.getElementById('importCsvSingle').disabled = true ;
	
	if(superVar == "outlook") 
	{
		document.getElementById("invite").style.height = 500+"px" ;
		document.getElementById("invite").style.overflow = "scroll" ;
		document.getElementById("imsLoginFrm").style.display = "none" ;
		superVar = "outlook" ;
		sendReq( '../ajax/getmodule.php?module=importContactOtherIMSajax&eventId='+preEvent  ) ;
		removePanel = "outlook" ;
		document.getElementById("imsContactListing").innerHTML = "" ;
		document.getElementById("outlookFormListing").style.display = 'none' ;
	}
	
	if(superVar == "ims") 
	{
		document.getElementById("imsLoginFrm").style.display = "none" ;
		document.getElementById("nofb").style.display = "" ;
		document.getElementById("fb").style.display = "none" ;
		sendReq( '../ajax/getmodule.php?module=importContactFanIMS&step='+chkvall+'&eventId='+preEvent  ) ;
		removePanel = "ims" ;
		superVar = "ims" ;
	}
	
	editBasicElem = "inv" ;
}
function invited()
{
document.getElementById("inviter").style.display = "none";
document.getElementById("invited").style.display = "block";
}

function site() {
document.getElementById("site").style.display = "";
document.getElementById("google").style.display = "none";
}

function google() {
document.getElementById("site").style.display = "none";
document.getElementById("google").style.display = "";
}
