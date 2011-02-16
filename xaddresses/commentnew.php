<?php
include 'header.php';
$loc_id = isset($_GET['loc_id']) ? (int)($_GET['loc_id']) : 0;
if ($loc_id > 0) {
    // Get location title
    $sql = 'SELECT loc_title, loc_id FROM ' . $xoopsDB->prefix('tdmdownloads_downloads') . " WHERE loc_id=" . $com_itemid;
    $result = $xoopsDB->query($sql);
    if ($result) {
    	$categories = xaddresses_MygetItemIds();
    	$row = $xoopsDB->fetchArray($result);
		if(!in_array($row['cat_id'], $categories)) {
			redirect_header(XOOPS_URL, 2, _NOPERM);
			exit();
		}    	
    	$com_replytitle = $row['cat_title'];
    	include XOOPS_ROOT_PATH.'/include/comment_new.php';
    }
}
?>
