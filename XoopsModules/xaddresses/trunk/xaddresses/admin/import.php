<?php
$currentFile = basename(__FILE__);

// include module admin header
include_once 'admin_header.php';

//load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$fieldHandler =& xoops_getModuleHandler('field', 'xaddresses');
$memberHandler =& xoops_gethandler('member');

// count location categories
$countCategories = $categoryHandler->getCount();
// redirection if no categories
if ($categoryHandler->getCount() == 0) {
    //redirect_header('index.php', 2, _AM_XADDRESSES_PERM_NOCAT);
}

// get/check parameters/post
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'import';

// render start here
xoops_cp_header();






$checkAddresses = xaddresses_checkModule('addresses');

switch ($op) {
    case 'import':
    default:
        echo "<div class='errorMsg'>";
        echo _AM_XADDRESSES_IMPORT_WARNING;
        echo "</div>";
        echo "<br />";
        echo '<div class="head">';
        if ($checkAddresses == "1.7")
            echo '<br /><a href="' . $currentFile . '?op=form_import_addresses_17">' . _AM_XADDRESSES_IMPORT_ADDRESSES17 . '</a><br />';
        if ($checkAddresses == "1.72")
        echo '<br /><a href="' . $currentFile . '?op=form_import_addresses_172">' . _AM_XADDRESSES_IMPORT_ADDRESSES172 . '</a>';
        echo '</div>';
        break;
    // import Addresses 1.7 + Google Maps
    case "import_addresses_17":
        // TO DO
        // TO DO
        // TO DO
        break;
    case "form_import_addresses_17":
        // TO DO
        // TO DO
        // TO DO
        break;
    // import Addresses 1.72 + Google Maps
    case "import_addresses_172":
        if ($_REQUEST['shots'] == '' || $_REQUEST['catimg'] == ''){
            redirect_header($currentFile . '?op=import_form_addresses_172', 3, _AM_XADDRESSES_IMPORT_ERROR);
        } else {
            //Import_wfdownloads($_REQUEST['shots'],$_REQUEST['catimg']);
        }
        break;
    case "form_import_addresses_172":
        global $xoopsDB;
        $sql = "SELECT COUNT(lid) as count";
        $sql.= " FROM ". $xoopsDB->prefix("addresses_links");
        $query = $xoopsDB->query($sql);
        list($countAddresses) = $xoopsDB->fetchRow($query) ;
        $sql = "SELECT COUNT(cid) as count";
        $sql.= " FROM ".$xoopsDB->prefix("addresses_cat");
        $query = $xoopsDB->query($sql);
        list($countCategories) = $xoopsDB->fetchRow($query) ;

        if(($countAddresses < 1) || ($countCategories < 1)) {
            echo "<fieldset>";
            echo "<legend style='font-weight: bold; color: #900;'>" . _AM_XADDRESSES_IMPORT_NUMBER . "</legend>";
            echo _AM_XADDRESSES_IMPORT_ADDRESSES_DONT;
            echo "<br />";
            echo _AM_XADDRESSES_IMPORT_CATEGORIES_DONT;
            echo "</fieldset>";
        } else {
            echo "<fieldset>";
            echo "<legend style='font-weight: bold; color: #900;'>" . _AM_XADDRESSES_IMPORT_NUMBER . "</legend>";
            echo sprintf(_AM_XADDRESSES_IMPORT_ADDRESSES_TOIMPORT, $countAddresses);
            echo "<br />";
            echo sprintf(_AM_XADDRESSES_IMPORT_CATEGORIES_TOIMPORT, $countCategories);
            echo "</fieldset>";
            echo "<br />";
            $form = new XoopsThemeForm(_AM_XADDRESSES_IMPORT_FORM, 'importform', $currentFile, 'post');

            // TO DO
            // TO DO
            // TO DO
            // import source categories
            $moduleHandler =& xoops_gethandler('module');
            $importModule =& $moduleHandler->getByDirname('addrsses');

            $sourceCategoriesTree = new XoopsTree ("addresses_cat", "cid", "pid");

            $tree = $sourceCategoriesTree->getChildTreeArray(0, "", array(), "--");
            print_r($tree);
            exit;
            // TO DO
            // TO DO
            // TO DO


                $formSourceCategories = new XoopsFormLabel(_AM_XADDRESSES_IMPORT_SOURCE_CATEGORIES, $sourceCategoriesTree->makeMySelBox ('source_categories', '', 0, 0, 'title', ''));
                $formSourceCategories->setDescription(_AM_XADDRESSES_IMPORT_SOURCE_CATEGORIES_DESC);
            $form->addElement($formSourceCategories, true);

            // import destination category
            $criteria = new CriteriaCompo();
            $criteria->setSort('cat_weight ASC, cat_title');
            $criteria->setOrder('ASC');
            $destinationCategoriesArray = $categoryHandler->getall($criteria);
            $destinationCategoriesTree = new XoopsObjectTree($destinationCategoriesArray, 'cat_id', 'cat_pid');
                $formDestinationCategory = new XoopsFormLabel(_AM_XADDRESSES_IMPORT_DESTINATION_CATEGORY, $destinationCategoriesTree->makeSelBox('loc_cat_id', 'cat_title', '--', null, true));
                $formDestinationCategory->setDescription(_AM_XADDRESSES_IMPORT_DESTINATION_CATEGORY_DESC);
            $form->addElement($formDestinationCategory, true);

            $form->addElement(new XoopsFormHidden('op', '=import_addresses_17'));
            // Submit button
                $button_tray = new XoopsFormElementTray(_AM_XADDRESSES_ACTION, '' ,'');
                $button_tray->addElement(new XoopsFormButton('', 'submit', _AM_XADDRESSES_IMPORT, 'submit'));
                $button_tray->addElement(new XoopsFormButton('', 'reset', _RESET, 'reset'));
                    $cancel_button = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
                    $cancel_button->setExtra("onclick='javascript:history.back();'");
                $button_tray->addElement($cancel_button);
            $form->addElement($button_tray);

            $output = $form->render();
            echo $output;
/*
            echo "<table width='100%' border='0'>
            <form action='{$currentFile}?op=import_addresses_17' method=POST>
            <tr>
                <td  class='even'>" . _AM_XADDRESSES_IMPORT_MYDOWNLOADS_PATH . "</td>
                <td  class='odd'><input type='text' name='path' id='import_data' size='100' value='" . XOOPS_ROOT_PATH . "/modules/mydownloads/images/shots/' /></td>
            </tr>
            <tr>
                <td  class='even'>" . _AM_XADDRESSES_IMPORT_MYDOWNLOADS_URL . "</td>
                <td  class='odd'><input type='text' name='imgurl' id='import_data' size='100' value='" . XOOPS_URL . "/modules/mydownloads/images/shots/' /></td>
            </tr>
            <tr>
                <td  class='even'>" . _AM_XADDRESSES_IMPORT_DOWNLOADS . "</td>
                <td  class='odd'><input type='submit' name='button' id='import_data' value='" . _AM_XADDRESSES_IMPORT . "'></td>
            </tr>
            </form>
        </table>";
*/
        }
        break;
}

include "admin_footer.php";
?>