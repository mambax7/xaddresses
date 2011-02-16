<?php
include 'admin_header.php';
$currentFile = basename(__FILE__);

// load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$brokenHandler =& xoops_getModuleHandler('broken', 'xaddresses');




xoops_cp_header();

// main admin menu
if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php"))	{
    xaddressesAdminMenu(1, _XADDRESSES_MI_ADMENU_INDEX);
} else {
    include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
    loadModuleAdminMenu (1, _XADDRESSES_MI_ADMENU_INDEX);
}

// count valid locations
$criteria = new CriteriaCompo();
//$criteria->add(new Criteria('loc_status', 0, '!='));
$countLocations = $locationHandler->getCount($criteria);

// count waiting/not valid locations
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_status', 0));
$waitingLocations = $locationHandler->getCount($criteria);

// count broken locations
$brokenLocations = $brokenHandler->getCount();

// count modified locations
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_mod_id', 0, '!='));
$modifiedLocations = $locationHandler->getCount($criteria);

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/menu.php';
//showIndex();
$menu = new xaddressesMenu();
$menu->addItem('Addresses Categories',      'locationcategory.php',     '../images/icons/folder_map.png', _XADDRESSES_MI_ADMENU_LOCATIONCATEGORY);
$menu->addItem('Addresses',                 'location.php',             '../images/icons/map.png', _XADDRESSES_MI_ADMENU_LOCATION);
$menu->addItem('Permissions',               'locationpermissions.php',  '../images/icons/lock_map.png', _XADDRESSES_MI_ADMENU_LOCATIONPERMISSIONS);
$menu->addItem('Extra Fields Categories',   'fieldcategory.php',        '../images/icons/folder_database.png', _XADDRESSES_MI_ADMENU_FIELDCATEGORY);
$menu->addItem('Extra Fields',              'field.php',                '../images/icons/database.png', _XADDRESSES_MI_ADMENU_FIELD);
$menu->addItem('Extra Fields Permissions',  'fieldpermissions.php',     '../images/icons/lock_database.png', _XADDRESSES_MI_ADMENU_FIELDPERMISSIONS);

$menu->addItem('About',                     'about.php',                '../images/icons/information.png', _XADDRESSES_MI_ADMENU_ABOUT);
//$menu->addItem('Update',                    '../../system/admin.php?fct=modulesadmin&op=update&module=TDMDownloads', '../images/icons/update.png', _XADDRESSES_MI_ADMENU_UPDATE);
//$menu->addItem('Import',                    'import.php',               '../images/icons/folder_put.png', _XADDRESSES_MI_ADMENU10);
$menu->addItem('Preference',                '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule ->getVar('mid') . '&amp;&confcat_id=1', '../images/icons/prefs.png', _PREFERENCES);

echo $menu->getCSS();
echo '<table width="100%" border="0" cellspacing="10" cellpadding="4">';
echo '<tr><td>' . $menu->render() . '</td>';

echo '<td valign="top" width="60%">';
echo '<fieldset><legend class="CPmediumTitle">' . _XADDRESSES_AM_INDEX_INFO . '</legend>';
echo '<br/>';
printf(_XADDRESSES_AM_INDEX_LOCATIONS, $countLocations);
echo '<br/>';
printf(_XADDRESSES_AM_INDEX_WAITING, $waitingLocations);
echo '<br/>';
printf(_XADDRESSES_AM_INDEX_BROKEN, $brokenLocations);
echo '<br/>';
printf(_XADDRESSES_AM_INDEX_MODIFIED, $modifiedLocations);
echo '<br/>';
echo '</fieldset>';


echo '</td></tr>';
echo '</table>';
/*
// message d'erreur si la copie du dossier dans uploads n'a pss marché à l'installation
$url_folder = XOOPS_ROOT_PATH . '/uploads/xaddresses/';
if (!is_dir($url_folder)){
    echo '<div class="errorMsg" style="text-align: left;">' . sprintf(_XADDRESSES_MI_INDEX_ERRORPFOLDER, XOOPS_ROOT_PATH) . '</div>'; 
}

//système pour indiquer si l'on utilise la dernière version
//moduleLastVersionInfo( $GLOBALS['xoopsModule']->getVar('version'), $xoopsModule->dirname() );

echo '<div align="center"><a href="http://www.tdmxoops.net" target="_blank"><img src="http://www.tdmxoops.net/images/logo_modules.gif" alt="TDM" title="TDM"></a></div>';
*/
//Affichage de la partie basse de l'administration de Xoops
xoops_cp_footer();
?>