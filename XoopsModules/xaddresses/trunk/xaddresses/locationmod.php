<?php
/**
 * ****************************************************************************
 *  - TDMDownloads By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - GNU Licence Copyright (c)  (www.xoops.org)
 *
 * La licence GNU GPL, garanti à l'utilisateur les droits suivants
 *
 * 1. La liberté d'exécuter le logiciel, pour n'importe quel usage,
 * 2. La liberté de l' étudier et de l'adapter à ses besoins,
 * 3. La liberté de redistribuer des copies,
 * 4. La liberté d'améliorer et de rendre publiques les modifications afin
 * que l'ensemble de la communauté en bénéficie.
 *
 * @copyright   http://www.tdmxoops.net
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		TDM (G.Mage); TEAM DEV MODULE
 *
 * ****************************************************************************
 */

include_once 'header.php';
// template d'affichage
$xoopsOption['template_main'] = 'xaddresses_locationmod.html';
include_once XOOPS_ROOT_PATH.'/header.php';
//On recupere la valeur de l'argument op dans l'URL$
if (isset($_REQUEST['op'])) {
	$op = $_REQUEST['op'];
} else {
	$op = 'liste';
}
//paramètres:
// pour les images des téléchargement:
$uploaddir = XOOPS_ROOT_PATH . '/uploads/TDMDownloads/images/shots/';
$uploadurl = XOOPS_URL . '/uploads/TDMDownloads/images/shots/';
// pour les fichiers
$uploaddir_downloads = XOOPS_ROOT_PATH . '/uploads/TDMDownloads/downloads/';
$uploadurl_downloads = XOOPS_URL . '/uploads/TDMDownloads/downloads/';
/////////////

//appel des class
$downloadscat_Handler =& xoops_getModuleHandler('category', 'xaddresses');
$downloads_Handler =& xoops_getModuleHandler('address', 'xaddresses');
//$downloadsmod_Handler =& xoops_getModuleHandler('Xaddresses_mod', 'xaddresses');
$downloadsfield_Handler =& xoops_getModuleHandler('field', 'xaddresses');
//$downloadsfieldmoddata_Handler =& xoops_getModuleHandler('Xaddresses_modfielddata', 'xaddresses');

// redirection si le téléchargement n'existe pas
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_id', intval($_REQUEST['loc_id'])));
if ($downloads_Handler->getCount($criteria) == 0){
    redirect_header('index.php', 3, _MD_XADDRESSES_SINGLEFILE_NONEXISTENT);
	exit();
}

if ($perm_modif == false) {
	redirect_header('index.php', 2, _NOPERM);
    exit();
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) 
{
	// Vue liste
    case "liste":
        //navigation
        $view_downloads = $downloads_Handler->get($_REQUEST['loc_id']);
        $view_categorie = $downloadscat_Handler->get($view_downloads->getVar('cid'));
        $categories = tdmdownloads_MygetItemIds();
        if(!in_array($view_downloads->getVar('cid'), $categories)) {
            redirect_header('index.php', 2, _NOPERM);
            exit();
        }       
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
        $downloadscat_arr = $downloadscat_Handler->getall($criteria);
        $mytree = new XoopsObjectTree($downloadscat_arr, 'cid', 'pid');
        $nav_parent_id = $mytree->getAllParent($view_downloads->getVar('cid'));
        $titre_page = $nav_parent_id;
        $nav_parent_id = array_reverse($nav_parent_id);
        $navigation = '<a href="index.php">' . $xoopsModule->name() . '</a>&nbsp;:&nbsp;';
        foreach (array_keys($nav_parent_id) as $i) {
            $navigation .= '<a href="viewcat.php?cid=' . $nav_parent_id[$i]->getVar('cid') . '">' . $nav_parent_id[$i]->getVar('title') . '</a>&nbsp;:&nbsp;';
        }
        $navigation .= '<a href="viewcat.php?cid=' . $view_categorie->getVar('cid') . '">' . $view_categorie->getVar('title') . '</a>&nbsp;:&nbsp;<a href="singlefile.php?loc_id=' . $view_downloads->getVar('loc_id') . '">' . $view_downloads->getVar('title') . '</a>&nbsp;:&nbsp;';
        $navigation .= _MD_XADDRESSES_SINGLEFILE_MODIFY;
        $xoopsTpl->assign('navigation', $navigation);
        
        // référencement
        // titre de la page        
        $titre = _MD_XADDRESSES_SINGLEFILE_MODIFY . '&nbsp;-&nbsp;' . $view_downloads->getVar('title') . '&nbsp;-&nbsp;' . $view_categorie->getVar('title') . '&nbsp;-&nbsp;';
        foreach (array_keys($titre_page) as $i) {
            $titre .= $titre_page[$i]->getVar('title') . '&nbsp;-&nbsp;';
        }
        $titre .= $xoopsModule->name();
        $xoopsTpl->assign('xoops_pagetitle', $titre);
        //description
        $xoTheme->addMeta( 'meta', 'description', strip_tags(_MD_XADDRESSES_SINGLEFILE_MODIFY . ' (' . $view_downloads->getVar('title') . ')'));

        
        //Affichage du formulaire de notation des téléchargements
    	$obj =& $downloadsmod_Handler->create();
    	$form = $obj->getForm(intval($_REQUEST['loc_id']), false, $donnee = array(), $groups);
        $xoopsTpl->assign('themeForm', $form->render());    
    break;
    // save
    case "save":
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        $obj =& $downloadsmod_Handler->create();
        $erreur = false;
        $message_erreur = '';
        $donnee = array();
        $obj->setVar('title', $_POST['title']);
        $donnee['title'] = $_POST['title'];
        $obj->setVar('cid', $_POST['cid']);
        $donnee['cid'] = $_POST['cid'];
        $obj->setVar('loc_id', $_POST['loc_id']);
        $obj->setVar('homepage', formatURL($_POST["homepage"]));
        $donnee['homepage'] = formatURL($_POST["homepage"]);
        $obj->setVar('version', $_POST["version"]);
        $donnee['version'] = $_POST["version"];
        $obj->setVar('size', $_POST["size"]);        
        $donnee['size'] = $_POST["size"];        
        $obj->setVar('platform', implode('|',$_POST["platform"]));
        $donnee['platform'] = implode('|',$_POST["platform"]);
        $obj->setVar('description', $_POST["description"]);
        $donnee['description'] = $_POST["description"];
		$obj->setVar('modifysubmitter', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);    

        // erreur si la taille du fichier n'est pas un nombre
        if (intval($_REQUEST['size']) == 0){
            if ($_REQUEST['size'] == '0' || $_REQUEST['size'] == ''){
                $erreur = false;
            }else{
                $erreur = true;
                $message_erreur .= _MD_XADDRESSES_ERROR_SIZE . '<br />';
            }
        }
        // erreur si la catégorie est vide
        if (isset($_REQUEST['cid'])){
            if ($_REQUEST['cid'] == 0){
                $erreur=true;
                $message_erreur .= _MD_XADDRESSES_ERROR_NOCAT . '<br />';
            }
        }
        // erreur si le captcha est faux
        xoops_load("captcha");
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if ( !$xoopsCaptcha->verify() ) {
            $message_erreur .=$xoopsCaptcha->getMessage().'<br />';
            $erreur=true;
        }
        // pour enregistrer temporairement les valeur des champs sup
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $downloadsfield_Handler->getall($criteria);
        foreach (array_keys($downloads_field) as $i) {
            if ($downloads_field[$i]->getVar('status_def') == 0){
                $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
                $donnee[$nom_champ] = $_POST[$nom_champ];
            }       
        }
        if ($erreur==true){
            $xoopsTpl->assign('message_erreur', $message_erreur);
        }else{
            // Pour le fichier
            if (isset($_POST['xoops_upload_file'][0])){            
                $uploader = new XoopsMediaUploader($uploaddir_downloads, explode('|',$xoopsModuleConfig['mimetype']), $xoopsModuleConfig['maxuploadsize'], null, null);
                if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                    if ($xoopsModuleConfig['newnamedownload']){
                        $uploader->setPrefix($xoopsModuleConfig['prefixdownloads']) ;
                    }
                    $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
                    if (!$uploader->upload()) {
                        $errors = $uploader->getErrors();
                        redirect_header("javascript:history.go(-1)",3, $errors);
                    } else {
                        $obj->setVar('url', $uploadurl_downloads . $uploader->getSavedFileName());
                    }
                } else {
                    $obj->setVar('url', $_REQUEST['url']);
                }
            }
            // Pour l'image
            if (isset($_POST['xoops_upload_file'][1])){
                $uploader_2 = new XoopsMediaUploader($uploaddir, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'), $xoopsModuleConfig['maxuploadsize'], null, null);
                if ($uploader_2->fetchMedia($_POST['xoops_upload_file'][1])) {
                    $uploader_2->setPrefix('downloads_') ;
                    $uploader_2->fetchMedia($_POST['xoops_upload_file'][1]);
                    if (!$uploader_2->upload()) {
                        $errors = $uploader_2->getErrors();
                        redirect_header("javascript:history.go(-1)",3, $errors);
                    } else {
                        $obj->setVar('logourl', $uploader_2->getSavedFileName());
                    }
                } else {
                    $obj->setVar('logourl', $_REQUEST['logo_img']);
                }
            }
            
            if ($downloadsmod_Handler->insert($obj)) {
                $loc_id_dowwnloads = $obj->get_new_enreg();
                // Récupération des champs supplémentaires:        
                $criteria = new CriteriaCompo();
                $criteria->setSort('weight ASC, title');
                $criteria->setOrder('ASC');
                $downloads_field = $downloadsfield_Handler->getall($criteria);
                foreach (array_keys($downloads_field) as $i) {
                    if ($downloads_field[$i]->getVar('status_def') == 0){
                        $objdata =& $downloadsfieldmoddata_Handler->create();
                        $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
                        $objdata->setVar('moddata', $_POST[$nom_champ]);
                        $objdata->setVar('loc_id', $loc_id_dowwnloads);
                        $objdata->setVar('fid', $downloads_field[$i]->getVar('fid'));
                        $downloadsfieldmoddata_Handler->insert($objdata) or $objdata->getHtmlErrors();
                    }       
                }
                $tags = array();
                $tags['MODIFYREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/modified.php';
                $notification_handler =& xoops_gethandler('notification');
                $notification_handler->triggerEvent('global', 0, 'file_modify', $tags);
                redirect_header('singlefile.php?loc_id=' . intval($_REQUEST['loc_id']), 1, _MD_XADDRESSES_MODFILE_THANKSFORINFO);
            }
            echo $obj->getHtmlErrors();
        }
        //Affichage du formulaire de notation des téléchargements
    	$form =& $obj->getForm(intval($_REQUEST['loc_id']), true, $donnee, $groups);
        $xoopsTpl->assign('themeForm', $form->render());   
        
    break;    
}
include XOOPS_ROOT_PATH.'/footer.php';
?>
