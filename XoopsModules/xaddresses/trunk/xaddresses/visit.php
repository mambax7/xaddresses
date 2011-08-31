<?php
error_reporting(0);
include "header.php";
//load classes
$categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
$locationHandler =& xoops_getModuleHandler('location', 'xaddresses');

// redirection si le téléchargement n'existe pas
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('loc_id', (int)($_REQUEST['loc_id'])));
if ($locationHandler->getCount($criteria) == 0){
    redirect_header('index.php', 3, _MD_XADDRESSES_SINGLEFILE_NONEXISTENT);
	exit();
}

@$xoopsLogger->activated = false;
error_reporting(0);
$loc_id = (int)($_GET['loc_id']);
$cat_id = (int)($_GET['cat_id']);
if ( $xoopsModuleConfig['check_host'] ) {
	$goodhost      = 0;
	$referer       = parse_url(xoops_getenv('HTTP_REFERER'));
	$referer_host  = $referer['host'];
	foreach ( $xoopsModuleConfig['referers'] as $ref ) {
		if ( !empty($ref) && preg_match("/".$ref."/i", $referer_host) ) {
			$goodhost = "1";
			break;
		}
	}
	if (!$goodhost) {
		redirect_header(XOOPS_URL . "/modules/xaddresses/singlefile.php?cid=$cid&amp;loc_id=$loc_id", 30, _MD_XADDRESSES_NOPERMISETOLINK);
		exit();
	}
}

// redirection si pas la permission d'accès
$view_downloads = $locationHandler->get($loc_id);
$categories = xaddresses_MygetItemIds();
if(!in_array($view_downloads->getVar('cat_id'), $categories)) {
	redirect_header(XOOPS_URL, 2, _NOPERM);
	exit();
}

// ajout +1 pour les hits
$sql = sprintf("UPDATE %s SET hits = hits+1 WHERE loc_id = %u AND status > 0", $xoopsDB->prefix("tdmdownloads_downloads"), $loc_id);
$xoopsDB->queryF($sql);

$url = $view_downloads->getVar('url');
if (!preg_match("/^ed2k*:\/\//i", $url)) {
	Header("Location: $url");
}
echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=" . $url . "\"></meta></head><body></body></html>";
exit();
?>
