<?php
$currentFile = basename(__FILE__);

// include module header
include_once 'header.php';

// Load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory', 'xaddresses');
$fieldHandler =& xoops_getModuleHandler('field', 'xaddresses');
$memberHandler =& xoops_gethandler('member');



$loc_id = (int)($_REQUEST['loc_id']);
// Redirect if id location not exist
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_id', $loc_id));
if ($locationHandler->getCount($criteria) == 0) {
    redirect_header('index.php', 3, _XADDRESSES_MD_SINGLELOC_NONEXISTENT);
    exit();
}



include_once XOOPS_ROOT_PATH . '/header.php';
$xoopsOption['template_main'] = 'xaddresses_locationview.html';



// Get location and category object
$location = $locationHandler->get($loc_id);
$category = $categoryHandler->get($location->getVar('loc_cat_id'));

// Breadcrumb
/*
$categories = $categoryHandler->getObjects(null, true, false);
$criteria = new CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$categoriesArray = $categoryHandler->getall($criteria);
$mytree = new XoopsObjectTree($categoriesArray, 'cat_id', 'cat_pid');
$nav_parent_id = $mytree->getAllParent($category->getVar('cat_id'));
$nav_parent_id = array_reverse($nav_parent_id);
$crumb['title'] = $xoopsModule->name();
$crumb['url'] = 'index.php';
$breadcrumb[] = $crumb;
foreach (array_keys($nav_parent_id) as $i) {
    $crumb['title'] = $nav_parent_id[$i]->getVar('cat_title');
    $crumb['url'] = 'locationcategoryview.php?cat_id=' . $nav_parent_id[$i]->getVar('cat_id');
    $breadcrumb[] = $crumb;
}
$crumb['title'] = $location->getVar('loc_title');
$crumb['url'] = $currentFile . '?loc_id=' . $location->getVar('loc_id');
$breadcrumb[] = $crumb;
// Set breadcrumb array for tamplate
$xoopsTpl->assign('breadcrumb', $breadcrumb);
unset($breadcrumb, $crumb);
*/
$breadcrumb = array();
$crumb['title'] = $location->getVar('loc_title');
$crumb['url'] = $currentFile . '?loc_id=' . $location->getVar('loc_id');
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
// Set breadcrumb array for tamplate
$breadcrumb = array_reverse($breadcrumb);
$xoopsTpl->assign('breadcrumb', $breadcrumb);
unset($breadcrumb, $crumb);


// Get location's standard fields
$locationAsArray = $location->toArray();
$submitter =& $memberHandler->getUser($locationAsArray['loc_submitter']);
$locationAsArray['loc_submitter_uname'] = $submitter->getVar('uname');
$locationAsArray['loc_submitter_linkeduname'] = xoops_getLinkedUnameFromId($submitter->getVar('uid'));
$locationAsArray['loc_submitter_uid'] = $submitter->getVar('uid');
unset($submitter);
$locationAsArray['loc_date'] = formatTimeStamp($locationAsArray['loc_date'], _DATESTRING);

// Set location's standard fields for tamplate
$xoopsTpl->assign('location', $locationAsArray);

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
// Set fields categories array for tamplate
$xoopsTpl->assign('fieldscategoriesarray', $fieldsCategoriesArray);

// Get ids of fields that can be viewed/edited
$groupPermHandler =& xoops_gethandler('groupperm');
$viewableFields = $groupPermHandler->getItemIds('field_view', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
$editableFields = $groupPermHandler->getItemIds('field_edit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );

//$fields = $locationHandler->loadFields();
// populate $elementsArray[$cat_id] tri-dimensional array with {@link XaddressesField} objects
// $elementsArray[$cat_id][]['element'] is a {@link XaddressesField} object
// $elementsArray[$cat_id][]['required'] is bool (true: required, false: not required)
// $elementsArray[$cat_id][]['...']
$elementsArray = array();
foreach ($fieldsCategoriesArray as $fieldsCategory) {
// Get location's extra fields by category
    $cat_id = $fieldsCategory['cat_id'];
//    echo $fieldsCategory['cat_title'];
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('cat_id', $cat_id));
    $criteria->setSort('field_weight');
    $criteria->setOrder('ASC');
    $fields = $locationHandler->getFields($criteria);
    foreach ($fields as $field) {
        // check if field is viewable by user
        if (in_array($field->getVar('field_id'), $viewableFields)) {
            // Fill elements array indexed by cat_id and field_weight
            $element = array();
            $element['name'] = $field->getVar('field_name');
            $element['title'] = $field->getVar('field_title');
            $element['description'] = $field->getVar('field_description');
            $element['value'] = $field->getOutputValue($location);
            $element['required'] = $field->getVar('field_required');
            $element['type'] = $field->getVar('field_type');
            $elementsArray[$cat_id][] = $element;
        }
    }
}

// Set location's extra fields for tamplate
$xoopsTpl->assign('fieldsarray', $elementsArray);


// Create Google map
require_once(XOOPS_ROOT_PATH . '/modules/xaddresses/kml/simpleGMapAPI/simpleGMapAPI.php');
$map = new simpleGMapAPI();

$map->printGMapsJS();

$map->setWidth(600);
$map->setHeight(600);
$map->setBackgroundColor('#d0d0d0');
$map->setMapDraggable(true);
$map->setDoubleclickZoom(false);
$map->setScrollwheelZoom(true);

$map->showDefaultUI(false);
$map->showMapTypeControl(true, 'DROPDOWN_MENU');
$map->showNavigationControl(true, 'DEFAULT');
$map->showScaleControl(true);
$map->showStreetViewControl(true);

$map->setZoomLevel($location->getVar('loc_zoom')); // not really needed because showMap is called in this demo with auto zoom
$map->setInfoWindowBehaviour('SINGLE_CLOSE_ON_MAPCLICK');
$map->setInfoWindowTrigger('CLICK');

$html = '<a href="locationview.php?loc_id=' . $location->getVar('loc_id') . '">' . $location->getVar('loc_title') . '</a>';
$map->addMarker($location->getVar('loc_lat'), $location->getVar('loc_lng'), $location->getVar('loc_title'), $html);

$htmlMap = $map->showMap(false, false); // no autozoom

// Set Google map for tamplate
$xoopsTpl->assign('htmlMap', $htmlMap);



/*
// permission
$xoopsTpl->assign('perm_vote', $perm_vote);
$xoopsTpl->assign('perm_modif', $perm_modif);
// link for tellafriend
if (($xoopsModuleConfig['usetellafriend'] == 1) and (is_dir('../tellafriend'))) {
    $string = sprintf(_MD_XADDRESSES_SINGLEITEM_INTFILEFOUND, $xoopsConfig['sitename'] . ':'.XOOPS_URL.'/modules/TDMDownloads/singlefile.php?loc_id=' . $_REQUEST['loc_id']);
    $subject = sprintf(_MD_XADDRESSES_SINGLEITEM_INTFILEFOUND, $xoopsConfig['sitename']);
    if( stristr( $subject , '%' ) ) $subject = rawurldecode( $subject ) ;
    if( stristr( $string , '%3F' ) ) $string = rawurldecode( $string ) ;
    if( preg_match( '/('.preg_quote(XOOPS_URL,'/').'.*)$/i' , $string , $matches ) ) {
        $target_uri = str_replace( '&amp;' , '&' , $matches[1] ) ;
    } else {
        $target_uri = XOOPS_URL . $_SERVER['REQUEST_URI'] ;
    }
    $tellafriend_href = XOOPS_URL . '/modules/tellafriend/index.php?target_uri=' . rawurlencode( $target_uri ) . '&amp;subject='.rawurlencode( $subject );
} else {
    $tellafriend_href = 'mailto:?subject=' . rawurlencode(sprintf(_MD_XADDRESSES_SINGLEITEM_INTFILEFOUND,$xoopsConfig['sitename'])) . '&amp;body=' . rawurlencode(sprintf(_MD_XADDRESSES_SINGLEITEM_INTFILEFOUND,$xoopsConfig['sitename']) . ':  ' . XOOPS_URL . '/modules/xaddresses/locationview.php?loc_id=' . $_REQUEST['loc_id']);
}
$xoopsTpl->assign('tellafriend_href', $tellafriend_href);
// référencement
// tags
if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../tag'))) {
    require_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php';
    $xoopsTpl->assign('tags', true);
    $xoopsTpl->assign('tagbar', tagBar($_REQUEST['loc_id'], 0));
} else {
    $xoopsTpl->assign('tags', false);
}
// page title
$title = $location->getVar('loc_title') . '&nbsp;-&nbsp;';
$title.= $category->getVar('cat_title') . '&nbsp;-&nbsp;';
$title.= $xoopsModule->name();
$xoopsTpl->assign('xoops_pagetitle', $title);
//keywords
$keywords = substr($keywords, 0, -1);
$xoTheme->addMeta('meta', 'keywords', $keywords);
//include XOOPS_ROOT_PATH . '/include/comment_view.php';
*/
include XOOPS_ROOT_PATH.'/footer.php';
?>