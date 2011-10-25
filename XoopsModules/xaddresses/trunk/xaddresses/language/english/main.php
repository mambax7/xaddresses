<?php
// locationview.php:
define("_XADDRESSES_MD_LOC_COMMENTS","Comments (%s)");
//define("_XADDRESSES_MD_LOC_NBTELECH","Viewed %s time");
//define("_XADDRESSES_MD_LOC_NONEXISTENT","this download does not exist in our database");
define("_XADDRESSES_MD_LOC_RATING","Rate");
define("_XADDRESSES_MD_LOC_VOTES","(%s votes)");
define("_XADDRESSES_MD_LOC_MODIFY","Modify");
define("_XADDRESSES_MD_LOC_RATELOCATION","Rate location");
define("_XADDRESSES_MD_LOC_REPORTBROKEN","Repport broken location");
define("_XADDRESSES_MD_LOC_REPORTMODIFY","Suggest location correction/modification");
define("_XADDRESSES_MD_LOC_TELLAFRIEND","Send to a Friend");
define("_XADDRESSES_MD_LOC_INTLOCATIONFOUND","Here is an important location %s");

// locationbroken.php
define("_XADDRESSES_MD_LOC_BROKEN_REPORTBROKEN","Broken Location Report");
define("_XADDRESSES_MD_LOC_BROKEN_THANKSFORHELP","Thank you for helping to maintain this database's integrity.");
define("_XADDRESSES_MD_LOC_BROKEN_FORSECURITY","For security reasons your user name and IP address will also be temporarily recorded.");
define("_XADDRESSES_MD_LOC_BROKEN_ALREADYREPORTED","You have already submitted an error report for this location.");
define("_XADDRESSES_MD_LOC_BROKEN_THANKSFORINFO","Thanks for the information. We'll look into your request shortly.");

// locationmodify.php
define("_XADDRESSES_MD_LOC_MODIFY_SUGGESTMODIFY","Suggest Location Correction/modification");
define("_XADDRESSES_MD_LOC_MODIFY_THANKSFORHELP","Thank you for helping to maintain this database's integrity.");
define("_XADDRESSES_MD_LOC_MODIFY_FORSECURITY","For security reasons your user name and IP address will also be temporarily recorded.");
define("_XADDRESSES_MD_LOC_MODIFY_CAPTION","<h3>Corrected/Modified data</h3>");
define("_XADDRESSES_MD_LOC_MODIFY_VALUE",
"<ul>
<li>Insert here below your Corrections/Modifications</li>
<li>This form shows original values in a not modificable line, and 
</b>
");
define("_XADDRESSES_MD_LOC_MODIFY_ALREADYSUGGESTED","You have already submitted an error report for this location.");
define("_XADDRESSES_MD_LOC_MODIFY_THANKSFORINFO","Thanks for the information. We'll look into your request shortly.");

// locationrate.php
define("_XADDRESSES_MD_LOC_RATE_BEOBJECTIVE","Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful.");
define("_XADDRESSES_MD_LOC_RATE_CANTVOTEOWN","Do not vote for your own resource.<br />All votes are recorded and verified.");
define("_XADDRESSES_MD_LOC_RATE_DONOTVOTE","Do not vote for your own resource.");
define("_XADDRESSES_MD_LOC_RATE_RATINGSCALE","The scale is 1 - 10, with 1 being poor and 10 being excellent.");
define("_XADDRESSES_MD_LOC_RATE_VOTE","Vote");
define("_XADDRESSES_MD_LOC_RATE_VOTEOK","Your vote is appreciated.<br />Thank you for taking the time to vote here");
define("_XADDRESSES_MD_LOC_RATE_VOTEONCE","Please do not vote for the same resource more than once.");

// submit.php
//define("_XADDRESSES_MD_SUBMIT_NEW","IN_PROGRESS");
//define("_XADDRESSES_MD_SUBMIT_EDIT","Edit");

define("_XADDRESSES_MD_SUBMIT_ALLPENDING","All location information are posted pending verification.");
define("_XADDRESSES_MD_SUBMIT_DONTABUSE","Username and IP are recorded, so please don't abuse the system.");
//define("_XADDRESSES_MD_SUBMIT_ISAPPROVED","Your Locatione has beeen approved");
//define("_XADDRESSES_MD_SUBMIT_PROPOSER","Submit a location");
//define("_XADDRESSES_MD_SUBMIT_RECEIVED","We have received your location info. Thank you !");
define("_XADDRESSES_MD_SUBMIT_SUBMITONCE","Submit your location only once.");
define("_XADDRESSES_MD_SUBMIT_TAKEDAYS","This will take many days to see your location addedd successfully in our database.");

// locationsearch.php
define("_XADDRESSES_MD_SEARCH","Search");
define("_XADDRESSES_MD_SEARCH_TITLE", "Filter location title");
define("_XADDRESSES_MD_SEARCH_TITLE_DESC", "It's possible to choose bewteen various matchs");
define("_XADDRESSES_MD_SEARCH_MAXDISTANCE", "Filter by distance from");
define("_XADDRESSES_MD_SEARCH_MAXDISTANCE_DESC", "It's possible to filter by distance from a choosen point");
define("_XADDRESSES_MD_SEARCH_CAT", "Filter by categories");
define("_XADDRESSES_MD_SEARCH_CAT_DESC", "It's possible to select more categories");
define("_XADDRESSES_MD_SEARCH_PERPAGE", "Locations per page");
//define("_XADDRESSES_MD_SEARCH","Filter in the modules list");
//define("_XADDRESSES_MD_SEARCH_ALL1","All");
//define("_XADDRESSES_MD_SEARCH_ALL2","All");
//define("_XADDRESSES_MD_SEARCH_BT","Search");
//define("_XADDRESSES_MD_SEARCH_CATEGORIES","Categories");
//define("_XADDRESSES_MD_SEARCH_DATE","Date");
//define("_XADDRESSES_MD_SEARCH_DOWNLOAD","Address ");
//define("_XADDRESSES_MD_SEARCH_HITS","Hits");
//define("_XADDRESSES_MD_SEARCH_NOTE","Rate");
//define("_XADDRESSES_MD_SEARCH_PAGETITLE","File List");
//define("_XADDRESSES_MD_SEARCH_THEREARE","There are<b>%s</b> locations(s)");
//define("_XADDRESSES_MD_SEARCH_TITLE","Name");


// -------------------------------- IN PROGRESS







/*





// index.php
define("_XADDRESSES_MD_INDEX_BLDATE","Recent Addresses :");
define("_XADDRESSES_MD_INDEX_BLNAME","Summary");
define("_XADDRESSES_MD_INDEX_BLRATING","Top rated files :");
define("_XADDRESSES_MD_INDEX_BLPOP","Top Addresses :");
    define("_XADDRESSES_MD_INDEX_DLNOW","Download now !");
define("_XADDRESSES_MD_INDEX_LATESTLIST","Latest lists");
define("_XADDRESSES_MD_INDEX_NEWTHISWEEK","New this week");
define("_XADDRESSES_MD_INDEX_POPULAR","Popular");
define("_XADDRESSES_MD_INDEX_UPTHISWEEK","Updated this Week");
define("_XADDRESSES_MD_INDEX_SCAT","Sub Categories: ");
define("_XADDRESSES_MD_INDEX_SUBMITDATE","Submitted Date ");
define("_XADDRESSES_MD_INDEX_SUBMITTER","Submitted by: ");
define("_XADDRESSES_MD_INDEX_THEREARE","There are<b>%s</b> file(s) in our databse");

// viewcat.php:
define("_XADDRESSES_MD_CAT_CURSORTBY","Files currently sorted by : %s");
define("_XADDRESSES_MD_CAT_DATE","Date");
define("_XADDRESSES_MD_CAT_DATENEW","Date (Descending)");
define("_XADDRESSES_MD_CAT_DATEOLD","Date (Ascending)");
define("_XADDRESSES_MD_CAT_HITS","Hits");
define("_XADDRESSES_MD_CAT_LIST","List");
define("_XADDRESSES_MD_CAT_NONEXISTENT","This category does not exist");
define("_XADDRESSES_MD_CAT_POPULARITY","Popularity;");
define("_XADDRESSES_MD_CAT_POPULARITYLTOM","Popularity; (from - to + Addresses)");
define("_XADDRESSES_MD_CAT_POPULARITYMTOL","Popularity; (from + to - Addresses)");
define("_XADDRESSES_MD_CAT_RATING","Rate");
define("_XADDRESSES_MD_CAT_RATINGLTOH","Rating (from - to + hight score)");
define("_XADDRESSES_MD_CAT_RATINGHTOL","Rating (from + to - hight score)");
define("_XADDRESSES_MD_CAT_SORTBY","Sorted by :");
define("_XADDRESSES_MD_CAT_SUMMARY","Summary");
define("_XADDRESSES_MD_CAT_THEREARE","There are <b>%s</b> location(s) in this category");
define("_XADDRESSES_MD_CAT_TITLE","Title");
define("_XADDRESSES_MD_CAT_TITLEATOZ","Title (A to Z)");
define("_XADDRESSES_MD_CAT_TITLEZTOA","Title (Z to A)");
define("_XADDRESSES_MD_CAT_VOTE","Vote");



// modfile.php
define("_XADDRESSES_MD_MODFILE_THANKSFORINFO","Thanks for the information. We'll look into your request shortly.");


//search.php
define("_XADDRESSES_MD_SEARCH","Filter in the modules list");
define("_XADDRESSES_MD_SEARCH_ALL1","All");
define("_XADDRESSES_MD_SEARCH_ALL2","All");
define("_XADDRESSES_MD_SEARCH_BT","Search");
define("_XADDRESSES_MD_SEARCH_CATEGORIES","Categories");
define("_XADDRESSES_MD_SEARCH_DATE","Date");
define("_XADDRESSES_MD_SEARCH_DOWNLOAD","Address ");
define("_XADDRESSES_MD_SEARCH_HITS","Hits");
define("_XADDRESSES_MD_SEARCH_NOTE","Rate");
define("_XADDRESSES_MD_SEARCH_PAGETITLE","File List");
define("_XADDRESSES_MD_SEARCH_THEREARE","There are<b>%s</b> file(s)");
define("_XADDRESSES_MD_SEARCH_TITLE","Name");

// formulaire
define("_XADDRESSES_AM_FORMADD","Add");
define("_XADDRESSES_AM_FORMEDIT","Edit");
define("_XADDRESSES_AM_FORMFILE","File");
define("_XADDRESSES_AM_FORMHOMEPAGE","Home Page");
define("_XADDRESSES_AM_FORMINCAT","in the category");
define("_XADDRESSES_AM_FORMIMG","Screen Shot");
define("_XADDRESSES_AM_FORMTEXTADDRESSES","Description : <br /><br />Use the delimiter '<b>[pagebreak]</b>' to difine the size of the short description. <br /> The short description <br /> allows to reduce the text size in the homepage of the module and categories .");
define("_XADDRESSES_AM_FORMTITLE","Title");

define("_XADDRESSES_AM_FORMPLATFORM","Plateform");
define("_XADDRESSES_AM_FORMSIZE","File Size(bytes)");

    define("_XADDRESSES_AM_FORMURL","Download URL");
define("_XADDRESSES_AM_FORMVERSION","Version");

//générique
define("_XADDRESSES_MD_EDITTHISDL","Edit this download");
define("_XADDRESSES_MD_MOREDETAILS",">> more details");
define("_XADDRESSES_MD_NUMBYTES","%s bytes");

//visit.php
define("_XADDRESSES_MD_NOPERMISETOLINK", "This file does not belongs to the website grom where you are comming.<br /><br />thanks for writng an email to the webmaster of the website from where you are comming and telling him :<br /><b>NO OWNERSHIP OF LINKS FROM OTHER SITES !! (LEECH)</b><br /><br /><b>Leecher definition :</b> Someone who is lazy to link to its own server or steals the hard work done by other people <br /><br />You are already <b>registered</b>.");

//Message d'erreur
define("_XADDRESSES_MD_ERROR_NOCAT","You have to choose a category!");
define("_XADDRESSES_MD_ERROR_SIZE","File size must be a number");

//pour xoops france:
define("_XADDRESSES_MD_SUP","<br /><br />[block]: Blocks<br />[notes]: Notes<br />[evolutions]: Envisaged Developments<br />[infos]: Informations<br />[changelog]: Changelog<br />[backoffice]: Back Office<br />[frontoffice]: Front Office");
define("_XADDRESSES_MD_SUP_BACKOFFICE","Back Office:");
define("_XADDRESSES_MD_SUP_BLOCS","Blocks:");
define("_XADDRESSES_MD_SUP_CHANGELOG","Changelog:");
define("_XADDRESSES_MD_SUP_EVOLUTIONS","Envisaged Developments:");
define("_XADDRESSES_MD_SUP_FRONTOFFICE","Front Office:");
define("_XADDRESSES_MD_SUP_INFOS","Informations:");
define("_XADDRESSES_MD_SUP_NOTES","Notes:");
*/
?>