<?php
include 'admin_header.php';
$currentFile = basename(__FILE__);

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : (isset($_REQUEST['cat_id']) ? "edit_locationcategory" : 'list_locationcategories');

// load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$fieldHandler =& xoops_getModuleHandler('field', 'xaddresses');
$brokenHandler =& xoops_getModuleHandler('broken', 'xaddresses');
$votedataHandler =& xoops_getModuleHandler('votedata', 'xaddresses');

$memberHandler =& xoops_gethandler('member');
$groupPermHandler =& xoops_gethandler('groupperm');


switch ($op) {
default:
case 'list_locationcategories':
    // render start here
    xoops_cp_header();
    // render main admin menu
    include (XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->dirname() . '/admin/menu.php');
    echo moduleAdminTabMenu($adminmenu, $currentFile);
    // Submenu
    $status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
    $submenuItem[] = ($op == 'new_locationcategory' ? _XADDRESSES_AM_CAT_NEW : '<a href="' . $currentFile . '?op=new_locationcategory">' . _XADDRESSES_AM_CAT_NEW . '</a>');
    $submenuItem[] = ($op == 'list_locationcategories' ? _XADDRESSES_AM_CAT_LIST : '' . _XADDRESSES_AM_CAT_LIST . '');
    xaddressesAdminSubmenu ($submenuItem);

    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('cat_pid', 0));
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $mainCategories = $categoryHandler->getAll($criteria);

    $prefix = '';
    $sufix = '';
    $order = 'cat_weight ASC, cat_title';

    // get all categories sorted by tree structure
    $categoriesList = array();
    foreach ($mainCategories as $mainCategory) {
        $categoriesList[] = array('prefix' => $prefix, 'sufix' => $sufix, 'category' => $mainCategory);
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('cat_pid', $mainCategory->getVar('cat_id')));
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $subCategories = $categoryHandler->getall($criteria);
        if (count($subCategories) != 0){
            $categoriesList = array_merge ($categoriesList, getChildrenTree($mainCategory->getVar('cat_id'), $subCategories, $prefix, $sufix, $order));
        }
    }

    // Get ids of categories in which locations can be viewed/edited/submitted
    $groupPermHandler =& xoops_gethandler('groupperm');
    $viewableCategories = $groupPermHandler->getItemIds('in_category_view', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
    $editableCategories = $groupPermHandler->getItemIds('in_category_edit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
    $submitableCategories = $groupPermHandler->getItemIds('in_category_submit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );

    foreach ($categoriesList as $key=>$categoriesListItem) {
        $category = $categoriesListItem['category'];
        $info = _XADDRESSES_AM_CAT_MAP_TYPE . ': ' . $category->getVar('cat_map_type');
        // count valid locations
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('loc_suggested', false));
        $criteria->add(new Criteria('loc_status', 0, '!='));
        $criteria->add(new Criteria('loc_cat_id', $category->getVar('cat_id')));
        $countLocations = $locationHandler->getCount($criteria);
        $info.= '<br />';
        $info.= _XADDRESSES_AM_LOCATIONS . ': ' . $countLocations;
        $categoriesList[$key]['info'] = $info;

        if ($GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid())) {
            // admin can do everything
            $categoriesList[$key]['canView'] = true;
            $categoriesList[$key]['canEdit'] = true;
            $categoriesList[$key]['canDelete'] = true;
        } else {
            $categoriesList[$key]['canView'] = (in_array($category->getVar('cat_id'), $viewableCategories)); // IN PROGRESS
            $categoriesList[$key]['canEdit'] = (in_array($category->getVar('cat_id'), $editableCategories)); // IN PROGRESS
            $categoriesList[$key]['canDelete'] = (in_array($category->getVar('cat_id'), $editableCategories)); // IN PROGRESS
        }
        unset($category);
        }


    $GLOBALS['xoopsTpl']->assign('categoriesList', $categoriesList);

    $GLOBALS['xoopsTpl']->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML() );
    $GLOBALS['xoopsTpl']->display("db:xaddresses_admin_locationcategorylist.html");

    xoops_cp_footer();
    break;



case 'reorder_locationcategories':
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors() ));
    }
    if (isset($_POST['category_ids']) && count($_POST['category_ids']) > 0) {
        $oldweight = $_POST['oldweight'];
        $weight = $_POST['weight'];
        $ids = array();
        foreach ($_POST['category_ids'] as $category_id ) {
            if ($oldweight[$category_id] != $weight[$category_id] ) {
                //if category has changed
                $ids[] = intval($category_id);
            }
        }
        if ( count($ids) > 0 ) {
            $errors = array();
            //if there are changed categories, fetch the category objects
            $categories = $categoryHandler->getObjects(new Criteria('cat_id', "(" . implode(',', $ids) . ")", "IN"), TRUE);
            foreach ($ids as $i) {
                $categories[$i]->setVar('cat_weight', intval($weight[$i]));
                if (!$categoryHandler->insert($categories[$i])) {
                    $errors = array_merge($errors, $categories[$i]->getErrors());
                }
            }
            if (count($errors) == 0) {
                //no errors
                redirect_header($currentFile, 2, sprintf(_XADDRESSES_AM_SAVEDSUCCESS, _XADDRESSES_AM_CATEGORIES));
            } else {
                redirect_header($currentFile, 3, implode('<br />', $errors));
            }
        }
    }
    break;



// new category form
case "new_locationcategory":
    xoops_cp_header();
    // main admin menu
    include (XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->dirname() . '/admin/menu.php');
    echo moduleAdminTabMenu($adminmenu, $currentFile);
    // Submenu
    $status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
    $submenuItem[] = ($op == 'new_locationcategory' ? _XADDRESSES_AM_CAT_NEW : '<a href="' . $currentFile . '?op=new_locationcategory">' . _XADDRESSES_AM_CAT_NEW . '</a>');
    $submenuItem[] = ($op == 'list_locationcategories' ? _XADDRESSES_AM_CAT_LIST : '<a href="' . $currentFile . '?op=list_locationcategories">' . _XADDRESSES_AM_CAT_LIST . '</a>');
    xaddressesAdminSubmenu ($submenuItem);

    $category =& $categoryHandler->create();
    $form = $category->getForm($currentFile);
    $form->display();

    xoops_cp_footer();
    break;



// edit category form
case "edit_locationcategory":
    xoops_cp_header();
    // main admin menu
    include (XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->dirname() . '/admin/menu.php');
    echo moduleAdminTabMenu($adminmenu, $currentFile);
    // Submenu
    $status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
    $submenuItem[] = ($op == 'new_locationcategory' ? _XADDRESSES_AM_CAT_NEW : '<a href="' . $currentFile . '?op=new_locationcategory">' . _XADDRESSES_AM_CAT_NEW . '</a>');
    $submenuItem[] = ($op == 'list_locationcategories' ? _XADDRESSES_AM_CAT_LIST : '<a href="' . $currentFile . '?op=list_locationcategories">' . _XADDRESSES_AM_CAT_LIST . '</a>');
    xaddressesAdminSubmenu ($submenuItem);

    $category = $categoryHandler->get($_REQUEST['cat_id']);
    $form = $category->getForm($currentFile);
    $form->display();

    xoops_cp_footer();
    break;



// delete category
case 'delete_locationcategory':
    $category =& $categoryHandler->get($_REQUEST['cat_id']);
    $categoriesToDelete = array();
    $categoriesToDelete[] = $category;
    // Get all subcategories
    $categories = $categoryHandler->getall();
    $mytree = new XoopsObjectTree($categories, 'cat_id', 'cat_pid');
    $subcategories =$mytree->getAllChild($_REQUEST['cat_id']);

    $categoriesToDelete = array_merge($categoriesToDelete, $subcategories);

    if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        foreach($categoriesToDelete as $categoryToDelete) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_cat_id', $categoryToDelete->getVar('cat_id')));
            $locationsToDelete = $locationHandler->getall($criteria);
            // Delete all locations in this category
            foreach($locationsToDelete as $locationToDelete) {
                $loc_id = $locationToDelete->getVar('loc_id');
                if ($locationHandler->delete($locationToDelete)) {
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
                    // Delete all modify suggestions
                    // TO DO
                    // TO DO
                    // TO DO
            /*
                        // supression des data des champs sup.
                        $criteria = new CriteriaCompo();
                        $criteria->add(new Criteria('loc_id', $addresses_loc_id));
                        $addresses_fielddata = $addressesfielddata_handler->getall( $criteria );
                        foreach (array_keys($addresses_fielddata) as $i) {
                            $category_fielddata =& $addressesfielddata_handler->get($addresses_fielddata[$i]->getVar('iddata'));
                            $addressesfielddata_handler->delete($category_fielddata) or $category_fielddata->getHtmlErrors();
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
                } else {
                    echo $locationToDelete->getHtmlErrors();
                }
            }
            // Delete the category
            if (!$categoryHandler->delete($categoryToDelete)) {
                echo $categoryToDelete->getHtmlErrors();
            }
        redirect_header($currentFile, 1, _XADDRESSES_AM_REDIRECT_DEL_OK);
        }
        
    } else {
        if (count($subcategories) > 0) {
            $warning = '<br />';
            $warning.= _XADDRESSES_AM_DEL_SUB_CATS;
            $warning.= '<br />';
            foreach (array_keys($subcategories) as $i) {
                $warning.= '<b><span style="color : Red">' . $subcategories[$i]->getVar('cat_title') . '</span></b>';
                $warning.= '<br />';
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('loc_suggested', false));
                $criteria->add(new Criteria('loc_cat_id', $subcategories[$i]->getVar('cat_id')));
                $locations = $locationHandler->getall( $criteria );
                if (count($locations) > 0) {
                    $warning .= _XADDRESSES_AM_DELADDRESSES .'<br />';
                    foreach (array_keys($locations) as $i) {
                        $warning .= '<span style="color : Red">' . $locations[$i]->getVar('loc_title') . '</span><br />';
                    }
                }
            }
        } else {
            $warning = '';
        }
        // render start here
        xoops_cp_header();
        xoops_confirm(array('ok' => 1, 'cat_id' => $_REQUEST['cat_id'], 'op' => 'delete_locationcategory'), $_SERVER['REQUEST_URI'], sprintf(_XADDRESSES_AM_FORM_SURE_DEL, $category->getVar('cat_title')) . $warning);
        xoops_cp_footer();
    }
    break;



case 'view_locationcategory':
// TO DO
// TO DO
// TO DO
// TO DO
// TO DO
// TO DO
    $viewCategory = $categoryHandler->get($_REQUEST['cat_id']);
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('loc_suggested', false));
    $criteria->add(new Criteria('loc_cat_id', $_REQUEST['cat_id']));
    $countLocations = $locationHandler->getCount($criteria);
    echo '<h1>' . $viewCategory->getVar('cat_title') . ' ' . 'IN_PROGRESS</h1>';
    echo _XADDRESSES_AM_FORMTEXT . ' ' . $viewCategory->getVar('cat_description');
    echo '<br />';
    echo _XADDRESSES_AM_FORMIMG . ' <img src="' . $viewCategory->getVar('cat_imgurl') . '" alt="" title="" height="60">';
    echo '<br />';
    echo _XADDRESSES_AM_FORMWEIGHT . ' ' . $viewCategory->getVar('cat_weight');
    echo '<br />';
    echo _XADDRESSES_AM_THEREIS . ' ' . $countLocations . ' ' . _XADDRESSES_AM_ADDRESSESINCAT;
    echo '<br />';
    echo _XADDRESSES_AM_FORMACTION;
    echo ' <br />';
    echo '<a href="' . $currentFile . '?op=edit_locationcategory&cat_id=' . $_REQUEST['cat_id'] . '">' . _EDIT . '</a>';
    echo '&nbsp;';
    echo '<a href="' . $currentFile . '?op=delete_locationcategory&cat_id=' . $_REQUEST['cat_id'] . '">' . _DELETE . '</a>';
    break;



case 'save_locationcategory':
    if ( !$GLOBALS['xoopsSecurity']->check()  ) {
        redirect_header($currentFile, 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors() ));
        exit;
    }

    if (isset($_REQUEST['cat_id'])) {
       $category =& $categoryHandler->get($_REQUEST['cat_id']);
    } else {
       $category =& $categoryHandler->create();
    }

    $errorFlag = false;
    $errorMessage = '';

    $category->setVar('cat_imgurl', $_POST['cat_imgurl']);
    $category->setVar('cat_pid', $_POST['cat_pid']);
    $category->setVar('cat_title', $_POST['cat_title']);
    $category->setVar('cat_description', $_POST['cat_description']);
    $category->setVar('cat_weight', (int)$_POST['cat_weight']);

    $category->setVar('cat_map_type', $_POST['cat_map_type']);
 
    // Check values
    if ((int)$_REQUEST['cat_weight']==0 && $_REQUEST['cat_weight'] != '0') {
        $errorFlag = true;
        $errorMessage = _XADDRESSES_AM_ERROR_WEIGHT . '<br />';
    }
    if (isset($_REQUEST['cat_id'])) {
        if ($_REQUEST['cat_id'] == $_REQUEST['cat_pid']) {
            $errorFlag = true;
            $errorMessage .= _XADDRESSES_AM_ERROR_CAT;
        }
    }
    if ($errorFlag == true) {
        echo '<div class="errorMsg" style="text-align: left;">' . $errorMessage . '</div>';        
    } else {
        if ($categoryHandler->insert($category)) {
            // Set permissions
            // Resets all permissions
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('gperm_itemid', $category->getVar('cat_id')));
            $criteria->add(new Criteria('gperm_modid', $GLOBALS['xoopsModule']->getVar('mid')));
            $groupPermHandler->deleteAll($criteria);
            // Set view permissions
            $groupsViewIds = $_POST['in_category_view'];
            foreach($groupsViewIds as $id => $value)
                $groupPermHandler->addRight('in_category_view', $category->getVar('cat_id'), $value, $GLOBALS['xoopsModule']->getVar('mid'));
            // Set submit permissions
            $groupsSubmitIds = $_POST['in_category_submit'];
            foreach($groupsSubmitIds as $id => $value)
                $groupPermHandler->addRight('in_category_submit', $category->getVar('cat_id'), $value, $GLOBALS['xoopsModule']->getVar('mid'));
            // Set edit permissions
            $groupsEditIds = $_POST['in_category_edit'];
            foreach($groupsEditIds as $id => $value)
                $groupPermHandler->addRight('in_category_edit', $category->getVar('cat_id'), $value, $GLOBALS['xoopsModule']->getVar('mid'));
            // Set delete permissions
            $groupsDeleteIds = $_POST['in_category_delete'];
            foreach($groupsDeleteIds as $id => $value)
                $groupPermHandler->addRight('in_category_delete', $category->getVar('cat_id'), $value, $GLOBALS['xoopsModule']->getVar('mid'));

            // Send notification
            if (!isset($_REQUEST['cat_modified'])) {
                $tags = array();
                $tags['CATEGORY_NAME'] = $_POST['cat_title'];
                $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/viewcat.php?cat_id=' . $category->getVar('cat_id');
                $notificationHandler =& xoops_gethandler('notification');
                $notificationHandler->triggerEvent('global', 0, 'new_category', $tags);
            }
            redirect_header($currentFile . '?op=list_categories', 1, sprintf(_XADDRESSES_AM_SAVEDSUCCESS, $category->getVar('cat_title')));
        }
        echo $category->getHtmlErrors();
    }
    $form =& $category->getForm();
    $form->display();
    break;
}



?>