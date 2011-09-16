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

// Get ids of categories in which locations can be viewed/edited/submitted
$groupPermHandler =& xoops_gethandler('groupperm');
$viewableCategories = $groupPermHandler->getItemIds('in_category_view', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
$editableCategories = $groupPermHandler->getItemIds('in_category_edit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
$submitableCategories = $groupPermHandler->getItemIds('in_category_submit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );

/*
$perm_submit = ($gperm_handler->checkRight('xaddresses_ac', 4, $GLOBALS['xoopsUser']->getGroups(), $xoopsModule->getVar('mid'))) ? true : false ;
$perm_modif = ($gperm_handler->checkRight('xaddresses_ac', 8, $GLOBALS['xoopsUser']->getGroups(), $xoopsModule->getVar('mid'))) ? true : false ;
$perm_vote = ($gperm_handler->checkRight('xaddresses_ac', 16, $GLOBALS['xoopsUser']->getGroups(), $xoopsModule->getVar('mid'))) ? true : false ;
$perm_upload = ($gperm_handler->checkRight('xaddresses_ac', 32, $GLOBALS['xoopsUser']->getGroups(), $xoopsModule->getVar('mid'))) ? true : false ;


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