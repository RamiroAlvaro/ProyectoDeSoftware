"use strict";
var latitude;
var longitude;
var latlngStart;
var latlon;
var info = [];
var map;
var markerArray = [];
var directionsDisplay;
var weatherDisplay;
var start;
var end;
var weatherStart;
var weatherEnd;
var directionsService;
var infoString;

// setup initial map
function initialize() {   
    directionsService = new google.maps.DirectionsService();
    var mapCanvas = document.getElementById('map-canvas');
    var mapOptions	= {	// options for map
        center: new google.maps.LatLng(-34.903605, -57.937726),
        zoom: 8
    };    
       
       map = new google.maps.Map(mapCanvas, mapOptions);
       latlngStart = new google.maps.LatLng(-34.903605, -57.937726); // facultad de
       
       directionsDisplay = new google.maps.DirectionsRenderer();
       directionsDisplay.setMap(map);
       directionsDisplay.setOptions({suppressMarkers:true});
       
}

function calcRoute(direccion) {
  
    for (var i=0;i<markerArray.length;i++) {
        markerArray[i].setMap(null);
    }
    
    start = latlngStart;
    end = direccion;    
    
    
    var request = {
        origin:start,
        destination:end,
        travelMode: google.maps.TravelMode.DRIVING
    };       
 
    directionsService.route(request, function(response, status) {
        if (status === google.maps.DirectionsStatus.OK) {
          directionsDisplay.setDirections(response);
          showWeather(response);    
        }
    });
  
 }
 
function showWeather(directionResult) {
    var myRoute = directionResult.routes[0].legs[0];
     weatherDisplay = new google.maps.InfoWindow({
         
     });
     for (var i=0; i<myRoute.steps.length;i++) {
        var icon="https://chart.googleapis.com/chart?chst=d_map_pin_letter&chId="+i+"|FF0000|00000";
        if (i===0) {
             //icon as start position
             icon = "https://chart.googleapis.com/chart?chst=d_map_xpin_icon&chld=pin_star|car-dealer|00FFFF|FF0000";    
             
        }
     
        var marker = new google.maps.Marker({
            position: myRoute.steps[i].start_point, 
            map: map,
            icon: icon
       });
       
       markerArray.push(marker);
       google.maps.event.addListener(marker,'click',(function(marker,i) {
           return function() {
               weatherDisplay.setContent(getWeatherForLocation(myRoute.steps[i].end_point));
               weatherDisplay.open(map,marker);
           }
       })(marker,i));       
       
   } 
       //Icon as end position
        var marker = new google.maps.Marker({
            position: myRoute.steps[i - 1].end_point, 
            map: map,
            icon: "https://chart.googleapis.com/chart?chst=d_map_pin_icon&chld=flag|ADDE63"
        });  
        
        markerArray.push(marker);
        google.maps.event.addListener(marker,'click',(function(marker,i) {
            return function() {
               weatherDisplay.setContent(getWeatherForLocation(myRoute.steps[i-1].end_point));
               weatherDisplay.open(map,marker);
           }
       })(marker,i)); 
            
//        attachWeatherText(marker, getWeatherForLocation(myRoute.steps[i-1].end_point),weatherDisplay);
//        markerArray.push(marker);   
//      
//  
}

function getWeatherForLocation(str) {
    var lat;
    var lon;
    var regex = /\((-?[0-9]+\.[0-9]+), (-?[0-9]+\.[0-9]+)\)/g;
    var latlonArray = [];
    var match = regex.exec(str);
    while (match) {
        latlonArray.push({
            "lat" : match[1],
            "lon" : match[2]
        });
        match = regex.exec(str);
        lat = latlonArray[0].lat;
        lon = latlonArray[0].lon;
    }
        $.getJSON( "http://api.openweathermap.org/data/2.5/weather?lat="+lat+"&lon="+lon+"",function(data) {
           
            infoString = "Temperatura actual: " + Math.round(((data.main.temp-273.15))).toString() + "°C" + " Max: " 
                            + Math.round(((data.main.temp_max-273.15))).toString() + "°C" + " Min: " + Math.round(((data.main.temp_min-273.15))).toString() + 
                            "°C" + " Presion: " + (data.main.pressure.toString()) + "hPa" + " Humedad: " + (data.main.humidity.toString()) + "%";
                  //   myRoute.steps[i].instructions = "infoString";
            
     
        });    
        return infoString;
}

function geoCoding(str) {
    $.getJSON("https://maps.googleapis.com/maps/api/geocode/json?address="+str+"&sensor=false",function(data) {
        latitude = data.results[0].geometry.location.lat;
        longitude = data.results[0].geometry.location.lng;       
    });    
    return("(" + latitude + "," + longitude + ")");
}

google.maps.event.addDomListener(window, 'load', initialize);	// setup initial map
function loadMap(direccion) {
    google.maps.event.trigger(map,'resize');
    calcRoute(direccion);
}
   