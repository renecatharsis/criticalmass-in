var markersArray = [];
var map;

function addMarker(options)
{
	var circleOptions = {
		strokeColor: options.strokeColor,
		strokeOpacity: options.strokeOpacity,
		strokeWeight: options.strokeWeight,
		fillColor: options.fillColor,
		fillOpacity: options.fillOpacity,
		map: map,
		center: new google.maps.LatLng(options.latitude, options.longitude),
		radius: options.radius
	};

	circleMarker = new google.maps.Circle(circleOptions);
	markersArray[options.id] = circleMarker;
}

function clearAllMarkers()
{
	if (markersArray)
	{
		for (i in markersArray)
		{
			markersArray[i].setMap(null);
		}
	}
}

function clearMarker(id)
{
	markersArray[id].setMap(null);
	delete markersArray[id];
}

function refreshMarkers()
{
	$.ajax({
		type: 'GET',
		url: '/mapapi/mapdata',
		data: {
		},
		cache: false,
		success: refreshMarkers2
	});

}

function placeNewMarkers(result)
{
	for (var pos in result.positions)
	{
		if (!existsMarker(result.positions[pos]))
		{
			addMarker(result.positions[pos]);
		}
	}
}

function refreshMarkers2(result)
{
	placeNewMarkers(result);
	flushOldMarkers(result);
}

function flushOldMarkers(result)
{
	var pos;
	var i;

	for (i in markersArray)
	{
		var found = false;

		for (pos in result.positions)
		{
			if (i == result.positions[pos].id)
			{
				found = true;
			}
		}

		if (!found)
		{
			clearMarker(i);
		}
	}
}

function existsMarker(options)
{
	var found = false;

	for (i in markersArray)
	{
		if (i == options.id)
		{
			found = true;
			break;
		}
	}

	return found;
}

function setMapOptions(result)
{
	var mapOptions = {
		zoom: result.zoom,
		center: new google.maps.LatLng(result.mapcenter.latitude, result.mapcenter.longitude),
		mapTypeId: google.maps.MapTypeId.TERRAIN,
		disableDefaultUI: true
	}

	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

	proceedLoadingMarkers(result);
}

function initializeLivePage()
{
	if ($('#map-canvas').length > 0)
	{
		$.ajax({
			type: 'GET',
			url: '/mapapi/mapdata',
			data: {
			},
			cache: false,
			success: setMapOptions
		});
	}

	$( "#slider-gps-interval" ).on( "slidestop", function( event, ui ) {
		$.ajax({
			type: 'GET',
			url: '/settings/gpsinterval',
			data: {
				'interval': event.target.value
			},
			cache: false
		});
	} );

	$( "#flip-gps-sender" ).on( "slidestop", function( event, ui ) {
		$.ajax({
			type: 'GET',
			url: '/settings/gpsstatus',
			data: {
				'status': $("select#flip-gps-sender")[0].selectedIndex
			},
			cache: false
		});
	} );
}

function startInitialization()
{
	initializeLivePage();

	var timer = setInterval(refreshMarkers, 5000);
}

google.maps.event.addDomListener(window, 'load', startInitialization);
