<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class Xaddresses_mod extends XoopsObject
{
// constructor
	function __construct()
	{
		$this->XoopsObject();
		$this->initVar("requestid",XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("loc_id",XOBJ_DTYPE_INT,null,false,11);
		$this->initVar("cid",XOBJ_DTYPE_INT,null,false,5);
		$this->initVar("title",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("url",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("homepage",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("version",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("size",XOBJ_DTYPE_INT,null,false,8);
        $this->initVar("platform",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("logourl",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("description",XOBJ_DTYPE_TXTAREA, null, false);
		// Pour autoriser le html
		$this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);        
		$this->initVar("modifysubmitter",XOBJ_DTYPE_INT,null,false,11);
	}

	function Xaddresses_mod($loc_id)
    {
        $this->__construct();        
    }
    function get_new_enreg()
    {
        global $xoopsDB;
        $new_enreg = $xoopsDB->getInsertId();
        return $new_enreg;
    }
    function getForm($loc_id, $erreur, $donnee = array(), $groups = array(), $action = false)
    {
        //pour xoops france (true) sinon false
        $referentiel = false;
        
		global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
		if ($action === false) {
			$action = $_SERVER['REQUEST_URI'];
		}
        $gperm_handler =& xoops_gethandler('groupperm');
        $perm_upload = ($gperm_handler->checkRight('xaddresses_ac', 32, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
        //appel des class
        $addresses_Handler =& xoops_getModuleHandler('Xaddresses_address', 'xaddresses');
        $categories_Handler =& xoops_getModuleHandler('Xaddresses_category', 'xaddresses');
        
        $view_downloads = $addresses_Handler->get($loc_id);
        include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
        
        // affectation des variables
        if ($erreur == true) {
        	$d_title = $donnee['title'];
            $d_cid = $donnee['cid'];
            $d_homepage = $donnee['homepage'];
            $d_version = $donnee['version'];
            $d_size = $donnee['size'];
            $d_platform = $donnee['platform'];
            $d_description = $donnee['description'];            
        }else{
            $d_title = $view_downloads->getVar('title');
            $d_cid = $view_downloads->getVar('cid');
            $d_homepage = $view_downloads->getVar('homepage');
            $d_version = $view_downloads->getVar('version');
            $d_size = $view_downloads->getVar('size');
            $d_platform = $view_downloads->getVar('platform');
            $d_description = $view_downloads->getVar('description', 'e');
        }

        //nom du formulaire
        $title = sprintf(_XADDRESSES_AM_FORMEDIT);
        
        //création du formulaire
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        //titre
        $form->addElement(new XoopsFormText(_XADDRESSES_AM_FORMTITLE, 'title', 50, 255, $d_title), true);
        // fichier
        $fichier = new XoopsFormElementTray(_XADDRESSES_AM_FORMFILE,'<br /><br />');
        $url = $view_downloads->getVar('url');
        $formurl = new XoopsFormText(_XADDRESSES_AM_FORMURL, 'url', 75, 255, $url);
        $fichier->addElement($formurl,false);
        if ($perm_upload == true) {
            $fichier->addElement(new XoopsFormFile(_XADDRESSES_AM_FORMUPLOAD , 'attachedfile', $xoopsModuleConfig['maxuploadsize']), false);
        }
        $form->addElement($fichier);

        //catégorie
        $categories = tdmdownloads_MygetItemIds('tdmdownloads_submit');
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
		$downloadscat_arr = $categories_Handler->getall($criteria);
        if ($categories_Handler->getCount($criteria) == 0){
            redirect_header('singleadress.php?loc_id=' . $loc_id, 2,  _NOPERM);
        }
        
		$mytree = new XoopsObjectTree($downloadscat_arr, 'cid', 'pid');
		$form->addElement(new XoopsFormLabel(_XADDRESSES_AM_FORMINCAT, $mytree->makeSelBox('cid', 'title', '--', $d_cid, true)), true);
        
        //affichage des champs        
        $downloadsfield_Handler =& xoops_getModuleHandler('Xaddresses_field', 'xaddresses');
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $downloadsfield_Handler->getall($criteria);
        foreach (array_keys($downloads_field) as $i) {
            if ($downloads_field[$i]->getVar('status_def') == 1){
                if ($downloads_field[$i]->getVar('fid') == 1){
                    //page d'accueil
                    if ($downloads_field[$i]->getVar('status') == 1){
                        $form->addElement(new XoopsFormText(_XADDRESSES_AM_FORMHOMEPAGE, 'homepage', 50, 255, $d_homepage));
                    }else{
                        $form->addElement(new XoopsFormHidden('homepage', ''));
                    }
                }
                if ($downloads_field[$i]->getVar('fid') == 2){
                    //version
                    if ($downloads_field[$i]->getVar('status') == 1){
                        $form->addElement(new XoopsFormText(_XADDRESSES_AM_FORMVERSION, 'version', 10, 255, $d_version));
                    }else{
                        $form->addElement(new XoopsFormHidden('version', ''));
                    }
                }
                if ($downloads_field[$i]->getVar('fid') == 3){
                    //taille du fichier
                    if ($downloads_field[$i]->getVar('status') == 1){
                        $form->addElement(new XoopsFormText(_XADDRESSES_AM_FORMSIZE, 'size', 10, 255, $d_size));
                    }else{
                        $form->addElement(new XoopsFormHidden('size', ''));
                    }
                }
                if ($downloads_field[$i]->getVar('fid') == 4){
                    //plateforme
                    if ($downloads_field[$i]->getVar('status') == 1){
                        $platformselect = new XoopsFormSelect(_XADDRESSES_AM_FORMPLATFORM, 'platform', explode('|',$d_platform), 5, true);
                        $platform_array = explode('|',$xoopsModuleConfig['plateform']);
                        foreach( $platform_array as $platform ) {
                            $platformselect->addOption("$platform", $platform);
                        }
                        $form->addElement($platformselect, false);
                        //$form->addElement(new XoopsFormText(_XADDRESSES_AM_FORMPLATFORM, 'platform', 50, 255, $d_platform));
                    }else{
                        $form->addElement(new XoopsFormHidden('platform', ''));
                    }
                }
            }else{
                $contenu = '';
                $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
                $downloadsfielddata_Handler =& xoops_getModuleHandler('Xaddresses_fielddata', 'xaddresses');
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('loc_id', $view_downloads->getVar('loc_id')));
                $criteria->add(new Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfielddata = $downloadsfielddata_Handler->getall($criteria);
                foreach (array_keys($downloadsfielddata) as $j) {
                    if ($erreur == true) {
                        $contenu = $donnee[$nom_champ];
                    }else{
                        $contenu = $downloadsfielddata[$j]->getVar('data');                        
                    }
                }
                if ($downloads_field[$i]->getVar('status') == 1){
                    $form->addElement(new XoopsFormText($downloads_field[$i]->getVar('title'), $nom_champ, 50, 255, $contenu));
                }else{
                    $form->addElement(new XoopsFormHidden($nom_champ, ''));
                }
            }            
        }        
        //description
        $editor_configs=array();
    	$editor_configs["name"] ="description";
    	$editor_configs["value"] = $d_description;
    	$editor_configs["rows"] = 20;
    	$editor_configs["cols"] = 60;
    	$editor_configs["width"] = "100%";
    	$editor_configs["height"] = "400px";
    	$editor_configs["editor"] = $xoopsModuleConfig['editor'];
        if ($referentiel == true){
            $form->addElement( new XoopsFormEditor(_XADDRESSES_AM_FORMTEXTADDRESSES . _MD_XADDRESSES_SUP, "description", $editor_configs), true);
        }else{
            $form->addElement( new XoopsFormEditor(_XADDRESSES_AM_FORMTEXTADDRESSES, "description", $editor_configs), true);
        }
        //image
        if ( $xoopsModuleConfig['useshots']){
            $downloadscat_img = $view_downloads->getVar('logourl') ? $view_downloads->getVar('logourl') : 'blank.gif';
            $uploadirectory='/uploads/xaddresses/images/shots';
            $imgtray = new XoopsFormElementTray(_XADDRESSES_AM_FORMIMG,'<br />');
            $imgpath=sprintf(_XADDRESSES_AM_FORMPATH, $uploadirectory );
            $imageselect= new XoopsFormSelect($imgpath, 'logo_img',$downloadscat_img);        
            $topics_array = XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH . $uploadirectory );
            foreach( $topics_array as $image ) {
                $imageselect->addOption("$image", $image);
            }
            $imageselect->setExtra( "onchange='showImgSelected(\"image3\", \"logo_img\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'" );
            $imgtray->addElement($imageselect,false);
            $imgtray -> addElement( new XoopsFormLabel( '', "<br /><img src='" . XOOPS_URL . "/" . $uploadirectory . "/" . $downloadscat_img . "' name='image3' id='image3' alt='' />" ) );
            $fileseltray= new XoopsFormElementTray('','<br />');
            if ($perm_upload == true) {
                $fileseltray->addElement(new XoopsFormFile(_XADDRESSES_AM_FORMUPLOAD , 'attachedimage', $xoopsModuleConfig['maxuploadsize']), false);
            }
            $imgtray->addElement($fileseltray);
            $form->addElement($imgtray);
        }
        $form->addElement(new XoopsFormCaptcha(), true);
        $form->addElement(new XoopsFormHidden('loc_id', $loc_id));
        //pour enregistrer le formulaire
        $form->addElement(new XoopsFormHidden('op', 'save'));
        //bouton d'envoi du formulaire
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    	return $form;
    }
}

class XaddressesXaddresses_modHandler extends XoopsPersistableObjectHandler 
{
	function __construct(&$db) 
	{
		parent::__construct($db, "xaddresses_mod", 'Xaddresses_mod', 'requestid', 'loc_id');
    }
}
?>