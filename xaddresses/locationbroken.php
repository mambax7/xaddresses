<?php
$currentFile = basename(__FILE__);

include_once 'header.php';
include_once XOOPS_ROOT_PATH . '/header.php';

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'new_broken';

if (isset($_REQUEST['loc_id'])) {
	$loc_id = (int)($_REQUEST['loc_id']);
} else {
	redirect_header('index.php', 3, _MD_XADDRESSES_SINGLEFILE_NONEXISTENT);
}

$xoopsOption['template_main'] = 'xaddresses_locationbroken.html';

//load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory', 'xaddresses');
$fieldHandler =& xoops_getModuleHandler('field', 'xaddresses');
$memberHandler =& xoops_gethandler('member');

$brokenHandler =& xoops_getModuleHandler('broken', 'xaddresses');
// IN PROGRESS
// TO DO
//$votedataHandler =& xoops_getModuleHandler('votedata', 'xaddresses');

// redirection if location not exists
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_id', $loc_id));
if ($locationHandler->getCount($criteria) == 0){
    redirect_header('index.php', 3, _MD_XADDRESSES_SINGLEFILE_NONEXISTENT);
	exit();
}



switch ($op) {
    default:
    case "new_broken":
        $newReport =& $brokenHandler->create();
        $form = $newReport->getForm($loc_id);
        $xoopsTpl->assign('themeForm', $form->render()); 
        echo "è sbagliata" . $loc_id;
    break;
/*
    case "liste":
        //navigation
        $view_downloads = $addresses_Handler->get(intval($_REQUEST['loc_id']));
        $view_categorie = $addressescat_Handler->get($view_downloads->getVar('cid'));
        $categories = tdmdownloads_MygetItemIds();
        if(!in_array($view_downloads->getVar('cid'), $categories)) {
            redirect_header('index.php', 2, _NOPERM);
            exit();
        }        
        
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
        $addressescat_arr = $addressescat_Handler->getall($criteria);
        $mytree = new XoopsObjectTree($addressescat_arr, 'cid', 'pid');
        $nav_parent_id = $mytree->getAllParent($view_downloads->getVar('cid'));
        $titre_page = $nav_parent_id;
        $nav_parent_id = array_reverse($nav_parent_id);
        $navigation = '<a href="index.php">' . $xoopsModule->name() . '</a>&nbsp;:&nbsp;';
        foreach (array_keys($nav_parent_id) as $i) {
            $navigation .= '<a href="viewcat.php?cid=' . $nav_parent_id[$i]->getVar('cid') . '">' . $nav_parent_id[$i]->getVar('title') . '</a>&nbsp;:&nbsp;';
        }
        $navigation .= '<a href="viewcat.php?cid=' . $view_categorie->getVar('cid') . '">' . $view_categorie->getVar('title') . '</a>&nbsp;:&nbsp;<a href="singlefile.php?loc_id=' . $view_downloads->getVar('loc_id') . '">' . $view_downloads->getVar('title') . '</a>&nbsp;:&nbsp;';
        $navigation .= _MD_XADDRESSES_SINGLEFILE_REPORTBROKEN;
        $xoopsTpl->assign('navigation', $navigation);
        
        // référencement
        // titre de la page        
        $titre = _MD_XADDRESSES_SINGLEFILE_REPORTBROKEN . '&nbsp;-&nbsp;' . $view_downloads->getVar('title') . '&nbsp;-&nbsp;' . $view_categorie->getVar('title') . '&nbsp;-&nbsp;';
        foreach (array_keys($titre_page) as $i) {
            $titre .= $titre_page[$i]->getVar('title') . '&nbsp;-&nbsp;';
        }
        $titre .= $xoopsModule->name();
        $xoopsTpl->assign('xoops_pagetitle', $titre);
        //description
        $xoTheme->addMeta( 'meta', 'description', strip_tags(_MD_XADDRESSES_SINGLEFILE_REPORTBROKEN . ' (' . $view_downloads->getVar('title') . ')'));
        
        //Affichage du formulaire de fichier brisé
        $obj =& $addressesbroken_Handler->create();
            $form = $obj->getForm(intval($_REQUEST['loc_id']));
        $xoopsTpl->assign('themeForm', $form->render());    
    break;
*/
    // save
    case "save_broken":
        $newReport =& $brokenHandler->create();
        if(empty($xoopsUser)){
            $reportingUserId = 0;
			// si c'est un utilisateur anonyme on vérifie qu'il n'envoie pas 2 fois un rapport
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', $loc_id));
            $criteria->add(new Criteria('report_sender', 0));
            $criteria->add(new Criteria('report_ip', getenv("REMOTE_ADDR")));
			if ($brokenHandler->getCount($criteria) >= 1) {
                redirect_header('locationview.php?loc_id=' . $loc_id, 2, _MD_XADDRESSES_LOC_BROKEN_ALREADYREPORTED);
                exit();
            }
        } else {
            $reportingUserId = $xoopsUser->getVar('uid');
            // si c'est un membre on vérifie qu'il n'envoie pas 2 fois un rapport
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', $loc_id));
            $brokenReports = $brokenHandler->getall($criteria);
            foreach ($brokenReports as $brokenReport) {
				if ($brokenReport->getVar('report_sender') == $reportingUserId) {
					redirect_header('locationview.php?loc_id=' . $loc_id, 2, _MD_XADDRESSES_LOC_BROKEN_ALREADYREPORTED);
					exit();
				}
            }
        }

        $error = false;
        $error_message = '';
        // Test avant la validation
        xoops_load("captcha");
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if ( !$xoopsCaptcha->verify() ) {
            $error_message.= $xoopsCaptcha->getMessage().'<br />';
            $error = true;
        }
        $newReport->setVar('loc_id', $loc_id);
        $newReport->setVar('report_sender', $reportingUserId);
        $newReport->setVar('report_ip', getenv("REMOTE_ADDR"));
        $newReport->setVar('report_date', time()); // creation date
        $newReport->setVar('report_description', $_POST['report_description']);
        if ($error == true) {
            $xoopsTpl->assign('error_message', $error_message);
        } else {
            if ($brokenHandler->insert($newReport)) {
                $tags = array();
                $tags['BROKENREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/broken.php';
                $notification_handler =& xoops_gethandler('notification');
                $notification_handler->triggerEvent('global', 0, 'file_broken', $tags);
                redirect_header('locationview.php?loc_id=' . $loc_id, 2, _MD_XADDRESSES_LOC_BROKEN_THANKSFORINFO);
            }
            echo $obj->getHtmlErrors();
        }
        //Affichage du formulaire de notation des téléchargements
    	$form =& $obj->getForm(intval($_REQUEST['loc_id']));
        $xoopsTpl->assign('themeForm', $form->render());   
        
    break;    
}

include XOOPS_ROOT_PATH.'/footer.php';
?>
