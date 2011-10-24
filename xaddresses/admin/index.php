<?php
include 'admin_header.php';
$currentFile = basename(__FILE__);

// load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
// IN PROGRESS
$brokenHandler =& xoops_getModuleHandler('broken', 'xaddresses');
//$addresses_mod_Handler =& xoops_getModuleHandler('xaddresses_mod', 'xaddresses');


// count location categories
$countCategories = $categoryHandler->getCount();

// count valid locations
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_suggested', false));
$criteria->add(new Criteria('loc_status', 0, '!='));
$countLocations = $locationHandler->getCount($criteria);
unset($criteria);

// count waiting/not valid locations
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_suggested', false));
$criteria->add(new Criteria('loc_status', 0));
$countWaitingLocations = $locationHandler->getCount($criteria);
unset($criteria);

// count broken locations
$countBrokenLocations = 0;

// count modified locations
$countModifyLocations = 0;
/*
// compte le nombre de rapport de téléchargements brisés
$countLocations_broken = $addresses_broken_Handler->getCount();
// compte le nombre de demande de modifications
$countLocations_modified = $addresses_mod_Handler->getCount();
*/



$op = (isset($_GET['op']))? $_GET['op'] : "";



xoops_cp_header();

// main admin menu
include (XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->dirname() . '/admin/menu.php');
echo moduleAdminTabMenu($adminmenu, $currentFile);

// index menu
include_once XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->dirname() . '/class/menu.php';
//$menu = new moduleMenu();
$menu = new testMenu();
foreach ($adminmenu as $menuitem) {
    $menu->addItem($menuitem['name'], '../' . $menuitem['link'], '../' . $menuitem['icon'], $menuitem['title']);
}

$menu->addItem('Preferences', '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $GLOBALS['xoopsModule'] ->getVar('mid') . '&amp;&confcat_id=1', '../images/icons/32x32/prefs.png', _PREFERENCES);

echo $menu->getCSS();
echo '<table width="100%" border="0" cellspacing="10" cellpadding="4">';
echo '<tr>';



echo '<td>' . $menu->render() . '</td>';
echo '<td valign="top" width="60%">';

echo '<fieldset>';
echo '<legend class="CPmediumTitle">' . _XADDRESSES_AM_INDEX_INFO . '</legend>';
echo '<h3>' . _XADDRESSES_AM_INDEX_SCONFIG . '</h3>';
echo '<br/>';
echo '<h3>' . _XADDRESSES_AM_INDEX_DATABASE . '</h3>';
printf(_XADDRESSES_AM_INDEX_COUNTCATEGORIES, $countCategories);
echo '<br />';
printf(_XADDRESSES_AM_INDEX_COUNTLOCATIONS, $countLocations);
echo '<br />';
printf(_XADDRESSES_AM_INDEX_COUNTWAITING, $countWaitingLocations);
echo '<br/>';
printf(_XADDRESSES_AM_INDEX_COUNTBROKEN, $countBrokenLocations);
echo '<br/>';
printf(_XADDRESSES_AM_INDEX_COUNTMODIFIED, $countModifyLocations);
echo '<br/>';
echo '</fieldset>';


echo '<br/>';


echo '<fieldset>';
echo '<legend class="CPmediumTitle">' . _XADDRESSES_AM_INDEX_SERVERSTATUS . '</legend>';
echo '<h3>' . _XADDRESSES_AM_INDEX_SPHPINI . '</h3>';
$safeMode = (ini_get('safe_mode')) ? _XADDRESSES_AM_INDEX_ON . _XADDRESSES_AM_INDEX_SAFEMODEPROBLEMS : _XADDRESSES_AM_INDEX_OFF;
$registerGlobals = (!ini_get('register_globals')) ? '<span style="color: green;">' . _XADDRESSES_AM_INDEX_OFF . '</span>' : '<span style="color: red;">' . _XADDRESSES_AM_INDEX_ON . '</span>';
$magicQuotesGpc = (get_magic_quotes_gpc()) ? _XADDRESSES_AM_INDEX_ON : _XADDRESSES_AM_INDEX_OFF;
$downloads = (ini_get('file_uploads')) ? '<span style="color: green;">' . _XADDRESSES_AM_INDEX_ON . '</span>' : '<span style="color: red;">' . _XADDRESSES_AM_INDEX_OFF . '</span>';
$gdLib = (function_exists('gd_info')) ? '<span style="color: green;">' . _XADDRESSES_AM_INDEX_GDON . '</span>' : '<span style="color: red;">' . _XADDRESSES_AM_INDEX_GDOFF . '</span>';
$zipLib = (class_exists('ZipArchive')) ? '<span style="color: green;">' . _XADDRESSES_AM_INDEX_ZIPON . '</span>' : '<span style="color: red;">' . _XADDRESSES_AM_INDEX_ZIPOFF . '</span>';
echo '<ul>';
echo '<li>' . _XADDRESSES_AM_INDEX_GDLIBSTATUS . $gdLib;
if (function_exists('gd_info')) {
    if (true == $gdLib = gd_info()) {
        echo '<li>' . _XADDRESSES_AM_INDEX_GDLIBVERSION . '<b>' . $gdLib['GD Version'] . '</b>';
    }
}
echo '<li>' . _XADDRESSES_AM_INDEX_ZIPLIBSTATUS . $zipLib;
echo '</ul>';
echo '<ul>';
echo '<li>' . _XADDRESSES_AM_INDEX_SAFEMODESTATUS . $safeMode;
echo '<li>' . _XADDRESSES_AM_INDEX_REGISTERGLOBALS . $registerGlobals;
echo '<li>' . _XADDRESSES_AM_INDEX_MAGICQUOTESGPC . $magicQuotesGpc;
echo '<li>' . _XADDRESSES_AM_INDEX_SERVERUPLOADSTATUS . $downloads;
echo '<li>' . _XADDRESSES_AM_INDEX_MAXUPLOADSIZE . ' <b><span style="color: blue;">' . ini_get('upload_max_filesize') . '</span></b>';
echo '<li>' . _XADDRESSES_AM_INDEX_MAXPOSTSIZE . ' <b><span style="color: blue;">' . ini_get('post_max_size') . '</span></b>';
echo '<li>' . _XADDRESSES_AM_INDEX_SERVERPATH . ' <b>' . XOOPS_ROOT_PATH . '</b>';

echo '</ul>';
echo '</fieldset>';



echo '</td></tr>';
echo '</table>';

xoops_cp_footer();
?>