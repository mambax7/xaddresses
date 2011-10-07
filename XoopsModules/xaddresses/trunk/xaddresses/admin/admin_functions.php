<?php
function moduleAdminTabMenu($adminmenu = array(), $currentFile = 0, $breadcrumb = " &gt; ")
{
    if (is_int($currentFile)) {
        $currentOption = $currentFile;
    } else if (is_string($currentFile)) {
        foreach($adminmenu as $key=>$adminitem ) {
            preg_match( "/.*[\\/]([^\\/]+.php)\?*.*/", $adminitem['link'], $matches);
            if ($matches[1] == $currentFile) $currentOption = $key;
        }
    } else {
        return false;
    }

    $moduleLink = XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/';
    /* Nice buttons styles */
    $adminTabCss = "
        <style type='text/css'>
        #buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
        #buttonbar { float:left; width:100%; background: #e7e7e7 url('" . $moduleLink . "images/deco/bg.png') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }
        #buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
        #buttonbar li { display:inline; margin:0; padding:0; }
        #buttonbar a { float:left; background:url('" . $moduleLink . "images/deco/left_both.png') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
        #buttonbar a span { float:left; display:block; background:url('" . $moduleLink . "images/deco/right_both.png') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
        /* Commented Backslash Hack hides rule from IE5-Mac \*/
        #buttonbar a span {float:none;}
        /* End IE5-Mac hack */
        #buttonbar a:hover span { color:#333; }
        #buttonbar .current a { background-position:0 -150px; border-width:0; }
        #buttonbar .current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
        #buttonbar a:hover { background-position:0% -150px; }
        #buttonbar a:hover span { background-position:100% -150px; }
        div.CPbigTitle{
            font-size: 12px;
            color: #606060;
            background: no-repeat left top;
            font-weight: bold;
            height: 30px;
            vertical-align: middle;
            padding: 10px 0 0 40px;
            /*border-bottom: 3px solid #393e41;*/
            border-bottom: none;
        }
        </style>
    ";
    
    global $xoopsConfig;
    xoops_loadLanguage('modinfo', $GLOBALS['xoopsModule']->getVar('dirname'));

    $adminTabHtml = '';
    $adminTabHtml.= "<div id='buttontop'>";
    $adminTabHtml.= "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
    $adminTabHtml.= "<td style='font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;'>";
    $adminTabHtml.= "<a class='nobutton' href='" . XOOPS_URL . "/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $GLOBALS['xoopsModule']->getVar('mid') . "'>" . _PREFERENCES . "</a>";
    $adminTabHtml.= " | ";
    $adminTabHtml.= "<a href='" . $moduleLink . "index.php'>" . $GLOBALS['xoopsModule']->getVar('name') . "</a>";
    $adminTabHtml.= "</td>";
    $adminTabHtml.= "<td style='font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;'><b>" . $GLOBALS['xoopsModule']->getVar('name') . $breadcrumb . $adminmenu[$currentOption]["title"] . "</b> </td>";
    $adminTabHtml.= "</tr></table>";
    $adminTabHtml.= "</div>";
    
    $adminTabHtml.= "<div id='buttonbar'>";
    $adminTabHtml.= "<ul>";

    foreach(array_keys($adminmenu) as $key ) {
        $adminTabHtml.= (($currentOption == $key)? '<li class="current">':'<li>') . '<a href="' . $moduleLink . $adminmenu[$key]["link"] . '"><span>' . $adminmenu[$key]["title"] . '</span></a></li>';
    }

    $adminTabHtml.= "</ul></div>";
    $adminTabHtml.= "<br style='clear:both;' >";

    $adminTabHtml.= '<div class="CPbigTitle" style="background-image: url(../' . $adminmenu[$currentOption]["icon"] .'); background-repeat: no-repeat; background-position: left; padding-left: 48px; height: 48px;">';
    $adminTabHtml.= '<strong>' . $adminmenu[$currentOption]["title"] . '</strong>';
    $adminTabHtml.= '</div>';

    return $adminTabCss . $adminTabHtml;
}



function moduleAdminSubmenu ($submenuItems) {
    echo "<div class='head'>";
    echo implode($submenuItems, ' | ');
    echo "</div>";
}










function xaddressesAdminMenu ($currentoption = 0, $breadcrumb = '') {

    /* Nice buttons styles */
    echo "
        <style type='text/css'>
        #buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
        #buttonbar { float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/xaddresses/images/deco/bg.png') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }
        #buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
        #buttonbar li { display:inline; margin:0; padding:0; }
        #buttonbar a { float:left; background:url('" . XOOPS_URL . "/modules/xaddresses/images/deco/left_both.png') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
        #buttonbar a span { float:left; display:block; background:url('" . XOOPS_URL . "/modules/xaddresses/images/deco/right_both.png') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
        /* Commented Backslash Hack hides rule from IE5-Mac \*/
        #buttonbar a span {float:none;}
        /* End IE5-Mac hack */
        #buttonbar a:hover span { color:#333; }
        #buttonbar #current a { background-position:0 -150px; border-width:0; }
        #buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
        #buttonbar a:hover { background-position:0% -150px; }
        #buttonbar a:hover span { background-position:100% -150px; }
        </style>
    ";
    
    global $xoopsConfig;
    
    $tblColors = Array();
    $tblColors[0] = $tblColors[1] = $tblColors[2] = $tblColors[3] = $tblColors[4] = $tblColors[5] = $tblColors[6] = $tblColors[7] = $tblColors[8] = '';
    $tblColors[$currentoption] = 'current';
    xoops_loadLanguage('modinfo', $GLOBALS['xoopsModule']->getVar('dirname'));

    echo "<div id='buttontop'>";
    echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
    //echo "<td style=\"width: 45%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\"><a class=\"nobutton\" href=\"../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule->getVar('mid') . "\">" . _AM_SF_OPTS . "</a> | <a href=\"import.php\">" . _AM_SF_IMPORT . "</a> | <a href=\"../index.php\">" . _AM_SF_GOMOD . "</a> | <a href=\"../help/index.html\" target=\"_blank\">" . _AM_SF_HELP . "</a> | <a href=\"about.php\">" . _AM_SF_ABOUT . "</a></td>";
    echo "<td style='font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;'>";
    echo "<a class='nobutton' href='" . XOOPS_URL . "/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $GLOBALS['xoopsModule']->getVar('mid') . "'>" . _PREFERENCES . "</a>";
    echo " | ";
    echo "<a href='" . XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname') . "/index.php'>" . $GLOBALS['xoopsModule']->getVar('name') . "</a>";
    echo "</td>";
    echo "<td style='font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;'><b>" . $GLOBALS['xoopsModule']->getVar('name') . " </b> </td>";
    echo "</tr></table>";
    echo "</div>";
    
    echo "<div id='buttonbar'>";
    echo "<ul>";
    echo "<li id='" . $tblColors[0] . "'><a href=\"" . XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname') . "/admin/index.php\"><span>" . _XADDRESSES_MI_ADMENU1 . "</span></a></li>";
    echo "<li id='" . $tblColors[1] . "'><a href=\"" . XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname') . "/admin/category.php\"><span>" . _XADDRESSES_MI_ADMENU2 . "</span></a></li>";
    echo "<li id='" . $tblColors[2] . "'><a href=\"" . XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname') . "/admin/addresses.php\"><span>" . _XADDRESSES_MI_ADMENU3 . "</span></a></li>";
    echo "<li id='" . $tblColors[3] . "'><a href=\"" . XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname') . "/admin/champs.php\"><span>" . _XADDRESSES_MI_ADMENU6 . "</span></a></li>";
    echo "<li id='" . $tblColors[4] . "'><a href=\"" . XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname') . "/admin/permissions.php\"><span>" . _XADDRESSES_MI_ADMENU7 . "</span></a></li>";
    echo "</ul></div>&nbsp;";
}



function xaddressesAdminSubmenu ($submenuItems) {
    echo "<div class='head'>";
    echo implode($submenuItems, ' | ');
    echo "</div>";
}
?>