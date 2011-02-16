<?php
include 'admin_header.php';
$currentFile = basename(__FILE__);
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : (isset($_REQUEST['id']) ? "edit_fieldcategory" : 'list_fieldcategories');

// load classes
$fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory', 'xaddresses');



xoops_cp_header();

// main admin menu
if (!is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
    xaddressesAdminMenu(4, _XADDRESSES_MI_ADMENU_FIELDCATEGORY);
} else {
    include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
    loadModuleAdminMenu (4, _XADDRESSES_MI_ADMENU_FIELDCATEGORY);
}



// Submenu
$status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
$submenuItem[] = ($op == 'new_fieldcategory' ? _XADDRESSES_AM_FIELDCAT_NEW : '<a href="' . $currentFile . '?op=new_fieldcategory">' . _XADDRESSES_AM_FIELDCAT_NEW . '</a>');
$submenuItem[] = ($op == 'list_fieldcategories' ? _XADDRESSES_AM_FIELDCAT_LIST : '<a href="' . $currentFile . '?op=list_fieldcategories">' . _XADDRESSES_AM_FIELDCAT_LIST . '</a>');
xaddressesAdminSubmenu ($submenuItem);



switch($op ) {
default:
case 'list_fieldcategories':
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight');
    $criteria->setOrder('ASC');
    $fieldCategories = $fieldCategoryHandler->getObjects($criteria, true, false);
    foreach ($fieldCategories as $key=>$value)
        $fieldCategories[$key]['canEdit'] = true; // IN PROGRESS

    $GLOBALS['xoopsTpl']->assign('categories', $fieldCategories);

    $GLOBALS['xoopsTpl']->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML() );
    $GLOBALS['xoopsTpl']->display("db:xaddresses_admin_fieldcategorylist.html");
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
                redirect_header($currentFile, 2, sprintf(_XADDRESSES_AM_SAVEDSUCCESS, _XADDRESSES_AM_CATEGORIES));
            } else {
                redirect_header($currentFile, 3, implode('<br />', $errors));
            }
        }
    }
    break;



case 'new_fieldcategory':
    include_once '../include/forms.php';
    $obj =& $fieldCategoryHandler->create();
    $form = $obj->getForm();
    $form->display();
    break;



case 'edit_fieldcategory':
    include_once '../include/forms.php';
    $obj = $fieldCategoryHandler->get($_REQUEST['id']);
    $form = $obj->getForm();
    $form->display();
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
        redirect_header($currentFile, 3, sprintf(_XADDRESSES_AM_SAVEDSUCCESS, _XADDRESSES_AM_CATEGORY) );
    }
    include_once '../include/forms.php';
    echo $obj->getHtmlErrors();
    $form =& $obj->getForm();
    $form->display();
    break;



case 'delete_fieldcategory':
    $obj =& $fieldCategoryHandler->get($_REQUEST['id']);
    if ( isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1 ) {
        if ( !$GLOBALS['xoopsSecurity']->check()  ) {
            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors() ));
        }
        if ( $fieldCategoryHandler->delete($obj)  ) {
            redirect_header($currentFile, 3, sprintf(_XADDRESSES_AM_DELETEDSUCCESS, _XADDRESSES_AM_CATEGORY) );
        } else {
            echo $obj->getHtmlErrors();
        }
    } else {
        xoops_confirm(array('ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete_fieldcategory'), $_SERVER['REQUEST_URI'], sprintf(_XADDRESSES_AM_RUSUREDEL, $obj->getVar('cat_title') ));
    }
    break;
}



xoops_cp_footer();
?>