<?php
$currentFile = basename(__FILE__);

// include module header
include_once 'header.php';

// load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory', 'xaddresses');
$fieldHandler =& xoops_getModuleHandler('field', 'xaddresses');
$gpermHandler =& xoops_gethandler('groupperm');
$memberHandler =& xoops_gethandler('member');
xoops_load('formgooglemap', 'xaddresses');

// Get ids of fields that can be searched
$searchableFields = $gpermHandler->getItemIds('field_search', $groups, $GLOBALS['xoopsModule']->getVar('mid'));
// Fields types that can be searched
$searchableTypes = array(
    'textbox',
    'select',
    'radio',
    'yesno',
    'date',
    'datetime',
    'timezone',
    'language');

$limit_default = 20;
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : "search";



switch ($op ) {
default:
case "search":
    // Get ids of categories in which locations can be viewed/edited/submitted
    $viewableCategories = $groupPermHandler->getItemIds('in_category_view', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
    $editableCategories = $groupPermHandler->getItemIds('in_category_edit', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
    $submitableCategories = $groupPermHandler->getItemIds('in_category_submit', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
    // Get ids of fields that can be edited
    $editableFields = $groupPermHandler->getItemIds('field_edit', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
    $searchableFields = $groupPermHandler->getItemIds('field_search', $groups, $GLOBALS['xoopsModule']->getVar('mid') );

    $xoopsOption['template_main'] = "xaddresses_locationsearch.html";
    include_once XOOPS_ROOT_PATH . '/header.php';
    
    // count valid locations
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('loc_suggested', false));
    $criteria->add(new Criteria('loc_status', 0, '!='));
    $countLocations = $locationHandler->getCount($criteria);
    unset($criteria);
    $GLOBALS['xoopsTpl']->assign('total_locations', sprintf(_XADDRESSES_AM_INDEX_COUNTLOCATIONS, $countLocations));

    /*
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
    // Set breadcrumb array for tamplate
    $breadcrumb = array_reverse($breadcrumb);
    $xoopsTpl->assign('breadcrumb', $breadcrumb);
    unset($breadcrumb, $crumb);
*/

/*
$R = 6371; // km: is the earth’s radius (mean radius = 6,371km)
$point1 = array('lat' => 0, 'lng' => 0);
$point2 = array('lat' => 0, 'lng' => 0);
$dLat = toRad($point2['lat'] - $point1['lat']);
$dLon = toRad($point2['lng'] - $point1['lng']);
$dLat1 = toRad($point1['lat']);
$dLat2 = toRad($point2['lat']);
$a = sin($dLat/2) * sin($dLat/2) + cos($dLat1) * cos($dLat1) * sin($dLon/2) * sin($dLon/2);
$c = 2 * atan2(sqrt($a), sqrt(1-$a));
$distance = $R * $c; // km

MORE INFO HERE
http://www.movable-type.co.uk/scripts/latlong.html

$distance = abs(acos(sin(lat1) * sin(lat2) + cos(lat1) * cos(lat2) * cos(lon2 - lon1))) * 6371;
*/

    include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
    $searchform = new XoopsThemeForm("", "searchform", $currentFile, "post");

    // location title
        $formLocTitle = new XoopsFormElementTray(_XADDRESSES_AM_LOC_TITLE);
        $formLocTitle->setDescription(_XADDRESSES_AM_LOC_TITLE_DESC);
        $formLocTitle->addElement(new XoopsFormSelectMatchOption('', 'loc_title_match'));
        $formLocTitle->addElement(new XoopsFormText('', 'loc_title', 35, 255));
    $searchform->addElement($formLocTitle);

    // location coordinates
        $formGoogleMap = new FormGoogleMap(_XADDRESSES_AM_LOC_COORDINATES, 'loc_googlemap', null);
        $formGoogleMap->setDescription(_XADDRESSES_AM_LOC_COORDINATES_DESC);
    //$searchform->addElement($formGoogleMap);
        $formMaxDistance = new XoopsFormElementTray(_XADDRESSES_AM_LOC_MAXDISTANCE, '<br />');
        $formMaxDistance->setDescription(_XADDRESSES_AM_LOC_MAXDISTANCE_DESC);
        //$formMaxDistance->addElement(new XoopsFormSelectMatchOption('', 'loc_maxdistance_match'));
        $formMaxDistance->addElement(new XoopsFormText('', 'loc_maxdistance', 35, 255));
        $formMaxDistance->addElement($formGoogleMap);
    $searchform->addElement($formMaxDistance);
    // TO DO
    // TO DO
    // TO DO
    // TO DO
    // TO DO
    // TO DO

    // location category
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('cat_id', ' (' . implode(',', $viewableCategories) . ')', 'IN'));
        $criteria->setOrder('ASC');
        $categoriesArray = $categoryHandler->getall($criteria);
        $categoriesTree = new XoopsObjectTree($categoriesArray, 'cat_id', 'cat_pid');
        $formSelectCategoryMaxLines = 5; // Max lines in groups select
        $countCategory = count($categoriesArray);
        $FormSelectCategoryLines = ($countGroups < $formSelectCategoryMaxLines) ? $countCategory : $formSelectCategoryMaxLines;
        $htmlSelBox = $categoriesTree->makeSelBox('loc_cat_id', 'cat_title', '--', 0, false, 0, "multiple='multiple' size='{formSelectCategoryMaxLines}'");
        //$htmlSelBox = str_replace("<select ", "<select multiple='multiple' size='5' ", $htmlSelBox);
        $formLocCategory = new XoopsFormLabel(_XADDRESSES_AM_LOC_CAT, $htmlSelBox);
        $formLocCategory->setDescription(_XADDRESSES_AM_LOC_CAT_DESC);
    $searchform->addElement($formLocCategory);

    
    // Get fields
    $fields = $locationHandler->loadFields();

    foreach (array_keys($fields) as $i) {
        if (!in_array($fields[$i]->getVar('field_id'), $searchableFields) || !in_array($fields[$i]->getVar('field_type'), $searchableTypes)) {
            continue;
        }
        $sortby_arr[$i] = $fields[$i]->getVar('field_title');
        switch ($fields[$i]->getVar('field_type')) {
            case "textbox":
                if ($fields[$i]->getVar('field_valuetype') == XOBJ_DTYPE_INT) {
                    $searchform->addElement(new XoopsFormText(sprintf(_PROFILE_MA_LARGERTHAN, $fields[$i]->getVar('field_title') ), $fields[$i]->getVar('field_name')."_larger", 35, 35));
                    $searchform->addElement(new XoopsFormText(sprintf(_PROFILE_MA_SMALLERTHAN, $fields[$i]->getVar('field_title') ), $fields[$i]->getVar('field_name')."_smaller", 35, 35));
                } else {
                    $tray = new XoopsFormElementTray($fields[$i]->getVar('field_title'));
                    $tray->addElement(new XoopsFormSelectMatchOption('', $fields[$i]->getVar('field_name')."_match"));
                    $tray->addElement(new XoopsFormText('', $fields[$i]->getVar('field_name'), 35, $fields[$i]->getVar('field_maxlength')));
                    $searchform->addElement($tray);
                    unset($tray);
                }
                break;
            case "radio":
            case "select":
                $options = $fields[$i]->getVar('field_options');
                $size = MIN( count($options), 10 );
                $element = new XoopsFormSelect($fields[$i]->getVar('field_title'), $fields[$i]->getVar('field_name'), null, $size, true);
                asort($options);
                $element->addOptionArray($options);
                $searchform->addElement($element);
                unset($element);
                break;
            case "yesno":
                $element = new XoopsFormSelect($fields[$i]->getVar('field_title'), $fields[$i]->getVar('field_name'), null, 2, true);
                $element->addOption(1, _YES);
                $element->addOption(0, _NO);
                $searchform->addElement($element);
                unset($element);
                break;
            case "date":
            case "datetime":
                $searchform->addElement(new XoopsFormTextDateSelect(sprintf(_PROFILE_MA_LATERTHAN, $fields[$i]->getVar('field_title') ), $fields[$i]->getVar('field_name')."_larger", 15, 0));
                $searchform->addElement(new XoopsFormTextDateSelect(sprintf(_PROFILE_MA_EARLIERTHAN, $fields[$i]->getVar('field_title') ), $fields[$i]->getVar('field_name')."_smaller", 15, time()));
                break;
            case "timezone":
                $element = new XoopsFormSelect($fields[$i]->getVar('field_title'), $fields[$i]->getVar('field_name'), null, 6, true);
                include_once $GLOBALS['xoops']->path('class/xoopslists.php');
                $element->addOptionArray(XoopsLists::getTimeZoneList());
                $searchform->addElement($element);
                unset($element);
                break;
            case "language":
                $element = new XoopsFormSelectLang($fields[$i]->getVar('field_title'), $fields[$i]->getVar('field_name'), null, 6);
                $searchform->addElement($element);
                unset($element);
                break;
        }
    }

    $searchform->addElement(new XoopsFormLabel('// IN PROGRESS', '// IN PROGRESS'));

    asort($sortby_arr);
        $sortby_arr = array_merge(array("" => _NONE, "title" =>_US_NICKNAME, "email" => _US_EMAIL), $sortby_arr);
        $sortby_select = new XoopsFormSelect(_PROFILE_MA_SORTBY, 'sortby');
        $sortby_select->addOptionArray($sortby_arr);
    $searchform->addElement($sortby_select);

        $order_select = new XoopsFormRadio(_PROFILE_MA_ORDER, 'order', 0);
        $order_select->addOption(0, _ASCENDING);
        $order_select->addOption(1, _DESCENDING);
    $searchform->addElement($order_select);

        $limit_text = new XoopsFormText(_PROFILE_MA_PERPAGE, 'limit', 15, 10, $limit_default);
        $searchform->addElement($limit_text);
        $searchform->addElement(new XoopsFormHidden('op', 'results'));
    $searchform->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

    $searchform->assign($GLOBALS['xoopsTpl']);
    $GLOBALS['xoopsTpl']->assign('page_title', _PROFILE_MA_SEARCH);

    break;

case "results":
    $xoopsOption['template_main'] = "xaddresses_locationsearchresults.html";
    include_once XOOPS_ROOT_PATH . '/header.php';

    $GLOBALS['xoopsTpl']->assign('page_title', _PROFILE_MA_RESULTS);
    $xoBreadcrumbs[] = array('link' => XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . '/search.php', 'title' => _SEARCH);
    $xoBreadcrumbs[] = array('title' => _PROFILE_MA_RESULTS);


    // Get fields
    $fields = $locationHandler->loadFields();
    // Get ids of fields that can be searched
    $searchvars = array();

    $criteria = new CriteriaCompo(new Criteria('level', 0, '>'));
    if (isset($_REQUEST['uname']) && $_REQUEST['uname'] != '') {
        $string = $myts->addSlashes(trim($_REQUEST['uname']));
        switch ($_REQUEST['uname_match']) {
            case XOOPS_MATCH_START:
                $string .= "%";
                break;

            case XOOPS_MATCH_END:
                $string = "%" . $string;
                break;

            case XOOPS_MATCH_CONTAIN:
                $string = "%" . $string . "%";
                break;
        }
        $criteria->add(new Criteria('uname', $string, "LIKE"));
        $searchvars[] = "uname";
    }
    if (isset($_REQUEST['email']) && $_REQUEST['email'] != '') {
        $string = $myts->addSlashes(trim($_REQUEST['email']));
        switch ($_REQUEST['email_match']) {
            case XOOPS_MATCH_START:
                $string .= "%";
                break;

            case XOOPS_MATCH_END:
                $string = "%" . $string;
                break;

            case XOOPS_MATCH_CONTAIN:
                $string = "%" . $string . "%";
                break;
        }
        $searchvars[] = "email";
        $criteria->add(new Criteria('email', $string, "LIKE"));
        $criteria->add(new Criteria('user_viewemail', 1) );
    }

    $search_url = array();
    foreach (array_keys($fields) as $i ) {
        if (!in_array($fields[$i]->getVar('field_id'), $searchableFields) || !in_array($fields[$i]->getVar('field_type'), $searchableTypes)) {
            continue;
        }
        $fieldname = $fields[$i]->getVar('field_name');
        if (in_array($fields[$i]->getVar('field_type'), array("select", "radio"))) {
            if (empty($_REQUEST[$fieldname])) {
                continue;
            }
            //If field value is sent through request and is not an empty value
            switch ($fields[$i]->getVar('field_valuetype')) {
                case XOBJ_DTYPE_OTHER:
                case XOBJ_DTYPE_INT:
                    $value = array_map('intval', $_REQUEST[$fieldname]);
                    $searchvars[] = $fieldname;
                    $criteria->add(new Criteria($fieldname, "(" . implode(',', $value) . ")", "IN"));
                    break;

                case XOBJ_DTYPE_URL:
                case XOBJ_DTYPE_TXTBOX:
                case XOBJ_DTYPE_TXTAREA:
                    $value = array_map(array($GLOBALS['xoopsDB'], "quoteString"), $_REQUEST[$fieldname]);
                    $searchvars[] = $fieldname;
                    $criteria->add(new Criteria($fieldname, "(" . implode(',', $value) . ")", "IN"));
                    break;
            }
        } else {
            switch ($fields[$i]->getVar('field_valuetype')) {
                case XOBJ_DTYPE_OTHER:
                case XOBJ_DTYPE_INT:
                    switch ($fields[$i]->getVar('field_type')) {
                        case "date":
                        case "datetime":
                            $value = $_REQUEST[$fieldname."_larger"];
                            if (!($value = strtotime($_REQUEST[$fieldname."_larger"]))) {
                                $value = intval($_REQUEST[$fieldname . "_larger"]);
                            }
                            if ($value > 0) {
                                $search_url[] = $fieldname . "_larger=" . $value;
                                $searchvars[] = $fieldname;
                                $criteria->add(new Criteria($fieldname, $value, ">="));
                            }

                            $value = $_REQUEST[$fieldname . "_smaller"];
                            if (!($value = strtotime($_REQUEST[$fieldname . "_smaller"]))) {
                                $value = intval($_REQUEST[$fieldname . "_smaller"]);
                            }
                            if ($value > 0) {
                                $search_url[] = $fieldname . "_smaller=" . $value;
                                $searchvars[] = $fieldname;
                                $criteria->add(new Criteria($fieldname, $value + 24 * 3600, "<="));
                            }
                            break;

                        default:
                            if (isset($_REQUEST[$fieldname . "_larger"]) && intval($_REQUEST[$fieldname . "_larger"]) != 0) {
                                $value = intval($_REQUEST[$fieldname . "_larger"]);
                                $search_url[] = $fieldname . "_larger=" . $value;
                                $searchvars[] = $fieldname;
                                $criteria->add(new Criteria($fieldname, $value, ">="));
                            }

                            if (isset($_REQUEST[$fieldname . "_smaller"]) && intval($_REQUEST[$fieldname . "_smaller"]) != 0) {
                                $value = intval($_REQUEST[$fieldname . "_smaller"]);
                                $search_url[] = $fieldname . "_smaller=" . $value;
                                $searchvars[] = $fieldname;
                                $criteria->add(new Criteria($fieldname, $value, "<="));
                            }
                            break;
                    }

                    if (isset($_REQUEST[$fieldname]) && !isset($_REQUEST[$fieldname . "_smaller"]) && !isset($_REQUEST[$fieldname . "_larger"])) {
                        if (!is_array($_REQUEST[$fieldname])) {
                            $value = intval($_REQUEST[$fieldname]);
                            $search_url[] = $fieldname . "=" . $value;
                            $criteria->add(new Criteria($fieldname, $value, "="));
                        } else {
                            $value = array_map("intval", $_REQUEST[$fieldname]);
                            foreach ($value as $thisvalue) {
                                $search_url[] = $fieldname . "[]=" . $thisvalue;
                            }
                            $criteria->add(new Criteria($fieldname, "(" . implode(',', $value) . ")", "IN"));
                        }

                        $searchvars[] = $fieldname;
                    }
                    break;

                case XOBJ_DTYPE_URL:
                case XOBJ_DTYPE_TXTBOX:
                case XOBJ_DTYPE_TXTAREA:
                    if (isset($_REQUEST[$fieldname]) && $_REQUEST[$fieldname] != "") {
                        $value = $myts->addSlashes(trim($_REQUEST[$fieldname]));
                        switch ($_REQUEST[$fieldname . '_match'] ) {
                            case XOOPS_MATCH_START:
                                $value .= "%";
                                break;

                            case XOOPS_MATCH_END:
                                $value = "%" . $value;
                                break;

                            case XOOPS_MATCH_CONTAIN:
                                $value = "%" . $value . "%";
                                break;
                        }
                        $search_url[] = $fieldname . "=" . $value;
                        $operator = "LIKE";
                        $criteria->add(new Criteria($fieldname, $value, $operator));
                        $searchvars[] = $fieldname;
                    }
                    break;
            }
        }
    }

    // Why break? What if admin does a search for groups with no name? Don't we need results?
    /*
    if ( $searchvars == array()  ) {
        break;
    }
    */
    if ($_REQUEST['sortby'] == "name") {
        $criteria->setSort("name");
    } else if ($_REQUEST['sortby'] == "email") {
        $criteria->setSort("email");
    } else if ($_REQUEST['sortby'] == "uname") {
        $criteria->setSort("uname");
    } else if (isset($fields[$_REQUEST['sortby']])) {
        $criteria->setSort($fields[$_REQUEST['sortby']]->getVar('field_name'));
    }
    // add search groups , only for Webmasters
    $searchgroups = empty($_POST['selgroups']) ? array() : array_map("intval", $_POST['selgroups']);

    $order = $_REQUEST['order'] == 0 ? "ASC" : "DESC";
    $criteria->setOrder($order);

    $limit = empty($_REQUEST['limit']) ? $limit_default : intval($_REQUEST['limit']);
    $criteria->setLimit($limit);

    $start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
    $criteria->setStart($start);

    list($users, $profiles, $total_users) = $profile_handler->search($criteria, $searchvars,$searchgroups);

    $total =sprintf(_PROFILE_MA_FOUNDUSER, "<span class='red'>{$total_users}</span>")." ";
    $GLOBALS['xoopsTpl']->assign('total_users', $total);

    //Sort information
    foreach (array_keys($users) as $k) {
        $userarray = array();
        $userarray["output"][] = "<a href='userinfo.php?uid=" . $users[$k]->getVar('uid') . "' title=''>" . $users[$k]->getVar('uname') . "</a>";
        $userarray["output"][] = ( $users[$k]->getVar('user_viewemail') == 1 || $GLOBALS['xoopsUser']->isAdmin() ) ? $users[$k]->getVar('email') : "";

        foreach (array_keys($fields) as $i) {
            if (in_array($fields[$i]->getVar('field_id'), $searchable_fields) && in_array($fields[$i]->getVar('field_type'), $searchable_types) && in_array($fields[$i]->getVar('field_name'), $searchvars)) {
                $userarray["output"][] = $fields[$i]->getOutputValue($users[$k], $profiles[$k]);
            }
        }
        $GLOBALS['xoopsTpl']->append('users', $userarray);
        unset($userarray);
    }

    //Get captions
    $captions[] = _US_NICKNAME;
    $captions[] = _US_EMAIL;
    foreach (array_keys($fields) as $i) {
        if (in_array($fields[$i]->getVar('field_id'), $searchable_fields) && in_array($fields[$i]->getVar('field_type'), $searchable_types) && in_array($fields[$i]->getVar('field_name'), $searchvars)) {
            $captions[] = $fields[$i]->getVar('field_title');
        }
    }
    $GLOBALS['xoopsTpl']->assign('captions', $captions);

    if ($total_users > $limit) {
        $search_url[] = "op=results";
        $search_url[] = "order=" . $order;
        $search_url[] = "sortby=" . htmlspecialchars($_REQUEST['sortby']);
        $search_url[] = "limit=" . $limit;
        if (isset($search_url)) {
            $args = implode("&amp;", $search_url);
        }
        include_once $GLOBALS['xoops']->path('class/pagenav.php');
        $nav = new XoopsPageNav($total_users, $limit, $start, "start", $args);
        $GLOBALS['xoopsTpl']->assign('nav', $nav->renderNav(5));
    }
    break;
}
include XOOPS_ROOT_PATH . '/footer.php';
?>