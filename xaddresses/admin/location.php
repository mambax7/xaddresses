<?php
include_once 'admin_header.php';
$currentFile = basename(__FILE__);
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list_locations';

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
    xaddressesAdminMenu(3, _XADDRESSES_MI_ADMENU_ITEM);
} else {
    include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
    loadModuleAdminMenu (3, _XADDRESSES_MI_ADMENU_ITEM);
}

// count valid locations
$criteria = new CriteriaCompo();
//$criteria->add(new Criteria('loc_status', 0, '!='));
$countLocations = $locationHandler->getCount($criteria);

// count waiting/not valid locations
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_status', 0));
$waitingLocations = $locationHandler->getCount($criteria);

// count broken locations
$brokenLocations = $brokenHandler->getCount();

// count modified locations
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_mod_id', 0, '!='));
$modifiedLocations = $locationHandler->getCount($criteria);


// Submenu
$status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
$submenuItem[] = ($op == 'new_location' ? _XADDRESSES_AM_LOC_NEW : '<a href="' . $currentFile . '?op=new_location">' . _XADDRESSES_AM_LOC_NEW . '</a>');
$submenuItem[] = ($op == 'list_locations' && $status_display == 1 ? _XADDRESSES_AM_LOC_LIST . ' (' . $countLocations . ')' : '<a href="' . $currentFile . '?op=list_locations">' . _XADDRESSES_AM_LOC_LIST . ' (' . $countLocations . ')' . '</a>');
$submenuItem[] = ($op == 'list_locations' && $status_display == 0 ? _XADDRESSES_AM_LOC_WAITING . ($waitingLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $waitingLocations . '</span>)') : '<a href="' . $currentFile . '?op=list_locations&status_display=0">' . _XADDRESSES_AM_LOC_WAITING . ($waitingLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $waitingLocations . '</span>)').'</a>');
$submenuItem[] = ($op == 'list_locations_broken' ? _XADDRESSES_AM_LOC_BROKEN . ($brokenLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $brokenLocations . '</span>)') : '<a href="' . $currentFile . '?op=list_locations_broken">' . _XADDRESSES_AM_LOC_BROKEN . ($brokenLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $brokenLocations . '</span>)').'</a>');
$submenuItem[] = ($op == 'list_locations_modified' ? _XADDRESSES_AM_LOC_MODIFIED . ($modifiedLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $modifiedLocations . '</span>)') : '<a href="' . $currentFile . '?op=list_locations_modified">' . _XADDRESSES_AM_LOC_MODIFIED . ($modifiedLocations == 0 ? ' (0)' : ' (<span style="color : Red">' . $modifiedLocations . '</span>)').'</a>');

//$submenuItem[] = ($op == 'search' ? _XADDRESSES_AM_LOC_SEARCH : '<a href="' . $currentFile . '?op=search">' . _XADDRESSES_AM_LOC_SEARCH . '</a>');
xaddressesAdminSubmenu ($submenuItem);



switch ($op) {
default:
case 'list_locations':
    // redirection if no categories
    if ($categoryHandler->getCount() == 0) {
        redirect_header('locationcategory.php?op=new_category', 2, _XADDRESSES_AM_REDIRECT_NOCAT);
    }

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
    // IN_PROGRESS - TO DO: use Xoops form objects
    $sortbyform = '<form id="form_sort_by" name="form_sort_by" method="get" action="' . $currentFile . '">';
    $sortbyform.= _XADDRESSES_AM_TRIPAR . "<select name=\"sort_by\" id=\"sort_by\" onchange=\"location='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/admin/location.php?status_display=$status_display&sort_order=$sort_order&sort_by='+this.options[this.selectedIndex].value\">";
    $sortbyform.= '<option value="loc_date"' . ($sort_by == 'loc_date' ? ' selected="selected"' : '') . '>' . _XADDRESSES_AM_FORMDATE . '</option>';
    $sortbyform.= '<option value="loc_title"' . ($sort_by == 'loc_title' ? ' selected="selected"' : '') . '>' . _XADDRESSES_AM_FORMTITLE . '</option>';
    $sortbyform.= '<option value="loc_cat_id"' . ($sort_by == 'loc_cat_id' ? ' selected="selected"' : '') . '>' . _XADDRESSES_AM_FORMCAT . '</option>';
    $sortbyform.= '</select> ';
    $sortbyform.= _XADDRESSES_AM_ORDER . "<select name=\"order_tri\" id=\"order_tri\" onchange=\"location='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/admin/location.php?status_display=$status_display&sort_by=$sort_by&sort_order='+this.options[this.selectedIndex].value\">";
    $sortbyform.= '<option value="DESC"' . ($sort_order == 'DESC' ? ' selected="selected"' : '') . '>DESC</option>';
    $sortbyform.= '<option value="ASC"' . ($sort_order == 'ASC' ? ' selected="selected"' : '') . '>ASC</option>';
    $sortbyform.= '</select> ';
    $sortbyform.= '</form>';
    $GLOBALS['xoopsTpl']->assign('sortbyform', $sortbyform);



    if ($numRows > 0) {
        $locations = $locationHandler->getObjects($criteria, true, false); // get an array of arrays, loc_id as key
        unset($criteria);

        // Get ids of categories in which locations can be viewed/edited/submitted
        $groupPermHandler =& xoops_gethandler('groupperm');
        $viewableCategories = $groupPermHandler->getItemIds('in_category_view', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
        $editableCategories = $groupPermHandler->getItemIds('in_category_edit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
        $deletableCategories = $groupPermHandler->getItemIds('in_category_delete', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
        $submitableCategories = $groupPermHandler->getItemIds('in_category_submit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );

        foreach (array_keys($locations) as $loc_id ) {
            $locations[$loc_id]['canView'] = (in_array($locations[$loc_id]['loc_cat_id'], $viewableCategories)); // IN PROGRESS
            $locations[$loc_id]['canEdit'] = (in_array($locations[$loc_id]['loc_cat_id'], $editableCategories)); // IN PROGRESS
            $locations[$loc_id]['canDelete'] = (in_array($locations[$loc_id]['loc_cat_id'], $deletableCategories)); // IN PROGRESS
            $submitter =& $memberHandler->getUser($locations[$loc_id]['loc_submitter']);
            $locations[$loc_id]['loc_submitter_name'] = $submitter->getVar('uname');
            $locations[$loc_id]['loc_submitter_id'] = $submitter->getVar('uid');
            unset($submitter);
            $locations[$loc_id]['loc_date'] = formatTimeStamp($locations[$loc_id]['loc_date'], _DATESTRING);
         }
        $GLOBALS['xoopsTpl']->assign('locations', $locations);

        $GLOBALS['xoopsTpl']->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML() );
        $GLOBALS['xoopsTpl']->display("db:xaddresses_admin_locationlist.html");
    } else {
        echo '<div class="errorMsg">' . _XADDRESSES_AM_ERROR_NOADDRESSES . '</div>';
    }
    break;



case 'new_location':
	$obj =& $locationHandler->create();
    $form = xaddresses_getLocationForm($obj);
    $form->display();
    break;



case 'edit_location':
    $obj =& $locationHandler->get($_REQUEST['loc_id']);
    $form = xaddresses_getLocationForm($obj, $currentFile);
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
                redirect_header($currentFile, 2, _US_PROFUPDATED, false);
            }
        }
    } else {
        foreach ($errors as $err) {
            $user->setErrors($err);
        }
    }
    //$location->setGroups($new_groups);
    echo $location->getHtmlErrors();

    $form = xlocations_getAddressForm($location);
    $form->display();
    break;



/*
	if (!$GLOBALS['xoopsSecurity']->check()) {
       redirect_header('category.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
    }
*/



case 'delete_location':
    global $xoopsModule;
    $obj =& $locationHandler->get($_REQUEST['loc_id']);
    if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $loc_id = $_REQUEST['loc_id'];
        if ($locationHandler->delete($obj)) {
// TO DO
// TO DO
// TO DO
// TO DO
// TO DO
/*
            // supression des votes
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', $addresses_loc_id));
            $addresses_votedata = $votedataHandler->getall( $criteria );
            foreach (array_keys($addresses_votedata) as $i) {
                $obj_votedata =& $votedataHandler->get($addresses_votedata[$i]->getVar('rating_id'));
                $votedataHandler->delete($obj_votedata) or $obj_votedata->getHtmlErrors();
            }
*/
/*
            // supression des rapports de fichier brisé
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', $addresses_loc_id));
            $addresses_broken = $addressesbroken_handler->getall( $criteria );
            foreach (array_keys($addresses_broken) as $i) {
                $obj_broken =& $addressesbroken_handler->get($addresses_broken[$i]->getVar('reportid'));
                $addressesbroken_handler->delete($obj_broken ) or $obj_broken ->getHtmlErrors();
            }
*/
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
/*
            // supression des commentaires
            xoops_comment_delete($xoopsModule->getVar('mid'), $addresses_loc_id);
            //supression des tags
            if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../../tag'))){
                $tag_handler = xoops_getmodulehandler('link', 'tag');
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('tag_locationid', $addresses_loc_id));
                $addresses_tags = $tag_handler->getall( $criteria );
                foreach (array_keys($addresses_tags) as $i) {
                    $obj_tags =& $tag_handler->get($addresses_tags[$i]->getVar('tl_id'));
                    $tag_handler->delete($obj_tags) or $obj_tags->getHtmlErrors();
                }
            }
*/
			redirect_header($currentFile, 1, _XADDRESSES_AM_REDIRECT_DELOK);
		} else {
			echo $obj->getHtmlErrors();
		}
	} else {
		xoops_confirm(array('ok' => 1, 'loc_id' => $_REQUEST['loc_id'], 'op' => 'delete_location'), $_SERVER['REQUEST_URI'], sprintf(_XADDRESSES_AM_FORMSUREDEL, $obj->getVar('loc_title')) . '<br />');
	}
    break;



case 'view_location':
// TO DO
// TO DO
// TO DO
// TO DO
// TO DO
    $viewLocation = $locationHandler->get($_REQUEST['loc_id']);
    $criteria = new CriteriaCompo();
    echo '<h1>' . $viewLocation->getVar('loc_title') . ' ' . 'IN_PROGRESS</h1>';
//    echo _XADDRESSES_AM_FORMTEXT . ' ' . $viewLocation->getVar('loc_description');
//    echo '<br />';
    echo _XADDRESSES_AM_FORMACTION;
    echo ' <br />';
    echo '<a href="' . $currentFile . '?op=edit_location&loc_id=' . $_REQUEST['loc_id'] . '">' . _EDIT . '</a>';
    echo '&nbsp;';
    echo '<a href="' . $currentFile . '?op=delete_location&loc_id=' . $_REQUEST['loc_id'] . '">' . _DELETE . '</a>';
    break;
/*
case 'view_location':
// TO DO
// TO DO
// TO DO
// TO DO
// TO DO
    //information du téléchargement
	$view_location = $locationHandler->get($_REQUEST['loc_id']);
    //category
    $view_category = $categoryHandler->get($view_addresses->getVar('loc_cat_id'));
    // sortie des informations
	$location_title = $view_location->getVar('loc_title');
    $location_description = $view_location->getVar('loc_description');
    //permet d'enlever [pagebreak] du texte
    $location_description = str_replace('[pagebreak]','',$location_description);
    // pour référentiel de module
    $location_description = str_replace('[block]','<h2><u>' . _MD_XADDRESSES_SUP_BLOCS . '</u></h2>',$location_description);
    $location_description = str_replace('[notes]','<h2><u>' . _MD_XADDRESSES_SUP_NOTES . '</u></h2>',$location_description);
    $location_description = str_replace('[evolutions]','<h2><u>' . _MD_XADDRESSES_SUP_EVOLUTIONS . '</u></h2>',$location_description);
    $location_description = str_replace('[infos]','<h2><u>' . _MD_XADDRESSES_SUP_INFOS . '</u></h2>',$location_description);
    $location_description = str_replace('[changelog]','<h2><u>' . _MD_XADDRESSES_SUP_CHANGELOG . '</u></h2>',$location_description);
    $location_description = str_replace('[backoffice]','<h2><u>' . _MD_XADDRESSES_SUP_BACKOFFICE . '</u></h2>',$location_description);
    $location_description = str_replace('[frontoffice]','<h2><u>' . _MD_XADDRESSES_SUP_FRONTOFFICE . '</u></h2>',$location_description);

    // affichages des informations du téléchargement
	echo '<table width="100%" cellspacing="1" class="outer">';
	echo '<tr>';
	echo '<th align="center" colspan="2">' . $location_title . '</th>';
	echo '</tr>';
    echo '<tr class="even">';
	echo '<td width="30%">' . _XADDRESSES_AM_FORMFILE . ' </td>';
	echo '<td><a href="../visit.php?cid=' . $view_location->getVar('loc_cat_id') . '&loc_id=' . $_REQUEST['loc_id'] . '"><img src="../images/icon/download.png" alt="Download ' . $location_title . '" title="Download ' . $location_title . '"></a></td>';
	echo '</tr>';
    echo '<tr class="odd">';
	echo '<td width="30%">' . _XADDRESSES_AM_FORMCAT . ' </td>';
	echo '<td>' . $view_category->getVar('title') . ' <a href="categories.php?op=view_cat&categories_cid=' . $view_location->getVar('cid') . '"><img src="../images/icon/view_mini.png" alt="' . _XADDRESSES_AM_FORMDISPLAY . '" title="' . _XADDRESSES_AM_FORMDISPLAY . '"></a></td>';
	echo '</tr>';
    $criteria = new CriteriaCompo();
    $criteria->setSort('weight ASC, title');
    $criteria->setOrder('ASC');
    $criteria->add(new Criteria('status', 1));
    $addresses_field = $addressesfield_handler->getall($criteria);
    $class = 'odd';
    foreach (array_keys($addresses_field) as $i) {
        $class = ($class == 'even') ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        if ($addresses_field[$i]->getVar('status_def') == 1){
            if ($addresses_field[$i]->getVar('fid') == 1){
                //page d'accueil
                echo '<td width="30%">' . _XADDRESSES_AM_FORMHOMEPAGE . ' </td>';
                echo '<td><a href="' . $view_addresses->getVar('homepage') . '">' . $view_addresses->getVar('homepage') . '</a></td>';
            }
            if ($addresses_field[$i]->getVar('fid') == 2){
                //version
                echo '<td width="30%">' . _XADDRESSES_AM_FORMVERSION . ' </td>';
                echo '<td>' . $view_addresses->getVar('version') . '</td>';
            }
            if ($addresses_field[$i]->getVar('fid') == 3){
                //taille du fichier
                echo '<td width="30%">' . _XADDRESSES_AM_FORMSIZE . ' </td>';
                echo '<td>' . trans_size($view_addresses->getVar('size')) . '</td>';
            }
            if ($addresses_field[$i]->getVar('fid') == 4){
                //plateforme
                echo '<td width="30%">' . _XADDRESSES_AM_FORMPLATFORM . ' </td>';
                echo '<td>' . $view_addresses->getVar('platform') . '</td>';
            }
        }else{
            $contenu = '';
            echo '<td width="30%">' . $addresses_field[$i]->getVar('title') . ' </td>';
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', $_REQUEST['addresses_loc_id']));
            $criteria->add(new Criteria('fid', $addresses_field[$i]->getVar('fid')));
            $addressesfielddata = $addressesfielddata_handler->getall($criteria);
            foreach (array_keys($addressesfielddata) as $j) {
                $contenu = $addressesfielddata[$j]->getVar('data');
            }
            echo '<td>' . $contenu . '</td>';
        }
        echo '</tr>';
    }
    $class = ($class == 'even') ? 'odd' : 'even';
	echo '<tr class="' . $class . '">';
	echo '<td width="30%">' . _XADDRESSES_AM_FORMTEXT . ' </td>';
	echo '<td>' . $location_description . '</td>';
	echo '</tr>';
    // tags
    if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../../tag'))){
        require_once XOOPS_ROOT_PATH.'/modules/tag/include/tagbar.php';
        $tags_array = tagBar($_REQUEST['downloads_loc_id'], 0);
        if (!empty($tags_array)){
            $tags = '';
            foreach (array_keys($tags_array['tags']) as $i) {
                $tags .= $tags_array['delimiter'] . ' ' . $tags_array['tags'][$i] . ' ';
            }

            $class = ($class == 'even') ? 'odd' : 'even';
            echo '<tr class="' . $class . '">';
            echo '<td width="30%">' . $tags_array['title'] . ' </td>';
            echo '<td>' . $tags . '</td>';
            echo '</tr>';
        }
    }
    if ( $xoopsModuleConfig['useshots']){
        $class = ($class == 'even') ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _XADDRESSES_AM_FORMIMG . ' </td>';
        echo '<td><img src="' . $uploadurl . $view_location->getVar('logourl') . '" alt="" title=""></td>';
        echo '</tr>';
    }
	$class = ($class == 'even') ? 'odd' : 'even';
	echo '<tr class="' . $class . '">';
	echo '<td width="30%">' . _XADDRESSES_AM_FORMDATE . ' </td>';
	echo '<td>' . formatTimestamp($view_location->getVar('date')) . '</td>';
	echo '</tr>';
	$class = ($class == 'even') ? 'odd' : 'even';
	echo '<tr class="' . $class . '">';
	echo '<td width="30%">' . _XADDRESSES_AM_FORMPOSTER . ' </td>';
	echo '<td>' . XoopsUser::getUnameFromId($view_location->getVar('submitter')) . '</td>';
	echo '</tr>';
	$class = ($class == 'even') ? 'odd' : 'even';
	echo '<tr class="' . $class . '">';
	echo '<td width="30%">' . _XADDRESSES_AM_FORMHITS . ' </td>';
	echo '<td>' . $view_location->getVar('hits'). '</td>';
	echo '</tr>';
	$class = ($class == 'even') ? 'odd' : 'even';
	echo '<tr class="' . $class . '">';
	echo '<td width="30%">' . _XADDRESSES_AM_FORMRATING . ' </td>';
	echo '<td>' . number_format($view_location->getVar('rating'),1) . ' (' . $view_location->getVar('votes') . ' ' . _XADDRESSES_AM_FORMVOTE . ')</td>';
	echo '</tr>';
	$class = ($class == 'even') ? 'odd' : 'even';
	echo '<tr class="' . $class . '">';
	echo '<td width="30%">' . _XADDRESSES_AM_FORMCOMMENTS . ' </td>';
	echo '<td>' . $view_location->getVar('comments') . ' <a href="../singlefile.php?cid=' . $view_location->getVar('cid') . '&loc_id=' . $_REQUEST['locationes_loc_id'] . '"><img src="../images/icon/view_mini.png" alt="' . _XADDRESSES_AM_FORMDISPLAY . '" title="' . _XADDRESSES_AM_FORMDISPLAY . '"></a></td>';
	echo '</tr>';
	$class = ($class == 'even') ? 'odd' : 'even';
	echo '<tr class="' . $class . '">';
	echo '<td width="30%">' . _XADDRESSES_AM_FORMACTION . ' </td>';
	echo '<td>';
    echo ($view_location->getVar('status') != 0 ? '' : '<a href="location.php?op=update_status&locationes_loc_id=' . $_REQUEST['addresses_loc_id'] . '"><img src="./../images/icon/renew_mini.gif" border="0" alt="' . _XADDRESSES_AM_FORMVAloc_id . '" title="' . _XADDRESSES_AM_FORMVAloc_id . '"></a> ');
    echo '<a href="location.php?op=edit_addresses&downloads_loc_id=' . $_REQUEST['addresses_loc_id'] . '"><img src="../images/icon/edit_mini.gif" alt="' . _XADDRESSES_AM_FORMEDIT .
    '" title="' . _XADDRESSES_AM_FORMEDIT . '"></a> <a href="location.php?op=del_addresses&addresses_loc_id=' . $_REQUEST['addresses_loc_id'] . '">
    <img src="../images/icon/delete_mini.gif" alt="' . _XADDRESSES_AM_FORMDEL . '" title="' . _XADDRESSES_AM_FORMDEL . '"></a></td>';
	echo '</tr>';
	echo '</table>';
	echo '<br />';
    // Affichage des votes:

    // Utilisateur enregistré
    echo '<hr>';
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('loc_id', $_REQUEST['addresses_loc_id']));
    $criteria->add(new Criteria('ratinguser', 0, '!='));
    $addressesvotedata_arr = $votedataHandler->getall($criteria);
    $total_vote = $votedataHandler->getCount($criteria);
    echo '<table width="100%">';
    echo '<tr><td colspan="5"><b>';
    printf(_XADDRESSES_AM_ADDRESSES_VOTESUSER, $total_vote);
    echo '</b><br /><br /></td></tr>';
    echo '<tr><td><b>' . _XADDRESSES_AM_ADDRESSES_VOTE_USER . '</b></td>' . '<td><b>' . _XADDRESSES_AM_ADDRESSES_VOTE_IP . '</b></td>' . '<td align="center"><b>' . _XADDRESSES_AM_FORMRATING . '</b></td>'
    . '<td><b>' . _XADDRESSES_AM_FORMDATE . '</b></td>' . '<td align="center"><b>' . _XADDRESSES_AM_FORMDEL . '</b></td></tr>';
    foreach (array_keys($addressesvotedata_arr) as $i) {
        echo '<tr>';
        echo '<td>' . XoopsUser::getUnameFromId($addressesvotedata_arr[$i]->getVar('ratinguser')) . '</td>';
        echo '<td>' . $addressesvotedata_arr[$i]->getVar('ratinghostname') . '</td>';
        echo '<td align="center">' . $addressesvotedata_arr[$i]->getVar('rating') . '</td>';
        echo '<td>' . formatTimestamp($addressesvotedata_arr[$i]->getVar('ratingtimestamp')) . '</td>';
        echo '<td align="center">';
        echo myTextForm('location.php?op=del_vote&loc_id=' . $addressesvotedata_arr[$i]->getVar('loc_id') . '&rid=' . $addressesvotedata_arr[$i]->getVar('ratingid') , 'X');
        echo '</td>';
        echo '</tr>';
    }
    // Utilisateur anonyme
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('loc_id', $_REQUEST['addresses_loc_id']));
    $criteria->add(new Criteria('ratinguser', 0));
    $addressesvotedata_arr = $votedataHandler->getall($criteria);
    $total_vote = $votedataHandler->getCount($criteria);
    echo '<tr><td colspan="5"><br /><b>';
    printf(_XADDRESSES_AM_ADDRESSES_VOTESANONYME, $total_vote);
    echo '</b><br /><br /></td></tr>';
    echo '<tr><td colspan="2"><b>' . _XADDRESSES_AM_ADDRESSES_VOTE_IP . '</b></td>' . '<td align="center"><b>' . _XADDRESSES_AM_FORMRATING . '</b></td>'
    . '<td><b>' . _XADDRESSES_AM_FORMDATE . '</b></td>' . '<td align="center"><b>' . _XADDRESSES_AM_FORMDEL . '</b></td></tr>';
    foreach (array_keys($addressesvotedata_arr) as $i) {
        echo '<tr>';
        echo '<td colspan="2">' . $addressesvotedata_arr[$i]->getVar('ratinghostname') . '</td>';
        echo '<td align="center">' . $addressesvotedata_arr[$i]->getVar('rating') . '</td>';
        echo '<td>' . formatTimestamp($addressesvotedata_arr[$i]->getVar('ratingtimestamp')) . '</td>';
        echo '<td align="center">';
        echo myTextForm('location.php?op=del_vote&loc_id=' . $addressesvotedata_arr[$i]->getVar('loc_id') . '&rid=' . $addressesvotedata_arr[$i]->getVar('ratingid') , 'X');
        echo '</tr>';
    }
    echo'</table>';
break;
*/



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



case 'list_locations_broken':
    // redirection if no broken locations
    if ($brokenHandler->getCount() == 0) {
        redirect_header($currentFile . '?op=list_locations', 2, _XADDRESSES_AM_REDIRECT_NOBROKENLOC);
    }
    echo "IN_PROGRESS";
    echo "IN_PROGRESS";
    echo "IN_PROGRESS";
    echo "IN_PROGRESS";
    echo "IN_PROGRESS";
    break;



case 'list_locations_modified':
    // redirection if no modified locations
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('loc_mod_id', 0, '!='));
    if ($locationHandler->getCount($criteria) == 0) {
        redirect_header($currentFile . '?op=list_locations', 2, _XADDRESSES_AM_REDIRECT_NOMODIFIEDLOC);
    }

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
    $criteria->add(new Criteria('loc_mod_id', 0, '!='));

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
    $sortbyform.= _XADDRESSES_AM_TRIPAR . "<select name=\"sort_by\" id=\"sort_by\" onchange=\"location='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/admin/location.php?status_display=$status_display&sort_order=$sort_order&sort_by='+this.options[this.selectedIndex].value\">";
    $sortbyform.= '<option value="loc_date"' . ($sort_by == 'loc_date' ? ' selected="selected"' : '') . '>' . _XADDRESSES_AM_FORMDATE . '</option>';
    $sortbyform.= '<option value="loc_title"' . ($sort_by == 'loc_title' ? ' selected="selected"' : '') . '>' . _XADDRESSES_AM_FORMTITLE . '</option>';
    $sortbyform.= '<option value="loc_cat_id"' . ($sort_by == 'loc_cat_id' ? ' selected="selected"' : '') . '>' . _XADDRESSES_AM_FORMCAT . '</option>';
    $sortbyform.= '</select> ';
    $sortbyform.= _XADDRESSES_AM_ORDER . "<select name=\"order_tri\" id=\"order_tri\" onchange=\"location='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/admin/location.php?status_display=$status_display&sort_by=$sort_by&sort_order='+this.options[this.selectedIndex].value\">";
    $sortbyform.= '<option value="DESC"' . ($sort_order == 'DESC' ? ' selected="selected"' : '') . '>DESC</option>';
    $sortbyform.= '<option value="ASC"' . ($sort_order == 'ASC' ? ' selected="selected"' : '') . '>ASC</option>';
    $sortbyform.= '</select> ';
    $sortbyform.= '</form>';
    $GLOBALS['xoopsTpl']->assign('sortbyform', $sortbyform);



    if ($numRows > 0) {
        //Affichage du tableau des téléchargements

        $modifiedLocations = $locationHandler->getObjects($criteria, true, false); // get an array of arrays
        unset($criteria);

        // Get ids of categories in which locations can be viewed/edited/submitted
        $groupPermHandler =& xoops_gethandler('groupperm');
        $viewableCategories = $groupPermHandler->getItemIds('in_category_view', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
        $editableCategories = $groupPermHandler->getItemIds('in_category_edit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
        $deletableCategories = $groupPermHandler->getItemIds('in_category_delete', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
        $submitableCategories = $groupPermHandler->getItemIds('in_category_submit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );

        foreach (array_keys($modifiedLocations) as $loc_id ) {
            $modifiedLocations[$loc_id]['canView'] = (in_array($modifiedLocations[$loc_id]['loc_cat_id'], $viewableCategories)); // IN PROGRESS
            $modifiedLocations[$loc_id]['canEdit'] = (in_array($modifiedLocations[$loc_id]['loc_cat_id'], $editableCategories)); // IN PROGRESS
            $modifiedLocations[$loc_id]['canDelete'] = (in_array($modifiedLocations[$loc_id]['loc_cat_id'], $deletableCategories)); // IN PROGRESS
            $submitter =& $memberHandler->getUser($modifiedLocations[$loc_id]['loc_submitter']);
            $modifiedLocations[$loc_id]['loc_submitter_name'] = $submitter->getVar('uname');
            $modifiedLocations[$loc_id]['loc_submitter_id'] = $submitter->getVar('uid');
            unset($submitter);
            $modifiedLocations[$loc_id]['loc_date'] = formatTimeStamp($modifiedLocations[$loc_id]['loc_date'], _DATESTRING);
            // get original location
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', $modifiedLocations[$i]['loc_mod_id']));
            $originalLocation = $locationHandler->get($criteria, true); // get an array of arrays
            $modifiedLocations[$i]['original_location'] = $originalLocation;

            unset($criteria);
            }

        $GLOBALS['xoopsTpl']->assign('modifiedlocations', $modifiedLocations);




        $GLOBALS['xoopsTpl']->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML() );
        $GLOBALS['xoopsTpl']->display("db:xaddresses_admin_locationmodifiedlist.html");
    } else {
        echo '<div class="errorMsg">' . _XADDRESSES_AM_ERROR_NOADDRESSES . '</div>';
    }
    break;


}



xoops_cp_footer();
?>