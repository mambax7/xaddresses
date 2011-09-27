<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name = "description" content = "Online tool to create code for polyline and polygon to Google Maps v3 (version 3)"/>
<title>KML editor - Google Maps API v3 Tool</title>
<head>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="kmleditor.js"></script>
<link type="text/css" rel="stylesheet" media="all" href="kmleditor.css" />
</head>



<body id="body" onload="init()">



<div id="header">
    <form id="tools" action="./" method="post" onsubmit="return false">
        <select id="toolchoice" name="toolchoice" onchange="toolID=parseInt(this.options[this.selectedIndex].value);setTool();">
            <option selected="selected" value="1">Polyline</option>
            <option value="2">Polygon</option>
            <option value="3">Rectangle</option>
            <option value="4">Circle</option>
            <option value="5">Marker</option>
            <option value="6">Directions</option>
        </select>
    </form>
    <form id="codes" action="./" method="post" onsubmit="return false">
        <select id="codechoice" name="codechoice" onchange="codeID=parseInt(this.options[this.selectedIndex].value);setCode();">
            <option selected="selected" value="1">KML</option>
            <option value="2">Javascript</option>
        </select>
    </form>

    <form action="#">
        <select id="over">
            <option>LngLat mousemove</option>
            <option selected="selected">LatLng mousemove</option>
        </select>
        <input type="button" onclick="mapcenter();" value="Mapcenter"/>
        <input type="text" id="centerofmap" />
        Zoom level:
        <input type="text" size="5" name="myzoom" id="myzoom" value="15" />
    </form>
</div>

<div id="buttonrow">
    <input type="button" onclick="nextshape();" value="Next shape"/>
    <input type="button" onclick="clearMap();" value="Clear Map"/>
    <input type="button" onclick="deleteLastPoint();" value="Delete Last Point"/>
    <input type="button" onclick="styleoptions();" value="Style Options"/>
    <input type="button" onclick="editlines();" value="Edit lines" id="EditButton"/>
    <input type="button" onclick='docudetails();document.getElementById("toppers").style.visibility = "visible";' value="KML input"/>
    <input type="button" onclick="showKML();" value="Show KML"/>
    <input style="width:150px" type="button" onclick="copyTextarea();" value="Select and copy text"/>
    <form action="#" onsubmit="showAddress(this.address.value); return false">
        <input type="text" size="50" name="address" value="search..." />
        <input type="submit" value="Search" />
    </form>
</div>


<div class="column_1_3">
    <div id="map_canvas"></div>
</div>

<div class="column_1_3">
    <div id="presenter">
        <form action="#">
        Live code presentation in textarea (hover):
        <input type="checkbox" name="showcodemode" id="presentcode" value="yes" onclick="showCodeintextarea();" checked="checked"/>
        <textarea id="coords1"></textarea>
        </form>
    </div>
</div>

<div class="column_1_3">
    <div id="help"></div>
</div>

<div>
    <p>
        This is a drawing tool for polyline, polygon, polygon with holes, rectangle, circle, marker(icon), direction(route, path).
        This application uses the Google Maps API Version 3 (V3). It has all the features of Google Maps MyMaps and
        has direct access to the code for the shapes (overlays) you create. It should be a full-fledged alternative.
        This application should now also serve as a mature alternative to my online V2 tool
        <a href="http://www.birdtheme.org/useful/googletool.html">Digitizer tool</a>
        <br />
        Code will be presented in the textarea. You may choose to see KML or Google Javascript V3.<br />
        <a href="http://www.birdtheme.org/">www.birdtheme.org</a>
    </p>
</div>



<div id="polygonstuff">
    <div><a style="padding-left:5px; color: #ffffff;" href="javascript:holecreator()">Hole</a></div>
    <div id="stepdiv" style="padding-left:5px">Step 0</div>
    <div><input id="multipleholes" type="button" onclick="nexthole();" value="Next hole"/></div>
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

<div id="directionsPanel" style="margin:20px;background-color:#FFEE77;"></div>
<p></p>
</body>

</html>
