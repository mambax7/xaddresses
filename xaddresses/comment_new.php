<?php
include 'header.php';

// We verify that the user can post comments **********************************
if(!isset($xoopsModuleConfig)) die();
// Comments are deactivate
if($xoopsModuleConfig['com_rule'] == 0) die();
// Anonymous users can't post
if($xoopsModuleConfig['com_anonpost'] == 0 && !is_object($GLOBALS['xoopsUser'])) die();


$com_itemid = isset($_GET['com_itemid']) ? (int)($_GET['com_itemid']) : 0;
if ($com_itemid > 0) {
    // Get location title
    $sql = 'SELECT loc_title, loc_id, loc_cat_id';
    $sql.= ' FROM ' . $xoopsDB->prefix('xaddresses_location');
    $sql.= ' WHERE loc_id=' . $com_itemid;
    $result = $xoopsDB->query($sql);
    if ($result) {
        $categories = xaddresses_MygetItemIds('in_category_view');
        $row = $xoopsDB->fetchArray($result);
        if(!in_array($row['loc_cat_id'], $categories)) {
            redirect_header(XOOPS_URL, 2, "PIPPO" . _NOPERM);
            exit();
        }
        $com_replytitle = $row['loc_title'];
        include XOOPS_ROOT_PATH . '/include/comment_new.php';
    }
}
?>