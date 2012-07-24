<?php
$currentFile = basename(__FILE__);
// include module header
include_once 'header.php';

// load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
//$locations_votedata_handler =& xoops_getModuleHandler('votedata', 'xaddresses');
//$locations_field_handler =& xoops_getModuleHandler('field', 'xaddresses');
//$locations_fielddata_handler =& xoops_getModuleHandler('fielddata', 'xaddresses');
//$locations_broken_handler =& xoops_getModuleHandler('broken', 'xaddresses');
//load classes
//$memberHandler =& xoops_gethandler('member');
// IN PROGRESS
//$brokenHandler =& xoops_getModuleHandler('broken', 'xaddresses');
// TO DO
//$votedataHandler =& xoops_getModuleHandler('votedata', 'xaddresses');
/*$criteria = new CriteriaCompo();
$criteria->add(new Criteria('cat_pid', 0));
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$mainCategories = $categoryHandler->getAll($criteria);

$viewableCategoriesIds = xaddresses_getMyItemIds();
//print_r($myCategories);
//affichage des catégories:
$criteria = new CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('cat_id', '(' . implode(',', $viewableCategoriesIds) . ')','IN'));
$myCategories = $categoryHandler->getall($criteria);
//print_r($myCategories_array);
$myTree = new XoopsObjectTree($myCategories, 'cat_id', 'cat_pid');

$prefix = '';
$sufix = '';

// get all categories sorted by tree structure
$categoriesList = array();
foreach ($mainCategories as $mainCategory) {
    $categoriesList[] = array('prefix' => $prefix, 'sufix' => $sufix, 'category' => $mainCategory);
    $mySubCategories = $myTree->getFirstChild($mainCategory->getVar('cat_id'));
    //print_r($mySubCategories_array);
    foreach ($mySubCategories as $mySubCategory)
        $categoriesList[] = array('prefix' => '-&gt;', 'sufix' => $sufix, 'category' => $mySubCategory);
}
*/

$viewableCategoriesIds = xaddresses_getMyItemIds('in_category_view');

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('cat_pid', 0));
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('cat_id', '(' . implode(',', $viewableCategoriesIds) . ')','IN'));
$mainCategories = $categoryHandler->getAll($criteria);
$mainCategoriesAsArray = $categoryHandler->getAll($criteria, null, false); // get categories as array

$prefix = '';
$sufix = '';
$order = 'cat_weight ASC, cat_title';

// get all categories sorted by tree structure
$categoriesAsArrayList = array();
foreach ($mainCategoriesAsArray as $mainCategoryAsArray) {
    $categoriesAsArrayList[] = array('prefix' => $prefix, 'sufix' => $sufix, 'category' => $mainCategoryAsArray);
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('cat_pid', $mainCategoryAsArray['cat_id']));
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $criteria->add(new Criteria('cat_id', '(' . implode(',', $viewableCategoriesIds) . ')','IN'));
    $subCategories = $categoryHandler->getAll($criteria);
    $subCategoriesAsArray = $categoryHandler->getAll($criteria, null, false); // get subcategories as array
    if (count($subCategories) != 0) {
        $subcategoriesList = xaddresses_getChildrenTree($mainCategoryAsArray['cat_id'], $subCategories, $prefix, $sufix, $order);
        $subcategoriesAsArrayList = array();
        foreach($subcategoriesList as $subcategoryList) {
            $subcategoriesAsArrayList[] = array('prefix' => $subcategoryList['prefix'], 'sufix' => $subcategoryList['sufix'], 'category' => $subcategoryList['category']->toArray());
        }
        $categoriesAsArrayList = array_merge ($categoriesAsArrayList, $subcategoriesAsArrayList);
    }
}

// render start here
// Load template
$xoopsOption['template_main'] = 'xaddresses_index.html';
include_once XOOPS_ROOT_PATH . '/header.php';

// Breadcrumb
$breadcrumb = array();
if ($xoopsModuleConfig['show_home_in_breadcrumb']) {
    $crumb['title'] = _MA_XADDRESSES_BREADCRUMB_HOME;
    $crumb['url'] = 'index.php';
    $breadcrumb[] = $crumb;
}
// Set breadcrumb array for tamplate
$breadcrumb = array_reverse($breadcrumb);
$xoopsTpl->assign('breadcrumb', $breadcrumb);
unset($breadcrumb, $crumb);

$GLOBALS['xoopsTpl']->assign('categoriesList', $categoriesAsArrayList);



/*
// pour les permissions
//affichage des catégories:
$criteria = new CriteriaCompo();
$criteria->setSort('weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('loc_cat_id', '(' . implode(',', $viewableCategoriesIds) . ')','IN'));
$downloadscat_arr = $categoryHandler->getall($criteria);
$mytree = new XoopsObjectTree($downloadscat_arr, 'cat_id', 'cat_pid');

$criteria = new CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('cat_pid', 0));
$criteria->add(new Criteria('cat_id', '(' . implode(',', $viewableCategoriesIds) . ')','IN'));
$downloadscat_arr = $categoryHandler->getall($criteria);

$count = 1;
$keywords = '';
foreach (array_keys($downloadscat_arr) as $i) {
    $totaldownloads = nombreEntree($downloadscat_arr[$i]->getVar('loc_cat_id'), '1');
    $subcategories_arr = $mytree->getFirstChild($downloadscat_arr[$i]->getVar('loc_cat_id'));
    $chcount = 0;
    $subcategories = '';    
    //pour les mots clef
    $keywords .= $downloadscat_arr[$i]->getVar('title') . ',';
    foreach (array_keys($subcategories_arr) as $j) {
            if ($chcount>=$xoopsModuleConfig['nbsouscat']){				
                $subcategories .= '<li>[<a href='' . XOOPS_URL . '/modules/xaddresses/viewcat.php?cat_id=' . $downloadscat_arr[$i]->getVar('loc_cat_id') . ''>+</a>]</li>';
                break;
            }
            $subcategories .= '<li><a href='' . XOOPS_URL . '/modules/xaddresses/viewcat.php?loc_cat_id=' . $subcategories_arr[$j]->getVar('loc_cat_id') . ''>' . $subcategories_arr[$j]->getVar('title') . '</a></li>';
            $keywords .= $downloadscat_arr[$i]->getVar('title') . ',';
            $chcount++;
    }
    $xoopsTpl->append('viewableCategoriesId', array('image' => $uploadurl . $downloadscat_arr[$i]->getVar('imgurl'), 'id' => $downloadscat_arr[$i]->getVar('loc_cat_id'), 'title' => $downloadscat_arr[$i]->getVar('title'), 'description_main' => $downloadscat_arr[$i]->getVar('description_main'), 'subcategories' => $subcategories, 'totaldownloads' => $totaldownloads, 'count' => $count));
    $count++;
}
*/
// Get 
// Get more recents locations list
if($xoopsModuleConfig['index_list_recent'] == true) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('loc_status', 0, '!='));
    $criteria->add(new Criteria('loc_cat_id', '(' . implode(',', $viewableCategoriesIds) . ')','IN'));
    $criteria->setSort('loc_date');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['index_list_number']);
    $locations = $locationHandler->getall($criteria);
    foreach ($locations as $location) {
        $title = $location->getVar('loc_title');
        if (strlen($title) >= $xoopsModuleConfig['index_list_titlelenght']) {
            $title = substr($title, 0, ($xoopsModuleConfig['index_list_titlelenght'])) . '...';
        }
        $date = formatTimestamp($location->getVar('loc_date'), 's');
        $xoopsTpl->append('index_list_recent', array(
            'loc_id' => $location->getVar('loc_id'), 
            'loc_cat_id' => $location->getVar('loc_cat_id'), 
            'loc_date' => $date, 
            'loc_title' => $title
        ));
    }
    unset($locations);
}

// Get top rated locations list
if($xoopsModuleConfig['index_list_toprated'] == true) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('loc_status', 0, '!='));
    $criteria->add(new Criteria('loc_cat_id', '(' . implode(',', $viewableCategoriesIds) . ')','IN'));
    $criteria->setSort('loc_rating');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['index_list_number']);
    $locations = $locationHandler->getall($criteria);
    foreach ($locations as $location) {
        $title = $location->getVar('loc_title');
        if (strlen($title) >= $xoopsModuleConfig['index_list_titlelenght']) {
            $title = substr($title,0,($xoopsModuleConfig['index_list_titlelenght'])) . '...';
        }
        $rating = number_format($location->getVar('rating'), 1);
        $xoopsTpl->append('index_list_toprated', array(
            'loc_id' => $location->getVar('loc_id'), 
            'loc_cat_id' => $location->getVar('loc_cat_id'), 
            'loc_rating' => $rating, 
            'loc_title' => $title
        ));
    }
    unset($locations);
}

/*
//plus téléchargés
if($xoopsModuleConfig['blpop']==1){
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('loc_cat_id', '(' . implode(',', $viewableCategoriesIds) . ')','IN'));
    $criteria->setSort('hits');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['index_show_number']);
    $downloads_arr = $locationHandler->getall($criteria);
    foreach (array_keys($downloads_arr) as $i) {
        $title = $downloads_arr[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['index_list_titlelenght']) {
            $title = substr($title,0,($xoopsModuleConfig['index_list_titlelenght'])) . '...';
        }
        $xoopsTpl->append('bl_pop', array(
            'id' => $downloads_arr[$i]->getVar('loc_id'), 
            'loc_cat_id' => $downloads_arr[$i]->getVar('loc_cat_id'), 
            'hits' => $downloads_arr[$i]->getVar('hits'), 
            'title' => $title
        ));
    }
}

// affichage des téléchargements
//Utilisation d'une copie d'écran avec la largeur selon les préférences

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', 0, '!='));
$criteria->add(new Criteria('loc_cat_id', '(' . implode(',', $viewableCategoriesIds) . ')','IN'));
$numrows = $locationHandler->getCount($criteria);
$xoopsTpl->assign('lang_thereare', sprintf(_MD_XADDRESSES_INDEX_THEREARE,$numrows));
$criteria->setLimit($xoopsModuleConfig['newdownloads']);
$tblsort = array();
$tblsort[1]='date';
$tblsort[2]='date';
$tblsort[3]='hits';
$tblsort[4]='hits';
$tblsort[5]='rating';
$tblsort[6]='rating';
$tblsort[7]='title';
$tblsort[8]='title';
$tblorder = array();
$tblorder[1]='DESC';
$tblorder[2]='ASC';
$tblorder[3]='DESC';
$tblorder[4]='ASC';
$tblorder[5]='DESC';
$tblorder[6]='ASC';
$tblorder[7]='DESC';
$tblorder[8]='ASC';
$sort = isset($xoopsModuleConfig['toporder']) ? $xoopsModuleConfig['toporder'] : 1;
$order = isset($xoopsModuleConfig['toporder']) ? $xoopsModuleConfig['toporder'] : 1;
$criteria->setSort($tblsort[$sort]);
$criteria->setOrder($tblorder[$order]);
$downloads_arr = $locationHandler->getall($criteria);

foreach (array_keys($downloads_arr) as $i) {
    $dtitle = $downloads_arr[$i]->getVar('title');
    $url = $downloads_arr[$i]->getVar('url');
    $logourl = $downloads_arr[$i]->getVar('logourl');
    $logourl = $uploadurl_shots . $logourl;
    $datetime = formatTimestamp($downloads_arr[$i]->getVar('date'),'s');
    $loc_cat_id = $downloads_arr[$i]->getVar('loc_cat_id');
    $loc_id = $downloads_arr[$i]->getVar('loc_id');
    $submitter = XoopsUser::getUnameFromId($downloads_arr[$i]->getVar('submitter'));
    $description = $downloads_arr[$i]->getVar('description');
    // pour référentiel de module
    $description = str_replace('[block]','<h2><u>' . _MD_XADDRESSES_SUP_BLOCS . '</u></h2>',$description);
    $description = str_replace('[notes]','<h2><u>' . _MD_XADDRESSES_SUP_NOTES . '</u></h2>',$description);
    $description = str_replace('[evolutions]','<h2><u>' . _MD_XADDRESSES_SUP_EVOLUTIONS . '</u></h2>',$description);
    $description = str_replace('[infos]','<h2><u>' . _MD_XADDRESSES_SUP_INFOS . '</u></h2>',$description);
    $description = str_replace('[changelog]','<h2><u>' . _MD_XADDRESSES_SUP_CHANGELOG . '</u></h2>',$description);
    $description = str_replace('[backoffice]','<h2><u>' . _MD_XADDRESSES_SUP_BACKOFFICE . '</u></h2>',$description);
    $description = str_replace('[frontoffice]','<h2><u>' . _MD_XADDRESSES_SUP_FRONTOFFICE . '</u></h2>',$description);
    //permet d'afficher uniquement la description courte
    if (strpos($description,'[pagebreak]')==false){	
        $description_short = $description;
    } else {
        $description_short = substr($description,0,strpos($description,'[pagebreak]'));
    }
    // pour les vignettes 'new' et 'mis à jour'
    $new = nouveau_image($downloads_arr[$i]->getVar('date'), $downloads_arr[$i]->getVar('status'));
    $pop = populaire_image($downloads_arr[$i]->getVar('hits'));
    
    // Défini si la personne est un admin
    if (is_object($GLOBALS['xoopsUser']) && $GLOBALS['xoopsUser']->isAdmin($xoopsModule->mid())) {
        $adminlink = '<a href='' . XOOPS_URL . '/modules/TDMDownloads/admin/addresses.php?op=view_downloads&amp;downloads_loc_id=' . $loc_id . '' title='' . _MD_XADDRESSES_EDITTHISDL . ''><img src='' . XOOPS_URL . '/modules/TDMDownloads/images/editicon.png' width='16px' height='16px' border='0' alt='' . _MD_XADDRESSES_EDITTHISDL . '' /></a>';
    } else {
        $adminlink = '';
    }    
    $xoopsTpl->append('file', array('id' => $loc_id,'loc_cat_id'=>$loc_cat_id, 'title' => $dtitle.$new.$pop,'logourl' => $logourl,'updated' => $datetime,'description_short' => $description_short,
                                    'adminlink' => $adminlink, 'submitter' => $submitter));
    //pour les mots clef
    $keywords .= $dtitle . ',';
}
// référencement
//description
$xoTheme->addMeta('meta', 'description', strip_tags($xoopsModule->name()));
//keywords
$keywords = substr($keywords,0,-1);
$xoTheme->addMeta('meta', 'keywords', $keywords);
*/
include XOOPS_ROOT_PATH . '/footer.php';
?>