<?php
$currentFile = basename(__FILE__);

include_once 'header.php';
include_once XOOPS_ROOT_PATH . '/header.php';

//load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory', 'xaddresses');
$fieldHandler =& xoops_getModuleHandler('field', 'xaddresses');
$memberHandler =& xoops_gethandler('member');

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'edit_location';
$loc_id = (int)($_REQUEST['loc_id']);

$xoopsOption['template_main'] = 'xaddresses_locationview.html';


// redirect if id location not exist
if ($op == 'edit_location' && isset($loc_id)) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('loc_id', $loc_id));
    if ($locationHandler->getCount($criteria) == 0) {
        redirect_header('index.php', 3, _XADDRESSES_MD_SINGLELOC_NONEXISTENT);
        exit();
    }
}



switch ($op) {
default:
    case 'edit_location':
        $location =& $locationHandler->get($_REQUEST['loc_id']);
        $form = xaddresses_getLocationForm($location, $currentFile);
        $form->display();
        break;


    case 'new_location':
        $location =& $locationHandler->create();
        $form = xaddresses_getLocationForm($location);
        $form->display();
        break;


    case 'save_location':
        xoops_loadLanguage("main", $GLOBALS['xoopsModule']->getVar('dirname', 'n') );
    //        if ( !$GLOBALS['xoopsSecurity']->check()  ) {
    //            redirect_header('address.php', 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors() ));
    //            exit;
    //        }

        // Get fields
        $fields = $fieldHandler->loadFields();

        // Get ids of fields that can be edited
        $gperm_handler =& xoops_gethandler('groupperm');
        //$editable_fields = $gperm_handler->getItemIds('profile_edit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
        
        $locationfields = $locationHandler->getLocationVars();

        $loc_id = empty($_POST['loc_id']) ? 0 : intval($_POST['loc_id']);
        if (!empty($loc_id)) {
            $location = $locationHandler->get($loc_id);
            if (!is_object($location)) {
                $location = $locationHandler->create();
                $location->setVar('loc_id', $loc_id);
            }
        } else {
            $location = $locationHandler->create();
            
            $location->setVar('loc_submitter', $xoopsUser->uid());
            $location->setVar('loc_date', time()); // creation date
            if (count($fields) > 0) {
                foreach ($fields as $field) {
                    $fieldname = $field->getVar('field_name');
                    if (in_array($fieldname, $locationfields)) {
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
        $location->setVar('loc_date', time()); // creation, last modification date

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

        $form = xaddresses_getAddressForm($location);
        $form->display();
        break;
}

include XOOPS_ROOT_PATH.'/footer.php';
?>