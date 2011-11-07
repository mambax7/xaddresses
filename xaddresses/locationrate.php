<?php
$currentFile = basename(__FILE__);

// include module header
include_once 'header.php';

// Check permissions
if ($permRate == false) {
    redirect_header('index.php', 2, _NOPERM);
    exit();
}

// load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$votedataHandler =& xoops_getModuleHandler('votedata', 'xaddresses');

$xoopsOption['template_main'] = 'xaddresses_locationrate.html';
include_once XOOPS_ROOT_PATH . '/header.php';



$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'new_vote';

$loc_id = (int)($_REQUEST['loc_id']);
// Redirect if id location not exist
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_id', $loc_id));
$criteria->add(new Criteria('loc_suggested', false));
if ($locationHandler->getCount($criteria) == 0) {
    redirect_header('index.php', 3, _XADDRESSES_MD_SINGLELOC_NONEXISTENT);
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
    $crumb['title'] = _XADDRESSES_MD_BREADCRUMB_HOME;
    $crumb['url'] = 'index.php';
    $breadcrumb[] = $crumb;
}
// Set breadcrumb array for tamplate
$breadcrumb = array_reverse($breadcrumb);
$xoopsTpl->assign('breadcrumb', $breadcrumb);
unset($breadcrumb, $crumb);



switch ($op) {
default:
case "new_vote":
    // Set title for template    
    $title = _XADDRESSES_MD_LOC_RATELOCATION . '&nbsp;-&nbsp;';
    $title.= $location->getVar('loc_title') . '&nbsp;-&nbsp;';
    $title.= $category->getVar('cat_title') . '&nbsp;-&nbsp;';
    $title.= $GLOBALS['xoopsModule']->name();
    $xoopsTpl->assign('xoops_pagetitle', $title);
    // Set description for template
    $xoTheme->addMeta( 'meta', 'description', strip_tags(_XADDRESSES_MD_LOC_RATELOCATION . ' (' . $location->getVar('loc_title') . ')'));
// IN PROGRESS
    // Set themeForm for template
    $vote =& $votedataHandler->create();
    $form = $vote->getForm($loc_id, $currentFile);
    $xoopsTpl->assign('themeForm', $form->render());    
    break;



case "list_vote":
    // NOP
    break;



case "save_vote":
    if ( !$GLOBALS['xoopsSecurity']->check()  ) {
        redirect_header('locationview.php?loc_id=' . $loc_id, 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors() ));
        exit;
    }

    $errorFlag = false;
    $errorMessage = '';

    // Captcha test
    xoops_load('xoopscaptcha');
    $xoopsCaptcha = XoopsCaptcha::getInstance();
    if ( !$xoopsCaptcha->verify() ) {
        $errorFlag = true;
        $errorMessage.= $xoopsCaptcha->getMessage() . '<br />';
    }
    
    if(empty($GLOBALS['xoopsUser'])) {
        $ratingUserId = 0; // Anonymous user
    } else {
        $ratingUserId = $GLOBALS['xoopsUser']->getVar('uid');
    }

    // A user cannot rate its locations
    if ($ratingUserId != 0) {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('loc_id', $loc_id));
        $locations = $locationHandler->getall($criteria);
        foreach (array_keys($locations) as $i) {
            if ($locations[$i]->getVar('loc_submitter') == $ratingUserId) {
                redirect_header('locationview.php?loc_id=' . $loc_id, 2, _XADDRESSES_MD_LOC_RATE_DONOTVOTE);
                exit();
            }
        }
        // A user cannot rate a location two times 
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('loc_id', $loc_id));
        $votes = $votedataHandler->getall($criteria);
        foreach (array_keys($votes) as $i) {
            if ($votes[$i]->getVar('rating_user') == $ratingUserId) {
                redirect_header('locationview.php?loc_id=' . $loc_id, 2, _XADDRESSES_MD_LOC_RATE_VOTEONCE);
                exit();
            }
        }
    } else {
        // Anonymous user cannot rate a location two times in a day
        $yesterday = (time()-86400);
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('loc_id', $loc_id));
        $criteria->add(new Criteria('rating_user', 0)); // Anonymous user
        $criteria->add(new Criteria('rating_hostname', getenv("REMOTE_ADDR")));
        $criteria->add(new Criteria('rating_timestamp', $yesterday, '>'));
        if ($votedataHandler->getCount($criteria) >= 1) {
            redirect_header('locationview.php?loc_id=' . $loc_id, 2, _XADDRESSES_MD_LOC_RATE_VOTEONCE);
            exit();
        }
    }

    // Check rating value
    $rating = (int)$_POST['rating'];
    if ($rating < 0 || $rating > 10) {
        $errorFlag = true;
        $errorMessage.= _MD_NORATING . '<br />';
    }



    $vote =& $votedataHandler->create();
    $vote->setVar('loc_id', $loc_id);
    $vote->setVar('rating_user', $ratingUserId);
    $vote->setVar('rating', $rating);
    $vote->setVar('rating_hostname', getenv("REMOTE_ADDR"));
    $vote->setVar('rating_timestamp', time());
    if ($errorFlag == true) {
        $xoopsTpl->assign('errorMessage', $errorMessage);
    } else {
        if ($votedataHandler->insert($vote)) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', $loc_id));
            $votes = $votedataHandler->getall($criteria);
            $countVotes = $votedataHandler->getCount($criteria);
            $totalRating = 0;
            foreach (array_keys($votes) as $i) {
                $totalRating += $votes[$i]->getVar('rating');
            }
            $rating = $totalRating / $countVotes;
            $location =& $locationHandler->get($loc_id);
            $location->setVar('loc_rating', number_format($rating, 1));
            $location->setVar('loc_votes', $countVotes);
            if ($locationHandler->insert($location)) {
                redirect_header('locationview.php?loc_id=' . $loc_id, 2, _XADDRESSES_MD_LOC_RATE_VOTEOK);
            }
            echo $location->getHtmlErrors();
        }
        echo $vote->getHtmlErrors();
    }
    // Set themeForm for template
    $form =& $vote->getForm($loc_id, $currentFile);
    $xoopsTpl->assign('themeForm', $form->render());   
    break;    
}

include XOOPS_ROOT_PATH . '/footer.php';
?>