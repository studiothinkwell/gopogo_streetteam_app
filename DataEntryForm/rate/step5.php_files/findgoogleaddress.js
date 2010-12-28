var marker=[];
var miniMarker=[];
var html=[];
var precis=[];
var iwn=0;
var poet=[];
var latitude=[];
var longitude=[];
var country=[];

//////////// Geocoding

var geo = new GClientGeocoder();
var lat = [];
var lng = [];
var bounds = new GLatLngBounds();
var Accu = [];

var status=[];
status[G_GEO_SUCCESS]            = "Success";
status[G_GEO_MISSING_ADDRESS]    = "Missing Address";
status[G_GEO_UNKNOWN_ADDRESS]    = "Unknown Address";
status[G_GEO_UNAVAILABLE_ADDRESS]= "Unavailable Address";
status[G_GEO_BAD_KEY]            = "Bad Key";
status[G_GEO_TOO_MANY_QUERIES]   = "Too Many Queries";
status[G_GEO_SERVER_ERROR]       = "Server Error"


//Enter button function

function showAddress() 
{
var adresslocation="";
var now=new Date();
lat.length=0;

document.getElementById("venulocationlist").innerHTML = "searching";
var search = document.getElementById("search").value;
geo.getLocations(search, function (result)
{ 
// show status code
document.getElementById("venulocationlist").innerHTML = "";

if (result.Status.code == G_GEO_SUCCESS) 
{
//start stopwatch
var then=new Date();
// Loop through the results, placing markers
for (var i=0; i<result.Placemark.length; i++) 
{
lat[i]=result.Placemark[i].Point.coordinates[1];
lng[i]=result.Placemark[i].Point.coordinates[0];
html[i]="<small>"+(i+1)+": </small> "+ result.Placemark[i].address;
Accu[i] = "";
adresslocation = result.Placemark[i].address ;
if(result.Placemark[i].AddressDetails){
Accu[i]=result.Placemark[i].AddressDetails.Accuracy;
}
precis[i]=3*html[i].split(",").length;
var point=new GLatLng(lat[i],lng[i]);
var coords="<small> ("+lat[i]+", "+lng[i]+")</small>";

document.getElementById("venulocationlist").innerHTML += "<a href='javascript: viewAddress(\""+ adresslocation +"\")'>" + html[i]+"</a><br>";
}

//// stopwatch display
//var delay=(then-now)/1000;
//document.getElementById("venulocationlist").innerHTML+="<small>"+ delay+" seconds</small>";

}else{
	document.getElementById("venulocationlist").innerHTML = "Invalid address. Please try again." ;
}
}
);
};

function viewAddress( address )
{
	if( document.getElementById( "addressflag" ) )
		document.getElementById( "addressflag" ).value = "1" ;

	document.getElementById( "eventlocation" ).value = address ;
}