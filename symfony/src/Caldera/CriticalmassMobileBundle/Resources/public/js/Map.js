Map = function(mapIdentifier, city, parentPage)
{
    this.mapIdentifier = mapIdentifier;
    this.city = city;
    this.parentPage = parentPage;

    this.initMap();

    this.initMapEventListeners();

    this.positions = new MapPositions(this);
    this.positions.startLoop();

    this.cities = new MapCities(this);
    this.cities.drawCityMarkers();

    this.ownPosition = new OwnPosition(this);
    this.ownPosition.showOwnPosition();

    this.quickLinks = new QuickLinks(this);
    this.quickLinks.initEventListeners();

    this.messages = new Messages(this);
    this.messages.startLoop();

};

Map.prototype.initMap = function()
{
    this.map = L.map('map');

    if (this.hasOverridingMapPosition())
    {
        var mapPositon = this.getOverridingMapPosition();

        this.map.setView([mapPositon.latitude,
            mapPositon.longitude],
            mapPositon.zoomFactor);

        $("select#flip-auto-center").val('off').slider('refresh');
    }
    else
    {
        this.initMapPosition();
    }


    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '<a href="http://www.mapbox.com/about/maps/" target="_blank">Terms &amp; Feedback</a>'
    }).addTo(this.map);

    //L.tileLayer('http://www.criticalmass.local/images/heatmap/8ef69b5739bc4fb6c06c7ba476bbe004/{z}/{x}/{y}.png').addTo(this.map);
};

Map.prototype.parentPage = null;
Map.prototype.mapIdentifier = null;

Map.prototype.setViewLatLngZoom = function(latitude, longitude, zoom)
{
    this.map.panTo([latitude, longitude]);
}

Map.prototype.initMapPosition = function()
{
    this.map.setView([53.5496385, 9.9625133], 15);
};

Map.prototype.initMapEventListeners = function(ajaxResultData)
{
    var this2 = this;

    this.map.on('dragstart', function()
    {
        this2.positions.stopAutoFollowing();
    });
};

Map.prototype.getNewMapData = function()
{
    $.ajax({
        type: 'GET',
        url: UrlFactory.getNodeJSApiPrefix() + '?action=fetch&rideId=1',
        cache: false,
        context: this,
        success: this.refreshMap
    });
};

Map.prototype.switchCity = function(newCitySlug)
{
    this.parentPage.switchCityBySlug(newCitySlug);

    this.getNewMapData();
};