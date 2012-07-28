<?php
// admin/index.php
define("_AM_XADDRESSES_INDEX_INFO", "Module Informations");
define("_AM_XADDRESSES_INDEX_SCONFIG", "<b>Information taken from Module Preferences:</b>");

define("_AM_XADDRESSES_INDEX_DATABASE", "Information taken from Module Database");
define("_AM_XADDRESSES_INDEX_COUNTCATEGORIES","There are %s Location Categories in our database");
define("_AM_XADDRESSES_INDEX_COUNTLOCATIONS","There are %s Locations in our database");
define("_AM_XADDRESSES_INDEX_COUNTBROKEN","There are %s broken Locations reports");
define("_AM_XADDRESSES_INDEX_COUNTWAITING","There are %s Locations waiting for approval");
define("_AM_XADDRESSES_INDEX_COUNTMODIFIED","There are %s Location info modification requests");

define("_AM_XADDRESSES_INDEX_SERVERSTATUS", "Server Status");
define("_AM_XADDRESSES_INDEX_SPHPINI", "<b>Information taken from PHP ini File:</b>");
define("_AM_XADDRESSES_INDEX_SERVERPATH", "Server Path to XOOPS Root: ");
define("_AM_XADDRESSES_INDEX_SAFEMODESTATUS", "Safe Mode Status: ");
define("_AM_XADDRESSES_INDEX_REGISTERGLOBALS", "Register Globals: ");
define("_AM_XADDRESSES_INDEX_MAGICQUOTESGPC", "'magic_quotes_gpc' Status: ");
define("_AM_XADDRESSES_INDEX_SERVERUPLOADSTATUS", "Server Uploads Status: ");
define("_AM_XADDRESSES_INDEX_MAXUPLOADSIZE", "Max Upload Size Permitted: ");
define("_AM_XADDRESSES_INDEX_MAXPOSTSIZE", "Max Post Size Permitted: ");
define("_AM_XADDRESSES_INDEX_SAFEMODEPROBLEMS", " (This May Cause Problems)");
define("_AM_XADDRESSES_INDEX_GDLIBSTATUS", "GD Library Support: ");
define("_AM_XADDRESSES_INDEX_ZIPLIBSTATUS", "Zip Library Support (ZipArchive class): ");
define("_AM_XADDRESSES_INDEX_GDLIBVERSION", "GD Library Version: ");
define("_AM_XADDRESSES_INDEX_GDON", "<b>Enabled</b>");
define("_AM_XADDRESSES_INDEX_GDOFF", "<b>Disabled</b>");
define("_AM_XADDRESSES_INDEX_ZIPON", "<b>Enabled</b>");
define("_AM_XADDRESSES_INDEX_ZIPOFF", "<b>Disabled</b>");
define("_AM_XADDRESSES_INDEX_OFF", "<b>OFF</b>");
define("_AM_XADDRESSES_INDEX_ON", "<b>ON</b>");



//admin/about.php
define("_AM_XADDRESSES_ABOUT_AUTHOR", "Author");
define("_AM_XADDRESSES_ABOUT_CHANGELOG", "Change log");
define("_AM_XADDRESSES_ABOUT_CREDITS", "Credits");
define("_AM_XADDRESSES_ABOUT_LICENSE", "License");
define("_AM_XADDRESSES_ABOUT_MODULEINFOS", "Module Informations");
define("_AM_XADDRESSES_ABOUT_MODULEWEBSITE", "Support Web Site");
define("_AM_XADDRESSES_ABOUT_AUTHORINFOS", "Author Informations");
define("_AM_XADDRESSES_ABOUT_AUTHORWEBSITE", "Web Site");
define("_AM_XADDRESSES_ABOUT_AUTHOREMAIL", "Email");
define("_AM_XADDRESSES_ABOUT_RELEASEDATE", "Date of launch");
define("_AM_XADDRESSES_ABOUT_STATUS", "Status");
define("_AM_XADDRESSES_ABOUT_DESCRIPTION", "Module Description &quot;description.html&quot;");



//admin/help.php
define("_AM_XADDRESSES_ABOUT_HELP","Module Help &quot;help.html&quot;");

//Error NoFrameworks
define("_AM_XADDRESSES_NOFRAMEWORKS","Error: You don&#39;t use the Frameworks \"admin module\". Please install this Frameworks");
define("_AM_XADDRESSES_MAINTAINEDBY", "is maintained by the");




// IN PROGRESS




define("_AM_XADDRESSES_ACTION","Action");


//admin/item.php
//class/location.php
define("_AM_XADDRESSES_LOC_NOCAT", "Lacation cannot be create: There are no Categories created yet! Please create a Category first.");
define("_AM_XADDRESSES_LOC_NEW","New location");
define("_AM_XADDRESSES_LOC_LIST","Locations list");
define("_AM_XADDRESSES_LOC_WAITING","Locations waiting for validation");
define("_AM_XADDRESSES_LOC_BROKEN","Broken location reports");
define("_AM_XADDRESSES_LOC_MODIFY","Location correction/modification suggests");
define("_AM_XADDRESSES_LOC_SEARCH","Search");

define("_AM_XADDRESSES_SORT_BY","sorted by: ");
define("_AM_XADDRESSES_SORT_BY_NONE","");
define("_AM_XADDRESSES_SORT_BY_DATE","date");
define("_AM_XADDRESSES_SORT_BY_TITLE","title");
define("_AM_XADDRESSES_SORT_BY_CAT","category");
define("_AM_XADDRESSES_ORDER"," order: ");
define("_AM_XADDRESSES_ORDER_DESC","DESC");
define("_AM_XADDRESSES_ORDER_ASC","ASC");
define("_AM_XADDRESSES_FILTER","filter by: ");

define("_AM_XADDRESSES_LOCATION","Location");
define("_AM_XADDRESSES_LOCATIONS","Locations");
define("_AM_XADDRESSES_LOC_ADD","Add new Location");
define("_AM_XADDRESSES_LOC_EDIT","Modify Location");
define("_AM_XADDRESSES_LOC_ID","Id");
define("_AM_XADDRESSES_LOC_TITLE","Location title");
define("_AM_XADDRESSES_LOC_TITLE_DESC","Text or HTML format");
define("_AM_XADDRESSES_LOC_COORDINATES","Coordinates");
define("_AM_XADDRESSES_LOC_COORDINATES_DESC","Latitude, Longitude and Zoom level");
define("_AM_XADDRESSES_LOC_CAT","Category");
define("_AM_XADDRESSES_LOC_CAT_DESC","Choose the Location category");
define("_AM_XADDRESSES_LOC_CATS","Categories");
define("_AM_XADDRESSES_LOC_WEIGHT","Weight");
define("_AM_XADDRESSES_LOC_WEIGHT_DESC","// IN PROGRESS");
define("_AM_XADDRESSES_LOC_DATE","Date");
define("_AM_XADDRESSES_LOC_DATE_DESC","// IN PROGRESS");
define("_AM_XADDRESSES_LOC_SUBMITTER","Submitter");
define("_AM_XADDRESSES_LOC_SUBMITTER_DESC","// IN PROGRESS");
define("_AM_XADDRESSES_LOC_STATUS","Status");
define("_AM_XADDRESSES_LOC_STATUS_ALL","All");
define("_AM_XADDRESSES_LOC_STATUS_OK","OK");
define("_AM_XADDRESSES_LOC_STATUS_NOT_OK","NOT OK");
define("_AM_XADDRESSES_LOC_STATUS_VALIDATED","Validated");
define("_AM_XADDRESSES_LOC_STATUS_NOT_VALIDATED","Not Validated");
define("_AM_XADDRESSES_LOC_STATUS_BROKEN","Reported as wrong");
define("_AM_XADDRESSES_LOC_STATUS_MODIFIED","Proposed correction");
define("_AM_XADDRESSES_LOC_HITS","Hits");
define("_AM_XADDRESSES_LOC_RATING","Rating");
define("_AM_XADDRESSES_DISPLAY","Display");
define("_AM_XADDRESSES_LOC_UNLOCK","Activate");
define("_AM_XADDRESSES_LOC_LOCK","Dectivate");

define("_AM_XADDRESSES_LOC_CREATED", "Location Created");

//class/broken.php
define("_AM_XADDRESSES_LOC_BROKEN_REPORT","Report");
define("_AM_XADDRESSES_LOC_BROKEN_NEW","Report Broken Location");
define("_AM_XADDRESSES_LOC_BROKEN_EDIT","Edit Broken Location");
define("_AM_XADDRESSES_LOC_BROKEN_DESCRIPTION","Description");
define("_AM_XADDRESSES_LOC_BROKEN_DESCRIPTION_DESC","// IN PROGRESS");
define("_AM_XADDRESSES_LOC_BROKEN_DATE","Data");
define("_AM_XADDRESSES_LOC_BROKEN_SENDER","Report Sender");
define("_AM_XADDRESSES_LOC_BROKEN_SENDER_IP","IP");
define("_AM_XADDRESSES_LOC_BROKEN_DEL","Delete report");
define("_AM_XADDRESSES_LOC_BROKEN_SURE_DEL","Are you sure you want to delete this report?");
define("_AM_XADDRESSES_LOC_BROKEN_EDIT_LOC","Edit location");



//class/votedata.php
define("_AM_XADDRESSES_LOC_RATE_NEW","Vote Location");
define("_AM_XADDRESSES_LOC_RATE_EDIT","Edit Location Vote");
define("_AM_XADDRESSES_LOC_RATE_VOTE","Vote");
define("_AM_XADDRESSES_LOC_RATE_RATE","Rate it !");

//class/modify.php
define("_AM_XADDRESSES_LOC_MODIFY_NEW","Suggest Location Correction/Modification");
define("_AM_XADDRESSES_LOC_MODIFY_EDIT","Edit Location Correction/Modification Suggestion");
define("_AM_XADDRESSES_LOC_MODIFY_DESCRIPTION","Description");
define("_AM_XADDRESSES_LOC_MODIFY_DESCRIPTION_DESC","// IN PROGRESS");
define("_AM_XADDRESSES_LOC_MODIFY_SUGGEST","Suggest");
define("_AM_XADDRESSES_LOC_MODIFY_DATE","Data");
define("_AM_XADDRESSES_LOC_MODIFY_SENDER","Suggest Sender");
define("_AM_XADDRESSES_LOC_MODIFY_SENDER_IP","IP");
define("_AM_XADDRESSES_LOC_MODIFY_DEL","Delete suggest");
define("_AM_XADDRESSES_LOC_MODIFY_SURE_DEL","Are you sure you want to delete this suggest?");
define("_AM_XADDRESSES_LOC_MODIFY_EDIT_LOC","Edit location");


//admin/itemcategory.php
//class/locationcategory.php
define("_AM_XADDRESSES_CAT_NEW","New Location Category");
define("_AM_XADDRESSES_CAT_LIST","Location Categories list");

define("_AM_XADDRESSES_CATEGORY","Category");
define("_AM_XADDRESSES_CAT_ADD","Add new Category");
define("_AM_XADDRESSES_CAT_EDIT","Modify Category");
define("_AM_XADDRESSES_CAT_ID","Id");
define("_AM_XADDRESSES_CAT_TITLE","Title");
define("_AM_XADDRESSES_CAT_DESCRIPTION","Description");
define("_AM_XADDRESSES_CAT_DESCRIPTION_DESC","Leave empty if category is a main category");
define("_AM_XADDRESSES_CAT_IMG","Category image");
define("_AM_XADDRESSES_CAT_PARENT","In the category");
define("_AM_XADDRESSES_CAT_PARENT_DESC","Leave empty if category is a main category");
define("_AM_XADDRESSES_CAT_WEIGHT","Weight");

define("_AM_XADDRESSES_CAT_MAP_SETTING","GoogleMaps settings");
define("_AM_XADDRESSES_CAT_MAP_TYPE","Map type");
define("_AM_XADDRESSES_CAT_MAP_TYPE_DESC","The initial map type<br /><ul><li>HYBRID This map type displays a transparent layer of major streets on satellite images.</li><li>ROADMAP This map type displays a normal street map.</li><li>SATELLITE This map type displays satellite images.</li><li>TERRAIN This map type displays maps with physical features such as terrain and vegetation.</li></ul>");

define("_AM_XADDRESSES_CAT_PERMISSIONS","Permissions");

define("_AM_XADDRESSES_CAT_INFO","Informations");

// include/form.php xaddresses_getFieldForm
define("_AM_XADDRESSES_ADD_FIELD","Add new field");
define("_AM_XADDRESSES_EDIT_FIELD","Edit field");

define("_AM_XADDRESSES_FIELD_NEXT_STEP","Edit field: next step");
define("_AM_XADDRESSES_FIELD_SUBMIT_AND_EDIT","Submit and edit");

define("_AM_XADDRESSES_FIELD", "Field");
define("_AM_XADDRESSES_FIELDS", "Fields");

define("_AM_XADDRESSES_FIELD_TITLE","Title");
define("_AM_XADDRESSES_FIELD_TITLE_DESC","// IN PROGRESS");
define("_AM_XADDRESSES_FIELD_DESCRIPTION", "Description");
define("_AM_XADDRESSES_FIELD_DESCRIPTION_DESC", "// IN PROGRESS");
define("_AM_XADDRESSES_FIELD_CATEGORY", "Category");
define("_AM_XADDRESSES_FIELD_CATEGORY_DESC", "// IN PROGRESS");
define("_AM_XADDRESSES_FIELD_CATEGORY_NONE", "NONE");
define("_AM_XADDRESSES_FIELD_CATEGORY_NONE_DESC", "// IN PROGRESS");
define("_AM_XADDRESSES_FIELD_WEIGHT", "Weight");
define("_AM_XADDRESSES_FIELD_WEIGHT_DESC", "// IN PROGRESS");
define("_AM_XADDRESSES_FIELD_NAME", "Field Name");
define("_AM_XADDRESSES_FIELD_NAME_DESC", "Field name in database<br />' ' will be automatically replaced by '_'<br />// IN PROGRESS");
define("_AM_XADDRESSES_FIELD_TYPE", "Field type");
define("_AM_XADDRESSES_FIELD_TYPE_DESC", "// IN PROGRESS");

define("_AM_XADDRESSES_FIELD_CHECKBOX", "Checkbox");
define("_AM_XADDRESSES_FIELD_GROUP", "Group Select");
define("_AM_XADDRESSES_FIELD_GROUPMULTI", "Group Multi Select");
define("_AM_XADDRESSES_FIELD_LANGUAGE", "Language Select");
define("_AM_XADDRESSES_FIELD_RADIO", "Radio Buttons");
define("_AM_XADDRESSES_FIELD_SELECT", "Select");
define("_AM_XADDRESSES_FIELD_SELECTMULTI", "Multi Select");
define("_AM_XADDRESSES_FIELD_TEXTAREA", "Text Area");
define("_AM_XADDRESSES_FIELD_DHTMLTEXTAREA", "DHTML Text Area");
define("_AM_XADDRESSES_FIELD_TEXTBOX", "Text Field");
define("_AM_XADDRESSES_FIELD_TIMEZONE", "Timezone");
define("_AM_XADDRESSES_FIELD_YESNO", "Radio Yes/No");
define("_AM_XADDRESSES_FIELD_DATE", "Date");
define("_AM_XADDRESSES_FIELD_AUTOTEXT", "Auto Text");
define("_AM_XADDRESSES_FIELD_DATETIME", "Date and Time");
define("_AM_XADDRESSES_FIELD_LONGDATE", "Long Date");
define("_AM_XADDRESSES_FIELD_XOOPSIMAGE", "Xoops Image");
define("_AM_XADDRESSES_FIELD_MULTIPLEXOOPSIMAGE", "Multiple Xoops Images");
define("_AM_XADDRESSES_FIELD_FILE", "File");
define("_AM_XADDRESSES_FIELD_MULTIPLEFILE", "Multiple Files");
define("_AM_XADDRESSES_FIELD_KMLMAP", "Kml Map");
define("_AM_XADDRESSES_FIELD_THEME", "Theme");
define("_AM_XADDRESSES_FIELD_RANK", "Rank");

define("_AM_XADDRESSES_FIELD_VALUETYPE","Field type");
define("_AM_XADDRESSES_FIELD_VALUETYPE_DESC","// IN PROGRESS");

define("_AM_XADDRESSES_FIELD_ARRAY", "Array");
define("_AM_XADDRESSES_FIELD_EMAIL", "Email");
define("_AM_XADDRESSES_FIELD_INT", "Integer");
define("_AM_XADDRESSES_FIELD_TXTAREA", "Text Area");
define("_AM_XADDRESSES_FIELD_TXTBOX", "Text field");
define("_AM_XADDRESSES_FIELD_URL", "URL");
define("_AM_XADDRESSES_FIELD_OTHER", "Other");
define("_AM_XADDRESSES_FIELD_FLOAT", "Floating Point");
define("_AM_XADDRESSES_FIELD_DECIMAL", "Decimal Number");
define("_AM_XADDRESSES_FIELD_UNICODE_ARRAY", "Unicode Array");
define("_AM_XADDRESSES_FIELD_UNICODE_EMAIL", "Unicode Email");
define("_AM_XADDRESSES_FIELD_UNICODE_TXTAREA", "Unicode Text Area");
define("_AM_XADDRESSES_FIELD_UNICODE_TXTBOX", "Unicode Text field");
define("_AM_XADDRESSES_FIELD_UNICODE_URL", "Unicode URL");

define("_AM_XADDRESSES_FIELD_ADDOPTION", "Add Option");
define("_AM_XADDRESSES_FIELD_REMOVEOPTIONS", "Remove Options");
define("_AM_XADDRESSES_FIELD_KEY", "Value to be stored");
define("_AM_XADDRESSES_FIELD_VALUE", "Text to be displayed");

define("_AM_XADDRESSES_FIELD_NOTNULL", "Not Null?");
define("_AM_XADDRESSES_FIELD_NOTNULL_DESC", "Not Null?");

//define("_AM_XADDRESSES_FIELD_ADDEXTRA", "Maximum Length");
//define("_AM_XADDRESSES_FIELD_ADDEXTRA_DESC", "// IN PROGRESS");

define("_AM_XADDRESSES_FIELD_LENGTH", "Field Length In Form");
define("_AM_XADDRESSES_FIELD_LENGTH_DESC", "// IN PROGRESS");
define("_AM_XADDRESSES_FIELD_TEXTAREAROWS", "Field rows attribute");
define("_AM_XADDRESSES_FIELD_TEXTAREAROWS_DESC", "Specifies the visible number of lines in a text area");
define("_AM_XADDRESSES_FIELD_TEXTAREACOLS", "Field cols attribute");
define("_AM_XADDRESSES_FIELD_TEXTAREACOLS_DESC", "Specifies the visible width of a text area");

define("_AM_XADDRESSES_FIELD_MAXLENGTH", "Maximum Field Length");
define("_AM_XADDRESSES_FIELD_MAXLENGTH_DESC", "// IN PROGRESS");
define("_AM_XADDRESSES_FIELD_DEFAULT", "Default");
define("_AM_XADDRESSES_FIELD_DEFAULT_DESC", "// IN PROGRESS");
define("_AM_XADDRESSES_FIELD_REQUIRED", "Required?");
define("_AM_XADDRESSES_FIELD_REQUIRED_DESC", "// IN PROGRESS");

define("_AM_XADDRESSES_FIELD_VIEWABLE", "Field viewable by");
define("_AM_XADDRESSES_FIELD_VIEWABLE_DESC", "Groups that can view data stored in this field");
define("_AM_XADDRESSES_FIELD_EDITABLE", "Field editable by");
define("_AM_XADDRESSES_FIELD_EDITABLE_DESC", "Groups that can edit data stored in this field");
define("_AM_XADDRESSES_FIELD_SEARCHABLE", "Field searchable by");
define("_AM_XADDRESSES_FIELD_SEARCHABLE_DESC", "Groups that can search data stored in field");
define("_AM_XADDRESSES_FIELD_EXPORTABLE", "Filed exportable by");
define("_AM_XADDRESSES_FIELD_EXPORTABLE_DESC", "Groups that can export data stored in field");


//admin/field.php
define("_AM_XADDRESSES_FIELD_NEW","New field");
define("_AM_XADDRESSES_FIELD_LIST","Fields List");

//admin/fieldcategory.php
define("_AM_XADDRESSES_FIELDCAT_NEW","New Field Category");
define("_AM_XADDRESSES_FIELDCAT_LIST","Field Categories list");

define("_AM_XADDRESSES_FIELDCAT","Field Category");
define("_AM_XADDRESSES_FIELDCAT_EDIT","Edit Field Category");

define("_AM_XADDRESSES_FIELDCAT_TITLE","Title");
define("_AM_XADDRESSES_FIELDCAT_TITLE_DESC","// IN PROGRESS");
define("_AM_XADDRESSES_FIELDCAT_DESCRIPTION", "Description");
define("_AM_XADDRESSES_FIELDCAT_DESCRIPTION_DESC", "// IN PROGRESS");
define("_AM_XADDRESSES_FIELDCAT_WEIGHT","Weight");
define("_AM_XADDRESSES_FIELDCAT_WEIGHT_DESC","// IN PROGRESS");



// admin/permissions.php
define("_AM_XADDRESSES_PERM_ITEM_PERMISSIONS", "Locations Permissions");
define("_AM_XADDRESSES_PERM_FIELD_PERMISSIONS", "Fields Permissions");
define("_AM_XADDRESSES_PERM_EXTRA_PERMISSIONS", "Extra Permissions");

define("_AM_XADDRESSES_PERM_NOCAT", "Permission cannot be set: There are no Categories created yet! Please create a Category first.");
define("_AM_XADDRESSES_PERM_NOCAT_DESC", "");
define("_AM_XADDRESSES_PERM_VIEW", "View Permission");
define("_AM_XADDRESSES_PERM_VIEW_DESC", "Choose groups than can view Locations in categories");
define("_AM_XADDRESSES_PERM_SUBMIT", "Submit Permission");
define("_AM_XADDRESSES_PERM_SUBMIT_DESC", "Choose groups that can submit Locations to categories");
define("_AM_XADDRESSES_PERM_EDIT", "Edit Permission");
define("_AM_XADDRESSES_PERM_EDIT_DESC", "Choose groups than can edit Locations in categories");
define("_AM_XADDRESSES_PERM_DELETE", "Delete Permission");
define("_AM_XADDRESSES_PERM_DELETE_DESC", "Choose groups than can delete Locations in categories");

define("_AM_XADDRESSES_PERM_VIEWFIELD", "View Fields Permission");
define("_AM_XADDRESSES_PERM_VIEWFIELD_DESC", "Choose fields than groups can view");
define("_AM_XADDRESSES_PERM_EDITFIELD", "Edit Fields Permission");
define("_AM_XADDRESSES_PERM_EDITFIELD_DESC", "Choose fields than groups can edit");
define("_AM_XADDRESSES_PERM_SEARCHFIELD", "Search in Fields Permission");
define("_AM_XADDRESSES_PERM_SEARCHFIELD_DESC", "Choose fields than groups can search");
define("_AM_XADDRESSES_PERM_EXPORTFIELD", "Export Fields Permission");
define("_AM_XADDRESSES_PERM_EXPORTFIELD_DESC", "Choose fields than groups can export");

define("_AM_XADDRESSES_PERM_OTHERS", "Other permissions");
define("_AM_XADDRESSES_PERM_OTHERS_DESC", "Select groups that can:");
define("_AM_XADDRESSES_PERMISSIONS_1","Modify location submitter");
define("_AM_XADDRESSES_PERMISSIONS_2","Modify location date");
//define("_AM_XADDRESSES_PERMISSIONS_4","Submit a location"); // IN PROGRESS
define("_AM_XADDRESSES_PERMISSIONS_8","Suggest location correction/modification"); // IN PROGRESS
define("_AM_XADDRESSES_PERMISSIONS_16","Send to a Friend");
define("_AM_XADDRESSES_PERMISSIONS_32","Rate location");
define("_AM_XADDRESSES_PERMISSIONS_64","Report broken location");
//define("_AM_XADDRESSES_PERMISSIONS_128","// IN PROGRESS"); // IN PROGRESS
//define("_AM_XADDRESSES_PERMISSIONS_256","// IN PROGRESS"); // IN PROGRESS



// admin/import.php
define("_AM_XADDRESSES_IMPORT", "Import");
define("_AM_XADDRESSES_IMPORT_WARNING", "<span style='color:#FF0000; font-size:16px; font-weight:bold'>Attention !</span><br /><br /> Importation will delete all data in Xaddresses. It's highly recomended that you make a backup of your data, also of your website.<br /><br />Xaddresses is not responsible if you lose your data.");
define("_AM_XADDRESSES_IMPORT_MOD_NOTPRESENT", "Module not present");
define("_AM_XADDRESSES_IMPORT_MOD_NOTINSTALLED", "Module present but not installed");
define("_AM_XADDRESSES_IMPORT_MOD_NOTACTIVE", "Module installed but not active");

define("_AM_XADDRESSES_IMPORT_ADDRESSES17", "Import from Addresses v1.7 + Google Maps");
define("_AM_XADDRESSES_IMPORT_ADDRESSES172", "Import from Addresses v1.72 + Google Maps");

define("_AM_XADDRESSES_IMPORT_NUMBER","Data to import");

define("_AM_XADDRESSES_IMPORT_ADDRESSES_DONT" ,"There are no Addresses to import");
define("_AM_XADDRESSES_IMPORT_ADDRESSES_TOIMPORT", "There are %s Addresses to import");
define("_AM_XADDRESSES_IMPORT_ADDRESSES_IMPORTED", "Categories: '%s' imported");

define("_AM_XADDRESSES_IMPORT_CATEGORIES_DONT", "There are no Categories to import");
define("_AM_XADDRESSES_IMPORT_CATEGORIES_TOIMPORT", "There are %s Categories to import");
define("_AM_XADDRESSES_IMPORT_CATEGORIES_IMPORTED", "Categories: '%s' imported");

define("_AM_XADDRESSES_IMPORT_FORM", "Import");
define("_AM_XADDRESSES_IMPORT_SOURCE_CATEGORIES", "Import data from categories");
define("_AM_XADDRESSES_IMPORT_SOURCE_CATEGORIES_DESC", "IN PROGRESS");
define("_AM_XADDRESSES_IMPORT_DESTINATION_CATEGORY", "Import data into category");
define("_AM_XADDRESSES_IMPORT_DESTINATION_CATEGORY_DESC", "Select where to import data.<br />Data WILL NOT BE overwriten");


// TO DO
/*
define("_AM_XADDRESSES_IMPORT_CONF_MYDOWNLOADS","Are you sure you want to import data from Mydownloads module to TDMDownloads");
define("_AM_XADDRESSES_IMPORT_CONF_WFDOWNLOADS","Are you sure you want to import data from WF-Downloads modules to TDMDownloads");

define("_AM_XADDRESSES_IMPORT_DOWNLOADS","files Importation");
define("_AM_XADDRESSES_IMPORT_ERROR","Select Upload Directory (the path)");
define("_AM_XADDRESSES_IMPORT_ERROR_DATA","Error during the importation of data");
define("_AM_XADDRESSES_IMPORT_MYDOWNLOADS","Import from Mydownloads");
define("_AM_XADDRESSES_IMPORT_MYDOWNLOADS_PATH","Select Upload Directory (the path) for screen shots of Mydownloads");
define("_AM_XADDRESSES_IMPORT_MYDOWNLOADS_URL","Choose the corresponding URL  for screen shots of Mydownloads");
define("_AM_XADDRESSES_IMPORT_NB_CAT","There are %s categories to import");
define("_AM_XADDRESSES_IMPORT_NB_DOWNLOADS","There are %s files to import");

define("_AM_XADDRESSES_IMPORT_OK","Import successfuly done !!!");
define("_AM_XADDRESSES_IMPORT_VOTE_IMP","VOTES: '%s' imported;");
define("_AM_XADDRESSES_IMPORT_WFDOWNLOADS","Import from WF Downloads(only for V3.2 RC2)");
define("_AM_XADDRESSES_IMPORT_WFDOWNLOADS_CATIMG","Select Upload Directory (the path) for categories inages of WF-Downloads");
define("_AM_XADDRESSES_IMPORT_WFDOWNLOADS_SHOTS","Select Upload Directory (the path) for screen shots of WF-Downloads");
*/

// Error messages
//define("_AM_XADDRESSES_ERROR_CAT","You can not use this category (looping on itself)");
//define("_AM_XADDRESSES_ERROR_NO_MOD_LOCS","there is not any modified addresses");
//define("_AM_XADDRESSES_ERROR_NOCAT","You have to choose a category!");
//define("_AM_XADDRESSES_ERROR_NODESCRIPTION","you have to write a description");
define("_AM_XADDRESSES_ERROR_NO_LOCS", "There are no locations");
define("_AM_XADDRESSES_ERROR_NO_BROKEN_REPORTS","There are no broken location reports");
define("_AM_XADDRESSES_ERROR_NO_MODIFY_SUGGESTS","There are no location correction/modification suggests");
define("_AM_XADDRESSES_ERROR_NO_FIELDS", "There are no extra fields");
//define("_AM_XADDRESSES_ERROR_SIZE","the file size must be a number");
define("_AM_XADDRESSES_ERROR_WEIGHT","weight must be a number");


// Redirection Messages
define("_AM_XADDRESSES_REDIRECT_DEL_OK","Successfuly deleted");
//define("_AM_XADDRESSES_REDIRECT_NODELFIELD","You can not delete this field (Basic Field)");
define("_AM_XADDRESSES_REDIRECT_SAVE","Successfully registred");
define("_AM_XADDRESSES_VIEW_NOT_ALLOWED", "Sorry, but you cannot view locations");
define("_AM_XADDRESSES_EDIT_NOT_ALLOWED", "Sorry, but you cannot edit locations");
define("_AM_XADDRESSES_SUBMIT_NOT_ALLOWED", "Sorry, but you cannot submit locations");

// Other Messages
define("_AM_XADDRESSES_RU_SURE_DEL", "Are you sure you want to delete %s");
define("_AM_XADDRESSES_FORM_SURE_DEL", "Are you sure you want to delete : <b><span style='color : Red'> %s </span></b>");
define("_AM_XADDRESSES_DEL_SUB_CATS","The following categories will be totally deleted:");
define("_AM_XADDRESSES_SAVEDSUCCESS", "%s saved successfully");
define("_AM_XADDRESSES_DELETEDSUCCESS", "%s deleted successfully");
define("_AM_XADDRESSES_UPDATESUCCESS", "%s updated successfully");


// IN PROGRESS FROM HERE --------------------------------------------------------------------------

/*


//version  1.1
define("_AM_XADDRESSES_INDEX_UPDATE_INFO","Latest version of Xaddresses");
define("_AM_XADDRESSES_INDEX_VERSION_OK","You have the latest version of Xaddresses %s");
define("_AM_XADDRESSES_INDEX_CHANGELOG","<b>Changelog</b>");
define("_AM_XADDRESSES_INDEX_VERSION_NOT_OK","There is a new version of Xaddresses %s, you can download <a href='http://www.tdmxoops.net/modules/TDMDownloads' target='_blank'>here</a>");
define("_AM_XADDRESSES_INDEX_VERSION_ALLOWURLFOPEN","To determine if a new version of TDMDownloads exists, you must have &#039;allow_url_fopen&#039; to &#039;on&#039;");
define("_AM_XADDRESSES_INDEX_VERSION_FICHIER_KO","The file version management module of TDM is currently unavailable");

//categories.php

define("_AM_XADDRESSES_DELADDRESSES","with the following addresses:");
define("_AM_XADDRESSES_ADDRESSESINCAT","address(es) in this category");
define("_AM_XADDRESSES_THEREIS","there are");


//addresses.php


define("_AM_XADDRESSES_ADDRESSES_VOTESANONYME","Votes by anonymous (total of votes : %s)");
define("_AM_XADDRESSES_ADDRESSES_VOTESUSER","Votes by users (total of votes : %s)");
define("_AM_XADDRESSES_ADDRESSES_VOTE_USER","Users");
define("_AM_XADDRESSES_ADDRESSES_VOTE_IP","IP Address");




//modified.php
define("_AM_XADDRESSES_MODIFIED_MOD","Submited by;");
define("_AM_XADDRESSES_MODIFIED_ORIGINAL","Original");
define("_AM_XADDRESSES_MODIFIED_SURDEL","Are you sure you want to delete this address modification request?");





// Import.php
    define("_AM_XADDRESSES_IMPORT1","Import");
    define("_AM_XADDRESSES_IMPORT_CAT_IMP","Categories: '%s' imported");
    define("_AM_XADDRESSES_IMPORT_CONF_MYDOWNLOADS","Are you sure you want to import data from Mydownloads module to TDMDownloads");
    define("_AM_XADDRESSES_IMPORT_CONF_WFDOWNLOADS","Are you sure you want to import data from WF-Downloads modules to TDMDownloads");
    define("_AM_XADDRESSES_IMPORT_DONT_DOWNLOADS","there is no files to import");
    define("_AM_XADDRESSES_IMPORT_DONT_TOPIC","there is no files to import");
    define("_AM_XADDRESSES_IMPORT_DOWNLOADS","files Importation");
    define("_AM_XADDRESSES_IMPORT_DOWNLOADS_IMP","files: '%s' imported;");
    define("_AM_XADDRESSES_IMPORT_ERROR","Select Upload Directory (the path)");
    define("_AM_XADDRESSES_IMPORT_ERROR_DATA","Error during the importation of data");
    define("_AM_XADDRESSES_IMPORT_MYDOWNLOADS","Import from Mydownloads");
    define("_AM_XADDRESSES_IMPORT_MYDOWNLOADS_PATH","Select Upload Directory (the path) for screen shots of Mydownloads");
    define("_AM_XADDRESSES_IMPORT_MYDOWNLOADS_URL","Choose the corresponding URL  for screen shots of Mydownloads");
    define("_AM_XADDRESSES_IMPORT_NB_CAT","There are %s categories to import");
    define("_AM_XADDRESSES_IMPORT_NB_DOWNLOADS","There are %s files to import");
    define("_AM_XADDRESSES_IMPORT_NUMBER","Data to import");
    define("_AM_XADDRESSES_IMPORT_OK","Import successfuly done !!!");
    define("_AM_XADDRESSES_IMPORT_VOTE_IMP","VOTES: '%s' imported;");
    define("_AM_XADDRESSES_IMPORT_WARNING","<span style='color:#FF0000; font-size:16px; font-weight:bold'>Attention !</span><br /><br /> Importation will delete all data in TDMDownloads. It's highly recomended that you make a backup of your data, also of your website.<br /><br />TDM is not responsible if you lose your data. It happens that screen shots cant be copied.");
    define("_AM_XADDRESSES_IMPORT_WFDOWNLOADS","Import from WF Downloads(only for V3.2 RC2)");
    define("_AM_XADDRESSES_IMPORT_WFDOWNLOADS_CATIMG","Select Upload Directory (the path) for categories inages of WF-Downloads");
    define("_AM_XADDRESSES_IMPORT_WFDOWNLOADS_SHOTS","Select Upload Directory (the path) for screen shots of WF-Downloads");

//Pour les options de filtre




//Formulaire et tableau
define("_AM_XADDRESSES_FORMADD","Add");
define("_AM_XADDRESSES_FORMACTION","Action");
define("_AM_XADDRESSES_FORMAFFICHE","Display the field?");
define("_AM_XADDRESSES_FORMAFFICHESEARCH","Search field?");
define("_AM_XADDRESSES_FORMAPPROVE","Aprouve");
define("_AM_XADDRESSES_FORMCAT","Category");
define("_AM_XADDRESSES_FORMCOMMENTS","Number of comments");
define("_AM_XADDRESSES_FORMDATE","Date");
define("_AM_XADDRESSES_FORMDEL","Delete");

define("_AM_XADDRESSES_FORMEDIT","Edit");
define("_AM_XADDRESSES_FORMFILE","File");
define("_AM_XADDRESSES_FORMHITS","Hits");
define("_AM_XADDRESSES_FORMHOMEPAGE","Home Page");
define("_AM_XADDRESSES_FORMLOCK","desactivate the address");
define("_AM_XADDRESSES_FORMIGNORE","Ignor");
define("_AM_XADDRESSES_FORMINCAT","in the category");
define("_AM_XADDRESSES_FORMIMAGE","Image");
define("_AM_XADDRESSES_FORMIMG","screen shots");

define("_AM_XADDRESSES_FORMPLATFORM","Plateform");
define("_AM_XADDRESSES_FORMPOSTER","Posted by ");
define("_AM_XADDRESSES_FORMRATING","Note");
define("_AM_XADDRESSES_FORMSIZE","File size(bytes)");

define("_AM_XADDRESSES_FORMTEXT","Description");
define("_AM_XADDRESSES_FORMTEXTADDRESSES","Description : <br /><br />Use the delimiter '<b>[pagebreak]</b>' to difine the size of the short description. <br /> The short description allows to reduce the text size in the homepage of the module and categories.");
define("_AM_XADDRESSES_FORMTITLE","Title");


define("_AM_XADDRESSES_FORMVERSION","Version");
define("_AM_XADDRESSES_FORMVOTE","Votes");
define("_AM_XADDRESSES_FORMWEIGHT","Weight");
define("_AM_XADDRESSES_FORMWITHFILE","With the file: ");
//version  1.1
define("_AM_XADDRESSES_FORMSUBMITTER","Posted by");
define("_AM_XADDRESSES_FORMDATEUPDATE","Update the date");



//générique
define("_MD_XADDRESSES_NUMBYTES","%s bytes");

//pour xoops france:
define("_MA_XADDRESSES_SUP","<br /><br />[block]: Blocks<br />[notes]: Notes<br />[evolutions]: Envisaged Developments<br />[infos]: Informations<br />[changelog]: Changelog<br />[backoffice]: Back Office<br />[frontoffice]: Front Office");
define("_MA_XADDRESSES_SUP_BACKOFFICE","Back Office:");
define("_MA_XADDRESSES_SUP_BLOCS","Blocks:");
define("_MA_XADDRESSES_SUP_CHANGELOG","Changelog:");
define("_MA_XADDRESSES_SUP_EVOLUTIONS","Envisaged Developments:");
define("_MA_XADDRESSES_SUP_FRONTOFFICE","Front Office:");
define("_MA_XADDRESSES_SUP_INFOS","Informations:");
define("_MA_XADDRESSES_SUP_NOTES","Notes:");












define("_AM_XADDRESSES_STEP", "Step");



define("_AM_XADDRESSES_FIELDNOTCONFIGURABLE", "The field is not configurable.");






define("_AM_XADDRESSES_PROF_VISIBLE_ON", "Field visible on these groups' profile");
define("_AM_XADDRESSES_PROF_VISIBLE_FOR", "Field visible on profile for these groups");
define("_AM_XADDRESSES_PROF_VISIBLE", "Visibility");
define("_AM_XADDRESSES_PROF_EDITABLE", "Field editable from profile");
define("_AM_XADDRESSES_PROF_REGISTER", "Show in registration form");
define("_AM_XADDRESSES_PROF_SEARCH", "Searchable by these groups");
define("_AM_XADDRESSES_PROF_ACCESS", "Profile accessible by these groups");
define("_AM_XADDRESSES_PROF_ACCESS_DESC",
        "<ul>" .
        "<li>Admin groups: If a user belongs to admin groups, the current user has access if and only if one of the current user's groups is allowed to access admin group; else</li>" .
        "<li>Non basic groups: If a user belongs to one or more non basic groups (NOT admin, user, anonymous), the current user has access if and only if one of the current user's groups is allowed to allowed to any of the non basic groups; else</li>" .
        "<li>User group: If a user belongs to User group only, the current user has access if and only if one of his groups is allowed to access User group</li>" .
        "</ul>");

define("_AM_XADDRESSES_FIELDVISIBLE", "The field ");
define("_AM_XADDRESSES_FIELDVISIBLEFOR", " is visible for ");
define("_AM_XADDRESSES_FIELDVISIBLEON", " viewing a profile of ");
define("_AM_XADDRESSES_FIELDVISIBLETOALL", "- Everyone");
define("_AM_XADDRESSES_FIELDNOTVISIBLE", "is not visible");




// User management
define("_AM_XADDRESSES_EDITUSER", "Edit User");
define("_AM_XADDRESSES_SELECTUSER", "Select User");
define("_AM_XADDRESSES_ADDUSER","Add User");
define("_AM_XADDRESSES_THEME","Theme");
define("_AM_XADDRESSES_RANK","Rank");
define("_AM_XADDRESSES_USERDONEXIT","User doesn't exist!");
define("_XADDRESSES_MA_USERLEVEL", "User Level");

define("_XADDRESSES_MA_ACTIVE", "Active");
define("_XADDRESSES_MA_INACTIVE", "Inactive");


define("_AM_XADDRESSES_USERCREATED", "User Created");
define("_AM_XADDRESSES_CANNOTDELETESELF", "Deleting your own account is not allowed - use your profile page to delete your own account");
define("_AM_XADDRESSES_CANNOTDELETEADMIN", "Deleting an administrator account is not allowed");

define("_AM_XADDRESSES_NOSELECTION", "No user selected");
define("_AM_XADDRESSES_USER_ACTIVATED", "User activated");
define("_AM_XADDRESSES_USER_DEACTIVATED", "User deactivated");
define("_AM_XADDRESSES_USER_NOT_ACTIVATED", "Error: User NOT activated");
define("_AM_XADDRESSES_USER_NOT_DEACTIVATED", "Error: User NOT deactivated");
*/
?>