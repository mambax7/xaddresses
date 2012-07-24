<?php   
/**
 * ****************************************************************************
 *  - A Project by Developers TEAM For Xoops - ( http://www.xoops.org )
 * ****************************************************************************
 *  XADDRESSES - MODULE FOR XOOPS
 *  Copyright (c) 2007 - 2012
 *  Rota Lucio ( http://luciorota.altervista.org/xoops/ )
 *
 *  You may not change or alter any portion of this comment or credits
 *  of supporting developers from this source code or any supporting
 *  source code which is considered copyrighted (c) material of the
 *  original comment or credit authors.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  ---------------------------------------------------------------------------
 *  @copyright  Rota Lucio ( http://luciorota.altervista.org/xoops/ )
 *  @license    GPL see LICENSE
 *  @package    xaddresses
 *  @author     Rota Lucio ( lucio.rota@gmail.com )
 *
 *  Version : 1.0 Mon 2012/07/23 14:17:53 : XOOPS Exp $
 * ****************************************************************************
 */

include "admin_header.php";
xoops_cp_header();

define('_RED', '#FF0000'); // Red
define('_GREEN', '#00AA00'); // Green



// load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
// IN PROGRESS
$brokenHandler =& xoops_getModuleHandler('broken', 'xaddresses');
$modifyHandler =& xoops_getModuleHandler('modify', 'xaddresses');
//$addresses_mod_Handler =& xoops_getModuleHandler('xaddresses_mod', 'xaddresses');

// count location categories
$countCategories = $categoryHandler->getCount();

// count valid locations
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_suggested', false));
$criteria->add(new Criteria('loc_status', 0, '!=')); // valid location
$countLocations = $locationHandler->getCount($criteria);
unset($criteria);

// count waiting/not valid locations
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_suggested', false));
$criteria->add(new Criteria('loc_status', 0));
$countWaitingLocations = $locationHandler->getCount($criteria);
unset($criteria);

// count wrong locations reports
$brokenReports = $brokenHandler->getall();
$countBrokenReports = $brokenHandler->getCount();
$countBrokenReportsByLoc = array();
foreach($brokenReports as $brokenReport) {
    $broken_loc_id = $brokenReport->getVar('loc_id');
    if (isset($countBrokenReportsByLoc[$broken_loc_id]))
        $countBrokenReportsByLoc[$broken_loc_id] = $countBrokenReportsByLoc[$broken_loc_id] + 1;
    else
        $countBrokenReportsByLoc[$broken_loc_id] = 1;
}
$countBrokenLocations = count($countBrokenReportsByLoc);

// count modified locations
$modifySuggests = $modifyHandler->getall();
$countModifySuggests = $modifyHandler->getCount();
$countModifySuggestsByLoc = array();
foreach($modifySuggests as $modifySuggest) {
    $modify_loc_id = $modifySuggest->getVar('loc_id');
    if (isset($countModifySuggestsByLoc[$broken_loc_id]))
        $countModifySuggestsByLoc[$modify_loc_id] = $countBrokenReportsByLoc[$broken_loc_id] + 1;
    else
        $countModifySuggestsByLoc[$modify_loc_id] = 1;
}
$countModifyLocations = count($countModifySuggestsByLoc);

// xaddresses directory
$folder = array(
    XOOPS_ROOT_PATH . '/uploads/xaddresses/'
    );

if (xaddresses_checkModuleAdmin()){
    $indexAdmin = new ModuleAdmin();
    $indexAdmin->addInfoBox(_AM_XADDRESSES_INDEX_DATABASE);
    $indexAdmin->addInfoBoxLine(_AM_XADDRESSES_INDEX_DATABASE, _AM_XADDRESSES_INDEX_COUNTCATEGORIES, $countCategories);
    $indexAdmin->addInfoBoxLine(_AM_XADDRESSES_INDEX_DATABASE, _AM_XADDRESSES_INDEX_COUNTLOCATIONS, $countLocations);
    if ($countWaitingLocations == 0){
        $indexAdmin->addInfoBoxLine(_AM_XADDRESSES_INDEX_DATABASE, _AM_XADDRESSES_INDEX_COUNTWAITING, $countWaitingLocations, _GREEN);
    } else {
        $indexAdmin->addInfoBoxLine(_AM_XADDRESSES_INDEX_DATABASE, _AM_XADDRESSES_INDEX_COUNTWAITING, $countWaitingLocations, _RED);
    }
    if ($countBrokenLocations == 0){
        $indexAdmin->addInfoBoxLine(_AM_XADDRESSES_INDEX_DATABASE, _AM_XADDRESSES_INDEX_COUNTBROKEN, $countBrokenLocations, _GREEN);
    }else{
        $indexAdmin->addInfoBoxLine(_AM_XADDRESSES_INDEX_DATABASE, _AM_XADDRESSES_INDEX_COUNTBROKEN, $countBrokenLocations, _RED);
    }
    if ($countModifyLocations == 0){
        $indexAdmin->addInfoBoxLine(_AM_XADDRESSES_INDEX_DATABASE, _AM_XADDRESSES_INDEX_COUNTMODIFIED, $countModifyLocations, _GREEN);
    }else{
        $indexAdmin->addInfoBoxLine(_AM_XADDRESSES_INDEX_DATABASE, _AM_XADDRESSES_INDEX_COUNTMODIFIED, $countModifyLocations, _RED);
    }
    foreach (array_keys($folder) as $i) {
        $indexAdmin->addConfigBoxLine($folder[$i], 'folder');
        $indexAdmin->addConfigBoxLine(array($folder[$i], '777'), 'chmod');
    }
    echo $indexAdmin->addNavigation('index.php');
    echo $indexAdmin->renderIndex();
}


include "admin_footer.php";
?>