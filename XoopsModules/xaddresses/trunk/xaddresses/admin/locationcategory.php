<?php
include 'admin_header.php';
$currentFile = basename(__FILE__);

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : (isset($_REQUEST['cat_id']) ? "edit_locationcategory" : 'list_locationcategories');

//load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
//$locations_votedata_handler =& xoops_getModuleHandler('votedata', 'xaddresses');
//$locations_field_handler =& xoops_getModuleHandler('field', 'xaddresses');
//$locations_fielddata_handler =& xoops_getModuleHandler('fielddata', 'xaddresses');
//$locations_broken_handler =& xoops_getModuleHandler('broken', 'xaddresses');



switch ($op) {
default:
case 'list_locationcategories':
    // render start here
    xoops_cp_header();
    // render main admin menu
    include (XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/admin/menu.php');
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
        if ($xoopsUser->isAdmin($GLOBALS['xoopsModule']->mid())) {
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
    include (XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/admin/menu.php');
    echo moduleAdminTabMenu($adminmenu, $currentFile);
    // Submenu
    $status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
    $submenuItem[] = ($op == 'new_locationcategory' ? _XADDRESSES_AM_CAT_NEW : '<a href="' . $currentFile . '?op=new_locationcategory">' . _XADDRESSES_AM_CAT_NEW . '</a>');
    $submenuItem[] = ($op == 'list_locationcategories' ? _XADDRESSES_AM_CAT_LIST : '<a href="' . $currentFile . '?op=list_locationcategories">' . _XADDRESSES_AM_CAT_LIST . '</a>');
    xaddressesAdminSubmenu ($submenuItem);

    $obj =& $categoryHandler->create();
    $form = $obj->getForm($currentFile);
    $form->display();

    xoops_cp_footer();
    break;



// edit category form
case "edit_locationcategory":
    xoops_cp_header();
    // main admin menu
    include (XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/admin/menu.php');
    echo moduleAdminTabMenu($adminmenu, $currentFile);
    // Submenu
    $status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
    $submenuItem[] = ($op == 'new_locationcategory' ? _XADDRESSES_AM_CAT_NEW : '<a href="' . $currentFile . '?op=new_locationcategory">' . _XADDRESSES_AM_CAT_NEW . '</a>');
    $submenuItem[] = ($op == 'list_locationcategories' ? _XADDRESSES_AM_CAT_LIST : '<a href="' . $currentFile . '?op=list_locationcategories">' . _XADDRESSES_AM_CAT_LIST . '</a>');
    xaddressesAdminSubmenu ($submenuItem);

    $obj = $categoryHandler->get($_REQUEST['cat_id']);
    $form = $obj->getForm($currentFile);
    $form->display();

    xoops_cp_footer();
    break;



// delete category
case 'delete_locationcategory':
// TO DO
// TO DO
// TO DO
// TO DO
// TO DO
    global $xoopsModule;
    $obj =& $categoryHandler->get($_REQUEST['cat_id']);
    if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // supression des téléchargements de la catégorie
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('cat_id', $_REQUEST['cat_id']));
        $locations = $locationHandler->getall($criteria);
        foreach (array_keys($locations) as $i) {
            // supression des votes
            $criteria_1 = new CriteriaCompo();
            $criteria_1->add(new Criteria('loc_id', $locations[$i]->getVar('loc_id')));
            $locations_votedata = $locations_votedata_handler->getall($criteria_1);
            foreach (array_keys($locations_votedata) as $j) {
                $obj_votedata =& $locations_votedata_handler->get($locations_votedata[$j]->getVar('ratingid'));
                $locations_votedata_handler->delete($obj_votedata) or $obj_votedata->getHtmlErrors();
            }
            // supression des rapports de fichier brisé
            $criteria_2 = new CriteriaCompo();
            $criteria_2->add(new Criteria('loc_id', $locations[$i]->getVar('loc_id')));
            $locations_broken = $locations_broken_handler->getall($criteria_2);
            foreach (array_keys($locations_broken) as $j) {
                $obj_broken =& $locations_broken_handler->get($locations_broken[$j]->getVar('reportid'));
                $locations_broken_handler->delete($obj_broken ) or $obj_broken ->getHtmlErrors();
            }
            // supression des data des champs sup.
            $criteria_3 = new CriteriaCompo();
            $criteria_3->add(new Criteria('loc_id', $locations[$i]->getVar('loc_id')));
            $locations_fielddata = $locations_fielddata_handler->getall($criteria_3);
            if ($locations_fielddata_handler->getCount( $criteria_3 ) > 0){
                foreach (array_keys($locations_fielddata) as $j) {
                    $obj_fielddata =& $locations_fielddata_handler->get($locations_fielddata[$j]->getVar('iddata'));
                    $locations_fielddata_handler->delete($obj_fielddata) or $obj_fielddata->getHtmlErrors();
                }
            }
            // supression des commentaires
            if ($locations[$i]->getVar('comments') > 0){
                xoops_comment_delete($xoopsModule->getVar('mid'), $locations[$i]->getVar('loc_id'));
            }
            //supression des tags
            if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../../tag'))){
                $tag_handler = xoops_getmodulehandler('link', 'tag');
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('tag_locationid', $locations[$i]->getVar('loc_id')));
                $locations_tags = $tag_handler->getall( $criteria );
                if (count($locations_tags) > 0){
                    foreach (array_keys($locations_tags) as $j) {
                        $obj_tags =& $tag_handler->get($locations_tags[$j]->getVar('tl_id'));
                        $tag_handler->delete($obj_tags) or $obj_tags->getHtmlErrors();
                    }
                }
            }
            // supression du fichier
            // pour extraire le nom du fichier
            $urlfile = substr_replace($downloads_arr[$i]->getVar('url'),'',0,strlen($uploadurl_downloads));
            // chemin du fichier
            $urlfile = $uploaddir_downloads . $urlfile;
            if (is_file($urlfile)){		
                chmod($urlfile, 0777);
                unlink($urlfile);
            }
            // supression du téléchargment
            $obj_addresses =& $locationHandler->get($locations[$i]->getVar('loc_id'));
            $locationHandler->delete($obj_addresses) or $obj_addresses->getHtmlErrors();
        }
        // supression des sous catégories avec leurs téléchargements            
        $categories = $categoryHandler->getall();
        $mytree = new XoopsObjectTree($categories, 'cat_id', 'cat_pid');
        $subcategories = $mytree->getAllChild($_REQUEST['cat_id']);
        foreach (array_keys($subcategories) as $i) {
            // supression de la catégorie
            $obj_child =& $categoryHandler->get($subcategories[$i]->getVar('cat_id'));
            $categoryHandler->delete($obj_child) or $obj_child->getHtmlErrors();
            // supression des téléchargements associés
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('cat_id', $subcategories[$i]->getVar('cat_id')));
            $locations = $locationHandler->getall( $criteria );
            foreach (array_keys($downloads_arr) as $i) {
                // supression des votes
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('loc_id', $locations[$i]->getVar('loc_id')));
                $locations_votedata = $locations_votedata_handler->getall( $criteria );
                foreach (array_keys($downloads_votedata) as $j) {
                    $obj_votedata =& $locations_votedata_handler->get($locations_votedata[$j]->getVar('rating_id'));
                    $locations_votedata_handler->delete($obj_votedata) or $obj_votedata->getHtmlErrors();
                }
                // supression des rapports de fichier brisé
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('loc_id', $locations[$i]->getVar('loc_id')));
                $locations_broken = $locations_broken_handler->getall( $criteria );
                foreach (array_keys($locations_broken) as $j) {
                    $obj_broken =& $locations_broken_handler->get($locations_broken[$j]->getVar('report_id'));
                    $locations_broken_handler->delete($obj_broken ) or $obj_broken ->getHtmlErrors();
                }
                // supression des data des champs sup.
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('loc_id', $locations[$i]->getVar('loc_id')));
                $locations_fielddata = $locations_fielddata_handler->getall( $criteria );
                foreach (array_keys($locations_fielddata) as $j) {
                    $obj_fielddata =& $locations_fielddata_handler->get($locations_fielddata[$j]->getVar('iddata'));
                    $locations_fielddata_handler->delete($obj_fielddata) or $obj_fielddata->getHtmlErrors();
                }
                // supression des commentaires
                if ($locations[$i]->getVar('comments') > 0){
                    xoops_comment_delete($xoopsModule->getVar('mid'), $locations[$i]->getVar('loc_id'));
                }
                //supression des tags
                if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../../tag'))){
                    $tag_handler = xoops_getmodulehandler('link', 'tag');
                    $criteria = new CriteriaCompo();
                    $criteria->add(new Criteria('tag_locationid', $downloads_arr[$i]->getVar('loc_id')));
                    $locations_tags = $tag_handler->getall( $criteria );
                    if (count($locations_tags) > 0){
                        foreach (array_keys($locations_tags) as $j) {
                            $obj_tags =& $tag_handler->get($locations_tags[$j]->getVar('tl_id'));
                            $tag_handler->delete($obj_tags) or $obj_tags->getHtmlErrors();
                        }
                    }
                }
                // supression du téléchargment
                $obj_addresses =& $locationHandler->get($locations[$i]->getVar('loc_id'));
                $locationHandler->delete($obj_addresses) or $obj_addresses->getHtmlErrors();
            }
        }
        if ($categoryHandler->delete($obj)) {
            redirect_header($currentFile, 1, _XADDRESSES_AM_REDIRECT_DELOK);
        } else {
            echo $obj->getHtmlErrors();
        }
    } else {
        $message = '';
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('cat_id', $_REQUEST['cat_id']));
        $locations = $locationHandler->getall( $criteria );
        if (count($locations) > 0) {
            $message .= _XADDRESSES_AM_DELADDRESSES .'<br />';
            foreach (array_keys($locations) as $i) {
                $message .= '<span style="color : Red">' . $locations[$i]->getVar('loc_title') . '</span><br />';
            }
        }            
        $categories = $categoryHandler->getall();
        $mytree = new XoopsObjectTree($categories, 'cat_id', 'cat_pid');
        $subcategories =$mytree->getAllChild($_REQUEST['cat_id']);
        if (count($subcategories) > 0) {
            $message .= _XADDRESSES_AM_DEL_SUB_CATS . ' <br /><br />';
            foreach (array_keys($subcategories) as $i) {
                $message .= '<b><span style="color : Red">' . $subcategories[$i]->getVar('cat_title') . '</span></b><br />';
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('cat_id', $subcategories[$i]->getVar('cat_id')));
                $locations = $locationHandler->getall( $criteria );
                if (count($locations) > 0) {
                    $message .= _XADDRESSES_AM_DELADDRESSES .'<br />';
                    foreach (array_keys($locations) as $i) {
                        $message .= '<span style="color : Red">' . $locations[$i]->getVar('loc_title') . '</span><br />';
                    }
                }
            }
        } else {
            $message.='';
        }
        // render start here
        xoops_cp_header();
        xoops_confirm(array('ok' => 1, 'cat_id' => $_REQUEST['cat_id'], 'op' => 'delete_locationcategory'), $_SERVER['REQUEST_URI'], sprintf(_XADDRESSES_AM_FORM_SURE_DEL, $obj->getVar('cat_title')) . '<br /><br />' . $message);
        xoops_cp_footer();
    }
    break;



case 'view_locationcategory':
// TO DO
// TO DO
// TO DO
// TO DO
// TO DO
    $viewCategory = $categoryHandler->get($_REQUEST['cat_id']);
    $criteria = new CriteriaCompo();
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
    if (!$GLOBALS['xoopsSecurity']->check()) {
       redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    if (isset($_REQUEST['cat_id'])) {
       $obj =& $categoryHandler->get($_REQUEST['cat_id']);
    } else {
       $obj =& $categoryHandler->create();
    }

    $error = FALSE;
    $messageError = '';

    $obj->setVar('cat_imgurl', $_POST['cat_imgurl']);
    $obj->setVar('cat_pid', $_POST['cat_pid']);
    $obj->setVar('cat_title', $_POST['cat_title']);
    $obj->setVar('cat_description', $_POST['cat_description']);
    $obj->setVar('cat_weight', $_POST['cat_weight']);
    if (intval($_REQUEST['cat_weight'])==0 && $_REQUEST['cat_weight'] != '0') {
        $error = TRUE;
        $messageError = _XADDRESSES_AM_ERROR_WEIGHT . '<br />';
    }
    if (isset($_REQUEST['cat_id'])) {
        if ($_REQUEST['cat_id'] == $_REQUEST['cat_pid']) {
            $error = TRUE;
            $messageError .= _XADDRESSES_AM_ERROR_CAT;
        }
    }
    if ($error == TRUE) {
        echo '<div class="errorMsg" style="text-align: left;">' . $messageError . '</div>';        
    } else {
        if ($categoryHandler->insert($obj)) {
            if (!isset($_REQUEST['cat_modified'])) {
                $tags = array();
                $tags['CATEGORY_NAME'] = $_POST['cat_title'];
                $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cat_id=' . $obj->getVar('cat_id');
                $notificationHandler =& xoops_gethandler('notification');
                $notificationHandler->triggerEvent('global', 0, 'new_category', $tags);
            }
            redirect_header($currentFile . '?op=list_categories', 1, _XADDRESSES_AM_REDIRECT_SAVE);
        }
        echo $obj->getHtmlErrors();
    }
    $form =& $obj->getForm();
    $form->display();
    break;
}




?>