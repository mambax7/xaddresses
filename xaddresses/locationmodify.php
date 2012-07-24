<?php
$currentFile = basename(__FILE__);

// include module header
include_once 'header.php';

// Check permissions
if ($permSuggestModify == false) {
    redirect_header('index.php', 2, _NOPERM);
    exit();
}

// load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory', 'xaddresses');
$fieldHandler =& xoops_getModuleHandler('field', 'xaddresses');
$modifyHandler =& xoops_getModuleHandler('modify', 'xaddresses');

$xoopsOption['template_main'] = 'xaddresses_locationmodify.html';
include_once XOOPS_ROOT_PATH . '/header.php';



$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'new_modify';

$loc_id = (int)($_REQUEST['loc_id']);
// Redirect if id location not exist
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_id', $loc_id));
if ($locationHandler->getCount($criteria) == 0) {
    redirect_header('index.php', 3, _MA_XADDRESSES_SINGLELOC_NONEXISTENT);
    exit();
}



// Get location and category object
$location = $locationHandler->get($loc_id);
$category = $categoryHandler->get($location->getVar('loc_cat_id'));
$viewableCategoriesIds = xaddresses_getMyItemIds('in_category_view');
// IN PROGRESS
// IN PROGRESS
// IN PROGRESS
// Check rights
if(!in_array($location->getVar('loc_cat_id'), $viewableCategoriesIds)) {
    redirect_header('index.php', 2, _NOPERM);
    exit();
}

// Breadcrumb
$breadcrumb = array();
$crumb['title'] = $location->getVar('loc_title');
$crumb['url'] = 'locationview.php?loc_id=' . $location->getVar('loc_id');
$breadcrumb[] = $crumb;
$crumb['title'] = $category->getVar('cat_title');
$crumb['url'] = 'locationcategoryview.php?cat_id=' . $category->getVar('cat_id');
$breadcrumb[] = $crumb;
while ($category->getVar('cat_pid') != 0) {
    $category = $categoryHandler->get($category->getVar('cat_pid'));
    $crumb['title'] = $category->getVar('cat_title');
    $crumb['url'] = 'locationcategoryview.php?cat_id=' . $category->getVar('cat_id');
    $breadcrumb[] = $crumb;
}
if ($xoopsModuleConfig['show_home_in_breadcrumb']) {
    $crumb['title'] = _MA_XADDRESSES_BREADCRUMB_HOME;
    $crumb['url'] = 'index.php';
    $breadcrumb[] = $crumb;
}
// Set breadcrumb array for tamplate
$breadcrumb = array_reverse($breadcrumb);
$xoopsTpl->assign('breadcrumb', $breadcrumb);
unset($breadcrumb, $crumb);



switch ($op) {
default:
case "new_modify":
    // Set title for template    
    $title = _MA_XADDRESSES_LOC_MODIFY_SUGGESTMODIFY . '&nbsp;-&nbsp;';
    $title.= $location->getVar('loc_title') . '&nbsp;-&nbsp;';
    $title.= $category->getVar('cat_title') . '&nbsp;-&nbsp;';
    $title.= $GLOBALS['xoopsModule']->name();
    $xoopsTpl->assign('xoops_pagetitle', $title);
    // Set description for template
    $xoTheme->addMeta( 'meta', 'description', strip_tags(_MA_XADDRESSES_LOC_MODIFY_SUGGESTMODIFY . ' (' . $location->getVar('loc_title') . ')'));

    $newSuggest =& $modifyHandler->create();
    $title = $newSuggest->isNew() ? _AM_XADDRESSES_LOC_MODIFY_NEW : _AM_XADDRESSES_LOC_MODIFY_EDIT;
    $form = new XoopsThemeForm($title, 'modifyform', $currentFile, 'post', true);
    $form = &$newSuggest->getForm($loc_id, $currentFile, $form);
    // Set themeSuggestForm for template
    $form->addElement(new XoopsFormLabel(_MA_XADDRESSES_LOC_MODIFY_CAPTION, _MA_XADDRESSES_LOC_MODIFY_VALUE));
    // Set themeModifyForm for template
    $form = &xaddresses_getModifyForm($location, $currentFile, $form);
    // Captcha
    xoops_load('xoopscaptcha');
    $form->addElement(new XoopsFormCaptcha(), true);
    // Hidden Fields
    $form->addElement(new XoopsFormHidden('loc_id', $loc_id));
    $form->addElement(new XoopsFormHidden('op', 'save_modify'));
    // Submit button		
        $button_tray = new XoopsFormElementTray(_AM_XADDRESSES_ACTION, '' ,'');
        $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $button_tray->addElement(new XoopsFormButton('', 'reset', _RESET, 'reset'));
            $cancel_button = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
            $cancel_button->setExtra("onclick='javascript:history.back();'");
        $button_tray->addElement($cancel_button);
    $form->addElement($button_tray);
    
    $xoopsTpl->assign('themeModifyForm', $form->render());  
    break;



case "list_modify":
    // NOP
    // LIST ALL MODIFY SUGGESTIONS
    break;



case "save_modify":
    $errorFlag = false;
    $errorMessage = '';

    if ( !$GLOBALS['xoopsSecurity']->check()  ) {
        redirect_header('locationview.php?loc_id=' . $loc_id, 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors() ));
        exit;
    }

    // Captcha test
    xoops_load('xoopscaptcha');
    $xoopsCaptcha = XoopsCaptcha::getInstance();
    if ( !$xoopsCaptcha->verify() ) {
        $errorMessage.= $xoopsCaptcha->getMessage() . '<br />';
        $errorFlag = true;
    }

    if(empty($GLOBALS['xoopsUser'])) {
        $suggestingUserId = 0; // Anonymous user
    } else {
        $suggestingUserId = $GLOBALS['xoopsUser']->getVar('uid');
    }

    // Check if user has already suggested for this location
    if($suggestingUserId == 0) {
        // If user is anonymous
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('loc_id', $loc_id));
        $criteria->add(new Criteria('suggest_sender', 0)); // Anonymous user
        $criteria->add(new Criteria('suggest_ip', getenv("REMOTE_ADDR")));
        if ($modifyHandler->getCount($criteria) >= 1) {
            redirect_header('locationview.php?loc_id=' . $loc_id, 2, _MA_XADDRESSES_LOC_MODIFY_ALREADYSUGGESTED);
            exit();
        }
    } else {
        // If user is not anonymous
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('loc_id', $loc_id));
        $modifySuggests = $modifyHandler->getall($criteria);
        foreach ($modifySuggests as $modifySuggest) {
            if ($modifySuggest->getVar('suggest_sender') == $suggestingUserId) {
                redirect_header('locationview.php?loc_id=' . $loc_id, 2, _MA_XADDRESSES_LOC_MODIFY_ALREADYSUGGESTED);
                exit();
            }
        }
    }

    if ($errorFlag == false) {
        // STEP 1: Create suggested location
        // Get fields
        $fields = $fieldHandler->loadFields();

        // Get ids of fields that can be viewed/edited
        //$groupPermHandler =& xoops_gethandler('groupperm');
        //$viewableFields = $groupPermHandler->getItemIds('field_view', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
        //$editableFields = $groupPermHandler->getItemIds('field_edit', $groups, $GLOBALS['xoopsModule']->getVar('mid') );

        $locationFields = $locationHandler->getLocationVars();

        $location = $locationHandler->create();
        if (count($fields) > 0) {
            foreach ($fields as $field) {
                $fieldname = $field->getVar('field_name');
                if (in_array($fieldname, $locationFields)) {
                    $default = $field->getVar('field_default');
                    if ($default === '' || $default === null) continue;
                    $location->setVar($fieldname, $default);
                }
            }
        }

        $location->setVar('loc_suggested', true); // Set suggested flag
        $location->setVar('loc_title', $_POST['loc_title_modify']);
        $location->setVar('loc_cat_id', $_POST['loc_cat_id_modify']);
        $location->setVar('loc_lat', $_POST['loc_googlemap_modify']['lat']);
        $location->setVar('loc_lng', $_POST['loc_googlemap_modify']['lng']);
        $location->setVar('loc_elevation', $_POST['loc_googlemap_modify']['elevation']);
        $location->setVar('loc_zoom', $_POST['loc_googlemap_modify']['zoom']);
        // Set submitter
        $location->setVar('loc_submitter', $suggestingUserId);
        // Set creation date
        $location->setVar('loc_date', time());

        foreach ($fields as $field) {
            $fieldname = $field->getVar('field_name');
            //if ( in_array($field->getVar('field_id'), $editable_fields) && isset($_REQUEST[$fieldname])  ) {
            $value = $field->getValueForSave((isset($_REQUEST[$fieldname . '_modify']) ? $_REQUEST[$fieldname . '_modify'] : ''));
            $location->setVar($fieldname, $value);
           //     }
        }

        if (!$locationHandler->insert($location)) {
            $errorFlag = true;
            $errorMessage.= $location->getHtmlErrors();
        }
    }

    if ($errorFlag == false) {
        $suggest_loc_id = $location->getVar('loc_id');

        // STEP 2: Create suggestion/modify
        $newSuggest =& $modifyHandler->create();
        $newSuggest->setVar('loc_id', $loc_id);
        $newSuggest->setVar('suggest_loc_id', $suggest_loc_id);
        $newSuggest->setVar('suggest_sender', $suggestingUserId);
        $newSuggest->setVar('suggest_ip', getenv("REMOTE_ADDR"));
        $newSuggest->setVar('suggest_date', time()); // creation date
        $newSuggest->setVar('suggest_description', $_POST['suggest_description']);
        if ($errorFlag == true) {
            $xoopsTpl->assign('errorMessage', $errorMessage);
        } else {
            if ($modifyHandler->insert($newSuggest)) {
                $tags = array();
                $tags['MODIFYSUGGESTS_URL'] = XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/admin/modify.php';
                $notification_handler =& xoops_gethandler('notification');
                $notification_handler->triggerEvent('global', 0, 'location_modify', $tags);
                redirect_header('locationview.php?loc_id=' . $loc_id, 2, _MA_XADDRESSES_LOC_MODIFY_THANKSFORINFO);
            } else {
                $errorFlag = true;
                $errorMessage.= $newSuggest->getHtmlErrors();
            }
        }
    }
    
    // Set themeForm for template
    if ($errorFlag == true)
        $xoopsTpl->assign('errorMessage', $errorMessage);
    // TO DO
    // TO DO
    // TO DO
    //Affichage du formulaire de notation des téléchargements
    //$form =& $obj->getForm((int)$_REQUEST['loc_id']);
    //$xoopsTpl->assign('themeForm', $form->render());   
    break;    
}

include XOOPS_ROOT_PATH . '/footer.php';
?>