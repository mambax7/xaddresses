<?php
include "../../mainfile.php";
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH."/class/tree.php";
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';
include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/include/functions.php';

//permission
$gperm_handler =& xoops_gethandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
	$groups = XOOPS_GROUP_ANONYMOUS;
}
$perm_submit = ($gperm_handler->checkRight('xaddresses_ac', 4, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_modif = ($gperm_handler->checkRight('xaddresses_ac', 8, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_vote = ($gperm_handler->checkRight('xaddresses_ac', 16, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_upload = ($gperm_handler->checkRight('xaddresses_ac', 32, $groups, $xoopsModule->getVar('mid'))) ? true : false ;

$myts =& MyTextSanitizer::getInstance();

?>