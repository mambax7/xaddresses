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
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html& ...  public license
 * @package         xaddresses
 * @since           1.0
 * @author          luciorota <lucio.rota@gmail.com>
 * @version         $Id$
 */

// include Xoops mainfile
include '../../mainfile.php';

include_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
include_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once XOOPS_ROOT_PATH . '/class/xoopstopic.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

$myts =& MyTextSanitizer::getInstance();

// Include language files
xoops_loadLanguage('admin', 'system');
xoops_loadLanguage('modinfo', $GLOBALS['xoopsModule']->getVar('dirname'));
xoops_loadLanguage('admin', $GLOBALS['xoopsModule']->getVar('dirname'));
xoops_loadLanguage('main', $GLOBALS['xoopsModule']->getVar('dirname'));

// Include module functions
include_once XOOPS_ROOT_PATH . '/modules/'. $GLOBALS['xoopsModule']->getVar('dirname') .'/include/functions.php'; // admin functions
include_once XOOPS_ROOT_PATH . '/modules/'. $GLOBALS['xoopsModule']->getVar('dirname') .'/include/forms.php';

// Get user groups
$groupPermHandler =& xoops_gethandler('groupperm');
if (is_object($GLOBALS['xoopsUser'])) {
    $groups = $GLOBALS['xoopsUser']->getGroups();
} else {
	$groups = XOOPS_GROUP_ANONYMOUS;
}

// Permission
$viewableCategoriesIds = xaddresses_getMyItemIds('in_category_view');
$editableCategoriesIds = xaddresses_getMyItemIds('in_category_edit');
$submitableCategoriesIds =  xaddresses_getMyItemIds('in_category_submit');

// Get extra permissions
$permModifySubmitter = ($groupPermHandler->checkRight('others', 1, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
$permModifyDate = ($groupPermHandler->checkRight('others', 2, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
$permSubmit = ($groupPermHandler->checkRight('others', 4, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
$permSuggestModify = ($groupPermHandler->checkRight('others', 8, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
$permTellAFriend = ($groupPermHandler->checkRight('others', 16, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
$permRate = ($groupPermHandler->checkRight('others', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
$permReportBroken = ($groupPermHandler->checkRight('others', 64, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
//$perm128 = ($groupPermHandler->checkRight('others', 128, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
//$perm256 = ($groupPermHandler->checkRight('others', 256, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;

if ( !isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])  ) {
    include_once $GLOBALS['xoops']->path( '/class/template.php' );
    $GLOBALS['xoopsTpl'] = new XoopsTpl();
}
