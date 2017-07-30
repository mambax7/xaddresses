<?php
function b_sitemap_xaddresses(){
	$xoopsDB =& Database::getInstance();
    $block = sitemap_get_categoires_map($xoopsDB->prefix("xaddresses_locationcategory"), "cat_id", "cat_pid", "cat_title", "categoryview.php?cat_id=", "cat_title");
	return $block;
}
?>