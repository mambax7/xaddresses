<?php
defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

/**
 * Get {@link XoopsThemeForm} for adding/editing fields
 *
 * @param object $field {@link XaddressesField} object to get edit form for
 * @param mixed $action URL to submit to - or false for $_SERVER['REQUEST_URI']
 *
 * @return object
 */
function xaddresses_getFieldForm(&$field, $action = false)
{
    if ( $action === false ) {
        $action = $_SERVER['REQUEST_URI'];
    }
    include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
    
    $title = $field->isNew() ? _XADDRESSES_AM_ADD_FIELD : _XADDRESSES_AM_EDIT_FIELD;
    $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
    // field_title
        $fieldtitletext = new XoopsFormText(_XADDRESSES_AM_FIELD_TITLE, 'field_title', 35, 255, $field->getVar('field_title', 'e'));
        $fieldtitletext->setDescription(_XADDRESSES_AM_FIELD_TITLE_DESC);
    $form->addElement($fieldtitletext);
    // field_description
        $fielddescriptiontextarea = new XoopsFormTextArea(_XADDRESSES_AM_FIELD_DESCRIPTION, 'field_description', $field->getVar('field_description', 'e'));
        $fielddescriptiontextarea->setDescription(_XADDRESSES_AM_FIELD_DESCRIPTION_DESC);
    $form->addElement($fielddescriptiontextarea);
    // field_category
    if (!$field->isNew()) {
        $fieldcat_id = $field->getVar('cat_id');
    } else {
        $fieldcat_id = 0;
    }
    $fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory');
        $fieldcategoryselect = new XoopsFormSelect(_XADDRESSES_AM_FIELD_CATEGORY, 'field_category', $fieldcat_id);
        $fieldcategoryselect->setDescription(_XADDRESSES_AM_FIELD_CATEGORY_DESC);
        $fieldcategoryselect->addOption(0, _XADDRESSES_AM_FIELD_CATEGORY_DEFAULT);
        $fieldcategoryselect->addOptionArray($fieldCategoryHandler->getList());
    $form->addElement($fieldcategoryselect);
    // field weight
        $fieldweighttext = new XoopsFormText(_XADDRESSES_AM_FIELD_WEIGHT, 'field_weight', 10, 10, $field->getVar('field_weight', 'e'));
        $fieldweighttext->setDescription(_XADDRESSES_AM_FIELD_WEIGHT_DESC);
    $form->addElement($fieldweighttext);

    if ($field->getVar('field_config') || $field->isNew()) {
    //field name
        if (!$field->isNew()) {
                $fieldnametext = new XoopsFormLabel(_XADDRESSES_AM_FIELD_NAME, $field->getVar('field_name'));
                $fieldnametext->setDescription(_XADDRESSES_AM_FIELD_NAME_DESC);
            $form->addElement($fieldnametext);
            $form->addElement(new XoopsFormHidden('field_id', $field->getVar('field_id')));
        } else {
                $fieldnametext = new XoopsFormText(_XADDRESSES_AM_FIELD_NAME, 'field_name', 35, 255, $field->getVar('field_name', 'e'));
                $fieldnametext->setDescription(_XADDRESSES_AM_FIELD_NAME_DESC);
            $form->addElement($fieldnametext);
        }
        //field_type
        //autotext and theme left out of this one as fields of that type should never be changed (valid assumption, I think)
        $fieldtypes = array(
            'checkbox'      => _XADDRESSES_AM_FIELD_CHECKBOX,
            'date'          => _XADDRESSES_AM_FIELD_DATE,
            'datetime'      => _XADDRESSES_AM_FIELD_DATETIME,
            'longdate'      => _XADDRESSES_AM_FIELD_LONGDATE,
            'group'         => _XADDRESSES_AM_FIELD_GROUP,
            'group_multi'   => _XADDRESSES_AM_FIELD_GROUPMULTI,
            'language'      => _XADDRESSES_AM_FIELD_LANGUAGE,
            'radio'         => _XADDRESSES_AM_FIELD_RADIO,
            'select'        => _XADDRESSES_AM_FIELD_SELECT,
            'select_multi'  => _XADDRESSES_AM_FIELD_SELECTMULTI,
            'textarea'      => _XADDRESSES_AM_FIELD_TEXTAREA,
            'dhtml'         => _XADDRESSES_AM_FIELD_DHTMLTEXTAREA,
            'textbox'       => _XADDRESSES_AM_FIELD_TEXTBOX,
            'timezone'      => _XADDRESSES_AM_FIELD_TIMEZONE,
            'image'         => _XADDRESSES_AM_FIELD_XOOPSIMAGE,
            'multipleimage' => _XADDRESSES_AM_FIELD_MULTIPLEXOOPSIMAGE,
            'file'          => _XADDRESSES_AM_FIELD_FILE,
            'multiplefile'  => _XADDRESSES_AM_FIELD_MULTIPLEFILE,
            'kmlmap'        => _XADDRESSES_AM_FIELD_KMLMAP,
            'yesno'         => _XADDRESSES_AM_FIELD_YESNO);
            $fieldtypeselect = new XoopsFormSelect(_XADDRESSES_AM_FIELD_TYPE, 'field_type', $field->getVar('field_type', 'e'));
            $fieldtypeselect->setDescription(_XADDRESSES_AM_FIELD_TYPE_DESC);
            $fieldtypeselect->addOptionArray($fieldtypes);
        $form->addElement($fieldtypeselect);
        //field_valuetype
        switch ($field->getVar('field_type')) {
        case "textbox":
            $fieldvaluetypes = array(
                XOBJ_DTYPE_ARRAY            => _XADDRESSES_AM_FIELD_ARRAY,
                XOBJ_DTYPE_EMAIL            => _XADDRESSES_AM_FIELD_EMAIL,
                XOBJ_DTYPE_INT              => _XADDRESSES_AM_FIELD_INT,
                XOBJ_DTYPE_FLOAT            => _XADDRESSES_AM_FIELD_FLOAT,
                XOBJ_DTYPE_DECIMAL          => _XADDRESSES_AM_FIELD_DECIMAL,
                XOBJ_DTYPE_TXTAREA          => _XADDRESSES_AM_FIELD_TXTAREA,
                XOBJ_DTYPE_TXTBOX           => _XADDRESSES_AM_FIELD_TXTBOX,
                XOBJ_DTYPE_URL              => _XADDRESSES_AM_FIELD_URL,
                XOBJ_DTYPE_OTHER            => _XADDRESSES_AM_FIELD_OTHER,
                XOBJ_DTYPE_UNICODE_ARRAY    => _XADDRESSES_AM_FIELD_UNICODE_ARRAY,
                XOBJ_DTYPE_UNICODE_TXTBOX   => _XADDRESSES_AM_FIELD_UNICODE_TXTBOX,
                XOBJ_DTYPE_UNICODE_TXTAREA  => _XADDRESSES_AM_FIELD_UNICODE_TXTAREA,
                XOBJ_DTYPE_UNICODE_EMAIL    => _XADDRESSES_AM_FIELD_UNICODE_EMAIL,
                XOBJ_DTYPE_UNICODE_URL      => _XADDRESSES_AM_FIELD_UNICODE_URL);
            $fieldvaluetypeselect = new XoopsFormSelect(_XADDRESSES_AM_FIELD_VALUETYPE, 'field_valuetype', $field->getVar('field_valuetype', 'e'));
            $fieldvaluetypeselect->setDescription(_XADDRESSES_AM_FIELD_VALUETYPE_DESC);
            $fieldvaluetypeselect->addOptionArray($fieldvaluetypes);
            $form->addElement($fieldvaluetypeselect);
            break;
        case "select":
        case "radio":
            $fieldvaluetypes = array(
                XOBJ_DTYPE_ARRAY            => _XADDRESSES_AM_FIELD_ARRAY,
                XOBJ_DTYPE_EMAIL            => _XADDRESSES_AM_FIELD_EMAIL,
                XOBJ_DTYPE_INT              => _XADDRESSES_AM_FIELD_INT,
                XOBJ_DTYPE_FLOAT            => _XADDRESSES_AM_FIELD_FLOAT,
                XOBJ_DTYPE_DECIMAL          => _XADDRESSES_AM_FIELD_DECIMAL,
                XOBJ_DTYPE_TXTAREA          => _XADDRESSES_AM_FIELD_TXTAREA,
                XOBJ_DTYPE_TXTBOX           => _XADDRESSES_AM_FIELD_TXTBOX,
                XOBJ_DTYPE_URL              => _XADDRESSES_AM_FIELD_URL,
                XOBJ_DTYPE_OTHER            => _XADDRESSES_AM_FIELD_OTHER,
                XOBJ_DTYPE_UNICODE_ARRAY    => _XADDRESSES_AM_FIELD_UNICODE_ARRAY,
                XOBJ_DTYPE_UNICODE_TXTBOX   => _XADDRESSES_AM_FIELD_UNICODE_TXTBOX,
                XOBJ_DTYPE_UNICODE_TXTAREA  => _XADDRESSES_AM_FIELD_UNICODE_TXTAREA,
                XOBJ_DTYPE_UNICODE_EMAIL    => _XADDRESSES_AM_FIELD_UNICODE_EMAIL,
                XOBJ_DTYPE_UNICODE_URL      => _XADDRESSES_AM_FIELD_UNICODE_URL);
            $fieldvaluetypeselect = new XoopsFormSelect(_XADDRESSES_AM_FIELD_VALUETYPE, 'field_valuetype', $field->getVar('field_valuetype', 'e'));
            $fieldvaluetypeselect->setDescription(_XADDRESSES_AM_FIELD_VALUETYPE_DESC);
            $fieldvaluetypeselect->addOptionArray($fieldvaluetypes);
            $form->addElement($fieldvaluetypeselect);
            break;
        }
        //field_notnull
            //$fiedlnotnullradio = new XoopsFormRadioYN(_XADDRESSES_AM_FIELD_NOTNULL, 'field_notnull', $field->getVar('field_notnull', 'e'));
            //$fiedlnotnullradio->setDescription(_XADDRESSES_AM_FIELD_NOTNULL_DESC);
        //$form->addElement($fiedlnotnullradio);
        //field_options
        $fieldtypeswithoptions = array('select', 'select-multi', 'radio', 'checkbox');
        if (in_array($field->getVar('field_type'), $fieldtypeswithoptions)) {
            $options = $field->getVar('field_options');
            if (count($options) > 0) {
                $remove_options = new XoopsFormCheckBox(_XADDRESSES_AM_REMOVEOPTIONS, 'removeOptions');
                $remove_options->columns = 3;
                asort($options);
                foreach (array_keys($options) as $key) {
                    $options[$key] .= "[{$key}]";
                }
                $remove_options->addOptionArray($options);
                $form->addElement($remove_options);
            }
            $option_text = "<table cellspacing='1'><tr><td width='20%'>" . _XADDRESSES_AM_KEY . "</td><td>" . _XADDRESSES_AM_VALUE . "</td></tr>";
            for ($i = 0; $i < 3; $i++) {
                $option_text .= "<tr>";
                $option_text .= "<td><input type='text' name='addOption[{$i}][key]' id='addOption[{$i}][key]' size='15' /></td>";
                $option_text .= "<td><input type='text' name='addOption[{$i}][value]' id='addOption[{$i}][value]' size='35' /></td>";
                $option_text .= "</tr>";
                $option_text .= "<tr height='3px'><td colspan='2'> </td></tr>";
            }
            $option_text .= "</table>";
            $form->addElement(new XoopsFormLabel(_XADDRESSES_AM_ADDOPTION, $option_text) );
        }
/* IN_PROGRESS
        //field_extras
        $fieldtypeswithextras = array('image');
        if (in_array($field->getVar('field_type'), $fieldtypeswithextras)) {
            $extras = $field->getVar('field_extras');
            if (count($extras) > 0) {
                $remove_extras = new XoopsFormCheckBox(_XADDRESSES_AM_REMOVEEXTRAS, 'removeExtras');
                $remove_extras->columns = 3;
                asort($extras);
                foreach (array_keys($extras) as $key) {
                    $extras[$key] .= "[{$key}]";
                }
                $remove_options->addOptionArray($extras);
                $form->addElement($remove_extras);
            }
            $extra_text = "<table cellspacing='1'><tr><td width='20%'>" . _XADDRESSES_AM_KEY . "</td><td>" . _XADDRESSES_AM_VALUE . "</td></tr>";
            for ($i = 0; $i < 3; $i++) {
                $extra_text .= "<tr>";
                $extra_text .= "<td><input type='text' name='addExtra[{$i}][key]' id='addExtra[{$i}][key]' size='15' /></td>";
                $extra_text .= "<td><input type='text' name='addExtra[{$i}][value]' id='addExtra[{$i}][value]' size='35' /></td>";
                $extra_text .= "</tr>";
                $extra_text .= "<tr height='3px'><td colspan='2'> </td></tr>";
            }
            $extra_text .= "</table>";
            $form->addElement(new XoopsFormLabel(_XADDRESSES_AM_ADDEXTRA, $extra_text) );
        }
*/
    }
    //field_default & field_maxlength
    if ($field->getVar('field_edit')) {
        switch ($field->getVar('field_type')) {
        case "textbox":
        case "textarea":
        case "dhtml":
        case "image":
        case "multipleimage":
        case "file":
        case "multiplefile":
        case "kmlmap":
                $fieldmaxlengthtext = new XoopsFormText(_XADDRESSES_AM_FIELD_MAXLENGTH, 'field_maxlength', 35, 35, $field->getVar('field_maxlength', 'e'));
                $fieldmaxlengthtext->setDescription(_XADDRESSES_AM_FIELD_MAXLENGTH_DESC);
            $form->addElement($fieldmaxlengthtext);
                $fielddefaulttextarea = new XoopsFormTextArea(_XADDRESSES_AM_FIELD_DEFAULT, 'field_default', $field->getVar('field_default', 'e'));
                $fielddefaulttextarea->setDescription(_XADDRESSES_AM_FIELD_DEFAULT_DESC);
            $form->addElement($fielddefaulttextarea);
            break;
        case "checkbox":
        case "select_multi":
                $defValue = $field->getVar('field_default', 'e') != null ? unserialize($field->getVar('field_default', 'n')) : null;
                $fielddefaultselect = new XoopsFormSelect(_XADDRESSES_AM_FIELD_DEFAULT, 'field_default', $defValue, 8, true);
                $options = $field->getVar('field_options');
                asort($options);
                // If options do not include an empty element, then add a blank option to prevent any default selection
                if (!in_array('', array_keys($options))) $fielddefaultselect->addOption('', _NONE);
                $fielddefaultselect->addOptionArray($options);
                $fielddefaultselect->setDescription(_XADDRESSES_AM_FIELD_DEFAULT_DESC);
            $form->addElement($fielddefaultselect);
            break;
        case "select":
        case "radio":
                $defValue = $field->getVar('field_default', 'e') != null ? $field->getVar('field_default') : null;
                $fielddefaultselect = new XoopsFormSelect(_XADDRESSES_AM_FIELD_DEFAULT, 'field_default', $defValue);
                $options = $field->getVar('field_options');
                asort($options);
                // If options do not include an empty element, then add a blank option to prevent any default selection
                if (!in_array('', array_keys($options))) $fielddefaultselect->addOption('', _NONE);
                $fielddefaultselect->addOptionArray($options);
                $fielddefaultselect->setDescription(_XADDRESSES_AM_FIELD_DEFAULT_DESC);
            $form->addElement($fielddefaultselect);
            break;
        case "date":
            $form->addElement(new XoopsFormTextDateSelect(_XADDRESSES_AM_FIELD_DEFAULT, 'field_default', 15, $field->getVar('field_default', 'e')));
            break;
        case "longdate":
            $form->addElement(new XoopsFormTextDateSelect(_XADDRESSES_AM_FIELD_DEFAULT, 'field_default', 15, strtotime($field->getVar('field_default', 'e'))));
            break;
        case "datetime":
            $form->addElement(new XoopsFormDateTime(_XADDRESSES_AM_FIELD_DEFAULT, 'field_default', 15, $field->getVar('field_default', 'e')));
            break;
        case "yesno":
            $form->addElement(new XoopsFormRadioYN(_XADDRESSES_AM_FIELD_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
            break;
        case "timezone":
            $form->addElement(new XoopsFormSelectTimezone(_XADDRESSES_AM_FIELD_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
            break;
        case "language":
            $form->addElement(new XoopsFormSelectLang(_XADDRESSES_AM_FIELD_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
            break;
        case "group":
            $form->addElement(new XoopsFormSelectGroup(_XADDRESSES_AM_FIELD_DEFAULT, 'field_default', true, $field->getVar('field_default', 'e')));
            break;
        case "group_multi":
            $form->addElement(new XoopsFormSelectGroup(_XADDRESSES_AM_FIELD_DEFAULT, 'field_default', true, $field->getVar('field_default', 'e'), 5, true));
            break;
        case "theme":
            $form->addElement(new XoopsFormSelectTheme(_XADDRESSES_AM_FIELD_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
            break;
        case "autotext":
            $form->addElement(new XoopsFormTextArea(_XADDRESSES_AM_FIELD_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
            break;
        }
    }
    // field_required
    if ($field->getVar('field_edit') || $field->isNew()) {
            $fieldrequiredradio = new XoopsFormRadioYN(_XADDRESSES_AM_FIELD_REQUIRED, 'field_required', $field->getVar('field_required', 'e'));
            $fieldrequiredradio->setDescription(_XADDRESSES_AM_FIELD_REQUIRED_DESC);
        $form->addElement($fieldrequiredradio);
    }
    // permissions: field_search
    $grouppermHandler =& xoops_gethandler('groupperm');
    $searchableTypes = array(
        'textbox',
        'select',
        'radio',
        'yesno',
        'date',
        'datetime',
        'timezone',
        'language');
    if (in_array($field->getVar('field_type'), $searchableTypes)) {
        $searchableGroups = $grouppermHandler->getGroupIds('field_search', $field->getVar('field_id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $fieldsearchableselectgroup = new XoopsFormSelectGroup(_XADDRESSES_AM_FIELD_SEARCHABLE, 'field_search', true, $searchableGroups, 5, true);
            $fieldsearchableselectgroup->setDescription(_XADDRESSES_AM_FIELD_SEARCHABLE_DESC);
        $form->addElement($fieldsearchableselectgroup);
    }
    // permissions: field_edit
    if ($field->getVar('field_edit') || $field->isNew()) {
        if (!$field->isNew()) {
            //Load groups
            $editableGroups = $grouppermHandler->getGroupIds('field_edit', $field->getVar('field_id'), $GLOBALS['xoopsModule']->getVar('mid'));
        } else {
            $editableGroups = array();
        }
        $fieldeditableselectgroup = new XoopsFormSelectGroup(_XADDRESSES_AM_FIELD_EDITABLE, 'field_edit', false, $editableGroups, 5, true);
            $fieldeditableselectgroup->setDescription(_XADDRESSES_AM_FIELD_EDITABLE_DESC);
        $form->addElement($fieldeditableselectgroup);
    }

    // buttons
    $form->addElement(new XoopsFormHidden('op', 'save_field') );
    $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

    return $form;
}



/**
* Get {@link XoopsThemeForm} for editing a user
*
* @param object $location {@link XaddressesLocation} to edit
*
* @return object
*/
function xaddresses_getLocationForm(&$location, $action = false)
{
    if ($action === false) {
        $action = $_SERVER['REQUEST_URI'];
    }

    $categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
    $locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
    $fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory', 'xaddresses');
    $fieldHandler =& xoops_getmodulehandler('field', 'xaddresses');
    include_once XOOPS_ROOT_PATH . '/modules/xaddresses/class/formgooglemap.php'; // IN PROGRESS individuare la posizione migliore


    include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
    $formTitle = $location->isNew() ? _XADDRESSES_AM_LOC_ADD : _XADDRESSES_AM_LOC_EDIT;
    $form = new XoopsThemeForm($formTitle, 'locationinfo', $action, 'post', true);

    $categories = $categoryHandler->getObjects(null, true, false);

    // Get ids of fields that can be edited
    $groupPermHandler =& xoops_gethandler('groupperm');
    $editableFields = $groupPermHandler->getItemIds('field_edit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );

    // location title
        $formLocTitle = new XoopsFormText(_XADDRESSES_AM_LOC_TITLE, 'loc_title', 35, 255, $location->getVar('loc_title'));
        $formLocTitle->setDescription(_XADDRESSES_AM_LOC_TITLE_DESC);
    $form->addElement($formLocTitle);

    // location coordinates
    $value = array(
        'lat'=>$location->getVar('loc_lat'), 
        'lng'=>$location->getVar('loc_lng'), 
        'zoom'=>$location->getVar('loc_zoom')
        );
        $formGoogleMap = new FormGoogleMap(_XADDRESSES_AM_LOC_COORDINATES, 'loc_googlemap', $value);
        $formGoogleMap->setDescription(_XADDRESSES_AM_LOC_COORDINATES_DESC);
    $form->addElement($formGoogleMap);

    // Get ids of categories in which locations can be viewed/edited/submitted
    $groupPermHandler =& xoops_gethandler('groupperm');
    $viewableCategories = $groupPermHandler->getItemIds('in_category_view', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
    $editableCategories = $groupPermHandler->getItemIds('in_category_edit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );
    $submitableCategories = $groupPermHandler->getItemIds('in_category_submit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid') );

    // location category
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $criteria->add(new Criteria('cat_id', ' (' . implode(',', $editableCategories) . ')', 'IN'));
    $criteria->setOrder('ASC');
    $categoriesArray = $categoryHandler->getall($criteria);
    $mytree = new XoopsObjectTree($categoriesArray, 'cat_id', 'cat_pid');
        $formLocCategory = new XoopsFormLabel(_XADDRESSES_AM_LOC_CAT, $mytree->makeSelBox('loc_cat_id', 'cat_title','--',$location->getVar('loc_cat_id'), false));
        $formLocCategory->setDescription(_XADDRESSES_AM_LOC_CAT_DESC);
    $form->addElement($formLocCategory);

    // Get extra fields categories
    $fieldsCategoriesArray = array();
    $fieldsCategoriesArray[0]= array(
        'cat_id' => 0,
        'cat_title' => _XADDRESSES_AM_FIELD_CATEGORY_DEFAULT,
        'cat_description' => _XADDRESSES_AM_FIELD_CATEGORY_DEFAULT_DESC,
        'cat_weight' => 0);
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $fieldsCategories = $fieldCategoryHandler->getall($criteria, null, false, true); //get fieldscategories as array
    $fieldsCategoriesArray = array_merge($fieldsCategoriesArray, $fieldsCategories);
    
    // Get all extra fields
    $fields = $locationHandler->loadFields();

    // populate $elements[cat_id][field_weight][] tri-dimensional array with {@link XaddressesField} objects
    // $elements[cat_id][field_weight][]['element'] is a {@link XaddressesField} object
    // $elements[cat_id][field_weight][]['required'] is bool (true: required, false: not required)
    $elements = array();
    foreach ($fields as $field) {
        // check if field is editable by user
        if (in_array($field->getVar('field_id'), $editableFields)) {
        // Set default value for location fields if available
            if ($location->isNew()) {
                $default = $field->getVar('field_default');
                if ($default !== '' && $default !== null) {
                    $location->setVar($field->getVar('field_name'), $default);
                }
            }
        // Fill elements array indexed by cat_id and field_weight
        $element = array();
        $element['element'] = $field->getEditElement($location);
        $element['required'] = $field->getVar('field_required');
        $elements[$field->getVar('cat_id')][$field->getVar('field_weight')][] = $element;
        unset($categorySubElement);
        }
    }

    //ksort($elements);

    foreach ($fieldsCategoriesArray as $fieldsCategoryArray) {
        $form->addElement(new XoopsFormLabel ('<h3>' . $fieldsCategoryArray['cat_title'] . '</h3>', $fieldsCategoryArray['cat_description']));
        $cat_id = $fieldsCategoryArray['cat_id'];
        $elementsByCategory = array();
        $elementsByCategory = $elements[$cat_id];
        foreach ($elementsByCategory as $elementsByWeight) {
            foreach ($elementsByWeight as $element) {
                $form->addElement($element['element'], $element['required']);
            }
        }
        unset($elementsByCategory);
    }

    $form->addElement(new XoopsFormHidden('loc_id', $location->getVar('loc_id')));
    $form->addElement(new XoopsFormHidden('op', 'save_location'));
    $form->addElement(new XoopsFormButton('', 'submit', _US_SAVECHANGES, 'submit'));
    return $form;
}
?>