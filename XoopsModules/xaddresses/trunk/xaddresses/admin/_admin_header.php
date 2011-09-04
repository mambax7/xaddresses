<?php
// Include xoops admin header
include_once '../../../include/cp_header.php';

include_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

include_once 'admin_functions.php'; // admin functions
include_once '../include/functions.php';
include_once '../include/forms.php';

if ( $xoopsUser ) {
    $xoopsModule = XoopsModule::getByDirname('xaddresses');
    if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);
        exit();
    }
} else {
    redirect_header(XOOPS_URL."/",3,_NOPERM);
    exit();
}

if ( !isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])  ) {
	include_once $GLOBALS['xoops']->path( "/class/template.php" );
	$GLOBALS['xoopsTpl'] = new XoopsTpl();
}

// Include language file
xoops_loadLanguage('admin', 'system');
xoops_loadLanguage('admin', $GLOBALS['xoopsModule']->getVar('dirname'));
xoops_loadLanguage('modinfo', $GLOBALS['xoopsModule']->getVar('dirname'));
$myts =& MyTextSanitizer::getInstance();
?>