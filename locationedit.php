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

// load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory', 'xaddresses');
$fieldHandler =& xoops_getModuleHandler('field', 'xaddresses');

$xoopsOption['template_main'] = 'xaddresses_locationedit.html';
include_once XOOPS_ROOT_PATH . '/header.php';



if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
} elseif (isset($_REQUEST['loc_id'])) {
    $op = 'edit_location';
} else {
    $op = 'new_location';
}


/*
// Check permissions
if ($permVote == false) {
    redirect_header('index.php', 2, _NOPERM);
    exit();
}
*/



switch ($op) {
default:
case 'edit_location':
    if (!isset($_REQUEST['loc_id'])) {
            redirect_header('index.php', 3, _MA_XADDRESSES_SINGLELOC_NONEXISTENT);
            exit();
    }
    // redirect if id location not exist
    $loc_id = (int)($_REQUEST['loc_id']);
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('loc_id', $loc_id));
    $criteria->add(new Criteria('loc_suggested', false));
    if ($locationHandler->getCount($criteria) == 0) {
        redirect_header('index.php', 3, _MA_XADDRESSES_SINGLELOC_NONEXISTENT);
        exit();
    }
    // get location
    $location = $locationHandler->get($loc_id);
    // redirect if not right edit permissions
    if (in_array($location->getVar('loc_cat_id'), $editableCategoriesIds)) {
        redirect_header('index.php', 3, _AM_XADDRESSES_EDIT_NOT_ALLOWED);
        exit();
    }
    // get location's category
    $category = $categoryHandler->get($location->getVar('loc_cat_id'));

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

    // Set title for template    
    $title = _MA_XADDRESSES_LOC_RATELOCATION . '&nbsp;-&nbsp;';
    $title.= $location->getVar('loc_title') . '&nbsp;-&nbsp;';
    $title.= $category->getVar('cat_title') . '&nbsp;-&nbsp;';
    $title.= $GLOBALS['xoopsModule']->name();
    $xoopsTpl->assign('xoops_pagetitle', $title);
    // Set description for template
    $xoTheme->addMeta( 'meta', 'description', strip_tags(_MA_XADDRESSES_LOC_RATELOCATION . ' (' . $location->getVar('loc_title') . ')'));

    // Set themeForm for template
    $form = &xaddresses_getLocationForm($location, $currentFile);
    $xoopsTpl->assign('themeForm', $form->render());  
    break;



case 'new_location':
    // redirect if not right submit permissions
    if (count($submitableCategoriesIds) == 0) {
        redirect_header('index.php', 3, _AM_XADDRESSES_SUBMIT_NOT_ALLOWED);
        exit();
    }
    $location =& $locationHandler->create();
    // Breadcrumb
    // NOP
    // Set breadcrumb array for tamplate
    $breadcrumb = array();
    if ($xoopsModuleConfig['show_home_in_breadcrumb']) {
        $crumb['title'] = _MA_XADDRESSES_BREADCRUMB_HOME;
        $crumb['url'] = 'index.php';
        $breadcrumb[] = $crumb;
    }
    // Set breadcrumb array for template
    $breadcrumb = array_reverse($breadcrumb);
    $xoopsTpl->assign('breadcrumb', $breadcrumb);
    unset($breadcrumb, $crumb);

    // Set themeForm for template
    $form = &xaddresses_getLocationForm($location);
    $xoopsTpl->assign('themeForm', $form->render());
    break;



case 'save_location':
    if ( !$GLOBALS['xoopsSecurity']->check()  ) {
        redirect_header($currentFile, 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors() ));
        exit;
    }

    $errorFlag = false;
    $errorMessage = '';

    // Captcha test
    xoops_load('xoopscaptcha');
    $xoopsCaptcha = XoopsCaptcha::getInstance();
    if ( !$xoopsCaptcha->verify() ) {
        $errorMessage.= $xoopsCaptcha->getMessage() . '<br />';
        $errorFlag = true;
    }

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
    $location->setVar('loc_title', $_POST['loc_title']);
    $location->setVar('loc_cat_id', $_POST['loc_cat_id']);
    $location->setVar('loc_lat', $_POST['loc_googlemap']['lat']);
    $location->setVar('loc_lng', $_POST['loc_googlemap']['lng']);
    $location->setVar('loc_elevation', $_POST['loc_googlemap']['elevation']);
    $location->setVar('loc_zoom', $_POST['loc_googlemap']['zoom']);
    // Set submitter
    if(empty($GLOBALS['xoopsUser'])) {
        $editUserId = 0; // Anonymous user
    } else {
        $editUserId = $GLOBALS['xoopsUser']->getVar('uid');
    }
    if (isset($_POST['loc_submitter'])) {
        $location->setVar('loc_submitter', $_POST['loc_submitter']);
    } else {
        $location->setVar('loc_submitter', $editUserId);
    }
    // Set creation date
    if (isset($_POST['loc_date'])) {
        $location->setVar('loc_date', strtotime($_POST['loc_date']['date']) + $_POST['loc_date']['time']); // creation date
    } else {
        $location->setVar('loc_date', time());
    }

    foreach ($fields as $field) {
        $fieldname = $field->getVar('field_name');
        //if ( in_array($field->getVar('field_id'), $editable_fields) && isset($_REQUEST[$fieldname])  ) {
        $value = $field->getValueForSave((isset($_REQUEST[$fieldname]) ? $_REQUEST[$fieldname] : ''));
        $location->setVar($fieldname, $value);
       //     }
    }

    if ($errorFlag == false) {
        if ($locationHandler->insert($location)) {
            if ($location->isNew()) {
                redirect_header($currentFile, 2, _AM_XADDRESSES_ADDRESSCREATED, false);
            } else {
                redirect_header('locationview.php?loc_id=' . $location->getVar('loc_id'), 2, _US_PROFUPDATED, false);
            }
        } else {
            $errorFlag = true;
            $errorMessage.= $location->getHtmlErrors();
        }
    }

    // Set themeForm for template
    if ($errorFlag == true)
        $xoopsTpl->assign('errorMessage', $errorMessage);
    $form = &xaddresses_getLocationForm($location);
    $xoopsTpl->assign('themeForm', $form->render());
    break;
}

include XOOPS_ROOT_PATH . '/footer.php';
