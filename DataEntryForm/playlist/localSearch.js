var LocalSearch = {};

LocalSearch.PhoneNumber = function() {
    /// <field name="type" type="String"/>
    /// <field name="number" type="String"/>
    /// <returns type="LocalSearch.PhoneNumber"/>

    if (arguments.length > 0) {
        this.type = arguments[0].type;
        this.number = arguments[0].number;
    }
    else {
        this.type = "";
        this.number = "";
    }
}



LocalSearch.Result = function() {
    /// <field name="GsearchResultClass" type="String"/>
    /// <field name="viewportmode" type="String"/>
    /// <field name="listingType" type="String"/>
    /// <field name="lat" type="String"/>
    /// <field name="lng" type="String"/>
    /// <field name="accuracy" type="String"/>
    /// <field name="title" type="String"/>
    /// <field name="titleNoFormatting" type="String"/>
    /// <field name="ddUrl" type="String"/>
    /// <field name="ddUrlToHere" type="String"/>
    /// <field name="ddUrlFromHere" type="String"/>
    /// <field name="streetAddress" type="String"/>
    /// <field name="city" type="String"/>
    /// <field name="region" type="String"/>
    /// <field name="country" type="String"/>
    /// <field name="staticMapUrl" type="String"/>
    /// <field name="url" type="String"/>
    /// <field name="content" type="String"/>
    /// <field name="maxAge" type="Number"/>
    /// <field name="phoneNumbers" type="Array"/>
    /// <field name="addressLines" type="Array"/>
    // <returns type="LocalSearch.Result"/>
    if (arguments.length > 0) {
        this.GsearchResultClass = arguments[0].GsearchResultClass;
        this.viewportmode = arguments[0].viewportmode;
        this.listingType = arguments[0].listingType;
        this.lat = arguments[0].lat;
        this.lng = arguments[0].lng;
        this.accuracy = arguments[0].accuracy;
        this.title = arguments[0].title;
        this.titleNoFormatting = arguments[0].titleNoFormatting;
        this.ddUrl = arguments[0].ddUrl;
        this.ddUrlToHere = arguments[0].ddUrlToHere;
        this.ddUrlFromHere = arguments[0].ddUrlFromHere;
        this.streetAddress = arguments[0].streetAddress;
        this.city = arguments[0].city;
        this.region = arguments[0].region;
        this.country = arguments[0].country;
        this.staticMapUrl = arguments[0].staticMapUrl;
        this.url = arguments[0].url;
        this.content = arguments[0].content;
        this.maxAge = arguments[0].maxAge;
        this.phoneNumbers = arguments[0].phoneNumbers;
        this.addressLines = arguments[0].addressLines;

    }
    else {

        this.GsearchResultClass = "";
        this.viewportmode = "";
        this.listingType = "";
        this.lat = "";
        this.lng = "";
        this.accuracy = "";
        this.title = "";
        this.titleNoFormatting = "";
        this.ddUrl = "";
        this.ddUrlToHere = "";
        this.ddUrlFromHere = "";
        this.streetAddress = "";
        this.city = "";
        this.region = "";
        this.country = "";
        this.staticMapUrl = "";
        this.url = "";
        this.content = "";
        this.maxAge = 0;
        this.phoneNumbers = [];
        this.addressLines = [];
    }


}
