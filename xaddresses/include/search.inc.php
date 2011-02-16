<?php
function xaddresses_search($queryarray, $andor, $limit, $offset, $userid)
{
	global $xoopsDB;
	
	$sql = "SELECT loc_id, cid, title, description, submitter, date FROM ".$xoopsDB->prefix("xaddresses_xaddresses")." WHERE status != 0";
	
	if ( $userid != 0 ) {
		$sql .= " AND submitter=".intval($userid)." ";
	}
    require_once XOOPS_ROOT_PATH.'/modules/xaddresses/include/functions.php';
    $categories = xaddresses_MygetItemIds();
	if(is_array($categories) && count($categories) > 0) {
		$sql .= ' AND cid IN ('.implode(',', $categories).') ';
	} else {
		return null;
	}
    
	if ( is_array($queryarray) && $count = count($queryarray) ) 
	{
		$sql .= " AND ((title LIKE '%$queryarray[0]%' OR description LIKE '%$queryarray[0]%')";
		
		for($i=1;$i<$count;$i++)
		{
			$sql .= " $andor ";
			$sql .= "(title LIKE '%$queryarray[$i]%' OR description LIKE '%$queryarray[$i]%')";
		}
		$sql .= ")";
	}
	
	$sql .= " ORDER BY date DESC";
	$result = $xoopsDB->query($sql,$limit,$offset);
	$ret = array();
	$i = 0;
	while($myrow = $xoopsDB->fetchArray($result))
	{
		$ret[$i]["image"] = "images/deco/xaddresses_search.png";
		$ret[$i]["link"] = "singlefile.php?cid=".$myrow["cid"]."&loc_id=".$myrow["loc_id"]."";
		$ret[$i]["title"] = $myrow["title"];
		$ret[$i]["time"] = $myrow["date"];
		$ret[$i]["uid"] = $myrow["submitter"];
		$i++;
	}
	return $ret;
}

	
?>