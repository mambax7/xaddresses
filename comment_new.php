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

$currentFile = basename(__FILE__);

// include module header
include_once 'header.php';

// We verify that the user can post comments **********************************
if(!isset($xoopsModuleConfig)) die();
// Comments are deactivate
if($xoopsModuleConfig['com_rule'] == 0) die();
// Anonymous users can't post
if($xoopsModuleConfig['com_anonpost'] == 0 && !is_object($GLOBALS['xoopsUser'])) die();


$com_itemid = isset($_GET['com_itemid']) ? (int)($_GET['com_itemid']) : 0;
if ($com_itemid > 0) {
    // Get location title
    $sql = 'SELECT loc_title, loc_id, loc_cat_id';
    $sql.= ' FROM ' . $xoopsDB->prefix('xaddresses_location');
    $sql.= ' WHERE loc_id=' . $com_itemid;
    $result = $xoopsDB->query($sql);
    if ($result) {
        $viewableCategoriesIds = xaddresses_getMyItemIds('in_category_view');
        $row = $xoopsDB->fetchArray($result);
        if(!in_array($row['loc_cat_id'], $viewableCategoriesIds)) {
            redirect_header(XOOPS_URL, 2, "PIPPO" . _NOPERM);
            exit();
        }
        $com_replytitle = $row['loc_title'];
        include XOOPS_ROOT_PATH . '/include/comment_new.php';
    }
}
