<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/**
 * Create a new directory that contains the file 'index.html'
 *
 */
function makeDir($dir) {
    if (!is_dir($dir)){
        if (!mkdir($dir, 0777)){
            return false;
        } else {
    		chmod($dir, 0777);
            if ($fh = @fopen($dir . '/index.html', 'w'))
                fwrite($fh, '<script>history.go(-1);</script>');
            @fclose($fh);
            return true;
        }
    }
}

function xoops_module_pre_install_xaddresses(&$xoopsModule) {
	$moduleId = $xoopsModule->getVar('mid');
	$moduleName = $xoopsModule->getVar('name');
	$moduleDirname = $xoopsModule->getVar('dirname');
	$moduleVersion = $xoopsModule->getVar('version');
    xoops_loadLanguage('modinfo', $moduleDirname);

    // Check if this PHP version is at least PHP 5.0
    if (phpversion() <= 5) {
        $xoopsModule->setErrors(_XADDRESSES_MI_INDEX_ERRORPHP);
        return false;
    }
    // Check if this XOOPS version is at least v2.4.5
    $minSupportedVersion = explode('.', '2.4.5');
    $currentVersion = explode('.', substr(XOOPS_VERSION, 6));
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
    $xoopsModule->setErrors(_XADDRESSES_MI_INDEX_ERRORXOOPSVERSION);
    return false;
}

function xoops_module_install_xaddresses(&$xoopsModule) {
	$moduleId = $xoopsModule->getVar('mid');
	$moduleName = $xoopsModule->getVar('name');
	$moduleDirname = $xoopsModule->getVar('dirname');
	$moduleVersion = $xoopsModule->getVar('version');
    xoops_loadLanguage('modinfo', $moduleDirname);

/*
    $xaddressesfield_Handler =& xoops_getModuleHandler('field', 'xaddresses');
    $obj =& $xaddressesfield_Handler->create();
    $obj->setVar('title', _XADDRESSES_AM_FORMHOMEPAGE);
    $obj->setVar('img', 'homepage.png');
    $obj->setVar('weight', 1);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $xaddressesfield_Handler->insert($obj);
    $obj =& $xaddressesfield_Handler->create();
    $obj->setVar('title', _XADDRESSES_AM_FORMVERSION);
    $obj->setVar('img', 'version.png');
    $obj->setVar('weight', 2);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $xaddressesfield_Handler->insert($obj);
    $obj =& $xaddressesfield_Handler->create();
    $obj->setVar('title', _XADDRESSES_AM_FORMSIZE);
    $obj->setVar('img', 'size.png');
    $obj->setVar('weight', 3);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $xaddressesfield_Handler->insert($obj);
    $obj =& $xaddressesfield_Handler->create();
    $obj->setVar('title', _XADDRESSES_AM_FORMPLATFORM);
    $obj->setVar('img', 'platform.png');
    $obj->setVar('weight', 4);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $xaddressesfield_Handler->insert($obj);
*/   

	makeDir(XOOPS_UPLOAD_PATH . '/' . $moduleName .'');
    // make directory for ajaxfilemanager file manager
	makeDir(XOOPS_UPLOAD_PATH . '/' . $moduleName . '/files');

	return true;
}
?>