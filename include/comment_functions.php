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
