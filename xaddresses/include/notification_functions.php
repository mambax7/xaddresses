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
