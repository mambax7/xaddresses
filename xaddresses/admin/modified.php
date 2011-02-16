<?php
include 'admin_header.php';
//Affichage de la partie haute de l'administration de Xoops
xoops_cp_header();
//On recupere la valeur de l'argument op dans l'URL$
if (isset($_REQUEST['op'])) {
	$op = $_REQUEST['op'];
} else {
	$op = 'liste';
}
//paramètres:
// pour les images des téléchargement:
$uploaddir = XOOPS_ROOT_PATH . '/uploads/xaddresses/images/shots/';
$uploadurl = XOOPS_URL . '/uploads/xaddresses/images/shots/';

/////////////

//appel des class
$downloadscat_Handler =& xoops_getModuleHandler('category', 'xaddresses');
$downloads_Handler =& xoops_getModuleHandler('address', 'xaddresses');
//$downloadsmod_Handler =& xoops_getModuleHandler('mod', 'xaddresses');
//$downloadsfield_Handler =& xoops_getModuleHandler('field', 'xaddresses');
//$downloadsfielddata_Handler =& xoops_getModuleHandler('fielddata', 'xaddresses');
//$downloadsfieldmoddata_Handler =& xoops_getModuleHandler('modfielddata', 'xaddresses');

//appel du menu admin
if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php"))	{
    xaddressesAdminMenu(5, _XADDRESSES_MI_ADMENU5);
} else {
    include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
    loadModuleAdminMenu (5, _XADDRESSES_MI_ADMENU5);
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) 
{
	// Vue liste
    case "liste":
		$criteria = new CriteriaCompo();
		if (isset($_REQUEST['limit'])) {
 			$criteria->setLimit($_REQUEST['limit']);
 			$limit = $_REQUEST['limit'];
 		} else {
 			$criteria->setLimit($xoopsModuleConfig['perpageadmin']);
 			$limit = $xoopsModuleConfig['perpageadmin'];
 		}
		if (isset($_REQUEST['start'])) {
			$criteria->setStart($_REQUEST['start']);
			$start = $_REQUEST['start'];
		} else {
			$criteria->setStart(0);
 			$start = 0;
 		}		
        $criteria->setSort('requestid'); 		
		$criteria->setOrder('ASC');	
		$downloadsmod_arr = $downloadsmod_Handler->getall($criteria);
        $numrows = $downloadsmod_Handler->getCount($criteria);
		if ( $numrows > $limit ) {
			$pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', 'op=liste&limit=' . $limit);
 			$pagenav = $pagenav->renderNav(4);
 		} else {
 			$pagenav = '';
 		}
		//Affichage du tableau des téléchargements modifiés
		if ($numrows > 0) {
			echo '<table width="100%" cellspacing="1" class="outer">';
			echo '<tr>';
			echo '<th align="center">' . _XADDRESSES_AM_FORMTITLE . '</th>';
			echo '<th align="center" width="20%">' . _XADDRESSES_AM_BROKEN_SENDER . '</th>';
			echo '<th align="center" width="15%">'._XADDRESSES_AM_FORMACTION.'</th>';
			echo '</tr>';
			$class = 'odd';
			foreach (array_keys($downloadsmod_arr) as $i) {
				$class = ($class == 'even') ? 'odd' : 'even';                
                $downloads_loc_id = $downloadsmod_arr[$i]->getVar('loc_id');
                $downloads_requestid = $downloadsmod_arr[$i]->getVar('requestid');
                $downloads =& $downloads_Handler->get($downloadsmod_arr[$i]->getVar('loc_id'));
                // pour savoir si le fichier est nouveau
                $downloads_url = $downloads->getVar('url');
                $moddownloads_url = $downloadsmod_arr[$i]->getVar('url');                
                $new_file = ($downloads_url == $moddownloads_url ? false : true);
 				echo '<tr class="' . $class . '">';               
                echo '<td align="center">' . $downloads->getVar('title') . '</td>';
                echo '<td align="center"><b>' . XoopsUser::getUnameFromId($downloadsmod_arr[$i]->getVar('modifysubmitter')) . '</b></td>';
				echo '<td align="center" width="15%">';
				echo '<a href="modified.php?op=view_downloads&downloads_loc_id=' . $downloads_loc_id . '&mod_id=' . $downloads_requestid . '"><img src="../images/icon/view_mini.png" alt="' . _XADDRESSES_AM_FORMDISPLAY . '" title="' . _XADDRESSES_AM_FORMDISPLAY . '"></a> ';
                echo '<a href="modified.php?op=del_moddownloads&mod_id=' . $downloads_requestid . '&new_file=' . $new_file . '"><img src="../images/icon/ignore_mini.png" alt="' . _XADDRESSES_AM_FORMIGNORE . '" title="' . _XADDRESSES_AM_FORMIGNORE . '"></a>';
				echo '</td>';
			 }
			 echo '</table><br />';
			 echo '<br /><div align=right>' . $pagenav . '</div><br />';
		}else{
            echo '<div class="errorMsg" style="text-align: center;">' . _XADDRESSES_AM_ERROR_NOBMODADDRESSES . '</div>';
        }
	break; 

    // Affiche la comparaison de fichier
    case "view_downloads":
        //information du téléchargement
		$view_downloads = $downloads_Handler->get($_REQUEST['downloads_loc_id']);
        //information du téléchargement modifié
		$view_moddownloads = $downloadsmod_Handler->get($_REQUEST['mod_id']);
        
        // original
        $downloads_title = $view_downloads->getVar('title');
        $downloads_url = $view_downloads->getVar('url');
        //catégorie
        $view_categorie = $downloadscat_Handler->get($view_downloads->getVar('cid'));
        $downloads_categorie = $view_categorie->getVar('title');
        $downloads_homepage = $view_downloads->getVar('homepage');
        $downloads_version = $view_downloads->getVar('version');
        $downloads_size = trans_size($view_downloads->getVar('size'));
        $downloads_platform = $view_downloads->getVar('platform');
        $downloads_description = $view_downloads->getVar('description');
        $downloads_logourl = $view_downloads->getVar('logourl');        
        // modifié
        $moddownloads_title = $view_moddownloads->getVar('title');
        $moddownloads_url = $view_moddownloads->getVar('url');
        //catégorie
        $view_categorie = $downloadscat_Handler->get($view_moddownloads->getVar('cid'));
        $moddownloads_categorie = $view_categorie->getVar('title');
        $moddownloads_homepage = $view_moddownloads->getVar('homepage');
        $moddownloads_version = $view_moddownloads->getVar('version');
        $moddownloads_size = trans_size($view_moddownloads->getVar('size'));
        $moddownloads_platform = $view_moddownloads->getVar('platform');
        $moddownloads_description = $view_moddownloads->getVar('description');
        $moddownloads_logourl = $view_moddownloads->getVar('logourl');
        echo "<style type=\"text/css\">\n";
        echo ".style_dif {color: #FF0000; font-weight: bold;}\n";
		echo ".style_ide {color: #009966; font-weight: bold;}\n";
		echo "</style>\n";
        //originale
        echo '<table width="100%" border="0" cellspacing="1" class="outer"><tr class="odd"><td>';
        echo '<table border="1" cellpadding="5" cellspacing="0" align="center"><tr><td>';
        echo '<h4>' . _XADDRESSES_AM_MODIFIED_ORIGINAL . '</h4>';
        echo '<table width="100%">';
        echo '<tr>';
        echo '<td valign="top" width="50%"><small><span class="' . ($downloads_title == $moddownloads_title ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMTITLE . '</span>: ' . $downloads_title . '</small></td>';
        echo '<td valign="top" rowspan="14"><small><span class="' . ($downloads_description == $moddownloads_description ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMTEXT . '</span>:<br />' . $downloads_description . '</small></td>';
        echo '</tr>';
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_url == $moddownloads_url ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMURL . '</span>:<br />' . $downloads_url . '</small></td></tr>';
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_categorie == $moddownloads_categorie ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMCAT . '</span>: ' . $downloads_categorie . '</small></td></tr>';
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('status', 1));
        $downloads_field = $downloadsfield_Handler->getall($criteria);
        foreach (array_keys($downloads_field) as $i) {
            if ($downloads_field[$i]->getVar('status_def') == 1){
                if ($downloads_field[$i]->getVar('fid') == 1){
                    //page d'accueil
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_homepage == $moddownloads_homepage ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMHOMEPAGE . '</span>: <a href="' . $downloads_homepage . '">' . $downloads_homepage . '</a></small></td></tr>';
                }
                if ($downloads_field[$i]->getVar('fid') == 2){
                    //version
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_version == $moddownloads_version ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMVERSION . '</span>: ' . $downloads_version . '</small></td></tr>';
                }
                if ($downloads_field[$i]->getVar('fid') == 3){
                    //taille du fichier
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_size == $moddownloads_size ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMSIZE . '</span>: ' . $downloads_size  . '</small></td></tr>';
                }
                if ($downloads_field[$i]->getVar('fid') == 4){
                    //plateforme
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_platform == $moddownloads_platform ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMPLATFORM . '</span>: ' . $downloads_platform  . '</small></td></tr>';
                }
            }else{
                //original
                $contenu = '';
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('loc_id', $_REQUEST['downloads_loc_id']));
                $criteria->add(new Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfielddata = $downloadsfielddata_Handler->getall($criteria);
                foreach (array_keys($downloadsfielddata) as $j) {
                    $contenu = $downloadsfielddata[$j]->getVar('data');
                }  
                //proposé
                $mod_contenu = '';
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('loc_id', $_REQUEST['mod_id']));
                $criteria->add(new Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfieldmoddata = $downloadsfieldmoddata_Handler->getall($criteria);
                foreach (array_keys($downloadsfieldmoddata) as $j) {
                    $mod_contenu = $downloadsfieldmoddata[$j]->getVar('moddata');
                }
                echo '<tr><td valign="top" width="40%"><small><span class="' . ($contenu == $mod_contenu ? 'style_ide' : 'style_dif') . '">' . $downloads_field[$i]->getVar('title') . '</span>: ' . $contenu  . '</small></td></tr>';
            }
        }
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_logourl == $moddownloads_logourl ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMIMG . '</span>:<br /> <img src="' . $uploadurl . $downloads_logourl . '" alt="" title=""></small></td></tr>';
        echo '</table>';
        //proposé
        echo '</td></tr><tr><td>';
        echo '<h4>' . _XADDRESSES_AM_MODIFIED_MOD . '</h4>';
        echo '<table width="100%">';
        echo '<tr>';
        echo '<td valign="top" width="40%"><small><span class="' . ($downloads_title == $moddownloads_title ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMTITLE . '</span>: ' . $moddownloads_title . '</small></td>';
        echo '<td valign="top" rowspan="14"><small><span class="' . ($downloads_description == $moddownloads_description ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMTEXT . '</span>:<br />' . $moddownloads_description . '</small></td>';
        echo '</tr>';
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_url == $moddownloads_url ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMURL . '</span>:<br />' . $moddownloads_url . '</small></td></tr>';
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_categorie == $moddownloads_categorie ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMCAT . '</span>: ' . $moddownloads_categorie . '</small></td></tr>';
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('status', 1));
        $downloads_field = $downloadsfield_Handler->getall($criteria);
        foreach (array_keys($downloads_field) as $i) {
            if ($downloads_field[$i]->getVar('status_def') == 1){
                if ($downloads_field[$i]->getVar('fid') == 1){
                    //page d'accueil
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_homepage == $moddownloads_homepage ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMHOMEPAGE . '</span>: <a href="' . $moddownloads_homepage . '">' . $moddownloads_homepage . '</a></small></td></tr>';
                }
                if ($downloads_field[$i]->getVar('fid') == 2){
                    //version
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_version == $moddownloads_version ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMVERSION . '</span>: ' . $moddownloads_version . '</small></td></tr>';
                }
                if ($downloads_field[$i]->getVar('fid') == 3){
                    //taille du fichier
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_size == $moddownloads_size ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMSIZE . '</span>: ' . $moddownloads_size  . '</small></td></tr>';
                }
                if ($downloads_field[$i]->getVar('fid') == 4){
                    //plateforme
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_platform == $moddownloads_platform ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMPLATFORM . '</span>: ' . $moddownloads_platform  . '</small></td></tr>';
                }
            }else{
                //original
                $contenu = '';
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('loc_id', $_REQUEST['downloads_loc_id']));
                $criteria->add(new Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfielddata = $downloadsfielddata_Handler->getall($criteria);
                foreach (array_keys($downloadsfielddata) as $j) {
                    $contenu = $downloadsfielddata[$j]->getVar('data');
                }  
                //proposé
                $mod_contenu = '';
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('loc_id', $_REQUEST['mod_id']));
                $criteria->add(new Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfieldmoddata = $downloadsfieldmoddata_Handler->getall($criteria);
                foreach (array_keys($downloadsfieldmoddata) as $j) {
                    $mod_contenu = $downloadsfieldmoddata[$j]->getVar('moddata');
                }
                echo '<tr><td valign="top" width="40%"><small><span class="' . ($contenu == $mod_contenu ? 'style_ide' : 'style_dif') . '">' . $downloads_field[$i]->getVar('title') . '</span>: ' . $mod_contenu  . '</small></td></tr>';
            }
        }
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_logourl == $moddownloads_logourl ? 'style_ide' : 'style_dif') . '">' . _XADDRESSES_AM_FORMIMG . '</span>:<br /> <img src="' . $uploadurl . $moddownloads_logourl . '" alt="" title=""></small></td></tr>';
        echo '</table>';
        echo '</table>';
        echo '</td></tr></table>';
        //permet de savoir si le fichier est nouveau        
        $new_file = ($downloads_url == $moddownloads_url ? false : true);
        echo '<table><tr><td>';
        echo myTextForm('modified.php?op=approve&mod_id=' . $_REQUEST['mod_id'] . '&new_file=' . $new_file , _XADDRESSES_AM_FORMAPPROVE);
        echo '</td><td>';
        echo myTextForm('addresses.php?op=edit_downloads&downloads_loc_id=' . $_REQUEST['downloads_loc_id'], _XADDRESSES_AM_FORMEDIT);
        echo '</td><td>';
        echo myTextForm('modified.php?op=del_moddownloads&mod_id=' . $_REQUEST['mod_id'] . '&new_file=' . $new_file, _XADDRESSES_AM_FORMIGNORE);
        echo '</td></tr></table>';
    break;
    
    // permet de suprimmer le téléchargment modifié
    case "del_moddownloads":
        $obj =& $downloadsmod_Handler->get($_REQUEST['mod_id']);
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('addresses.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
			}
            if ($_REQUEST['new_file']==true){
                $urlfile = substr_replace($obj->getVar('url'),'',0,strlen($uploadurl_downloads));
                // permet de donner le chemin du fichier
                $urlfile = $uploaddir_downloads . $urlfile;
                // si le fichier est sur le serveur il es détruit
                if (is_file($urlfile)){		
                    chmod($urlfile, 0777);
                    unlink($urlfile);
                }
            }
            // supression des data des champs sup
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('loc_id', $_REQUEST['mod_id']));
            $downloads_fielddata = $downloadsfieldmoddata_Handler->getall( $criteria );
            foreach (array_keys($downloads_fielddata) as $i) {
                $objfielddata =& $downloadsfieldmoddata_Handler->get($downloads_fielddata[$i]->getVar('modiddata'));
                $downloadsfieldmoddata_Handler->delete($objfielddata) or $objvfielddata->getHtmlErrors();
            }
            if ($downloadsmod_Handler->delete($obj)) {
                redirect_header('modified.php', 1, _XADDRESSES_AM_REDIRECT_DELOK);
            }
            echo $objvotedata->getHtmlErrors();
        } else {
			xoops_confirm(array('ok' => 1, 'mod_id' => $_REQUEST['mod_id'], 'new_file' => $_REQUEST['new_file'], 'op' => 'del_moddownloads'), $_SERVER['REQUEST_URI'], _XADDRESSES_AM_MODIFIED_SURDEL . '<br />');
		}
    break;
    
    // permet d'accépter la modification
    case "approve":
        // choix du téléchargement:
        $view_moddownloads = $downloadsmod_Handler->get($_REQUEST['mod_id']);
        $obj =& $downloads_Handler->get($view_moddownloads->getVar('loc_id'));
        // permet d'effacer le fichier actuel si un nouveau fichier proposé est accepté.
        if ($_REQUEST['new_file']==true){
            $urlfile = substr_replace($obj->getVar('url'),'',0,strlen($uploadurl_downloads));
            // permet de donner le chemin du fichier
            $urlfile = $uploaddir_downloads . $urlfile;
            // si le fichier est sur le serveur il es détruit
            if (is_file($urlfile)){		
                chmod($urlfile, 0777);
                unlink($urlfile);
            }
        }
        // mise à jour:
        $obj->setVar('title', $view_moddownloads->getVar('title'));
        $obj->setVar('url', $view_moddownloads->getVar('url'));        
        $obj->setVar('cid', $view_moddownloads->getVar('cid'));
        $obj->setVar('homepage', $view_moddownloads->getVar('homepage'));
        $obj->setVar('version', $view_moddownloads->getVar('version'));
        $obj->setVar('size', $view_moddownloads->getVar('size'));
        $obj->setVar('platform', $view_moddownloads->getVar('platform'));
        $obj->setVar('description', $view_moddownloads->getVar('description'));
        $obj->setVar('logourl', $view_moddownloads->getVar('logourl'));
        $obj->setVar('date', time());
        $obj->setVar('status', 2);
        // Récupération des champs supplémentaires:
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $downloadsfield_Handler->getall($criteria);
        foreach (array_keys($downloads_field) as $i) {
            $contenu = '';
            $iddata = 0;
            if ($downloads_field[$i]->getVar('status_def') == 0){
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('loc_id', $view_moddownloads->getVar('requestid')));
                $criteria->add(new Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfieldmoddata = $downloadsfieldmoddata_Handler->getall($criteria);
                foreach (array_keys($downloadsfieldmoddata) as $j) {
                    $contenu = $downloadsfieldmoddata[$j]->getVar('moddata');
                }
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('loc_id', $view_moddownloads->getVar('loc_id')));
                $criteria->add(new Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfielddata = $downloadsfielddata_Handler->getall($criteria);
                foreach (array_keys($downloadsfielddata) as $j) {
                    $iddata = $downloadsfielddata[$j]->getVar('iddata');
                }
                if ($iddata == 0){
                    $objdata =& $downloadsfielddata_Handler->create();
                    $objdata->setVar('fid', $downloads_field[$i]->getVar('fid'));
                    $objdata->setVar('loc_id', $view_moddownloads->getVar('loc_id'));
                }else{
                    $objdata =& $downloadsfielddata_Handler->get($iddata);                    
                }
                $objdata->setVar('data', $contenu);
                $downloadsfielddata_Handler->insert($objdata) or $objdata->getHtmlErrors();
            }       
        }
        // supression du rapport de modification
        $objmod =& $downloadsmod_Handler->get($_REQUEST['mod_id']);
        $downloadsmod_Handler->delete($objmod);
        // supression des data des champs sup
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('loc_id', $_REQUEST['mod_id']));
        $downloads_fielddata = $downloadsfieldmoddata_Handler->getall( $criteria );
        foreach (array_keys($downloads_fielddata) as $i) {
            $objfielddata =& $downloadsfieldmoddata_Handler->get($downloads_fielddata[$i]->getVar('modiddata'));
            $downloadsfieldmoddata_Handler->delete($objfielddata) or $objvfielddata->getHtmlErrors();
        }
        // enregistrement
        if ($downloads_Handler->insert($obj)){
            redirect_header('modified.php?op=liste', 1, _XADDRESSES_AM_REDIRECT_SAVE);
        }
        echo $obj->getHtmlErrors();
    break;   
}
//Affichage de la partie basse de l'administration de Xoops
xoops_cp_footer();
?>