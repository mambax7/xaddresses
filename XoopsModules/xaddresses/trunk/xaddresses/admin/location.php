<?php
$currentFile = basename(__FILE__);
include_once 'admin_header.php';

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list_locations';

// load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$fieldHandler =& xoops_getModuleHandler('field', 'xaddresses');
$memberHandler =& xoops_gethandler('member');
$brokenHandler =& xoops_getModuleHandler('broken', 'xaddresses');
$votedataHandler =& xoops_getModuleHandler('votedata', 'xaddresses');

// redirection if no categories
if ($categoryHandler->getCount() == 0) {
    redirect_header('locationcategory.php?op=new_category', 2, _XADDRESSES_AM_LOC_NOCAT);
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
    $countBrokenReportsByLoc[$brokenReport->getVar('loc_id')]++;
}
$countBrokenLocations = count($countBrokenReportsByLoc);

// count modified locations
// IN PROGRESS
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_status', 0));
$countModifyLocations = 1;



switch ($op) {
default:
case 'list_locations':
    // render start here
    xoops_cp_header();
    // render main admin menu
    include (XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->dirname() . '/admin/menu.php');
    echo moduleAdminTabMenu($adminmenu, $currentFile);
    // render submenu
    $status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
    $submenuItem[] = ($op == 'new_location' ? _XADDRESSES_AM_LOC_NEW : '<a href="' . $currentFile . '?op=new_location">' . _XADDRESSES_AM_LOC_NEW . '</a>');
    $submenuItem[] = ($op == 'list_locations' && $status_display == 1 ? _XADDRESSES_AM_LOC_LIST . ' (' . $countLocations . ')' : '<a href="' . $currentFile . '?op=list_locations">' . _XADDRESSES_AM_LOC_LIST . ' (' . $countLocations . ')' . '</a>');
    $submenuItem[] = ($op == 'list_locations' && $status_display == 0 ? _XADDRESSES_AM_LOC_WAITING . ($countWaitingLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $countWaitingLocations . '</span>)') : '<a href="' . $currentFile . '?op=list_locations&status_display=0">' . _XADDRESSES_AM_LOC_WAITING . ($countWaitingLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $countWaitingLocations . '</span>)').'</a>');
    $submenuItem[] = ($op == 'list_locations_broken' ? _XADDRESSES_AM_LOC_BROKEN . ($countBrokenLocations == 0 ? '(0)' : ' (<span style="color : Red">' . $countBrokenLocations . '</span>)') : '<a href="' . $currentFile . '?op=list_locations_broken">' . _XADDRESSES_AM_LOC_BROKEN . ($countBrokenLocations == 0 ? '' : ' (<span style="color : Red">' . $countBrokenLocations . '</span>)').'</a>');
    $submenuItem[] = ($op == 'list_locations_modified' ? _XADDRESSES_AM_LOC_MODIFIED . ($countModifyLocations == 0 ? '(0)' : ' (<span style="color : Red">' . $countModifyLocations . '</span>)') : '<a href="' . $currentFile . '?op=list_locations_modified">' . _XADDRESSES_AM_LOC_MODIFIED . ($countModifyLocations == 0 ? '' : ' (<span style="color : Red">' . $countModifyLocations . '</span>)').'</a>');
    //$submenuItem[] = ($op == 'search' ? _XADDRESSES_AM_LOC_SEARCH : '<a href="' . $currentFile . '?op=search">' . _XADDRESSES_AM_LOC_SEARCH . '</a>');
    xaddressesAdminSubmenu ($submenuItem);
    
    // get fields categories
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight');
    $cats = $categoryHandler->getObjects($criteria, true);
    unset($criteria);
    //$categories[0] = _XADDRESSES_AM_DEFAULT;
    if ( count($cats) > 0 ) {
        foreach (array_keys($cats) as $i ) {
            $categories[$cats[$i]->getVar('cat_id')] = $cats[$i]->getVar('cat_title');
        }
    }
    $GLOBALS['xoopsTpl']->assign('categories', $categories);
    unset($categories);

    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('loc_suggested', false));
    // list only active (loc_status == 1) locations
    if (isset($_REQUEST['status_display'])) {
        if ($_REQUEST['status_display'] == 0) {
            $criteria->add(new Criteria('loc_status', 0));
            $status_display = 0;
        } else {
            $criteria->add(new Criteria('loc_status', 0, '!='));
            $status_display = 1;
        }
    } else {
        //$criteria->add(new Criteria('loc_status', 0, '!='));
        //$status_display = 1;
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
        $pagenav = new XoopsPageNav($numRows, $limit, $start, 'start', 'op=list_locations&limit=' . $limit . '&sort_by=' . $sort_by. '&sort_order=' . $sort_order . '&status_display=' . $status_display);
        $pagenav = $pagenav->renderNav(4);
    } else {
        $pagenav = '';
    }
    $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav);

    // sort by form
    $sortbyform = '<form id="form_sort_by" name="form_sort_by" method="get" action="' . $currentFile . '">';
    $sortbyform.= _XADDRESSES_AM_SORT_BY . "<select name=\"sort_by\" id=\"sort_by\" onchange=\"location='" . XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->dirname() . "/admin/location.php?status_display=$status_display&sort_order=$sort_order&sort_by='+this.options[this.selectedIndex].value\">";
    $sortbyform.= '<option value="loc_date"' . ($sort_by == 'loc_date' ? ' selected="selected"' : '') . '>' . _XADDRESSES_AM_SORT_BY_DATE . '</option>';
    $sortbyform.= '<option value="loc_title"' . ($sort_by == 'loc_title' ? ' selected="selected"' : '') . '>' . _XADDRESSES_AM_SORT_BY_TITLE . '</option>';
    $sortbyform.= '<option value="loc_cat_id"' . ($sort_by == 'loc_cat_id' ? ' selected="selected"' : '') . '>' . _XADDRESSES_AM_SORT_BY_CAT . '</option>';
    $sortbyform.= '</select> ';
    $sortbyform.= _XADDRESSES_AM_ORDER . "<select name=\"order_tri\" id=\"order_tri\" onchange=\"location='" . XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->dirname() . "/admin/location.php?status_display=$status_display&sort_by=$sort_by&sort_order='+this.options[this.selectedIndex].value\">";
    $sortbyform.= '<option value="DESC"' . ($sort_order == 'DESC' ? ' selected="selected"' : '') . '>' . _XADDRESSES_AM_ORDER_DESC . '</option>';
    $sortbyform.= '<option value="ASC"' . ($sort_order == 'ASC' ? ' selected="selected"' : '') . '>' . _XADDRESSES_AM_ORDER_ASC . '</option>';
    $sortbyform.= '</select> ';
    $sortbyform.= '</form>';
    $GLOBALS['xoopsTpl']->assign('sortbyform', $sortbyform);

    if ($numRows > 0) {
        $locations = $locationHandler->getObjects($criteria, true, false); // get an array of arrays
        unset($criteria);

        // Get ids of categories in which locations can be viewed/edited/submitted
        $groupPermHandler =& xoops_gethandler('groupperm');
        $viewableCategories = $groupPermHandler->getItemIds('in_category_view', $groups, $GLOBALS['xoopsModule']->getVar('mid'));
        $editableCategories = $groupPermHandler->getItemIds('in_category_edit', $groups, $GLOBALS['xoopsModule']->getVar('mid'));
        $submitableCategories = $groupPermHandler->getItemIds('in_category_submit', $groups, $GLOBALS['xoopsModule']->getVar('mid'));

        foreach (array_keys($locations) as $i ) {
            if ($GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid())) {
                // admin can do everything
                $locations[$i]['canView'] = true;
                $locations[$i]['canEdit'] = true;
                $locations[$i]['canDelete'] = true;
            } else {
                $locations[$i]['canView'] = (in_array($i, $viewableCategories)); // IN PROGRESS
                $locations[$i]['canEdit'] = (in_array($i, $editableCategories)); // IN PROGRESS
                $locations[$i]['canDelete'] = (in_array($i, $editableCategories)); // IN PROGRESS
            }
            $submitter =& $memberHandler->getUser($locations[$i]['loc_submitter']);
            $locations[$i]['loc_submitter_uname'] = $submitter->getVar('uname');
            $locations[$i]['loc_submitter_linkeduname'] = xoops_getLinkedUnameFromId($submitter->getVar('uid'));
            $locations[$i]['loc_submitter_uid'] = $submitter->getVar('uid');
            unset($submitter);
            $locations[$i]['loc_date'] = formatTimeStamp($locations[$i]['loc_date'], _DATESTRING);
         }
        $GLOBALS['xoopsTpl']->assign('locations', $locations);
    
        $GLOBALS['xoopsTpl']->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML() );
        $GLOBALS['xoopsTpl']->display("db:xaddresses_admin_locationlist.html");
    } else {
        echo '<div class="errorMsg">' . _XADDRESSES_AM_ERROR_NO_LOCS . '</div>';
    }
    
    xoops_cp_footer();
    break;



case 'new_location':
    xoops_cp_header();
    // main admin menu
    include (XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->dirname() . '/admin/menu.php');
    echo moduleAdminTabMenu($adminmenu, $currentFile);
    // Submenu
    $status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
    $submenuItem[] = ($op == 'new_location' ? _XADDRESSES_AM_LOC_NEW : '<a href="' . $currentFile . '?op=new_location">' . _XADDRESSES_AM_LOC_NEW . '</a>');
    $submenuItem[] = ($op == 'list_locations' && $status_display == 1 ? _XADDRESSES_AM_LOC_LIST . ' (' . $countLocations . ')' : '<a href="' . $currentFile . '?op=list_locations">' . _XADDRESSES_AM_LOC_LIST . ' (' . $countLocations . ')' . '</a>');
    $submenuItem[] = ($op == 'list_locations' && $status_display == 0 ? _XADDRESSES_AM_LOC_WAITING . ($countWaitingLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $waitingLocations . '</span>)') : '<a href="' . $currentFile . '?op=list_locations&status_display=0">' . _XADDRESSES_AM_LOC_WAITING . ($countWaitingLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $countWaitingLocations . '</span>)').'</a>');
    $submenuItem[] = ($op == 'list_locations_broken' ? _XADDRESSES_AM_LOC_BROKEN . ($brokenLocations == 0 ? '(0)' : ' (<span style="color : Red">' . $countBrokenLocations . '</span>)') : '<a href="' . $currentFile . '?op=list_locations_broken">' . _XADDRESSES_AM_LOC_BROKEN . ($countBrokenLocations == 0 ? '' : ' (<span style="color : Red">' . $countBrokenLocations . '</span>)').'</a>');
    $submenuItem[] = ($op == 'list_locations_modified' ? _XADDRESSES_AM_LOC_MODIFIED . ($modifiedLocations == 0 ? '(0)' : ' (<span style="color : Red">' . $ountModifiedLocations . '</span>)') : '<a href="' . $currentFile . '?op=list_locations_modified">' . _XADDRESSES_AM_LOC_MODIFIED . ($countModifyLocations == 0 ? '' : ' (<span style="color : Red">' . $countModifyLocations . '</span>)').'</a>');
    //$submenuItem[] = ($op == 'search' ? _XADDRESSES_AM_LOC_SEARCH : '<a href="' . $currentFile . '?op=search">' . _XADDRESSES_AM_LOC_SEARCH . '</a>');
    xaddressesAdminSubmenu ($submenuItem);

	$obj =& $locationHandler->create();
    $form = xaddresses_getLocationForm($obj);
    $form->display();

    xoops_cp_footer();
    break;



case 'edit_location':
    xoops_cp_header();
    // main admin menu
    include (XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->dirname() . '/admin/menu.php');
    echo moduleAdminTabMenu($adminmenu, $currentFile);
    // Submenu
    $status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
    $submenuItem[] = ($op == 'new_location' ? _XADDRESSES_AM_LOC_NEW : '<a href="' . $currentFile . '?op=new_location">' . _XADDRESSES_AM_LOC_NEW . '</a>');
    $submenuItem[] = ($op == 'list_locations' && $status_display == 1 ? _XADDRESSES_AM_LOC_LIST . ' (' . $countLocations . ')' : '<a href="' . $currentFile . '?op=list_locations">' . _XADDRESSES_AM_LOC_LIST . ' (' . $countLocations . ')' . '</a>');
    $submenuItem[] = ($op == 'list_locations' && $status_display == 0 ? _XADDRESSES_AM_LOC_WAITING . ($countWaitingLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $countWaitingLocations . '</span>)') : '<a href="' . $currentFile . '?op=list_locations&status_display=0">' . _XADDRESSES_AM_LOC_WAITING . ($countWaitingLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $countWaitingLocations . '</span>)').'</a>');
    $submenuItem[] = ($op == 'list_locations_broken' ? _XADDRESSES_AM_LOC_BROKEN . ($countBrokenLocations == 0 ? '(0)' : ' (<span style="color : Red">' . $countBrokenLocations . '</span>)') : '<a href="' . $currentFile . '?op=list_locations_broken">' . _XADDRESSES_AM_LOC_BROKEN . ($countBrokenLocations == 0 ? '' : ' (<span style="color : Red">' . $countBrokenLocations . '</span>)').'</a>');
    $submenuItem[] = ($op == 'list_locations_modified' ? _XADDRESSES_AM_LOC_MODIFIED . ($countModifyLocations == 0 ? '(0)' : ' (<span style="color : Red">' . $countModifyLocations . '</span>)') : '<a href="' . $currentFile . '?op=list_locations_modified">' . _XADDRESSES_AM_LOC_MODIFIED . ($countModifyLocations == 0 ? '' : ' (<span style="color : Red">' . $countModifyLocations . '</span>)').'</a>');
    //$submenuItem[] = ($op == 'search' ? _XADDRESSES_AM_LOC_SEARCH : '<a href="' . $currentFile . '?op=search">' . _XADDRESSES_AM_LOC_SEARCH . '</a>');
    xaddressesAdminSubmenu ($submenuItem);

    $obj =& $locationHandler->get($_REQUEST['loc_id']);
    $form = xaddresses_getLocationForm($obj, $currentFile);
    $form->display();

    xoops_cp_footer();
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
                redirect_header($currentFile, 2, _XADDRESSES_AM_LOC_CREATED, false);
            } else {
                redirect_header($currentFile, 2, sprintf(_XADDRESSES_AM_UPDATESUCCESS, $location->getVar('loc_title')), false);
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

            redirect_header($currentFile, 1, _XADDRESSES_AM_REDIRECT_DEL_OK);
		} else {
			echo $location->getHtmlErrors();
		}
	} else {
        // render start here
        xoops_cp_header();
		xoops_confirm(array('ok' => 1, 'loc_id' => $_REQUEST['loc_id'], 'op' => 'delete_location'), $_SERVER['REQUEST_URI'], sprintf(_XADDRESSES_AM_FORM_SURE_DEL, $location->getVar('loc_title')) . '<br />');
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
//    echo _XADDRESSES_AM_FORMTEXT . ' ' . $location->getVar('loc_description');
//    echo '<br />';
    echo _XADDRESSES_AM_FORMATION;
    echo ' <br />';
    echo '<a href="' . $currentFile . '?op=edit_location&loc_id=' . $_REQUEST['loc_id'] . '">' . _EDIT . '</a>';
    echo '&nbsp;';
    echo '<a href="' . $currentFile . '?op=delete_location&loc_id=' . $_REQUEST['loc_id'] . '">' . _DELETE . '</a>';
    break;



case 'unlock_status':
    $obj =& $locationHandler->get($_REQUEST['loc_id']);
    $obj->setVar('loc_status', true);
    if ($locationHandler->insert($obj, true)) {
        redirect_header($currentFile . '?op=list_locations&status_display=0', 1, _XADDRESSES_AM_REDIRECT_SAVE);
    }
    echo $obj->getHtmlErrors();
    break;



case 'lock_status':
    $obj =& $locationHandler->get($_REQUEST['loc_id']);
    $obj->setVar('loc_status', false);
    if ($locationHandler->insert($obj, true)) {
        redirect_header($currentFile . '?op=list_locations&status_display=0', 1, _XADDRESSES_AM_REDIRECT_SAVE);
    }        
    echo $obj->getHtmlErrors();
    break;


}
?>