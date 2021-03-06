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

// include module admin header
include_once 'admin_header.php';

//load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$fieldHandler =& xoops_getModuleHandler('field', 'xaddresses');
$memberHandler =& xoops_gethandler('member');

// count location categories
$countCategories = $categoryHandler->getCount();
// redirection if no categories
if ($categoryHandler->getCount() == 0) {
    redirect_header('index.php', 2, _AM_XADDRESSES_PERM_NOCAT);
}

// get/check parameters/post
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'in_category_view';

// render start here
xoops_cp_header();

switch ($op) {
default:
case 'in_category_view':
case 'in_category_submit':
case 'in_category_edit':
case 'in_category_delete':
    // submenu
    $submenuItem[] = _AM_XADDRESSES_PERM_ITEM_PERMISSIONS ;
    $submenuItem[] = '<a href="' . $currentFile . '?op=field_view">' . _AM_XADDRESSES_PERM_FIELD_PERMISSIONS . '</a>';
    $submenuItem[] = '<a href="' . $currentFile . '?op=extra">' . _AM_XADDRESSES_PERM_EXTRA_PERMISSIONS . '</a>';
    xaddressesAdminSubmenu ($submenuItem);
    // permissions selector
    include_once $GLOBALS['xoops']->path( '/class/xoopsformloader.php' );
    $opForm = new XoopsSimpleForm('', 'opform', $currentFile, 'get');
    $opSelect = new XoopsFormSelect("", 'op', $op);
    $opSelect->setExtra('onchange="document.forms.opform.submit()"');
    $opSelect->addOption('in_category_view', _AM_XADDRESSES_PERM_VIEW);
    $opSelect->addOption('in_category_submit', _AM_XADDRESSES_PERM_SUBMIT);
    $opSelect->addOption('in_category_edit', _AM_XADDRESSES_PERM_EDIT);
    $opSelect->addOption('in_category_delete', _AM_XADDRESSES_PERM_DELETE); // IN PROGRESS ha senso questo permesso?
    $opForm->addElement($opSelect);
    $opForm->display();
    break;
case 'field_view':
case 'field_edit':
case 'field_search':
case 'field_export': // IN PROGRESS
    // submenu
    $submenuItem[] = '<a href="' . $currentFile . '?op=in_category_view">' . _AM_XADDRESSES_PERM_ITEM_PERMISSIONS . '</a>';
    $submenuItem[] = _AM_XADDRESSES_PERM_FIELD_PERMISSIONS ;
    $submenuItem[] = '<a href="' . $currentFile . '?op=extra">' . _AM_XADDRESSES_PERM_EXTRA_PERMISSIONS . '</a>';
    xaddressesAdminSubmenu ($submenuItem);
    // permissions selector
    include_once $GLOBALS['xoops']->path( '/class/xoopsformloader.php' );
    $opForm = new XoopsSimpleForm('', 'opform', $currentFile, 'get');
    $opSelect = new XoopsFormSelect("", 'op', $op);
    $opSelect->setExtra('onchange="document.forms.opform.submit()"');
    $opSelect->addOption('field_view', _AM_XADDRESSES_PERM_VIEWFIELD);
    $opSelect->addOption('field_edit', _AM_XADDRESSES_PERM_EDITFIELD);
    $opSelect->addOption('field_export', _AM_XADDRESSES_PERM_EXPORTFIELD); // IN PROGRESS
    $opSelect->addOption('field_search', _AM_XADDRESSES_PERM_SEARCHFIELD); // IN PROGRESS
    $opForm->addElement($opSelect);
    $opForm->display();
    break;
case 'extra':
    // submenu
    $submenuItem[] = '<a href="' . $currentFile . '?op=in_category_view">' . _AM_XADDRESSES_PERM_ITEM_PERMISSIONS . '</a>';
    $submenuItem[] = '<a href="' . $currentFile . '?op=field_view">' . _AM_XADDRESSES_PERM_FIELD_PERMISSIONS . '</a>';
    $submenuItem[] = _AM_XADDRESSES_PERM_EXTRA_PERMISSIONS ;
    xaddressesAdminSubmenu ($submenuItem);
    // permissions selector
    include_once $GLOBALS['xoops']->path( '/class/xoopsformloader.php' );
    $opForm = new XoopsSimpleForm('', 'opform', $currentFile, 'get');
    $opSelect = new XoopsFormSelect("", 'op', $op);
    $opSelect->setExtra('onchange="document.forms.opform.submit()"');
    $opSelect->addOption('extra', _AM_XADDRESSES_PERM_OTHERS); // IN_PROGRESS
    $opForm->addElement($opSelect);
    $opForm->display();
    break;
}
   
switch($op) {
default:
case 'in_category_view': // view permission
    $titleOfForm = _AM_XADDRESSES_PERM_VIEW;
    $permName = 'in_category_view';
    $permDesc = _AM_XADDRESSES_PERM_VIEW_DESC;
    $anonymous = true;
    break;
case 'in_category_submit': // submit permission
    $titleOfForm = _AM_XADDRESSES_PERM_SUBMIT;
    $permName = 'in_category_submit';
    $permDesc = _AM_XADDRESSES_PERM_SUBMIT_DESC;
    $anonymous = true;
    break;
case 'in_category_edit': // edit permission
    $titleOfForm = _AM_XADDRESSES_PERM_EDIT;
    $permName = 'in_category_edit';
    $permDesc = _AM_XADDRESSES_PERM_EDIT_DESC;
    $anonymous = true;
    break;
case 'in_category_delete': // edit permission
    $titleOfForm = _AM_XADDRESSES_PERM_DELETE;
    $permName = 'in_category_delete';
    $permDesc = _AM_XADDRESSES_PERM_DELETE_DESC;
    $anonymous = true;
    break;
case 'field_view': // view field permission
    $titleOfForm = _AM_XADDRESSES_PERM_VIEWFIELD;
    $permName = 'field_view';
    $permDesc = _AM_XADDRESSES_PERM_VIEWFIELD_DESC;
    $restriction = 'field_edit';
    $anonymous = true;
    break;
case 'field_edit': // edit field permission
    $titleOfForm = _AM_XADDRESSES_PERM_EDITFIELD;
    $permName = 'field_edit';
    $permDesc = _AM_XADDRESSES_PERM_EDITFIELD_DESC;
    $restriction = '';
    $anonymous = true;
    break;
case 'field_search': // search field permission
    $titleOfForm = _AM_XADDRESSES_PERM_SEARCHFIELD;
    $permName = 'field_search';
    $permDesc = _AM_XADDRESSES_PERM_SEARCHFIELD_DESC;
    $restriction = '';
    $anonymous = true;
    break;
case 'field_export': // export field permission // IN PROGRESS
    $titleOfForm = _AM_XADDRESSES_PERM_EXPORTFIELD;
    $permName = 'field_export';
    $permDesc = _AM_XADDRESSES_PERM_EXPORTFIELD_DESC;
    $restriction = '';
    $anonymous = true;
    break;
case 'extra': // IN_PROGRESS
    $titleOfForm = _AM_XADDRESSES_PERM_OTHERS;
    $permName = "others";
    $permDesc = _AM_XADDRESSES_PERM_OTHERS_DESC;
    $anonymous = true;
    $globalPermsArray = array(
        '1'     => _AM_XADDRESSES_PERMISSIONS_1 ,   // Modify location submitter
        '2'     => _AM_XADDRESSES_PERMISSIONS_2 ,   // Modify location date
        //'4'     => _AM_XADDRESSES_PERMISSIONS_4 ,   // Submit a location
        '8'     => _AM_XADDRESSES_PERMISSIONS_8 ,   // Suggest location correction/modification
        '16'    => _AM_XADDRESSES_PERMISSIONS_16 ,  // Send to a Friend
        '32'    => _AM_XADDRESSES_PERMISSIONS_32,   // Rate location
        '64'    => _AM_XADDRESSES_PERMISSIONS_64,   // Report broken location
        //'128'   => _AM_XADDRESSES_PERMISSIONS_128,
        //'256'   => _AM_XADDRESSES_PERMISSIONS_256,
         );
    break;
}

// render permissions grid
$module_id = $GLOBALS['xoopsModule']->getVar('mid');
include_once $GLOBALS['xoops']->path( '/class/xoopsform/grouppermform.php' );
$permissionsForm = new XoopsGroupPermForm($titleOfForm, $module_id, $permName, $permDesc, "admin/" . $currentFile . "?op=" . $op , $anonymous);
switch($op) {
default:
case 'in_category_view': // view
case 'in_category_submit': // submit
case 'in_category_edit': // edit
case 'in_category_delete': // edit
    // location categories
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $categoriesArray = $categoryHandler->getall($criteria);
    foreach ($categoriesArray as $category) {
        $permissionsForm->addItem($category->getVar('cat_id'), $category->getVar('cat_title'), $category->getVar('cat_pid'));
    }
    break;
case 'field_view': // view field
case 'field_edit': // edit field
case 'field_export': // export field // IN PROGRESS
    $fields = $locationHandler->loadFields();
    foreach (array_keys($fields) as $i ) {
        if ($restriction == '' || $fields[$i]->getVar($restriction)) {
            $permissionsForm->addItem($fields[$i]->getVar('field_id'), xoops_substr($fields[$i]->getVar('field_title'), 0, 25));
        }
    }
    break;
case 'field_search': // search field permission
    $fields = $locationHandler->loadFields();
    $searchableTypes = array(
        'textbox',
        'select',
        'radio',
        'yesno',
        'date',
        'datetime',
        'timezone',
        'language');
    foreach (array_keys($fields) as $i ) {
        if (in_array($fields[$i]->getVar('field_type'), $searchableTypes)) {
            $permissionsForm->addItem($fields[$i]->getVar('field_id'), xoops_substr($fields[$i]->getVar('field_title'), 0, 25));
        }
    }
    break;
case 'extra': // IN_PROGRESS
    foreach( $globalPermsArray as $permId => $permName ) {
        $permissionsForm->addItem($permId, $permName) ;
    }
    break;
}
$permissionsForm->display();
unset ($permissionsForm);

include "admin_footer.php";
