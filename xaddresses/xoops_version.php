<?php
if (!defined('XOOPS_ROOT_PATH')) die('XOOPS root path not defined');
$moduleDirname = basename( dirname( __FILE__ ) ) ;

$modversion['name'] = _XADDRESSES_MI_NAME;
$modversion['version'] = 1.0;
$modversion['description'] = _XADDRESSES_MI_DESC;
$modversion['author'] = "Rota Lucio";
$modversion['author_mail'] = 'lucio.rota@gmail.com';
$modversion['author_website_url'] = 'http://luciorota.altervista.org';
$modversion['author_website_name'] = 'http://luciorota.altervista.org';
$modversion['credits'] = "XOOPS";
$modversion['help'] = 'page=help';
$modversion['license'] = 'GPL see LICENSE';
$modversion['license_url'] = 'http://www.gnu.org/licenses/gpl.html';
$modversion['official'] = 0;
$modversion['image'] = "images/xaddresses_slogo.png";
$modversion['release_info'] = 'RC';
$modversion['release_file'] = 'RC';
$modversion['manual'] = 'Help';
$modversion['manual_file'] = 'help.html';
$modversion['dirname'] = $dirname;
// Extra informations
$modversion["release"] = "23-10-2011";
$modversion["module_status"] = "Stable";
$modversion['support_site_url']	= "http://www.xoops.org";
$modversion['support_site_name'] = "www.xoops.org";

// About
$modversion['status_version'] = 'RC';
$modversion['release_date'] = '2011/10/23';
$modversion['release'] = strtotime('2011/10/23'); // 'YYYY/MM/DD' format
$modversion['demo_site_url'] = 'IN PROGRESS';
$modversion['demo_site_name'] = 'IN PROGRESS';
$modversion['forum_site_url'] = 'IN PROGRESS';
$modversion['forum_site_name'] = 'IN PROGRESS';
$modversion['module_website_url'] = 'IN PROGRESS';
$modversion['module_website_name'] = 'IN PROGRESS';
$modversion['module_status'] = 'In progress';
$modversion["author_website_url"] = 'http://luciorota.altervista.org/xoops/';
$modversion["author_website_name"] = 'luciorota.altervista.org/xoops';
$modversion['min_php'] = 5.2;
$modversion['min_xoops'] = 'XOOPS 2.4.5';

// Admin things
$modversion['hasAdmin'] = true;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";
$modversion['system_menu'] = 0;

// Scripts to run upon installation or update
$modversion['onInstall'] = 'include/install_function.php';
//$modversion['onUpdate'] = 'include/update_function.php';
$modversion['onUninstall'] = 'include/uninstall_function.php';

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

// Menu
$modversion['hasMain'] = true;
if (is_object($GLOBALS['xoopsModule']) && $GLOBALS['xoopsModule']->getVar('dirname') == $modversion['dirname']) {
    $i = 0;
    $isAdmin = false;
    if (!empty($GLOBALS['xoopsUser'])) {
        //$modversion['sub'][0]['name'] = _XADDRESSES_MI_TODO;
        //$modversion['sub'][0]['url'] = "public-useralbum.php?id=".$GLOBALS['xoopsUser']->uid();
        // Check if xoopsUser is a module administrator
        $isAdmin = ($GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->getVar('mid')));
    }
    // Add the Submit new item button
    if ($isAdmin || (isset($GLOBALS['xoopsModuleConfig']['allowsubmit']) &&
        $GLOBALS['xoopsModuleConfig']['allowsubmit'] == true &&
            (is_object($GLOBALS['xoopsUser']) ||
            (isset($GLOBALS['xoopsModuleConfig']['anonpost']) && $GLOBALS['xoopsModuleConfig']['anonpost'] == 1)))) {
        $i++;
        $modversion['sub'][$i]['name'] = _XADDRESSES_MI_SUBMIT;
        $modversion['sub'][$i]['url'] = "locationedit.php?op=new_location";
    }
        $i++;
        $modversion['sub'][$i]['name'] = _XADDRESSES_MI_SEARCH;
        $modversion['sub'][$i]['url'] = "locationsearch.php";
    if ($isAdmin) {
        $i++;
        $modversion['sub'][$i]['name'] = _XADDRESSES_MI_ADMIN;
        $modversion['sub'][$i]['url'] = "admin/index.php";
    }
}

// Pour les blocs
$modversion['blocks'][1]['file'] = "xaddresses_top.php";
$modversion['blocks'][1]['name'] = _XADDRESSES_MI_BNAME1;
$modversion['blocks'][1]['description'] = _XADDRESSES_MI_BNAMEDSC1;
$modversion['blocks'][1]['show_func'] = "b_xaddresses_top_show";
$modversion['blocks'][1]['edit_func'] = "b_xaddresses_top_edit";
$modversion['blocks'][1]['options'] = "date|10|19|0";
$modversion['blocks'][1]['template'] = 'xaddresses_block_new.html';

$modversion['blocks'][2]['file'] = "xaddresses_top.php";
$modversion['blocks'][2]['name'] = _XADDRESSES_MI_BNAME2;
$modversion['blocks'][2]['description'] = _XADDRESSES_MI_BNAMEDSC2;
$modversion['blocks'][2]['show_func'] = "b_xaddresses_top_show";
$modversion['blocks'][2]['edit_func'] = "b_xaddresses_top_edit";
$modversion['blocks'][2]['options'] = "hits|10|19|0";
$modversion['blocks'][2]['template'] = 'xaddresses_block_top.html';

$modversion['blocks'][3]['file'] = "xaddresses_top.php";
$modversion['blocks'][3]['name'] = _XADDRESSES_MI_BNAME3;
$modversion['blocks'][3]['description'] = _XADDRESSES_MI_BNAMEDSC3;
$modversion['blocks'][3]['show_func'] = "b_xaddresses_top_show";
$modversion['blocks'][3]['edit_func'] = "b_xaddresses_top_edit";
$modversion['blocks'][3]['options'] = "rating|10|19|0";
$modversion['blocks'][3]['template'] = 'xaddresses_block_rating.html';

$modversion['blocks'][4]['file'] = "xaddresses_top.php";
$modversion['blocks'][4]['name'] = _XADDRESSES_MI_BNAME4;
$modversion['blocks'][4]['description'] = _XADDRESSES_MI_BNAMEDSC4;
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
$modversion['config']['category'][$i]['title'] = _XADDRESSES_MI_GLOBAL_CONFIG;
$modversion['config']['category'][$i]['description'] = _XADDRESSES_MI_GLOBAL_CONFIG_DESC;
$i++;
$modversion['config']['category'][$i]['name'] = 'category';
$modversion['config']['category'][$i]['title'] = _XADDRESSES_MI_CATEGORY_CONFIG;
$modversion['config']['category'][$i]['description'] = _XADDRESSES_MI_CATEGORY_CONFIG_DESC;
$i++;
$modversion['config']['category'][$i]['name'] = 'location';
$modversion['config']['category'][$i]['title'] = _XADDRESSES_MI_LOCATION_CONFIG;
$modversion['config']['category'][$i]['description'] = _XADDRESSES_MI_LOCATION_CONFIG_DESC;
*/

$i = 0;
$i++;
    $modversion['config'][$i]['name']           = 'popular';
    $modversion['config'][$i]['title']          = '_XADDRESSES_MI_POPULAR';
    $modversion['config'][$i]['description']    = '_XADDRESSES_MI_POPULAR_DESC';
    $modversion['config'][$i]['formtype']       = 'textbox';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 100;
    $modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'nbsouscat';
    $modversion['config'][$i]['title']          = '_XADDRESSES_MI_SUBCATPARENT';
    $modversion['config'][$i]['description']    = '_XADDRESSES_MI_SUBCATPARENT_DESC';
    $modversion['config'][$i]['formtype']       = 'textbox';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 5;
    $modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'index_list_recent';
$modversion['config'][$i]['title']          = '_XADDRESSES_MI_LIST_RECENT';
$modversion['config'][$i]['description']    = '_XADDRESSES_MI_LIST_RECENT_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1; // true
$modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'blpop';
    $modversion['config'][$i]['title']          = '_XADDRESSES_MI_BLPOP';
    $modversion['config'][$i]['description']    = '_XADDRESSES_MI_BLPOP_DESC';
    $modversion['config'][$i]['formtype']       = 'yesno';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 1; // true
    $modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'index_list_toprated';
$modversion['config'][$i]['title']          = '_XADDRESSES_MI_LIST_TOPRATED';
$modversion['config'][$i]['description']    = '_XADDRESSES_MI_LIST_TOPRATED_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1; // true
$modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'index_list_number';
$modversion['config'][$i]['title']          = '_XADDRESSES_MI_LIST_NUMBER';
$modversion['config'][$i]['description']    = '_XADDRESSES_MI_LIST_NUMBER_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 5;
$modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'index_list_titlelenght';
$modversion['config'][$i]['title']          = '_XADDRESSES_MI_LIST_TITLELENGHT';
$modversion['config'][$i]['description']    = '_XADDRESSES_MI_LIST_TITLELENGHT_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 20;
$modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'show_home_in_breadcrumb';
$modversion['config'][$i]['title']          = '_XADDRESSES_MI_SHOWHOMEINBREADCRUMB';
$modversion['config'][$i]['description']    = '_XADDRESSES_MI_SHOWHOMEINBREADCRUMB_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1; // true
$modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'usetellafriend';
$modversion['config'][$i]['title']          = '_XADDRESSES_MI_USETELLAFRIEND';
$modversion['config'][$i]['description']    = '_XADDRESSES_MI_USETELLAFRIEND_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 0;
$modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name']           = 'usetag';
$modversion['config'][$i]['title']          = '_XADDRESSES_MI_USETAG';
$modversion['config'][$i]['description']    = '_XADDRESSES_MI_USETAG_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 0;
$modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'autoapprove';
    $modversion['config'][$i]['title']          = '_XADDRESSES_MI_AUTOAPPROVE';
    $modversion['config'][$i]['description']    = '_XADDRESSES_MI_AUTOAPPROVE_DESC';
    $modversion['config'][$i]['formtype']       = 'yesno';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 0;
    $modversion["config"][$i]["category"]       = "global";
$i++;
include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
$modversion["config"][$i]["name"]           = "editor";
$modversion["config"][$i]["title"]          = "_XADDRESSES_MI_FORM_OPTIONS";
$modversion["config"][$i]["description"]    = "_XADDRESSES_MI_FORM_OPTIONS_DESC";
$modversion["config"][$i]["formtype"]       = "select";
$modversion["config"][$i]["valuetype"]      = "text";
$modversion["config"][$i]["default"]        = "dhtmltextarea";
$modversion["config"][$i]["options"]        = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . "/class/xoopseditor");
$modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'toporder';
    $modversion['config'][$i]['title']          = '_XADDRESSES_MI_TOPORDER';
    $modversion['config'][$i]['description']    = '_XADDRESSES_MI_TOPORDER_DESC';
    $modversion['config'][$i]['formtype']       = 'select';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 1;
    $modversion['config'][$i]['options']        = array('_XADDRESSES_MI_TOPORDER1' => 1, '_XADDRESSES_MI_TOPORDER2' => 2, '_XADDRESSES_MI_TOPORDER3' => 3, '_XADDRESSES_MI_TOPORDER4' => 4, '_XADDRESSES_MI_TOPORDER5' => 5, '_XADDRESSES_MI_TOPORDER6' => 6, '_XADDRESSES_MI_TOPORDER7' => 7, '_XADDRESSES_MI_TOPORDER8' => 8);
    $modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'newdownloads';
    $modversion['config'][$i]['title']          = '_XADDRESSES_MI_NEWDLS';
    $modversion['config'][$i]['description']    = '_XADDRESSES_MI_NEWDLS_DESC';
    $modversion['config'][$i]['formtype']       = 'textbox';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 10;
    $modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'searchorder';
    $modversion['config'][$i]['title']          = '_XADDRESSES_MI_SEARCHORDER';
    $modversion['config'][$i]['description']    = '_XADDRESSES_MI_SEARCHORDER_DESC';
    $modversion['config'][$i]['formtype']       = 'select';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 8;
    $modversion['config'][$i]['options']        = array('_XADDRESSES_MI_TOPORDER1' => 1, '_XADDRESSES_MI_TOPORDER2' => 2, '_XADDRESSES_MI_TOPORDER3' => 3, '_XADDRESSES_MI_TOPORDER4' => 4, '_XADDRESSES_MI_TOPORDER5' => 5, '_XADDRESSES_MI_TOPORDER6' => 6, '_XADDRESSES_MI_TOPORDER7' => 7, '_XADDRESSES_MI_TOPORDER8' => 8);
    $modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'perpageliste';
    $modversion['config'][$i]['title']          = '_XADDRESSES_MI_PERPAGELISTE';
    $modversion['config'][$i]['description']    = '_XADDRESSES_MI_PERPAGELISTE_DESC';
    $modversion['config'][$i]['formtype']       = 'textbox';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 15;
    $modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'perpage';
    $modversion['config'][$i]['title']          = '_XADDRESSES_MI_PERPAGE';
    $modversion['config'][$i]['description']    = '_XADDRESSES_MI_PERPAGE_DESC';
    $modversion['config'][$i]['formtype']       = 'textbox';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 10;
    $modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'perpageadmin';
    $modversion['config'][$i]['title']          = '_XADDRESSES_MI_PERPAGEADMIN';
    $modversion['config'][$i]['description']    = '_XADDRESSES_MI_PERPAGEADMIN_DESC';
    $modversion['config'][$i]['formtype']       = 'textbox';
    $modversion['config'][$i]['valuetype']      = 'int';
    $modversion['config'][$i]['default']        = 15;
    $modversion["config"][$i]["category"]       = "global";
    $i++;
    $modversion['config'][$i]['name']           = 'showupdated';
    $modversion['config'][$i]['title']          = '_XADDRESSES_MI_SHOW_UPDATED';
    $modversion['config'][$i]['description']    = '_XADDRESSES_MI_SHOW_UPDATED_DESC';
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
$modversion['notification']['category'][$i]['title'] = _XADDRESSES_MI_GLOBAL_NOTIFY;
$modversion['notification']['category'][$i]['description'] = _XADDRESSES_MI_GLOBAL_NOTIFYDESC;
$modversion['notification']['category'][$i]['subscribe_from'] = array('index.php', 'locationcategoryview.php', 'locationview.php');
$i++;
$modversion['notification']['category'][$i]['name'] = 'category';
$modversion['notification']['category'][$i]['title'] = _XADDRESSES_MI_CATEGORY_NOTIFY;
$modversion['notification']['category'][$i]['description'] = _XADDRESSES_MI_CATEGORY_NOTIFYDESC;
$modversion['notification']['category'][$i]['subscribe_from'] = array('locationcategoryview.php', 'locationview.php');
$modversion['notification']['category'][$i]['item_name'] = 'cat_id';
$modversion['notification']['category'][$i]['allow_bookmark'] = true;
$i++;
$modversion['notification']['category'][$i]['name'] = 'location';
$modversion['notification']['category'][$i]['title'] = _XADDRESSES_MI_LOCATION_NOTIFY;
$modversion['notification']['category'][$i]['description'] = _XADDRESSES_MI_LOCATION_NOTIFYDESC;
$modversion['notification']['category'][$i]['subscribe_from'] = 'locationview.php';
$modversion['notification']['category'][$i]['item_name'] = 'loc_id';
$modversion['notification']['category'][$i]['allow_bookmark'] = true;

$i = 0;
$i++;
$modversion['notification']['event'][$i]['name'] = 'new_category';
$modversion['notification']['event'][$i]['category'] = 'global';
$modversion['notification']['event'][$i]['title'] = _XADDRESSES_MI_GLOBAL_NEWCATEGORY_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _XADDRESSES_MI_GLOBAL_NEWCATEGORY_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _XADDRESSES_MI_GLOBAL_NEWCATEGORY_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_newcategory_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _XADDRESSES_MI_GLOBAL_NEWCATEGORY_NOTIFYSBJ;
$i++;
$modversion['notification']['event'][$i]['name'] = 'location_modify';
$modversion['notification']['event'][$i]['category'] = 'global';
$modversion['notification']['event'][$i]['admin_only'] = true;
$modversion['notification']['event'][$i]['title'] = _XADDRESSES_MI_GLOBAL_LOCATIONMODIFY_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _XADDRESSES_MI_GLOBAL_LOCATIONMODIFY_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _XADDRESSES_MI_GLOBAL_LOCATIONMODIFY_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_locationmodify_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _XADDRESSES_MI_GLOBAL_LOCATIONMODIFY_NOTIFYSBJ;
$i++;
$modversion['notification']['event'][$i]['name'] = 'location_broken';
$modversion['notification']['event'][$i]['category'] = 'global';
$modversion['notification']['event'][$i]['admin_only'] = true;
$modversion['notification']['event'][$i]['title'] = _XADDRESSES_MI_GLOBAL_LOCATIONBROKEN_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _XADDRESSES_MI_GLOBAL_LOCATIONBROKEN_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _XADDRESSES_MI_GLOBAL_LOCATIONBROKEN_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_locationbroken_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _XADDRESSES_MI_GLOBAL_LOCATIONBROKEN_NOTIFYSBJ;
$i++;
$modversion['notification']['event'][$i]['name'] = 'location_submit';
$modversion['notification']['event'][$i]['category'] = 'global';
$modversion['notification']['event'][$i]['admin_only'] = true;
$modversion['notification']['event'][$i]['title'] = _XADDRESSES_MI_GLOBAL_LOCATIONSUBMIT_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _XADDRESSES_MI_GLOBAL_LOCATIONSUBMIT_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _XADDRESSES_MI_GLOBAL_LOCATIONSUBMIT_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_locationsubmit_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _XADDRESSES_MI_GLOBAL_LOCATIONSUBMIT_NOTIFYSBJ;
$i++;
$modversion['notification']['event'][$i]['name'] = 'new_location';
$modversion['notification']['event'][$i]['category'] = 'global';
$modversion['notification']['event'][$i]['title'] = _XADDRESSES_MI_GLOBAL_NEWLOCATION_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _XADDRESSES_MI_GLOBAL_NEWLOCATION_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _XADDRESSES_MI_GLOBAL_NEWLOCATION_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_newlocation_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _XADDRESSES_MI_GLOBAL_NEWLOCATION_NOTIFYSBJ;
$i++;
$modversion['notification']['event'][$i]['name'] = 'location_submit';
$modversion['notification']['event'][$i]['category'] = 'category';
$modversion['notification']['event'][$i]['admin_only'] = true;
$modversion['notification']['event'][$i]['title'] = _XADDRESSES_MI_CATEGORY_LOCATIONSUBMIT_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _XADDRESSES_MI_CATEGORY_LOCATIONSUBMIT_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _XADDRESSES_MI_CATEGORY_LOCATIONSUBMIT_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'category_locationsubmit_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _XADDRESSES_MI_CATEGORY_LOCATIONSUBMIT_NOTIFYSBJ;
$i++;
$modversion['notification']['event'][$i]['name'] = 'new_location';
$modversion['notification']['event'][$i]['category'] = 'category';
$modversion['notification']['event'][$i]['title'] = _XADDRESSES_MI_CATEGORY_NEWLOCATION_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _XADDRESSES_MI_CATEGORY_NEWLOCATION_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _XADDRESSES_MI_CATEGORY_NEWLOCATION_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'category_newlocation_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _XADDRESSES_MI_CATEGORY_NEWLOCATION_NOTIFYSBJ;
$i++;
$modversion['notification']['event'][$i]['name'] = 'approve';
$modversion['notification']['event'][$i]['category'] = 'location';
$modversion['notification']['event'][$i]['invisible'] = true;
$modversion['notification']['event'][$i]['title'] = _XADDRESSES_MI_LOCATION_APPROVE_NOTIFY;
$modversion['notification']['event'][$i]['caption'] = _XADDRESSES_MI_LOCATION_APPROVE_NOTIFYCAP;
$modversion['notification']['event'][$i]['description'] = _XADDRESSES_MI_LOCATION_APPROVE_NOTIFYDESC;
$modversion['notification']['event'][$i]['mail_template'] = 'location_approve_notify';
$modversion['notification']['event'][$i]['mail_subject'] = _XADDRESSES_MI_LOCATION_APPROVE_NOTIFYSBJ;
?>