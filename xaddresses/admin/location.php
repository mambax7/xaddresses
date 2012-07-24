<?php
$currentFile = basename(__FILE__);

// include module admin header
include_once 'admin_header.php';

// load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$fieldHandler =& xoops_getModuleHandler('field', 'xaddresses');
$brokenHandler =& xoops_getModuleHandler('broken', 'xaddresses');
$modifyHandler =& xoops_getModuleHandler('modify', 'xaddresses');
$votedataHandler =& xoops_getModuleHandler('votedata', 'xaddresses');

$memberHandler =& xoops_gethandler('member');



// redirection if no categories
if ($categoryHandler->getCount() == 0) {
    redirect_header('locationcategory.php?op=new_category', 2, _AM_XADDRESSES_LOC_NOCAT);
}

// count valid locations
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_suggested', false));
$criteria->add(new Criteria('loc_status', 0, '!='));
$countLocations = $locationHandler->getCount($criteria);

// count waiting/not valid locations
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_suggested', false));
$criteria->add(new Criteria('loc_status', 0));
$countWaitingLocations = $locationHandler->getCount($criteria);

// count wrong locations reports
$brokenReports = $brokenHandler->getall();
$countBrokenReports = $brokenHandler->getCount();
$countBrokenReportsByLoc = array();
foreach($brokenReports as $brokenReport) {
    $broken_loc_id = $brokenReport->getVar('loc_id');
    if (isset($countBrokenReportsByLoc[$broken_loc_id]))
        $countBrokenReportsByLoc[$broken_loc_id] = $countBrokenReportsByLoc[$broken_loc_id] + 1;
    else
        $countBrokenReportsByLoc[$broken_loc_id] = 1;
}
$countBrokenLocations = count($countBrokenReportsByLoc);

// count modified locations
$modifySuggests = $modifyHandler->getall();
$countModifySuggests = $modifyHandler->getCount();
$countModifySuggestsByLoc = array();
foreach($modifySuggests as $modifySuggest) {
    $modify_loc_id = $modifySuggest->getVar('loc_id');
    if (isset($countModifySuggestsByLoc[$modify_loc_id]))
        $countModifySuggestsByLoc[$modify_loc_id] = $countBrokenReportsByLoc[$broken_loc_id] + 1;
    else
        $countModifySuggestsByLoc[$modify_loc_id] = 1;
}
$countModifyLocations = count($countModifySuggestsByLoc);



$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list_locations';
// IN PROGRESS
// IN PROGRESS
// IN PROGRESS
$next_op = isset($_REQUEST['next_op']) ? $_REQUEST['next_op'] : '';
// IN PROGRESS
// IN PROGRESS
// IN PROGRESS

// fill submenu
$submenuItem[] = ($op == 'new_location' ? _AM_XADDRESSES_LOC_NEW : '<a href="' . $currentFile . '?op=new_location">' . _AM_XADDRESSES_LOC_NEW . '</a>');
$submenuItem[] = ($op == 'list_locations' ? _AM_XADDRESSES_LOC_LIST . ' (' . $countLocations . ')' : '<a href="' . $currentFile . '?op=list_locations">' . _AM_XADDRESSES_LOC_LIST . ' (' . $countLocations . ')' . '</a>');
$submenuItem[] = ($op == 'list_locations_broken' ? _AM_XADDRESSES_LOC_BROKEN . ($countBrokenLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $countBrokenLocations . '</span>)') : '<a href="' . $currentFile . '?op=list_locations_broken">' . _AM_XADDRESSES_LOC_BROKEN . ($countBrokenLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $countBrokenLocations . '</span>)').'</a>');
$submenuItem[] = ($op == 'list_locations_modify' ? _AM_XADDRESSES_LOC_MODIFY . ($countModifyLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $countModifyLocations . '</span>)') : '<a href="' . $currentFile . '?op=list_locations_modify">' . _AM_XADDRESSES_LOC_MODIFY . ($countModifyLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $countModifyLocations . '</span>)').'</a>');



switch ($op) {
default:
case 'list_locations':
    // render start here
    xoops_cp_header();
    // render submenu
    xaddressesAdminSubmenu ($submenuItem);

    // get fields categories
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight');
    $cats = $categoryHandler->getObjects($criteria, true);
    unset($criteria);
    //$categories[0] = _AM_XADDRESSES_DEFAULT;
    if ( count($cats) > 0 ) {
        foreach (array_keys($cats) as $i ) {
            $categories[$cats[$i]->getVar('cat_id')] = $cats[$i]->getVar('cat_title');
        }
    }
    $GLOBALS['xoopsTpl']->assign('categories', $categories);
    unset($categories);

    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('loc_suggested', 0));

    // get filter_status
    $filter_status = 'all';
    if (isset($_REQUEST['filter_status'])) {
        if ($_REQUEST['filter_status'] == '0') {
            $criteria->add(new Criteria('loc_status', 0, '!='));
            $filter_status = 0;
        }
        if ($_REQUEST['filter_status'] == '1') {
            $criteria->add(new Criteria('loc_status', 0));
            $filter_status = 1;
        }
    }

    $numRows = $locationHandler->getCount($criteria);

    // get limit
    if (isset($_REQUEST['limit'])) {
        $criteria->setLimit($_REQUEST['limit']);
        $limit = $_REQUEST['limit'];
    } else {
        $criteria->setLimit($xoopsModuleConfig['perpageadmin']);
        $limit = $xoopsModuleConfig['perpageadmin'];
    }
    // get start
    if (isset($_REQUEST['start'])) {
        $criteria->setStart($_REQUEST['start']);
        $start = $_REQUEST['start'];
    } else {
        $criteria->setStart(0);
        $start = 0;
    }
    // get sort_by
    $sort_by = 'loc_date';
    if (isset($_REQUEST['sort_by'])) {
        if ($_REQUEST['sort_by'] == 'loc_date') {
            $criteria->setSort('loc_date');
            $sort_by = 'loc_date';
        }
        if ($_REQUEST['sort_by'] == 'loc_title') {
            $criteria->setSort('loc_title');
            $sort_by = 'loc_title';
        }
        if ($_REQUEST['sort_by'] == 'loc_cat_id') {
            $criteria->setSort('loc_cat_id');
            $sort_by = 'loc_cat_id';
        }
    } else {
        $criteria->setSort('loc_date');
    }
    // get sort_order
    $sort_order = 'DESC';
    if (isset($_REQUEST['sort_order'])) {
        if ($_REQUEST['sort_order'] == 'DESC') {
            $criteria->setOrder('DESC');
            $sort_order = 'DESC';
        }
        if ($_REQUEST['sort_order'] == 'ASC') {
            $criteria->setOrder('ASC');
            $sort_order = 'ASC';
        }
    } else {
        $criteria->setOrder('DESC');
    }

    // page navigation form
    if ($numRows > $limit) {
        $pagenav = new XoopsPageNav($numRows, $limit, $start, 'start', 'op=list_locations&limit=' . $limit . '&sort_by=' . $sort_by. '&sort_order=' . $sort_order . '&filter_status=' . $filter_status);
        $pagenav = $pagenav->renderNav(4);
    } else {
        $pagenav = '';
    }
    $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav);

    // sort by/filter form
    $sortbyfilterform = '<form id="form_sort_by_filter" name="form_sort_by_filter" method="get" action="' . $currentFile . '">';
    $sortbyfilterform.= _AM_XADDRESSES_SORT_BY;
    $sortbyfilterform.= "<select name=\"sort_by\" id=\"sort_by\" onchange=\"location='" . XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->dirname() . "/admin/location.php?sort_by='+this.options[this.selectedIndex].value+'&sort_order=" . $sort_order . "&filter_status= " . $filter_status . "'\">";
    $sortbyfilterform.= '<option value="loc_date"' . ($sort_by == 'loc_date' ? ' selected="selected"' : '') . '>' . _AM_XADDRESSES_SORT_BY_DATE . '</option>';
    $sortbyfilterform.= '<option value="loc_title"' . ($sort_by == 'loc_title' ? ' selected="selected"' : '') . '>' . _AM_XADDRESSES_SORT_BY_TITLE . '</option>';
    $sortbyfilterform.= '<option value="loc_cat_id"' . ($sort_by == 'loc_cat_id' ? ' selected="selected"' : '') . '>' . _AM_XADDRESSES_SORT_BY_CAT . '</option>';
    $sortbyfilterform.= '</select> ';
    $sortbyfilterform.= _AM_XADDRESSES_ORDER;
    $sortbyfilterform.= "<select name=\"sort_order\" id=\"sort_order\" onchange=\"location='" . XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->dirname() . "/admin/location.php?sort_by=" . $sort_by . "&sort_order='+this.options[this.selectedIndex].value+'&filter_status=" . $filter_status . "'\">";
    $sortbyfilterform.= '<option value="DESC"' . ($sort_order == 'DESC' ? ' selected="selected"' : '') . '>' . _AM_XADDRESSES_ORDER_DESC . '</option>';
    $sortbyfilterform.= '<option value="ASC"' . ($sort_order == 'ASC' ? ' selected="selected"' : '') . '>' . _AM_XADDRESSES_ORDER_ASC . '</option>';
    $sortbyfilterform.= '</select> ';
    $sortbyfilterform.= _AM_XADDRESSES_FILTER;
    $sortbyfilterform.= ' | ';
    $sortbyfilterform.= _AM_XADDRESSES_LOC_STATUS . ' ';
    $sortbyfilterform.= "<select name=\"filter_status\" id=\"filter_status\" onchange=\"location='" . XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->dirname() . "/admin/location.php?sort_by=" . $sort_by . "&sort_order=" . $sort_order . "&filter_status='+this.options[this.selectedIndex].value+''\">";
    $sortbyfilterform.= '<option value="all"' . ($filter_status == 'all' ? ' selected="selected"' : '') . '>' . _AM_XADDRESSES_LOC_STATUS_ALL . '</option>';
    $sortbyfilterform.= '<option value="0"' . ($filter_status == '0' ? ' selected="selected"' : '') . '>' . _AM_XADDRESSES_LOC_STATUS_OK . '</option>';
    $sortbyfilterform.= '<option value="1"' . ($filter_status == '1' ? ' selected="selected"' : '') . '>' . _AM_XADDRESSES_LOC_STATUS_NOT_VALIDATED . '</option>';
    $sortbyfilterform.= '</select> ';
    $sortbyfilterform.= '</form>';
    $GLOBALS['xoopsTpl']->assign('sortbyfilterform', $sortbyfilterform);

    $locations = $locationHandler->getObjects($criteria, true, false); // get an array of arrays
    unset($criteria);

    // Get ids of categories in which locations can be viewed/edited/deleted/submitted
    $viewableCategoriesIds = xaddresses_getMyItemIds('in_category_view');
    $editableCategoriesIds = xaddresses_getMyItemIds('in_category_edit');
    $deletableCategoriesIds = xaddresses_getMyItemIds('in_category_delete');
    $submitableCategoriesIds = xaddresses_getMyItemIds('in_category_submit');

    foreach (array_keys($locations) as $i ) {
        if ($GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid())) {
            // admin can do everything
            $locations[$i]['canView'] = true;
            $locations[$i]['canEdit'] = true;
            $locations[$i]['canDelete'] = true;
        } else {
            $locations[$i]['canView'] = (in_array($i, $viewableCategoriesIds)); // IN PROGRESS
            $locations[$i]['canEdit'] = (in_array($i, $editableCategoriesIds)); // IN PROGRESS
            $locations[$i]['canDelete'] = (in_array($i, $deletableCategoriesIds)); // IN PROGRESS
        }
        $submitter =& $memberHandler->getUser($locations[$i]['loc_submitter']);
        $locations[$i]['loc_submitter_uname'] = $submitter->getVar('uname');
        //$locations[$i]['loc_submitter_linkeduname'] = xoops_getLinkedUnameFromId($submitter->getVar('uid')); // DEPRECATED
        $locations[$i]['loc_submitter_linkeduname'] = XoopsUserUtility::getUnameFromId($submitter->getVar('uid'), false, true);
        $locations[$i]['loc_submitter_uid'] = $submitter->getVar('uid');
        unset($submitter);
        $locations[$i]['loc_date'] = formatTimeStamp($locations[$i]['loc_date'], _DATESTRING);
     }
    $GLOBALS['xoopsTpl']->assign('locations', $locations);

    $GLOBALS['xoopsTpl']->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML() );
    $GLOBALS['xoopsTpl']->display("db:xaddresses_admin_locationlist.html");
    
    include "admin_footer.php";
    break;



case 'new_location':
    xoops_cp_header();
    // render submenu
    xaddressesAdminSubmenu ($submenuItem);

	$obj =& $locationHandler->create();
    $form = xaddresses_getLocationForm($obj);
    $form->display();

    include "admin_footer.php";
    break;



case 'list_locations_broken':
    xoops_cp_header();
    // render submenu
    xaddressesAdminSubmenu ($submenuItem);

    $brokenReports = $brokenHandler->getObjects(null, true, false); // get an array of arrays

    if (count($brokenReports) > 0) {
        foreach (array_keys($brokenReports) as $i ) {
            $loc_id = $brokenReports[$i]['loc_id'];
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
            $brokenReports[$i]['report_location'] = $location;
            $submitter =& $memberHandler->getUser($brokenReports[$i]['report_sender']);
            $brokenReports[$i]['report_sender_uname'] = $submitter->getVar('uname');
            $brokenReports[$i]['report_sender_linkeduname'] = XoopsUserUtility::getUnameFromId($submitter->getVar('uid'), false, true);
            $brokenReports[$i]['report_sender_uid'] = $submitter->getVar('uid');
            unset($submitter);
            $brokenReports[$i]['report_date'] = formatTimeStamp($brokenReports[$i]['report_date'], _DATESTRING);
         }
        $GLOBALS['xoopsTpl']->assign('brokenReports', $brokenReports);

        $GLOBALS['xoopsTpl']->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML() );
        $GLOBALS['xoopsTpl']->display("db:xaddresses_admin_locationbrokenlist.html");
    } else {
        echo '<div class="errorMsg">' . _AM_XADDRESSES_ERROR_NO_BROKEN_REPORTS . '</div>';
    }

    include "admin_footer.php";
    break;


    
case 'delete_broken':
    $brokenReport =& $brokenHandler->get($_REQUEST['report_id']);
    if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $report_id = $_REQUEST['report_id'];
        if ($brokenHandler->delete($brokenReport)) {
            redirect_header($currentFile, 1, _AM_XADDRESSES_REDIRECT_DEL_OK);
		} else {
			echo $location->getHtmlErrors();
		}
	} else {
        // render start here
        xoops_cp_header();
		xoops_confirm(array('ok' => 1, 'report_id' => $_REQUEST['report_id'], 'op' => 'delete_broken'), $_SERVER['REQUEST_URI'], _AM_XADDRESSES_LOC_BROKEN_SURE_DEL . '<br />');
        xoops_cp_footer();
	}
    break;

    

case 'edit_broken':
    xoops_cp_header();
    // render submenu
    xaddressesAdminSubmenu ($submenuItem);

    $brokenReport =& $brokenHandler->get($_REQUEST['report_id']);
    $location =& $locationHandler->get($brokenReport->getVar('loc_id'));
    
    include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
    $report_form = new XoopsThemeForm(_AM_XADDRESSES_LOC_BROKEN_REPORT, 'location_form', $currentFile, 'post', true);
    $report_form->setExtra('enctype="multipart/form-data"');
    $report_form->addElement(new XoopsFormLabel(_AM_XADDRESSES_LOC_BROKEN_DATE, formatTimeStamp($brokenReport->getVar('report_date'), _DATESTRING), ''));
 
    $submitter =& $memberHandler->getUser($brokenReport->getVar('report_sender'));
    $submitter_html = "";
    $submitter_html.= XoopsUserUtility::getUnameFromId($submitter->getVar('uid'), false, true);
    $submitter_html.= "<br />";
    if (is_object($submitter)) {
        if ($submitter->getVar('user_viewemail') == true || $GLOBALS['xoopsUser']->isAdmin()) {
            $submitter_html.= _US_EMAIL . ": ";
                $mailto_subject = urlencode("IN PROGRESS");
                $mailto_body = urlencode("IN PROGRESS");
                $submitter_html.= "<a href='mailto:" . $submitter->getVar('email', 'E') . "?subject= " . $mailto_subject . "&body=" . $mailto_body . "'>";
            $submitter_html.= $submitter->getVar('email', 'E');
            $submitter_html.= "</a>";
            $submitter_html.= "<br />";
        }
        $submitter_html.= _US_PM . ": ";
        $submitter_html.= "<a href=\"javascript:openWithSelfMain('" . XOOPS_URL . "/pmlite.php?send2=1&amp;to_userid=" . $submitter->getVar('uid') . "', 'pmlite', 450, 380);\"><img src=\"" . XOOPS_URL . "/images/icons/pm.gif\" alt=\"" . sprintf(_SENDPMTO, $submitter->getVar('uname')) . "\" /></a>";
        $submitter_html.= "<br />";
    }
    $submitter_html.= _AM_XADDRESSES_LOC_BROKEN_SENDER_IP . ": ";
    $submitter_html.= $brokenReport->getVar('report_ip');
    $submitter_html.= "<br />";
    $report_form->addElement(new XoopsFormLabel(_AM_XADDRESSES_LOC_BROKEN_SENDER, $submitter_html));

    $report_form->addElement(new XoopsFormLabel(_AM_XADDRESSES_LOC_BROKEN_DESCRIPTION, $brokenReport->getVar('report_description')));
    //
    $report_form->addElement(new XoopsFormHidden('report_id', $brokenReport->getVar('report_id')));
    $report_form->addElement(new XoopsFormHidden('op', 'delete_broken'));
        // Submit button
        $report_button_tray = new XoopsFormElementTray(_AM_XADDRESSES_ACTION, '' ,'');
        $report_button_tray->addElement(new XoopsFormButton('', 'delete_broken', _AM_XADDRESSES_LOC_BROKEN_DEL, 'submit'));
            $report_cancel_button = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
            $report_cancel_button->setExtra("onclick='javascript:history.back();'");
        $report_button_tray->addElement($report_cancel_button);
    $report_form->addElement($report_button_tray);
    //
    $report_form->display();
    
    include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
    $loc_form = new XoopsThemeForm(_AM_XADDRESSES_LOC_BROKEN_EDIT, 'location_form', $currentFile, 'post', true);
    $loc_form->setExtra('enctype="multipart/form-data"');
    $loc_form = xaddresses_getLocationForm($location, $currentFile, $loc_form);
    //
    $loc_form->addElement(new XoopsFormHidden('report_id', $brokenReport->getVar('report_id')));
    $loc_form->addElement(new XoopsFormHidden('loc_id', $brokenReport->getVar('loc_id')));
    $loc_form->addElement(new XoopsFormHidden('op', 'save_location'));
    $loc_form->addElement(new XoopsFormHidden('next_op', 'list_locations_broken'));
        // Submit button
        $loc_button_tray = new XoopsFormElementTray(_AM_XADDRESSES_ACTION, '' ,'');
        $loc_button_tray->addElement(new XoopsFormButton('', 'submit', _AM_XADDRESSES_LOC_EDIT, 'submit'));
    // IN PROGRESS
    // IN PROGRESS
    // IN PROGRESS
    //        $loc_submit_delete_broken_button = new XoopsFormButton('', '', _AM_XADDRESSES_LOC_EDIT . ' & ' . _AM_XADDRESSES_LOC_BROKEN_DEL, 'button');
    //        $loc_submit_delete_broken_button->setExtra("onclick='location.href=\"" . $currentFile . "?op=submit_and_delete_broken&report_id=" . $brokenReport->getVar('report_id') . "\"'");
    //    $loc_button_tray->addElement($loc_submit_delete_broken_button);
    // IN PROGRESS
    // IN PROGRESS
    // IN PROGRESS
        $loc_delete_broken_button = new XoopsFormButton('', '', _AM_XADDRESSES_LOC_BROKEN_DEL, 'button');
            $loc_delete_broken_button->setExtra("onclick='location.href=\"" . $currentFile . "?op=delete_broken&report_id=" . $brokenReport->getVar('report_id') . "\"'");
        $loc_button_tray->addElement($loc_delete_broken_button);
        $loc_button_tray->addElement(new XoopsFormButton('', 'reset', _RESET, 'reset'));
            $loc_cancel_button = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
            $loc_cancel_button->setExtra("onclick='javascript:history.back();'");
        $loc_button_tray->addElement($loc_cancel_button);
    $loc_form->addElement($loc_button_tray);
    //
    $loc_form->display();

    $report_form->display();
    
    include "admin_footer.php";
    break;



case 'list_locations_modify':
    xoops_cp_header();
    // render submenu
    xaddressesAdminSubmenu ($submenuItem);

    $modifySuggests = $modifyHandler->getObjects(null, true, false); // get an array of arrays

    if (count($modifySuggests) > 0) {
        foreach (array_keys($modifySuggests) as $i ) {
            $loc_id = $modifySuggests[$i]['loc_id'];
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
            $modifySuggests[$i]['report_location'] = $location;
            $submitter =& $memberHandler->getUser($modifySuggests[$i]['suggest_sender']);
            $modifySuggests[$i]['suggest_sender_uname'] = $submitter->getVar('uname');
            $modifySuggests[$i]['suggest_sender_linkeduname'] = XoopsUserUtility::getUnameFromId($submitter->getVar('uid'), false, true);
            $modifySuggests[$i]['suggest_sender_uid'] = $submitter->getVar('uid');
            unset($submitter);
            $modifySuggests[$i]['suggest_date'] = formatTimeStamp($modifySuggests[$i]['suggest_date'], _DATESTRING);
         }
        $GLOBALS['xoopsTpl']->assign('modifySuggests', $modifySuggests);

        $GLOBALS['xoopsTpl']->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML() );
        $GLOBALS['xoopsTpl']->display("db:xaddresses_admin_locationmodifylist.html");
    } else {
        echo '<div class="errorMsg">' . _AM_XADDRESSES_ERROR_NO_MODIFY_SUGGESTS . '</div>';
    }

    include "admin_footer.php";
    break;


    
case 'delete_modify':
    if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $suggest_id = (int)$_REQUEST['suggest_id'];
        $modifySuggest =& $modifyHandler->get($suggest_id);
        $suggest_loc_id = $modifySuggest->getVar('suggest_loc_id');
        $modifyLocation =& $locationHandler->get($suggest_loc_id);
        if (!$modifyHandler->delete($modifySuggest)) {
            echo $modifySuggest->getHtmlErrors();
		} elseif (!$locationHandler->delete($modifyLocation)) {
            echo $modifyLocation->getHtmlErrors();
		} else {
			redirect_header($currentFile, 1, _AM_XADDRESSES_REDIRECT_DEL_OK);
		}
	} else {
        // render start here
        xoops_cp_header();
		xoops_confirm(array('ok' => 1, 'suggest_id' => $_REQUEST['suggest_id'], 'op' => 'delete_modify'), $_SERVER['REQUEST_URI'], _AM_XADDRESSES_LOC_MODIFY_SURE_DEL . '<br />');
        xoops_cp_footer();
	}
    break;

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
case 'edit_location':
    xoops_cp_header();
    // render submenu
    xaddressesAdminSubmenu ($submenuItem);

    $obj =& $locationHandler->get($_REQUEST['loc_id']);
    $form = xaddresses_getLocationForm($obj, $currentFile);
    $form->display();

    include "admin_footer.php";
    break;



case 'save_location':
    if ( !$GLOBALS['xoopsSecurity']->check()  ) {
        redirect_header($currentFile, 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors() ));
        exit;
    }

    $errorFlag = false;
    $errorMessage = '';

    // Get fields
    $fields = $fieldHandler->loadFields();

    // Get ids of fields that can be viewed/edited
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
        $location->setVar('loc_date', time()); // creation date
    }

    $errors = array();

    foreach ($fields as $field) {
        $fieldname = $field->getVar('field_name');
        //if ( in_array($field->getVar('field_id'), $editable_fields) && isset($_REQUEST[$fieldname])  ) {
        $value = $field->getValueForSave((isset($_REQUEST[$fieldname]) ? $_REQUEST[$fieldname] : ''));
        $location->setVar($fieldname, $value);
       //     }
    }

    if (count($errors) == 0) {
        if ($locationHandler->insert($location)) {
        // TO DO NOTIFICATION SYSTEM
        // TO DO NOTIFICATION SYSTEM
        // TO DO NOTIFICATION SYSTEM
            $notificationHandler =& xoops_gethandler('notification');
            $tags = array();
            $tags['LOCATION_NAME'] = $location->getVar('loc_title');
            $tags['LOCATION_URL'] = XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/locationview.php?loc_id=' . $location->getVar('loc_id') . '&cat_id=' . $location->getVar('loc_cat_id');
            $category = $categoryHandler->get($location->getVar('loc_cat_id'));                
            $tags['CATEGORY_NAME'] = $category->getVar('cat_title');
            $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/locationcategoryview.php?cat_id=' . $category->getVar('cat_id');
            if ($location->isNew()) {
                $notificationHandler->triggerEvent('global', 0, 'new_location', $tags);
                $notificationHandler->triggerEvent('category', $location->getVar('loc_cat_id'), 'new_location', $tags);  
            } else {
            $tags['WAITINGLOCATIONS_URL'] = XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/admin/location.php?op=listlocations';
            $notificationHandler->triggerEvent('global', 0, 'location_submit', $tags);
            $notificationHandler->triggerEvent('category', $location->getVar('loc_cat_id'), 'file_submit', $tags);
            }
        // TO DO NOTIFICATION SYSTEM
        // TO DO NOTIFICATION SYSTEM
        // TO DO NOTIFICATION SYSTEM

            if ($location->isNew()) {
                redirect_header($currentFile . "?op=" . $next_op, 2, _AM_XADDRESSES_LOC_CREATED, false);
            } else {
                redirect_header($currentFile . "?op=" . $next_op, 2, sprintf(_AM_XADDRESSES_UPDATESUCCESS, $location->getVar('loc_title')), false);
            }
        }
    } else {
        foreach ($errors as $err) {
            $user->setErrors($err);
        }
    }

    echo $location->getHtmlErrors();

    $form = xaddresses_getAddressForm($location);
    $form->display();
    break;



case 'delete_location':
    $location =& $locationHandler->get($_REQUEST['loc_id']);
    if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $loc_id = $_REQUEST['loc_id'];
        if ($locationHandler->delete($location)) {
            // Delete all ratings
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', $loc_id));
            $votes = $votedataHandler->getall($criteria);
            foreach ($votes as $vote) {
                $votedataHandler->delete($vote) or $vote->getHtmlErrors();
            }
            // Delete all broken reports
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', $loc_id));
            $brokens = $brokenHandler->getall($criteria);
            foreach ($brokens as $broken) {
                $brokenHandler->delete($broken) or $broken->getHtmlErrors();
            }
    /*
                // supression des data des champs sup.
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('loc_id', $addresses_loc_id));
                $addresses_fielddata = $addressesfielddata_handler->getall( $criteria );
                foreach (array_keys($addresses_fielddata) as $i) {
                    $obj_fielddata =& $addressesfielddata_handler->get($addresses_fielddata[$i]->getVar('iddata'));
                    $addressesfielddata_handler->delete($obj_fielddata) or $obj_fielddata->getHtmlErrors();
                }
    */
            // Delete all comments
            xoops_comment_delete($GLOBALS['xoopsModule']->getVar('mid'), $loc_id);
            // Delete tags
            if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../../tag'))){
                $tagHandler = xoops_getmodulehandler('link', 'tag');
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('tag_itemid', $loc_id));
                $location_tags = $tagHandler->getall($criteria);
                foreach (array_keys($location_tags) as $i) {
                    $obj_tags =& $tagHandler->get($location_tags[$i]->getVar('tl_id'));
                    $tagHandler->delete($obj_tags) or $obj_tags->getHtmlErrors();
                }
            }

            redirect_header($currentFile, 1, _AM_XADDRESSES_REDIRECT_DEL_OK);
		} else {
			echo $location->getHtmlErrors();
		}
	} else {
        // render start here
        xoops_cp_header();
		xoops_confirm(array('ok' => 1, 'loc_id' => $_REQUEST['loc_id'], 'op' => 'delete_location'), $_SERVER['REQUEST_URI'], sprintf(_AM_XADDRESSES_FORM_SURE_DEL, $location->getVar('loc_title')) . '<br />');
        xoops_cp_footer();
	}
    break;



case 'view_location':
// TO DO
// TO DO
// TO DO
// TO DO
// TO DO
// TO DO
    $location = $locationHandler->get($_REQUEST['loc_id']);
    $criteria = new CriteriaCompo();
    echo '<h1>' . $location->getVar('loc_title') . ' ' . 'IN_PROGRESS</h1>';
//    echo _AM_XADDRESSES_FORMTEXT . ' ' . $location->getVar('loc_description');
//    echo '<br />';
    echo _AM_XADDRESSES_FORMATION;
    echo ' <br />';
    echo '<a href="' . $currentFile . '?op=edit_location&loc_id=' . $_REQUEST['loc_id'] . '">' . _EDIT . '</a>';
    echo '&nbsp;';
    echo '<a href="' . $currentFile . '?op=delete_location&loc_id=' . $_REQUEST['loc_id'] . '">' . _DELETE . '</a>';
    break;



case 'unlock_status':
    $obj =& $locationHandler->get($_REQUEST['loc_id']);
    $obj->setVar('loc_status', true);
    if ($locationHandler->insert($obj, true)) {
        redirect_header($currentFile . '?op=list_locations', 1, _AM_XADDRESSES_REDIRECT_SAVE);
    }
    echo $obj->getHtmlErrors();
    break;



case 'lock_status':
    $obj =& $locationHandler->get($_REQUEST['loc_id']);
    $obj->setVar('loc_status', false);
    if ($locationHandler->insert($obj, true)) {
        redirect_header($currentFile . '?op=list_locations', 1, _AM_XADDRESSES_REDIRECT_SAVE);
    }        
    echo $obj->getHtmlErrors();
    break;


}
?>