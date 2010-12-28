// <copyright file="clientLocation.js" company="Sky Sanders">
// This source is placed in the Public Domain.
// http://skysanders.net/subtext
// Attribution is appreciated.
// </copyright>


/*
object literal format for google.loader.clientlocation  
{
"latitude": 33.324,
"longitude": -111.867,
"address": {
"city": "Chandler",
"region": "AZ",
"country": "USA",
"country_code": "US"
}
}
*/

var ClientLocation = {};

ClientLocation.Address = function() {
    /// <field name="city" type="String" />
    /// <field name="region" type="String" />
    /// <field name="country" type="String" />
    /// <field name="country_code" type="String" />
    /// <returns type="ClientLocation.Address"/>
    if (arguments.length > 0) {
        this.city = arguments[0].city;
        this.region = arguments[0].region;
        this.country = arguments[0].country;
        this.country_code = arguments[0].country_code;
        return;
    }
    else {
        this.city = "";
        this.region = "";
        this.country = "";
        this.country_code = "";
    }

}
ClientLocation.Location = function() {
    /// <field name="latitude" type="Number" />
    /// <field name="longitude" type="Number" />
    /// <field name="address" type="ClientLocation.Address" />
    if (arguments.length > 0) {

        this.latitude = arguments[0].latitude;
        this.longitude = arguments[0].longitude;
        this.address = arguments[0].address;

    }
    else {
        this.latitude = 0;
        this.longitude = 0;
        this.address = undefined;
    }

}


// <copyright file="localSearc.js" company="Sky Sanders">
// This source is placed in the Public Domain.
// http://skysanders.net/subtext
// Attribution is appreciated.
// </copyright>
/*
GlocalSearch result

{
"GsearchResultClass": "GlocalSearch",
"viewportmode": "computed",
"listingType": "local",
"lat": "33.389689",
"lng": "-111.853909",
"accuracy": "8",
"title": "Best \u003cb\u003eBuy\u003c/b\u003e",
"titleNoFormatting": "Best Buy",
"ddUrl": "http://www.google.com/maps....",
"ddUrlToHere": "http://www.google.com/maps?....",
"ddUrlFromHere": "http://www.google.com/maps?....",
"streetAddress": "1337 South Alma School Road",
"city": "Mesa",
"region": "AZ",
"country": "United States",
"staticMapUrl": "http://mt.google.com/mapdata?....",
"url": "http://www.google.com/maps/place?source....",
"content": "",
"maxAge": 604800,
"phoneNumbers": [{
"type": "",
"number": "(480) 644-7139"
},
{
"type": "",
"number": "(480) 464-0444"
}],
"addressLines": ["1337 South Alma School Road", "Mesa, AZ"]
}

*/
