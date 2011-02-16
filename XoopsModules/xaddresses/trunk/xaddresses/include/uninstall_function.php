<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/**
 * Delete a not empty directory
 *
 */
function delDir($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!delDir($dir . DS . $item)) return false;
    }
    return rmdir($dir);
}

/**
 * Desactivate textsanitizer extention
 *
 */
function desactivateExtention() {
    $conf = include XOOPS_ROOT_PATH . '/class/textsanitizer/config.php';
    $conf['extensions']['map'] = 0;
    file_put_contents(XOOPS_ROOT_PATH . '/class/textsanitizer/config.php', "<?php\rreturn \$config = " . var_export($conf, true) . "\r?>");
}

function xoops_module_pre_uninstall_xaddresses(&$xoopsModule) {
    return true;
}

function xoops_module_uninstall_xaddresses(&$xoopsModule) {
	// Desactivate and delete xaddresses textsanitizer extention
    //desactivateExtention();
    //delDir(XOOPS_ROOT_PATH . '/class/textsanitizer/map');
	// Delete xaddresses main upload directory and all subdirectories
    delDir(XOOPS_UPLOAD_PATH . '/xaddresses');
	return true;
}
?>