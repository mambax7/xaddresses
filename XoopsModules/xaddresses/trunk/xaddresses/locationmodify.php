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
$modifyHandler =& xoops_getModuleHandler('modify', 'xaddresses');

$xoopsOption['template_main'] = 'xaddresses_locationmodify.html';
include_once XOOPS_ROOT_PATH . '/header.php';



$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'new_modify';

$loc_id = (int)($_REQUEST['loc_id']);
// Redirect if id location not exist
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_id', $loc_id));
if ($locationHandler->getCount($criteria) == 0) {
    redirect_header('index.php', 3, _XADDRESSES_MD_SINGLELOC_NONEXISTENT);
    exit();
}



// Get location and category object
$location = $locationHandler->get($loc_id);
$category = $categoryHandler->get($location->getVar('loc_cat_id'));
$categories = xaddresses_MygetItemIds();
// IN PROGRESS
// IN PROGRESS
// IN PROGRESS
// Check rights
if(!in_array($location->getVar('loc_cat_id'), $categories)) {
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
// Set breadcrumb array for tamplate
$breadcrumb = array_reverse($breadcrumb);
$xoopsTpl->assign('breadcrumb', $breadcrumb);
unset($breadcrumb, $crumb);



switch ($op) {
default:
case "new_modify":
    // Set title for template    
    $title = _XADDRESSES_MD_LOC_MODIFY_SUGGESTMODIFY . '&nbsp;-&nbsp;';
    $title.= $location->getVar('loc_title') . '&nbsp;-&nbsp;';
    $title.= $category->getVar('cat_title') . '&nbsp;-&nbsp;';
    $title.= $xoopsModule->name();
    $xoopsTpl->assign('xoops_pagetitle', $title);
    // Set description for template
    $xoTheme->addMeta( 'meta', 'description', strip_tags(_XADDRESSES_MD_LOC_MODIFY_SUGGESTMODIFY . ' (' . $location->getVar('loc_title') . ')'));
// IN PROGRESS
    // Set themeSuggestForm for template
    $newSuggest =& $modifyHandler->create();
    $form = $newSuggest->getForm($loc_id);
    $xoopsTpl->assign('themeSuggestForm', $form->render());
    // Set themeModifyForm for template
    $form = xaddresses_getModifyForm($location, $currentFile);
    $xoopsTpl->assign('themeModifyForm', $form->render());  
    break;



case "list_modify":
    // NOP
    break;



case "save_modify":
    $newSuggest =& $modifyHandler->create();
    if(empty($xoopsUser)){
        $suggestingUserId = 0;
        // si c'est un utilisateur anonyme on vérifie qu'il n'envoie pas 2 fois un rapport
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('loc_id', $loc_id));
        $criteria->add(new Criteria('suggest_sender', 0));
        $criteria->add(new Criteria('suggest_ip', getenv("REMOTE_ADDR")));
        if ($modifyHandler->getCount($criteria) >= 1) {
            redirect_header('locationview.php?loc_id=' . $loc_id, 2, _MD_XADDRESSES_LOC_BROKEN_ALREADYREPORTED);
            exit();
        }
    } else {
        $suggestingUserId = $xoopsUser->getVar('uid');
        // si c'est un membre on vérifie qu'il n'envoie pas 2 fois un rapport
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('loc_id', $loc_id));
        $modifySuggests = $modifyHandler->getall($criteria);
        foreach ($modifySuggests as $modifySuggest) {
            if ($modifySuggest->getVar('suggest_sender') == $suggestingUserId) {
                redirect_header('locationview.php?loc_id=' . $loc_id, 2, _MD_XADDRESSES_LOC_BROKEN_ALREADYREPORTED);
                exit();
            }
        }
    }

    $error = false;
    $errorMessage = '';
    // Test avant la validation
    xoops_load("captcha");
    $xoopsCaptcha = XoopsCaptcha::getInstance();
    if ( !$xoopsCaptcha->verify() ) {
        $errorMessage.= $xoopsCaptcha->getMessage() . '<br />';
        $error = true;
    }
    $newSuggest->setVar('loc_id', $loc_id);
    $newSuggest->setVar('suggest_loc_id', $loc_id);
    $newSuggest->setVar('suggest_sender', $suggestingUserId);
    $newSuggest->setVar('suggest_ip', getenv("REMOTE_ADDR"));
    $newSuggest->setVar('suggest_date', time()); // creation date
    $newSuggest->setVar('suggest_description', $_POST['suggest_description']);
    if ($error == true) {
        $xoopsTpl->assign('errorMessage', $errorMessage);
    } else {
        if ($modifyHandler->insert($newSuggest)) {
            $tags = array();
            $tags['MODIFYSUGGESTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/modify.php';
            $notification_handler =& xoops_gethandler('notification');
            $notification_handler->triggerEvent('global', 0, 'location_modify', $tags);
            redirect_header('locationview.php?loc_id=' . $loc_id, 2, _MD_XADDRESSES_LOC_MODIFY_THANKSFORINFO);
        }
        echo $obj->getHtmlErrors();
    }
    //Affichage du formulaire de notation des téléchargements
    $form =& $obj->getForm((int)$_REQUEST['loc_id']);
    $xoopsTpl->assign('themeForm', $form->render());   
    break;    
    
    
/*
    xoops_loadLanguage("main", $GLOBALS['xoopsModule']->getVar('dirname', 'n') );
//        if ( !$GLOBALS['xoopsSecurity']->check()  ) {
//            redirect_header('address.php', 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors() ));
//            exit;
//        }

    // Get fields
    $fields = $fieldHandler->loadFields();

    // Get ids of fields that can be viewed/edited
    $groupPermHandler =& xoops_gethandler('groupperm');
    $viewableFields = $groupPermHandler->getItemIds('field_view', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
    $editableFields = $groupPermHandler->getItemIds('field_edit', $groups, $GLOBALS['xoopsModule']->getVar('mid') );

    $locationFields = $locationHandler->getLocationVars();

    if (!empty($_POST['loc_id'])) {
        $loc_id = (int)$_POST['loc_id'];
        $location = $locationHandler->get($loc_id);
        if (!is_object($location)) {
            $location = $locationHandler->create();
            $location->setVar('loc_id', $loc_id);
        }
    } else {
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
    }

    $myts =& MyTextSanitizer::getInstance();
    $location->setVar('loc_title', $_POST['loc_title']);
    $location->setVar('loc_cat_id', $_POST['loc_cat_id']);
    $location->setVar('loc_lat', $_POST['loc_googlemap']['lat']);
    $location->setVar('loc_lng', $_POST['loc_googlemap']['lng']);
    $location->setVar('loc_zoom', $_POST['loc_googlemap']['zoom']);
    // Set submitter and time
    if (isset($_POST['loc_submitter'])) {
        $location->setVar('loc_submitter', $_POST['loc_submitter']);
    } else {
        $location->setVar('loc_submitter', $xoopsUser->uid());
    }
    if (isset($_POST['loc_date'])) {
        $location->setVar('loc_date', strtotime($_POST['loc_date']['date']) + $_POST['loc_date']['time']); // creation date
    } else {
        $location->setVar('loc_date', time()); // creation date
    }

    $errors = array();
    if ($stop != "") {
        $errors[] = $stop;
    }

    foreach ($fields as $field) {
        $fieldname = $field->getVar('field_name');
        //if ( in_array($field->getVar('field_id'), $editable_fields) && isset($_REQUEST[$fieldname])  ) {
        $value = $field->getValueForSave((isset($_REQUEST[$fieldname]) ? $_REQUEST[$fieldname] : ''));
        $location->setVar($fieldname, $value);
       //     }
    }

    $new_groups = isset($_POST['groups']) ? $_POST['groups'] : array();

    if (count($errors) == 0) {
        if ($locationHandler->insert($location)) {
            if ($location->isNew()) {
                redirect_header($currentFile, 2, _XADDRESSES_AM_ADDRESSCREATED, false);
            } else {
                redirect_header('locationview.php?loc_id=' . $location->getVar('loc_id'), 2, _US_PROFUPDATED, false);
            }
        }
    } else {
        foreach ($errors as $err) {
            $user->setErrors($err);
        }
    }
    //$location->setGroups($new_groups);
    echo $location->getHtmlErrors();

    $form = xaddresses_getModifyForm($location, $currentFile);
    $form->display();
    break;
*/
    
    
    
    }

include XOOPS_ROOT_PATH.'/footer.php';
?>