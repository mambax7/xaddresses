<?php

/**
* simpleGMapAPI | Uses Google Maps API v3 to create customizable maps
*                 that can be embedded on your website
*                 Heiko Holtkamp, 2010
*
*                 (simpleGMapAPI is based on phoogle)
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA 
*
*
* simpleGMapAPI
* Uses Google Maps API to create customizable maps
* that can be embedded on your website
*
* @class        simpleGMapAPI
* @author       Heiko Holtkamp <heiko@rvs.uni-bielefeld.de>
* @version      0.1.3
* @copyright    2010 HH
*/

/*
 * include class for the geocoder
 */
require_once('simpleGMapGeocoder.php');

class simpleGMapAPI {

/**
* mapName : string
* Holds map name
*/
private $mapName = array();

/**
* mapMarkers : array
* Holds data (coords etc.) of Markers
*/
private $mapMarkers = array();

/**
* mapCircles : array
* Holds data (coords etc.) of Circles
*/
private $mapCircles = array();

/**
* mapRectangles : array
* Holds data (coords etc.) of Rectangles
*/
private $mapRectangles = array();

/**
* mapKmlLayers : array
* Holds data (coords etc.) of Kml/GeoRSS Layers
*/
private $mapKmlLayers = array();

/**
* mapWidth
* width of the Google Map, in pixels
*/
private $mapWidth = 400;

/**
* mapHeight
* height of the Google Map, in pixels
*/
private $mapHeight = 400;

/**
* mapBackgroundColor
* string : color for the map background
*/
private $mapBackgroundColor = "";

/**
* apiSensor : boolean
* True/False wether the device has a sensor or not
*/
private $apiSensor = false;

/**
* mapDraggable : boolean
* True/False wether the map is draggable or not
*/
private $mapDraggable = true;

/**
* mapType
* string : can be 'ROADMAP', 'SATELLITE', 'HYBRID' or 'TERRAIN'
* display's either a (road)map, satellite, hybrid or terrain view, (road)map by default
*/
private $mapType = 'ROADMAP';

/**
* zoomLevel
* int : 0 - 19
* set's the initial zoom level of the map (0 is fully zoomed out and 19 is fully zoomed in)
*/
private $zoomLevel = 6;

/**
* showDefaultUI
* True/False whether to show the deault UI controls or not
*/
private $showDefaultUI = true;

/**
* showMapTypeControl
* True/False whether to show map type control or not
*/
private $showMapTypeControl = true;

/**
* mapTypeControlStyle
* string : can be 'HORIZONTAL_BAR', 'DROPDOWN_MENU' or 'DEFAULT'
* Style of the map type control
*/
private $mapTypeControlStyle = 'DEFAULT';

/**
* showNavigationControl
* True/False whether to show navigation control or not
*/
private $showNavigationControl = true;

/**
* navigationControlStyle
* string : can be 'ANDROID', 'DEFAULT', 'SMALL' or 'ZOOM_PAN'
* Style of the navigation control
*/
private $navigationControlStyle = 'DEFAULT';

/**
* showScaleControl
* True/False whether to show scale control or not
*/
private $showScaleControl = true;

/**
* showStreetViewControl
* True/False whether to show StreetView control or not
*/
private $showStreetViewControl = true;

/**
* enableScrollwheelZoom
* True/False whether the scrollwhell zoom is enabled or not
*/
private $enableScrollwheelZoom = true;

/**
* enableDoubleClickZoom
* True/False whether doubleclick zoom zoom is enabled or not
*/
private $enableDoubleClickZoom = true;

/**
* infoWindowBehaviour
* string : can be 'MULTIPLE', 'SINGLE', 'CLOSE_ON_MAPCLICK' or 'SINGLE_CLOSE_ON_MAPCLICK'
* Behavious of InfoWindow overlays
*/
private $infoWindowBehaviour = 'MULTIPLE';

/**
* infoWindowTrigger
* string : can be 'CLICK' OR 'MOUSEOVER'
* Determines if InfoWindow is shown with a click or by mouseover
*/
private $infoWindowTrigger = 'CLICK';

/**
 * maximum longitude of all markers
 * 
 * @var float
 */
private $maxLng = -1000000;

/**
 * minimum longitude of all markers
 *
 * @var float
 */
private $minLng = 1000000;

/**
 * max latitude
 *
 * @var float
 */
private $maxLat = -1000000;

/**
 * min latitude
 *
 * @var float
 */
private $minLat = 1000000;

/**
 * map center latitude (horizontal)
 * calculated automatically as markers
 * are added to the map.
 *
 * @var float
 */
private $centerLat = null;

/**
 * map center longitude (vertical)
 * calculated automatically as markers
 * are added to the map.
 *
 * @var float
 */
private $centerLng = null;


/**********
 *** START OF FUNCTION BLOCK
 **********/

/**
* @function     __construct
* @description  constructor
* @param    $sensor : boolean
*/
function __construct($sensor = false) {
    $this->setSensor($sensor);
    //$code = md5(uniqid(rand(), true));
    //if ($length != "") return substr($code, 0, $length);
    //else return $code;
    $this->mapName = md5(uniqid(rand(), true));
}

/**
* @function     setMapName
* @param        $NapName : string
* @returns      nothing
* @description  Tells the v3 API the name for div and for map
*/
function setMapName($mapName) {
    $this->mapName = $mapName;
}

/**
* @function     getMapName
* @param        nothing
* @returns      map name: string
* @description  Return the name for div and for map
*/
function getMapName() {
    return $this->mapName;
}

/**
* @function     setSensor
* @param        $sensor : boolean
* @returns      nothing
* @description  Tells the v3 API wether the device has a sensor or not
*/
function setSensor($sensor = false) {
    $this->apiSensor = $sensor;
}

/**
* @function     setWidth
* @param        $width : int
* @returns      nothing
* @description  Sets the width of the map to be displayed
*/
function setWidth($width) {
    if ($width <= 0) { $width = $this->mapWidth; }
    $this->mapWidth = $width;
}

/**
* @function     setHeight
* @param        $height : int
* @returns      nothing
* @description  Sets the height of the map to be displayed
*/
function setHeight($height) {
    if ($height <= 0) { $height = $this->mapHeight; }
    $this->mapHeight = $height;
}

/**
* @function     setBackgroundColor
* @param        $color : string
* @returns      nothing
* @description  Sets the background color of the map
*/
function setBackgroundColor($color = "") {
    $this->mapBackgroundColor = $color;
}

/**
* @function     setZoomLevel
* @param        $zoom : int (0 - 19)
* @returns      nothing
* @description  Sets the zoom level of the map (0 is fully zoomed out and 19 is fully zoomed in)
*/
function setZoomLevel($zoom) {
    if (($zoom <= 0) OR ($zoom > 19)) { $zoom = $this->zoomLevel; }
    $this->zoomLevel = $zoom;
}

/**
* @function     setMapType
* @param        $mapType : string (can be 'ROADMAP', 'SATELLITE', 'HYBRID' or 'TERRAIN')
* @returns      nothing
* @description  Sets the type of the map to be displayed, either a (road)map, satellite, hybrid or terrain view; (road)map by default
*/
function setMapType($mapType) {
    switch ($mapType) {
        case 'SATELLITE' :
        case 'HYBRID' :
        case 'TERRAIN' :
            $this->mapType = $mapType;
            break;
        default :
            $this->mapType = 'ROADMAP';
            break;        
    }
}

/**
* @function     setMapDraggable
* @param        $draggable : boolean
* @returns      nothing
* @description  Sets wether the map is draggable or not
*/
function setMapDraggable($draggable = true) {
    $this->mapDraggable = $draggable;
}

/**
* @function     setInfoWindowBehaviour
* @param        $infoWindowBehaviour : string (can be 'MULTIPLE', 'SINGLE' or 'CLOSE_ON_MAPCLICK')
* @returns      nothing
* @description  Sets the behaviour of InfoWindow overlays, either multiple or single windows are displayed
*/
function setInfoWindowBehaviour($infoWindowBehaviour) {
    switch ($infoWindowBehaviour) {
        case 'MULTIPLE' :
        case 'SINGLE' :
        case 'CLOSE_ON_MAPCLICK' :
        case 'SINGLE_CLOSE_ON_MAPCLICK' :
            $this->infoWindowBehaviour = $infoWindowBehaviour;
            break;
        default :
            $this->infoWindowBehaviour = 'MULTIPLE'; // default behaviour of Google Maps V3
            break;        
    }
}

/**
* @function     setInfoWindowTrigger
* @param        $infoWindowTrigger : string : can be 'CLICK' OR 'ONMOUSEOVER'
* @returns      nothing
* @description  Determines if InfoWindow is shown with a click or by mouseover
*/
function setInfoWindowTrigger($infoWindowTrigger) {
    switch ($infoWindowTrigger) {
        case 'MOUSEOVER' :
            $this->infoWindowTrigger = $infoWindowTrigger;
            break;
        default :
            $this->infoWindowTrigger = 'CLICK';
            break;        
    }
}

/**
* @function     showDefaultUI
* @param        $control : boolean
* @returns      nothing
* @description  Tells the v3 API wether to show the default UI (its behaviour) or not 
*/
function showDefaultUI($control = true) {
    $this->showDefaultUI = $control;
}

/**
* @function     showMapTypeControl
* @param        $control : boolean
* @param        $style : string (can be 'HORIZONTAL_BAR', 'DROPDOWN_MENU' or 'DEFAULT')
* @returns      nothing
* @description  Tells the v3 API wether to show the map type control or not
*/
function showMapTypeControl($control = true, $style ) {
    $this->showMapTypeControl = $control;
    
    switch ( $style ) {
        case 'HORIZONTAL_BAR' :
        case 'DROPDOWN_MENU' :
            $this->mapTypeControlStyle = $style;
            break;
        default :
            $this->mapTypeControlStyle = 'DEFAULT';
            break;    
    }
}

/**
* @function     showNavigationControl
* @param        $control : boolean
* @param        $style : string (can be 'ANDROID', 'DEFAULT', 'SMALL' or 'ZOOM_PAN')
* @returns      nothing
* @description  Tells the v3 API wether to show the navigation control or not
*/
function showNavigationControl($control = true, $style) {
    $this->showNavigationControl = $control;
    switch ( $style ) {
        case 'ANDROID' :
        case 'SMALL' :
        case 'ZOOM_PAN' :
            $this->navigationControlStyle = $style;
            break;
        default :
            $this->navigationTypeControlStyle = 'DEFAULT';
            break;    
    }
}

/**
* @function     showScaleControl
* @param        $control : boolean
* @returns      nothing
* @description  Tells the v3 API wether to show the scale control or not
*/
function showScaleControl($control = true) {
    $this->showScaleControl = $control;
}

/**
* @function     showStreetViewControl
* @param        $control : boolean
* @returns      nothing
* @description  Tells the v3 API wether to show the StreetView control or not
*/
function showStreetViewControl($control = true) {
    $this->showStreetViewControl = $control;
}

/**
* @function     setScrollwheelZoom
* @param        $swzoom : boolean
* @returns      nothing
* @description  Sets wether scrollwheel zoom is enabled or not
*/
function setScrollwheelZoom($swzoom = true) {
    $this->enableScrollwheelZoom = $swzoom;
}

/**
* @function     setDoubleClickZoom
* @param        $dczoom : boolean
* @returns      nothing
* @description  Sets wether doubleclick zoom is enabled or not
*/
function setDoubleclickZoom($dczoom = true) {
    $this->enableDoubleClickZoom = $dczoom;
}

/**
 * @function     adjustCenterCoords
 * 
 * @param        $lng the map longitude : string
 * @param        $lat the map latitude  : string
 * @description  adjust map center coordinates by the given lat/lon point
 */
private function adjustCenterCoords($lat, $lng) {
    if ( (strlen((string)$lat) != 0) AND (strlen((string)$lng) != 0) ) {
        $this->maxLat = (float) max($lat, $this->maxLat);
        $this->minLat = (float) min($lat, $this->minLat);
        $this->maxLng = (float) max($lng, $this->maxLng);
        $this->minLng = (float) min($lng, $this->minLng);
    
        $this->centerLng = (float) ($this->minLng + $this->maxLng) / 2;
        $this->centerLat = (float) ($this->minLat + $this->maxLat) / 2;
    }
}

/**
* @function     addMarker
* @param        $lat : string (latitude)
*               $lng : string (longitude)
*               $tooltip : string (tooltip text)
*               $info : Message to be displayed in an InfoWindow
*               $iconURL : URL to an icon to be displayed instead of the default icon
*               (see for example http://code.google.com/p/google-maps-icons/)
*               $clickable : boolean (true if the marker should be clickable)
* @description  Add's a Marker to be displayed on the Google Map using latitude/longitude
*/
function addMarker($lat, $lng, $tooltip="", $info="", $iconURL="", $clickable=true) {
    $count = count($this->mapMarkers);
    $this->mapMarkers[$count]['lat']     = $lat;
    $this->mapMarkers[$count]['lng']     = $lng;
    $this->mapMarkers[$count]['tooltip'] = $tooltip;
    $this->mapMarkers[$count]['info']    = $info;
    $this->mapMarkers[$count]['iconURL'] = $iconURL;
    $this->mapMarkers[$count]['clickable'] = $clickable;
    
    $this->adjustCenterCoords($lat, $lng);
}

/**
* @function     addMarkerByAddress
* @param        $lat : string (latitude)
*               $lng : string (longitude)
*               $tooltip : string (tooltip text)
*               $info : Message to be displayed in an InfoWindow
*               $iconURL : URL to an icon to be displayed instead of the default icon
*               (see for example http://code.google.com/p/google-maps-icons/)
*               $clickable : boolean (true if the marker should be clickable)
*               @description  Add's a Marker to be displayed on the Google Map using latitude/longitude
*/
function addMarkerByAddress($address, $tooltip="", $info="", $iconURL="", $clickable=true) {
    $geoCoder = new simpleGMapGeocoder();
    $result = array();
    
    if (!is_string($address)) {
    die("All Addresses must be passed as a string");
    }
    
    $result = $geoCoder->getGeoCoords($address);
    
    if ( $result['status'] == "OK" ) {
        $count = count($this->mapMarkers);
        $this->mapMarkers[$count]['lat']     = $result['lat'];
        $this->mapMarkers[$count]['lng']     = $result['lng'];
        $this->mapMarkers[$count]['tooltip'] = $tooltip;
        $this->mapMarkers[$count]['info']    = $info;
        $this->mapMarkers[$count]['iconURL'] = $iconURL;
        $this->mapMarkers[$count]['clickable'] = $clickable;        

        $this->adjustCenterCoords($result['lat'], $result['lng']);
    }
}

/**
* @function     addCircle
* @param        $lat : string (latitude)
*               $lng : string (longitude)
*               $rad : string (radius of circle in meters)
*               $info : Message to be displayed in an InfoWindow
*               $options : array (options like stroke color etc. for the circle)
* @description  Add's an circle to be displayed on the Google Map using latitude/longitude and radius
*/
function addCircle($lat, $lng, $rad, $info="", $options=array()) {
    $count = count($this->mapCircles);
    $this->mapCircles[$count]['lat']  = $lat;
    $this->mapCircles[$count]['lng']  = $lng;
    $this->mapCircles[$count]['rad']  = $rad;
    $this->mapCircles[$count]['info'] = $info;
    
    /* set options */
    if ( sizeof($options) != 0 ) {
        $this->mapCircles[$count]['fillColor']     = $options['fillColor'];
        $this->mapCircles[$count]['fillOpacity']   = $options['fillOpacity'];
        $this->mapCircles[$count]['strokeColor']   = $options['strokeColor'];
        $this->mapCircles[$count]['strokeOpacity'] = $options['strokeOpacity'];
        $this->mapCircles[$count]['strokeWeight']  = $options['strokeWeight'];
        
        if ( $options['clickable'] == "" OR $options['clickable'] == false ) {
            $this->mapCircles[$count]['clickable'] = false;
        } else {
            $this->mapCircles[$count]['clickable'] = true;
        }
    }
    $this->adjustCenterCoords($lat, $lng);
}

/**
* @function     addRectangle
* @param        $lat1 : string (latitude sw corner)
*               $lng1 : string (longitude sw corner)
*               $lat2 : string (latitude ne corner)
*               $lng2 : string (longitude ne corner)
*               $info : Message to be displayed in an InfoWindow
*               $options : array (options like stroke color etc. for the rectangle)
* @description  Add's a rectangle to be displayed on the Google Map using latitude/longitude for soutwest and northeast corner
*/
function addRectangle($lat1, $lng1, $lat2, $lng2, $info="", $options=array()) {
    $count = count($this->mapRectangles);
    $this->mapRectangles[$count]['lat1'] = $lat1;
    $this->mapRectangles[$count]['lng1'] = $lng1;
    $this->mapRectangles[$count]['lat2'] = $lat2;
    $this->mapRectangles[$count]['lng2'] = $lng2;
    $this->mapRectangles[$count]['info'] = $info;
    
    /* set options */
    if ( sizeof($options) != 0 ) {
        $this->mapRectangles[$count]['fillColor']     = $options['fillColor'];
        $this->mapRectangles[$count]['fillOpacity']   = $options['fillOpacity'];
        $this->mapRectangles[$count]['strokeColor']   = $options['strokeColor'];
        $this->mapRectangles[$count]['strokeOpacity'] = $options['strokeOpacity'];
        $this->mapRectangles[$count]['strokeWeight']  = $options['strokeWeight'];
        
        if ( $options['clickable'] == "" OR $options['clickable'] == false ) {
            $this->mapRectangles[$count]['clickable'] = false;
        } else {
            $this->mapRectangles[$count]['clickable'] = true;
        }
    }
    $this->adjustCenterCoords($lat1, $lng1);
    $this->adjustCenterCoords($lat2, $lng2);    
}

/**
* @function     addRectangle
* @param        $url : string (url of Kml/GeoRSS file)
*               $options : array (options like clickable, preserveViewport, suppressInfoWindows)
* @description  Add's a kml layer
*/
function addKmlLayer($url, $options=array()) {
    $count = count($this->mapKmlLayers);
    $this->mapKmlLayers[$count]['url'] = $url;

    /* set options */
    if ( sizeof($options) != 0 ) {
        if ( $options['clickable'] == "" OR $options['clickable'] == false ) {
            $this->mapKmlLayers[$count]['clickable'] = false;
        } else {
            $this->mapKmlLayers[$count]['clickable'] = true;
        }
        if ( $options['preserveViewport'] == "" OR $options['preserveViewport'] == false ) {
            $this->mapKmlLayers[$count]['preserveViewport'] = false;
        } else {
            $this->mapKmlLayers[$count]['preserveViewport'] = true;
        }
        if ( $options['suppressInfoWindows'] == "" OR $options['suppressInfoWindows'] == false ) {
            $this->mapKmlLayers[$count]['suppressInfoWindows'] = false;
        } else {
            $this->mapKmlLayers[$count]['suppressInfoWindows'] = true;
        }
    }
//    $this->adjustCenterCoords($lat1, $lng1);
//    $this->adjustCenterCoords($lat2, $lng2);
}

/**
* @function     calculateDistance
* @param        $lat1 : string (latitude location 1)
*               $lng1: string (longitude location 1)
*               $lat2 : string (latitude location 2)
*               $lng2: string (longitude location 2)
*               $unit : km (killometers), m (miles), n (nautical miles), i (inch)
* @description  calculates distance between two locations in given unit (default kilometers)
*/
function calculateDistance($lat1, $lng1, $lat2, $lng2, $unit="km") {
    $radius = 6371; // mean radius of the earth in kilometers
    $lat1 = (float)$lat1;
    $lat2 = (float)$lat2;
    $lng1 = (float)$lng1;
    $lng2 = (float)$lng2;
    
    // calculation of distance in km using Great Circle Distance Formula
    $dist = $radius *
            acos( sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
                  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lng2) - deg2rad($lng1)) );
    
    switch ( strtolower($unit) ) {
        case 'm' :     // miles
            $dist = $dist / 1.609;
            break;
        case 'n' :     // nautical miles
            $dist = $dist / 1.852;
            break;
        case 'i' :     // inch
            $dist = $dist * 39370;
            break;
    }
    
    return $dist;
}



/**
* @function     printGMapJS
* 
* @param        $output output if true : boolean
* @returns      nothing
* @description  Adds the necessary Javascript for the Google Map API v3
*               (should be called in between the html <head></head> tags)
*/
function printGMapsJS($output = true) {
    static $isGoogleMapJsLoaded = false;
    $ret ='';
    $this->apiSensor ? $_sensor = "true" : $_sensor = "false";
    if(!$isGoogleMapJsLoaded ) {
        $isGoogleMapJsLoaded = true;
        if ( !isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme']) ) {
            $ret.= "\n<!-- Include Google Maps JS -->";
            $ret.= "\n<script src=\"http://maps.google.com/maps/api/js?sensor=$_sensor\" type=\"text/javascript\"></script>\n";
        } else {
            $GLOBALS['xoTheme']->addScript('http://maps.google.com/maps/api/js?sensor=false');
        }
    }
    if ($output) echo $ret;
    return $ret;
}

/**
* @function     showMap
* @param        $zoomToBounds : boolean
*               $output : boolean
* @description  Displays the Google Map on the page
*/
function showMap($zoomToBounds = true, $output = true) {
    $this->showDefaultUI ? $_disableDefaultUI = "false" : $_disableDefaultUI = "true";
    $this->showMapTypeControl ? $_mapTypeControl = "true" : $_mapTypeControl = "false";
    $this->showNavigationControl ? $_navigationControl = "true" : $_navigationControl = "false";
    $this->showScaleControl ? $_scaleControl = "true" : $_scaleControl = "false";
    $this->showStreetViewControl ? $_streetViewControl = "true" : $_streetViewControl = "false";
    $this->mapDraggable ? $_mapDraggable = "true" : $_mapDraggable = "false";
    $this->enableScrollwheelZoom ? $_scrollwheelZoom = "true" : $_scrollwheelZoom = "false";
    $this->enableDoubleClickZoom ? $_disableDoubleClickZoom = "false" : $_disableDoubleClickZoom = "true";

    // just set the infoWindowTrigger to lower case so we can use it direct as a string 
    $_infowindowtrigger = strtolower($this->infoWindowTrigger);

    $ret = "";
    // create div for the map canvas
    $ret.= "\n<!-- DIV container for the map -->";
    $ret.= "\n<div id=\"" . $this->mapName ."\" style=\"width: " . $this->mapWidth . "px; height: " . $this->mapHeight . "px;\">\n</div>\n";
        
    // create JS to display the map
    $ret.= "\n<!-- Display the Google Map -->";
    $ret.= "\n<script type=\"text/javascript\">\n";
    $ret.= "var currentInfoWindow = null;\n";
    $ret.= "var bounds = new google.maps.LatLngBounds();\n";
    $ret.= "var latlng = new google.maps.LatLng(" . $this->centerLat . ", " . $this->centerLng . ");\n";
    $ret.= "var options = {\n";
    $ret.= "\tzoom: " . $this->zoomLevel . ",\n";
    $ret.= "\tcenter: latlng,\n";
    $ret.= "\tmapTypeId: google.maps.MapTypeId." . $this->mapType . ",\n";
    if ( $this->mapBackgroundColor != "" ) {
        $ret.= "backgroundColor: '" . $this->mapBackgroundColor . "',\n"; 
    }
    $ret.= "\tdisableDefaultUI: " . $_disableDefaultUI . ",\n";
    $ret.= "\tmapTypeControl: " . $_mapTypeControl.",\n";
    $ret.= "\tmapTypeControlOptions: { style: google.maps.MapTypeControlStyle." . $this->mapTypeControlStyle . " },\n";
    $ret.= "\tnavigationControl: " . $_navigationControl . ",\n";
    $ret.= "\tnavigationControlOptions: { style: google.maps.NavigationControlStyle." . $this->navigationControlStyle . " },\n";
    $ret.= "\tscaleControl: " . $_scaleControl.",\n";
    $ret.= "\tstreetViewControl: " . $_streetViewControl . ",\n";
    $ret.= "\tdraggable: " . $_mapDraggable.",\n";
    $ret.= "\tscrollwheel: " . $_scrollwheelZoom . ",\n";
    $ret.= "\tdisableDoubleClickZoom: " . $_disableDoubleClickZoom . "\n";
    $ret.= "};\n\n";
    $ret.= "function simpleGMapAPIShowmap" . $this->mapName ."() {\n";
    $ret.= "\tvar map = new google.maps.Map(document.getElementById('" . $this->mapName ."'), options);\n\n";
    
    // infoWindowBehaviour     
    if ( ($this->infoWindowBehaviour == 'CLOSE_ON_MAPCLICK') OR ($this->infoWindowBehaviour == 'SINGLE_CLOSE_ON_MAPCLICK') ) {
        $ret.= "\tgoogle.maps.event.addListener(map, 'click', function() { if (currentInfoWindow != null) { currentInfoWindow.close(); } });\n";
    }

    /*
     * Run through the mapMarkers array to display markers on the map
     */
    for ( $count = 0; $count < sizeof($this->mapMarkers); $count++ ) {
        // place the marker on the map
        $ret.= "\tvar markerLatLng = new google.maps.LatLng(" . $this->mapMarkers[$count]['lat'] . ", " . $this->mapMarkers[$count]['lng'] . ");\n";
        $ret.= "\tvar marker$count = new google.maps.Marker({\n";
        $ret.= "\t position: markerLatLng,\n";
        $ret.= "\t title: '" . $this->mapMarkers[$count]['tooltip'] . "',\n";
        if ( $this->mapMarkers[$count]['iconURL'] != "" ) {
             $ret.= "\t icon: '" . $this->mapMarkers[$count]['iconURL'] . "',\n";
        }
        if ( $this->mapMarkers[$count]['clickable'] == false ) {
             $ret.= "\t clickable: false,\n";
        }
        $ret.= "\t map: map\n";
        $ret.= "\t});\n";
             
        // add an InfoWindow if there is a text to be displayed
        if ( $this->mapMarkers[$count]['info'] != "") {
            // create InfoWindow
            $ret.= "\tvar infowindowM$count = new google.maps.InfoWindow({\n";
            $ret.= "\t content: '" . $this->mapMarkers[$count]['info'] . "'\n";
            $ret.= "\t});\n";
            // add an event to the marker
            $ret.= "\tgoogle.maps.event.addListener (marker$count, '$_infowindowtrigger', function() {\n";
            // infoWindowBehaviour     
            if ( ($this->infoWindowBehaviour == 'SINGLE') OR ($this->infoWindowBehaviour == 'SINGLE_CLOSE_ON_MAPCLICK') ) {
                $ret.= "\t if (currentInfoWindow != null) { currentInfoWindow.close(); } \n";
            }
            $ret.= "\t infowindowM$count.open(map, marker$count);\n";
            $ret.= "\t currentInfoWindow = infowindowM$count;\n";
            $ret.= "\t});\n";
        }
        $ret.= "\tbounds.extend(markerLatLng);\n\n";
    }

    /*
     * Run through the mapCircles array to display circles on the map
     */
    for ( $count = 0; $count < sizeof($this->mapCircles); $count++ ) {
        // place the circle on the map
        $ret.= "\tvar circleLatLng = new google.maps.LatLng(" . $this->mapCircles[$count]['lat'] . ", " . $this->mapCircles[$count]['lng'] . ");\n";
        $ret.= "\tvar circle$count = new google.maps.Circle({\n";
        $ret.= "\t center: circleLatLng,\n";
        $ret.= "\t radius: " . $this->mapCircles[$count]['rad'] . ",\n";
        // check if there are options set for the circle     
        if ( $this->mapCircles[$count]['fillColor'] != "" )     { $ret.= "\t fillColor: '" . $this->mapCircles[$count]['fillColor'] . "',\n"; }
        if ( $this->mapCircles[$count]['fillOpacity'] != "" )   { $ret.= "\t fillOpacity: " . $this->mapCircles[$count]['fillOpacity'] . ",\n"; }
        if ( $this->mapCircles[$count]['strokeColor'] != "" )   { $ret.= "\t strokeColor: '" . $this->mapCircles[$count]['strokeColor'] . "',\n"; }
        if ( $this->mapCircles[$count]['strokeOpacity'] != "" ) { $ret.= "\t strokeOpacity: " . $this->mapCircles[$count]['strokeOpacity'] . ",\n"; }
        if ( $this->mapCircles[$count]['strokeWeight'] != "" )  { $ret.= "\t strokeWeight: " . $this->mapCircles[$count]['strokeWeight'] . ",\n"; }
        if ( $this->mapCircles[$count]['clickable'] == false )  { $ret.= "\t clickable: false,\n"; }
        $ret.= "\t map: map\n";
        $ret.= "\t});\n";
             
        // add an InfoWindow if there is a text to be displayed and circle is clickable
        if ( ($this->mapCircles[$count]['info'] != "") AND ($this->mapCircles[$count]['clickable'] != false) ) {
            // create InfoWindow
            $ret.= "\tvar infowindowC$count = new google.maps.InfoWindow({\n";
            $ret.= "\t content: '" . $this->mapCircles[$count]['info'] . "',\n";
            $ret.= "\t position: circleLatLng,\n";
            $ret.= "\t});\n";
            // add an event to the marker
            $ret.= "\tgoogle.maps.event.addListener (circle$count, '$_infowindowtrigger', function() {\n";
            // infoWindowBehaviour     
            if ( ($this->infoWindowBehaviour == 'SINGLE') OR ($this->infoWindowBehaviour == 'SINGLE_CLOSE_ON_MAPCLICK') ) {
                $ret.= "\t if (currentInfoWindow != null) { currentInfoWindow.close(); } \n";
            }
            $ret.= "\t var tmplat1 = circle$count.getCenter().lat()+(circle$count.getBounds().getNorthEast().lat() - circle$count.getCenter().lat())/2;\n";
            $ret.= "\t var tmplng1 = circle$count.getCenter().lng()+(circle$count.getBounds().getNorthEast().lng() - circle$count.getCenter().lng())/2;\n";
            $ret.= "\t var newpos = new google.maps.LatLng(tmplat1, tmplng1);\n";
            $ret.= "\t infowindowC$count.open(map);\n";
            $ret.= "\t infowindowC$count.setPosition(newpos);\n";
            $ret.= "\t currentInfoWindow = infowindowC$count;\n";
            $ret.= "\t});\n";
        }

        $ret.= "\tbounds.extend(circle$count.getBounds().getNorthEast());\n";
        $ret.= "\tbounds.extend(circle$count.getBounds().getSouthWest());\n\n";
    }

    /*
     * Run through the mapRectangles array to display circles on the map
     */
    for ( $count = 0; $count < sizeof($this->mapRectangles); $count++ ) {
        // place the rectangle on the map
        $ret.= "\tvar rectangleSW = new google.maps.LatLng(" . $this->mapRectangles[$count]['lat1'] . ", " . $this->mapRectangles[$count]['lng1'] . ");\n";
        $ret.= "\tvar rectangleNE = new google.maps.LatLng(" . $this->mapRectangles[$count]['lat2'] . ", " . $this->mapRectangles[$count]['lng2'] . ");\n";
        $ret.= "\tvar rectangleBounds = new google.maps.LatLngBounds(rectangleSW,rectangleNE);\n";
        $ret.= "\tvar rectangle$count = new google.maps.Rectangle({\n";
        $ret.= "\t bounds: rectangleBounds,\n";
        // check if there are options set for the rectangle     
        if ( $this->mapRectangles[$count]['fillColor'] != "" )     { $ret.= "\t fillColor: '" . $this->mapRectangles[$count]['fillColor'] . "',\n"; }
        if ( $this->mapRectangles[$count]['fillOpacity'] != "" )   { $ret.= "\t fillOpacity: " . $this->mapRectangles[$count]['fillOpacity'] . ",\n"; }
        if ( $this->mapRectangles[$count]['strokeColor'] != "" )   { $ret.= "\t strokeColor: '" . $this->mapRectangles[$count]['strokeColor'] . "',\n"; }
        if ( $this->mapRectangles[$count]['strokeOpacity'] != "" ) { $ret.= "\t strokeOpacity: " . $this->mapRectangles[$count]['strokeOpacity'] . ",\n"; }
        if ( $this->mapRectangles[$count]['strokeWeight'] != "" )  { $ret.= "\t strokeWeight: " . $this->mapRectangles[$count]['strokeWeight'] .",\n"; }
        if ( $this->mapRectangles[$count]['clickable'] == false )  { $ret.= "\t clickable: false,\n"; }
        $ret.= "\t map: map\n";
        $ret.= "\t});\n";
             
        // add an InfoWindow if there is a text to be displayed and rectangle is clickable
        if ( ($this->mapRectangles[$count]['info'] != "") AND ($this->mapRectangles[$count]['clickable'] != false) ) {
            // create InfoWindow
            $ret.= "\tvar infowindowR$count = new google.maps.InfoWindow({\n";
            $ret.= "\t content: '" . $this->mapRectangles[$count]['info'] . "',\n";
            $ret.= "\t position: rectangleNE,\n";
            $ret.= "\t});\n";
            // add an event to the marker
            $ret.= "\tgoogle.maps.event.addListener (rectangle$count, '$_infowindowtrigger', function() {\n";
            // infoWindowBehaviour     
            if ( ($this->infoWindowBehaviour == 'SINGLE') OR ($this->infoWindowBehaviour == 'SINGLE_CLOSE_ON_MAPCLICK') ) {
                $ret.= "\t if (currentInfoWindow != null) { currentInfoWindow.close(); } \n";
            }
            $ret.= "\t infowindowR$count.open(map);\n";
            $ret.= "\t currentInfoWindow = infowindowR$count;\n";
            $ret.= "\t});\n";
        }

        $ret.= "\tbounds.extend(rectangleNE);\n";
        $ret.= "\tbounds.extend(rectangleSW);\n\n";
    }

    /*
     * Run through the mapKmlLayers array to display Kml/GeoRSS layers on the map
     */
    for ( $count = 0; $count < sizeof($this->mapKmlLayers); $count++ ) {
        // place the rectangle on the map
        $ret.= "\tvar georssLayer$count = new google.maps.KmlLayer('" . $this->mapKmlLayers[$count]['url'] . "',{\n";
        // check if there are options set for the rectangle
        if ( $this->mapKmlLayers[$count]['clickable'] == false )  { $ret.= "\t clickable: false,\n"; }
        if ( $this->mapKmlLayers[$count]['preserveViewport'] == false )  { $ret.= "\t preserveViewport: false,\n"; }
        if ( $this->mapKmlLayers[$count]['suppressInfoWindows'] == false )  { $ret.= "\t suppressInfoWindows: false,\n"; }
        $ret.= "\t map: map\n";
        $ret.= "\t});\n";
        //IN PROGRESS echo "\tgeorssLayer$count.setMap(map);\n";
    }

    if ( $zoomToBounds ) {
        $ret.= "\tmap.fitBounds(bounds);\n";
    }
    $ret.= "}\n\n";
    $ret.= "google.maps.event.addDomListener(window, 'load', simpleGMapAPIShowmap" . $this->mapName .");\n";
    $ret.= "</script>\n";

    if ($output) echo $ret;
    return $ret;
}

}
//End Of Class


?>
