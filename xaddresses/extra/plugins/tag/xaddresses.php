<?php
function xaddresses_tag_iteminfo(&$items) {
	if(empty($items) || !is_array($items)) {
		return false;
	}
	$items_id = array();    
	foreach(array_keys($items) as $cat_id){
		foreach(array_keys($items[$cat_id]) as $item_id){
			$items_id[] = (int)$item_id;
		}
	}
    $locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
    $locations = $locationHandler->getObjects(new Criteria("loc_id", "(" . implode(", ", $items_id) . ")", "IN"), true);

	foreach(array_keys($items) as $cat_id) {
		foreach(array_keys($items[$cat_id]) as $item_id) {
			if(isset($locations[$item_id])) {
				$item_obj =& $locations[$item_id];
				$items[$cat_id][$item_id] = array(
					'title'		=> $item_obj->getVar("title"),
					'uid'		=> $item_obj->getVar("submitter"),
					'link'		=> "singlefile.php?cid={$item_obj->getVar("cid")}&loc_id={$item_id}",
					'time'		=> $item_obj->getVar("date"),
					'tags'		=> '',
					'content'	=> '',
					);
            }
        }
	}
	unset($locations);
}

function xaddresses_tag_synchronization($mid) {
    $locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
    $link_handler =& xoops_getmodulehandler("link", "tag");
        
    /* clear tag-item links */
    if (version_compare( mysql_get_server_info(), "4.1.0", "ge" )) {
        $sql = "DELETE FROM {$link_handler->table}" .
               " WHERE " .
               " tag_modid = {$mid}" .
               " AND " .
               " ( tag_itemid NOT IN " .
               " ( SELECT DISTINCT {$locationHandler->keyName} " .
               " FROM {$locationHandler->table} " .
               " WHERE {$locationHandler->table}.status > 0" .
               " ) " .
               " )";
    }else {
        $sql = "DELETE {$link_handler->table} FROM {$link_handler->table}" .
               " LEFT JOIN {$locationHandler->table} AS aa ON {$link_handler->table}.tag_itemid = aa.{$locationHandler->keyName} " .
               " WHERE " .
               " tag_modid = {$mid}" .
               " AND " .
               " ( aa.{$locationHandler->keyName} IS NULL" .
               " OR aa.status < 1" .
               " )";
    }
    if (!$result = $link_handler->db->queryF($sql)) {
        //xoops_error($link_handler->db->error());
    }
}
?>