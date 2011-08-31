<?php
//================================================================
// What's New Module
// get addresses from module
// for xaddresses
// 12/02/2011 Rota Lucio
//================================================================

function xaddresses_new($limit=0, $offset=0) {
    global $xoopsDB;
    $myts =& MyTextSanitizer::getInstance();
    $sql = "SELECT loc_id, loc_cat_id, loc_title, loc_submitter, loc_date";
    $sql.= " FROM " . $xoopsDB->prefix("xaddresses_location");
    $sql.= " WHERE loc_status>0";
    $sql.= " ORDER BY loc_date";
    $result = $xoopsDB->query($sql, $limit, $offset);
    $i = 0;
    $ret = array();
    while( $row = $xoopsDB->fetchArray($result) ) {
        $loc_id = $row['loc_id'];
        $ret[$i]['link']     = XOOPS_URL . "/modules/xaddresses/locationview.php?loc_id=".$loc_id;
        $ret[$i]['cat_link'] = XOOPS_URL . "/modules/xaddresses/categoryview.php?cat_id=".$row['loc_cat_id'];
        $ret[$i]['title']    = $row['loc_title'];
        $ret[$i]['time']     = $row['loc_date'];
        // atom feed
        $ret[$i]['id'] = $loc_id;
        $ret[$i]['description'] = $myts->makeTareaData4Show( $row['loc_description'], 0 ); //no html // IN PROGRESS
        // category
        //$ret[$i]['cat_name'] = $row['loc_cat_id']; // IN PROGRESS
        // counter
        $ret[$i]['hits'] = $row['loc_hits']; // IN PROGRESS
        // this module dont show user name
        $ret[$i]['uid'] = $row['loc_submitter'];
        $i++;
    }
    return $ret;
}

function xaddresses_num() {
    global $xoopsDB;
    $sql = "SELECT count(*)";
    $sql.= " FROM " . $xoopsDB->prefix("xaddresses_location");
    $sql.= " WHERE loc_status>0";
    $sql.= " ORDER BY loc_id";
    $array = $xoopsDB->fetchRow( $xoopsDB->query($sql) );
    $num   = $array[0];
    if (empty($num)) $num = 0;
    return $num;
}

function xaddresses_data($limit=0, $offset=0) {
    global $xoopsDB;
    $sql = "SELECT loc_id, loc_title, loc_date";
    $sql.= " FROM " . $xoopsDB->prefix("xaddresses_location");
    $sql.= " WHERE loc_status>0";
    $sql.= " ORDER BY loc_id";
    $result = $xoopsDB->query($sql, $limit, $offset);
    $i = 0;
    $ret = array();
    while($myrow = $xoopsDB->fetchArray($result)) {
        $loc_id = $myrow['loc_id'];
        $ret[$i]['id']   = $loc_id;
        $ret[$i]['link'] = XOOPS_URL . "/modules/xaddresses/locationview.php?loc_id=" . $loc_id . "";
        $ret[$i]['title'] = $myrow['loc_title'];
        $ret[$i]['time']  = $myrow['loc_date'];
        $i++;
    }
    return $ret;
}
?>