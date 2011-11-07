<?php
function xaddresses_notify_iteminfo($category, $item_id) {
	global $xoopsModule, $xoopsModuleConfig, $xoopsConfig;
	$item_id = (int)$item_id;
	if (empty($GLOBALS['xoopsModule']) || $GLOBALS['xoopsModule']->getVar('dirname') != 'xaddresses') {	
		$configHandler =& xoops_gethandler('config');
		$config =& $configHandler->getConfigsByCat(0, $GLOBALS['xoopsModule']->getVar('mid'));
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
		$sql = 'SELECT cat_title FROM ' . $xoopsDB->prefix('xaddresses_locationcategory') . ' WHERE cat_id = '.$item_id;
		$result = $xoopsDB->query($sql); // TODO: error check
		$result_array = $xoopsDB->fetchArray($result);
		$item['name'] = $result_array['cat_title'];
		$item['url'] = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/locationcategoryview.php?cat_id=' . $item_id;
		return $item;
	}

	if ($category == 'location') {
		// Assume we have a valid file id
		$sql = 'SELECT loc_cat_id, loc_title FROM '.$xoopsDB->prefix('xaddresses_location') . ' WHERE loc_id = ' . $item_id;
		$result = $xoopsDB->query($sql); // TODO: error check
		$result_array = $xoopsDB->fetchArray($result);
		$item['name'] = $result_array['loc_title'];
		$item['url'] = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/locationview.php?loc_cat_id=' . $result_array['loc_cat_id'] . '&amp;loc_id=' . $item_id;
		return $item;
	}
}
?>