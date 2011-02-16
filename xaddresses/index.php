<?php
include_once 'header.php';
// template
$xoopsOption['template_main'] = 'xaddresses_index.html';
include_once XOOPS_ROOT_PATH.'/header.php';

//load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
//$downloadsvotedata_Handler =& xoops_getModuleHandler('Xaddresses_votedata', 'xaddresses');

// pour les permissions
$categories = xaddresses_MygetItemIds();
//affichage des categories:
$criteria = new CriteriaCompo();
$criteria->setSort('weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
$downloadscat_arr = $categoryHandler->getall($criteria);
$mytree = new XoopsObjectTree($downloadscat_arr, 'cat_id', 'cat_pid');

$criteria = new CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('cat_pid', 0));
$criteria->add(new Criteria('cat_id', '(' . implode(',', $categories) . ')','IN'));
$downloadscat_arr = $categoryHandler->getall($criteria);

$count = 1;
$keywords = '';
foreach (array_keys($downloadscat_arr) as $i) {
    $totaldownloads = nombreEntree($downloadscat_arr[$i]->getVar('cid'), '1');
    $subcategories_arr = $mytree->getFirstChild($downloadscat_arr[$i]->getVar('cid'));
    $chcount = 0;
    $subcategories = '';
    //pour les mots clef
    $keywords .= $downloadscat_arr[$i]->getVar('title') . ',';
    foreach (array_keys($subcategories_arr) as $j) {
            if ($chcount>=$xoopsModuleConfig['nbsouscat']){				
                $subcategories .= '<li>[<a href="' . XOOPS_URL . '/modules/xaddresses/viewcat.php?cid=' . $downloadscat_arr[$i]->getVar('cid') . '">+</a>]</li>';
                break;
            }
            $subcategories .= '<li><a href="' . XOOPS_URL . '/modules/xaddresses/viewcat.php?cid=' . $subcategories_arr[$j]->getVar('cid') . '">' . $subcategories_arr[$j]->getVar('title') . '</a></li>';
            $keywords .= $downloadscat_arr[$i]->getVar('title') . ',';
            $chcount++;
    }
    $xoopsTpl->append('categories', array('image' => $uploadurl . $downloadscat_arr[$i]->getVar('imgurl'), 'id' => $downloadscat_arr[$i]->getVar('cid'), 'title' => $downloadscat_arr[$i]->getVar('title'), 'description_main' => $downloadscat_arr[$i]->getVar('description_main'), 'subcategories' => $subcategories, 'totaldownloads' => $totaldownloads, 'count' => $count));
    $count++;
}

//pour afficher les r�sum�s
//t�l�chargements r�cents
if($xoopsModuleConfig['bldate']==1){
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
    $criteria->setSort('date');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $downloads_arr = $locationHandler->getall($criteria);
    foreach (array_keys($downloads_arr) as $i) {
        $title = $downloads_arr[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title,0,($xoopsModuleConfig['longbl']))."...";
        }
        $date = formatTimestamp($downloads_arr[$i]->getVar('date'),"s");
        $xoopsTpl->append('bl_date', array('id' => $downloads_arr[$i]->getVar('loc_id'),'cid' => $downloads_arr[$i]->getVar('cid'),'date' => $date,'title' => $title));
    }
}
//plus t�l�charg�s
if($xoopsModuleConfig['blpop']==1){
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
    $criteria->setSort('hits');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $downloads_arr = $locationHandler->getall($criteria);
    foreach (array_keys($downloads_arr) as $i) {
        $title = $downloads_arr[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title,0,($xoopsModuleConfig['longbl']))."...";
        }
        $xoopsTpl->append('bl_pop', array('id' => $downloads_arr[$i]->getVar('loc_id'),'cid' => $downloads_arr[$i]->getVar('cid'),'hits' => $downloads_arr[$i]->getVar('hits'),'title' => $title));
    }
}
//mieux not�s
if($xoopsModuleConfig['blrating']==1){
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
    $criteria->setSort('rating');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $downloads_arr = $locationHandler->getall($criteria);
    foreach (array_keys($downloads_arr) as $i) {
        $title = $downloads_arr[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title,0,($xoopsModuleConfig['longbl']))."...";
        }
        $rating = number_format($downloads_arr[$i]->getVar('rating'),1);
        $xoopsTpl->append('bl_rating', array('id' => $downloads_arr[$i]->getVar('loc_id'),'cid' => $downloads_arr[$i]->getVar('cid'),'rating' => $rating,'title' => $title));
    }
}
if ($xoopsModuleConfig['bldate']==0 and $xoopsModuleConfig['blpop']==0 and $xoopsModuleConfig['blrating']==0){
    $bl_affichage = 0;
}else{
    $bl_affichage = 1;
}
$xoopsTpl->assign('bl_affichage', $bl_affichage);

// affichage des t�l�chargements
//Utilisation d'une copie d'�cran avec la largeur selon les pr�f�rences
if ($xoopsModuleConfig['useshots'] == 1) {
    $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
    $xoopsTpl->assign('show_screenshot', true);
}
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', 0, '!='));
$criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
$numrows = $locationHandler->getCount($criteria);
$xoopsTpl->assign('lang_thereare', sprintf(_MD_XADDRESSES_INDEX_THEREARE,$numrows));
$criteria->setLimit($xoopsModuleConfig['newdownloads']);
$tblsort = array();
$tblsort[1]='date';
$tblsort[2]='date';
$tblsort[3]='hits';
$tblsort[4]='hits';
$tblsort[5]='rating';
$tblsort[6]='rating';
$tblsort[7]='title';
$tblsort[8]='title';
$tblorder = array();
$tblorder[1]='DESC';
$tblorder[2]='ASC';
$tblorder[3]='DESC';
$tblorder[4]='ASC';
$tblorder[5]='DESC';
$tblorder[6]='ASC';
$tblorder[7]='DESC';
$tblorder[8]='ASC';
$sort = isset($xoopsModuleConfig['toporder']) ? $xoopsModuleConfig['toporder'] : 1;
$order = isset($xoopsModuleConfig['toporder']) ? $xoopsModuleConfig['toporder'] : 1;
$criteria->setSort($tblsort[$sort]);
$criteria->setOrder($tblorder[$order]);
$downloads_arr = $locationHandler->getall($criteria);

foreach (array_keys($downloads_arr) as $i) {
    $dtitle = $downloads_arr[$i]->getVar('title');
    $url = $downloads_arr[$i]->getVar('url');
    $logourl = $downloads_arr[$i]->getVar('logourl');
    $logourl = $uploadurl_shots . $logourl;
    $datetime = formatTimestamp($downloads_arr[$i]->getVar('date'),'s');
    $cid = $downloads_arr[$i]->getVar('cid');
    $loc_id = $downloads_arr[$i]->getVar('loc_id');
    $submitter = XoopsUser::getUnameFromId($downloads_arr[$i]->getVar('submitter'));
    $description = $downloads_arr[$i]->getVar('description');
    // pour r�f�rentiel de module
    $description = str_replace('[block]','<h2><u>' . _MD_XADDRESSES_SUP_BLOCS . '</u></h2>',$description);
    $description = str_replace('[notes]','<h2><u>' . _MD_XADDRESSES_SUP_NOTES . '</u></h2>',$description);
    $description = str_replace('[evolutions]','<h2><u>' . _MD_XADDRESSES_SUP_EVOLUTIONS . '</u></h2>',$description);
    $description = str_replace('[infos]','<h2><u>' . _MD_XADDRESSES_SUP_INFOS . '</u></h2>',$description);
    $description = str_replace('[changelog]','<h2><u>' . _MD_XADDRESSES_SUP_CHANGELOG . '</u></h2>',$description);
    $description = str_replace('[backoffice]','<h2><u>' . _MD_XADDRESSES_SUP_BACKOFFICE . '</u></h2>',$description);
    $description = str_replace('[frontoffice]','<h2><u>' . _MD_XADDRESSES_SUP_FRONTOFFICE . '</u></h2>',$description);
    //permet d'afficher uniquement la description courte
    if (strpos($description,'[pagebreak]')==false){	
        $description_short = $description;
    } else {
        $description_short = substr($description,0,strpos($description,'[pagebreak]'));
    }
    // pour les vignettes "new" et "mis � jour"
    $new = nouveau_image($downloads_arr[$i]->getVar('date'), $downloads_arr[$i]->getVar('status'));
    $pop = populaire_image($downloads_arr[$i]->getVar('hits'));
    
    // D�fini si la personne est un admin
    if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
        $adminlink = '<a href="' . XOOPS_URL . '/modules/TDMDownloads/admin/addresses.php?op=view_downloads&amp;downloads_loc_id=' . $loc_id . '" title="' . _MD_XADDRESSES_EDITTHISDL . '"><img src="' . XOOPS_URL . '/modules/TDMDownloads/images/editicon.png" width="16px" height="16px" border="0" alt="' . _MD_XADDRESSES_EDITTHISDL . '" /></a>';
    } else {
        $adminlink = '';
    }    
    $xoopsTpl->append('file', array('id' => $loc_id,'cid'=>$cid, 'title' => $dtitle.$new.$pop,'logourl' => $logourl,'updated' => $datetime,'description_short' => $description_short,
                                    'adminlink' => $adminlink, 'submitter' => $submitter));
    //pour les mots clef
    $keywords .= $dtitle . ',';
}
// r�f�rencement
//description
$xoTheme->addMeta('meta', 'description', strip_tags($xoopsModule->name()));
//keywords
$keywords = substr($keywords,0,-1);
$xoTheme->addMeta('meta', 'keywords', $keywords);

include XOOPS_ROOT_PATH.'/footer.php';
?>
