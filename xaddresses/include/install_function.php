<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

function xoops_module_pre_install_xaddresses(&$xoopsModule) {
    // Check if this XOOPS version is supported
    $minSupportedVersion = explode('.', '2.4.5');
    $currentVersion = explode('.', substr(XOOPS_VERSION,6));
    if($currentVersion[0] > $minSupportedVersion[0]) {
        return true;
    } elseif($currentVersion[0] == $minSupportedVersion[0]) {
        if($currentVersion[1] > $minSupportedVersion[1]) {
            return true;
        } elseif($currentVersion[1] == $minSupportedVersion[1]) {
            if($currentVersion[2] > $minSupportedVersion[2]) {
                return true;
            } elseif ($currentVersion[2] == $minSupportedVersion[2]) {
                return true;
            }
        }
    }
    return false;
}

function xoops_module_install_xaddresses(&$xoopsModule) {
    xoops_loadLanguage('modinfo', $xoopsModule->getVar('dirname'));
    include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/functions.php';

    $ret = true;
    $msg = '';
    // Create xaddresses main upload directory
    $dir = XOOPS_ROOT_PATH . "/uploads/xaddresses";
	if (!makeDir($dir))
        $msg.= sprintf(_MI_XADDRESSES_WARNING_DIRNOTCREATED, $dir);
    if (empty($msg))
        return $ret;
    else
        return $msg;
}
?>