<?php
require_once("simpleGMapAPI.php");
require_once("simpleGMapGeocoder.php");

$map1 = new simpleGMapAPI();
$map2 = new simpleGMapAPI();
$geo = new simpleGMapGeocoder();

$map1->setMapName('prima');
$map1->setWidth(200);
$map1->setHeight(200);
$map1->setBackgroundColor('#d0d0d0');
$map1->setMapDraggable(true);
$map1->setDoubleclickZoom(false);
$map1->setScrollwheelZoom(true);

$map1->showDefaultUI(false);
$map1->showMapTypeControl(true, 'DROPDOWN_MENU');
$map1->showNavigationControl(true, 'DEFAULT');
$map1->showScaleControl(true);
$map1->showStreetViewControl(true);

$map1->setZoomLevel(14); // not really needed because showMap is called in this demo with auto zoom
$map1->setInfoWindowBehaviour('SINGLE_CLOSE_ON_MAPCLICK');
$map1->setInfoWindowTrigger('CLICK');

$map2->setMapName('seconda');
$map2->setWidth(400);
$map2->setHeight(400);
$map2->setBackgroundColor('#cccccc');
$map2->setMapDraggable(true);
$map2->setDoubleclickZoom(false);
$map2->setScrollwheelZoom(true);

$map2->showDefaultUI(false);
$map2->showMapTypeControl(true, 'DROPDOWN_MENU');
$map2->showNavigationControl(true, 'DEFAULT');
$map2->showScaleControl(true);
$map2->showStreetViewControl(true);

$map2->setZoomLevel(14); // not really needed because showMap is called in this demo with auto zoom
$map2->setInfoWindowBehaviour('SINGLE_CLOSE_ON_MAPCLICK');
$map2->setInfoWindowTrigger('CLICK');



//$map->addMarkerByAddress("Ravensberger Park 1 , Bielefeld", "Ravensberger Spinnerei", "Ravensberger Spinnerei", "http://google-maps-icons.googlecode.com/files/museum-historical.png");
//$map->addMarkerByAddress("Universit‰tsstraﬂe 25, Bielefeld", "Universit‰t Bielefeld", "<a href=\"http://www.uni-bielefeld.de\" target=\"_blank\">http://www.uni-bielefeld.de</a>", "http://google-maps-icons.googlecode.com/files/university.png");
$map1->addMarker(52.0149436, 8.5275128, "Sparrenburg Bielefeld", "Sparrenburg, 33602 Bielefeld, Deutschland<br /><img src=\"http://www.bielefeld.de/ftp/bilder/sehenswuerdigkeiten/sehenswuerdigkeiten/sparrenburg-bielefeld-435.gif\"", "http://google-maps-icons.googlecode.com/files/museum-archeological.png");

$opts = array('fillColor'=>'#0000dd', 'fillOpacity'=>0.2, 'strokeColor'=>'#000000', 'strokeOpacity'=>1, 'strokeWeight'=>2, 'clickable'=>true);
$map1->addCircle(52.0149436, 8.5275128, 1500, "1,5km Umgebung um die Sparrenburg", $opts);

$opts = array('fillColor'=>'#00dd00', 'fillOpacity'=>0.2, 'strokeColor'=>'#003300', 'strokeOpacity'=>1, 'strokeWeight'=>2, 'clickable'=>true);
$map2->addRectangle(52.0338, 8.487, 52.0414, 8.502, "Campus Universit‰t Bielefeld", $opts);

$map2->addKmlLayer('http://gmaps-samples.googlecode.com/svn/trunk/ggeoxml/cta.kml');


echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";
echo "<title>simpleGMapAPI test</title>";

$map1->printGMapsJS();

echo "</head>\n";
echo "\n\n<body>\n\n";

// showMap with auto zoom enabled
$map1->showMap(true);
$map2->showMap(true);

echo "</body>\n";
echo "</html>\n";

?>