<?php
$currentFile = basename(__FILE__);
include 'admin_header.php';
$versionInfo =& $module_handler->get($GLOBALS['xoopsModule']->getVar('mid'));

// load classes

// get/check parameters/post

// render start here
xoops_cp_header();

// main admin menu
include (XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->dirname() . '/admin/menu.php');
echo moduleAdminTabMenu($adminmenu, $currentFile);

echo "
    <style type=\"text/css\">
    label,text {
        display: block;
        float: left;
        margin-bottom: 2px;
    }
    label {
        text-align: right;
        width: 150px;
        padding-right: 20px;
    }
    br {
        clear: left;
    }
    </style>
";

if (file_exists(XOOPS_ROOT_PATH . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/help/help.html")) {
    $file = XOOPS_ROOT_PATH . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/help/help.html";
} else {
    $file = XOOPS_ROOT_PATH . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname') . "/language/english/help/help.html";
}
if (is_readable($file)) {
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _XADDRESSES_AM_ABOUT_HELP . "</legend>";
    echo "<div style='padding: 8px;'>";
    echo "<div>" . implode('', file($file)) . "</div>";
    echo "</div>";
    echo "</fieldset>";
    echo "<br clear=\"all\" />";
} else {
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _XADDRESSES_AM_ABOUT_HELP . "</legend>";
    echo "<div style='padding: 8px;'>";
    echo "<div>warning: help.html file not found!</div>";
    echo "</div>";
    echo "</fieldset>";
    echo "<br clear=\"all\" />";
}

xoops_cp_footer();
?>