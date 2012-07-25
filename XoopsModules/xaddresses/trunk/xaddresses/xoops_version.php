<?php
if (!defined('XOOPS_ROOT_PATH')){ exit(); }
$dirname = basename( dirname( __FILE__ ) ) ;

xoops_load('XoopsLists');

$modversion['name'] = _MI_XADDRESSES_NAME;
$modversion['version'] = '1.0';
$modversion['description'] = _MI_XADDRESSES_DESC;
$modversion['author'] = "Rota Lucio";
$modversion['author_mail'] = 'lucio.rota@gmail.com';
$modversion['author_website_url'] = 'http://luciorota.altervista.org';
$modversion['author_website_name'] = 'http://luciorota.altervista.org';
$modversion['credits'] = "XOOPS";
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0 see Licence';
$modversion['license_url'] = "www.gnu.org/licenses/gpl-2.0.html/";

$modversion['release_info'] = "RC";
$modversion['release_file'] = XOOPS_URL."/modules/{$dirname}/docs/RC";
$modversion['release_date'] = "2012/07/25"; // 'Y/m/d'

$modversion['manual'] = 'Help';
$modversion['manual_file'] = XOOPS_URL."/modules/{$dirname}/docs/help.html";
$modversion['min_php'] = '5.2';
$modversion['min_xoops'] = '2.4.5'; // 'XOOPS 2.5';
$modversion['min_admin']= '1.1';
$modversion['min_db']= array('mysql'=>'5.0.7', 'mysqli'=>'5.0.7');
$modversion['image'] = "images/xaddresses_slogo.png";
$modversion['dirname'] = "{$dirname}";
$modversion['official'] = false;

$modversion['dirmoduleadmin'] = "Frameworks/moduleclasses";
$modversion['icons16'] = "modules/{$dirname}/images/icons/16x16";
$modversion['icons32'] = "modules/{$dirname}/images/icons/32x32";

//About
$modversion['demo_site_url'] = "IN PROGRESS";
$modversion['demo_site_name'] = "IN PROGRESS";
$modversion['forum_site_url'] = "IN PROGRESS";
$modversion['forum_site_name'] = "IN PROGRESS";
$modversion['module_website_url'] = "IN PROGRESS";
$modversion['module_website_name'] = "IN PROGRESS";
//$modversion['support_site_url']	= "http://www.xoops.org";
//$modversion['support_site_name'] = "www.xoops.org";
$modversion['release'] = "release";
$modversion['module_status'] = 'In progress'; //"Stable";

// Admin things
$modversion['hasAdmin'] = true;
// Admin system menu
$modversion['system_menu'] = true;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";
// Mysql file
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
// Mysql file
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
// Tables created by sql file (without prefix!)
$modversion['tables'][0] = "xaddresses_locationcategory";
$modversion['tables'][1] = "xaddresses_location";

$modversion['tables'][2] = "xaddresses_fieldcategory";
$modversion['tables'][3] = "xaddresses_field";
// IN PROGRESS
$modversion['tables'][4] = "xaddresses_broken";
$modversion['tables'][5] = "xaddresses_modify";
$modversion['tables'][6] = "xaddresses_votedata";
// TO DO
$modversion['tables'][7] = "xaddresses_marker";

// Scripts to run upon installation or update
$modversion['onInstall'] = 'include/install_function.php';
//$modversion['onUpdate'] = 'include/update_function.php';
$modversion['onUninstall'] = 'include/uninstall_function.php';

// Main menu
$modversion['hasMain'] = true;
if (is_object($GLOBALS['xoopsModule']) && $GLOBALS['xoopsModule']->getVar('dirname') == $modversion['dirname']) {
    $i = 0;
    $isAdmin = false;
    if (!empty($GLOBALS['xoopsUser'])) {
        //$modversion['sub'][0]['name'] = _MI_XADDRESSES_TODO;
        //$modversion['sub'][0]['url'] = "public-useralbum.php?id=".$GLOBALS['xoopsUser']->uid();
        // Check if xoopsUser is a module administrator
        $isAdmin = ($GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->getVar('mid')));
    }
    // Add the Submit new item button (if user has right submit permissions)
    if ($isAdmin || (isset($GLOBALS['xoopsModuleConfig']['allowsubmit']) &&
        $GLOBALS['xoopsModuleConfig']['allowsubmit'] == true &&
            (is_object($GLOBALS['xoopsUser']) ||
            (isset($GLOBALS['xoopsModuleConfig']['anonpost']) && $GLOBALS['xoopsModuleConfig']['anonpost'] == 1)))) {
        $submitableCategoriesIds =  xaddresses_getMyItemIds('in_category_submit');
        if (count($submitableCategoriesIds) > 0) {
            $i++;
            $modversion['sub'][$i]['name'] = _MI_XADDRESSES_SUBMIT;
            $modversion['sub'][$i]['url'] = "locationedit.php?op=new_location";
        }
    }
        $i++;
        $modversion['sub'][$i]['name'] = _MI_XADDRESSES_SEARCH;
        $modversion['sub'][$i]['url'] = "locationsearch.php";
    // Add the Module Administration button (if user has right permissions)
    if ($isAdmin) {
        $i++;
        $modversion['sub'][$i]['name'] = _MI_XADDRESSES_ADMIN;
        $modversion['sub'][$i]['url'] = "admin/index.php";
    }
}

// Pour les blocs
$modversion['blocks'][1]['file'] = "xaddresses_top.php";
$modversion['blocks'][1]['name'] = _MI_XADDRESSES_BNAME1;
$modversion['blocks'][1]['description'] = _MI_XADDRESSES_BNAMEDSC1;
$modversion['blocks'][1]['show_func'] = "b_xaddresses_top_show";
$modversion['blocks'][1]['edit_func'] = "b_xaddresses_top_edit";
$modversion['blocks'][1]['options'] = "date|10|19|0";
$modversion['blocks'][1]['template'] = 'xaddresses_block_new.html';

$modversion['blocks'][2]['file'] = "xaddresses_top.php";
$modversion['blocks'][2]['name'] = _MI_XADDRESSES_BNAME2;
$modversion['blocks'][2]['description'] = _MI_XADDRESSES_BNAMEDSC2;
$modversion['blocks'][2]['show_func'] = "b_xaddresses_top_show";
$modversion['blocks'][2]['edit_func'] = "b_xaddresses_top_edit";
$modversion['blocks'][2]['options'] = "hits|10|19|0";
$modversion['blocks'][2]['template'] = 'xaddresses_block_top.html';

$modversion['blocks'][3]['file'] = "xaddresses_top.php";
$modversion['blocks'][3]['name'] = _MI_XADDRESSES_BNAME3;
$modversion['blocks'][3]['description'] = _MI_XADDRESSES_BNAMEDSC3;
$modversion['blocks'][3]['show_func'] = "b_xaddresses_top_show";
$modversion['blocks'][3]['edit_func'] = "b_xaddresses_top_edit";
$modversion['blocks'][3]['options'] = "rating|10|19|0";
$modversion['blocks'][3]['template'] = 'xaddresses_block_rating.html';

$modversion['blocks'][4]['file'] = "xaddresses_top.php";
$modversion['blocks'][4]['name'] = _MI_XADDRESSES_BNAME4;
$modversion['blocks'][4]['description'] = _MI_XADDRESSES_BNAMEDSC4;
$modversion['blocks'][4]['show_func'] = "b_xaddresses_top_show";
$modversion['blocks'][4]['edit_func'] = "b_xaddresses_top_edit";
$modversion['blocks'][4]['options'] = "random|10|19|0";
$modversion['blocks'][4]['template'] = 'xaddresses_block_random.html';



// Search
$modversion['hasSearch'] = true;
$modversion['search']['file'] = "include/search_functions.php";
$modversion['search']['func'] = "xaddresses_search";



// Comments
$modversion['hasComments'] = true;
$modversion['comments']['itemName'] = 'loc_id';
$modversion['comments']['pageName'] = 'locationview.php';
$modversion['comments']['extraParams'] = array('loc_cat_id');
// Comment callback functions
$modversion['comments']['callbackFile'] = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'xaddresses_com_approve';
$modversion['comments']['callback']['update'] = 'xaddresses_com_update';



// Templates
$i = 0;
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_index.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_locationcategoryview.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_locationview.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_locationedit.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_locationbroken.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_locationrate.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_locationmodify.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_locationsearch.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_locationsearchresults.html';
$modversion['templates'][$i]['description'] = '';

// Admin Templates
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_admin_locationcategorylist.html';
$modversion['templates'][$i]['description'] = '';
//$modversion['templates'][$i]['type'] = 'admin';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_admin_locationlist.html';
$modversion['templates'][$i]['description'] = '';
//$modversion['templates'][$i]['type'] = 'admin';
    $i++;
    $modversion['templates'][$i]['file'] = 'xaddresses_admin_locationbrokenlist.html';
    $modversion['templates'][$i]['description'] = '';
    //$modversion['templates'][$i]['type'] = 'admin';
    $i++;
    $modversion['templates'][$i]['file'] = 'xaddresses_admin_locationmodifylist.html';
    $modversion['templates'][$i]['description'] = '';
    //$modversion['templates'][$i]['type'] = 'admin';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_admin_fieldcategorylist.html';
$modversion['templates'][$i]['description'] = '';
//$modversion['templates'][$i]['type'] = 'admin';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_admin_fieldlist.html';
$modversion['templates'][$i]['description'] = '';
//$modversion['templates'][$i]['type'] = 'admin';

// Image Manager Templates
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_imagemanager.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_imagemanager2.html';
$modversion['templates'][$i]['description'] = '';



// Preferences
// FOR FUTURE XOOPS VERSIONS
/*
$i = 0;
$i++;
$modversion['config']['category'][$i]['name'] = 'global';
$modversion['config']['category'][$i]['title'] = _MI_XADDRESSES_GLOBAL_CONFIG;
$modversion['config']['category'][$i]['description'] = _MI_XADDRESSES_GLOBAL_CONFIG_DESC;
$i++;
$modversion['config']['category'][$i]['name'] = 'category';
$modversion['config']['category'][$i]['title'] = _MI_XADDRESSES_CATEGORY_CONFIG;
$modversion['config']['category'][$i]['description'] = _MI_XADDRESSES_CATEGORY_CONFIG_DESC;
$i++;
$modversion['config']['category'][$i]['name'] = 'location';
$modversion['config']['category'][$i]['title'] = _MI_XADDRESSES_LOCATION_CONFIG;
$modversion['config']['category'][$i]['description'] = _MI_XADDRESSES_LOCATION_CONFIG_DESC;
*/

$i = 0;
$i++;
    $modversion['config'][$i]['name']           = 'google_apikey';
    $modversion['config'][$i]['title']          = '_MI_XADDRESSES_GOOGLE_APIKEY';
    $modversion['config'][$i]['description']    = '_MI_XADDRESSES_GOOGLE_APIKEY_DESC';
    $modversion['config'][$i]['formtype']       = 'textbox';
    $modversion['config'][$i]['valuetype']      = 'text';
    $modversion['config'][$i]['default']        = '';
    $modversion["config"][$i]["category"]       = "global";
$i++;
    $modversion['config'][$i]['name']           = 'popular';
    $modversion['config'][$i]['title']          = '_MI_XADDRESSES_POPULAR';
    $modversion['config'][$i]['description']    = '_MI_XADDRESSES_POPULAR_DESC';
    $modversion['config'][$i]['formtype']       = 'textbox';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 100;
    $modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'nbsouscat';
    $modversion['config'][$i]['title']          = '_MI_XADDRESSES_SUBCATPARENT';
    $modversion['config'][$i]['description']    = '_MI_XADDRESSES_SUBCATPARENT_DESC';
    $modversion['config'][$i]['formtype']       = 'textbox';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 5;
    $modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'index_list_recent';
$modversion['config'][$i]['title']          = '_MI_XADDRESSES_LIST_RECENT';
$modversion['config'][$i]['description']    = '_MI_XADDRESSES_LIST_RECENT_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1; // true
$modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'blpop';
    $modversion['config'][$i]['title']          = '_MI_XADDRESSES_BLPOP';
    $modversion['config'][$i]['description']    = '_MI_XADDRESSES_BLPOP_DESC';
    $modversion['config'][$i]['formtype']       = 'yesno';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 1; // true
    $modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'index_list_toprated';
$modversion['config'][$i]['title']          = '_MI_XADDRESSES_LIST_TOPRATED';
$modversion['config'][$i]['description']    = '_MI_XADDRESSES_LIST_TOPRATED_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1; // true
$modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'index_list_number';
$modversion['config'][$i]['title']          = '_MI_XADDRESSES_LIST_NUMBER';
$modversion['config'][$i]['description']    = '_MI_XADDRESSES_LIST_NUMBER_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 5;
$modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'index_list_titlelenght';
$modversion['config'][$i]['title']          = '_MI_XADDRESSES_LIST_TITLELENGHT';
$modversion['config'][$i]['description']    = '_MI_XADDRESSES_LIST_TITLELENGHT_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 20;
$modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'show_home_in_breadcrumb';
$modversion['config'][$i]['title']          = '_MI_XADDRESSES_SHOWHOMEINBREADCRUMB';
$modversion['config'][$i]['description']    = '_MI_XADDRESSES_SHOWHOMEINBREADCRUMB_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1; // true/yes
$modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'usetellafriend';
$modversion['config'][$i]['title']          = '_MI_XADDRESSES_USETELLAFRIEND';
$modversion['config'][$i]['description']    = '_MI_XADDRESSES_USETELLAFRIEND_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 0; // false/no
$modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'usetag';
$modversion['config'][$i]['title']          = '_MI_XADDRESSES_USETAG';
$modversion['config'][$i]['description']    = '_MI_XADDRESSES_USETAG_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 0; // false/no
$modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'useajaxfilemanager';
$modversion['config'][$i]['title']          = '_MI_XADDRESSES_USEAJAXFILEMANAGER';
$modversion['config'][$i]['description']    = '_MI_XADDRESSES_USAJAXFILEMANAGER_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 0; // false/no
$modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'autoapprove';
    $modversion['config'][$i]['title']          = '_MI_XADDRESSES_AUTOAPPROVE';
    $modversion['config'][$i]['description']    = '_MI_XADDRESSES_AUTOAPPROVE_DESC';
    $modversion['config'][$i]['formtype']       = 'yesno';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 0;
    $modversion["config"][$i]["category"]       = "global";
$i++;
$modversion["config"][$i]["name"]           = "editor";
$modversion["config"][$i]["title"]          = "_MI_XADDRESSES_FORM_OPTIONS";
$modversion["config"][$i]["description"]    = "_MI_XADDRESSES_FORM_OPTIONS_DESC";
$modversion["config"][$i]["formtype"]       = "select";
$modversion["config"][$i]["valuetype"]      = "text";
$modversion["config"][$i]["default"]        = "dhtmltextarea";
$modversion["config"][$i]["options"]        = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . "/class/xoopseditor");
$modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'toporder';
    $modversion['config'][$i]['title']          = '_MI_XADDRESSES_TOPORDER';
    $modversion['config'][$i]['description']    = '_MI_XADDRESSES_TOPORDER_DESC';
    $modversion['config'][$i]['formtype']       = 'select';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 1;
    $modversion['config'][$i]['options']        = array('_MI_XADDRESSES_TOPORDER1' => 1, '_MI_XADDRESSES_TOPORDER2' => 2, '_MI_XADDRESSES_TOPORDER3' => 3, '_MI_XADDRESSES_TOPORDER4' => 4, '_MI_XADDRESSES_TOPORDER5' => 5, '_MI_XADDRESSES_TOPORDER6' => 6, '_MI_XADDRESSES_TOPORDER7' => 7, '_MI_XADDRESSES_TOPORDER8' => 8);
    $modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'newdownloads';
    $modversion['config'][$i]['title']          = '_MI_XADDRESSES_NEWDLS';
    $modversion['config'][$i]['description']    = '_MI_XADDRESSES_NEWDLS_DESC';
    $modversion['config'][$i]['formtype']       = 'textbox';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 10;
    $modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'searchorder';
    $modversion['config'][$i]['title']          = '_MI_XADDRESSES_SEARCHORDER';
    $modversion['config'][$i]['description']    = '_MI_XADDRESSES_SEARCHORDER_DESC';
    $modversion['config'][$i]['formtype']       = 'select';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 8;
    $modversion['config'][$i]['options']        = array('_MI_XADDRESSES_TOPORDER1' => 1, '_MI_XADDRESSES_TOPORDER2' => 2, '_MI_XADDRESSES_TOPORDER3' => 3, '_MI_XADDRESSES_TOPORDER4' => 4, '_MI_XADDRESSES_TOPORDER5' => 5, '_MI_XADDRESSES_TOPORDER6' => 6, '_MI_XADDRESSES_TOPORDER7' => 7, '_MI_XADDRESSES_TOPORDER8' => 8);
    $modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'perpageliste';
    $modversion['config'][$i]['title']          = '_MI_XADDRESSES_PERPAGELISTE';
    $modversion['config'][$i]['description']    = '_MI_XADDRESSES_PERPAGELISTE_DESC';
    $modversion['config'][$i]['formtype']       = 'textbox';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 15;
    $modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'perpage';
    $modversion['config'][$i]['title']          = '_MI_XADDRESSES_PERPAGE';
    $modversion['config'][$i]['description']    = '_MI_XADDRESSES_PERPAGE_DESC';
    $modversion['config'][$i]['formtype']       = 'textbox';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 10;
    $modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'perpageadmin';
    $modversion['config'][$i]['title']          = '_MI_XADDRESSES_PERPAGEADMIN';
    $modversion['config'][$i]['description']    = '_MI_XADDRESSES_PERPAGEADMIN_DESC';
    $modversion['config'][$i]['formtype']       = 'textbox';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 15;
    $modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'showupdated';
    $modversion['config'][$i]['title']          = '_MI_XADDRESSES_SHOW_UPDATED';
    $modversion['config'][$i]['description']    = '_MI_XADDRESSES_SHOW_UPDATED_DESC';
    $modversion['config'][$i]['formtype']       = 'yesno';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 1;
    $modversion["config"][$i]["category"]       = "global";



// Notifications
$modversion['hasNotification'] = true;
$modversion['notification']['lookup_file'] = 'include/notification_functions.php';
$modversion['notification']['lookup_func'] = 'xaddresses_notify_iteminfo';

$i = 0;
$i++;
$modversion['notification']['category'][$i]['name'] = 'global';
$modversion['notification']['category'][$i]['title'] = _MI_XADDRESSES_GLOBAL_NOTIFY;
$modversion['notification']['category'][$i]['description'] = _MI_XADDRESSES_GLOBAL_NOTIFYDESC;
$modversion['notification']['category'][$i]['subscribe_from'] = array('index.php', 'locationcategoryview.php', 'locationview.php');
$i++;
$modversion['notification']['category'][$i]['name'] = 'category';
$modversion['notification']['category'][$i]['title'] = _MI_XADDRESSES_CATEGORY_NOTIFY;
$modversion['notification']['category'][$i]['description'] = _MI_XADDRESSES_CATEGORY_NOTIFYDESC;
$modversion['notification']['category'][$i]['subscribe_from'] = array('locationcategoryview.php', 'locationview.php');
$modversion['notification']['category'][$i]['item_name'] = 'cat_id';
$modversion['notification']['category'][$i]['allow_bookmark'] = true;
$i++;
$modversion['notification']['category'][$i]['name'] = 'location';
$modversion['notification']['category'][$i]['title'] = _MI_XADDRESSES_LOCATION_NOTIFY;
$modversion['notification']['category'][$i]['description'] = _MI_XADDRESSES_LOCATION_NOTIFYDESC;
$modversion['notification']['category'][$i]['subscribe_from'] = 'locationview.php';
$modversion['notification']['category'][$i]['item_name'] = 'loc_id';
$modversion['notification']['category'][$i]['allow_bookmark'] = true;

$i = 0;
$i++;
$modversion['notification']['event'][$i]['name'] = 'new_category';
$modversion['notification']['event'][$i]['category'] = 'global';
$modversion['notification']['event'][$i]['title'] = _MI_XADDRESSES_GLOBAL_NEWCATEGORY_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _MI_XADDRESSES_GLOBAL_NEWCATEGORY_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _MI_XADDRESSES_GLOBAL_NEWCATEGORY_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_newcategory_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _MI_XADDRESSES_GLOBAL_NEWCATEGORY_NOTIFYSBJ;
$i++;
$modversion['notification']['event'][$i]['name'] = 'location_modify';
$modversion['notification']['event'][$i]['category'] = 'global';
$modversion['notification']['event'][$i]['admin_only'] = true;
$modversion['notification']['event'][$i]['title'] = _MI_XADDRESSES_GLOBAL_LOCATIONMODIFY_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _MI_XADDRESSES_GLOBAL_LOCATIONMODIFY_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _MI_XADDRESSES_GLOBAL_LOCATIONMODIFY_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_locationmodify_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _MI_XADDRESSES_GLOBAL_LOCATIONMODIFY_NOTIFYSBJ;
$i++;
$modversion['notification']['event'][$i]['name'] = 'location_broken';
$modversion['notification']['event'][$i]['category'] = 'global';
$modversion['notification']['event'][$i]['admin_only'] = true;
$modversion['notification']['event'][$i]['title'] = _MI_XADDRESSES_GLOBAL_LOCATIONBROKEN_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _MI_XADDRESSES_GLOBAL_LOCATIONBROKEN_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _MI_XADDRESSES_GLOBAL_LOCATIONBROKEN_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_locationbroken_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _MI_XADDRESSES_GLOBAL_LOCATIONBROKEN_NOTIFYSBJ;
$i++;
$modversion['notification']['event'][$i]['name'] = 'location_submit';
$modversion['notification']['event'][$i]['category'] = 'global';
$modversion['notification']['event'][$i]['admin_only'] = true;
$modversion['notification']['event'][$i]['title'] = _MI_XADDRESSES_GLOBAL_LOCATIONSUBMIT_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _MI_XADDRESSES_GLOBAL_LOCATIONSUBMIT_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _MI_XADDRESSES_GLOBAL_LOCATIONSUBMIT_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_locationsubmit_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _MI_XADDRESSES_GLOBAL_LOCATIONSUBMIT_NOTIFYSBJ;
$i++;
$modversion['notification']['event'][$i]['name'] = 'new_location';
$modversion['notification']['event'][$i]['category'] = 'global';
$modversion['notification']['event'][$i]['title'] = _MI_XADDRESSES_GLOBAL_NEWLOCATION_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _MI_XADDRESSES_GLOBAL_NEWLOCATION_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _MI_XADDRESSES_GLOBAL_NEWLOCATION_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_newlocation_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _MI_XADDRESSES_GLOBAL_NEWLOCATION_NOTIFYSBJ;
$i++;
$modversion['notification']['event'][$i]['name'] = 'location_submit';
$modversion['notification']['event'][$i]['category'] = 'category';
$modversion['notification']['event'][$i]['admin_only'] = true;
$modversion['notification']['event'][$i]['title'] = _MI_XADDRESSES_CATEGORY_LOCATIONSUBMIT_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _MI_XADDRESSES_CATEGORY_LOCATIONSUBMIT_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _MI_XADDRESSES_CATEGORY_LOCATIONSUBMIT_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'category_locationsubmit_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _MI_XADDRESSES_CATEGORY_LOCATIONSUBMIT_NOTIFYSBJ;
$i++;
$modversion['notification']['event'][$i]['name'] = 'new_location';
$modversion['notification']['event'][$i]['category'] = 'category';
$modversion['notification']['event'][$i]['title'] = _MI_XADDRESSES_CATEGORY_NEWLOCATION_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _MI_XADDRESSES_CATEGORY_NEWLOCATION_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _MI_XADDRESSES_CATEGORY_NEWLOCATION_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'category_newlocation_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _MI_XADDRESSES_CATEGORY_NEWLOCATION_NOTIFYSBJ;
$i++;
$modversion['notification']['event'][$i]['name'] = 'approve';
$modversion['notification']['event'][$i]['category'] = 'location';
$modversion['notification']['event'][$i]['invisible'] = true;
$modversion['notification']['event'][$i]['title'] = _MI_XADDRESSES_LOCATION_APPROVE_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _MI_XADDRESSES_LOCATION_APPROVE_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _MI_XADDRESSES_LOCATION_APPROVE_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'location_approve_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _MI_XADDRESSES_LOCATION_APPROVE_NOTIFYSBJ;
?>