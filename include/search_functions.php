<?php
/**
 * ****************************************************************************
 *  - A Project by Developers TEAM For Xoops - ( http://www.xoops.org )
 * ****************************************************************************
 *  XADDRESSES - MODULE FOR XOOPS
 *  Copyright (c) 2007 - 2012
 *  Rota Lucio ( http://luciorota.altervista.org/xoops/ )
 *
 *  You may not change or alter any portion of this comment or credits
 *  of supporting developers from this source code or any supporting
 *  source code which is considered copyrighted (c) material of the
 *  original comment or credit authors.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  ---------------------------------------------------------------------------
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html& ...  public license
 * @package         xaddresses
 * @since           1.0
 * @author          luciorota <lucio.rota@gmail.com>
 * @version         $Id$
 */

function xaddresses_search($queryarray, $andor, $limit, $offset, $userid = 0) {
	global $xoopsDB;
    require_once XOOPS_ROOT_PATH . '/modules/xaddresses/include/functions.php';
	
	$sql = "SELECT loc_id, loc_cat_id, loc_title, loc_submitter, loc_date";
    $sql.= " FROM " . $xoopsDB->prefix("xaddresses_xaddresses");
    $sql.= " WHERE loc_status != 0"; // valid location
    $sql.= " AND loc_suggested == 0";
	
	if ( $userid != 0 ) {
		$sql .= " AND submitter=" . (int)$userid . " ";
	}
    
    $viewableCategoriesIds = xaddresses_getMyItemIds('in_category_view');
	if(is_array($viewableCategoriesIds) && count($viewableCategoriesIds) > 0) {
		$sql .= ' AND loc_cat_id IN (' . implode(',', $viewableCategoriesIds) . ') ';
	} else {
		return null;
	}
    
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$sql .= " AND ((loc_title LIKE '%$queryarray[0]%')";
		
		for($i=1;$i<$count;$i++) {
			$sql .= " $andor ";
			$sql .= "(loc_title LIKE '%$queryarray[$i]%')";
		}
		$sql .= ")";
	}
	
	$sql .= " ORDER BY loc_date DESC";
	$result = $xoopsDB->query($sql, $limit, $offset);
	$ret = array();
	$i = 0;
	while($myrow = $xoopsDB->fetchArray($result)) {
		//$ret[$i]["image"] = "images/xaddresses_search.png";
		$ret[$i]["link"] = "locationview.php?cat_id=" . $myrow["loc_cat_id"] . "&loc_id=" . $myrow["loc_id"]."";
		$ret[$i]["title"] = $myrow["loc_title"];
		$ret[$i]["time"] = $myrow["loc_date"];
		$ret[$i]["uid"] = $myrow["Loc_submitter"];
		$i++;
	}
	return $ret;
}
