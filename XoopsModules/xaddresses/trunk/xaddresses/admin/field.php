<?php
include 'admin_header.php';
$currentFile = basename(__FILE__);
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : (isset($_REQUEST['field_id']) ? 'edit_field' : 'list_fields');

// load classes
$locationHandler =& xoops_getmodulehandler('location', 'xaddresses');
$fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory', 'xaddresses');
$fieldHandler =& xoops_getmodulehandler('field', 'xaddresses');



xoops_cp_header();

// main admin manu
if (!is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php"))	{
    xaddressesAdminMenu(5, _XADDRESSES_MI_ADMENU_FIELD);
} else {
    include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
    loadModuleAdminMenu (5, _XADDRESSES_MI_ADMENU_FIELD);
}



// Submenu
$status_display = isset($_REQUEST['status_display']) ? $_REQUEST['status_display'] : 1;
$submenuItem[] = ($op == 'new_field' ? _XADDRESSES_AM_FIELD_NEW : '<a href="' . $currentFile . '?op=new_field">' . _XADDRESSES_AM_FIELD_NEW . '</a>');
$submenuItem[] = ($op == 'list_fields' ? _XADDRESSES_AM_FIELD_LIST : '<a href="' . $currentFile . '?op=list_fields">' . _XADDRESSES_AM_FIELD_LIST . '</a>');
xaddressesAdminSubmenu ($submenuItem);



switch ($op) {
default:
case 'list_fields':
    // get fields categories
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight');
    $cats = $fieldCategoryHandler->getObjects($criteria, true);
    unset($criteria);
    $categories[0] = _XADDRESSES_AM_FIELD_CATEGORY_DEFAULT;
    if ( count($cats) > 0 ) {
        foreach (array_keys($cats) as $i ) {
            $categories[$cats[$i]->getVar('cat_id')] = $cats[$i]->getVar('cat_title');
        }
    }
    $GLOBALS['xoopsTpl']->assign('categories', $categories);
    unset($categories);

    $valuetypes = array(
        XOBJ_DTYPE_ARRAY =>     _XADDRESSES_AM_FIELD_ARRAY,
        XOBJ_DTYPE_EMAIL =>     _XADDRESSES_AM_FIELD_EMAIL,
        XOBJ_DTYPE_INT =>       _XADDRESSES_AM_FIELD_INT,
        XOBJ_DTYPE_TXTAREA =>   _XADDRESSES_AM_FIELD_TXTAREA,
        XOBJ_DTYPE_TXTBOX =>    _XADDRESSES_AM_FIELD_TXTBOX,
        XOBJ_DTYPE_URL =>       _XADDRESSES_AM_FIELD_URL,
        XOBJ_DTYPE_OTHER =>     _XADDRESSES_AM_FIELD_OTHER,
        XOBJ_DTYPE_MTIME =>     _XADDRESSES_AM_FIELD_DATE
        );
    $fieldtypes = array(
        'checkbox' =>       _XADDRESSES_AM_FIELD_CHECKBOX,
        'group' =>          _XADDRESSES_AM_FIELD_GROUP,
        'group_multi' =>    _XADDRESSES_AM_FIELD_GROUPMULTI,
        'language' =>       _XADDRESSES_AM_FIELD_LANGUAGE,
        'radio' =>          _XADDRESSES_AM_FIELD_RADIO,
        'select' =>         _XADDRESSES_AM_FIELD_SELECT,
        'select_multi' =>   _XADDRESSES_AM_FIELD_SELECTMULTI,
        'textarea' =>       _XADDRESSES_AM_FIELD_TEXTAREA,
        'dhtml' =>          _XADDRESSES_AM_FIELD_DHTMLTEXTAREA,
        'textbox' =>        _XADDRESSES_AM_FIELD_TEXTBOX,
        'timezone' =>       _XADDRESSES_AM_FIELD_TIMEZONE,
        'yesno' =>          _XADDRESSES_AM_FIELD_YESNO,
        'date' =>           _XADDRESSES_AM_FIELD_DATE,
        'datetime' =>       _XADDRESSES_AM_FIELD_DATETIME,
        'longdate' =>       _XADDRESSES_AM_FIELD_LONGDATE,
        'theme' =>          _XADDRESSES_AM_FIELD_THEME,
        'autotext' =>       _XADDRESSES_AM_FIELD_AUTOTEXT,
        'rank' =>           _XADDRESSES_AM_FIELD_RANK,
        'image' =>          _XADDRESSES_AM_FIELD_XOOPSIMAGE,
        'multipleimage' =>  _XADDRESSES_AM_FIELD_MULTIPLEXOOPSIMAGE,
        'file' =>           _XADDRESSES_AM_FIELD_FILE,
        'multiplefile' =>   _XADDRESSES_AM_FIELD_MULTIPLEFILE,
        'kmlmap' =>         _XADDRESSES_AM_FIELD_KMLMAP
        );

    // get fields
    $fields = $fieldHandler->getObjects(null, true, false);  // get an array of arrays
    foreach (array_keys($fields) as $i ) {
        $fields[$i]['canEdit'] = $fields[$i]['field_config'] || $fields[$i]['field_show'] || $fields[$i]['field_edit'];
        $fields[$i]['canDelete'] = $fields[$i]['field_config'];
        $fields[$i]['fieldtype'] = $fieldtypes[$fields[$i]['field_type']];
        $fields[$i]['valuetype'] = $valuetypes[$fields[$i]['field_valuetype']];
        $fieldcategories[$fields[$i]['cat_id']][] = $fields[$i];
        $weights[$fields[$i]['cat_id']][] = $fields[$i]['field_weight'];
    }
    //sort fields order in categories
    foreach (array_keys($fieldcategories) as $i ) {
        array_multisort($weights[$i], SORT_ASC, array_keys($fieldcategories[$i]), SORT_ASC, $fieldcategories[$i]);
    }
    ksort($fields);
    $GLOBALS['xoopsTpl']->assign('fieldcategories', $fieldcategories);

    $GLOBALS['xoopsTpl']->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML() );
    $GLOBALS['xoopsTpl']->display("db:xaddresses_admin_fieldlist.html");
    break;



case 'new_field':
    include_once('../include/forms.php');
    $obj =& $fieldHandler->create();
    $form = xaddresses_getFieldForm($obj);
    $form->display();
    break;



case 'edit_field':
    $obj =& $fieldHandler->get($_REQUEST['field_id']);
    if ( !$obj->getVar('field_config') && !$obj->getVar('field_show') && !$obj->getVar('field_edit')  ) { //If no configs exist
        redirect_header($currentFile, 2, _XADDRESSES_AM_FIELDNOTCONFIGURABLE);
    }
    include_once('../include/forms.php');
    $form = xaddresses_getFieldForm($obj);
    $form->display();
    break;



case 'reorder_fields':
    if ( !$GLOBALS['xoopsSecurity']->check()  ) {
        redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors() ));
    }
    if ( isset($_POST['field_ids']) && count($_POST['field_ids']) > 0 ) {
        $oldweight = $_POST['oldweight'];
        $oldcat = $_POST['oldcat'];
        $category = $_POST['category'];
        $weight = $_POST['weight'];
        $ids = array();
        foreach ($_POST['field_ids'] as $field_id ) {
            if ( $oldweight[$field_id] != $weight[$field_id] || $oldcat[$field_id] != $category[$field_id] ) {
                //if field has changed
                $ids[] = intval($field_id);
            }
        }
        if ( count($ids) > 0 ) {
            $errors = array();
            //if there are changed fields, fetch the fieldcategory objects
            $fields = $fieldHandler->getObjects(new Criteria('field_id', "(" . implode(',', $ids) . ")", "IN"), true);
            foreach ($ids as $i ) {
                $fields[$i]->setVar('field_weight', intval($weight[$i]) );
                $fields[$i]->setVar('cat_id', intval($category[$i]) );
                if ( !$fieldHandler->insert($fields[$i])  ) {
                    $errors = array_merge($errors, $fields[$i]->getErrors() );
                }
            }
            if ( count($errors) == 0 ) {
                //no errors
                redirect_header($currentFile, 2, sprintf(_XADDRESSES_AM_SAVEDSUCCESS, _XADDRESSES_AM_FIELDS) );
            } else {
                redirect_header($currentFile, 3, implode('<br />', $errors) );
            }
        }
    }
    break;



case 'save_field':
    if ( !$GLOBALS['xoopsSecurity']->check()  ) {
        redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors() ));
    }
    // check if field editing is not complete
    $redirectToEdit = false;
    if ( isset($_REQUEST['field_id'])  ) {
        $obj =& $fieldHandler->get($_REQUEST['field_id']);
        // if no configs exist
        if ( !$obj->getVar('field_config') && !$obj->getVar('field_show') && !$obj->getVar('field_edit')  ) { 
            redirect_header('admin.php', 2, _XADDRESSES_AM_FIELDNOTCONFIGURABLE);
        }
    } else {
        // if is a new field
        $obj =& $fieldHandler->create();
        $obj->setVar('field_name', $_REQUEST['field_name']);
        $obj->setVar('field_show', true);
        $obj->setVar('field_edit', true);
        $obj->setVar('field_config', true);
        $redirectToEdit = true;
    }
    $obj->setVar('field_title', $_REQUEST['field_title']);
    $obj->setVar('field_description', $_REQUEST['field_description']);
    if ( $obj->getVar('field_config')  ) {
        $obj->setVar('field_type', $_REQUEST['field_type']);
        if ( isset($_REQUEST['field_valuetype'])  ) {
            $obj->setVar('field_valuetype', $_REQUEST['field_valuetype']);
        }
        
        // field_options
        $options = $obj->getVar('field_options');
        // if options are removed
        if ( isset($_REQUEST['removeOptions']) && is_array($_REQUEST['removeOptions'])  ) {
            foreach ($_REQUEST['removeOptions'] as $index ) {
                unset($options[$index]);
            }
            $redirectToEdit = true;
        }
        // if options are added
        if ( !empty($_REQUEST['addOption'])  ) {
            foreach ($_REQUEST['addOption'] as $option ) {
                if ( empty($option['value']) ) continue;
                $options[$option['key']] = $option['value'];
                $redirectToEdit = true;
            }
        }
        $obj->setVar('field_options', $options);
        
        // field_extras
        $extras = $obj->getVar('field_extras');
        if ( !empty($_REQUEST['addExtra'])  ) {
            foreach ($_REQUEST['addExtra'] as $extra ) {
                if ( empty($extra['value']) ) continue;
                $extras[$extra['key']] = $extra['value'];
                $redirectToEdit = true;
            }
        }
        $obj->setVar('field_extras', $extras);
    }
    if ( $obj->getVar('field_edit')  ) {
        // field_notnull
        //$notnull = isset($_REQUEST['field_notnull']) ? $_REQUEST['field_notnull'] : false;
        //$obj->setVar('field_notnull', $notnull); //0 = no, 1 = yes
        // field_required
        $required = isset($_REQUEST['field_required']) ? $_REQUEST['field_required'] : false;
        $obj->setVar('field_required', $required); //0 = no, 1 = yes
        
        // field_maxlength
        if ( isset($_REQUEST['field_maxlength'])  ) {
            $obj->setVar('field_maxlength', $_REQUEST['field_maxlength']);
        }
        
        // field_default
        if ( isset($_REQUEST['field_default'])  ) {
            $field_default = $obj->getValueForSave($_REQUEST['field_default']);
            //Check for multiple selections
            if ( is_array($field_default)  ) {
                $obj->setVar('field_default', serialize($field_default) );
            } else {
                $obj->setVar('field_default', $field_default);
            }
        }
        
        // field_extras
        if ( isset($_REQUEST['field_extras'])  ) {
            $field_extras = $obj->getValueForSave($_REQUEST['field_extras']);
            //Check for multiple selections
            if ( is_array($field_extras)  ) {
                $obj->setVar('field_extras', serialize($field_extras) );
            } else {
                $obj->setVar('field_extras', $field_extras);
            }
        }
    }

    if ($obj->getVar('field_show')) {
        $obj->setVar('field_weight', $_REQUEST['field_weight']);
        $obj->setVar('cat_id', $_REQUEST['field_category']);
    }
    if ($fieldHandler->insert($obj)) {
        $groupPermHandler =& xoops_gethandler('groupperm');

        $permArray = array();
        if ($obj->getVar('field_show')) {
            $permArray[] = 'field_show';
            $permArray[] = 'field_visible';
        }
        if ($obj->getVar('field_edit')) {
            $permArray[] = 'field_edit';
        }
        if ($obj->getVar('field_edit') || $obj->getVar('field_show')) {
            $permArray[] = 'field_search';
        }
        if (count($permArray) > 0) {
            foreach ($permArray as $perm ) {
                $criteria = new CriteriaCompo(new Criteria('gperm_name', $perm));
                $criteria->add(new Criteria('gperm_itemid', intval($obj->getVar('field_id'))));
                $criteria->add(new Criteria('gperm_modid', intval($GLOBALS['xoopsModule']->getVar('mid'))));
                if ( isset($_REQUEST[$perm]) && is_array($_REQUEST[$perm])) {
                    $perms = $groupPermHandler->getObjects($criteria);
                    if ( count($perms) > 0 ) {
                        foreach (array_keys($perms) as $i ) {
                            $groups[$perms[$i]->getVar('gperm_groupid')] =& $perms[$i];
                        }
                    } else {
                        $groups = array();
                    }
                    foreach ($_REQUEST[$perm] as $groupId ) {
                        $groupId = intval($groupId);
                        if ( !isset($groups[$groupId])) {
                            $permObj =& $groupPermHandler->create();
                            $permObj->setVar('gperm_name', $perm);
                            $permObj->setVar('gperm_itemid', intval($obj->getVar('field_id') ));
                            $permObj->setVar('gperm_modid', $GLOBALS['xoopsModule']->getVar('mid') );
                            $permObj->setVar('gperm_groupid', $groupId);
                            $groupPermHandler->insert($permObj);
                            unset($permObj);
                        }
                    }
                    $removedGroups = array_diff(array_keys($groups), $_REQUEST[$perm]);
                    if ( count($removedGroups) > 0 ) {
                        $criteria->add(new Criteria('gperm_groupid', "(".implode(',', $removedGroups).")", "IN") );
                        $groupPermHandler->deleteAll($criteria);
                    }
                    unset($groups);

                } else {
                    $groupPermHandler->deleteAll($criteria);
                }
                unset($criteria);
            }
        }
        $url = $redirectToEdit ? $currentFile . '?op=edit_field&amp;field_id=' . $obj->getVar('field_id') : $currentFile;
        redirect_header($url, 3, sprintf(_XADDRESSES_AM_SAVEDSUCCESS, _XADDRESSES_AM_FIELD) );
    }
    include_once('../include/forms.php');
    echo $obj->getHtmlErrors();
    $form = xaddresses_getFieldForm($obj);
    $form->display();
    break;



case 'delete_field':
    $obj =& $fieldHandler->get($_REQUEST['field_id']);
    if ( !$obj->getVar('field_config')  ) {
        redirect_header('index.php', 2, _XADDRESSES_AM_FIELDNOTCONFIGURABLE);
    }
    if ( isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1 ) {
        if ( !$GLOBALS['xoopsSecurity']->check()  ) {
            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors() ));
        }
        if ( $fieldHandler->delete($obj)  ) {
        	redirect_header($currentFile, 3, sprintf(_XADDRESSES_AM_DELETEDSUCCESS, _XADDRESSES_AM_FIELD) );
        } else {
            echo $obj->getHtmlErrors();
        }
    } else {
        xoops_confirm(array('ok' => 1, 'field_id' => $_REQUEST['field_id'], 'op' => 'delete_field'), $_SERVER['REQUEST_URI'], sprintf(_XADDRESSES_AM_RUSUREDEL, $obj->getVar('field_title') ));
    }
    break;
}



xoops_cp_footer();
?>