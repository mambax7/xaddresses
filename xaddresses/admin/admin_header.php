<?php
// include Xoops admin header
include '../../../include/cp_header.php';

include_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
include_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once XOOPS_ROOT_PATH . '/class/xoopstopic.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

$myts =& MyTextSanitizer::getInstance();

// Only Xoops or Module administrators can access admin area
if ( $GLOBALS['xoopsUser'] ) {
    if ( !$GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid()) ) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);
        exit();
    }
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);
    exit();
}

// Get user groups
$groupPermHandler =& xoops_gethandler('groupperm');
if (is_object($GLOBALS['xoopsUser'])) {
    $groups = $GLOBALS['xoopsUser']->getGroups();
} else {
	$groups = XOOPS_GROUP_ANONYMOUS;
}

if ( !isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])  ) {
    include_once $GLOBALS['xoops']->path( '/class/template.php' );
    $GLOBALS['xoopsTpl'] = new XoopsTpl();
}

// Include language files
xoops_loadLanguage('admin', 'system');
xoops_loadLanguage('modinfo', $GLOBALS['xoopsModule']->getVar('dirname'));
xoops_loadLanguage('admin', $GLOBALS['xoopsModule']->getVar('dirname'));
xoops_loadLanguage('main', $GLOBALS['xoopsModule']->getVar('dirname'));

// Include module functions
include_once XOOPS_ROOT_PATH . '/modules/'. $GLOBALS['xoopsModule']->getVar('dirname') .'/include/functions.php';
include_once XOOPS_ROOT_PATH . '/modules/'. $GLOBALS['xoopsModule']->getVar('dirname') .'/admin/admin_functions.php'; // admin functions
include_once XOOPS_ROOT_PATH . '/modules/'. $GLOBALS['xoopsModule']->getVar('dirname') .'/include/forms.php';
?>