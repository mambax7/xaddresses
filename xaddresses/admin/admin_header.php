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

include_once dirname(dirname(dirname(dirname(__FILE__)))) . '/mainfile.php';
include_once XOOPS_ROOT_PATH . '/include/cp_functions.php';
include_once '../include/config.php';
include_once '../include/functions.php';

    include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    include_once XOOPS_ROOT_PATH . '/class/tree.php';
    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    xoops_load ('XoopsUserUtility');

$pathDir = $GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin');
$globlang = $GLOBALS['xoopsConfig']['language'];

if ( file_exists($pathDir.'/language/'.$globlang.'/main.php')){
	include_once $pathDir.'/language/'.$globlang.'/main.php';        
} else {
	include_once $pathDir.'/language/english/main.php';        
}
    
if ( file_exists($pathDir.'/moduleadmin.php')){
	include_once $pathDir.'/moduleadmin.php';
	//return true;
}else{
	xoops_cp_header();
	echo xoops_error(_AM_XADDRESSES_NOFRAMEWORKS);
	xoops_cp_footer();
	//return false;
}

$dirname = basename(dirname(dirname( __FILE__ ) ));
$module_handler =& xoops_gethandler('module');
$xoopsModule = & $module_handler->getByDirname($dirname); 
$moduleInfo =& $module_handler->get($xoopsModule->getVar('mid'));
$pathImageIcon = XOOPS_URL .'/'. $moduleInfo->getInfo('icons16');
$pathImageAdmin = XOOPS_URL .'/'. $moduleInfo->getInfo('icons32');
$pathImageModule = XOOPS_URL . '/modules/'. $GLOBALS['xoopsModule']->getVar('dirname') .'/images';

$myts =& MyTextSanitizer::getInstance();

// Get user groups
$groupPermHandler =& xoops_gethandler('groupperm');
if ($xoopsUser) {
    $moduleperm_handler =& xoops_gethandler('groupperm');
    if (!$moduleperm_handler->checkRight('module_admin', $xoopsModule->getVar( 'mid' ), $xoopsUser->getGroups())) {
        redirect_header(XOOPS_URL, 1, _NOPERM);
        exit();
    }
} else {
    redirect_header(XOOPS_URL . "/user.php", 1, _NOPERM);
    exit();
}

if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
	include_once(XOOPS_ROOT_PATH."/class/template.php");
	$xoopsTpl = new XoopsTpl();
}

$xoopsTpl->assign('pathImageIcon', $pathImageIcon);
$xoopsTpl->assign('pathImageAdmin', $pathImageAdmin);
//xoops_cp_header();

//Load languages
xoops_loadLanguage('admin', $xoopsModule->getVar("dirname"));
xoops_loadLanguage('modinfo', $xoopsModule->getVar("dirname"));
xoops_loadLanguage('main', $xoopsModule->getVar("dirname"));

// Include module functions
include_once XOOPS_ROOT_PATH . '/modules/'. $GLOBALS['xoopsModule']->getVar('dirname') .'/include/functions.php';
include_once XOOPS_ROOT_PATH . '/modules/'. $GLOBALS['xoopsModule']->getVar('dirname') .'/admin/admin_functions.php'; // admin functions
include_once XOOPS_ROOT_PATH . '/modules/'. $GLOBALS['xoopsModule']->getVar('dirname') .'/include/forms.php';
