<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

function xoops_module_pre_uninstall_xaddresses(&$xoopsModule) {
    return true;
}

function xoops_module_uninstall_xaddresses(&$xoopsModule) {
    include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/functions.php';
	// Delete xaddresses main upload directory and all subdirectories
    delDir(XOOPS_UPLOAD_PATH . '/xaddresses');
	return true;
}
?>