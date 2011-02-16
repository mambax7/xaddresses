<?php
// comment callback functions

function xaddresses_com_update($address_id, $total_num){
	$db =& Database::getInstance();
	$sql = 'UPDATE '.$db->prefix('xaddresses_xaddresses').' SET comments = '.$total_num.' WHERE cid = '.$address_id;
	$db->query($sql);
}

function xaddresses_com_approve(&$comment){
	// notification mail here
}
?>