<?php
function xaddresses_checkModuleAdmin() {
    if ( file_exists($GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php'))){
        include_once $GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php');
        return true;
    } else {
        echo xoops_error("Error: You don't use the Frameworks \"admin module\". Please install this Frameworks");
        return false;
    }
}



function xaddresses_CleanVars(&$global, $key, $default = '', $type = 'int') {
    switch ($type) {
        case 'array':
            $ret = (isset($global[$key]) && is_array($global[$key])) ? $global[$key] : $default;
            break;
        case 'date':
            $ret = (isset($global[$key])) ? strtotime($global[$key]) : $default;
            break;
        case 'string':
            $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_SANITIZE_MAGIC_QUOTES) : $default;
            break;
        case 'int': default:
            $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_SANITIZE_NUMBER_INT) : $default;
            break;
    }
    if ($ret === false) {
        return $default;
    }
    return $ret;
}



function xaddresses_checkModule($dirname) {
    $moduleHandler =& xoops_gethandler('module');
    $checkedModule =& $moduleHandler->getByDirname($dirname);
    if (is_dir(XOOPS_ROOT_PATH . '/modules/' . $dirname)) {
        if ((is_object($checkedModule)) && ($checkedModule instanceof XoopsModule)) {
            if ($checkedModule->isactive()) {
                return round($checkedModule->getVar('version') / 100, 2);
            } else {
                return _AM_XADDRESSES_IMPORT_MOD_NOTACTIVE;
            }
        } else {
            return _AM_XADDRESSES_IMPORT_MOD_NOTINSTALLED;
        }
    } else {
        return _AM_XADDRESSES_IMPORT_MOD_NOTPRESENT;
    }
}



function xaddresses_meta_keywords($content) {
	global $xoopsTpl, $xoTheme;
	$myts =& MyTextSanitizer::getInstance();
	$content= $myts->undoHtmlSpecialChars($myts->displayTbox($content));
	if(isset($xoTheme) && is_object($xoTheme)) {
		$xoTheme->addMeta( 'meta', 'keywords', strip_tags($content));
	} else {	// Compatibility for old Xoops versions
		$xoopsTpl->assign('xoops_meta_keywords', strip_tags($content));
	}
}

function xaddresses_meta_description($content) {
	global $xoopsTpl, $xoTheme;
	$myts =& MyTextSanitizer::getInstance();
	$content= $myts->undoHtmlSpecialChars($myts->displayTarea($content));
	if(isset($xoTheme) && is_object($xoTheme)) {
		$xoTheme->addMeta( 'meta', 'description', strip_tags($content));
	} else {	// Compatibility for old Xoops versions
		$xoopsTpl->assign('xoops_meta_description', strip_tags($content));
	}
}

/**
 * Convert StringToTime Date
 *
 * @param mixed $date
 * @return
 */
function xaddresses_convertDate($date)
{
    if (strpos(_SHORTDATESTRING, "/"))
    {
       $date=str_replace("/", "-", $date);
    }
    return strtotime($date);
}



// 
/**
 * Internal function that return list category children
 *
 * Returns an array of category children
 *
 * @param string	$permtype	The type of permission
 * @return array Permitted categories Ids
 */

function xaddresses_getChildrenTree ($cat_id = 0, $categories, $prefix = '', $sufix = '', $order = '') {
    $prefix = $prefix . '--';
    $sufix = $sufix . '';
    //load classes
    $categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');

    $return = array();
    foreach ($categories as $category) {
        $return[] = array('prefix' => $prefix, 'sufix' => $sufix, 'category' => $category);
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('cat_pid', $category->getVar('cat_id')));
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $subcategories = $categoryHandler->getall($criteria);
        if (count($subcategories) != 0){
            $return = array_merge ($return, xaddresses_getChildrenTree($category->getVar('cat_id'), $subcategories, $prefix, $sufix, $order));
        }
    }
    return $return;
}

/**
 * Internal function for permissions
 *
 * Returns a list of all the permitted categories Ids for the current user
 *
 * @param string	$permtype	The type of permission
 * @return array Permitted categories Ids
 */
function xaddresses_getMyItemIds($permtype = 'in_category_view') {
    static $permissions = array();
    $gpermHandler =& xoops_gethandler('groupperm');

    if(is_array($permissions) && array_key_exists($permtype, $permissions)) {
        return $permissions[$permtype];
    }
    $groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $categories = $gpermHandler->getItemIds($permtype, $groups, $GLOBALS['xoopsModule']->getVar('mid'));
    $permissions[$permtype] = $categories; // static
    return $categories;
}



/**
* This function transforms a numerical size (like 2048) to a lettteral size (like 2MB)
* @param   integer    $ret     numerical size
* @return  string     $size    letteral size
**/
function numToLet($size) {
    if($size>0) {
        $unit=array('B','KB','MB','GB','TB','PB');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    } else {
        return '';
    }
}

/**
* This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
*
* @param   string     $size    letteral size
* @return  integer    $ret     numerical size
**/
function letToNum($size) { 
    $l = substr($size, -1);
    $ret = substr($size, 0, -1);
    switch(strtoupper($l)) {
        case 'P':
        case 'p':
            $ret *= 1024;
        case 'T':
        case 't':
            $ret *= 1024;
        case 'G':
        case 'g':
            $ret *= 1024;
        case 'M':
        case 'm':
            $ret *= 1024;
        case 'K':
        case 'k':
            $ret *= 1024;
            break;
        }
    return $ret;
}



/**
 * This function will read the full structure of a directory.
 * It's recursive because it doesn't stop with the one directory,
 * it just keeps going through all of the directories in the folder you specify.
 *
 */
function getDir($path = '.', $level = 0) {
    $ret = array();
    $ignore = array('cgi-bin', '.', '..');
    // Directories to ignore when listing output. Many hosts will deny PHP access to the cgi-bin.
    $dirHandler = @opendir($path);
    // Open the directory to the handle $dirHandler
    while( false !== ($file = readdir($dirHandler ))){
    // Loop through the directory
        if( !in_array($file, $ignore)){
        // Check that this file is not to be ignored
            $spaces = str_repeat('&nbsp;',( $level * 4 ));
            // Just to add spacing to the list, to better show the directory tree.
            if(is_dir("$path/$file")){
            // Its a directory, so we need to keep reading down...
                $ret[] = "<strong>$spaces $file</strong>";
                $ret = array_merge($ret, getDir($path . DIRECTORY_SEPARATOR . $file, ($level+1)));
                // Re-call this same function but on a new directory.
                // this is what makes function recursive.
            } else {
                $ret[] = "$spaces $file";
                // Just print out the filename
            }
        }
    }
    closedir( $dirHandler );
    // Close the directory handle
    return $ret;
}



/**
 * Create a new directory that contains the file index.html
 *
 */
function makeDir($dir, $perm = 0777) {
    if (!is_dir($dir)){
        if (!@mkdir($dir, $perm)){
            return false;
        } else {
            if ($fileHandler = @fopen($dir . '/index.html', 'w'))
                fwrite($fileHandler, '<script>history.go(-1);</script>');
            @fclose($fileHandler);
            return true;
        }
    }
}



/**
 * Create a new directory that contains the file index.html
 *
 * $source: is the original directory
 * $destination: is the destination directory
 * Returns TRUE on success or FALSE on failure
 *
 */
function copyDir($source, $destination) {
    if (!$dirHandler = opendir($source))
        return false;
    @mkdir($destination);
    while(false !== ( $file = readdir($dirHandler)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($source . '/' . $file) ) {
                if (!copyDir($source . '/' . $file, $destination . '/' . $file))
                    return false;
            }
            else {
                if (!copy($source . '/' . $file, $destination . '/' . $file))
                    return false;
            }
        }
    }
    closedir($dirHandler);
    return true;
}



/**
 * Delete a not empty directory
 *
 * $dir: is the directory to delete
 * $if_not_empty: if FALSE it delete directory only if false
 * Returns TRUE on success or FALSE on failure
 */
function delDir($dir, $if_not_empty = true) {
    if (!file_exists($dir)) return true;
    if ($if_not_empty == true) {
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!delDir($dir . '/' . $item)) return false;
        }
    } else {
        // NOP
    }
    return rmdir($dir);
}

/***************Blocks***************/
function xaddresses_block_addCatSelect($cats) {
	if(is_array($cats))
	{
		$cat_sql = "(".current($cats);
		array_shift($cats);
		foreach($cats as $cat)
		{
			$cat_sql .= ",".$cat;
		}
		$cat_sql .= ")";
	}
	return $cat_sql;
}
?>