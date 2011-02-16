<?php
function xaddresses_notify_iteminfo($category, $item_id)
{
	global $xoopsModule, $xoopsModuleConfig, $xoopsConfig;
	$item_id = intval($item_id);
	if (empty($xoopsModule) || $xoopsModule->getVar('dirname') != 'xaddresses') {	
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname('xaddresses');
		$config_handler =& xoops_gethandler('config');
		$config =& $config_handler->getConfigsByCat(0,$module->getVar('mid'));
	} else {
		$module =& $xoopsModule;
		$config =& $xoopsModuleConfig;
	}

	if ($category == 'global') {
		$item['name'] = '';
		$item['url'] = '';
		return $item;
	}

	global $xoopsDB;
	if ($category == 'category') {
		// Assume we have a valid category id
		$sql = 'SELECT title FROM ' . $xoopsDB->prefix('xaddresses_cat') . ' WHERE cid = '.$item_id;
		$result = $xoopsDB->query($sql); // TODO: error check
		$result_array = $xoopsDB->fetchArray($result);
		$item['name'] = $result_array['title'];
		$item['url'] = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/viewcat.php?cid=' . $item_id;
		return $item;
	}

	if ($category=='file') {
		// Assume we have a valid file id
		$sql = 'SELECT cid,title FROM '.$xoopsDB->prefix('xaddresses_xaddresses') . ' WHERE loc_id = ' . $item_id;
		$result = $xoopsDB->query($sql); // TODO: error check
		$result_array = $xoopsDB->fetchArray($result);
		$item['name'] = $result_array['title'];
		$item['url'] = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/singlefile.php?cid=' . $result_array['cid'] . '&amp;loc_id=' . $item_id;
		return $item;
	}
}
?>
