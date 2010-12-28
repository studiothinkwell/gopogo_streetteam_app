	
function popupCaption(e,text){
	if(document.all)e = event;
	
	var obj = document.getElementById('bubble_caption');
	var obj2 = document.getElementById('bubble_caption_content');
	obj2.innerHTML = '<div align="center" style="width:90px;text-align:center;">'+text+'</div>';
	obj.style.display = 'block';
	var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
	if(navigator.userAgent.toLowerCase().indexOf('safari')>=0)st=0; 
	var leftPos = e.clientX - 100;
	if(leftPos<0)leftPos = 0;
	obj.style.left = leftPos + 'px';
	//obj.style.top = e.clientY - obj.offsetHeight -1 + st + 'px';
	obj.style.top = 10+'px';
}	

function hideCaption()
{
	document.getElementById('bubble_caption').style.display = 'none';
	
}