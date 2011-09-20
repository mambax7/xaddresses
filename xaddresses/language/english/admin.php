<?php
// admin/index.php
define("_XADDRESSES_AM_INDEX_INFO", "Module Informations");
define("_XADDRESSES_AM_INDEX_SCONFIG", "<b>Information taken from Module Preferences:</b>");

define("_XADDRESSES_AM_INDEX_DATABASE", "<b>Information taken from Module Database:</b>");
define("_XADDRESSES_AM_INDEX_COUNTCATEGORIES","There are <b><span style='color : Red'> %s </span></b> Location Categories in our database");
define("_XADDRESSES_AM_INDEX_COUNTLOCATIONS","There are <b><span style='color : Red'> %s </span></b> Locations in our database");
define("_XADDRESSES_AM_INDEX_COUNTBROKEN","There are <b><span style='color : Red'> %s </span></b> broken Locations reports");
define("_XADDRESSES_AM_INDEX_COUNTWAITING","There are <b><span style='color : Red'> %s </span></b> Locations waiting for approval");
define("_XADDRESSES_AM_INDEX_COUNTMODIFIED","There are <b><span style='color : Red'> %s </span></b> Location info modification requests");

define("_XADDRESSES_AM_INDEX_SERVERSTATUS", "Server Status");
define("_XADDRESSES_AM_INDEX_SPHPINI", "<b>Information taken from PHP ini File:</b>");
define("_XADDRESSES_AM_INDEX_SERVERPATH", "Server Path to XOOPS Root: ");
define("_XADDRESSES_AM_INDEX_SAFEMODESTATUS", "Safe Mode Status: ");
define("_XADDRESSES_AM_INDEX_REGISTERGLOBALS", "Register Globals: ");
define("_XADDRESSES_AM_INDEX_MAGICQUOTESGPC", "'magic_quotes_gpc' Status: ");
define("_XADDRESSES_AM_INDEX_SERVERUPLOADSTATUS", "Server Uploads Status: ");
define("_XADDRESSES_AM_INDEX_MAXUPLOADSIZE", "Max Upload Size Permitted: ");
define("_XADDRESSES_AM_INDEX_MAXPOSTSIZE", "Max Post Size Permitted: ");
define("_XADDRESSES_AM_INDEX_SAFEMODEPROBLEMS", " (This May Cause Problems)");
define("_XADDRESSES_AM_INDEX_GDLIBSTATUS", "GD Library Support: ");
define("_XADDRESSES_AM_INDEX_ZIPLIBSTATUS", "Zip Library Support (ZipArchive class): ");
define("_XADDRESSES_AM_INDEX_GDLIBVERSION", "GD Library Version: ");
define("_XADDRESSES_AM_INDEX_GDON", "<b>Enabled</b>");
define("_XADDRESSES_AM_INDEX_GDOFF", "<b>Disabled</b>");
define("_XADDRESSES_AM_INDEX_ZIPON", "<b>Enabled</b>");
define("_XADDRESSES_AM_INDEX_ZIPOFF", "<b>Disabled</b>");
define("_XADDRESSES_AM_INDEX_OFF", "<b>OFF</b>");
define("_XADDRESSES_AM_INDEX_ON", "<b>ON</b>");



//admin/about.php
define("_XADDRESSES_AM_ABOUT_AUTHOR", "Author");
define("_XADDRESSES_AM_ABOUT_CHANGELOG", "Change log");
define("_XADDRESSES_AM_ABOUT_CREDITS", "Credits");
define("_XADDRESSES_AM_ABOUT_LICENSE", "License");
define("_XADDRESSES_AM_ABOUT_MODULEINFOS", "Module Informations");
define("_XADDRESSES_AM_ABOUT_MODULEWEBSITE", "Support Web Site");
define("_XADDRESSES_AM_ABOUT_AUTHORINFOS", "Author Informations");
define("_XADDRESSES_AM_ABOUT_AUTHORWEBSITE", "Web Site");
define("_XADDRESSES_AM_ABOUT_AUTHOREMAIL", "Email");
define("_XADDRESSES_AM_ABOUT_RELEASEDATE", "Date of launch");
define("_XADDRESSES_AM_ABOUT_STATUS", "Status");
define("_XADDRESSES_AM_ABOUT_DESCRIPTION", "Module Description &quot;description.html&quot;");



//admin/help.php
define("_XADDRESSES_AM_ABOUT_HELP","Module Help &quot;help.html&quot;");






// IN PROGRESS




define("_XADDRESSES_AM_ACTION","Action");


//admin/item.php
//class/location.php
define("_XADDRESSES_AM_LOC_NOCAT", "Lacation cannot be create: There are no Categories created yet! Please create a Category first.");
define("_XADDRESSES_AM_LOC_NEW","New location");
define("_XADDRESSES_AM_LOC_LIST","Locations list");
define("_XADDRESSES_AM_LOC_WAITING","Locations waiting for validation");
define("_XADDRESSES_AM_LOC_BROKEN","Broken locations");
define("_XADDRESSES_AM_LOC_MODIFIED","Modified locations");
define("_XADDRESSES_AM_LOC_SEARCH","Search");

define("_XADDRESSES_AM_LOCATION","Location");
define("_XADDRESSES_AM_LOC_ADD","Add new Location");
define("_XADDRESSES_AM_LOC_EDIT","Modify Location");
define("_XADDRESSES_AM_LOC_ID","Id");
define("_XADDRESSES_AM_LOC_TITLE","Location title");
define("_XADDRESSES_AM_LOC_TITLE_DESC","Text or HTML format");
define("_XADDRESSES_AM_LOC_COORDINATES","Coordinates");
define("_XADDRESSES_AM_LOC_COORDINATES_DESC","Latitude, Longitude and Zoom level");
define("_XADDRESSES_AM_LOC_CAT","Category");
define("_XADDRESSES_AM_LOC_CAT_DESC","Choose the Location category");
define("_XADDRESSES_AM_LOC_WEIGHT","Weight");
define("_XADDRESSES_AM_LOC_DATE","Date");
define("_XADDRESSES_AM_LOC_SUBMITTER","Submitter");
define("_XADDRESSES_AM_LOC_STATUS","Status");
define("_XADDRESSES_AM_LOC_STATUS_OK","OK");
define("_XADDRESSES_AM_LOC_STATUS_NOT_OK","NOT OK");
define("_XADDRESSES_AM_LOC_STATUS_VALIDATED","Validated");
define("_XADDRESSES_AM_LOC_STATUS_NOT_VALIDATED","Not Validated");
define("_XADDRESSES_AM_LOC_STATUS_BROKEN","Reported as wrong");
define("_XADDRESSES_AM_LOC_STATUS_MODIFIED","Proposed correction");
define("_XADDRESSES_AM_LOC_HITS","Hits");
define("_XADDRESSES_AM_LOC_RATING","Rating");
define("_XADDRESSES_AM_DISPLAY","Display");
define("_XADDRESSES_AM_LOC_UNLOCK","Activate");
define("_XADDRESSES_AM_LOC_LOCK","Dectivate");

//locationbroken.php
//class/broken.php
define("_XADDRESSES_AM_LOC_BROKEN_NEW","Repport Broken Location");
define("_XADDRESSES_AM_LOC_BROKEN_EDIT","Edit Broken Location");
define("_XADDRESSES_AM_LOC_BROKEN_DESCRIPTION","Description");
define("_XADDRESSES_AM_LOC_BROKEN_SENDER","Repport Author");
define("_XADDRESSES_AM_LOC_BROKEN_SURDEL","Are you sure you want to delete this repport?");


//admin/itemcategory.php
//class/locationcategory.php
define("_XADDRESSES_AM_CAT_NEW","New Location Category");
define("_XADDRESSES_AM_CAT_LIST","Location Categories list");

define("_XADDRESSES_AM_CATEGORY","Category");
define("_XADDRESSES_AM_CAT_ADD","Add new Category");
define("_XADDRESSES_AM_CAT_EDIT","Modify Category");
define("_XADDRESSES_AM_CAT_ID","Id");
define("_XADDRESSES_AM_CAT_TITLE","Title");
define("_XADDRESSES_AM_CAT_DESCRIPTION","Description");
define("_XADDRESSES_AM_CAT_IMG","Category image");
define("_XADDRESSES_AM_CAT_PARENT","In the category");
define("_XADDRESSES_AM_CAT_WEIGHT","Weight");


// include/form.php xaddresses_getFieldForm
define("_XADDRESSES_AM_ADD_FIELD","Add new field");
define("_XADDRESSES_AM_EDIT_FIELD","Edit field");

define("_XADDRESSES_AM_FIELD", "Field");
define("_XADDRESSES_AM_FIELDS", "Fields");

define("_XADDRESSES_AM_FIELD_TITLE","Title");
define("_XADDRESSES_AM_FIELD_TITLE_DESC","// IN PROGRESS");
define("_XADDRESSES_AM_FIELD_DESCRIPTION", "Description");
define("_XADDRESSES_AM_FIELD_DESCRIPTION_DESC", "// IN PROGRESS");
define("_XADDRESSES_AM_FIELD_CATEGORY", "Category");
define("_XADDRESSES_AM_FIELD_CATEGORY_DESC", "// IN PROGRESS");
define("_XADDRESSES_AM_FIELD_CATEGORY_DEFAULT", "Default");
define("_XADDRESSES_AM_FIELD_CATEGORY_DEFAULT_DESC", "// IN PROGRESS");
define("_XADDRESSES_AM_FIELD_WEIGHT", "Weight");
define("_XADDRESSES_AM_FIELD_WEIGHT_DESC", "// IN PROGRESS");
define("_XADDRESSES_AM_FIELD_NAME", "Field Name");
define("_XADDRESSES_AM_FIELD_NAME_DESC", "Field name in database<br />' ' will be automatically replaced by '_'<br />// IN PROGRESS");
define("_XADDRESSES_AM_FIELD_TYPE", "Field type");
define("_XADDRESSES_AM_FIELD_TYPE_DESC", "// IN PROGRESS");

define("_XADDRESSES_AM_FIELD_CHECKBOX", "Checkbox");
define("_XADDRESSES_AM_FIELD_GROUP", "Group Select");
define("_XADDRESSES_AM_FIELD_GROUPMULTI", "Group Multi Select");
define("_XADDRESSES_AM_FIELD_LANGUAGE", "Language Select");
define("_XADDRESSES_AM_FIELD_RADIO", "Radio Buttons");
define("_XADDRESSES_AM_FIELD_SELECT", "Select");
define("_XADDRESSES_AM_FIELD_SELECTMULTI", "Multi Select");
define("_XADDRESSES_AM_FIELD_TEXTAREA", "Text Area");
define("_XADDRESSES_AM_FIELD_DHTMLTEXTAREA", "DHTML Text Area");
define("_XADDRESSES_AM_FIELD_TEXTBOX", "Text Field");
define("_XADDRESSES_AM_FIELD_TIMEZONE", "Timezone");
define("_XADDRESSES_AM_FIELD_YESNO", "Radio Yes/No");
define("_XADDRESSES_AM_FIELD_DATE", "Date");
define("_XADDRESSES_AM_FIELD_AUTOTEXT", "Auto Text");
define("_XADDRESSES_AM_FIELD_DATETIME", "Date and Time");
define("_XADDRESSES_AM_FIELD_LONGDATE", "Long Date");
define("_XADDRESSES_AM_FIELD_XOOPSIMAGE", "Xoops Image");
define("_XADDRESSES_AM_FIELD_MULTIPLEXOOPSIMAGE", "Multiple Xoops Images");
define("_XADDRESSES_AM_FIELD_FILE", "File");
define("_XADDRESSES_AM_FIELD_MULTIPLEFILE", "Multiple Files");
define("_XADDRESSES_AM_FIELD_KMLMAP", "Kml Map");
define("_XADDRESSES_AM_FIELD_THEME", "Theme");
define("_XADDRESSES_AM_FIELD_RANK", "Rank");

define("_XADDRESSES_AM_FIELD_VALUETYPE","Field type");
define("_XADDRESSES_AM_FIELD_VALUETYPE_DESC","// IN PROGRESS");

define("_XADDRESSES_AM_FIELD_ARRAY", "Array");
define("_XADDRESSES_AM_FIELD_EMAIL", "Email");
define("_XADDRESSES_AM_FIELD_INT", "Integer");
define("_XADDRESSES_AM_FIELD_TXTAREA", "Text Area");
define("_XADDRESSES_AM_FIELD_TXTBOX", "Text field");
define("_XADDRESSES_AM_FIELD_URL", "URL");
define("_XADDRESSES_AM_FIELD_OTHER", "Other");
define("_XADDRESSES_AM_FIELD_FLOAT", "Floating Point");
define("_XADDRESSES_AM_FIELD_DECIMAL", "Decimal Number");
define("_XADDRESSES_AM_FIELD_UNICODE_ARRAY", "Unicode Array");
define("_XADDRESSES_AM_FIELD_UNICODE_EMAIL", "Unicode Email");
define("_XADDRESSES_AM_FIELD_UNICODE_TXTAREA", "Unicode Text Area");
define("_XADDRESSES_AM_FIELD_UNICODE_TXTBOX", "Unicode Text field");
define("_XADDRESSES_AM_FIELD_UNICODE_URL", "Unicode URL");

define("_XADDRESSES_AM_FIELD_NOTNULL", "Not Null?");
define("_XADDRESSES_AM_FIELD_NOTNULL_DESC", "Not Null?");

//define("_XADDRESSES_AM_FIELD_ADDEXTRA", "Maximum Length");
//define("_XADDRESSES_AM_FIELD_ADDEXTRA_DESC", "// IN PROGRESS");
define("_XADDRESSES_AM_FIELD_LENGTH", "Field Length In Form");
define("_XADDRESSES_AM_FIELD_LENGTH_DESC", "// IN PROGRESS");
define("_XADDRESSES_AM_FIELD_MAXLENGTH", "Maximum Field Length");
define("_XADDRESSES_AM_FIELD_MAXLENGTH_DESC", "// IN PROGRESS");
define("_XADDRESSES_AM_FIELD_DEFAULT", "Default");
define("_XADDRESSES_AM_FIELD_DEFAULT_DESC", "// IN PROGRESS");
define("_XADDRESSES_AM_FIELD_REQUIRED", "Required?");
define("_XADDRESSES_AM_FIELD_REQUIRED_DESC", "// IN PROGRESS");

define("_XADDRESSES_AM_FIELD_VIEWABLE", "Vieawble by");
define("_XADDRESSES_AM_FIELD_VIEWABLE_DESC", "// IN PROGRESS");
define("_XADDRESSES_AM_FIELD_EDITABLE", "Editable by");
define("_XADDRESSES_AM_FIELD_EDITABLE_DESC", "// IN PROGRESS");
define("_XADDRESSES_AM_FIELD_SEARCHABLE", "Searchable by");
define("_XADDRESSES_AM_FIELD_SEARCHABLE_DESC", "// IN PROGRESS");
define("_XADDRESSES_AM_FIELD_EXPORTABLE", "Exportable by");
define("_XADDRESSES_AM_FIELD_EXPORTABLE_DESC", "// IN PROGRESS");


//admin/field.php
define("_XADDRESSES_AM_FIELD_NEW","New field");
define("_XADDRESSES_AM_FIELD_LIST","Fields List");

//admin/fieldcategory.php
define("_XADDRESSES_AM_FIELDCAT_NEW","New Field Category");
define("_XADDRESSES_AM_FIELDCAT_LIST","Field Categories list");

define("_XADDRESSES_AM_FIELDCAT","Field Category");
define("_XADDRESSES_AM_FIELDCAT_EDIT","Edit Field Category");

define("_XADDRESSES_AM_FIELDCAT_TITLE","Title");
define("_XADDRESSES_AM_FIELDCAT_TITLE_DESC","// IN PROGRESS");
define("_XADDRESSES_AM_FIELDCAT_DESCRIPTION", "Description");
define("_XADDRESSES_AM_FIELDCAT_DESCRIPTION_DESC", "// IN PROGRESS");
define("_XADDRESSES_AM_FIELDCAT_WEIGHT","Weight");
define("_XADDRESSES_AM_FIELDCAT_WEIGHT_DESC","// IN PROGRESS");



// admin/permissions.php
define("_XADDRESSES_AM_PERM_ITEM_PERMISSIONS", "Addresses Permissions");
define("_XADDRESSES_AM_PERM_FIELD_PERMISSIONS", "Fields Permissions");
define("_XADDRESSES_AM_PERM_EXTRA_PERMISSIONS", "Extra Permissions");

define("_XADDRESSES_AM_PERM_NOCAT", "Permission cannot be set: There are no Categories created yet! Please create a Category first.");
define("_XADDRESSES_AM_PERM_NOCAT_DESC", "");
define("_XADDRESSES_AM_PERM_VIEW", "View Permission");
define("_XADDRESSES_AM_PERM_VIEW_DSC", "Choose groups than can view Locations in categories");
define("_XADDRESSES_AM_PERM_SUBMIT", "Submit Permission");
define("_XADDRESSES_AM_PERM_SUBMIT_DSC", "Choose groups that can submit Locations to categories");
define("_XADDRESSES_AM_PERM_EDIT", "Edit Permission");
define("_XADDRESSES_AM_PERM_EDIT_DSC", "Choose groups than can edit Locations in categories");
define("_XADDRESSES_AM_PERM_DELETE", "Delete Permission");
define("_XADDRESSES_AM_PERM_DELETE_DSC", "Choose groups than can delete Locations in categories");

define("_XADDRESSES_AM_PERM_VIEWFIELD", "View Fields Permission");
define("_XADDRESSES_AM_PERM_VIEWFIELD_DSC", "Choose fields than groups can view");
define("_XADDRESSES_AM_PERM_EDITFIELD", "Edit Fields Permission");
define("_XADDRESSES_AM_PERM_EDITFIELD_DSC", "Choose fields than groups can edit");
define("_XADDRESSES_AM_PERM_SEARCHFIELD", "Search in Fields Permission");
define("_XADDRESSES_AM_PERM_SEARCHFIELD_DSC", "Choose fields than groups can search");
define("_XADDRESSES_AM_PERM_EXPORTFIELD", "Export Fields Permission");
define("_XADDRESSES_AM_PERM_EXPORTFIELD_DSC", "Choose fields than groups can export");

define("_XADDRESSES_AM_PERM_OTHERS", "Other permissions");
define("_XADDRESSES_AM_PERM_OTHERS_DSC", "Select groups that can:");
//define("_XADDRESSES_AM_PERMISSIONS_1","// IN PROGRESS");
//define("_XADDRESSES_AM_PERMISSIONS_2","// IN PROGRESS");
//define("_XADDRESSES_AM_PERMISSIONS_4","Submit a location");
//define("_XADDRESSES_AM_PERMISSIONS_8","Submit a modification");
define("_XADDRESSES_AM_PERMISSIONS_16","Tell a friend");
define("_XADDRESSES_AM_PERMISSIONS_32","Vote a location");
//define("_XADDRESSES_AM_PERMISSIONS_64","// IN PROGRESS");
//define("_XADDRESSES_AM_PERMISSIONS_128","// IN PROGRESS");
//define("_XADDRESSES_AM_PERMISSIONS_256","// IN PROGRESS");

// IN PROGRESS FROM HERE --------------------------------------------------------------------------




//version  1.1
define("_XADDRESSES_AM_INDEX_UPDATE_INFO","Latest version of Xaddresses");
define("_XADDRESSES_AM_INDEX_VERSION_OK","You have the latest version of Xaddresses %s");
define("_XADDRESSES_AM_INDEX_CHANGELOG","<b>Changelog</b>");
define("_XADDRESSES_AM_INDEX_VERSION_NOT_OK","There is a new version of Xaddresses %s, you can download <a href='http://www.tdmxoops.net/modules/TDMDownloads' target='_blank'>here</a>");
define("_XADDRESSES_AM_INDEX_VERSION_ALLOWURLFOPEN","To determine if a new version of TDMDownloads exists, you must have &#039;allow_url_fopen&#039; to &#039;on&#039;");
define("_XADDRESSES_AM_INDEX_VERSION_FICHIER_KO","The file version management module of TDM is currently unavailable");

//categories.php

define("_XADDRESSES_AM_DELADDRESSES","with the following addresses:");
define("_XADDRESSES_AM_DELSOUSCAT","The following categories will be totally deleted:");
define("_XADDRESSES_AM_ADDRESSESINCAT","address(es) in this category");
define("_XADDRESSES_AM_THEREIS","there are");


//addresses.php


define("_XADDRESSES_AM_ADDRESSES_VOTESANONYME","Votes by anonymous (total of votes : %s)");
define("_XADDRESSES_AM_ADDRESSES_VOTESUSER","Votes by users (total of votes : %s)");
define("_XADDRESSES_AM_ADDRESSES_VOTE_USER","Users");
define("_XADDRESSES_AM_ADDRESSES_VOTE_IP","IP Address");




//modified.php
define("_XADDRESSES_AM_MODIFIED_MOD","Submited by;");
define("_XADDRESSES_AM_MODIFIED_ORIGINAL","Original");
define("_XADDRESSES_AM_MODIFIED_SURDEL","Are you sure you want to delete this address modification request?");





// Import.php
    define("_XADDRESSES_AM_IMPORT1","Import");
    define("_XADDRESSES_AM_IMPORT_CAT_IMP","Categories: '%s' imported");
    define("_XADDRESSES_AM_IMPORT_CONF_MYDOWNLOADS","Are you sure you want to import data from Mydownloads module to TDMDownloads");
    define("_XADDRESSES_AM_IMPORT_CONF_WFDOWNLOADS","Are you sure you want to import data from WF-Downloads modules to TDMDownloads");
    define("_XADDRESSES_AM_IMPORT_DONT_DOWNLOADS","there is no files to import");
    define("_XADDRESSES_AM_IMPORT_DONT_TOPIC","there is no files to import");
    define("_XADDRESSES_AM_IMPORT_DOWNLOADS","files Importation");
    define("_XADDRESSES_AM_IMPORT_DOWNLOADS_IMP","files: '%s' imported;");
    define("_XADDRESSES_AM_IMPORT_ERROR","Select Upload Directory (the path)");
    define("_XADDRESSES_AM_IMPORT_ERROR_DATA","Error during the importation of data");
    define("_XADDRESSES_AM_IMPORT_MYDOWNLOADS","Import from Mydownloads");
    define("_XADDRESSES_AM_IMPORT_MYDOWNLOADS_PATH","Select Upload Directory (the path) for screen shots of Mydownloads");
    define("_XADDRESSES_AM_IMPORT_MYDOWNLOADS_URL","Choose the corresponding URL  for screen shots of Mydownloads");
    define("_XADDRESSES_AM_IMPORT_NB_CAT","There are %s categories to import");
    define("_XADDRESSES_AM_IMPORT_NB_DOWNLOADS","There are %s files to import");
    define("_XADDRESSES_AM_IMPORT_NUMBER","Data to import");
    define("_XADDRESSES_AM_IMPORT_OK","Import successfuly done !!!");
    define("_XADDRESSES_AM_IMPORT_VOTE_IMP","VOTES: '%s' imported;");
    define("_XADDRESSES_AM_IMPORT_WARNING","<span style='color:#FF0000; font-size:16px; font-weight:bold'>Attention !</span><br /><br /> Importation will delete all data in TDMDownloads. It's highly recomended that you make a backup of your data, also of your website.<br /><br />TDM is not responsible if you lose your data. It happens that screen shots cant be copied.");
    define("_XADDRESSES_AM_IMPORT_WFDOWNLOADS","Import from WF Downloads(only for V3.2 RC2)");
    define("_XADDRESSES_AM_IMPORT_WFDOWNLOADS_CATIMG","Select Upload Directory (the path) for categories inages of WF-Downloads");
    define("_XADDRESSES_AM_IMPORT_WFDOWNLOADS_SHOTS","Select Upload Directory (the path) for screen shots of WF-Downloads");

//Pour les options de filtre
define("_XADDRESSES_AM_ORDER"," order: ");
define("_XADDRESSES_AM_TRIPAR","sorted by: ");

//Formulaire et tableau
define("_XADDRESSES_AM_FORMADD","Add");
define("_XADDRESSES_AM_FORMACTION","Action");
define("_XADDRESSES_AM_FORMAFFICHE","Display the field?");
define("_XADDRESSES_AM_FORMAFFICHESEARCH","Search field?");
define("_XADDRESSES_AM_FORMAPPROVE","Aprouve");
define("_XADDRESSES_AM_FORMCAT","Category");
define("_XADDRESSES_AM_FORMCOMMENTS","Number of comments");
define("_XADDRESSES_AM_FORMDATE","Date");
define("_XADDRESSES_AM_FORMDEL","Delete");

define("_XADDRESSES_AM_FORMEDIT","Edit");
define("_XADDRESSES_AM_FORMFILE","File");
define("_XADDRESSES_AM_FORMHITS","Hits");
define("_XADDRESSES_AM_FORMHOMEPAGE","Home Page");
define("_XADDRESSES_AM_FORMLOCK","desactivate the address");
define("_XADDRESSES_AM_FORMIGNORE","Ignor");
define("_XADDRESSES_AM_FORMINCAT","in the category");
define("_XADDRESSES_AM_FORMIMAGE","Image");
define("_XADDRESSES_AM_FORMIMG","screen shots");

define("_XADDRESSES_AM_FORMPLATFORM","Plateform");
define("_XADDRESSES_AM_FORMPOSTER","Posted by ");
define("_XADDRESSES_AM_FORMRATING","Note");
define("_XADDRESSES_AM_FORMSIZE","File size(bytes)");
define("_XADDRESSES_AM_FORMSUREDEL", "Are you sure you want to delete : <b><span style='color : Red'> %s </span></b>");
define("_XADDRESSES_AM_FORMTEXT","Description");
define("_XADDRESSES_AM_FORMTEXTADDRESSES","Description : <br /><br />Use the delimiter '<b>[pagebreak]</b>' to difine the size of the short description. <br /> The short description allows to reduce the text size in the homepage of the module and categories.");
define("_XADDRESSES_AM_FORMTITLE","Title");


define("_XADDRESSES_AM_FORMVERSION","Version");
define("_XADDRESSES_AM_FORMVOTE","Votes");
define("_XADDRESSES_AM_FORMWEIGHT","Weight");
define("_XADDRESSES_AM_FORMWITHFILE","With the file: ");
//version  1.1
define("_XADDRESSES_AM_FORMSUBMITTER","Posted by");
define("_XADDRESSES_AM_FORMDATEUPDATE","Update the date");

//Message d'erreur
define("_XADDRESSES_AM_ERROR_CAT","You can not use this category (looping on itself)");
define("_XADDRESSES_AM_ERROR_NOBMODADDRESSES","there is not any modified addresses");
define("_XADDRESSES_AM_ERROR_NOBROKENADDRESSES","there is not any broken addresses");
define("_XADDRESSES_AM_ERROR_NOCAT","You have to choose a category!");
define("_XADDRESSES_AM_ERROR_NODESCRIPTION","you have to write a description");
    define("_XADDRESSES_AM_ERROR_NOADDRESSES","there is no files to download");
define("_XADDRESSES_AM_ERROR_SIZE","the file size must be a number");
define("_XADDRESSES_AM_ERROR_WEIGHT","weight must be a number");

//Message de redirection
define("_XADDRESSES_AM_REDIRECT_DELOK","Successfuly deleted ");
define("_XADDRESSES_AM_REDIRECT_NODELFIELD","You can not delete this field (Basic Field)");
define("_XADDRESSES_AM_REDIRECT_SAVE","Successfuly registred");

//générique
define("_MD_XADDRESSES_NUMBYTES","%s bytes");

//pour xoops france:
define("_XADDRESSES_MD_SUP","<br /><br />[block]: Blocks<br />[notes]: Notes<br />[evolutions]: Envisaged Developments<br />[infos]: Informations<br />[changelog]: Changelog<br />[backoffice]: Back Office<br />[frontoffice]: Front Office");
define("_XADDRESSES_MD_SUP_BACKOFFICE","Back Office:");
define("_XADDRESSES_MD_SUP_BLOCS","Blocks:");
define("_XADDRESSES_MD_SUP_CHANGELOG","Changelog:");
define("_XADDRESSES_MD_SUP_EVOLUTIONS","Envisaged Developments:");
define("_XADDRESSES_MD_SUP_FRONTOFFICE","Front Office:");
define("_XADDRESSES_MD_SUP_INFOS","Informations:");
define("_XADDRESSES_MD_SUP_NOTES","Notes:");












define("_XADDRESSES_AM_STEP", "Step");

define("_XADDRESSES_AM_SAVEDSUCCESS", "%s saved successfully");
define("_XADDRESSES_AM_DELETEDSUCCESS", "%s deleted successfully");
define("_XADDRESSES_AM_RUSUREDEL", "Are you sure you want to delete %s");
define("_XADDRESSES_AM_FIELDNOTCONFIGURABLE", "The field is not configurable.");






define("_XADDRESSES_AM_PROF_VISIBLE_ON", "Field visible on these groups' profile");
define("_XADDRESSES_AM_PROF_VISIBLE_FOR", "Field visible on profile for these groups");
define("_XADDRESSES_AM_PROF_VISIBLE", "Visibility");
define("_XADDRESSES_AM_PROF_EDITABLE", "Field editable from profile");
define("_XADDRESSES_AM_PROF_REGISTER", "Show in registration form");
define("_XADDRESSES_AM_PROF_SEARCH", "Searchable by these groups");
define("_XADDRESSES_AM_PROF_ACCESS", "Profile accessible by these groups");
define("_XADDRESSES_AM_PROF_ACCESS_DESC",
        "<ul>" .
        "<li>Admin groups: If a user belongs to admin groups, the current user has access if and only if one of the current user's groups is allowed to access admin group; else</li>" .
        "<li>Non basic groups: If a user belongs to one or more non basic groups (NOT admin, user, anonymous), the current user has access if and only if one of the current user's groups is allowed to allowed to any of the non basic groups; else</li>" .
        "<li>User group: If a user belongs to User group only, the current user has access if and only if one of his groups is allowed to access User group</li>" .
        "</ul>");

define("_XADDRESSES_AM_FIELDVISIBLE", "The field ");
define("_XADDRESSES_AM_FIELDVISIBLEFOR", " is visible for ");
define("_XADDRESSES_AM_FIELDVISIBLEON", " viewing a profile of ");
define("_XADDRESSES_AM_FIELDVISIBLETOALL", "- Everyone");
define("_XADDRESSES_AM_FIELDNOTVISIBLE", "is not visible");


define("_XADDRESSES_AM_ADDOPTION", "Add Option");
define("_XADDRESSES_AM_REMOVEOPTIONS", "Remove Options");
define("_XADDRESSES_AM_KEY", "Value to be stored");
define("_XADDRESSES_AM_VALUE", "Text to be displayed");

// User management
define("_XADDRESSES_AM_EDITUSER", "Edit User");
define("_XADDRESSES_AM_SELECTUSER", "Select User");
define("_XADDRESSES_AM_ADDUSER","Add User");
define("_XADDRESSES_AM_THEME","Theme");
define("_XADDRESSES_AM_RANK","Rank");
define("_XADDRESSES_AM_USERDONEXIT","User doesn't exist!");
define("_XADDRESSES_MA_USERLEVEL", "User Level");

define("_XADDRESSES_MA_ACTIVE", "Active");
define("_XADDRESSES_MA_INACTIVE", "Inactive");
define("_XADDRESSES_AM_USERCREATED", "User Created");

define("_XADDRESSES_AM_CANNOTDELETESELF", "Deleting your own account is not allowed - use your profile page to delete your own account");
define("_XADDRESSES_AM_CANNOTDELETEADMIN", "Deleting an administrator account is not allowed");

define("_XADDRESSES_AM_NOSELECTION", "No user selected");
define("_XADDRESSES_AM_USER_ACTIVATED", "User activated");
define("_XADDRESSES_AM_USER_DEACTIVATED", "User deactivated");
define("_XADDRESSES_AM_USER_NOT_ACTIVATED", "Error: User NOT activated");
define("_XADDRESSES_AM_USER_NOT_DEACTIVATED", "Error: User NOT deactivated");
?>