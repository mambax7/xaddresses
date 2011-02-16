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
    xaddressesAdminMenu(7, _XADDRESSES_MI_ADMENU_FIELDPERMISSIONS);
} else {
    include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
    loadModuleAdminMenu (7, _XADDRESSES_MI_ADMENU_FIELDPERMISSIONS);
}



$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'in_category_view';

include_once $GLOBALS['xoops']->path( '/class/xoopsformloader.php' );
$opForm = new XoopsSimpleForm('', 'opform', $currentFile, 'get');
$opSelect = new XoopsFormSelect("", 'op', $op);
$opSelect->setExtra('onchange="document.forms.opform.submit()"');
$opSelect->addOption('field_view', _XADDRESSES_AM_PERM_VIEWFIELD);
$opSelect->addOption('field_edit', _XADDRESSES_AM_PERM_EDITFIELD);
$opSelect->addOption('field_search', _XADDRESSES_AM_PERM_SEARCHFIELD);
$opForm->addElement($opSelect);
$opForm->display();

switch($op) {
case 'field_view': // view field permission
    $formTitle = _XADDRESSES_AM_PERM_VIEWFIELD;
    $permissionName = 'field_view';
    $permissionDescription = _XADDRESSES_AM_PERM_VIEWFIELD_DSC;
    $restriction = 'field_edit';
    $anonymous = TRUE;
    break;
case 'field_search': // search field permission
    $formTitle = _XADDRESSES_AM_PERM_SEARCHFIELD;
    $permissionName = 'field_search';
    $permissionDescription = _XADDRESSES_AM_PERM_SEARCHFIELD_DSC;
    $restriction = '';
    $anonymous = TRUE;
    break;
case 'field_edit': // edit field permission
    $formTitle = _XADDRESSES_AM_PERM_EDITFIELD;
    $permissionName = 'field_edit';
    $permissionDescription = _XADDRESSES_AM_PERM_EDITFIELD_DSC;
    $restriction = '';
    $anonymous = TRUE;
    break;
}



$module_id = $GLOBALS['xoopsModule']->getVar('mid');
include_once $GLOBALS['xoops']->path( '/class/xoopsform/grouppermform.php' );
$form = new XoopsGroupPermForm($formTitle, $module_id, $permissionName, $permissionDescription, "admin/" . $currentFile . "?op=" . $op , $anonymous);



switch($op) {
case 'field_view': // view field
case 'field_edit': // edit field
    $fields = $locationHandler->loadFields();
    foreach (array_keys($fields) as $i ) {
        if ($restriction == '' || $fields[$i]->getVar($restriction)) {
            $form->addItem($fields[$i]->getVar('field_id'), xoops_substr($fields[$i]->getVar('field_title'), 0, 25));
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
            $form->addItem($fields[$i]->getVar('field_id'), xoops_substr($fields[$i]->getVar('field_title'), 0, 25));
        }
    }
    break;
}



$form->display();
xoops_cp_footer();
?>