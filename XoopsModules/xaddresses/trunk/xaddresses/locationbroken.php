<?php
$currentFile = basename(__FILE__);

// include module header
include_once 'header.php';

// Check permissions
if ($permReportBroken == false) {
    redirect_header('index.php', 2, _NOPERM);
    exit();
}

// load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$brokenHandler =& xoops_getModuleHandler('broken', 'xaddresses');

$xoopsOption['template_main'] = 'xaddresses_locationbroken.html';
include_once XOOPS_ROOT_PATH . '/header.php';



$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'new_broken';

$loc_id = (int)($_REQUEST['loc_id']);
// Redirect if id location not exist
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_id', $loc_id));
$criteria->add(new Criteria('loc_suggested', false));
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
case "new_broken":
    // Set title for template    
    $title = _MA_XADDRESSES_LOC_BROKEN_REPORTBROKEN . '&nbsp;-&nbsp;';
    $title.= $location->getVar('loc_title') . '&nbsp;-&nbsp;';
    $title.= $category->getVar('cat_title') . '&nbsp;-&nbsp;';
    $title.= $GLOBALS['xoopsModule']->name();
    $xoopsTpl->assign('xoops_pagetitle', $title);
    // Set description for template
    $xoTheme->addMeta( 'meta', 'description', strip_tags(_MA_XADDRESSES_LOC_BROKEN_REPORTBROKEN . ' (' . $location->getVar('loc_title') . ')'));
// IN PROGRESS
    // Set themeForm for template
    $newReport =& $brokenHandler->create();
    $form = $newReport->getForm($loc_id);
    $xoopsTpl->assign('themeForm', $form->render());  
    break;



case "list_broken":
    // NOP
    break;



case "save_broken":
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
        $reportingUserId = 0; // Anonymous user
    } else {
        $reportingUserId = $GLOBALS['xoopsUser']->getVar('uid');
    }

    // Check if user has already suggested for this location
    if($reportingUserId == 0) {
        // If user is anonymous
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('loc_id', $loc_id));
        $criteria->add(new Criteria('report_sender', 0)); // Anonymous user
        $criteria->add(new Criteria('report_ip', getenv("REMOTE_ADDR")));
        if ($brokenHandler->getCount($criteria) >= 1) {
            redirect_header('locationview.php?loc_id=' . $loc_id, 2, _MA_XADDRESSES_LOC_BROKEN_ALREADYREPORTED);
            exit();
        }
    } else {
        // If user is not anonymous
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('loc_id', $loc_id));
        $brokenReports = $brokenHandler->getall($criteria);
        foreach ($brokenReports as $brokenReport) {
            if ($brokenReport->getVar('report_sender') == $reportingUserId) {
                redirect_header('locationview.php?loc_id=' . $loc_id, 2, _MA_XADDRESSES_LOC_BROKEN_ALREADYREPORTED);
                exit();
            }
        }
    }


    if ($errorFlag == false) {
        $newReport =& $brokenHandler->create();
        $newReport->setVar('loc_id', $loc_id);
        $newReport->setVar('report_sender', $reportingUserId);
        $newReport->setVar('report_ip', getenv("REMOTE_ADDR"));
        $newReport->setVar('report_date', time()); // creation date
        $newReport->setVar('report_description', $_POST['report_description']);
        if ($brokenHandler->insert($newReport)) {
            $tags = array();
            $tags['BROKENREPORTS_URL'] = XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/admin/broken.php';
            $notification_handler =& xoops_gethandler('notification');
            $notification_handler->triggerEvent('global', 0, 'location_broken', $tags);
            redirect_header('locationview.php?loc_id=' . $loc_id, 2, _MA_XADDRESSES_LOC_BROKEN_THANKSFORINFO);
        } else {
            $errorFlag = true;
            $errorMessage.= $brokenHandler->getHtmlErrors();
        }
    }

    // Set themeForm for template
    if ($errorFlag == true)
        $xoopsTpl->assign('errorMessage', $errorMessage);
    $form =& $brokenHandler->getForm($loc_id);
    $xoopsTpl->assign('themeForm', $form->render());   
    break;    
}

include XOOPS_ROOT_PATH.'/footer.php';
?>