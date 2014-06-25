RideFactory = function()
{

}

RideFactory.hasRides = function()
{
    return (localStorage.rideListData != null) && (localStorage.rideListData != '');
}

RideFactory.convertObjectToRide = function(objectData)
{
    var ride = new Ride();

    ride.setId(objectData.id);
    ride.setCitySlug(objectData.slug);
    ride.setDateTime(new Date(objectData.dateTime));
    ride.setLocation(objectData.location);
    ride.setLatitude(objectData.latitude);
    ride.setLongitude(objectData.longitude);
    ride.setHasLocation(objectData.hasLocation);
    ride.setTitle(objectData.title);
    ride.setDescription(objectData.description);

    return ride;
}

RideFactory.getRideFromStorageBySlug = function(citySlug)
{
    if (!localStorage.rideListData)
    {
        //alert("STORAGE IST LEER");
        return null;
    }

    var rideList = JSON.parse(localStorage.rideListData);

    for (index in rideList.rides)
    {
        if (rideList.rides[index].slug == citySlug)
        {
            return this.convertObjectToRide(rideList.rides[index]);
        }
    }

    return null;
}

RideFactory.storeAllRides = function()
{
    if (!localStorage.rideListData) or (localStorage.rideListData == null)
    {
        $.ajax({
            type: 'GET',
            async: false,
            url: UrlFactory.getApiPrefix() + 'ride/getcurrent',
            cache: false,
            context: this,
            success: function(data)
            {
                localStorage.rideListData = JSON.stringify(data);
                CallbackHell.executeEventListener('rideListRefreshed');
            }
        });
    }
}

RideFactory.refreshAllStoredRides = function()
{
    localStorage.rideListData = null;
    this.storeAllRides();
}