<?php
defined('XOOPS_ROOT_PATH') or die('Restricted access');
xoops_loadLanguage('formgooglemap', 'xaddresses');

class FormGoogleMap extends XoopsFormElementTray
{
    var $config = array();
    var $setting = array();
    var $_caption;
    var $_name;
    var $_value;

    /**
     * FormGoogleMap::FormGoogleMap()
     *
     * @param mixed $caption
     * @param mixed $name
     * @param array('lat'=> , 'lng'=> , 'elevation'=> , 'zoom'=>) $value
     * @param array('option name'=>'option_value') $configs
     */
    function __construct($caption, $name, $value = NULL, $config=array())
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->_caption = $caption;
        $this->_name = $name;
        $this->_value = $value;
        $this->config['style'] = 'width:100%;height:200px;border:1px solid black;';
        $this->config['readonly'] = false;
        $this->config = array_merge($this->config, $config);

        $this->lat = (is_array($value) && isset($value['lat'])) ? $value['lat'] : '0'; // default lat
        $this->lng = (is_array($value) && isset($value['lng'])) ? $value['lng'] : '0'; // default lng
        $this->elevation = (is_array($value) && isset($value['elevation'])) ? $value['elevation'] : ''; // default elevation
        $this->zoom = (is_array($value) && isset($value['zoom'])) ? $value['zoom'] : 8; // default zoom level
        $this->search = '';
        //$kml = (is_array($value) && isset($value['kml'])) ? $value['kml'] : ''; // default kml
    }
    
    function FormGoogleMap($caption, $name, $value = NULL, $config=array())
    {
        $this->__construct($caption, $name, $value, $config);
    }

        /**
     * FormGoogleMap::setConfig()
     *
     * @param mixed $key, $options or array($key=>, $options=>)
     * @return
     */
    function setConfig()
    {
        $args = func_get_args();
        // For backward compatibility
        if (!is_array($args[0])) {
            if (count($args) >= 2) {
                $this->config = array_merge($this->config, array($args[0] => $args[1]));
                return true;
            } else {
                return false;
            }
        } else {
            $this->config = array_merge($this->config, $args[0]);
            return true;
        }
        return false;
    }
    
    function render() {
        static $isGoogleMapJsLoaded = false;
        $ret = '';
        $class = '';
        $js = '';
        $html = '';

        $class = "
            function xoopsFormGoogleMap(formId, mapId, lat, lng, zoom, draggable) {
                var _geocoder = new google.maps.Geocoder();
                var _elevator = new google.maps.ElevationService(); // Create an ElevationService

                var _map = null;
                var _formId = formId;
                var _mapId = mapId;
                var _initLatLng = null;
                var _initZoomLevel = null;
                var _draggable = draggable;
                
                if (!zoom) {
                    _initZoomLevel = 8; // default zoom level
                } else {
                    _initZoomLevel = parseInt(zoom);
                }
                
                if (!lat && !lng) {
                    _initLatLng = new google.maps.LatLng(0, 0);
                    // Experimental: try to find browser location
                    // Check to see if this browser supports geolocation
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            // function successCallback(position)
                            function(position) {
                                lat = position.coords.latitude;
                                lng = position.coords.longitude;
                                document.getElementById(formId + '[lat]').value = lat;
                                document.getElementById(formId + '[lng]').value = lng;
                                _initLatLng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
                            }, 
                            // function errorCallback(error)
                            function(error) {
                                //alert('error code:' + error.code + ' error:' + error.message);
                            }, 
                            {maximumAge:600000, timeout:0}
                        );
                    }
                    // Use Google ClientLocation to find browser position
                    if(google.loader.ClientLocation) {
                        lat = google.loader.ClientLocation.latitude;
                        lng = google.loader.ClientLocation.longitude;
                        search = google.loader.ClientLocation.address.city + ', ' + google.loader.ClientLocation.address.country;
                        document.getElementById(formId + '[lat]').value = lat;
                        document.getElementById(formId + '[lng]').value = lng;
                        if (document.getElementById(formId + '[search]')) {
                            document.getElementById(formId + '[search]').value = search;
                        }
                        _initLatLng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
                    }
                } else {
                    _initLatLng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng)); 
                }

                function getElevation(map, markerLatLng) {
                    var locations = [];
                    // Create a LocationElevationRequest object using the array's one value
                    locations.push(markerLatLng);
                    var positionalRequest = {
                        'locations': locations
                    }
                    // Initiate the location request
                    _elevator.getElevationForLocations(positionalRequest, function(results, status) {
                        if (status == google.maps.ElevationStatus.OK) {
                            // Retrieve the first result
                            if (results[0]) {
                                // Open an info window indicating the elevation at the clicked position
                                document.getElementById(formId + '[elevation]').value = results[0].elevation;
                                //alert('The elevation at this point <br/>is ' + results[0].elevation + ' meters.');
                            } else {
                                //alert('No results found');
                            }
                        } else {
                            alert('Elevation service failed due to: ' + status);
                        }
                    });
                }
                
                function addMarker(map, formId, markerLatLng, draggable) {
                    if (draggable) {
                        var marker = new google.maps.Marker({
                            map: map,
                            draggable: true,
                            animation: google.maps.Animation.DROP,
                            position: markerLatLng
                        });
                        google.maps.event.addListener(marker, 'dragend', function() {
                            document.getElementById(formId + '[lat]').value = marker.getPosition().lat();
                            document.getElementById(formId + '[lng]').value = marker.getPosition().lng();
                            getElevation(map, marker.getPosition());
                            });
                    } else {
                        var marker = new google.maps.Marker({
                            map: map,
                            draggable: false,
                            position: markerLatLng
                        });
                    }
                    return marker;
                }
                
                var myOptions = {
                    zoom: _initZoomLevel,
                    center: _initLatLng,
                    mapTypeControl: true,
                    mapTypeControlOptions: {
                        style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                    },
                    navigationControl: true,
                    navigationControlOptions: {
                        style: google.maps.NavigationControlStyle.SMALL
                    },
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                _map = new google.maps.Map(document.getElementById(_mapId), myOptions);
                google.maps.event.addListener(_map, 'zoom_changed', function() {
                    document.getElementById(_formId + '[zoom]').value = _map.getZoom();
                });
                _marker = addMarker(_map, _formId, _initLatLng, _draggable);
                getElevation(_map, _initLatLng);
                
                function goInitControl(map, marker, formId, initLatLng, initZoomLevel, controlDiv) {
                    // Set CSS styles for the DIV containing the control
                    // Setting padding to 5 px will offset the control from the edge of the map
                    controlDiv.style.padding = '5px';
                    // Set CSS for the control border
                    var controlUI = document.createElement('DIV');
                    controlUI.style.backgroundColor = 'white';
                    controlUI.style.borderStyle = 'solid';
                    controlUI.style.borderWidth = '2px';
                    controlUI.style.cursor = 'pointer';
                    controlUI.style.textAlign = 'center';
                    controlUI.title = '" . _FORMGOOGLEMAP_GO_INIT_DESC . "';
                    controlDiv.appendChild(controlUI);
                    // Set CSS for the control interior
                    var controlText = document.createElement('DIV');
                    controlText.style.fontFamily = 'Arial,sans-serif';
                    controlText.style.fontSize = '12px';
                    controlText.style.paddingLeft = '4px';
                    controlText.style.paddingRight = '4px';
                    controlText.innerHTML = '" . _FORMGOOGLEMAP_GO_INIT . "';
                    controlUI.appendChild(controlText);
                    // Setup the click event listeners: simply reset the map to initial values
                    google.maps.event.addDomListener(controlUI, 'click', function() {
                        marker.setPosition(initLatLng);
                        map.setCenter(initLatLng);
                        map.setZoom(initZoomLevel);
                        document.getElementById(formId + '[lat]').value = initLatLng.lat();
                        document.getElementById(formId + '[lng]').value = initLatLng.lng();
                        getElevation(_map, initLatLng);
                        document.getElementById(formId + '[zoom]').value = initZoomLevel;
                    });
                }
                // Create the DIV to hold the control and call the HomeControl() constructor passing in this DIV.
                var goInitControlDiv = document.createElement('DIV');
                var goInitControlButton = new goInitControl(_map, _marker, _formId, _initLatLng, _initZoomLevel, goInitControlDiv);
                goInitControlDiv.index = 1;
                _map.controls[google.maps.ControlPosition.TOP_RIGHT].push(goInitControlDiv);

                function goCurrentControl(map, marker, controlDiv) {
                    // Set CSS styles for the DIV containing the control
                    // Setting padding to 5 px will offset the control from the edge of the map
                    controlDiv.style.padding = '5px';
                    // Set CSS for the control border
                    var controlUI = document.createElement('DIV');
                    controlUI.style.backgroundColor = 'white';
                    controlUI.style.borderStyle = 'solid';
                    controlUI.style.borderWidth = '2px';
                    controlUI.style.cursor = 'pointer';
                    controlUI.style.textAlign = 'center';
                    controlUI.title = '" . _FORMGOOGLEMAP_GO_CURRENT_DESC . "';
                    controlDiv.appendChild(controlUI);
                    // Set CSS for the control interior
                    var controlText = document.createElement('DIV');
                    controlText.style.fontFamily = 'Arial,sans-serif';
                    controlText.style.fontSize = '12px';
                    controlText.style.paddingLeft = '4px';
                    controlText.style.paddingRight = '4px';
                    controlText.innerHTML = '" . _FORMGOOGLEMAP_GO_CURRENT . "';
                    controlUI.appendChild(controlText);
                    // Setup the click event listeners: simply set the map to newLatLng
                    google.maps.event.addDomListener(controlUI, 'click', function() {
                        map.setCenter(marker.getPosition());
                    });
                }
                if (_draggable == true) {
                    // Create the DIV to hold the control and call the goCurrentControl() constructor passing in this DIV.
                    var goCurrentControlDiv = document.createElement('DIV');
                    var goCurrentControlButton = new goCurrentControl(_map, _marker, goCurrentControlDiv);
                    goCurrentControlDiv.index = 2;
                    _map.controls[google.maps.ControlPosition.TOP_RIGHT].push(goCurrentControlDiv);
                }
                
                this.geocodeAddress = function() {
                    address = document.getElementById(_formId + '[search]').value;
                    _geocoder.geocode( { 'address': address}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            searchLatLng = results[0].geometry.location;
                            _marker.setPosition(searchLatLng);
                            _map.setCenter(searchLatLng);
                            document.getElementById(formId + '[lat]').value = searchLatLng.lat();
                            document.getElementById(formId + '[lng]').value = searchLatLng.lng();
                            getElevation(_map, searchLatLng);
                        } else {
                            alert('" . _FORMGOOGLEMAP_SEARCHERROR . "' + status);
                        }
                    });
                }
                
                this.setMapPositionByForm = function() {
                    lat = parseFloat(document.getElementById(_formId + '[lat]').value);
                    lng = parseFloat(document.getElementById(_formId + '[lng]').value);
                    formLatLng = new google.maps.LatLng(lat, lng);
                    formZoomLevel = parseInt(document.getElementById(_formId + '[zoom]').value);
                    _marker.setPosition(formLatLng);
                    _map.setCenter(formLatLng);
                    _map.setZoom(formZoomLevel);
                    getElevation(_map, formLatLng);
                }

                this.setMapPosition = function(lat, lng) {
                    lat = parseFloat(lat);
                    lng = parseFloat(lng);
                    newLatLng = new google.maps.LatLng(lat, lng);
                    _marker.setPosition(newLatLng);
                    _map.setCenter(newLatLng);
                    getElevation(_map, newLatLng);
                }
            }
        ";
        if ( is_object($GLOBALS['xoTheme']) ) {
            if ( !$isGoogleMapJsLoaded ) {
                // CodeMirror stuff
                $GLOBALS['xoTheme']->addScript('http://maps.google.com/maps/api/js?sensor=false');
                $GLOBALS['xoTheme']->addScript('http://www.google.com/jsapi');
                $GLOBALS['xoTheme']->addScript('', array(), $class);
                $isGoogleMapJsLoaded = true;
            }
        } else {
            if ( !$isGoogleMapJsLoaded ) {
                $ret.= "<script src='http://maps.google.com/maps/api/js?sensor=false' type='text/javascript'></script>\n";
                $ret.= "<script src='http://www.google.com/jsapi' type='text/javascript'></script>\n";
                $ret.= "<script type='text/javascript'>\n";
                $ret.= $class . "\n";
                $ret.= "</script>\n";
                $isGoogleMapJsLoaded = true;
            }
        }

        $js.= "<script type='text/javascript'>\n";
        $js.= "var xoopsFormGoogleMap_{$this->_name} = null;\n";
        if ($this->config['readonly'] == false) {
            $js.= "xoopsOnloadEvent(
                    function() {
                        xoopsFormGoogleMap_{$this->_name} = new xoopsFormGoogleMap('{$this->_name}', '{$this->_name}_GoogleMap', {$this->lat}, {$this->lng}, {$this->zoom}, true);
                    });";
        } else {
            $js.= "xoopsOnloadEvent(
                    function() {
                        xoopsFormGoogleMap_{$this->_name} = new xoopsFormGoogleMap('{$this->_name}', '{$this->_name}_GoogleMap', {$this->lat}, {$this->lng}, {$this->zoom}, false);
                    });";
        }
        $js.= "</script>\n";
        $ret.= $js . "\n";

        $html.= "<div id='{$this->_name}_GoogleMap' style='{$this->config['style']}'>";
        $html.= _FORMGOOGLEMAP_GOOGLEMAPHERE;
        $html.= "<br />";
        $html.= _FORMGOOGLEMAP_GOOGLEMAPHERE_DESC;
        $html.= "</div>";
        $ret.= $html . "\n";

        if ($this->config['readonly'] == false) {
            $ret.= _FORMGOOGLEMAP_LATLNGZOOM_DESC;
            $ret.= "<br />";
            $ret.= _FORMGOOGLEMAP_LAT;
            $ret.= "&nbsp;";
            $ret.= "<input id='{$this->_name}[lat]' type='text' value='{$this->lat}' maxlength='255' size='18' title='Latitude' name='{$this->_name}[lat]' onchange='xoopsFormGoogleMap_{$this->_name}.setMapPositionByForm();'>";
            $ret.= "&nbsp;";
            $ret.= _FORMGOOGLEMAP_LNG;
            $ret.= "&nbsp;";
            $ret.= "<input id='{$this->_name}[lng]' type='text' value='{$this->lng}' maxlength='255' size='18' title='Longitude' name='{$this->_name}[lng]' onchange='xoopsFormGoogleMap_{$this->_name}.setMapPositionByForm();'>";
            $ret.= "&nbsp;";
            $ret.= _FORMGOOGLEMAP_ELEVATION;
            $ret.= "&nbsp;";
            $ret.= "<input id='{$this->_name}[elevation]' type='text' value='{$this->elevation}' maxlength='255' size='18' title='Longitude' name='{$this->_name}[elevation]'>";
            $ret.= "&nbsp;";
            $ret.= "<br />";
            $ret.= _FORMGOOGLEMAP_ZOOM;
            $ret.= "&nbsp;";
            $ret.= "<input id='{$this->_name}[zoom]' type='text' value='{$this->zoom}' maxlength='255' size='2' title='Zoom level' name='{$this->_name}[zoom]' onchange='xoopsFormGoogleMap_{$this->_name}.setMapPositionByForm();'>";
            $ret.= "<br />";
            $ret.= _FORMGOOGLEMAP_SEARCH_DESC;
            $ret.= "<br />";
            $ret.= _FORMGOOGLEMAP_SEARCH;
            $ret.= "<br />";
            $ret.= "<input id='{$this->_name}[search]' type='text' value='' maxlength='255' size='80' title='Search location' name='{$this->_name}[search]'>";
            $ret.= "<input id='{$this->_name}button' class='formButton' type='button' onclick='xoopsFormGoogleMap_{$this->_name}.geocodeAddress();' title='Search' value='Search' name='{$this->_name}button'>";
        } else {
            $ret.= _FORMGOOGLEMAP_LAT;
            $ret.= "&nbsp;";
            $ret.= "<span id='{$this->name}[lat]'>{$this->lat}</span>";
            $ret.= "&nbsp;";
            $ret.= _FORMGOOGLEMAP_LNG;
            $ret.= "&nbsp;";
            $ret.= "<span id='{$this->name}[lng]'>{$this->lng}</span>";
            $ret.= "&nbsp;";
            $ret.= _FORMGOOGLEMAP_ELEVATION;
            $ret.= "&nbsp;";
            $ret.= "<span id='{$this->name}[elevation]'>{$this->elevation}</span>";
            $ret.= "&nbsp;";
            $ret.= "<br />";
            $ret.= _FORMGOOGLEMAP_ZOOM;
            $ret.= "&nbsp;";
            $ret.= "<span id='{$this->name}[zoom]'>{$this->zoom}</span>";
        }
        return $ret;
    }
}
?>