<?php
include_once 'header.php';

$xoopsOption['template_main'] = 'xaddresses_locationrate.html';
include_once XOOPS_ROOT_PATH.'/header.php';

if (isset($_REQUEST['op'])) {
	$op = $_REQUEST['op'];
} else {
	$op = 'list';
}

$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
$votedataHandler =& xoops_getModuleHandler('votedata', 'xaddresses');

// redirection si le téléchargement n'existe pas
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_id', intval($_REQUEST['loc_id'])));
if ($locationHandler->getCount($criteria) == 0){
    redirect_header('index.php', 3, _MD_XADDRESSES_SINGLEFILE_NONEXISTENT);
	exit();
}
if ($perm_vote == false) {
	redirect_header('index.php', 2, _NOPERM);
    exit();
}


//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) 
{
	// Vue liste
    case "list":
        //navigation
        $view_downloads = $locationHandler->get(intval($_REQUEST['loc_id']));
        $view_categorie = $categoryHandler->get($view_downloads->getVar('cid'));
        $categories = xaddresses_MygetItemIds();
        if(!in_array($view_downloads->getVar('cid'), $categories)) {
            redirect_header('index.php', 2, _NOPERM);
            exit();
        }        
        
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('cat_id', '(' . implode(',', $categories) . ')','IN'));
        $downloadscat_arr = $categoryHandler->getall($criteria);
        $mytree = new XoopsObjectTree($downloadscat_arr, 'cat_id', 'cat_pid');
        $nav_parent_id = $mytree->getAllParent($view_downloads->getVar('cat_id'));
        $title_page = $nav_parent_id;
        $nav_parent_id = array_reverse($nav_parent_id);
        $navigation = '<a href="index.php">' . $xoopsModule->name() . '</a>&nbsp;:&nbsp;';
        foreach (array_keys($nav_parent_id) as $i) {
            $navigation .= '<a href="viewcat.php?cat_id=' . $nav_parent_id[$i]->getVar('cat_id') . '">' . $nav_parent_id[$i]->getVar('cat_title') . '</a>&nbsp;:&nbsp;';
        }
        $navigation .= '<a href="viewcat.php?cat_id=' . $view_categorie->getVar('cat_id') . '">' . $view_categorie->getVar('cat_title') . '</a>&nbsp;:&nbsp;<a href="singleaddress.php?loc_id=' . $view_downloads->getVar('loc_id') . '">' . $view_downloads->getVar('loc_title') . '</a>&nbsp;:&nbsp;';
        $navigation .= _MD_XADDRESSES_SINGLEFILE_RATHFILE;
        $xoopsTpl->assign('navigation', $navigation);
        
        // référencement
        // title de la page        
        $title = _MD_XADDRESSES_SINGLEFILE_RATHFILE . '&nbsp;-&nbsp;' . $view_downloads->getVar('title') . '&nbsp;-&nbsp;' . $view_categorie->getVar('title') . '&nbsp;-&nbsp;';
        foreach (array_keys($title_page) as $i) {
            $title .= $title_page[$i]->getVar('title') . '&nbsp;-&nbsp;';
        }
        $title .= $xoopsModule->name();
        $xoopsTpl->assign('xoops_pagetitle', $title);
        //description
        $xoTheme->addMeta( 'meta', 'description', strip_tags(_MD_XADDRESSES_SINGLEFILE_RATHFILE . ' (' . $view_downloads->getVar('title') . ')'));

        
        //Affichage du formulaire de notation des téléchargements
    	$obj =& $votedataHandler->create();
    	$form = $obj->getForm(11,intval($_REQUEST['loc_id']));
        $xoopsTpl->assign('themeForm', $form->render());    
    break;
    // save
    case "save":
        $obj =& $votedataHandler->create();
        if(empty($xoopsUser)){
			$ratinguser = 0;
		}else{
			$ratinguser = $xoopsUser->getVar('uid');
		}
        // si c'est un membre on vérifie qu'il ne vote pas pour son fichier
		if ($ratinguser != 0) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', intval($_REQUEST['loc_id'])));
            $addresses = $locationHandler->getall($criteria);
            foreach (array_keys($addresses) as $i) {
				if ($addresses[$i]->getVar('submitter') == $ratinguser) {
					redirect_header('singleaddress.php?loc_id=' . intval($_REQUEST['loc_id']), 2, _MD_XADDRESSES_RATEFILE_CANTVOTEOWN);
					exit();
				}
            }    
            // si c'est un membre on vérifie qu'il ne vote pas 2 fois
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', intval($_REQUEST['loc_id'])));
            $downloadsvotes_arr = $votedataHandler->getall($criteria);
            foreach (array_keys($downloadsvotes_arr) as $i) {
				if ($downloadsvotes_arr[$i]->getVar('ratinguser') == $ratinguser) {
					redirect_header('singleaddress.php?loc_id=' . intval($_REQUEST['loc_id']), 2, _MD_XADDRESSES_RATEFILE_VOTEONCE);
					exit();
				}
            }
		} else {
			// si c'est un utilisateur anonyme on vérifie qu'il ne vote pas 2 fois par jour
			$yesterday = (time()-86400);
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', intval($_REQUEST['loc_id'])));
            $criteria->add(new Criteria('ratinguser', 0));
            $criteria->add(new Criteria('ratinghostname', getenv("REMOTE_ADDR")));
            $criteria->add(new Criteria('ratingtimestamp', $yesterday, '>'));
			if ($votedataHandler->getCount($criteria) >= 1) {
				redirect_header('singleaddress.php?loc_id=' . intval($_REQUEST['loc_id']), 2, _MD_XADDRESSES_RATEFILE_VOTEONCE);
				exit();
			}
		}
        $error = false;
        $message_error = '';
        // Test avant la validation
        $rating = intval($_POST['rating']);
        if ($rating < 0 || $rating > 10) {
            $message_error.= _MD_NORATING.'<br />';
            $error = true;
        }	
        xoops_load("captcha");
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if ( !$xoopsCaptcha->verify() ) {
            $message_error.= $xoopsCaptcha->getMessage() . '<br />';
            $error = true;
        }
        $obj->setVar('loc_id', intval($_REQUEST['loc_id']));
        $obj->setVar('ratinguser', $ratinguser);
        $obj->setVar('rating', $rating);
        $obj->setVar('ratinghostname', getenv("REMOTE_ADDR"));
        $obj->setVar('ratingtimestamp', time());
        if ($error == true){
            $xoopsTpl->assign('message_error', $message_error);
        }else{
            if ($votedataHandler->insert($obj)) {
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('loc_id', intval($_REQUEST['loc_id'])));
                $downloadsvotes_arr = $votedataHandler->getall($criteria);
                $total_vote = $votedataHandler->getCount($criteria);
                $total_rating = 0;
                foreach (array_keys($downloadsvotes_arr) as $i) {
                    $total_rating += $downloadsvotes_arr[$i]->getVar('rating');
                }            
                $rating = $total_rating / $total_vote;
                $objdownloads =& $locationHandler->get(intval($_REQUEST['loc_id']));
                $objdownloads->setVar('rating', number_format($rating, 1));
                $objdownloads->setVar('votes', $total_vote);
                if ($locationHandler->insert($objdownloads)) {
                    redirect_header('singleaddress.php?loc_id=' . intval($_REQUEST['loc_id']), 2, _MD_XADDRESSES_RATEFILE_VOTEOK);
                }
                echo $objdownloads->getHtmlErrors();
            }
            echo $obj->getHtmlErrors();
        }
        //Affichage du formulaire de notation des téléchargements
    	$form =& $obj->getForm($rating,intval($_REQUEST['loc_id']));
        $xoopsTpl->assign('themeForm', $form->render());   
        
    break;    
}
include XOOPS_ROOT_PATH . '/footer.php';
?>
