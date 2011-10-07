<?php
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

include_once XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/include/functions.php';
include_once XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/include/forms.php';

$myts =& MyTextSanitizer::getInstance();

//permission
$groupPermHandler =& xoops_gethandler('groupperm');
if (is_object($GLOBALS['xoopsUser'])) {
    $groups = $GLOBALS['xoopsUser']->getGroups();
} else {
	$groups = XOOPS_GROUP_ANONYMOUS;
}
$viewableCategories = $groupPermHandler->getItemIds('in_category_view', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
$editableCategories = $groupPermHandler->getItemIds('in_category_edit', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
$submitableCategories = $groupPermHandler->getItemIds('in_category_submit', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
// Get extra permissions
$permModifySubmitter = ($groupPermHandler->checkRight('others', 1, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
$permModifyDate = ($groupPermHandler->checkRight('others', 2, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
$permSubmit = ($groupPermHandler->checkRight('others', 4, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
$permModif = ($groupPermHandler->checkRight('others', 8, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
$permTellAFriend = ($groupPermHandler->checkRight('others', 16, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
$permRate = ($groupPermHandler->checkRight('others', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
$permReportBroken = ($groupPermHandler->checkRight('others', 64, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
//$perm128 = ($groupPermHandler->checkRight('others', 128, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
//$perm256 = ($groupPermHandler->checkRight('others', 256, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
/*
if ( $xoopsUser ) {
    if ( !$xoopsUser->isAdmin($GLOBALS['xoopsModule']->mid()) ) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);
        exit();
    }
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);
    exit();
}
*/

if ( !isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])  ) {
    include_once $GLOBALS['xoops']->path( '/class/template.php' );
    $GLOBALS['xoopsTpl'] = new XoopsTpl();
}

// include language files
xoops_loadLanguage('admin', 'system');
xoops_loadLanguage('modinfo', $GLOBALS['xoopsModule']->getVar('dirname'));
xoops_loadLanguage('admin', $GLOBALS['xoopsModule']->getVar('dirname'));
xoops_loadLanguage('main', $GLOBALS['xoopsModule']->getVar('dirname'));
?>