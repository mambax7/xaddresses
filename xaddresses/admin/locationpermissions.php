<?php
include_once 'admin_header.php';
$currentFile = basename(__FILE__);

//load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$fieldHandler =& xoops_getModuleHandler('field', 'xaddresses');
$memberHandler =& xoops_gethandler('member');
// IN PROGRESS
$brokenHandler =& xoops_getModuleHandler('broken', 'xaddresses');
// TO DO
$votedataHandler =& xoops_getModuleHandler('votedata', 'xaddresses');



xoops_cp_header();

// main admin manu
if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php"))	{
    xaddressesAdminMenu(4, _XADDRESSES_MI_ADMENU_LOCATIONPERMISSIONS);
} else {
    include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
    loadModuleAdminMenu (4, _XADDRESSES_MI_ADMENU_LOCATIONPERMISSIONS);
}



$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'in_category_view';

include_once $GLOBALS['xoops']->path( '/class/xoopsformloader.php' );
$opForm = new XoopsSimpleForm('', 'opform', $currentFile, 'get');
$opSelect = new XoopsFormSelect("", 'op', $op);
$opSelect->setExtra('onchange="document.forms.opform.submit()"');
$opSelect->addOption('in_category_view', _XADDRESSES_AM_PERM_VIEW);
$opSelect->addOption('in_category_submit', _XADDRESSES_AM_PERM_SUBMIT);
$opSelect->addOption('in_category_edit', _XADDRESSES_AM_PERM_EDIT);
$opSelect->addOption('in_category_delete', _XADDRESSES_AM_PERM_DELETE); // IN PROGRESS ha senso questo permesso?
$opSelect->addOption('others', _XADDRESSES_AM_PERM_OTHERS); // IN_PROGRESS
$opForm->addElement($opSelect);
$opForm->display();

switch($op) {
case 'in_category_view': // view permission
    $formTitle = _XADDRESSES_AM_PERM_VIEW;
    $permissionName = 'in_category_view';
    $permissionDescription = _XADDRESSES_AM_PERM_VIEW_DSC;
    $anonymous = TRUE;
    break;
case 'in_category_submit': // submit permission
    $formTitle = _XADDRESSES_AM_PERM_SUBMIT;
    $permissionName = 'in_category_submit';
    $permissionDescription = _XADDRESSES_AM_PERM_SUBMIT_DSC;
    $anonymous = TRUE;
    break;
case 'in_category_edit': // edit permission
    $formTitle = _XADDRESSES_AM_PERM_EDIT;
    $permissionName = 'in_category_edit';
    $permissionDescription = _XADDRESSES_AM_PERM_EDIT_DSC;
    $anonymous = TRUE;
    break;
case 'in_category_delete': // edit permission
    $formTitle = _XADDRESSES_AM_PERM_DELETE;
    $permissionName = 'in_category_delete';
    $permissionDescription = _XADDRESSES_AM_PERM_DELETE_DSC;
    $anonymous = TRUE;
    break;

case 'others': // IN_PROGRESS
    $formTitle = _XADDRESSES_AM_PERM_OTHERS;
    $permissionName = "others";
    $permissionDescription = _XADDRESSES_AM_PERM_OTHERS_DSC;
    $anonymous = TRUE;
    $global_perms_array = array(
        '4' => _XADDRESSES_AM_PERMISSIONS_4 ,
        '8' => _XADDRESSES_AM_PERMISSIONS_8 ,
        '16' => _XADDRESSES_AM_PERMISSIONS_16 ,
        '32' => _XADDRESSES_AM_PERMISSIONS_32
         );
    break;
}



$module_id = $GLOBALS['xoopsModule']->getVar('mid');
include_once $GLOBALS['xoops']->path( '/class/xoopsform/grouppermform.php' );
$form = new XoopsGroupPermForm($formTitle, $module_id, $permissionName, $permissionDescription, "admin/" . $currentFile . "?op=" . $op , $anonymous);



switch($op) {
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
        $form->addItem($category->getVar('cat_id'), $category->getVar('cat_title'), $category->getVar('cat_pid'));
    }
    break;

case 'others': // IN_PROGRESS
    foreach( $global_perms_array as $perm_id => $permissionName ) {
        $form->addItem($perm_id , $permissionName) ;
    }
    break;
}



$form->display();
xoops_cp_footer();
?>