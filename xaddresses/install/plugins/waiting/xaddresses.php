<?php
/*************************************************************************/
# Waiting Contents Extensible                                            #
# Plugin for module Xaddresses                                           #
#                                                                        #
# Author          -   Lucio ROta                                         #
# Danordesign     -   lucio.rota@gmail.com                               #
#                                                                        #
# Last modified on 11/11/2010                                            #
/*************************************************************************/
function b_waiting_xaddresses()
{
	$xoopsDB =& Database::getInstance();
	$ret = array() ;

	// Xaddresses waiting
	$block = array();
	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("xaddresses_location")." WHERE loc_status=0");
	if ( $result ) {
		$block['adminlink'] = XOOPS_URL."/modules/xaddresses/admin/addresses.php?op=list&statut_display=0";
		list($block['pendingnum']) = $xoopsDB->fetchRow($result);
		$block['lang_linkname'] = _PI_WAITING_WAITINGS ;
	}
	$ret[] = $block ;

	// Xaddresses broken
	$block = array();
	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("xaddresses_broken"));
	if ( $result ) {
		$block['adminlink'] = XOOPS_URL."/modules/xaddresses/admin/broken.php";
		list($block['pendingnum']) = $xoopsDB->fetchRow($result);
		$block['lang_linkname'] = _PI_WAITING_BROKENS ;
	}
	$ret[] = $block ;

	// Xaddresses modreq
	$block = array();
	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("xaddresses_mod"));
	if ( $result ) {
		$block['adminlink'] = XOOPS_URL."/modules/xaddresses/admin/modified.php";
		list($block['pendingnum']) = $xoopsDB->fetchRow($result);
		$block['lang_linkname'] = _PI_WAITING_MODREQS ;
	}
	$ret[] = $block ;
	
	return $ret;
}
?>