<?php
include_once 'header.php';
// template d'affichage
$xoopsOption['template_main'] = 'xaddresses_locationbroken.html';
include_once XOOPS_ROOT_PATH.'/header.php';
//On recupere la valeur de l'argument op dans l'URL$
if (isset($_REQUEST['op'])) {
	$op = $_REQUEST['op'];
} else {
	$op = 'liste';
}
//appel des class
$addressescat_Handler =& xoops_getModuleHandler('xaddresses_cat', 'xaddresses');
$addresses_Handler =& xoops_getModuleHandler('xaddresses_addresses', 'xaddresses');
$addressesvotedata_Handler =& xoops_getModuleHandler('xaddresses_votedata', 'xaddresses');
$addressesbroken_Handler =& xoops_getModuleHandler('xaddresses_broken', 'xaddresses');

// redirection si le téléchargement n'existe pas
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_id', intval($_REQUEST['loc_id'])));
if ($addresses_Handler->getCount($criteria) == 0){
    redirect_header('index.php', 3, _MD_XADDRESSES_SINGLEFILE_NONEXISTENT);
	exit();
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) 
{
	// Vue liste
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
    // save
    case "save":
        $obj =& $addressesbroken_Handler->create();
        if(empty($xoopsUser)){
			$ratinguser = 0;
		}else{
			$ratinguser = $xoopsUser->getVar('uid');
		}
		if ($ratinguser != 0) {   
            // si c'est un membre on vérifie qu'il n'envoie pas 2 fois un rapport
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', intval($_REQUEST['loc_id'])));
            $downloadsbroken_arr = $addressesbroken_Handler->getall($criteria);
            foreach (array_keys($downloadsbroken_arr) as $i) {
				if ($downloadsbroken_arr[$i]->getVar('sender') == $ratinguser) {
					redirect_header('singlefile.php?loc_id=' . intval($_REQUEST['loc_id']), 2, _MD_XADDRESSES_BROKENFILE_ALREADYREPORTED);
					exit();
				}
            }
		} else {
			// si c'est un utilisateur anonyme on vérifie qu'il n'envoie pas 2 fois un rapport
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', intval($_REQUEST['loc_id'])));
            $criteria->add(new Criteria('sender', 0));
            $criteria->add(new Criteria('ip', getenv("REMOTE_ADDR")));
			if ($addressesbroken_Handler->getCount($criteria) >= 1) {
				redirect_header('singlefile.php?loc_id=' . intval($_REQUEST['loc_id']), 2, _MD_XADDRESSES_BROKENFILE_ALREADYREPORTED);
				exit();
			}
		}
        $erreur = false;
        $message_erreur = '';
        // Test avant la validation
        xoops_load("captcha");
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if ( !$xoopsCaptcha->verify() ) {
            $message_erreur.=$xoopsCaptcha->getMessage().'<br />';
            $erreur=true;
        }
        $obj->setVar('loc_id', intval($_REQUEST['loc_id']));
        $obj->setVar('sender', $ratinguser);
        $obj->setVar('ip', getenv("REMOTE_ADDR"));
        if ($erreur==true){
            $xoopsTpl->assign('message_erreur', $message_erreur);
        }else{
            if ($addressesbroken_Handler->insert($obj)) {
                $tags = array();
                $tags['BROKENREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/broken.php';
                $notification_handler =& xoops_gethandler('notification');
                $notification_handler->triggerEvent('global', 0, 'file_broken', $tags);
                redirect_header('singlefile.php?loc_id=' . intval($_REQUEST['loc_id']), 2, _MD_XADDRESSES_BROKENFILE_THANKSFORINFO);
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
