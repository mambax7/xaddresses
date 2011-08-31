<?php
/**
 * Internal function for permissions
 *
 * Returns a list of all the permitted categories Ids for the current user
 *
 * @param string	$permtype	The type of permission
 * @return array Permitted categories Ids
 *
 * @package News
 * @author Hervé Thouzard of Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */

function xaddresses_MygetItemIds($permtype = 'xaddresses_view') {
    global $xoopsUser;
    static $permissions = array();
    if(is_array($permissions) && array_key_exists($permtype, $permissions)) {
        return $permissions[$permtype];
    }

    $module_handler =& xoops_gethandler('module');
    $xaddressesModule =& $module_handler->getByDirname('xaddresses');
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gperm_handler =& xoops_gethandler('groupperm');
    $categories = $gperm_handler->getItemIds($permtype, $groups, $xaddressesModule->getVar('mid'));
    $permissions[$permtype] = $categories;
    return $categories;
}
 /*
/**
* retourne le nombre de téléchargements dans le catégories enfants d'une catégorie
*
* @param   string     $cid    ID da la catégorie dans laquel nous voulons chercher
* @param   string     $status Etat du téléchargment (0,1 ou 2)
* @return  integer    $count  nombre de téléchargements
**/
/*
function nombreEntree($cat_id, $status='') {
    // appel des class
    $categories_Handler =& xoops_getModuleHandler('category', 'xaddresses');
    $addresses_Handler =& xoops_getModuleHandler('address', 'xaddresses');

    $categories = xaddresses_MygetItemIds();
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('cat_id', '(' . implode(',', $categories) . ')','IN'));
    $xaddressescat_arr = $categories_Handler->getall($criteria);
    $mytree = new XoopsObjectTree($xaddressescat_arr, 'cid', 'pid');
    $count = 0;
    if(in_array($cid, $categories)) {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('cid', $cid));
        $criteria->add(new Criteria('status', $status, '>='));
        $count = $addresses_Handler->getCount($criteria);
        
        $child = $mytree->getAllChild($cid);
        foreach (array_keys($child) as $i) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
            $criteria->add(new Criteria('cid', $child[$i]->getVar('cid')));
            $criteria->add(new Criteria('status', $status, '>='));
            $count += $addresses_Handler->getCount($criteria);
        }
    }
    return $count;
}

function nouveau_image($time, $status) {
    global $xoopsModuleConfig;
    $count = 7;
    $new = '';
    $startdate = (time()-(86400 * $count));
    if($xoopsModuleConfig['showupdated'] == 1) {
        if ($startdate < $time) {
            if($status==1) {
                $new = '&nbsp;<img src="' . XOOPS_URL . '/modules/xaddresses/images/newred.gif" alt="' . _MD_XADDRESSES_INDEX_NEWTHISWEEK . '" title="' . _MD_XADDRESSES_INDEX_NEWTHISWEEK . '"/>';
            } elseif($status==2) {
                $new = '&nbsp;<img src="' . XOOPS_URL . '/modules/xaddresses/images/update.gif" alt="' . _MD_XADDRESSES_INDEX_UPTHISWEEK . '" title="' . _MD_XADDRESSES_INDEX_UPTHISWEEK . '"/>';
            }
        }
    }
    return $new;
}

function populaire_image($hits) {
    global $xoopsModuleConfig;
    if ($hits >= $xoopsModuleConfig['popular']) {
        return '&nbsp;<img src ="' . XOOPS_URL . '/modules/xaddresses/images/pop.gif" alt="' . _MD_XADDRESSES_INDEX_POPULAR . '" title="' . _MD_XADDRESSES_INDEX_POPULAR . '"/>';
    }
    return '';
}
*/


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
?>