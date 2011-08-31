<?php
include_once 'header.php';
include_once XOOPS_ROOT_PATH.'/header.php';
$currentFile = basename(__FILE__);
$loc_id = (int)($_REQUEST['loc_id']);

$xoopsOption['template_main'] = 'xaddresses_locationview.html';

//load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory', 'xaddresses');
$fieldHandler =& xoops_getModuleHandler('field', 'xaddresses');
$memberHandler =& xoops_gethandler('member');
// IN PROGRESS
$brokenHandler =& xoops_getModuleHandler('broken', 'xaddresses');
// TO DO
$votedataHandler =& xoops_getModuleHandler('votedata', 'xaddresses');



// redirect if id location not exist
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_id', $loc_id));
if ($locationHandler->getCount($criteria) == 0) {
    redirect_header('index.php', 3, _XADDRESSES_MD_SINGLELOC_NONEXISTENT);
    exit();
}

$categories = $categoryHandler->getObjects(null, true, false);
// location categories
$criteria = new CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$categoriesArray = $categoryHandler->getall($criteria);

// get location and category object
$location = $locationHandler->get($loc_id);
$category = $categoryHandler->get($location->getVar('loc_cat_id'));

echo '<h1>IN_PROGRESS</h1>';

echo $location->getVar('loc_title') . '<br />';
// location coordinates

$position = array(
    'lat'=>$location->getVar('loc_lat'),
    'lng'=>$location->getVar('loc_lng'),
    'zoom'=>$location->getVar('loc_zoom')
    );
echo $position['lat'] . '<br />' . $position['lng'] . '<br />' . $position['zoom'] . '<br />';

echo $category->getVar('cat_title') . '<br />';








// Get extra fields categories
$fieldsCategoriesArray = array();
$fieldsCategoriesArray[0]= array(
    'cat_id' => 0,
    'cat_title' => _XADDRESSES_AM_FIELD_CATEGORY_DEFAULT,
    'cat_description' => _XADDRESSES_AM_FIELD_CATEGORY_DEFAULT_DESC,
    'cat_weight' => 0);
$criteria = new CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');

$fieldsCategories = $fieldCategoryHandler->getall($criteria, null, false, true); //get fieldscategories as array
$fieldsCategoriesArray = array_merge($fieldsCategoriesArray, $fieldsCategories);

// Get all extra fields
$fields = $locationHandler->loadFields();

// populate $elements[cat_id][field_weight][] tri-dimensional array with {@link XaddressesField} objects
// $elements[cat_id][field_weight][]['element'] is a {@link XaddressesField} object
// $elements[cat_id][field_weight][]['required'] is bool (true: required, false: not required)
$elements = array();
foreach ($fields as $field) {
    // check if field is editable by user
    //if (in_array($field->getVar('field_id'), $editableFields)) {
    // Fill elements array indexed by cat_id and field_weight
    $element = array();
    $element['element'] = $field->getOutputValue($location);
    echo $field->getOutputValue($location);
    echo $field->getVar('field_type');
    $element['required'] = $field->getVar('field_required');
    $elements[$field->getVar('cat_id')][$field->getVar('field_weight')][] = $element;
    unset($categorySubElement);
    //}
}










/*
$categories = tdmdownloads_MygetItemIds();
if(!in_array($view_downloads->getVar('cid'), $categories)) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
    exit();
}
//navigation
$criteria = new CriteriaCompo();
$criteria->setSort('weight ASC, title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
$downloadscat_arr = $categoryHandler->getall($criteria);
$mytree = new XoopsObjectTree($downloadscat_arr, 'cid', 'pid');
$nav_parent_id = $mytree->getAllParent($view_downloads->getVar('cid'));
$titre_page = $nav_parent_id;
$nav_parent_id = array_reverse($nav_parent_id);
$navigation = '<a href="index.php">' . $xoopsModule->name() . '</a>&nbsp;:&nbsp;';
foreach (array_keys($nav_parent_id) as $i) {
    $navigation .= '<a href="viewcat.php?cid=' . $nav_parent_id[$i]->getVar('cid') . '">' . $nav_parent_id[$i]->getVar('title') . '</a>&nbsp;:&nbsp;';
}
$navigation .= '<a href="viewcat.php?cid=' . $view_categorie->getVar('cid') . '">' . $view_categorie->getVar('title') . '</a>&nbsp;:&nbsp;';
$navigation .= $view_downloads->getVar('title');
$xoopsTpl->assign('navigation', $navigation);
// sortie des informations
//Utilisation d'une copie d'écran avec la largeur selon les préférences
if ($xoopsModuleConfig['useshots'] == 1) {
    $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
    $xoopsTpl->assign('show_screenshot', true);
}
// sortie des informations
$logourl = $view_downloads->getVar('logourl');
$logourl = $uploadurl_shots . $logourl;
// Défini si la personne est un admin
if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $adminlink = '<a href="' . XOOPS_URL . '/modules/TDMDownloads/admin/addresses.php?op=view_downloads&amp;downloads_loc_id=' . $_REQUEST['loc_id'] . '" title="' . _MD_XADDRESSES_EDITTHISDL . '"><img src="' . XOOPS_URL . '/modules/TDMDownloads/images/editicon.png" width="16px" height="16px" border="0" alt="' . _MD_XADDRESSES_EDITTHISDL . '" /></a>';
} else {
    $adminlink = '';
}
$description = $view_downloads->getVar('description');
// pour référentiel de module
$description = str_replace('[block]','<h2><u>' . _MD_XADDRESSES_SUP_BLOCS . '</u></h2>',$description);
$description = str_replace('[notes]','<h2><u>' . _MD_XADDRESSES_SUP_NOTES . '</u></h2>',$description);
$description = str_replace('[evolutions]','<h2><u>' . _MD_XADDRESSES_SUP_EVOLUTIONS . '</u></h2>',$description);
$description = str_replace('[infos]','<h2><u>' . _MD_XADDRESSES_SUP_INFOS . '</u></h2>',$description);
$description = str_replace('[changelog]','<h2><u>' . _MD_XADDRESSES_SUP_CHANGELOG . '</u></h2>',$description);
$description = str_replace('[backoffice]','<h2><u>' . _MD_XADDRESSES_SUP_BACKOFFICE . '</u></h2>',$description);
$description = str_replace('[frontoffice]','<h2><u>' . _MD_XADDRESSES_SUP_FRONTOFFICE . '</u></h2>',$description);
$xoopsTpl->assign('description' , str_replace('[pagebreak]','',$description));

$xoopsTpl->assign('loc_id' , $_REQUEST['loc_id']);
$xoopsTpl->assign('cid' , $view_downloads->getVar('cid'));
$xoopsTpl->assign('logourl' , $logourl);
// pour les vignettes "new" et "mis à jour"
$new = nouveau_image($view_downloads->getVar('date'), $view_downloads->getVar('status'));
$pop = populaire_image($view_downloads->getVar('hits'));
$xoopsTpl->assign('title' , $view_downloads->getVar('title') . $new . $pop);
$xoopsTpl->assign('adminlink' , $adminlink);
$xoopsTpl->assign('date' , formatTimestamp($view_downloads->getVar('date'),'s'));
$xoopsTpl->assign('author' , XoopsUser::getUnameFromId($view_downloads->getVar('submitter')));
$xoopsTpl->assign('hits', sprintf(_MD_XADDRESSES_SINGLEFILE_NBTELECH,$view_downloads->getVar('hits')));
$xoopsTpl->assign('rating', number_format($view_downloads->getVar('rating'),1));
$xoopsTpl->assign('votes', sprintf(_MD_XADDRESSES_SINGLEFILE_VOTES,$view_downloads->getVar('votes')));
$xoopsTpl->assign('nb_comments', sprintf(_MD_XADDRESSES_SINGLEFILE_COMMENTS,$view_downloads->getVar('comments')));

// pour les champs supplémentaires
$criteria = new CriteriaCompo();
$criteria->setSort('weight ASC, title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('status', 1));
$downloads_field = $downloadsfield_Handler->getall($criteria);
$nb_champ = $downloadsfield_Handler->getCount($criteria);
foreach (array_keys($downloads_field) as $i) {
    if ($downloads_field[$i]->getVar('status_def') == 1) {
        if ($downloads_field[$i]->getVar('fid') == 1) {
            //page d'accueil
            $champ_sup = '&nbsp;' . _XADDRESSES_AM_FORMHOMEPAGE . ':&nbsp;<a href="' . $view_downloads->getVar('homepage') . '">' . _MD_XADDRESSES_SINGLEFILE_ICI . '</a>';
        }
        if ($downloads_field[$i]->getVar('fid') == 2) {
            //version
            $champ_sup = '&nbsp;' . _XADDRESSES_AM_FORMVERSION . ':&nbsp;' . $view_downloads->getVar('version');
        }
        if ($downloads_field[$i]->getVar('fid') == 3) {
            //taille du fichier
            $champ_sup = '&nbsp;' . _XADDRESSES_AM_FORMSIZE . ':&nbsp;' . trans_size($view_downloads->getVar('size'));
        }
        if ($downloads_field[$i]->getVar('fid') == 4) {
            //plateforme
            $champ_sup = '&nbsp;' . _XADDRESSES_AM_FORMPLATFORM . ':&nbsp;' . $view_downloads->getVar('platform');
        }
    } else {
        $view_data = $downloadsfielddata_Handler->get();
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('loc_id', $_REQUEST['loc_id']));
        $criteria->add(new Criteria('fid', $downloads_field[$i]->getVar('fid')));
        $downloadsfielddata = $downloadsfielddata_Handler->getall($criteria);
        $contenu = '';
        foreach (array_keys($downloadsfielddata) as $j) {
            $contenu = $downloadsfielddata[$j]->getVar('data');
        }
        $champ_sup = '&nbsp;' . $downloads_field[$i]->getVar('title') . ':&nbsp;' . $contenu;
    }
$xoopsTpl->append('champ_sup', array('image' => $uploadurl_field . $downloads_field[$i]->getVar('img'), 'data' => $champ_sup));
}
if ($nb_champ > 0) {
    $xoopsTpl->assign('sup_aff', true);
}else{
    $xoopsTpl->assign('sup_aff', false);
}
//permission
$xoopsTpl->assign('perm_vote', $perm_vote);
$xoopsTpl->assign('perm_modif', $perm_modif);

// pour utiliser tellafriend.
if (($xoopsModuleConfig['usetellafriend'] == 1) and (is_dir('../tellafriend'))) {
    $string = sprintf(_MD_XADDRESSES_SINGLEFILE_INTFILEFOUND,$xoopsConfig['sitename'].':  '.XOOPS_URL.'/modules/TDMDownloads/singlefile.php?loc_id=' . $_REQUEST['loc_id']);
    $subject = sprintf(_MD_XADDRESSES_SINGLEFILE_INTFILEFOUND,$xoopsConfig['sitename']);
    if( stristr( $subject , '%' ) ) $subject = rawurldecode( $subject ) ;
    if( stristr( $string , '%3F' ) ) $string = rawurldecode( $string ) ;
    if( preg_match( '/('.preg_quote(XOOPS_URL,'/').'.*)$/i' , $string , $matches ) ) {
        $target_uri = str_replace( '&amp;' , '&' , $matches[1] ) ;
    } else {
        $target_uri = XOOPS_URL . $_SERVER['REQUEST_URI'] ;
    }
    $tellafriend_texte = '<a target="_top" href="' . XOOPS_URL . '/modules/tellafriend/index.php?target_uri=' . rawurlencode( $target_uri ) . '&amp;subject='.rawurlencode( $subject ) . '">' . _MD_XADDRESSES_SINGLEFILE_TELLAFRIEND . '</a>';
} else {
    $tellafriend_texte = '<a target="_top" href="mailto:?subject=' . rawurlencode(sprintf(_MD_XADDRESSES_SINGLEFILE_INTFILEFOUND,$xoopsConfig['sitename'])) . '&amp;body=' . rawurlencode(sprintf(_MD_XADDRESSES_SINGLEFILE_INTFILEFOUND,$xoopsConfig['sitename']).':  ' . XOOPS_URL.'/modules/TDMDownloads/singlefile.php?loc_id=' . $_REQUEST['loc_id']) . '">' . _MD_XADDRESSES_SINGLEFILE_TELLAFRIEND . '</a>';
}
$xoopsTpl->assign('tellafriend_texte', $tellafriend_texte);

// référencement
// tags
if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../tag'))) {
    require_once XOOPS_ROOT_PATH.'/modules/tag/include/tagbar.php';
    $xoopsTpl->assign('tags', true);
    $xoopsTpl->assign('tagbar', tagBar($_REQUEST['loc_id'], 0));
} else {
    $xoopsTpl->assign('tags', false);
}

// titre de la page
$titre = $view_downloads->getVar('title') . '&nbsp;-&nbsp;' . $view_categorie->getVar('title') . '&nbsp;-&nbsp;';
foreach (array_keys($titre_page) as $i) {
    $titre .= $titre_page[$i]->getVar('title') . '&nbsp;-&nbsp;';
}
$titre .= $xoopsModule->name();
$xoopsTpl->assign('xoops_pagetitle', $titre);
//description
if (strpos($description,'[pagebreak]')==false) {
    $description_short = substr($description,0,400);
} else {
    $description_short = substr($description,0,strpos($description,'[pagebreak]'));
}
$xoTheme->addMeta( 'meta', 'description', strip_tags($description_short));
//keywords
$keywords = substr($keywords,0,-1);
$xoTheme->addMeta( 'meta', 'keywords', $keywords);
//include XOOPS_ROOT_PATH.'/include/comment_view.php';
*/
include XOOPS_ROOT_PATH.'/footer.php';
?>
