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
include 'admin_header.php';
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : (isset($_REQUEST['id']) ? "edit_fieldcategory" : 'list_fieldcategories');

// load classes
$fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory', 'xaddresses');



switch($op ) {
default:
    case 'list_fieldcategories':
    // render start here
    xoops_cp_header();
    // render submenu
    $status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
    $submenuItem[] = ($op == 'new_fieldcategory' ? _AM_XADDRESSES_FIELDCAT_NEW : '<a href="' . $currentFile . '?op=new_fieldcategory">' . _AM_XADDRESSES_FIELDCAT_NEW . '</a>');
    $submenuItem[] = ($op == 'list_fieldcategories' ? _AM_XADDRESSES_FIELDCAT_LIST : '<a href="' . $currentFile . '?op=list_fieldcategories">' . _AM_XADDRESSES_FIELDCAT_LIST . '</a>');
    xaddressesAdminSubmenu ($submenuItem);

    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight');
    $criteria->setOrder('ASC');
    $fieldCategories = $fieldCategoryHandler->getObjects($criteria, true, false);
    foreach ($fieldCategories as $key=>$value)
        $fieldCategories[$key]['canEdit'] = true; // IN PROGRESS

    $GLOBALS['xoopsTpl']->assign('categories', $fieldCategories);

    $GLOBALS['xoopsTpl']->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML() );
    $GLOBALS['xoopsTpl']->display("db:xaddresses_admin_fieldcategorylist.html");
    
    include "admin_footer.php";
    break;



case 'reorder_fieldcategories':
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors() ));
    }
    if (isset($_POST['category_ids']) && count($_POST['category_ids']) > 0) {
        $oldweight = $_POST['oldweight'];
        $weight = $_POST['weight'];
        $ids = array();
        foreach ($_POST['category_ids'] as $category_id ) {
            if ($oldweight[$category_id] != $weight[$category_id] ) {
                //if category has changed
                $ids[] = intval($category_id);
            }
        }
        if ( count($ids) > 0 ) {
            $errors = array();
            //if there are changed fieldcategories, fetch the fieldcategory objects
            $categories = $fieldCategoryHandler->getObjects(new Criteria('cat_id', "(" . implode(',', $ids) . ")", "IN"), true);
            foreach ($ids as $i) {
                $categories[$i]->setVar('cat_weight', intval($weight[$i]));
                if (!$fieldCategoryHandler->insert($categories[$i])) {
                    $errors = array_merge($errors, $categories[$i]->getErrors());
                }
            }
            if (count($errors) == 0) {
                //no errors
                redirect_header($currentFile, 2, sprintf(_AM_XADDRESSES_SAVEDSUCCESS, _AM_XADDRESSES_CATEGORIES));
            } else {
                redirect_header($currentFile, 3, implode('<br />', $errors));
            }
        }
    }
    break;



case 'new_fieldcategory':
    // render start here
    xoops_cp_header();
    // render submenu
    $status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
    $submenuItem[] = ($op == 'new_fieldcategory' ? _AM_XADDRESSES_FIELDCAT_NEW : '<a href="' . $currentFile . '?op=new_fieldcategory">' . _AM_XADDRESSES_FIELDCAT_NEW . '</a>');
    $submenuItem[] = ($op == 'list_fieldcategories' ? _AM_XADDRESSES_FIELDCAT_LIST : '<a href="' . $currentFile . '?op=list_fieldcategories">' . _AM_XADDRESSES_FIELDCAT_LIST . '</a>');
    xaddressesAdminSubmenu ($submenuItem);

    include_once '../include/forms.php';
    $obj =& $fieldCategoryHandler->create();
    $form = $obj->getForm();
    $form->display();
    
    include "admin_footer.php";
    break;



case 'edit_fieldcategory':
    // render start here
    xoops_cp_header();
    // render submenu
    $status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
    $submenuItem[] = ($op == 'new_fieldcategory' ? _AM_XADDRESSES_FIELDCAT_NEW : '<a href="' . $currentFile . '?op=new_fieldcategory">' . _AM_XADDRESSES_FIELDCAT_NEW . '</a>');
    $submenuItem[] = ($op == 'list_fieldcategories' ? _AM_XADDRESSES_FIELDCAT_LIST : '<a href="' . $currentFile . '?op=list_fieldcategories">' . _AM_XADDRESSES_FIELDCAT_LIST . '</a>');
    xaddressesAdminSubmenu ($submenuItem);

    include_once '../include/forms.php';
    $obj = $fieldCategoryHandler->get($_REQUEST['id']);
    $form = $obj->getForm();
    $form->display();
    
    include "admin_footer.php";
    break;



case 'save_fieldcategory':
    if ( !$GLOBALS['xoopsSecurity']->check()  ) {
        redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors() ));
    }
    if ( isset($_REQUEST['id'])  ) {
        $obj =& $fieldCategoryHandler->get($_REQUEST['id']);
    } else {
        $obj =& $fieldCategoryHandler->create();
    }
    $obj->setVar('cat_title', $_REQUEST['cat_title']);
    $obj->setVar('cat_description', $_REQUEST['cat_description']);
    $obj->setVar('cat_weight', $_REQUEST['cat_weight']);
    if ( $fieldCategoryHandler->insert($obj)  ) {
        redirect_header($currentFile, 3, sprintf(_AM_XADDRESSES_SAVEDSUCCESS, _AM_XADDRESSES_CATEGORY) );
    } else {
        // render start here
        xoops_cp_header();
        // render submenu
        $status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
        $submenuItem[] = ($op == 'new_fieldcategory' ? _AM_XADDRESSES_FIELDCAT_NEW : '<a href="' . $currentFile . '?op=new_fieldcategory">' . _AM_XADDRESSES_FIELDCAT_NEW . '</a>');
        $submenuItem[] = ($op == 'list_fieldcategories' ? _AM_XADDRESSES_FIELDCAT_LIST : '<a href="' . $currentFile . '?op=list_fieldcategories">' . _AM_XADDRESSES_FIELDCAT_LIST . '</a>');
        xaddressesAdminSubmenu ($submenuItem);
        
        include_once '../include/forms.php';
        echo $obj->getHtmlErrors();
        $form =& $obj->getForm();
        $form->display();
        
        include "admin_footer.php";
    }
    break;



case 'delete_fieldcategory':
    $obj =& $fieldCategoryHandler->get($_REQUEST['id']);
    if ( isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1 ) {
        if ( !$GLOBALS['xoopsSecurity']->check()  ) {
            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors() ));
        }
        if ( $fieldCategoryHandler->delete($obj)  ) {
            redirect_header($currentFile, 3, sprintf(_AM_XADDRESSES_DELETEDSUCCESS, _AM_XADDRESSES_CATEGORY) );
        } else {
            echo $obj->getHtmlErrors();
        }
    } else {
        // render start here
        xoops_cp_header();
        xoops_confirm(array('ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete_fieldcategory'), $_SERVER['REQUEST_URI'], sprintf(_AM_XADDRESSES_RU_SURE_DEL, $obj->getVar('cat_title') ));
        xoops_cp_footer();
    }
    break;
}
