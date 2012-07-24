<?php   
/**
 * ****************************************************************************
 *  - A Project by Developers TEAM For Xoops - ( http://www.xoops.org )
 * ****************************************************************************
 *  XADDRESSES - MODULE FOR XOOPS
 *  Copyright (c) 2007 - 2012
 *  Rota Lucio ( http://luciorota.altervista.org/xoops/ )
 *
 *  You may not change or alter any portion of this comment or credits
 *  of supporting developers from this source code or any supporting
 *  source code which is considered copyrighted (c) material of the
 *  original comment or credit authors.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  ---------------------------------------------------------------------------
 *  @copyright  Rota Lucio ( http://luciorota.altervista.org/xoops/ )
 *  @license    GPL see LICENSE
 *  @package    xaddresses
 *  @author     Rota Lucio ( lucio.rota@gmail.com )
 *
 *  Version : 1.0 Mon 2012/07/23 14:17:52 : XOOPS Exp $
 * ****************************************************************************
 */

$dirname = basename( dirname( dirname( __FILE__ ) ) ) ;
$module_handler =& xoops_gethandler("module");
$xoopsModule =& XoopsModule::getByDirname($dirname);
$moduleInfo =& $module_handler->get($xoopsModule->getVar("mid"));
$pathImageAdmin = $moduleInfo->getInfo("icons32");	
	
$adminmenu = array();
$i = 1;
$i++;
$adminmenu[$i]['name'] = 'Index';
$adminmenu[$i]['title'] = _MI_XADDRESSES_ADMENU_INDEX;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['desc'] = _MI_XADDRESSES_ADMENU_INDEX_DESC;
$adminmenu[$i]['icon'] = "../../{$pathImageAdmin}/house.png";
$i++;
$adminmenu[$i]['name'] = 'Locationcategory';
$adminmenu[$i]['title'] = _MI_XADDRESSES_ADMENU_ITEMCATEGORY;
$adminmenu[$i]['link'] = "admin/locationcategory.php";
$adminmenu[$i]['desc'] = _MI_XADDRESSES_ADMENU_ITEMCATEGORY_DESC;
$adminmenu[$i]['icon'] = "../../{$pathImageAdmin}/folder_map.png";
$i++;
$adminmenu[$i]['name'] = 'Location';
$adminmenu[$i]['title'] = _MI_XADDRESSES_ADMENU_ITEM;
$adminmenu[$i]['link'] = "admin/location.php";
$adminmenu[$i]['desc'] = _MI_XADDRESSES_ADMENU_ITEM_DESC;
$adminmenu[$i]['icon'] = "../../{$pathImageAdmin}/map.png";
$i++;
$adminmenu[$i]['name'] = 'Fieldcategory';
$adminmenu[$i]['title'] = _MI_XADDRESSES_ADMENU_FIELDCATEGORY;
$adminmenu[$i]['link'] = "admin/fieldcategory.php";
$adminmenu[$i]['desc'] = _MI_XADDRESSES_ADMENU_FIELDCATEGORY_DESC;
$adminmenu[$i]['icon'] = "../../{$pathImageAdmin}/folder_database.png";
$i++;
$adminmenu[$i]['name'] = 'Field';
$adminmenu[$i]['title'] = _MI_XADDRESSES_ADMENU_FIELD;
$adminmenu[$i]['link'] = "admin/field.php";
$adminmenu[$i]['desc'] = _MI_XADDRESSES_ADMENU_FIELD_DESC;
$adminmenu[$i]['icon'] = "../../{$pathImageAdmin}/database.png";
$i++;
$adminmenu[$i]['name'] = 'Permissions';
$adminmenu[$i]['title'] = _MI_XADDRESSES_ADMENU_PERMISSIONS;
$adminmenu[$i]['link'] = "admin/permissions.php";
$adminmenu[$i]['desc'] = _MI_XADDRESSES_ADMENU_PERMISSIONS_DESC;
$adminmenu[$i]['icon'] = "../../{$pathImageAdmin}/lock.png";
$i++;
$adminmenu[$i]['name'] = 'Import';
$adminmenu[$i]['title'] = _MI_XADDRESSES_ADMENU_IMPORT;
$adminmenu[$i]['link'] = "admin/import.php";
$adminmenu[$i]['desc'] = _MI_XADDRESSES_ADMENU_IMPORT_DESC;
$adminmenu[$i]['icon'] = "../../{$pathImageAdmin}/folder_put.png";
$i++;
$adminmenu[$i]['name'] = 'About';
$adminmenu[$i]['title'] = _MI_XADDRESSES_ADMENU_ABOUT;
$adminmenu[$i]['link'] = "admin/about.php";
$adminmenu[$i]['desc'] = _MI_XADDRESSES_ADMENU_ABOUT_DESC;
$adminmenu[$i]['icon'] = "../../{$pathImageAdmin}/information.png";
/*
$i++;
$adminmenu[$i]['name'] = 'Help';
$adminmenu[$i]['title'] = _MI_XADDRESSES_ADMENU_HELP;
$adminmenu[$i]['link'] = "admin/help.php";
$adminmenu[$i]['desc'] = _MI_XADDRESSES_ADMENU_HELP_DESC;
$adminmenu[$i]['icon'] = "../../{$pathImageAdmin}/help.png";
*/
unset( $i );
?>