<?php
// comment callback functions
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

function xaddresses_com_update($loc_id, $total_num) {
	$loc_id = (int)$loc_id;
	$total_num = (int)$total_num;
    // it will be better to use a new location object method
	$db =& Database::getInstance();
	$sql = 'UPDATE ' . $db->prefix('xaddresses_location' ). ' SET loc_comments = ' . $total_num . ' WHERE loc_id = ' . $loc_id;
	if (!$db->query($sql))
        return false;
    else
        return true;
}

function xaddresses_com_approve(&$comment){
	// notification mail here
}
?>