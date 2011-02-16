<?php
if (!defined('XOOPS_ROOT_PATH')) die('XOOPS root path not defined');

$modversion['name'] = _XADDRESSES_MI_NAME;
$modversion['version'] = 1.0;
$modversion['description'] = _XADDRESSES_MI_DESC;
$modversion['author'] = "Rota Lucio";
$modversion['credits'] = "XOOPS";
$modversion['help'] = "help.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/xaddresses_slogo.png";
$modversion['dirname'] = "xaddresses";
//extra informations
$modversion["release"] = "16-02-2011";
$modversion["module_status"] = "Stable";
$modversion['support_site_url']	= "http://www.xoops.org";
$modversion['support_site_name'] = "www.xoops.org";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Scripts to run upon installation or update
$modversion['onInstall'] = 'include/install_function.php';
//$modversion['onUpdate'] = 'include/update_function.php';
$modversion['onUninstall'] = 'include/uninstall_function.php';

// Mysql file
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][] = "xaddresses_locationcategory";
$modversion['tables'][] = "xaddresses_location";

$modversion['tables'][] = "xaddresses_fieldcategory";
$modversion['tables'][] = "xaddresses_field";
$modversion['tables'][] = "xaddresses_visibility";

// IN PROGRESS
$modversion['tables'][] = "xaddresses_broken";

// TO DO
$modversion['tables'][] = "xaddresses_votedata";
$modversion['tables'][] = "xaddresses_marker";



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

// Menu
$modversion['hasMain'] = 1;
$modversion['sub'][1]['name'] = _XADDRESSES_MI_SMNAME1;
$modversion['sub'][1]['url'] = "submit.php";
$modversion['sub'][2]['name'] = _XADDRESSES_MI_SMNAME2;
$modversion['sub'][2]['url'] = "search.php";

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "xaddresses_search";

// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'loc_id';
$modversion['comments']['pageName'] = 'singlefile.php';
$modversion['comments']['extraParams'] = array('cid');
$modversion['comments']['callbackFile'] = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'xaddresses_com_approve';
$modversion['comments']['callback']['update'] = 'xaddresses_com_update';



// Templates
$i = 0;
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_locationbroken.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_location.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_index.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_locationmod.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_locationrate.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_locationview.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_locationsubmit.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_categoryview.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_list.html';
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
$modversion['templates'][$i]['file'] = 'xaddresses_admin_locationmodifiedlist.html';
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
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_admin_steplist.html';
$modversion['templates'][$i]['description'] = '';
//$modversion['templates'][$i]['type'] = 'admin';
$i++;
$modversion['templates'][$i]['file'] = 'xaddresses_admin_visibility.html';
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
$i = 0;
$i++;
$modversion['config'][$i]['name'] = 'popular';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_POPULAR';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_POPULARDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 100;
$i++;
$modversion['config'][$i]['name'] = 'nbsouscat';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_SUBCATPARENT';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_SUBCATPARENTDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 5;
$i++;
$modversion['config'][$i]['name'] = 'bldate';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_BLDATE';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_BLDATEDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;
$i++;
$modversion['config'][$i]['name'] = 'blpop';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_BLPOP';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_BLPOPDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;
$i++;
$modversion['config'][$i]['name'] = 'blrating';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_BLRATING';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_BLRATINGDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;
$i++;
$modversion['config'][$i]['name'] = 'nbbl';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_NBBL';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_NBBLDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 5;
$i++;
$modversion['config'][$i]['name'] = 'longbl';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_LONGBL';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_LONGBLDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 20;
$i++;
$modversion['config'][$i]['name'] = 'usetellafriend';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_USETELLAFRIEND';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_USETELLAFRIENDDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$i++;
$modversion['config'][$i]['name'] = 'usetag';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_USETAG';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_USETAGDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$i++;
$modversion['config'][$i]['name'] = 'autoapprove';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_AUTOAPPROVE';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_AUTOAPPROVEDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$i++;
$modversion['config'][$i]['name'] = 'useshots';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_USESHOTS';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_USESHOTSDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;
$i++;
$modversion['config'][$i]['name'] = 'shotwidth';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_SHOTWIDTH';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_SHOTWIDTHDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 90;
$i++;
$modversion['config'][$i]['name'] = 'check_host';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_CHECKHOST';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_CHECKHOSTDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$i++;
$modversion['config'][$i]['name'] = 'maxuploadsize';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_MAXUPLOAD_SIZE';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_MAXUPLOAD_SIZEDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1048576;
$i++;
include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
$modversion["config"][$i]["name"]           = "editor";
$modversion["config"][$i]["title"]          = "_XADDRESSES_MI_FORM_OPTIONS";
$modversion["config"][$i]["description"]    = "_XADDRESSES_MI_FORM_OPTIONSDSC";
$modversion["config"][$i]["formtype"]       = "select";
$modversion["config"][$i]["valuetype"]      = "text";
$modversion["config"][$i]["default"]        = "dhtmltextarea";
$modversion["config"][$i]["options"]        = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . "/class/xoopseditor");
$modversion["config"][$i]["category"]       = "global";
$i++;
$modversion['config'][$i]['name'] = 'toporder';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_TOPORDER';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_TOPORDERDSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;
$modversion['config'][$i]['options'] = array('_XADDRESSES_MI_TOPORDER1' => 1, '_XADDRESSES_MI_TOPORDER2' => 2, '_XADDRESSES_MI_TOPORDER3' => 3, '_XADDRESSES_MI_TOPORDER4' => 4, '_XADDRESSES_MI_TOPORDER5' => 5, '_XADDRESSES_MI_TOPORDER6' => 6, '_XADDRESSES_MI_TOPORDER7' => 7, '_XADDRESSES_MI_TOPORDER8' => 8);
$i++;
$modversion['config'][$i]['name'] = 'newdownloads';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_NEWDLS';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_NEWDLSDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 10;
$i++;
$modversion['config'][$i]['name'] = 'searchorder';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_SEARCHORDER';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_SEARCHORDERDSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 8;
$modversion['config'][$i]['options'] = array('_XADDRESSES_MI_TOPORDER1' => 1, '_XADDRESSES_MI_TOPORDER2' => 2, '_XADDRESSES_MI_TOPORDER3' => 3, '_XADDRESSES_MI_TOPORDER4' => 4, '_XADDRESSES_MI_TOPORDER5' => 5, '_XADDRESSES_MI_TOPORDER6' => 6, '_XADDRESSES_MI_TOPORDER7' => 7, '_XADDRESSES_MI_TOPORDER8' => 8);
$i++;
$modversion['config'][$i]['name'] = 'perpageliste';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_PERPAGELISTE';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_PERPAGELISTEDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 15;
$i++;
$modversion['config'][$i]['name'] = 'perpage';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_PERPAGE';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_PERPAGEDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 10;
$i++;
$modversion['config'][$i]['name'] = 'perpageadmin';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_PERPAGEADMIN';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_PERPAGEADMINDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 15;
$i++;
$modversion['config'][$i]['name'] = 'autosummary';
$modversion['config'][$i]['title'] = "_XADDRESSES_MI_AUTO_SUMMARY";
$modversion['config'][$i]['description'] = "_XADDRESSES_MI_AUTO_SUMMARYDSC";
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$i++;
$modversion['config'][$i]['name'] = 'showupdated';
$modversion['config'][$i]['title'] = '_XADDRESSES_MI_SHOW_UPDATED';
$modversion['config'][$i]['description'] = '_XADDRESSES_MI_SHOW_UPDATEDDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;


// Notifications
$modversion['hasNotification'] = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'xaddresses_notify_iteminfo';

$modversion['notification']['category'][1]['name'] = 'global';
$modversion['notification']['category'][1]['title'] = _XADDRESSES_MI_GLOBAL_NOTIFY;
$modversion['notification']['category'][1]['description'] = _XADDRESSES_MI_GLOBAL_NOTIFYDSC;
$modversion['notification']['category'][1]['subscribe_from'] = array('index.php','viewcat.php','singlefile.php');

$modversion['notification']['category'][2]['name'] = 'category';
$modversion['notification']['category'][2]['title'] = _XADDRESSES_MI_CATEGORY_NOTIFY;
$modversion['notification']['category'][2]['description'] = _XADDRESSES_MI_CATEGORY_NOTIFYDSC;
$modversion['notification']['category'][2]['subscribe_from'] = array('viewcat.php', 'singlefile.php');
$modversion['notification']['category'][2]['item_name'] = 'cid';
$modversion['notification']['category'][2]['allow_bookmark'] = 1;

$modversion['notification']['category'][3]['name'] = 'address';
$modversion['notification']['category'][3]['title'] = _XADDRESSES_MI_FILE_NOTIFY;
$modversion['notification']['category'][3]['description'] = _XADDRESSES_MI_FILE_NOTIFYDSC;
$modversion['notification']['category'][3]['subscribe_from'] = 'singlefile.php';
$modversion['notification']['category'][3]['item_name'] = 'loc_id';
$modversion['notification']['category'][3]['allow_bookmark'] = 1;

$modversion['notification']['event'][1]['name'] = 'new_category';
$modversion['notification']['event'][1]['category'] = 'global';
$modversion['notification']['event'][1]['title'] = _XADDRESSES_MI_GLOBAL_NEWCATEGORY_NOTIFY;
$modversion['notification']['event'][1]['caption'] = _XADDRESSES_MI_GLOBAL_NEWCATEGORY_NOTIFYCAP;
$modversion['notification']['event'][1]['description'] = _XADDRESSES_MI_GLOBAL_NEWCATEGORY_NOTIFYDSC;
$modversion['notification']['event'][1]['mail_template'] = 'global_newcategory_notify';
$modversion['notification']['event'][1]['mail_subject'] = _XADDRESSES_MI_GLOBAL_NEWCATEGORY_NOTIFYSBJ;

$modversion['notification']['event'][2]['name'] = 'address_modify';
$modversion['notification']['event'][2]['category'] = 'global';
$modversion['notification']['event'][2]['admin_only'] = 1;
$modversion['notification']['event'][2]['title'] = _XADDRESSES_MI_GLOBAL_FILEMODIFY_NOTIFY;
$modversion['notification']['event'][2]['caption'] = _XADDRESSES_MI_GLOBAL_FILEMODIFY_NOTIFYCAP;
$modversion['notification']['event'][2]['description'] = _XADDRESSES_MI_GLOBAL_FILEMODIFY_NOTIFYDSC;
$modversion['notification']['event'][2]['mail_template'] = 'global_addressmodify_notify';
$modversion['notification']['event'][2]['mail_subject'] = _XADDRESSES_MI_GLOBAL_FILEMODIFY_NOTIFYSBJ;

$modversion['notification']['event'][3]['name'] = 'address_broken';
$modversion['notification']['event'][3]['category'] = 'global';
$modversion['notification']['event'][3]['admin_only'] = 1;
$modversion['notification']['event'][3]['title'] = _XADDRESSES_MI_GLOBAL_FILEBROKEN_NOTIFY;
$modversion['notification']['event'][3]['caption'] = _XADDRESSES_MI_GLOBAL_FILEBROKEN_NOTIFYCAP;
$modversion['notification']['event'][3]['description'] = _XADDRESSES_MI_GLOBAL_FILEBROKEN_NOTIFYDSC;
$modversion['notification']['event'][3]['mail_template'] = 'global_addressbroken_notify';
$modversion['notification']['event'][3]['mail_subject'] = _XADDRESSES_MI_GLOBAL_FILEBROKEN_NOTIFYSBJ;

$modversion['notification']['event'][4]['name'] = 'address_submit';
$modversion['notification']['event'][4]['category'] = 'global';
$modversion['notification']['event'][4]['admin_only'] = 1;
$modversion['notification']['event'][4]['title'] = _XADDRESSES_MI_GLOBAL_FILESUBMIT_NOTIFY;
$modversion['notification']['event'][4]['caption'] = _XADDRESSES_MI_GLOBAL_FILESUBMIT_NOTIFYCAP;
$modversion['notification']['event'][4]['description'] = _XADDRESSES_MI_GLOBAL_FILESUBMIT_NOTIFYDSC;
$modversion['notification']['event'][4]['mail_template'] = 'global_addresssubmit_notify';
$modversion['notification']['event'][4]['mail_subject'] = _XADDRESSES_MI_GLOBAL_FILESUBMIT_NOTIFYSBJ;

$modversion['notification']['event'][5]['name'] = 'new_address';
$modversion['notification']['event'][5]['category'] = 'global';
$modversion['notification']['event'][5]['title'] = _XADDRESSES_MI_GLOBAL_NEWFILE_NOTIFY;
$modversion['notification']['event'][5]['caption'] = _XADDRESSES_MI_GLOBAL_NEWFILE_NOTIFYCAP;
$modversion['notification']['event'][5]['description'] = _XADDRESSES_MI_GLOBAL_NEWFILE_NOTIFYDSC;
$modversion['notification']['event'][5]['mail_template'] = 'global_newadress_notify';
$modversion['notification']['event'][5]['mail_subject'] = _XADDRESSES_MI_GLOBAL_NEWFILE_NOTIFYSBJ;

$modversion['notification']['event'][6]['name'] = 'address_submit';
$modversion['notification']['event'][6]['category'] = 'category';
$modversion['notification']['event'][6]['admin_only'] = 1;
$modversion['notification']['event'][6]['title'] = _XADDRESSES_MI_CATEGORY_FILESUBMIT_NOTIFY;
$modversion['notification']['event'][6]['caption'] = _XADDRESSES_MI_CATEGORY_FILESUBMIT_NOTIFYCAP;
$modversion['notification']['event'][6]['description'] = _XADDRESSES_MI_CATEGORY_FILESUBMIT_NOTIFYDSC;
$modversion['notification']['event'][6]['mail_template'] = 'category_addresssubmit_notify';
$modversion['notification']['event'][6]['mail_subject'] = _XADDRESSES_MI_CATEGORY_FILESUBMIT_NOTIFYSBJ;

$modversion['notification']['event'][7]['name'] = 'new_address';
$modversion['notification']['event'][7]['category'] = 'category';
$modversion['notification']['event'][7]['title'] = _XADDRESSES_MI_CATEGORY_NEWFILE_NOTIFY;
$modversion['notification']['event'][7]['caption'] = _XADDRESSES_MI_CATEGORY_NEWFILE_NOTIFYCAP;
$modversion['notification']['event'][7]['description'] = _XADDRESSES_MI_CATEGORY_NEWFILE_NOTIFYDSC;
$modversion['notification']['event'][7]['mail_template'] = 'category_newaddress_notify';
$modversion['notification']['event'][7]['mail_subject'] = _XADDRESSES_MI_CATEGORY_NEWFILE_NOTIFYSBJ;

$modversion['notification']['event'][8]['name'] = 'approve';
$modversion['notification']['event'][8]['category'] = 'address';
$modversion['notification']['event'][8]['invisible'] = 1;
$modversion['notification']['event'][8]['title'] = _XADDRESSES_MI_FILE_APPROVE_NOTIFY;
$modversion['notification']['event'][8]['caption'] = _XADDRESSES_MI_FILE_APPROVE_NOTIFYCAP;
$modversion['notification']['event'][8]['description'] = _XADDRESSES_MI_FILE_APPROVE_NOTIFYDSC;
$modversion['notification']['event'][8]['mail_template'] = 'address_approve_notify';
$modversion['notification']['event'][8]['mail_subject'] = _XADDRESSES_MI_FILE_APPROVE_NOTIFYSBJ;

?>
