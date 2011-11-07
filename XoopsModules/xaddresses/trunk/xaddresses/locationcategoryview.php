<?php
$currentFile = basename(__FILE__);

// include module header
include_once 'header.php';

// load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');



$cat_id = (int)($_REQUEST['cat_id']);
// Redirect if id category not exist
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('cat_id', $cat_id));
if ($categoryHandler->getCount($criteria) == 0) {
    redirect_header('index.php', 3, _XADDRESSES_MD_SINGLECAT_NONEXISTENT);
    exit();
}



$xoopsOption['template_main'] = 'xaddresses_locationcategoryview.html';
include_once XOOPS_ROOT_PATH . '/header.php';




// Get category object
$category = $categoryHandler->get($cat_id);
// Set category object for tamplate
$xoopsTpl->assign('category', $category);


// Breadcrumb
$breadcrumb = array();
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


//echo $category->getVar('cat_title');
// get locations in this category
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_cat_id', $cat_id));
$criteria->add(new Criteria('loc_suggested', false));
$criteria->add(new Criteria('loc_status', 0, '!=')); // show only active locations
$criteria->setSort('loc_date ASC, loc_title');
$criteria->setOrder('ASC');
$locationsArray = $locationHandler->getAll($criteria);
// Set locations object array for tamplate
$xoopsTpl->assign('locations', $locationsArray);

//echo '<h1>IN_PROGRESS</h1>';

// get all subcategories
$criteria = new CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('cat_pid', (int)$_REQUEST['cat_id']));
//$criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
$subcategoriesArray = $categoryHandler->getall($criteria);
// Set subcategory object array for tamplate
$xoopsTpl->assign('subcategories', $subcategoriesArray);











// Create Google map
require_once(XOOPS_ROOT_PATH . '/modules/xaddresses/kml/simpleGMapAPI/simpleGMapAPI.php');
$map = new simpleGMapAPI();

$map->printGMapsJS(); // add google maps scripts in header

$map->setWidth(600);
$map->setHeight(600);
$map->setBackgroundColor('#d0d0d0');
$map->setMapDraggable(true);
$map->setMapType($category->getVar('cat_map_type'));
$map->setDoubleclickZoom(false);
$map->setScrollwheelZoom(true);

$map->showDefaultUI(false);
$map->showMapTypeControl(true, 'DROPDOWN_MENU');
$map->showNavigationControl(true, 'DEFAULT');
$map->showScaleControl(true);
$map->showStreetViewControl(true);

$map->setInfoWindowBehaviour('SINGLE_CLOSE_ON_MAPCLICK');
$map->setInfoWindowTrigger('CLICK');

foreach($locationsArray as $location) {
    $html = '<a href="locationview.php?loc_id=' . $location->getVar('loc_id') . '">' . $location->getVar('loc_title') . '</a>';
    $map->addMarker($location->getVar('loc_lat'), $location->getVar('loc_lng'), $location->getVar('loc_title'), $html);
}

$htmlMap = $map->showMap(true, false); // autozoom

// Set Google map for tamplate
$xoopsTpl->assign('htmlMap', $htmlMap);



/*





//navigation
$navParentsId = $mytree->getAllParent($cat_id);
$titre_page = $navParentsId;
$nav_parent_id = array_reverse($nav_parent_id);
$navigation = '<a href='index.php'>' . $xoopsModule->name() . '</a>&nbsp;:&nbsp;';
foreach (array_keys($nav_parent_id) as $i) {
    $navigation .= '<a href='viewcategory.php?cat_id=' . $nav_parent_id[$i]->getVar('cat_id') . ''>' . $nav_parent_id[$i]->getVar('cat_title') . '</a>&nbsp;:&nbsp;';
}
$view_categorie = $categoryHandler->get(intval($_REQUEST['cid']));
$navigation .= $view_categorie->getVar('title');
$xoopsTpl->assign('category_path', $navigation);



//pour afficher les résumés
//téléchargements récents
if($xoopsModuleConfig['bldate']==1){
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
    $criteria->add(new Criteria('cid', intval($_REQUEST['cid'])));
    $criteria->setSort('date');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $downloads_arr = $locationHandler->getall($criteria);
    foreach (array_keys($downloads_arr) as $i) {
        $title = $downloads_arr[$i]->getVar('title');
		if (strlen($title) >= $xoopsModuleConfig['longbl']) {
				$title = substr($title,0,($xoopsModuleConfig['longbl'])).'...';
		}
        $date = formatTimestamp($downloads_arr[$i]->getVar('date'),'s');
        $xoopsTpl->append('bl_date', array('id' => $downloads_arr[$i]->getVar('loc_id'),'cid' => $downloads_arr[$i]->getVar('cid'),'date' => $date,'title' => $title));
    }
}
//plus téléchargés
if($xoopsModuleConfig['blpop']==1){
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
    $criteria->add(new Criteria('cid', intval($_REQUEST['cid'])));
    $criteria->setSort('hits');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $downloads_arr = $locationHandler->getall($criteria);
    foreach (array_keys($downloads_arr) as $i) {
        $title = $downloads_arr[$i]->getVar('title');
		if (strlen($title) >= $xoopsModuleConfig['longbl']) {
				$title = substr($title,0,($xoopsModuleConfig['longbl'])) . '...';
		}
        $xoopsTpl->append('bl_pop', array('id' => $downloads_arr[$i]->getVar('loc_id'),'cid' => $downloads_arr[$i]->getVar('cid'),'hits' => $downloads_arr[$i]->getVar('hits'),'title' => $title));
    }
}
//mieux notés
if($xoopsModuleConfig['blrating']==1){
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
    $criteria->add(new Criteria('cid', intval($_REQUEST['cid'])));
    $criteria->setSort('rating');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $downloads_arr = $locationHandler->getall($criteria);
    foreach (array_keys($downloads_arr) as $i) {
        $title = $downloads_arr[$i]->getVar('title');
		if (strlen($title) >= $xoopsModuleConfig['longbl']) {
				$title = substr($title,0,($xoopsModuleConfig['longbl'])).'...';
		}
        $rating = number_format($downloads_arr[$i]->getVar('rating'),1);
        $xoopsTpl->append('bl_rating', array('id' => $downloads_arr[$i]->getVar('loc_id'),'cid' => $downloads_arr[$i]->getVar('cid'),'rating' => $rating,'title' => $title));
    }
}
// affichage des téléchargements
//Utilisation d'une copie d'écran avec la largeur selon les préférences
if ($xoopsModuleConfig['useshots'] == 1) {
	$xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
	$xoopsTpl->assign('show_screenshot', true);
}
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', 0, '!='));
$criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
$criteria->add(new Criteria('cid', intval($_REQUEST['cid'])));
$numrows = $locationHandler->getCount($criteria);
$xoopsTpl->assign('lang_thereare', sprintf(_MD_XADDRESSES_CAT_THEREARE,$numrows));

// Pour un affichage sur plusieurs pages
if (isset($_REQUEST['limit'])) {
    $criteria->setLimit($_REQUEST['limit']);
    $limit = $_REQUEST['limit'];
} else {
    $criteria->setLimit($xoopsModuleConfig['perpage']);
    $limit = $xoopsModuleConfig['perpage'];
}
if (isset($_REQUEST['start'])) {
    $criteria->setStart($_REQUEST['start']);
	$start = $_REQUEST['start'];
} else {
	$criteria->setStart(0);
 	$start = 0;
}
if (isset($_REQUEST['sort'])) {
    $criteria->setSort($_REQUEST['sort']);
    $sort = $_REQUEST['sort'];
}else{
    $criteria->setSort('date');
    $sort = 'date';
}
if (isset($_REQUEST['order'])) {
    $criteria->setOrder($_REQUEST['order']);
    $order = $_REQUEST['order'];
}else{
    $criteria->setOrder('DESC');
    $order = 'DESC';
}

$downloads_arr = $locationHandler->getall($criteria);
if ( $numrows > $limit ) {
	$pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', 'limit=' . $limit . '&cid=' . intval($_REQUEST['cid']) . '&sort=' . $sort . '&order=' . $order);
 	$pagenav = $pagenav->renderNav(4);
} else {
 	$pagenav = '';
}
$xoopsTpl->assign('pagenav', $pagenav);
$summary = '';
$cpt = 0;
$keywords = '';
foreach (array_keys($downloads_arr) as $i) {
	$dtitle = $downloads_arr[$i]->getVar('title');
	$url = $downloads_arr[$i]->getVar('url');
    $logourl = $downloads_arr[$i]->getVar('logourl');
    $logourl = $uploadurl_shots . $logourl;
	$datetime = formatTimestamp($downloads_arr[$i]->getVar('date'),'s');
    $cid = $downloads_arr[$i]->getVar('cid');
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
	}else{
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
    // utilisation du sommaire
    $cpt++;
    $summary = $cpt . '- <a href='#l' . $cpt . ''>' . $dtitle . '</a><br />'; 
    $xoopsTpl->append('summary', array('title' => $summary, 'count' => $cpt));
    
    $xoopsTpl->append('file', array('id' => $loc_id,'cid' => $cid, 'title' => $dtitle.$new.$pop,'logourl' => $logourl,'updated' => $datetime,'description_short' => $description_short,
                                    'adminlink' => $adminlink, 'submitter' => $submitter));
    //pour les mots clef
    $keywords .= $dtitle . ',';
}

// affichage du résumé
if ($xoopsModuleConfig['bldate']==0 and $xoopsModuleConfig['blpop']==0 and $xoopsModuleConfig['blrating']==0){
    $bl_affichage = 0;
}else{
    $bl_affichage = 1;  
}
if ($numrows == 0){
    $bl_affichage = 0;
}
$xoopsTpl->assign('bl_affichage', $bl_affichage);

// affichage du sommaire
if($xoopsModuleConfig['autosummary']) {
    if ($numrows == 0){
        $xoopsTpl->assign('aff_summary', false);
    }else{
        $xoopsTpl->assign('aff_summary', true);
    }
} else {
	$xoopsTpl->assign('aff_summary', false);
}

// affichage du menu de tri
if($numrows>1){
	$xoopsTpl->assign('navigation', true);
    $xoopsTpl->assign('category_id', intval($_REQUEST['cid']));
    $sortorder = $sort . $order;
    if ($sortorder == 'hitsASC') $affichage_tri = _MD_XADDRESSES_CAT_POPULARITYLTOM;
	if ($sortorder == 'hitsDESC') $affichage_tri = _MD_XADDRESSES_CAT_POPULARITYMTOL;
	if ($sortorder == 'titleASC') $affichage_tri = _MD_XADDRESSES_CAT_TITLEATOZ;
	if ($sortorder == 'titleDESC') $affichage_tri = _MD_XADDRESSES_CAT_TITLEZTOA;
	if ($sortorder == 'dateASC') $affichage_tri = _MD_XADDRESSES_CAT_DATEOLD;
	if ($sortorder == 'dateDESC') $affichage_tri = _MD_XADDRESSES_CAT_DATENEW;
	if ($sortorder == 'ratingASC') $affichage_tri = _MD_XADDRESSES_CAT_RATINGLTOH;
	if ($sortorder == 'ratingDESC') $affichage_tri = _MD_XADDRESSES_CAT_RATINGHTOL;
	$xoopsTpl->assign('affichage_tri', sprintf(_MD_XADDRESSES_CAT_CURSORTBY, $affichage_tri));
}

// référencement
// titre de la page
$title = $view_categorie->getVar('title') . '&nbsp;-&nbsp;';
foreach (array_keys($title_page) as $i) {
    $title .= $titre_page[$i]->getVar('title') . '&nbsp;-&nbsp;';
}
$title .= $xoopsModule->name();
$xoopsTpl->assign('xoops_pagetitle', $titre);
//description
$xoTheme->addMeta( 'meta', 'description', strip_tags($view_categorie->getVar('description_main')));
//keywords
$keywords = substr($keywords, 0, -1);
$xoTheme->addMeta( 'meta', 'keywords', $keywords);
*/
include XOOPS_ROOT_PATH . '/footer.php';
?>