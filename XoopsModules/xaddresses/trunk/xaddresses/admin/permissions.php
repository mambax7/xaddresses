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

// count location categories
$countCategories = $categoryHandler->getCount();
// redirection if no categories
if ($categoryHandler->getCount() == 0) {
    redirect_header('index.php', 2, _XADDRESSES_AM_PERM_NOCAT);
}

// get/check parameters/post
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'in_category_view';

// render start here
xoops_cp_header();

// render main admin menu
include (XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/admin/menu.php');
echo moduleAdminTabMenu($adminmenu, $currentFile);

// render permissions selector
include_once $GLOBALS['xoops']->path( '/class/xoopsformloader.php' );
$opForm = new XoopsSimpleForm('', 'opform', $currentFile, 'get');
$opSelect = new XoopsFormSelect("", 'op', $op);
$opSelect->setExtra('onchange="document.forms.opform.submit()"');
$opSelect->addOption('in_category_view', _XADDRESSES_AM_PERM_VIEW);
$opSelect->addOption('in_category_submit', _XADDRESSES_AM_PERM_SUBMIT);
$opSelect->addOption('in_category_edit', _XADDRESSES_AM_PERM_EDIT);
$opSelect->addOption('in_category_delete', _XADDRESSES_AM_PERM_DELETE); // IN PROGRESS ha senso questo permesso?
$opSelect->addOption('field_view', _XADDRESSES_AM_PERM_VIEWFIELD);
$opSelect->addOption('field_edit', _XADDRESSES_AM_PERM_EDITFIELD);
$opSelect->addOption('field_search', _XADDRESSES_AM_PERM_SEARCHFIELD);
$opSelect->addOption('extra', _XADDRESSES_AM_PERM_OTHERS); // IN_PROGRESS
$opForm->addElement($opSelect);
$opForm->display();

switch($op) {
default:
case 'in_category_view': // view permission
    $titleOfForm = _XADDRESSES_AM_PERM_VIEW;
    $permName = 'in_category_view';
    $permDesc = _XADDRESSES_AM_PERM_VIEW_DSC;
    $anonymous = true;
    break;
case 'in_category_submit': // submit permission
    $titleOfForm = _XADDRESSES_AM_PERM_SUBMIT;
    $permName = 'in_category_submit';
    $permDesc = _XADDRESSES_AM_PERM_SUBMIT_DSC;
    $anonymous = true;
    break;
case 'in_category_edit': // edit permission
    $titleOfForm = _XADDRESSES_AM_PERM_EDIT;
    $permName = 'in_category_edit';
    $permDesc = _XADDRESSES_AM_PERM_EDIT_DSC;
    $anonymous = true;
    break;
case 'in_category_delete': // edit permission
    $titleOfForm = _XADDRESSES_AM_PERM_DELETE;
    $permName = 'in_category_delete';
    $permDesc = _XADDRESSES_AM_PERM_DELETE_DSC;
    $anonymous = true;
    break;

case 'field_view': // view field permission
    $titleOfForm = _XADDRESSES_AM_PERM_VIEWFIELD;
    $permName = 'field_view';
    $permDesc = _XADDRESSES_AM_PERM_VIEWFIELD_DSC;
    $restriction = 'field_edit';
    $anonymous = true;
    break;
case 'field_search': // search field permission
    $titleOfForm = _XADDRESSES_AM_PERM_SEARCHFIELD;
    $permName = 'field_search';
    $permDesc = _XADDRESSES_AM_PERM_SEARCHFIELD_DSC;
    $restriction = '';
    $anonymous = true;
    break;
case 'field_edit': // edit field permission
    $titleOfForm = _XADDRESSES_AM_PERM_EDITFIELD;
    $permName = 'field_edit';
    $permDesc = _XADDRESSES_AM_PERM_EDITFIELD_DSC;
    $restriction = '';
    $anonymous = true;
    break;
case 'extra': // IN_PROGRESS
    $titleOfForm = _XADDRESSES_AM_PERM_OTHERS;
    $permName = "others";
    $permDesc = _XADDRESSES_AM_PERM_OTHERS_DSC;
    $anonymous = true;
    $globalPermsArray = array(
        //'1'     => _XADDRESSES_AM_PERMISSIONS_1 ,
        //'2'     => _XADDRESSES_AM_PERMISSIONS_2 ,
        '4'     => _XADDRESSES_AM_PERMISSIONS_4 ,   // Submit a location
        '8'     => _XADDRESSES_AM_PERMISSIONS_8 ,   // Submit a modification
        '16'    => _XADDRESSES_AM_PERMISSIONS_16 ,  // Note a location
        '32'    => _XADDRESSES_AM_PERMISSIONS_32,   // IN_PROGRESS
        //'64'    => _XADDRESSES_AM_PERMISSIONS_64,
        //'128'   => _XADDRESSES_AM_PERMISSIONS_128,
        //'256'   => _XADDRESSES_AM_PERMISSIONS_256,
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

xoops_cp_footer();
?>