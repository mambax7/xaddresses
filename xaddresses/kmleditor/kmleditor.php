<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name = "description" content = "Online tool to create code for polyline and polygon to Google Maps v3 (version 3)"/>
<title>Tool for Google Maps v3 (version 3)</title>
<head>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
//<![CDATA[

// This application is provided by Kjell Scharning
//  Licensed under the Apache License, Version 2.0;
//  http://www.apache.org/licenses/LICENSE-2.0

function gob(e){if(typeof(e)=='object')return(e);if(document.getElementById)return(document.getElementById(e));return(eval(e))}
var map;
var polyShape;
var markerShape;
var oldDirMarkers = [];
//var tmpPolyLine;
var drawnShapes = [];
var holeShapes = [];
var startMarker;
var nemarker;
var tinyMarker;
var markers = [];
var midmarkers = [];
//var markerlistener1;
//var markerlistener2;
var rectangle;
var circle;
var southWest;
var northEast;
var centerPoint;
var radiusPoint;
var calc;
var startpoint;
var dirpointstart = null;
var dirpointend = 0;
var dirline;
var waypts = [];
//var waypots = [];
var polyPoints = [];
var pointsArray = [];
var markersArray = [];
var addresssArray = [];
var pointsArrayKml = [];
var markersArrayKml = [];
var toolID = 1;
var codeID = 1;
var shapeId = 0;
var adder = 0;
var plmcur = 0;
var lcur = 0;
var pcur = 0;
//var rcur = 0;
var ccur = 0;
var mcur = 0;
var outerPoints = [];
var holePolyArray = [];
var outerShape;
var anotherhole = false;
//var it;
var outerArray = [];
var innerArray = [];
var innerArrays = [];
var outerArrayKml = [];
var innerArrayKml = [];
var innerArraysKml = [];
var placemarks = [];
//var mylistener;
var editing = false;
var notext = false;
var kmlcode = "";
var javacode = "";
var polylineDecColorCur = "255,0,0";
var polygonDecColorCur = "255,0,0";
var docuname = "My document";
var docudesc = "Content";
var polylinestyles = [];
var polygonstyles = [];
//var rectanglestyles = [];
var circlestyles = [];
var markerstyles = [];
var geocoder; // = new google.maps.Geocoder();
//var startLocation;
var endLocation;
//var dircount;
var dircountstart;
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var directionsYes = 0;
var dirtool = 0;
//var oldDirections = [];
var destinations = [];
//var currentDirections = null;
var oldpoint = null;
//var infowindow = new google.maps.InfoWindow({size: new google.maps.Size(150,50)});
var tinyIcon = new google.maps.MarkerImage(
    'http://labs.google.com/ridefinder/images/mm_20_red.png',
    new google.maps.Size(12,20),
    new google.maps.Point(0,0),
    new google.maps.Point(6,16)
);
var tinyShadow = new google.maps.MarkerImage(
    'http://labs.google.com/ridefinder/images/mm_20_shadow.png',
    new google.maps.Size(22,20),
    new google.maps.Point(6,20),
    new google.maps.Point(5,1)
);
var imageNormal = new google.maps.MarkerImage(
	"images/square.png",
	new google.maps.Size(11, 11),
	new google.maps.Point(0, 0),
	new google.maps.Point(6, 6)
);
var imageHover = new google.maps.MarkerImage(
	"images/square_over.png",
	new google.maps.Size(11, 11),
	new google.maps.Point(0, 0),
	new google.maps.Point(6, 6)
);
var imageNormalMidpoint = new google.maps.MarkerImage(
	"images/square_transparent.png",
	new google.maps.Size(11, 11),
	new google.maps.Point(0, 0),
	new google.maps.Point(6, 6)
);
/*var imageHoverMidpoint = new google.maps.MarkerImage(
	"square_transparent_over.png",
	new google.maps.Size(11, 11),
	new google.maps.Point(0, 0),
	new google.maps.Point(6, 6)
);*/
function polystyle() {
    this.name = "Lump";
    this.kmlcolor = "CD0000FF";
    this.kmlfill = "9AFF0000";
    this.color = "#FF0000";
    this.fill = "#0000FF";
    this.width = 2;
    this.lineopac = 0.8;
    this.fillopac = 0.6;
}
function linestyle() {
    this.name = "Path";
    this.kmlcolor = "FF0000FF";
    this.color = "#FF0000";
    this.width = 3;
    this.lineopac = 1;
}
function circstyle() {
    this.name = "Circ";
    this.color = "#FF0000";
    this.fill = "#0000FF";
    this.width = 2;
    this.lineopac = 0.8;
    this.fillopac = 0.6;
}
function markerstyleobject() {
    this.name = "markerstyle";
    this.icon = "http://maps.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png";
}
function placemarkobject() {
    this.name = "NAME";
    this.desc = "YES";
    this.style = "Path";
    this.stylecur = 0;
    this.tess = 1;
    this.alt = "clampToGround";
    this.plmtext = "";
    this.jstext = "";
    this.jscode = [];
    this.kmlcode = [];
    this.poly = "pl";
    this.shape = null;
    this.point = null;
    this.toolID = 1;
    this.hole = 0;
    this.ID = 0;
}
function createplacemarkobject() {
    var thisplacemark = new placemarkobject();
    placemarks.push(thisplacemark);
}
function createpolygonstyleobject() {
    var polygonstyle = new polystyle();
    polygonstyles.push(polygonstyle);
}
function createlinestyleobject() {
    var polylinestyle = new linestyle();
    polylinestyles.push(polylinestyle);
}
function createcirclestyleobject() {
    var cirstyle = new circstyle();
    circlestyles.push(cirstyle);
}
function createmarkerstyleobject() {
    var thisstyle = new markerstyleobject();
    markerstyles.push(thisstyle);
}
function initmap(){
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(59.914063, 10.737874);//(45.0,7.0);//45.074723, 7.656433
    var myOptions = {
        zoom: 15, //3
        center: latlng,
        draggableCursor: 'default',
        draggingCursor: 'pointer',
        scaleControl: true,
        mapTypeControl: true,
        mapTypeControlOptions:{style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false};
    map = new google.maps.Map(gob('map_canvas'),myOptions);
    polyPoints = new google.maps.MVCArray(); // collects coordinates
    createplacemarkobject();
    createlinestyleobject();
    createpolygonstyleobject();
    createcirclestyleobject();
    createmarkerstyleobject();
    preparePolyline(); // create a Polyline object

    google.maps.event.addListener(map, 'click', addLatLng);
    google.maps.event.addListener(map,'zoom_changed',mapzoom);
    google.maps.event.addListener(map,'mousemove',function(point){
        var LnglatStr6 = point.latLng.lng().toFixed(6) + ', ' + point.latLng.lat().toFixed(6);
        var latLngStr6 = point.latLng.lat().toFixed(6) + ', ' + point.latLng.lng().toFixed(6);
        gob('over').options[0].text = LnglatStr6;
        gob('over').options[1].text = latLngStr6;
    });
}

function preparePolyline(){
    var polyOptions = {
        path: polyPoints,
        strokeColor: polylinestyles[lcur].color,
        strokeOpacity: polylinestyles[lcur].lineopac,
        strokeWeight: polylinestyles[lcur].width};
    polyShape = new google.maps.Polyline(polyOptions);
    polyShape.setMap(map);
    /*var tmpPolyOptions = {
    	strokeColor: polylinestyles[lcur].color,
    	strokeOpacity: polylinestyles[lcur].lineopac,
    	strokeWeight: polylinestyles[lcur].width
    };
    tmpPolyLine = new google.maps.Polyline(tmpPolyOptions);
    tmpPolyLine.setMap(map);*/
}

function preparePolygon(){
    var polyOptions = {
        path: polyPoints,
        strokeColor: polygonstyles[pcur].color,
        strokeOpacity: polygonstyles[pcur].lineopac,
        strokeWeight: polygonstyles[pcur].width,
        fillColor: polygonstyles[pcur].fill,
        fillOpacity: polygonstyles[pcur].fillopac};
    polyShape = new google.maps.Polygon(polyOptions);
    polyShape.setMap(map);
}
function activateRectangle() {
    rectangle = new google.maps.Rectangle({
        map: map,
        strokeColor: polygonstyles[pcur].color,
        strokeOpacity: polygonstyles[pcur].lineopac,
        strokeWeight: polygonstyles[pcur].width,
        fillColor: polygonstyles[pcur].fill,
        fillOpacity: polygonstyles[pcur].fillopac
    });
}
function activateCircle() {
    circle = new google.maps.Circle({
        map: map,
        fillColor: circlestyles[ccur].fill,
        fillOpacity: circlestyles[ccur].fillopac,
        strokeColor: circlestyles[ccur].color,
        strokeOpacity: circlestyles[ccur].lineopac,
        strokeWeight: circlestyles[ccur].width
    });
}
function activateMarker() {
    markerShape = new google.maps.Marker({
        map: map,
        icon: markerstyles[mcur].icon
    });
}
function addLatLng(point){
    if(directionsYes == 1) {
        drawDirections(point.latLng);
        return;
    }
    if(plmcur != placemarks.length-1) {
        nextshape();
    }

    // Rectangle and circle can't collect points with getPath. solved by letting Polyline collect the points and then erase Polyline
    polyPoints = polyShape.getPath();
    polyPoints.insertAt(polyPoints.length, point.latLng); // or: polyPoints.push(point.latLng)
    if(polyPoints.length == 1) {
        startpoint = point.latLng;
        placemarks[plmcur].point = startpoint; // stored because it's to be used when the shape is clicked on as a stored shape
        setstartMarker(startpoint);
        if(toolID == 5) {
            drawMarkers(startpoint);
        }
    }
    if(polyPoints.length == 2 && toolID == 3) createrectangle(point);
    if(polyPoints.length == 2 && toolID == 4) createcircle(point);
    if(toolID == 1 || toolID == 2) { // if polyline or polygon
        var stringtobesaved = point.latLng.lat().toFixed(6) + ',' + point.latLng.lng().toFixed(6);
        var kmlstringtobesaved = point.latLng.lng().toFixed(6) + ',' + point.latLng.lat().toFixed(6);
        if(adder == 0) {
            pointsArray.push(stringtobesaved);
            pointsArrayKml.push(kmlstringtobesaved);
            if(polyPoints.length == 1 && toolID == 2) closethis('polygonstuff');
            if(codeID == 1 && toolID == 1) logCode1(); // write kml for polyline
            if(codeID == 1 && toolID == 2) logCode2(); // write kml for polygon
            if(codeID == 2) logCode4(); // write Google javascript
        }
        if(adder == 1) {
            outerArray.push(stringtobesaved);
            outerArrayKml.push(kmlstringtobesaved);
        }
        if(adder == 2) {
            innerArray.push(stringtobesaved);
            innerArrayKml.push(kmlstringtobesaved);
        }
    }
}
function drawMarkers(point) {
    if(startMarker) startMarker.setMap(null);
    if(polyShape) polyShape.setMap(null);
    var id = plmcur;
    placemarks[plmcur].jscode = point.lat().toFixed(6) + ',' + point.lng().toFixed(6);
    placemarks[plmcur].kmlcode = point.lng().toFixed(6) + ',' + point.lat().toFixed(6);
    activateMarker();
    markerShape.setPosition(point);
    var marker = markerShape;
    tinyMarker = new google.maps.Marker({
        position: placemarks[plmcur].point,
        map: map,
        icon: tinyIcon
    });
    google.maps.event.addListener(marker, 'click', function(event){
        plmcur = id;
        markerShape = marker;
        var html = "<b>" + placemarks[plmcur].name + "</b> <br/>" + placemarks[plmcur].desc;
        var infowindow = new google.maps.InfoWindow({
            content: html
        });
        if(tinyMarker) tinyMarker.setMap(null);
        tinyMarker = new google.maps.Marker({
            position: placemarks[plmcur].point,
            map: map,
            icon: tinyIcon
        });
        if(toolID != 5) toolID = gob('toolchoice').value = 5;
        if(codeID == 1)logCode9();
        if(codeID == 2)logCode8();
        infowindow.open(map,marker);
    });
    drawnShapes.push(markerShape);
    if(codeID == 2) logCode8();
    if(codeID == 1) logCode9();
}
function drawDirections(point) {
    if(dirpointstart == null) {
        //setDirectionsMarker(point);
        dirpointstart = point;
        setstartMarker(dirpointstart);
        increaseplmcur();
        dirline = plmcur;
        placemarks[dirline].shape = polyShape;
        placemarks[dirline].point = dirpointstart;
        directionsDisplay = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            preserveViewport: true
        });
    }else{
        if(startMarker) startMarker.setMap(null);
        directionsDisplay.setOptions({polylineOptions: {
            strokeColor: polylinestyles[lcur].color,
            strokeOpacity: polylinestyles[lcur].lineopac,
            strokeWeight: polylinestyles[lcur].width}});
        if(dirpointend == 0) {
            var request = {
                origin: dirpointstart,
                destination: point,
                travelMode: google.maps.DirectionsTravelMode.DRIVING
            };
            oldpoint = point;
            destinations.push(request.destination);
            calcRoute(request);

            dirpointend = 1;
            dircountstart = plmcur+1;
        }else{
            if(oldpoint) waypts.push({location:oldpoint, stopover:true});
            request = {
                origin: dirpointstart,
                destination: point,
                waypoints: waypts,
                travelMode: google.maps.DirectionsTravelMode.DRIVING
            };
            oldpoint = point;
            destinations.push(request.destination);
            calcRoute(request);
        }
    }
}
function calcRoute(request) {
    if(waypts.length == 0) directionsDisplay.setMap(map);
    directionsService.route(request, RenderCustomDirections);
}
function RenderCustomDirections(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
        polyPoints = [];
        pointsArray = [];
        pointsArrayKml = [];
        markersArray = [];
        markersArrayKml = [];
        addresssArray = [];
        var result = directionsDisplay.getDirections().routes[0];
        for(var i = 0; i < result.overview_path.length; i++) {
            polyPoints.push(result.overview_path[i]);
            pointsArray.push(result.overview_path[i].lat().toFixed(6) + ',' + result.overview_path[i].lng().toFixed(6));
            pointsArrayKml.push(result.overview_path[i].lng().toFixed(6) + ',' + result.overview_path[i].lat().toFixed(6));
        }
        polyShape.setPath(polyPoints);
        endLocation = new Object();
        var legs = response.routes[0].legs;
        for (var i=0;i<legs.length;i++) {
            if (i == 0) {
                createdirMarker(legs[i].start_location,"start",legs[i].start_address,"green");
            } else {
                createdirMarker(legs[i].start_location,"waypoint"+i,legs[i].start_address,"yellow");
            }
            endLocation.latlng = legs[i].end_location;
            endLocation.address = legs[i].end_address;
            markersArray.push(legs[i].start_location.lat().toFixed(6) + ',' + legs[i].start_location.lng().toFixed(6));
            markersArrayKml.push(legs[i].start_location.lng().toFixed(6) + ',' + legs[i].start_location.lat().toFixed(6));
            addresssArray.push(legs[i].start_address);

        }
        createdirMarker(endLocation.latlng,"end",endLocation.address,"red");
        markersArray.push(endLocation.latlng.lat().toFixed(6) + ',' + endLocation.latlng.lng().toFixed(6));
        markersArrayKml.push(endLocation.latlng.lng().toFixed(6) + ',' + endLocation.latlng.lat().toFixed(6));
        addresssArray.push(endLocation.address);
        logCode1a();
    }
    else alert(status);
}
function createdirMarker(latlng, label, html, color) {
    if(tinyMarker) tinyMarker.setMap(null);
    createplacemarkobject();
    plmcur++;
    if(color == "green") {
        if(plmcur != dircountstart) {
            for(var i=dircountstart;i<plmcur;i++) {
                placemarks.pop();
                drawnShapes[drawnShapes.length-1].setMap(null);
                drawnShapes.pop();
            }
            plmcur = dircountstart;
        }
    }
    activateMarker();
    markerShape.setPosition(latlng);
    placemarks[plmcur].jscode = latlng.lat().toFixed(6) + ',' + latlng.lng().toFixed(6);
    placemarks[plmcur].kmlcode = latlng.lng().toFixed(6) + ',' + latlng.lat().toFixed(6);
    placemarks[plmcur].desc = html;
    placemarks[plmcur].point = latlng;
    placemarks[plmcur].style = markerstyles[mcur].name;
    placemarks[plmcur].stylecur = mcur;
    var marker = markerShape;
    var thisshape = plmcur;
    google.maps.event.addListener(marker, 'click', function(event){
        markerShape = marker;
        plmcur = thisshape;
        var html = "<b>" + placemarks[thisshape].name + "</b> <br/>" + placemarks[thisshape].desc;
        var infowindow = new google.maps.InfoWindow({
            content: html
        });
        if(tinyMarker) tinyMarker.setMap(null);
        tinyMarker = new google.maps.Marker({
            position: placemarks[plmcur].point,
            map: map,
            icon: tinyIcon
        });
        if(toolID != 5 && directionsYes == 0) toolID = gob('toolchoice').value = 5;
        if(codeID == 1) logCode9();
        if(codeID == 2) logCode8();
        infowindow.open(map,marker);
    });
    drawnShapes.push(markerShape);
}
// Called from button
function undo() {
    drawnShapes[drawnShapes.length-1].setMap(null);
    destinations.pop();
    var point = destinations[destinations.length-1];
    oldpoint = point;
    waypts.pop();
    var request = {
        origin: dirpointstart,
        destination: point,
        waypoints: waypts,
        travelMode: google.maps.DirectionsTravelMode.DRIVING
    };
    calcRoute(request);
}
function setstartMarker(point){
    startMarker = new google.maps.Marker({
        position: point,
        map: map});
    startMarker.setTitle("#" + polyPoints.length);
}
function setDirectionsMarker(point) {
    var image = new google.maps.MarkerImage('images/square.png');
    var marker = new google.maps.Marker({
        position: point,
        map: map,
        icon: image
    });
    /*var shadow = new google.maps.MarkerImage('http://maps.google.com/intl/en_us/mapfiles/ms/micons/msmarker.shadow.png',
        new google.maps.Size(37,32),
        new google.maps.Point(16,0),
        new google.maps.Point(0,32));
    var title = "#" + markers.length;
    var id = plmcur;
    placemarks[plmcur].point = point;
    placemarks[plmcur].coord = point.lng().toFixed(6) + ', ' + point.lat().toFixed(6);*/
    /*var marker = new google.maps.Marker({
        position: point,
        map: map,
        draggable: true,
        icon: image,
        shadow: shadow});*/
    //marker.setTitle(title);
    //markers.push(marker);
}
function createrectangle(point) {
    // startMarker is southwest point. now set northeast
    nemarker = new google.maps.Marker({
        position: point.latLng,
        draggable: true,
        title: "Draggable",
        map: map});
    google.maps.event.addListener(startMarker, 'drag', drawRectangle);
    google.maps.event.addListener(nemarker, 'drag', drawRectangle);
    startMarker.setDraggable(true);
    startMarker.setTitle("Draggable");
    polyShape.setMap(null); // remove the Polyline that has collected the points
    polyPoints = [];
    drawRectangle();
}
function drawRectangle() {
    southWest = startMarker.getPosition(); // used in logCode6()
    northEast = nemarker.getPosition(); // used in logCode6()
    var latLngBounds = new google.maps.LatLngBounds(
        southWest,
        northEast
    );
    rectangle.setBounds(latLngBounds);
    // the Rectangle was created in activateRectangle(), called from newstart(), which may have been called from setTool()
    var northWest = new google.maps.LatLng(southWest.lat(), northEast.lng());
    var southEast = new google.maps.LatLng(northEast.lat(), southWest.lng());
    polyPoints = [];
    pointsArray = [];
    pointsArrayKml = [];
    polyPoints.push(southWest);
    polyPoints.push(northWest);
    polyPoints.push(northEast);
    polyPoints.push(southEast);
    var stringtobesaved = southWest.lng().toFixed(6)+','+southWest.lat().toFixed(6);
    pointsArrayKml.push(stringtobesaved);
    stringtobesaved = southWest.lng().toFixed(6)+','+northEast.lat().toFixed(6);
    pointsArrayKml.push(stringtobesaved);
    stringtobesaved = northEast.lng().toFixed(6)+','+northEast.lat().toFixed(6);
    pointsArrayKml.push(stringtobesaved);
    stringtobesaved = northEast.lng().toFixed(6)+','+southWest.lat().toFixed(6);
    pointsArrayKml.push(stringtobesaved);
    stringtobesaved = southWest.lat().toFixed(6)+','+southWest.lng().toFixed(6);
    pointsArray.push(stringtobesaved);
    stringtobesaved = northEast.lat().toFixed(6)+','+southWest.lng().toFixed(6);
    pointsArray.push(stringtobesaved);
    stringtobesaved = northEast.lat().toFixed(6)+','+northEast.lng().toFixed(6);
    pointsArray.push(stringtobesaved);
    stringtobesaved = southWest.lat().toFixed(6)+','+northEast.lng().toFixed(6);
    pointsArray.push(stringtobesaved);
    if(codeID == 2) logCode6();
    if(codeID == 1) logCode2();
}
function createcircle(point) {
    // startMarker is center point. now set radius
    nemarker = new google.maps.Marker({
        position: point.latLng,
        draggable: true,
        title: "Draggable",
        map: map});
    google.maps.event.addListener(startMarker, 'drag', drawCircle);
    google.maps.event.addListener(nemarker, 'drag', drawCircle);
    startMarker.setDraggable(true);
    startMarker.setTitle("Draggable");
    drawCircle();
    polyShape.setMap(null); // remove the Polyline that has collected the points
    polyPoints = [];
}
function drawCircle() {
    centerPoint = startMarker.getPosition();
    radiusPoint = nemarker.getPosition();
    circle.bindTo('center', startMarker, 'position');
    calc = distance(centerPoint.lat(),centerPoint.lng(),radiusPoint.lat(),radiusPoint.lng());
    circle.setRadius(calc);
    codeID = gob('codechoice').value = 2;
    logCode7();
}
// calculate distance between two coordinates
function distance(lat1,lon1,lat2,lon2) {
    var R = 6371000; // earth's radius in meters
    var dLat = (lat2-lat1) * Math.PI / 180;
    var dLon = (lon2-lon1) * Math.PI / 180;
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(lat1 * Math.PI / 180 ) * Math.cos(lat2 * Math.PI / 180 ) *
    Math.sin(dLon/2) * Math.sin(dLon/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var d = R * c;
    return d;
}
function kmlheading() {
    var heading = "";
    var styleforpolygon = "";
    var styleforrectangle = "";
    var styleforpolyline = "";
    var styleformarker = "";
    var i;
    heading = '<?xml version="1.0" encoding="UTF-8"?>\n' +
        '<kml xmlns="http://www.opengis.net/kml/2.2">\n' +
        '<Document><name>'+docuname+'</name>\n' +
        '<description>'+docudesc+'</description>\n';
    for(i=0;i<polygonstyles.length;i++) {
        styleforpolygon += '<Style id="'+polygonstyles[i].name+'">\n' +
        '<LineStyle><color>'+polygonstyles[i].kmlcolor+'</color><width>'+polygonstyles[i].width+'</width></LineStyle>\n' +
        '<PolyStyle><color>'+polygonstyles[i].kmlfill+'</color></PolyStyle>\n' +
        '</Style>\n';
    }
    for(i=0;i<polylinestyles.length;i++) {
        styleforpolyline += '<Style id="'+polylinestyles[i].name+'">\n' +
        '<LineStyle><color>'+polylinestyles[i].kmlcolor+'</color><width>'+polylinestyles[i].width+'</width></LineStyle>\n' +
        '</Style>\n';
    }
    for(i=0;i<markerstyles.length;i++) {
        styleformarker += '<Style id="'+markerstyles[i].name+'">\n' +
        '<IconStyle><Icon><href>\n'+markerstyles[i].icon+'\n</href></Icon></IconStyle>\n' +
        '</Style>\n';
    }
    return heading+styleforpolygon+styleforpolyline+styleformarker;
}
function kmlend() {
    var ending;
    return ending = '</Document>\n</kml>';
}
// write kml for polyline in text area
function logCode1(){
    if (notext === true) return;
    var code = ""; // placemarks[plmcur].style = polylinestyles[lcur].name
    var kmltext1 = '<Placemark><name>'+placemarks[plmcur].name+'</name>\n' +
                    '<description>'+placemarks[plmcur].desc+'</description>\n' +
                    '<styleUrl>#'+placemarks[plmcur].style+'</styleUrl>\n' +
                    '<LineString>\n<tessellate>'+placemarks[plmcur].tess+'</tessellate>\n' +
                    '<altitudeMode>'+placemarks[plmcur].alt+'</altitudeMode>\n<coordinates>\n';
    for(var i = 0; i < pointsArrayKml.length; i++) {
        code += pointsArrayKml[i] + ',0\n';
    }
    kmltext2 = '</coordinates>\n</LineString>\n</Placemark>\n';
    placemarks[plmcur].plmtext = kmlcode = kmltext1+code+kmltext2;
    placemarks[plmcur].poly = "pl";
    placemarks[plmcur].jscode = pointsArray;
    placemarks[plmcur].kmlcode = pointsArrayKml;
    gob('coords1').value = kmlheading()+kmltext1+code+kmltext2+kmlend();
}
// write kml for Directions in text area
function logCode1a(){
    if (notext === true) return;
    gob('coords1').value = "";
    var code = "";
    //var kmlMarker = "";
    //var kmlMarkers = "";
    var kmltext1 = '<Placemark><name>'+placemarks[dirline].name+'</name>\n' +
                    '<description>'+placemarks[dirline].desc+'</description>\n' +
                    '<styleUrl>#'+placemarks[dirline].style+'</styleUrl>\n' +
                    '<LineString>\n<tessellate>'+placemarks[dirline].tess+'</tessellate>\n' +
                    '<altitudeMode>'+placemarks[dirline].alt+'</altitudeMode>\n<coordinates>\n';
    if(pointsArrayKml.length != 0) {
        for(var i = 0; i < pointsArrayKml.length; i++) {
            code += pointsArrayKml[i] + ',0\n';
        }
        placemarks[dirline].jscode = pointsArray;
        placemarks[dirline].kmlcode = pointsArrayKml;
    }
    kmltext2 = '</coordinates>\n</LineString>\n</Placemark>\n';
    placemarks[dirline].plmtext = kmltext1+code+kmltext2;
    placemarks[dirline].poly = "pl";
    gob('coords1').value = kmlheading()+kmltext1+code+kmltext2;
    if(markersArrayKml.length != 0) {
        for(i = 0; i < markersArrayKml.length; i++) {
            var kmlMarker = "";
            var m = dirline + 1;
            kmlMarker += '<Placemark><name>'+placemarks[m+i].name+'</name>\n' +
                            '<description>'+addresssArray[i]+'</description>\n' +
                            '<styleUrl>#'+markerstyles[mcur].name+'</styleUrl>\n' +
                            '<Point>\n<coordinates>';
            kmlMarker += markersArrayKml[i] + ',0\n';
            kmlMarker += '</coordinates>\n</Point>\n</Placemark>\n';
            placemarks[m+i].jscode = markersArray[i];
            placemarks[m+i].kmlcode = markersArrayKml[i];
            placemarks[m+i].plmtext = kmlMarker;
            gob('coords1').value += kmlMarker;
        }
    }
    //placemarks[dirline].plmtext = kmlcode = kmltext1+code+kmltext2+kmlMarkers;
    gob('coords1').value += kmlend();
}
// write kml for polygon in text area
function logCode2(){
    if (notext === true) return;
    var code = "";
    var kmltext1 = '<Placemark><name>'+placemarks[plmcur].name+'</name>\n' +
                    '<description>'+placemarks[plmcur].desc+'</description>\n' +
                    '<styleUrl>#'+placemarks[plmcur].style+'</styleUrl>\n' +
                    '<Polygon>\n<tessellate>'+placemarks[plmcur].tess+'</tessellate>\n' +
                    '<altitudeMode>'+placemarks[plmcur].alt+'</altitudeMode>\n' +
                    '<outerBoundaryIs><LinearRing><coordinates>\n';
    if(pointsArrayKml.length != 0) {
        for(var i = 0; i < pointsArrayKml.length; i++) {
            code += pointsArrayKml[i] + ',0\n';
        }
        code += pointsArrayKml[0] + ',0\n';
        placemarks[plmcur].jscode = pointsArray;
        placemarks[plmcur].kmlcode = pointsArrayKml;
    }
    kmltext2 = '</coordinates></LinearRing></outerBoundaryIs>\n</Polygon>\n</Placemark>\n';
    placemarks[plmcur].plmtext = kmlcode = kmltext1+code+kmltext2;
    placemarks[plmcur].poly = "pg";
    gob('coords1').value = kmlheading()+kmltext1+code+kmltext2+kmlend();
}
// write kml for polygon with hole
function logCode3(){
    if (notext === true) return;
    var kmltext = '<Placemark><name>'+placemarks[plmcur].name+'</name>\n' +
                    '<description>'+placemarks[plmcur].desc+'</description>\n' +
                    '<styleUrl>#'+placemarks[plmcur].style+'</styleUrl>\n' +
                    '<Polygon>\n<tessellate>'+placemarks[plmcur].tess+'</tessellate>\n' +
                    '<altitudeMode>'+placemarks[plmcur].alt+'</altitudeMode>\n' +
                    '<outerBoundaryIs><LinearRing><coordinates>\n';
    for(var i = 0; i < outerArrayKml.length; i++) {
        kmltext += outerArrayKml[i]+',0\n';
    }
    kmltext += outerArrayKml[0]+',0\n';
    kmltext += '</coordinates></LinearRing></outerBoundaryIs>\n';
    for(var m = 0; m < innerArraysKml.length; m++) {
        kmltext += '<innerBoundaryIs><LinearRing><coordinates>\n';
        for(var i = 0; i < innerArraysKml[m].length; i++) {
            kmltext += innerArraysKml[m][i]+',0\n';
        }
        kmltext += innerArraysKml[m][0]+',0\n';
        kmltext += '</coordinates></LinearRing></innerBoundaryIs>\n';
    }
    kmltext += '</Polygon>\n</Placemark>\n';
    placemarks[plmcur].plmtext = kmlcode = kmltext;
    gob('coords1').value = kmlheading()+kmltext+kmlend();
}
// write javascript
function logCode4(){
    if (notext === true) return;
    gob('coords1').value = 'var myCoordinates = [\n';
    for(var i=0; i<pointsArray.length; i++){
        if(i == pointsArray.length-1){
            gob('coords1').value += 'new google.maps.LatLng('+pointsArray[i] + ')\n';
        }else{
            gob('coords1').value += 'new google.maps.LatLng('+pointsArray[i] + '),\n';
        }
    }
    if(toolID == 1){
        gob('coords1').value += '];\n';
        var options = 'var polyOptions = {\n'
        +'path: myCoordinates,\n'
        +'strokeColor: "'+polylinestyles[lcur].color+'",\n'
        +'strokeOpacity: '+polylinestyles[lcur].lineopac+',\n'
        +'strokeWeight: '+polylinestyles[lcur].width+'\n'
        +'}\n';
        gob('coords1').value += options;
        gob('coords1').value +='var it = new google.maps.Polyline(polyOptions);\n'
        +'it.setMap(map);\n';
    }
    if(toolID == 2){
        gob('coords1').value += '];\n';
        var options = 'var polyOptions = {\n'
        +'path: myCoordinates,\n'
        +'strokeColor: "'+polygonstyles[pcur].color+'",\n'
        +'strokeOpacity: '+polygonstyles[pcur].lineopac+',\n'
        +'strokeWeight: '+polygonstyles[pcur].width+',\n'
        +'fillColor: "'+polygonstyles[pcur].fill+'",\n'
        +'fillOpacity: '+polygonstyles[pcur].fillopac+'\n'
        +'}\n';
        gob('coords1').value += options;
        gob('coords1').value +='var it = new google.maps.Polygon(polyOptions);\n'
        +'it.setMap(map);\n';
    }
    javacode = gob('coords1').value;
}
// write javascript for polygon with hole
function logCode5() {
    if (notext === true) return;
    var hstring = "";
    gob('coords1').value = 'var outerPoints = [\n';
    for(var i=0; i<outerArray.length; i++){
        if(i == outerArray.length-1){
            gob('coords1').value += 'new google.maps.LatLng('+outerArray[i] + ')\n'; // without trailing comma
        }else{
            gob('coords1').value += 'new google.maps.LatLng('+outerArray[i] + '),\n';
        }
    }
    gob('coords1').value += '];\n';
    for(var m=0; m<innerArrays.length; m++){
        gob('coords1').value += 'var innerPoints'+m+' = [\n';
        var holestring = 'innerPoints'+m;
        if(m<innerArrays.length-1) holestring += ',';
        hstring += holestring;
        for(i=0; i<innerArrays[m].length; i++){
            if(i == innerArrays[m].length-1){
                gob('coords1').value += 'new google.maps.LatLng('+innerArrays[m][i] + ')\n';
            }else{
                gob('coords1').value += 'new google.maps.LatLng('+innerArrays[m][i] + '),\n';
            }
        }
        gob('coords1').value += '];\n';
    }
    gob('coords1').value += 'var myCoordinates = [outerPoints,'+hstring+'];\n';
    gob('coords1').value += 'var polyOptions = {\n'
    +'paths: myCoordinates,\n'
    +'strokeColor: "'+polygonstyles[pcur].color+'",\n'
    +'strokeOpacity: '+polygonstyles[pcur].lineopac+',\n'
    +'strokeWeight: '+polygonstyles[pcur].width+',\n'
    +'fillColor: "'+polygonstyles[pcur].fill+'",\n'
    +'fillOpacity: '+polygonstyles[pcur].fillopac+'\n'
    +'};\n'
    +'var it = new google.maps.Polygon(polyOptions);\n'
    +'it.setMap(map);\n';
    javacode = gob('coords1').value;
}
// write javascript or kml for rectangle
function logCode6() {
    if (notext === true) return;
    //placemarks[plmcur].style = polygonstyles[pcur].name;
    if(codeID == 2) { // javascript
        gob('coords1').value = 'var rectangle = new google.maps.Rectangle({\n'
            +'map: map,\n'
            +'fillColor: '+polygonstyles[pcur].fill+',\n'
            +'fillOpacity: '+polygonstyles[pcur].fillopac+',\n'
            +'strokeColor: '+polygonstyles[pcur].color+',\n'
            +'strokeOpacity: '+polygonstyles[pcur].lineopac+',\n'
            +'strokeWeight: '+polygonstyles[pcur].width+'\n'
            +'});\n';
        gob('coords1').value += 'var sWest = new google.maps.LatLng('+southWest.lat().toFixed(6)+','+southWest.lng().toFixed(6)+');\n'
        +'var nEast = new google.maps.LatLng('+northEast.lat().toFixed(6)+','+northEast.lng().toFixed(6)+');\n'
        +'var bounds = new google.maps.LatLngBounds(sWest,nEast);\n'
        +'rectangle.setBounds(bounds);\n';
        gob('coords1').value += '\n\\\\ Code for polyline rectangle\n';
        gob('coords1').value += 'var myCoordinates = [\n';
        gob('coords1').value += southWest.lat().toFixed(6) + ',' + southWest.lng().toFixed(6) + ',\n' +
                    southWest.lat().toFixed(6) + ',' + northEast.lng().toFixed(6) + ',\n' +
                    northEast.lat().toFixed(6) + ',' + northEast.lng().toFixed(6) + ',\n' +
                    northEast.lat().toFixed(6) + ',' + southWest.lng().toFixed(6) + ',\n' +
                    southWest.lat().toFixed(6) + ',' + southWest.lng().toFixed(6) + '\n';
        gob('coords1').value += '];\n';
        var options = 'var polyOptions = {\n'
        +'path: myCoordinates,\n'
        +'strokeColor: "'+polygonstyles[pcur].color+'",\n'
        +'strokeOpacity: '+polygonstyles[pcur].lineopac+',\n'
        +'strokeWeight: '+polygonstyles[pcur].width+'\n'
        +'}\n';
        gob('coords1').value += options;
        gob('coords1').value +='var it = new google.maps.Polyline(polyOptions);\n'
        +'it.setMap(map);\n';
        javacode = gob('coords1').value;
    }
    if(codeID == 1) { // kml
        var kmltext = '<Placemark><name>'+placemarks[plmcur].name+'</name>\n' +
                        '<description>'+placemarks[plmcur].desc+'</description>\n' +
                        '<styleUrl>#'+placemarks[plmcur].style+'</styleUrl>\n' +
                        '<Polygon>\n<tessellate>'+placemarks[plmcur].tess+'</tessellate>\n' +
                        '<altitudeMode>'+placemarks[plmcur].alt+'</altitudeMode>\n' +
                        '<outerBoundaryIs><LinearRing><coordinates>\n';
        kmltext += southWest.lng().toFixed(6) + ',' + southWest.lat().toFixed(6) + ',0\n' +
                    southWest.lng().toFixed(6) + ',' + northEast.lat().toFixed(6) + ',0\n' +
                    northEast.lng().toFixed(6) + ',' + northEast.lat().toFixed(6) + ',0\n' +
                    northEast.lng().toFixed(6) + ',' + southWest.lat().toFixed(6) + ',0\n' +
                    southWest.lng().toFixed(6) + ',' + southWest.lat().toFixed(6) + ',0\n';
        kmltext += '</coordinates></LinearRing></outerBoundaryIs>\n</Polygon>\n</Placemark>\n';
        placemarks[plmcur].plmtext = kmlcode = kmltext;
        gob('coords1').value = kmlheading()+kmltext+kmlend();
    }
}
function logCode7() { // javascript for circle
    if (notext === true) return;
    //placemarks[plmcur].style = circlestyles[ccur].name;
    gob('coords1').value = 'var circle = new google.maps.Circle({\n'
        +'map: map,\n'
        +'center: new google.maps.LatLng('+centerPoint.lat().toFixed(6)+','+centerPoint.lng().toFixed(6)+'),\n'
        +'fillColor: '+circlestyles[ccur].fill+',\n'
        +'fillOpacity: '+circlestyles[ccur].fillopac+',\n'
        +'strokeColor: '+circlestyles[ccur].color+',\n'
        +'strokeOpacity: '+circlestyles[ccur].lineopac+',\n'
        +'strokeWeight: '+circlestyles[ccur].width+'\n'
        +'});\n';
    gob('coords1').value += 'circle.setRadius('+calc+');\n';
    javacode = gob('coords1').value;
}
function logCode8(){ //javascript for Marker
    if(notext === true) return;
    var text = 'var image = \''+markerstyles[mcur].icon+'\';\n'
        +'var marker = new google.maps.Marker({\n'
        +'position: '+placemarks[plmcur].point+',\n'
        +'map: map, //global variable \'map\' from opening function\n'
        +'icon: image\n'
        +'});\n'
        +'//Your content for the infowindow\n'
        +'var html = \'<b>'+ placemarks[plmcur].name +'</b> <br/>'+ placemarks[plmcur].desc +'\';';
    gob('coords1').value = text;
    javacode = gob('coords1').value;
}
function logCode9() { //KML for marker
    if(notext === true) return;
    gob('coords1').value = "";
    var kmlMarkers = "";
    kmlMarkers += '<Placemark><name>'+placemarks[plmcur].name+'</name>\n' +
                    '<description>'+placemarks[plmcur].desc+'</description>\n' +
                    '<styleUrl>#'+placemarks[plmcur].style+'</styleUrl>\n' +
                    '<Point>\n<coordinates>';
    kmlMarkers += placemarks[plmcur].kmlcode + ',0';
    kmlMarkers += '</coordinates>\n</Point>\n</Placemark>\n';
    //placemarks[plmcur].poly = "pl";
    placemarks[plmcur].plmtext = kmlcode = kmlMarkers;
    gob('coords1').value = kmlheading()+kmlMarkers+kmlend();
}

function setTool(){
    if(polyPoints.length == 0 && kmlcode == "" && javacode == "") {
        newstart();
    }else{
        if(toolID == 1){ // polyline
            // change to polyline draw mode not allowed
            if(outerArray.length > 0) { //indicates polygon with hole
                polyShape.setMap(null);
                newstart();
                return;
            }
            if(rectangle) {
                toolID = 3;
                nextshape();
                toolID = 1;
                newstart();
                return;
            }
            if(circle) {
                toolID = 4;
                nextshape();
                toolID = 1;
                newstart();
                return;
            }
            if(markerShape) {
                toolID = 5;
                nextshape();
                toolID = 1;
                newstart();
                return;
            }
            if(directionsYes == 1) {
                toolID = 6;
                nextshape();
                directionsYes = 0;
                toolID = 1;
                newstart();
                return;
            }
            placemarks[plmcur].style = polylinestyles[polylinestyles.length-1].name;
            placemarks[plmcur].stylecur = polylinestyles.length-1;
            if(polyShape) polyShape.setMap(null);
            preparePolyline(); //if a polygon exists, it will be redrawn as polylines
            if(codeID == 1) logCode1(); // KML
            if(codeID == 2) logCode4(); // Javascipt
        }
        if(toolID == 2){ // polygon
            if(rectangle) {
                toolID = 3;
                nextshape();
                toolID = 2;
                newstart();
                return;
            }
            if(circle) {
                toolID = 4;
                nextshape();
                toolID = 2;
                newstart();
                return;
            }
            if(markerShape) {
                toolID = 5;
                nextshape();
                toolID = 2;
                newstart();
                return;
            }
            if(directionsYes == 1) {
                toolID = 6;
                nextshape();
                directionsYes = 0;
                toolID = 2;
                newstart();
                return;
            }
            placemarks[plmcur].style = polygonstyles[polygonstyles.length-1].name;
            placemarks[plmcur].stylecur = polygonstyles.length-1;
            if(polyShape) polyShape.setMap(null);
            preparePolygon(); //if a polyline exists, it will be redrawn as a polygon
            if(codeID == 1) logCode2(); // KML
            if(codeID == 2) logCode4(); // Javascript
        }
        if(toolID == 3 || toolID == 4 || toolID == 5 || toolID == 6){
            if(polyShape) polyShape.setMap(null);
            if(directionsDisplay) directionsDisplay.setMap(null);
            if(circle) circle.setMap(null);
            if(rectangle) rectangle.setMap(null);
            directionsYes = 0;
            newstart();
        }
    }
}
function setCode(){
    if(toolID == 4) { // circle
        codeID = gob('codechoice').value = 2; // javascript
        return;
    }
    if(toolID == 6) { // directions
        codeID = gob('codechoice').value = 1; // KML
        return;
    }
    if(polyPoints.length !== 0 || kmlcode !== "" || javacode !== ""){
        if(codeID == 1 && toolID == 1) logCode1();
        if(codeID == 1 && toolID == 2 && outerArray.length == 0) logCode2();
        if(codeID == 1 && toolID == 2 && outerArray.length > 0) logCode3();
        if(codeID == 2 && toolID == 2 && outerArray.length == 0) logCode4();
        if(codeID == 2 && toolID == 2 && outerArray.length > 0) logCode5();
        if(toolID == 3) { // rectangle
            if(codeID == 1) logCode2();
            if(codeID == 2) logCode6();
        }
        if(toolID == 5) { // marker
            if(codeID == 1) logCode9();
            if(codeID == 2) logCode8();
        }
    }
}
function increaseplmcur() {
    if(placemarks[plmcur].plmtext != "") {
        if(polyShape && directionsYes == 0) {
            placemarks[plmcur].shape = polyShape;
            if(toolID==1 || toolID==2 || toolID==3) addpolyShapelistener();
            createplacemarkobject();
            plmcur = placemarks.length -1;
        }
        if(markerShape) {
            placemarks[plmcur].shape = markerShape;
            createplacemarkobject();
            plmcur = placemarks.length -1;
        }
    }
}
function nextshape() {
    if(editing == true) stopediting();
    placemarks[plmcur].shape = polyShape;
    if(southWest) {
        rectangle.setMap(null);
        southWest = northEast = null;
        preparePolygon();
    }
    if(plmcur < placemarks.length -1) addpolyShapelistener();
    plmcur = placemarks.length -1;
    increaseplmcur();
    if(directionsYes == 1) {
        plmcur = dirline;
        addpolyShapelistener();
        plmcur = placemarks.length -1;
        toolID = 6;
    }
    if(polyShape) drawnShapes.push(polyShape); // used in clearMap, to have it removed from the map, drawnShapes[i].setMap(null)
    if(outerShape) drawnShapes.push(outerShape);
    if(circle) drawnShapes.push(circle);
    if(directionsDisplay) directionsDisplay.setMap(null);
    if(tinyMarker) drawnShapes.push(tinyMarker);
    polyShape = null;
    outerShape = null;
    rectangle = null;
    circle = null;
    markerShape = null;
    directionsDisplay = null;
    newstart();
}
function addpolyShapelistener() {
    if(outerPoints.length>>0) return;
    var thisshape = plmcur;
    // In v2 I can give a shape an ID and have that ID revealed, with the map listener, when the shape is clicked on
    // I can't do that in v3. Instead I put a listener on the shape
    google.maps.event.addListener(polyShape,'click',function(point){
        if(tinyMarker) tinyMarker.setMap(null);
        directionsYes = 0;
        polyShape = placemarks[thisshape].shape;
        polyPoints = polyShape.getPath();
        //polyShape.setMap(null);
        if(startMarker) startMarker.setMap(null);
        setstartMarker(placemarks[thisshape].point);
        plmcur = thisshape;
        pointsArray = placemarks[plmcur].jscode;
        pointsArrayKml = placemarks[plmcur].kmlcode;
        closethis('polygonstuff');
        if(placemarks[plmcur].poly == "pl") {
            toolID = gob('toolchoice').value = 1;
            lcur = placemarks[plmcur].stylecur;
            if(codeID == 1) logCode1();
            if(codeID == 2) logCode4(); // write Google javascript
        }else{
            toolID = gob('toolchoice').value = 2;
            pcur = placemarks[plmcur].stylecur;
            if(codeID == 1) logCode2();
            if(codeID == 2) logCode4(); // write Google javascript
        }
    });
}
// Clear current Map
function clearMap(){
    if(editing == true) stopediting();
    if(polyShape) polyShape.setMap(null); // polyline or polygon
    if(outerShape) outerShape.setMap(null);
    if(rectangle) rectangle.setMap(null);
    if(circle) circle.setMap(null);
    if(drawnShapes.length > 0) {
        for(var i = 0; i < drawnShapes.length; i++) {
            drawnShapes[i].setMap(null);
        }
    }
    plmcur = 0;
    newstart();
    placemarks = [];
    createplacemarkobject();
}
function newstart() {
    polyPoints = [];
    outerPoints = [];
    pointsArray = [];
    markersArray = [];
    pointsArrayKml = [];
    markersArrayKml = [];
    addresssArray = [];
    outerArray = [];
    innerArray = [];
    outerArrayKml = [];
    innerArrayKml = [];
    holePolyArray = [];
    innerArrays = [];
    innerArraysKml = [];
    waypts = [];
    destinations = [];
    adder = 0;
    directionsYes = 0;
    dirpointend = 0;
    dirpointstart = null;
    oldpoint = null;
    closethis('polylineoptions');
    closethis('polygonoptions');
    closethis('circleoptions');
    if(toolID != 2) closethis('polygonstuff');
    if(directionsDisplay) directionsDisplay.setMap(null);
    if(startMarker) startMarker.setMap(null);
    if(nemarker) nemarker.setMap(null);
    if(tinyMarker) tinyMarker.setMap(null);
    if(toolID == 1) {
        placemarks[plmcur].style = polylinestyles[polylinestyles.length-1].name;
        placemarks[plmcur].stylecur = polylinestyles.length-1;
        preparePolyline();
        polylineintroduction();
    }
    if(toolID == 2){
        showthis('polygonstuff');
        gob('stepdiv').innerHTML = "Step 0";
        placemarks[plmcur].style = polygonstyles[polygonstyles.length-1].name;
        placemarks[plmcur].stylecur = polygonstyles.length-1;
        preparePolygon();
        polygonintroduction();
    }
    if(toolID == 3) {
        placemarks[plmcur].style = polygonstyles[polygonstyles.length-1].name;
        placemarks[plmcur].stylecur = polygonstyles.length-1;
        preparePolyline(); // use Polyline to collect clicked point
        activateRectangle();
        rectangleintroduction();
    }
    if(toolID == 4) {
        placemarks[plmcur].style = circlestyles[circlestyles.length-1].name;
        placemarks[plmcur].stylecur = circlestyles.length-1;
        preparePolyline(); // use Polyline to collect clicked point
        activateCircle();
        circleintroduction();
        codeID = gob('codechoice').value = 2; // javascript, no KML for circle
    }
    if(toolID == 5) {
        placemarks[plmcur].style = markerstyles[markerstyles.length-1].name;
        placemarks[plmcur].stylecur = markerstyles.length-1;
        preparePolyline();
        markerintroduction();
    }
    if(toolID == 6){
        directionsYes = 1;
        preparePolyline();
        directionsintroduction();
        codeID = gob('codechoice').value = 1;
    }
    kmlcode = "";
    javacode = "";
}

function deleteLastPoint(){
    if(directionsYes == 1) {
        if(destinations.length == 1) return;
        undo();
        return;
    }
    if(toolID == 1) {
        if(polyShape) {
            polyPoints = polyShape.getPath();
            if(polyPoints.length > 0) {
                polyPoints.removeAt(polyPoints.length-1);
                if(codeID == 1) logCode1();
                if(codeID == 2) logCode4();
            }
        }
    }
    if(toolID == 2) {
        if(outerPoints.length===0) {
            if(polyShape) {
                polyPoints = polyShape.getPath();
                if(polyPoints.length > 0) {
                    polyPoints.removeAt(polyPoints.length-1);
                    if(codeID == 1) logCode2();
                    if(codeID == 2) logCode4();
                }
            }
        }
    }
    if(polyPoints.length === 0) nextshape();
}
function counter(num){
    return adder = adder + num;
}
function holecreator(){
    var step = counter(1);
    if(step == 1){
        if(gob('stepdiv').innerHTML == "Finished"){
            adder = 0;
            return;
        }else{
            if(startMarker) startMarker.setMap(null);
            if(polyShape) polyShape.setMap(null);
            polyPoints = [];
            preparePolyline();
            gob('stepdiv').innerHTML = "Step 1";
            gob('coords1').value = 'You may now draw the outer boundary. When finished, click Hole to move on to the next step.'
            +' Remember, you do not have to let start and end meet.'
            +' The API will close the shape in the finished polygon.';
        }
    }
    if(step == 2){
        if(anotherhole == false) {
            // outer line is finished, in Polyline draw mode
            polyPoints.insertAt(polyPoints.length, startpoint); // let start and end meet
            outerPoints = polyPoints;
            holePolyArray.push(outerPoints);
            outerShape = polyShape;
        }
        gob('stepdiv').innerHTML = "Step 2";
        gob('coords1').value = 'You may now draw an inner boundary. Click Hole again to see the finished polygon.';
        if(anotherhole == true) {
            // a hole has been drawn, another is about to be drawn
            if(polyShape && polyPoints.length == 0) {
                polyShape.setMap(null);
                gob('coords1').value = 'Oops! Not programmed yet, but you may continue drawing holes. '+
                'Everything you have created will show up when you click Hole again.';
            }else{
                polyPoints.insertAt(polyPoints.length, startpoint);
                holePolyArray.push(polyPoints);
                if(innerArray.length>0) innerArrays.push(innerArray);
                if(innerArrayKml.length>0) innerArraysKml.push(innerArrayKml);
                holeShapes.push(polyShape);
                innerArray = [];
            }
        }
        polyPoints = [];
        preparePolyline();
        if(startMarker) startMarker.setMap(null);
    }
    if(step == 3){
        if(startMarker) startMarker.setMap(null);
        if(outerShape) outerShape.setMap(null);
        if(polyShape) polyShape.setMap(null);
        if(polyPoints.length>0) holePolyArray.push(polyPoints);
        if(innerArray.length>0) innerArrays.push(innerArray);
        if(innerArrayKml.length>0) innerArraysKml.push(innerArrayKml);
        drawpolywithhole();
        gob('stepdiv').innerHTML = "Finished";
        adder = 0;
        if(codeID == 1) logCode3();
        if(codeID == 2) logCode5();
    }
}
function drawpolywithhole() {
    if(holeShapes.length > 0) {
        for(var i = 0; i < holeShapes.length; i++) {
            holeShapes[i].setMap(null);
        }
    }
    var Points = new google.maps.MVCArray(holePolyArray);
    var polyOptions = {
        paths: Points,
        strokeColor: polygonstyles[pcur].color,
        strokeOpacity: polygonstyles[pcur].lineopac,
        strokeWeight: polygonstyles[pcur].width,
        fillColor: polygonstyles[pcur].fill,
        fillOpacity: polygonstyles[pcur].fillopac
    };
    polyShape = new google.maps.Polygon(polyOptions);
    polyShape.setMap(map);
    anotherhole = false;
    startMarker = new google.maps.Marker({
        position: outerPoints.getAt(0),
        map: map});
    startMarker.setTitle("Polygon with hole");
}
function nexthole() {
    if(gob('stepdiv').innerHTML != "Finished") {
        if(outerPoints.length > 0) {
            adder = 1;
            anotherhole = true;
            drawnShapes.push(polyShape);
            holecreator();
        }
    }
}
function stopediting(){
    editing = false;
    gob('EditButton').value = 'Edit lines';
    for(var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    for(var i = 0; i < midmarkers.length; i++) {
        midmarkers[i].setMap(null);
    }
    polyPoints = polyShape.getPath();
    markers = [];
    midmarkers = [];
    if(plmcur != placemarks.length-1) {
        placemarks[plmcur].shape = polyShape;
        drawnShapes.push(polyShape);
        addpolyShapelistener();
    }
    setstartMarker(polyPoints.getAt(0));
}
// the "Edit lines" button has been pressed
function editlines(){
    if(editing == true){
        stopediting();
    }else{
        if(outerArray.length > 0) {
            return;
        }
        polyPoints = polyShape.getPath();
        if(polyPoints.length > 0){
            toolID = gob('toolchoice').value = 1; // editing is set to be possible only in polyline draw mode
            setTool();
            if(startMarker) startMarker.setMap(null);
            for(var i = 0; i < polyPoints.length; i++) {
                var marker = setmarkers(polyPoints.getAt(i));
                markers.push(marker);
                if(i > 0) {
                    var midmarker = setmidmarkers(polyPoints.getAt(i));
                    midmarkers.push(midmarker);
                }
            }
            editing = true;
            gob('EditButton').value = 'Stop edit';
        }
    }
}
function setmarkers(point) {
    var marker = new google.maps.Marker({
    	position: point,
    	map: map,
    	icon: imageNormal,
    	draggable: true
    });
    google.maps.event.addListener(marker, "mouseover", function() {
    	marker.setIcon(imageHover);
    });
    google.maps.event.addListener(marker, "mouseout", function() {
    	marker.setIcon(imageNormal);
    });
    google.maps.event.addListener(marker, "drag", function() {
        for (var i = 0; i < markers.length; i++) {
            if (markers[i] == marker) {
                polyShape.getPath().setAt(i, marker.getPosition());
                movemidmarker(i);
                break;
            }
        }
        polyPoints = polyShape.getPath();
        var stringtobesaved = marker.getPosition().lat().toFixed(6) + ',' + marker.getPosition().lng().toFixed(6);
        var kmlstringtobesaved = marker.getPosition().lng().toFixed(6) + ',' + marker.getPosition().lat().toFixed(6);
        pointsArray.splice(i,1,stringtobesaved);
        pointsArrayKml.splice(i,1,kmlstringtobesaved);
        logCode1();
    });
    google.maps.event.addListener(marker, "click", function() {
        for (var i = 0; i < markers.length; i++) {
            if (markers[i] == marker && markers.length != 1) {
                marker.setMap(null);
                markers.splice(i, 1);
                polyShape.getPath().removeAt(i);
                removemidmarker(i);
                break;
            }
        }
        polyPoints = polyShape.getPath();
        if(markers.length > 0) {
            pointsArray.splice(i,1);
            pointsArrayKml.splice(i,1);
            logCode1();
        }
    });
    return marker;
}
function setmidmarkers(point) {
    var prevpoint = markers[markers.length-2].getPosition();
    var marker = new google.maps.Marker({
    	position: new google.maps.LatLng(
    		point.lat() - (0.5 * (point.lat() - prevpoint.lat())),
    		point.lng() - (0.5 * (point.lng() - prevpoint.lng()))
    	),
    	map: map,
    	icon: imageNormalMidpoint,
    	draggable: true
    });
    google.maps.event.addListener(marker, "mouseover", function() {
    	marker.setIcon(imageNormal);
    });
    google.maps.event.addListener(marker, "mouseout", function() {
    	marker.setIcon(imageNormalMidpoint);
    });
    /*google.maps.event.addListener(marker, "dragstart", function() {
    	for (var i = 0; i < midmarkers.length; i++) {
    		if (midmarkers[i] == marker) {
    			var tmpPath = tmpPolyLine.getPath();
    			tmpPath.push(markers[i].getPosition());
    			tmpPath.push(midmarkers[i].getPosition());
    			tmpPath.push(markers[i+1].getPosition());
    			break;
    		}
    	}
    });
    google.maps.event.addListener(marker, "drag", function() {
    	for (var i = 0; i < midmarkers.length; i++) {
    		if (midmarkers[i] == marker) {
    			tmpPolyLine.getPath().setAt(1, marker.getPosition());
    			break;
    		}
    	}
    });*/
    google.maps.event.addListener(marker, "dragend", function() {
    	for (var i = 0; i < midmarkers.length; i++) {
    		if (midmarkers[i] == marker) {
    			var newpos = marker.getPosition();
    			var startMarkerPos = markers[i].getPosition();
    			var firstVPos = new google.maps.LatLng(
    				newpos.lat() - (0.5 * (newpos.lat() - startMarkerPos.lat())),
    				newpos.lng() - (0.5 * (newpos.lng() - startMarkerPos.lng()))
    			);
    			var endMarkerPos = markers[i+1].getPosition();
    			var secondVPos = new google.maps.LatLng(
    				newpos.lat() - (0.5 * (newpos.lat() - endMarkerPos.lat())),
    				newpos.lng() - (0.5 * (newpos.lng() - endMarkerPos.lng()))
    			);
    			var newVMarker = setmidmarkers(secondVPos);
    			newVMarker.setPosition(secondVPos);//apply the correct position to the midmarker
    			var newMarker = setmarkers(newpos);
    			markers.splice(i+1, 0, newMarker);
    			polyShape.getPath().insertAt(i+1, newpos);
    			marker.setPosition(firstVPos);
    			midmarkers.splice(i+1, 0, newVMarker);
    			/*tmpPolyLine.getPath().removeAt(2);
    			tmpPolyLine.getPath().removeAt(1);
    			tmpPolyLine.getPath().removeAt(0);
    			newpos = null;
    			startMarkerPos = null;
    			firstVPos = null;
    			endMarkerPos = null;
    			secondVPos = null;
    			newVMarker = null;
    			newMarker = null;*/
    			break;
    		}
    	}
        polyPoints = polyShape.getPath();
        var stringtobesaved = newpos.lat().toFixed(6) + ',' + newpos.lng().toFixed(6);
        var kmlstringtobesaved = newpos.lng().toFixed(6) + ',' + newpos.lat().toFixed(6);
        pointsArray.splice(i+1,0,stringtobesaved);
        pointsArrayKml.splice(i+1,0,kmlstringtobesaved);
        logCode1();
    });
    return marker;
}
function movemidmarker(index) {
    var newpos = markers[index].getPosition();
    if (index != 0) {
    	var prevpos = markers[index-1].getPosition();
    	midmarkers[index-1].setPosition(new google.maps.LatLng(
    		newpos.lat() - (0.5 * (newpos.lat() - prevpos.lat())),
    		newpos.lng() - (0.5 * (newpos.lng() - prevpos.lng()))
    	));
    	//prevpos = null;
    }
    if (index != markers.length - 1) {
    	var nextpos = markers[index+1].getPosition();
    	midmarkers[index].setPosition(new google.maps.LatLng(
    		newpos.lat() - (0.5 * (newpos.lat() - nextpos.lat())),
    		newpos.lng() - (0.5 * (newpos.lng() - nextpos.lng()))
    	));
    	//nextpos = null;
    }
    //newpos = null;
    //index = null;
}
function removemidmarker(index) {
    if (markers.length > 0) {//clicked marker has already been deleted
    	if (index != markers.length) {
    		midmarkers[index].setMap(null);
    		midmarkers.splice(index, 1);
    	} else {
    		midmarkers[index-1].setMap(null);
    		midmarkers.splice(index-1, 1);
    	}
    }
    if (index != 0 && index != markers.length) {
    	var prevpos = markers[index-1].getPosition();
    	var newpos = markers[index].getPosition();
    	midmarkers[index-1].setPosition(new google.maps.LatLng(
    		newpos.lat() - (0.5 * (newpos.lat() - prevpos.lat())),
    		newpos.lng() - (0.5 * (newpos.lng() - prevpos.lng()))
    	));
    	//prevpos = null;
    	//newpos = null;
    }
    //index = null;
}
function showKML() {
    if(codeID != 1) {
        codeID = gob('codechoice').value = 1; // set KML
        setCode();
    }
    gob('coords1').value = kmlheading();
    for (var i = 0; i < placemarks.length; i++) {
        gob('coords1').value += placemarks[i].plmtext;
    }
    gob('coords1').value += kmlend();
}
function showAddress(address) {
    geocoder.geocode({'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var pos = results[0].geometry.location;
            map.setCenter(pos);
            if(directionsYes == 1) drawDirections(pos);
            if(toolID == 5) drawMarkers(pos);
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }
    });
}
function activateDirections() {
    directionsYes = 1;
    directionsintroduction();
}
function closethis(name){
    gob(name).style.visibility = 'hidden';
}
function showthis(name){
    gob(name).style.visibility = 'visible';
}
function iconoptions(chosenicon) {
    gob("st2").value = chosenicon;
    gob("currenticon").innerHTML = '<img src="'+chosenicon+'" alt="" />';
}
function styleoptions(){ //present current style
    closethis('polylineoptions');
    closethis('polygonoptions');
    //closethis('rectang');
    closethis('circleoptions');
    closethis('markeroptions');
    if(directionsYes == 1) {
        if(dirtool == 0) {
            showthis('directionstyles');
            dirtool = 1;
        }else{
            closethis('directionstyles');
            dirtool = 0;
        }
    }
    if(toolID == 1){
        showthis('polylineoptions');
        //if(plmcur<placemarks.length-1) lcur = placemarks[plmcur].stylecur;
        gob('polylineinput1').value = polylinestyles[lcur].color;
        gob('polylineinput2').value = polylinestyles[lcur].lineopac;
        gob('polylineinput3').value = polylinestyles[lcur].width;
        gob('polylineinput4').value = polylinestyles[lcur].name;
        gob("stylenumberl").innerHTML = (lcur+1)+' ';
    }
    if(toolID == 2 || toolID == 3){
        showthis('polygonoptions');
        //if(plmcur<placemarks.length-1) pcur = placemarks[plmcur].stylecur;
        gob('polygoninput1').value = polygonstyles[pcur].color;
        gob('polygoninput2').value = polygonstyles[pcur].lineopac;
        gob('polygoninput3').value = polygonstyles[pcur].width;
        gob('polygoninput4').value = polygonstyles[pcur].fill;
        gob('polygoninput5').value = polygonstyles[pcur].fillopac;
        gob('polygoninput6').value = polygonstyles[pcur].name;
        gob("stylenumberp").innerHTML = (pcur+1)+' ';
    }
    if(toolID == 4) {
        showthis('circleoptions');
        gob('circinput1').value = circlestyles[ccur].color;
        gob('circinput2').value = circlestyles[ccur].lineopac;
        gob('circinput3').value = circlestyles[ccur].width;
        gob('circinput4').value = circlestyles[ccur].fill;
        gob('circinput5').value = circlestyles[ccur].fillopac;
        gob('circinput6').value = circlestyles[ccur].name;
        gob("stylenumberc").innerHTML = (ccur+1)+' ';
    }
    if(toolID == 5){
        showthis('markeroptions');
        gob('st1').value = markerstyles[mcur].name;
        iconoptions(markerstyles[mcur].icon);
        gob("stylenumberm").innerHTML = (mcur+1)+' ';
    }
}
function polylinestyle(e){ //save style
    if(e == 1) {
        createlinestyleobject();
        lcur++;
    }
    polylinestyles[lcur].color = gob('polylineinput1').value;
    polylineDecColorCur = color_hex2dec(polylinestyles[lcur].color);
    polylinestyles[lcur].lineopac = gob('polylineinput2').value;
    if(polylinestyles[lcur].lineopac<0 || polylinestyles[lcur].lineopac>1) return alert('Opacity must be between 0 and 1');
    polylinestyles[lcur].width = gob('polylineinput3').value;
    if(polylinestyles[lcur].width<0 || polylinestyles[lcur].width>20) return alert('Numbers below zero and above 20 are not accepted');
    polylinestyles[lcur].kmlcolor = getopacityhex(polylinestyles[lcur].lineopac) + color_html2kml(""+polylinestyles[lcur].color);
    polylinestyles[lcur].name = gob('polylineinput4').value;
    placemarks[plmcur].style = polylinestyles[lcur].name;
    placemarks[plmcur].stylecur = lcur;
    gob("stylenumberl").innerHTML = (lcur+1)+' ';
    if(polyShape) polyShape.setMap(null);
    preparePolyline();
    if(polyPoints.length > 0) {
        if(codeID == 1) logCode1();
        if(codeID == 2) logCode4();
    }else{
        alert("SAVED!");
    }
}
function polygonstyle(e) {
    if(e == 1) {
        createpolygonstyleobject();
        pcur++;
    }
    polygonstyles[pcur].color = gob('polygoninput1').value;
    polygonDecColorCur = color_hex2dec(polygonstyles[pcur].color);
    polygonstyles[pcur].lineopac = gob('polygoninput2').value;
    if(polygonstyles[pcur].lineopac<0 || polygonstyles[pcur].lineopac>1) return alert('Opacity must be between 0 and 1');
    polygonstyles[pcur].width = gob('polygoninput3').value;
    if(polygonstyles[pcur].width<0 || polygonstyles[pcur].width>20) return alert('Numbers below zero and above 20 are not accepted');
    polygonstyles[pcur].fill = gob('polygoninput4').value;
    polygonFillDecColorCur = color_hex2dec(polygonstyles[pcur].fill);
    polygonstyles[pcur].fillopac = gob('polygoninput5').value;
    if(polygonstyles[pcur].fillopac<0 || polygonstyles[pcur].fillopac>1) return alert('Opacity must be between 0 and 1');
    polygonstyles[pcur].kmlcolor = getopacityhex(polygonstyles[pcur].lineopac) + color_html2kml(""+polygonstyles[pcur].color);
    polygonstyles[pcur].kmlfill = getopacityhex(polygonstyles[pcur].fillopac) + color_html2kml(""+polygonstyles[pcur].fill);
    polygonstyles[pcur].name = gob('polygoninput6').value;
    placemarks[plmcur].style = polygonstyles[pcur].name;
    placemarks[plmcur].stylecur = pcur;
    gob("stylenumberp").innerHTML = (pcur+1)+' ';
    if(polyShape) polyShape.setMap(null);
    if(outerShape) outerShape.setMap(null);
    if(holePolyArray.length > 0) {
        drawpolywithhole();
        if(codeID == 1) logCode3();
        if(codeID == 2) logCode5();
    }
    if(holePolyArray.length == 0) {
        preparePolygon();
        if(polyPoints.length > 0) {
            if(codeID == 1) logCode2();
            if(codeID == 2) logCode4();
        }else{
            alert("SAVED!");
        }
    }
}

function circlestyle(e) {
    if(e == 1) {
        createcirclestyleobject();
        ccur++;
    }
    circlestyles[ccur].color = gob('circinput1').value;
    circlestyles[ccur].lineopac = gob('circinput2').value;
    if(circlestyles[ccur].lineopac<0 || circlestyles[ccur].lineopac>1) return alert('Opacity must be between 0 and 1');
    circlestyles[ccur].width = gob('circinput3').value;
    circlestyles[ccur].fill = gob('circinput4').value;
    circlestyles[ccur].fillopac = gob('circinput5').value;
    if(circlestyles[ccur].fillopac<0 || circlestyles[ccur].fillopac>1) return alert('Opacity must be between 0 and 1');
    circlestyles[ccur].name = gob('circinput6').value;
    placemarks[plmcur].style = circlestyles[ccur].name;
    placemarks[plmcur].stylecur = ccur;
    gob("stylenumberc").innerHTML = (ccur+1)+' ';
    if(circle) circle.setMap(null);
    activateCircle();
    if(radiusPoint) {
        drawCircle();
        logCode7();
    }else{
        alert("SAVED!");
    }
}
function markerstyle(e) {
    if(e == 1) {
        createmarkerstyleobject();
        mcur++;
    }
    markerstyles[mcur].name = gob('st1').value;
    markerstyles[mcur].icon = gob('st2').value;
    placemarks[plmcur].style = markerstyles[mcur].name;
    placemarks[plmcur].stylecur = mcur;
    gob("stylenumberm").innerHTML = (mcur+1)+' ';
    if(markerShape) {
        markerShape.setIcon(markerstyles[mcur].icon);
    }else{
        alert("SAVED!");
    }
}
function stepstyles(a) {
    if(toolID == 1) {
        if(a == -1) {
            if (lcur > 0) {
                lcur--;
                gob("stylenumberl").innerHTML = (lcur+1)+' ';
                styleoptions();
            }
        }
        if(a == 1){
            if (lcur < polylinestyles.length - 1) {
                lcur++;
                gob("stylenumberl").innerHTML = (lcur+1)+' ';
                styleoptions();
            }
        }
        placemarks[plmcur].style = polylinestyles[lcur].name;
        placemarks[plmcur].stylecur = lcur;
        if(polyShape) polyShape.setMap(null);
        preparePolyline();
        if(polyPoints.length) {
            if(codeID == 1) logCode1();
            if(codeID == 2) logCode4();
        }
    }
    if(toolID == 2 || toolID == 3) {
        if(a == -1) {
            if (pcur > 0) {
                pcur--;
                gob("stylenumberp").innerHTML = (pcur+1)+' ';
                styleoptions();
            }
        }
        if(a == 1){
            if (pcur < polygonstyles.length - 1) {
                pcur++;
                gob("stylenumberp").innerHTML = (pcur+1)+' ';
                styleoptions();
            }
        }
        placemarks[plmcur].style = polygonstyles[pcur].name;
        placemarks[plmcur].stylecur = pcur;
        if(polyShape) {
            polyShape.setMap(null);
            preparePolygon();
            if(polyPoints.length) {
                if(codeID == 1) logCode2();
                if(codeID == 2) logCode4();
            }
        }
        if(rectangle) {
            rectangle.setMap(null);
            activateRectangle();
            if(polyPoints.length) {
                if(codeID == 1) logCode2();
                if(codeID == 2) logCode4();
            }
        }
    }
    if(toolID == 4) {
        if(a == -1) {
            if (ccur > 0) {
                ccur--;
                gob("stylenumberc").innerHTML = (ccur+1)+' ';
                styleoptions();
            }
        }
        if(a == 1){
            if (ccur < circlestyles.length - 1) {
                ccur++;
                gob("stylenumberc").innerHTML = (ccur+1)+' ';
                styleoptions();
            }
        }
        placemarks[plmcur].style = circlestyles[ccur].name;
        placemarks[plmcur].stylecur = ccur;
        if(circle) circle.setMap(null);
        activateCircle();
        if(radiusPoint) {
            logCode7();
        }
    }
    if(toolID == 5) {
        if(a == -1) {
            if (mcur > 0) {
                mcur--;
                gob("stylenumberm").innerHTML = (mcur+1)+' ';
                styleoptions();
            }
        }
        if(a == 1){
            if (mcur < markerstyles.length - 1) {
                mcur++;
                gob("stylenumberm").innerHTML = (mcur+1)+' ';
                styleoptions();
            }
        }
        placemarks[plmcur].style = markerstyles[mcur].name;
        placemarks[plmcur].stylecur = mcur;
        if(markerShape) {
            markerShape.setIcon(markerstyles[mcur].icon);
            logCode9();
        }
    }
}
function docudetails(){
    gob("plm1").value = placemarks[plmcur].name;
    gob("plm2").value = placemarks[plmcur].desc;
    gob("plm3").value = placemarks[plmcur].tess;
    gob("plm4").value = placemarks[plmcur].alt;
    gob("doc1").value = docuname;
    gob("doc2").value = docudesc;
}
function savedocudetails(){
    docuname = gob("doc1").value;
    docudesc = gob("doc2").value;
    placemarks[plmcur].name = gob("plm1").value;
    placemarks[plmcur].desc = gob("plm2").value;
    placemarks[plmcur].tess = gob("plm3").value;
    placemarks[plmcur].alt = gob("plm4").value;
    if(placemarks[plmcur].poly == "pl") logCode1();
    if(placemarks[plmcur].poly == "pg") logCode2();
}
function mapzoom(){
    var mapZoom = map.getZoom();
    gob("myzoom").value = mapZoom;
}
function mapcenter(){
    var mapCenter = map.getCenter();
    var latLngStr = mapCenter.lat().toFixed(6) + ', ' + mapCenter.lng().toFixed(6);
    gob("centerofmap").value = latLngStr;
}
function showCodeintextarea(){
    if (notext === false){
        gob("presentcode").checked = false;
        notext = true;
    }else{
        gob("presentcode").checked = true;
        notext = false;
        if(polyPoints.length > 0){
            if(toolID==1) { // Polyline
                if(codeID==1) logCode1();
                if(codeID==2) logCode4();
            }
            if(toolID==2) { // Polygon
                if(adder!==0) { // with hole
                    adder = 0;
                    if(codeID == 1) logCode3();
                    if(codeID == 2) logCode5();
                }else{
                    if(codeID==1) logCode2();
                    if(codeID==2) logCode4();
                }
            }
            if(toolID==3) { // Rectangle
                if(codeID == 2) logCode6();
                if(codeID == 1) logCode2();
            }
            if(toolID==5) {  // Marker
                if(codeID == 1) logCode9();
            }
            if(toolID==6) { // Directions
                if(codeID == 1) logCode1a();
            }
        }
        if(toolID==4) { // Circle
            if(codeID == 2) logCode7();
        }
    }
}
// the copy part may not work with all web browsers
function copyTextarea(){
    gob('coords1').focus();
    gob('coords1').select();
    copiedTxt = document.selection.createRange();
    copiedTxt.execCommand("Copy");
}
function color_html2kml(color){
    var newcolor ="FFFFFF";
    if(color.length == 7) newcolor = color.substring(5,7)+color.substring(3,5)+color.substring(1,3);
    return newcolor;
}
function color_hex2dec(color) {
    var deccolor = "255,0,0";
    var dec1 = parseInt(color.substring(1,3),16);
    var dec2 = parseInt(color.substring(3,5),16);
    var dec3 = parseInt(color.substring(5,7),16);
    if(color.length == 7) deccolor = dec1+','+dec2+','+dec3;
    return deccolor;
}
function getopacityhex(opa){
    var hexopa = "66";
    if(opa == 0) hexopa = "00";
    if(opa == .0) hexopa = "00";
    if(opa >= .1) hexopa = "1A";
    if(opa >= .2) hexopa = "33";
    if(opa >= .3) hexopa = "4D";
    if(opa >= .4) hexopa = "66";
    if(opa >= .5) hexopa = "80";
    if(opa >= .6) hexopa = "9A";
    if(opa >= .7) hexopa = "B3";
    if(opa >= .8) hexopa = "CD";
    if(opa >= .9) hexopa = "E6";
    if(opa == 1.0) hexopa = "FF";
    if(opa == 1) hexopa = "FF";
    return hexopa;
}
function directionsintroduction() {
    gob('coords1').value = 'Ready for Directions. Create a route along roads with markers at chosen locations.\n'
            +'Click on the map, or enter an address and click "Search", to place a marker.\n'
            +'Lines will be drawn along roads from marker to marker.\n'
            +'Use "Delete Last Point" if you want to undo.\n'
            +'KML input may be done at any time for markers by clicking on them.\n'
            +'KML input for the line may be done by clicking on it after you have finished '
            +'drawing and clicked "Next shape".';
}
function markerintroduction() {
    gob('coords1').value = 'Ready for Marker. Click on the map, or enter an address and click "Search", to place a marker.\n'
            +'You may enter your content for the infowindow with "KML input" even if your code choice is Javascript.\n'
            +'Click "Next shape" before each additional marker.';
}
function polylineintroduction() {
    gob('coords1').value = 'Ready for Polyline. Click on the map. The code for the shape you create will be presented here.\n\n'
                        +'When finished with a shape, click Next shape and draw another shape, if you wish.\n'
                        +'\nIf you want to edit a saved polyline or polygon, click on it. Then click Edit lines.\n'
                        +'\nThe complete KML code for what you have created, is always available with Show KML.';
}
function polygonintroduction() {
    gob('coords1').value = 'Ready for Polygon. Click on the map. The code for the shape you create will be presented here. '
            +'The Maps API will automatically "close" any polygons by drawing a stroke connecting the last coordinate back to the '
            +'first coordinate for any given paths.\n'
            +'\nTo create a polygon with hole(-s), click "Hole" before you start the drawing.\n'
            +'\nWhen finished with a shape, click Next shape and draw another shape, if you wish.\n'
            +'\nIf you want to edit a saved polyline or polygon, click on it. Then click Edit lines.\n'
            +'\nThe complete KML code for what you have created, is always available with Show KML.';
}
function rectangleintroduction() {
    gob('coords1').value = 'Ready for Rectangle. Click two times on the map - first for the southwest and '+
            'then for the northeast corner. You may resize and move '+
            'the rectangle with the two draggable markers you then have.\n\n'+
            'The v3 Rectangle is a polygon. But in Javascript code mode an extra code for '+
            'polyline is presented here in the text area.';
}
function circleintroduction() {
    gob('coords1').value = 'Ready for Circle. Click for center. Then click for radius distance. '+
    'You may resize and move the circle with the two draggable markers you then have.\n\n'+
    'KML code is not available for Circle.';
}
//]]>
</script>

<style type="text/css">
* {margin:0;padding:0;border:0;outline:0}
BODY {
    font-family: "Trebuchet MS", Arial,Helvetica,Sans Serif;
    font-size: 10pt;
}
a  {
	text-decoration : none;
}
a:hover  {
	text-decoration: underline;
}
p {
    padding-bottom: 8px;
}
#coords1 {
    padding: 5px;
    font-weight: bold;
}
#presenter {
    opacity:0.7;
    filter:alpha(opacity=70);
    -ms-filter:”alpha(opacity=70)”;
    -moz-opacity:0.7;
    KHTMLOpacity: 0.7;
    MozOpacity: 0.7;
    -khtml-opacity:.70;
}
#buttonrow {
    position: absolute;
    top: 50px;
    left: 410px;
    width: 505px;
    height: 80px;
    background-color: #ffffff;
}
#presenter {
    position: absolute;
    top: 160px;
    left: 410px;
    width: 400px;
    height: 300px;
    border: 2px solid #ccc;
}
#header {
    position: absolute;
    top: 5px;
    left: 10px;
    width: 860px;
    height: 40px;
    background-color: #ffffff;
}
#map_canvas {
    position: absolute;
    top: 50px;
    left: 0px;
    width: 400px;
    height: 400px;
    background-color: #ffffff;
}
#polygonstuff {
    position: absolute;
    top: 400px;
    left: 5px;
    width: 70px;
    height: 60px;
    background-color: #ff0000;
    color: #ffffff;
    visibility: hidden;
}
#title {
    background-color: transparent;
    width: 160px;
    float: left;
}
.choice {
    background-color: transparent;
    width: 100px;
    float: left;
}
h1 {
    font-size:25px;
    font-weight: bold;
    color: #ff0000;
    padding-top: 4px;
    padding-left: 10px;
    padding-bottom: 11px;
}
.topbutton {
    padding-top: 10px;
    padding-bottom: 10px;
    padding-left: 10px;
    float: left;
}
#googlead {
    margin: 0 auto;
    width: 800px;
    padding-top: 5px;
}
.clear {
	clear:both;
}
#content {
    margin: 10px
}
#polylineoptions {
    position: absolute;
    background-color: #ffffff;
    top: 200px;
    left: 560px;
    width: 260px;
    height: 210px;
    border: 2px solid #337EB7;
    visibility: hidden;
}
#polygonoptions {
    position: absolute;
    background-color: #ffffff;
    top: 200px;
    left: 560px;
    width: 260px;
    height: 255px;
    border: 2px solid #337EB7;
    visibility: hidden;
}
#rectang {
    position: absolute;
    background-color: #ffffff;
    top: 200px;
    left: 560px;
    width: 260px;
    height: 260px;
    border: 2px solid #337EB7;
    visibility: hidden;
}
#circleoptions {
    position: absolute;
    background-color: #ffffff;
    top: 200px;
    left: 560px;
    width: 260px;
    height: 260px;
    border: 2px solid #337EB7;
    visibility: hidden;
}
#markeroptions {
    width: 850px;
    height: 310px;
    background-color: #ffffff;
    margin: 2px 2px 2px 2px;
    padding: 8px;
    border: 1px solid #aaa;
    position: absolute;
    top: 45px;
    left: 10px;
    z-index:99999;
    visibility:hidden;
}
#directionstyles {
    position: absolute;
    background-color: #ffffff;
    top: 200px;
    left: 560px;
    width: 260px;
    height: 210px;
    border: 2px solid #337EB7;
    visibility: hidden;
}
#iconimages {
    width: 430px;
    height: 300px;
    background-color: #ffffff;
    margin: 2px 2px 2px 2px;
    padding: 8px;
    float: left;
}
#stylestext {
    width: 485px;
    height: 280px;
    background-color: #ffffff;
    margin: 0px 0px 2px 2px;
    padding: 5px;
    float: left;
}
#toppers {
    width: 405px;
    height: 300px;
    background-color: #ffffff;
    margin: 2px 2px 2px 2px;
    padding: 8px;
    position: absolute;
    top: 165px;
    left: 90px;
    z-index:99999;
    visibility:hidden;
}
.styletitle {
    margin-left:5px;
    padding:0 3px 0 5px;
    font-weight:bold;
    background-color: #CCCCFF;
}
.closebutton {
    border:1px solid #000000;
    padding:1px 4px 1px 4px;
    margin:1px 3px 1px 0px;
    color:#ffffff;
    background-color: #337EB7;
}
.label {
	float: left;
	text-align: right;
	margin-right: 5px;
	width: 80px;
	padding-top: 5px;
}
.input {
    float: left;
	padding-left: 3px;
	width: 55px;
	margin: 3px 0px 0px 3px;
	border: 2px solid #ccc;
}
.inputlong {
    float: left;
	padding-left: 3px;
	width: 75px;
	margin: 3px 0px 0px 3px;
	border: 2px solid #ccc;
}
.oklink {
    margin-left: 25px;
    padding:0px 3px 0px 3px;
    background-color: #CCFF66;
    text-decoration: none;
}

</style>
</head>

<body id="body" onload="initmap()">

<div id="map_canvas"></div>

<div id="header">
    <div id="title">
        <h1>KML editor</h1>
        <span>Google Maps API v3 Tool</span>
    </div>
    <div class="choice">
        <form id="tools" style="float:left;text-align:right;padding:10px 0px 0px 10px;" action="./" method="post" onsubmit="return false">
        <select id="toolchoice" name="toolchoice" onchange="toolID=parseInt(this.options[this.selectedIndex].value);setTool();">
        <option selected="selected" value="1">Polyline</option>
        <option value="2">Polygon</option>
        <option value="3">Rectangle</option>
        <option value="4">Circle</option>
        <option value="5">Marker</option>
        <option value="6">Directions</option>
        </select>
        </form>
    </div>
    <div class="choice">
        <form id="codes" style="float:left;text-align:right;padding-top:10px;" action="./" method="post" onsubmit="return false">
        <select id="codechoice" name="codechoice" onchange="codeID=parseInt(this.options[this.selectedIndex].value);setCode();">
        <option selected="selected" value="1">KML</option>
        <option value="2">Javascript</option>
        </select>
        </form>
    </div>
    <div id="middlerow">
    <form style="float:left;text-align:right;padding-top:10px;" action="#">
    <select id="over" style="width:180px;" >
    <option>LngLat mousemove</option>
    <option selected="selected">LatLng mousemove</option>
    </select>
    <input type="button" onclick="mapcenter();" value="Mapcenter"/>
    <input type="text" style="width:140px; border: 1px solid #000000;" id="centerofmap" />
    Zoom level:
    <input type="text" size="5" name="myzoom" id="myzoom" value="15" style="width:15px; border: 1px solid #000000;" />
    </form>
    </div>
</div>

<div id="buttonrow">
    <div class="topbutton"><input type="button" onclick="nextshape();" value="Next shape"/></div>
    <div class="topbutton"><input type="button" onclick="clearMap();" value="Clear Map"/></div>
    <div class="topbutton"><input type="button" onclick="deleteLastPoint();" value="Delete Last Point"/></div>
    <div class="topbutton"><input type="button" onclick="styleoptions();" value="Style Options"/></div>
    <div class="topbutton"><input type="button" onclick="editlines();" value="Edit lines" id="EditButton"/></div>
    <div class="topbutton"><input type="button" onclick='docudetails();document.getElementById("toppers").style.visibility = "visible";' value="KML input"/></div>
    <div class="topbutton"><input type="button" onclick="showKML();" value="Show KML"/></div>
    <div class="topbutton"><input style="width:150px" type="button" onclick="copyTextarea();" value="Select and copy text"/></div>
    <form style="margin-right:5px" action="#" onsubmit="showAddress(this.address.value); return false">
    <input type="text" style="border: 1px solid #000000;" size="50" name="address" value="Karl Johans gate 37, 0162 Oslo, Norge" />
    <input type="submit" value="Search" />
    </form>
</div>

<div id="polygonstuff">
    <div><a style="padding-left:5px; color: #ffffff;" href="javascript:holecreator()">Hole</a></div>
    <div id="stepdiv" style="padding-left:5px">Step 0</div>
    <div><input id="multipleholes" type="button" onclick="nexthole();" value="Next hole"/></div>
</div>

<div id="presenter">
    <form style="margin-bottom:5px" action="#">
    &nbsp;&nbsp;&nbsp;Live code presentation in textarea (below):
    <input type="checkbox" name="showcodemode" id="presentcode" value="yes" onclick="showCodeintextarea();" checked="checked"/>
<textarea id="coords1" cols="46" rows="18">
Click on the map. The code for the shape you create will be presented here.
You may shift from Polyline to Polygon and vice/versa at any time when you draw.
When finished with a shape, click 'Next shape' and draw another shape, if you wish.
If you want to draw polygon with hole(-s), choose Polygon and follow the instructions.
Polyline and polygon may be edited by clicking 'Edit lines'.
If you want to edit a saved polyline or polygon (including Rectangle), click on it. Then click 'Edit lines'.
There are style options for everything you draw on the map.
The complete KML code for what you have created, is always available with Show KML.
Uncheck 'Live code presentation' for greater speed when you draw shapes with large amount of points.
Check it when you want to see the code.
</textarea>
    </form>
</div>

<div id="polylineoptions">
    <div style="padding-top:5px; margin-bottom:10px;">
        <div style="float:left;" class="styletitle">POLYLINE</div>
        <div style="float:right;"><a class="closebutton" href="javascript:closethis('polylineoptions')">X</a></div>
    </div>
    <div class="clear"></div>
    <div style="float:left; padding-left:5px; width:230px">
        <form id="style1" style="padding-bottom:1px;" action="./" method="post" onsubmit="return false">
        <div class="label">strokeColor</div>
        <input class="input" type="text" name="color" id="polylineinput1" />
        <div class="clear"></div>
        <div class="label">strokeOpacity</div>
        <input class="input" type="text" name="opacity" id="polylineinput2" />
        <div class="clear"></div>
        <div class="label">strokeWeight</div>
        <input class="input" type="text" name="weight" id="polylineinput3" />
        <div class="clear"></div>
        <div class="label">Style id</div>
        <input class="inputlong" type="text" name="styleid" id="polylineinput4" />
        </form>
    </div>
    <div class="clear"></div>
    <div>
        <a class="oklink" href="javascript:polylinestyle(0)">Click here to save style changes</a>
    </div>
    <div style="margin-top:5px">
        <a class="oklink" href="javascript:polylinestyle(1)">Click here to save as new style</a>
    </div>
    <div style="width:100%; text-align:center; margin-top:5px">
        <input type="button" class="buttons" name="backwards" id="backwards" value="Previous" onclick="stepstyles(-1);"/>
         Style <span id="stylenumberl">1 </span>
        <input type="button" class="buttons" name="forwards" id="forwards" value="Next" onclick="stepstyles(1);"/>
    </div>
</div>

<div id="polygonoptions">
    <div style="padding-top:5px; margin-bottom:10px;">
        <div style="float:left;" class="styletitle">POLYGON/RECTANGLE</div>
        <div style="float:right;"><a class="closebutton" href="javascript:closethis('polygonoptions')">X</a></div>
    </div>
    <div class="clear"></div>
    <div style="float:left; padding-left:5px; width:230px">
        <form id="style2" style="padding-bottom:1px;" action="./" method="post" onsubmit="return false">
        <div class="label">strokeColor</div>
        <input class="input" type="text" name="color" id="polygoninput1" />
        <div class="clear"></div>
        <div class="label">strokeOpacity</div>
        <input class="input" type="text" name="opacity" id="polygoninput2" />
        <div class="clear"></div>
        <div class="label">strokeWeight</div>
        <input class="input" type="text" name="weight" id="polygoninput3" />
        <div class="clear"></div>
        <div class="label">fillColor</div>
        <input class="input" type="text" name="fillcolor" id="polygoninput4" />
        <div class="clear"></div>
        <div class="label">fillOpacity</div>
        <input class="input" type="text" name="fillopacity" id="polygoninput5" />
        <div class="clear"></div>
        <div class="label">Style id</div>
        <input class="inputlong" type="text" name="styleid" id="polygoninput6" />
        </form>
    </div>
    <div class="clear"></div>
    <div>
        <a class="oklink" href="javascript:polygonstyle(0)">Click here to save style changes</a>
    </div>
    <div style="margin-top:5px">
        <a class="oklink" href="javascript:polygonstyle(1)">Click here to save as new style</a>
    </div>
    <div style="width:100%; text-align:center; margin-top:5px">
        <input type="button" class="buttons" name="backwards" id="backwards" value="Previous" onclick="stepstyles(-1);"/>
         Style <span id="stylenumberp">1 </span>
        <input type="button" class="buttons" name="forwards" id="forwards" value="Next" onclick="stepstyles(1);"/>
    </div>
</div>

<div id="circleoptions">
    <div style="padding-top:5px; margin-bottom:10px;">
        <div style="float:left;" class="styletitle">CIRCLE</div>
        <div style="float:right;"><a class="closebutton" href="javascript:closethis('circleoptions')">X</a></div>
    </div>
    <div class="clear"></div>
    <div style="float:left; padding-left:5px; width:250px">
        <form id="rect" style="padding-bottom:1px;" action="./" method="post" onsubmit="return false">
        <div class="label">strokeColor</div>
        <input class="input" type="text" name="color" id="circinput1" />
        <div class="clear"></div>
        <div class="label">strokeOpacity</div>
        <input class="input" type="text" name="opacity" id="circinput2" />
        <div class="clear"></div>
        <div class="label">strokeWeight</div>
        <input class="input" type="text" name="weight" id="circinput3" />
        <div class="clear"></div>
        <div class="label">fillColor</div>
        <input class="input" type="text" name="fillcolor" id="circinput4" />
        <div class="clear"></div>
        <div class="label">fillOpacity</div>
        <input class="input" type="text" name="fillopacity" id="circinput5" />
        <div class="clear"></div>
        <div class="label">Style id</div>
        <input class="inputlong" type="text" name="styleid" id="circinput6" />
        </form>
    </div>
    <div class="clear"></div>
    <div>
        <a class="oklink" href="javascript:circlestyle(0)">Click here to save style changes</a>
    </div>
    <div style="margin-top:5px">
        <a class="oklink" href="javascript:circlestyle(1)">Click here to save as new style</a>
    </div>
    <div style="width:100%; text-align:center; margin-top:5px">
        <input type="button" class="buttons" name="backwards" id="backwards" value="Previous" onclick="stepstyles(-1);"/>
         Style <span id="stylenumberc">1 </span>
        <input type="button" class="buttons" name="forwards" id="forwards" value="Next" onclick="stepstyles(1);"/>
    </div>
</div>

<div id="markeroptions">
    <div id="iconimages">
    <table>
        <tr>
        <td><img src="http://maps.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png" alt="" /></td>
        <td><img src="http://maps.google.com/intl/en_us/mapfiles/ms/micons/orange-dot.png" alt="" /></td>
        <td><img src="http://maps.google.com/intl/en_us/mapfiles/ms/micons/yellow-dot.png" alt="" /></td>
        <td><img src="http://maps.google.com/intl/en_us/mapfiles/ms/micons/green-dot.png" alt="" /></td>
        <td><img src="http://maps.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png" alt="" /></td>
        <td><img src="http://maps.google.com/intl/en_us/mapfiles/ms/micons/purple-dot.png" alt="" /></td>
        <td><img src="http://maps.google.com/intl/en_us/mapfiles/ms/micons/red.png" alt="" /></td>
        <td><img src="http://maps.google.com/intl/en_us/mapfiles/ms/micons/orange.png" alt="" /></td>
        <td><img src="http://maps.google.com/intl/en_us/mapfiles/ms/micons/yellow.png" alt="" /></td>
        <td><img src="http://maps.google.com/intl/en_us/mapfiles/ms/micons/green.png" alt="" /></td>
        <td><img src="http://maps.google.com/intl/en_us/mapfiles/ms/micons/blue.png" alt="" /></td>
        <td><img src="http://maps.google.com/intl/en_us/mapfiles/ms/micons/purple.png" alt="" /></td>
        </tr>
        <tr>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/intl/en_us/mapfiles/ms/micons/orange-dot.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/intl/en_us/mapfiles/ms/micons/yellow-dot.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/intl/en_us/mapfiles/ms/micons/green-dot.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/intl/en_us/mapfiles/ms/micons/purple-dot.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/intl/en_us/mapfiles/ms/micons/red.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/intl/en_us/mapfiles/ms/micons/orange.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/intl/en_us/mapfiles/ms/micons/yellow.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/intl/en_us/mapfiles/ms/micons/green.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/intl/en_us/mapfiles/ms/micons/blue.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/intl/en_us/mapfiles/ms/micons/purple.png");' /></td>
        </tr>
        <tr>
        <td><img src="http://maps.google.com/mapfiles/dd-start.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/dd-end.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/markerA.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/marker_orangeA.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/marker_yellowA.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/marker_greenA.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/marker_brownA.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/marker_purpleA.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/marker_blackA.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/marker_greyA.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/marker_whiteA.png" alt="" /></td>
        </tr>
        <tr>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/dd-start.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/dd-end.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/markerA.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/marker_orangeA.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/marker_yellowA.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/marker_greenA.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/marker_brownA.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/marker_purpleA.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/marker_blackA.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/marker_greyA.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/marker_whiteA.png");' /></td>
        </tr>
    </table>
    <table>
        <tr>
        <td><img src="http://labs.google.com/ridefinder/images/mm_20_red.png" alt="" /></td>
        <td><img src="http://labs.google.com/ridefinder/images/mm_20_orange.png" alt="" /></td>
        <td><img src="http://labs.google.com/ridefinder/images/mm_20_yellow.png" alt="" /></td>
        <td><img src="http://labs.google.com/ridefinder/images/mm_20_green.png" alt="" /></td>
        <td><img src="http://labs.google.com/ridefinder/images/mm_20_brown.png" alt="" /></td>
        <td><img src="http://labs.google.com/ridefinder/images/mm_20_blue.png" alt="" /></td>
        <td><img src="http://labs.google.com/ridefinder/images/mm_20_purple.png" alt="" /></td>
        <td><img src="http://labs.google.com/ridefinder/images/mm_20_black.png" alt="" /></td>
        <td><img src="http://labs.google.com/ridefinder/images/mm_20_gray.png" alt="" /></td>
        <td><img src="http://labs.google.com/ridefinder/images/mm_20_white.png" alt="" /></td>
        </tr>
        <tr>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://labs.google.com/ridefinder/images/mm_20_red.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://labs.google.com/ridefinder/images/mm_20_orange.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://labs.google.com/ridefinder/images/mm_20_yellow.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://labs.google.com/ridefinder/images/mm_20_green.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://labs.google.com/ridefinder/images/mm_20_brown.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://labs.google.com/ridefinder/images/mm_20_blue.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://labs.google.com/ridefinder/images/mm_20_purple.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://labs.google.com/ridefinder/images/mm_20_black.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://labs.google.com/ridefinder/images/mm_20_gray.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://labs.google.com/ridefinder/images/mm_20_white.png");' /></td>
        </tr>
    </table>
    <table>
        <tr>
        <td><img src="http://maps.google.com/mapfiles/ms/micons/bar.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/ms/micons/restaurant.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/ms/micons/lodging.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/ms/micons/golfer.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/ms/micons/sportvenue.png" alt="" /></td>
        <td><img src="http://maps.google.com/mapfiles/ms/micons/plane.png" alt="" /></td>
        <td><img src="images/square.png" alt="" /></td>
        </tr>
        <tr>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/ms/micons/bar.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/ms/micons/restaurant.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/ms/micons/lodging.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/ms/micons/golfer.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/ms/micons/sportvenue.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("http://maps.google.com/mapfiles/ms/micons/plane.png");' /></td>
        <td><input type="button" name="button" value="Use" onclick='iconoptions("images/square.png");' /></td>
        </tr>
    </table>
    </div>
    <div id="stylestext">
        <form action="#" style="padding-top:3px; margin-top:-5px">
            <div style="float:left;" class="styletitle">MARKER</div>
            <div style="float:right;"><a class="closebutton" href="javascript:closethis('markeroptions')">X</a></div>
            <div class="clear"></div>
            <div><br />
                &lt;Style id =<input type="text" id="st1" value="markerstyle" style="width:100px; border: 2px solid #ccc;" /><br />
                &nbsp;&nbsp;&lt;Icon&gt;&lt;href&gt;
                <input type="text" id="st2" value="http://maps.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png" style="width:380px; border: 2px solid #ccc;" /><br />
                <span id="currenticon" style="height: 35px"><img src="http://maps.google.com/intl/en_us/mapfiles/ms/micons/orange-dot.png" alt="" /></span>
                Use default icon, or choose icon from the chart
                <input style="width:120px; margin-left:8px" type="button" name="button" value="Back to default icon" onclick='iconoptions("http://maps.google.com/intl/en_us/mapfiles/ms/micons/orange-dot.png");' />
                <br /><br />
            </div>
            <div style="margin-top:5px">
                <a class="oklink" href="javascript:markerstyle(0)">Click here to save style changes</a>
                <a class="oklink" href="javascript:markerstyle(1)">Click here to save as new style</a>
            </div>
            <div style="width:100%; text-align:center; margin-top:5px">
                <input type="button" class="buttons" name="backwards" value="Previous" onclick="stepstyles(-1);"/>
                 Style <span id="stylenumberm">1 </span>
                <input type="button" class="buttons" name="forwards" value="Next" onclick="stepstyles(1);"/>
            </div>
        </form>
    </div>
</div>
<div id="directionstyles">
    <div style="float:right;"><a class="closebutton" href="javascript:closethis('directionstyles')">X</a></div>
    <div class="clear"></div>
    <div style="width:100%; text-align:center; padding-top:40px">
    <input type="button" class="buttons" name="markerbutton" value="Markerstyles" onclick="toolID=5;styleoptions();"/>
    </div>
    <div style="width:100%; text-align:center; padding-top:15px">
    <input type="button" class="buttons" name="linebutton" value="Linestyles" onclick="toolID=1;styleoptions();"/>
    </div>
</div>
<div id="toppers">
    <form action="#">
    &lt;Document&gt;<br />
    &nbsp;&nbsp;&lt;name&gt;<input type="text" id="doc1" value="My document" style="width:345px; border:2px solid #ccc;" /><br />
    &nbsp;&nbsp;&lt;description&gt;<input type="text" id="doc2" value="Content" style="width:312px; border:2px solid #ccc;" /><br /><br />
    &lt;Placemark&gt;<br />
    &nbsp;&nbsp;&lt;name&gt;<input type="text" id="plm1" value="NAME" style="width:345px; border:2px solid #ccc;" /><br />
    &nbsp;&nbsp;&lt;description&gt;<input type="text" id="plm2" value="YES" style="width:312px; border:2px solid #ccc;" /><br />
    &nbsp;&nbsp;&lt;styleURL&gt;<em> current style</em><br />
    &nbsp;&nbsp;&lt;tessellate&gt;<input type="text" id="plm3" value="1" style="width:20px; border:2px solid #ccc;" />&lt;/tessellate&gt;
    &lt;altitudemode&gt;<input type="text" id="plm4" value="clampToGround" style="width:100px; border:2px solid #ccc;" /><br /><br />
    You may create or change styles with the "Style Options" button.<br />
    You may press it now or anytime.<br /><br />
    <input type="button" name="docu" id="docu" value="Save" onclick='savedocudetails();document.getElementById("toppers").style.visibility = "hidden";'/>
    <input type="button" value="Close" onclick='document.getElementById("toppers").value="";document.getElementById("toppers").style.visibility = "hidden";'/>
    </form>
</div>
<div class="clear"></div>
<div style="height:580px"></div>
<div id="googlead">
<script type="text/javascript"><!--
google_ad_client = "pub-8191813783307951";
/* v3, 728x90, created 10/27/09 */
google_ad_slot = "4900980609";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
</div>

<div id = "content">
<p>
This is a drawing tool for polyline, polygon, polygon with holes, rectangle, circle, marker(icon), direction(route, path).
This application uses the Google Maps API Version 3 (V3). It has all the features of Google Maps MyMaps and
has direct access to the code for the shapes (overlays) you create. It should be a full-fledged alternative.
This application should now also serve as a mature alternative to my online V2 tool
<a href="http://www.birdtheme.org/useful/googletool.html">Digitizer tool</a>
</p>
<p>Code will be presented in the textarea. You may choose to see KML or Google Javascript V3.
</p>
</div>
<div>
<a href="http://www.birdtheme.org/">www.birdtheme.org</a>
</div>
<div id="directionsPanel" style="margin:20px;background-color:#FFEE77;"></div>
<p></p>
</body>

</html>
