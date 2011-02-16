<?php
function b_xaddresses_top_show($options) {
    require_once XOOPS_ROOT_PATH."/modules/xaddresses/include/functions.php";
    //appel de la class
    $addresses_Handler =& xoops_getModuleHandler('xaddresses_addresses', 'xaddresses');
    $block = array();
	$type_block = $options[0];
	$nb_entree = $options[1];
	$lenght_title = $options[2];
    array_shift($options);
	array_shift($options);
	array_shift($options);
    $categories = tdmdownloads_MygetItemIds();
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
    if (!(count($options) == 1 && $options[0] == 0)) {
		$criteria->add(new Criteria('cid', '(' . implode(',', $options) . ')','IN'));
	}
    $criteria->add(new Criteria('status', 0, '!='));
    switch ($type_block) 
	{	// pour le bloc: dernier fichier
		case "date":
			$criteria->setSort('date');
			$criteria->setOrder('DESC');
		break;
		// pour le bloc: plus téléchargé
		case "hits":
			$criteria->setSort('hits');
			$criteria->setOrder('DESC');
		break;
		// pour le bloc: mieux noté
        case "rating":
			$criteria->setSort('rating');
			$criteria->setOrder('DESC');
		break;
        // pour le bloc: aléatoire
		case "random":
			$criteria->setSort('RAND()');
		break;
	}
    $criteria->setLimit($nb_entree);
	$addresses_arr = $addresses_Handler->getall($criteria);
	foreach (array_keys($addresses_arr) as $i) {
		$block[$i]['loc_id'] = $addresses_arr[$i]->getVar('loc_id');
		$block[$i]['title'] = strlen($addresses_arr[$i]->getVar('title')) > $lenght_title ? substr($addresses_arr[$i]->getVar('title'),0,($lenght_title))."..." : $addresses_arr[$i]->getVar('title');
		$block[$i]['hits'] = $addresses_arr[$i]->getVar("hits");
        $block[$i]['rating'] = number_format($addresses_arr[$i]->getVar("rating"),1);
		$block[$i]['date'] = formatTimeStamp($addresses_arr[$i]->getVar("date"),"s");
	}
	return $block;
}

function b_xaddresses_top_edit($options) {
    //appel de la class
    $addressescat_Handler =& xoops_getModuleHandler('xaddresses_cat', 'xaddresses');
    $criteria = new CriteriaCompo();
    $criteria = new CriteriaCompo();
    $criteria->setSort('weight ASC, title');
    $criteria->setOrder('ASC');
    $addressescat_arr = $addressescat_Handler->getall($criteria);
	$form = _MB_XADDRESSES_DISP . "&nbsp;\n";
	$form .= "<input type=\"hidden\" name=\"options[0]\" value=\"" . $options[0] . "\" />";
	$form .= "<input name=\"options[1]\" size=\"5\" maxlength=\"255\" value=\"" . $options[1] . "\" type=\"text\" />&nbsp;" . _MB_XADDRESSES_FILES . "<br />";
	$form .= _MB_XADDRESSES_CHARS . " : <input name=\"options[2]\" size=\"5\" maxlength=\"255\" value=\"" . $options[2] . "\" type=\"text\" /><br /><br />";
	array_shift($options);
	array_shift($options);
	array_shift($options);
	$form .= _MB_XADDRESSES_CATTODISPLAY . "<br /><select name=\"options[]\" multiple=\"multiple\" size=\"5\">";
	$form .= "<option value=\"0\" " . (array_search(0, $options) === false ? '' : 'selected="selected"') . ">" . _MB_XADDRESSES_ALLCAT . "</option>";
	foreach (array_keys($addressescat_arr) as $i) {
		$form .= "<option value=\"" . $addressescat_arr[$i]->getVar('cid') . "\" " . (array_search($addressescat_arr[$i]->getVar('cid'), $options) === false ? '' : 'selected="selected"') . ">".$addressescat_arr[$i]->getVar('title')."</option>";
	}
	$form .= "</select>";
	return $form;
}
?>
