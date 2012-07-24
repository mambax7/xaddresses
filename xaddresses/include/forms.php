<?php
defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");







/**
 * Get {@link XoopsThemeForm} for adding/editing fields
 *
 * @param object $field {@link XaddressesField} object to get edit form for
 * @param mixed $action URL to submit to - or false for $_SERVER['REQUEST_URI']
 * @param object $form {@link XoopsThemeForm} object or null
 *
 * @return object
 */
function xaddresses_getFieldForm(&$field, $action = false, &$form = null)
{
    // Module classes/handlers
    $fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory', 'xaddresses');
    $fieldHandler =& xoops_getmodulehandler('field', 'xaddresses');
    // Xoops classes/handlers
    $groupPermHandler =& xoops_gethandler('groupperm');

    if ($action === false) {
        $action = $_SERVER['REQUEST_URI'];
    }
    include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
    if (!isset($form) || empty($form) || get_class($form) != 'XoopsThemeForm') {
        $formIsNew = true;
        $title = $field->isNew() ? _AM_XADDRESSES_ADD_FIELD : _AM_XADDRESSES_EDIT_FIELD;
        $form = new XoopsThemeForm($title, 'field_form', $action, 'post', true);
    } else {
        $formIsNew = false;
    }
    $form->setExtra('enctype="multipart/form-data"');

    // Get user groups
    if (is_object($GLOBALS['xoopsUser'])) {
        $groups = $GLOBALS['xoopsUser']->getGroups();
    } else {
    	$groups = XOOPS_GROUP_ANONYMOUS;
    }

    // field_title
        $fieldTitleText = new XoopsFormText(_AM_XADDRESSES_FIELD_TITLE, 'field_title', 35, 255, $field->getVar('field_title', 'e'));
        $fieldTitleText->setDescription(_AM_XADDRESSES_FIELD_TITLE_DESC);
    $form->addElement($fieldTitleText);
    // field_description
        $fieldDescriptionTextarea = new XoopsFormTextArea(_AM_XADDRESSES_FIELD_DESCRIPTION, 'field_description', $field->getVar('field_description', 'e'));
        $fieldDescriptionTextarea->setDescription(_AM_XADDRESSES_FIELD_DESCRIPTION_DESC);
    $form->addElement($fieldDescriptionTextarea);
    // field_category
    if (!$field->isNew()) {
        $fieldcat_id = $field->getVar('cat_id');
    } else {
        $fieldcat_id = 0;
    }
        $fieldCategorySelect = new XoopsFormSelect(_AM_XADDRESSES_FIELD_CATEGORY, 'field_category', $fieldcat_id);
        $fieldCategorySelect->setDescription(_AM_XADDRESSES_FIELD_CATEGORY_DESC);
        $fieldCategorySelect->addOption(0, _AM_XADDRESSES_FIELD_CATEGORY_NONE);
        $fieldCategorySelect->addOptionArray($fieldCategoryHandler->getList());
    $form->addElement($fieldCategorySelect);
    // field weight
        $fieldWeightText = new XoopsFormText(_AM_XADDRESSES_FIELD_WEIGHT, 'field_weight', 10, 10, $field->getVar('field_weight', 'e'));
        $fieldWeightText->setDescription(_AM_XADDRESSES_FIELD_WEIGHT_DESC);
    $form->addElement($fieldWeightText);

    if ($field->getVar('field_config') || $field->isNew()) {
    //field name
        if (!$field->isNew()) {
                $fieldNameText = new XoopsFormLabel(_AM_XADDRESSES_FIELD_NAME, $field->getVar('field_name'));
                $fieldNameText->setDescription(_AM_XADDRESSES_FIELD_NAME_DESC);
            $form->addElement($fieldNameText);
            $form->addElement(new XoopsFormHidden('field_id', $field->getVar('field_id')));
        } else {
                $fieldNameText = new XoopsFormText(_AM_XADDRESSES_FIELD_NAME, 'field_name', 35, 255, $field->getVar('field_name', 'e'));
                $fieldNameText->setDescription(_AM_XADDRESSES_FIELD_NAME_DESC);
            $form->addElement($fieldNameText);
        }
        //field_type
        //autotext and theme left out of this one as fields of that type should never be changed (valid assumption, I think)
        $fieldTypes = array(
            'checkbox'      => _AM_XADDRESSES_FIELD_CHECKBOX,
            'date'          => _AM_XADDRESSES_FIELD_DATE,
            'datetime'      => _AM_XADDRESSES_FIELD_DATETIME,
            'longdate'      => _AM_XADDRESSES_FIELD_LONGDATE,
            'group'         => _AM_XADDRESSES_FIELD_GROUP,
            'group_multi'   => _AM_XADDRESSES_FIELD_GROUPMULTI,
            'language'      => _AM_XADDRESSES_FIELD_LANGUAGE,
            'radio'         => _AM_XADDRESSES_FIELD_RADIO,
            'select'        => _AM_XADDRESSES_FIELD_SELECT,
            'select_multi'  => _AM_XADDRESSES_FIELD_SELECTMULTI,
            'textarea'      => _AM_XADDRESSES_FIELD_TEXTAREA,
            'dhtml'         => _AM_XADDRESSES_FIELD_DHTMLTEXTAREA,
            'textbox'       => _AM_XADDRESSES_FIELD_TEXTBOX,
            'timezone'      => _AM_XADDRESSES_FIELD_TIMEZONE,
            'image'         => _AM_XADDRESSES_FIELD_XOOPSIMAGE,
            'multipleimage' => _AM_XADDRESSES_FIELD_MULTIPLEXOOPSIMAGE,
            'file'          => _AM_XADDRESSES_FIELD_FILE,
            'multiplefile'  => _AM_XADDRESSES_FIELD_MULTIPLEFILE,
            'kmlmap'        => _AM_XADDRESSES_FIELD_KMLMAP,
            'yesno'         => _AM_XADDRESSES_FIELD_YESNO);
            $fieldTypeSelect = new XoopsFormSelect(_AM_XADDRESSES_FIELD_TYPE, 'field_type', $field->getVar('field_type', 'e'));
            $fieldTypeSelect->setDescription(_AM_XADDRESSES_FIELD_TYPE_DESC);
            $fieldTypeSelect->addOptionArray($fieldTypes);
        $form->addElement($fieldTypeSelect);
        //field_valuetype
        switch ($field->getVar('field_type')) {
        case "textbox":
            $fieldValueTypes = array(
                XOBJ_DTYPE_ARRAY            => _AM_XADDRESSES_FIELD_ARRAY,
                XOBJ_DTYPE_EMAIL            => _AM_XADDRESSES_FIELD_EMAIL,
                XOBJ_DTYPE_INT              => _AM_XADDRESSES_FIELD_INT,
                XOBJ_DTYPE_FLOAT            => _AM_XADDRESSES_FIELD_FLOAT,
                XOBJ_DTYPE_DECIMAL          => _AM_XADDRESSES_FIELD_DECIMAL,
                XOBJ_DTYPE_TXTAREA          => _AM_XADDRESSES_FIELD_TXTAREA,
                XOBJ_DTYPE_TXTBOX           => _AM_XADDRESSES_FIELD_TXTBOX,
                XOBJ_DTYPE_URL              => _AM_XADDRESSES_FIELD_URL,
                XOBJ_DTYPE_OTHER            => _AM_XADDRESSES_FIELD_OTHER,
                XOBJ_DTYPE_UNICODE_ARRAY    => _AM_XADDRESSES_FIELD_UNICODE_ARRAY,
                XOBJ_DTYPE_UNICODE_TXTBOX   => _AM_XADDRESSES_FIELD_UNICODE_TXTBOX,
                XOBJ_DTYPE_UNICODE_TXTAREA  => _AM_XADDRESSES_FIELD_UNICODE_TXTAREA,
                XOBJ_DTYPE_UNICODE_EMAIL    => _AM_XADDRESSES_FIELD_UNICODE_EMAIL,
                XOBJ_DTYPE_UNICODE_URL      => _AM_XADDRESSES_FIELD_UNICODE_URL);
            $fieldValueTypeSelect = new XoopsFormSelect(_AM_XADDRESSES_FIELD_VALUETYPE, 'field_valuetype', $field->getVar('field_valuetype', 'e'));
            $fieldValueTypeSelect->setDescription(_AM_XADDRESSES_FIELD_VALUETYPE_DESC);
            $fieldValueTypeSelect->addOptionArray($fieldValueTypes);
            $form->addElement($fieldValueTypeSelect);
            break;
        case "select":
        case "radio":
            $fieldValueTypes = array(
                XOBJ_DTYPE_ARRAY            => _AM_XADDRESSES_FIELD_ARRAY,
                XOBJ_DTYPE_EMAIL            => _AM_XADDRESSES_FIELD_EMAIL,
                XOBJ_DTYPE_INT              => _AM_XADDRESSES_FIELD_INT,
                XOBJ_DTYPE_FLOAT            => _AM_XADDRESSES_FIELD_FLOAT,
                XOBJ_DTYPE_DECIMAL          => _AM_XADDRESSES_FIELD_DECIMAL,
                XOBJ_DTYPE_TXTAREA          => _AM_XADDRESSES_FIELD_TXTAREA,
                XOBJ_DTYPE_TXTBOX           => _AM_XADDRESSES_FIELD_TXTBOX,
                XOBJ_DTYPE_URL              => _AM_XADDRESSES_FIELD_URL,
                XOBJ_DTYPE_OTHER            => _AM_XADDRESSES_FIELD_OTHER,
                XOBJ_DTYPE_UNICODE_ARRAY    => _AM_XADDRESSES_FIELD_UNICODE_ARRAY,
                XOBJ_DTYPE_UNICODE_TXTBOX   => _AM_XADDRESSES_FIELD_UNICODE_TXTBOX,
                XOBJ_DTYPE_UNICODE_TXTAREA  => _AM_XADDRESSES_FIELD_UNICODE_TXTAREA,
                XOBJ_DTYPE_UNICODE_EMAIL    => _AM_XADDRESSES_FIELD_UNICODE_EMAIL,
                XOBJ_DTYPE_UNICODE_URL      => _AM_XADDRESSES_FIELD_UNICODE_URL);
            $fieldValueTypeSelect = new XoopsFormSelect(_AM_XADDRESSES_FIELD_VALUETYPE, 'field_valuetype', $field->getVar('field_valuetype', 'e'));
            $fieldValueTypeSelect->setDescription(_AM_XADDRESSES_FIELD_VALUETYPE_DESC);
            $fieldValueTypeSelect->addOptionArray($fieldValueTypes);
            $form->addElement($fieldValueTypeSelect);
            break;
        }
        //field_notnull
            //$fiedlnotnullradio = new XoopsFormRadioYN(_AM_XADDRESSES_FIELD_NOTNULL, 'field_notnull', $field->getVar('field_notnull', 'e'));
            //$fiedlnotnullradio->setDescription(_AM_XADDRESSES_FIELD_NOTNULL_DESC);
        //$form->addElement($fiedlnotnullradio);

        //field_options
        $fieldTypesWithOptions = array('select', 'select-multi', 'radio', 'checkbox');
        if (in_array($field->getVar('field_type'), $fieldTypesWithOptions)) {
            $options = $field->getVar('field_options');
//            print_r($options);
            if (count($options) > 0) {
                $remove_options = new XoopsFormCheckBox(_AM_XADDRESSES_FIELD_REMOVEOPTIONS, 'removeOptions');
                $remove_options->columns = 3;
                asort($options);
                foreach (array_keys($options) as $key) {
                    $options[$key] .= "[{$key}]";
                }
                $remove_options->addOptionArray($options);
                $form->addElement($remove_options);
            }
            $option_text = "<table cellspacing='1'><tr><td width='20%'>" . _AM_XADDRESSES_FIELD_KEY . "</td><td>" . _AM_XADDRESSES_FIELD_VALUE . "</td></tr>";
            for ($i = 0; $i < 3; $i++) {
                $option_text .= "<tr>";
                $option_text .= "<td><input type='text' name='addOption[{$i}][key]' id='addOption[{$i}][key]' size='15' /></td>";
                $option_text .= "<td><input type='text' name='addOption[{$i}][value]' id='addOption[{$i}][value]' size='35' /></td>";
                $option_text .= "</tr>";
                $option_text .= "<tr height='3px'><td colspan='2'> </td></tr>";
            }
            $option_text .= "</table>";
            $form->addElement(new XoopsFormLabel(_AM_XADDRESSES_FIELD_ADDOPTION, $option_text) );
        }
/* IN_PROGRESS
        //field_extras
        $fieldTypeswithextras = array('image');
        if (in_array($field->getVar('field_type'), $fieldTypeswithextras)) {
            $extras = $field->getVar('field_extras');
            if (count($extras) > 0) {
                $remove_extras = new XoopsFormCheckBox(_AM_XADDRESSES_REMOVEEXTRAS, 'removeExtras');
                $remove_extras->columns = 3;
                asort($extras);
                foreach (array_keys($extras) as $key) {
                    $extras[$key] .= "[{$key}]";
                }
                $remove_options->addOptionArray($extras);
                $form->addElement($remove_extras);
            }
            $extra_text = "<table cellspacing='1'><tr><td width='20%'>" . _AM_XADDRESSES_KEY . "</td><td>" . _AM_XADDRESSES_VALUE . "</td></tr>";
            for ($i = 0; $i < 3; $i++) {
                $extra_text .= "<tr>";
                $extra_text .= "<td><input type='text' name='addExtra[{$i}][key]' id='addExtra[{$i}][key]' size='15' /></td>";
                $extra_text .= "<td><input type='text' name='addExtra[{$i}][value]' id='addExtra[{$i}][value]' size='35' /></td>";
                $extra_text .= "</tr>";
                $extra_text .= "<tr height='3px'><td colspan='2'> </td></tr>";
            }
            $extra_text .= "</table>";
            $form->addElement(new XoopsFormLabel(_AM_XADDRESSES_ADDEXTRA, $extra_text) );
        }
*/
    }
    //field_default & field_maxlength
    if ($field->getVar('field_edit')) {
        switch ($field->getVar('field_type')) {
        case "textbox":
        case "textarea":
        case "dhtml":
        case "kmlmap":
                $fieldLengthText = new XoopsFormText(_AM_XADDRESSES_FIELD_LENGTH, 'field_length', 35, 35, $field->getVar('field_length', 'e'));
                $fieldLengthText->setDescription(_AM_XADDRESSES_FIELD_LENGTH_DESC);
            $form->addElement($fieldLengthText);
                $fieldMaxLengthText = new XoopsFormText(_AM_XADDRESSES_FIELD_MAXLENGTH, 'field_maxlength', 35, 35, $field->getVar('field_maxlength', 'e'));
                $fieldMaxLengthText->setDescription(_AM_XADDRESSES_FIELD_MAXLENGTH_DESC);
            $form->addElement($fieldMaxLengthText);
                $fieldDefaultTextarea = new XoopsFormTextArea(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', $field->getVar('field_default', 'e'));
                $fieldDefaultTextarea->setDescription(_AM_XADDRESSES_FIELD_DEFAULT_DESC);
            $form->addElement($fieldDefaultTextarea);
            break;
        case "image":
                $fieldLengthText = new XoopsFormText(_AM_XADDRESSES_FIELD_LENGTH, 'field_length', 35, 35, $field->getVar('field_length', 'e'));
                $fieldLengthText->setDescription(_AM_XADDRESSES_FIELD_LENGTH_DESC);
            $form->addElement($fieldLengthText);
                $fieldMaxLengthText = new XoopsFormText(_AM_XADDRESSES_FIELD_MAXLENGTH, 'field_maxlength', 35, 35, $field->getVar('field_maxlength', 'e'));
                $fieldMaxLengthText->setDescription(_AM_XADDRESSES_FIELD_MAXLENGTH_DESC);
            $form->addElement($fieldMaxLengthText);
                $fieldDefaultXoopsImage = new FormXoopsImage (_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', 35, 255, $field->getVar('field_default', 'e')); // custom form class
                $fieldDefaultXoopsImage->setDescription(_AM_XADDRESSES_FIELD_DEFAULT_DESC);
            $form->addElement($fieldDefaultXoopsImage);
            break;
        case "multipleimage":
                $fieldLengthText = new XoopsFormText(_AM_XADDRESSES_FIELD_LENGTH, 'field_length', 35, 35, $field->getVar('field_length', 'e'));
                $fieldLengthText->setDescription(_AM_XADDRESSES_FIELD_LENGTH_DESC);
            $form->addElement($fieldLengthText);
                $fieldMaxLengthText = new XoopsFormText(_AM_XADDRESSES_FIELD_MAXLENGTH, 'field_maxlength', 35, 35, $field->getVar('field_maxlength', 'e'));
                $fieldMaxLengthText->setDescription(_AM_XADDRESSES_FIELD_MAXLENGTH_DESC);
            $form->addElement($fieldMaxLengthText);
                $fieldDefaultMultipleXoopsImage = new FormMultipleXoopsImage(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', 35, 255, $field->getVar('field_default', 'e'), null);
                $fieldDefaultMultipleXoopsImage->setDescription(_AM_XADDRESSES_FIELD_DEFAULT_DESC);
            $form->addElement($fieldDefaultMultipleXoopsImage);
            break;
        case "file":
                $fieldLengthText = new XoopsFormText(_AM_XADDRESSES_FIELD_LENGTH, 'field_length', 35, 35, $field->getVar('field_length', 'e'));
                $fieldLengthText->setDescription(_AM_XADDRESSES_FIELD_LENGTH_DESC);
            $form->addElement($fieldLengthText);
                $fieldMaxLengthText = new XoopsFormText(_AM_XADDRESSES_FIELD_MAXLENGTH, 'field_maxlength', 35, 35, $field->getVar('field_maxlength', 'e'));
                $fieldMaxLengthText->setDescription(_AM_XADDRESSES_FIELD_MAXLENGTH_DESC);
            $form->addElement($fieldMaxLengthText);
                $fieldDefaultXoopsFilemanager = new FormFileManager (_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', 35, 255, $field->getVar('field_default', 'e')); // custom form class
                $fieldDefaultXoopsFilemanager->setDescription(_AM_XADDRESSES_FIELD_DEFAULT_DESC);
            $form->addElement($fieldDefaultXoopsFilemanager);
            break;
        case "multiplefile":
                $fieldLengthText = new XoopsFormText(_AM_XADDRESSES_FIELD_LENGTH, 'field_length', 35, 35, $field->getVar('field_length', 'e'));
                $fieldLengthText->setDescription(_AM_XADDRESSES_FIELD_LENGTH_DESC);
            $form->addElement($fieldLengthText);
                $fieldMaxLengthText = new XoopsFormText(_AM_XADDRESSES_FIELD_MAXLENGTH, 'field_maxlength', 35, 35, $field->getVar('field_maxlength', 'e'));
                $fieldMaxLengthText->setDescription(_AM_XADDRESSES_FIELD_MAXLENGTH_DESC);
            $form->addElement($fieldMaxLengthText);
                $fieldDefaultMultipleXoopsFilemanager = new FormMultipleFilemanager(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', 35, 255, $field->getVar('field_default', 'e'), null);
                $fieldDefaultMultipleXoopsFilemanager->setDescription(_AM_XADDRESSES_FIELD_DEFAULT_DESC);
            $form->addElement($fieldDefaultMultipleXoopsFilemanager);
            break;
        case "checkbox":
        case "select_multi":
                $defValue = $field->getVar('field_default', 'e') != null ? unserialize($field->getVar('field_default', 'n')) : null;
                $fieldDefaultSelect = new XoopsFormSelect(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', $defValue, 8, true);
                $options = $field->getVar('field_options');
                asort($options);
                // If options do not include an empty element, then add a blank option to prevent any default selection
                if (!in_array('', array_keys($options))) $fieldDefaultSelect->addOption('', _NONE);
                $fieldDefaultSelect->addOptionArray($options);
                $fieldDefaultSelect->setDescription(_AM_XADDRESSES_FIELD_DEFAULT_DESC);
            $form->addElement($fieldDefaultSelect);
            break;
        case "select":
        case "radio":
                $defValue = $field->getVar('field_default', 'e') != null ? $field->getVar('field_default') : null;
                $fieldDefaultSelect = new XoopsFormSelect(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', $defValue);
                $options = $field->getVar('field_options');
                asort($options);
                // If options do not include an empty element, then add a blank option to prevent any default selection
                if (!in_array('', array_keys($options))) $fieldDefaultSelect->addOption('', _NONE);
                $fieldDefaultSelect->addOptionArray($options);
                $fieldDefaultSelect->setDescription(_AM_XADDRESSES_FIELD_DEFAULT_DESC);
            $form->addElement($fieldDefaultSelect);
            break;
        case "date":
            $form->addElement(new XoopsFormTextDateSelect(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', 15, $field->getVar('field_default', 'e')));
            break;
        case "longdate":
            $form->addElement(new XoopsFormTextDateSelect(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', 15, strtotime($field->getVar('field_default', 'e'))));
            break;
        case "datetime":
            $form->addElement(new XoopsFormDateTime(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', 15, $field->getVar('field_default', 'e')));
            break;
        case "yesno":
            $form->addElement(new XoopsFormRadioYN(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
            break;
        case "timezone":
            $form->addElement(new XoopsFormSelectTimezone(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
            break;
        case "language":
            $form->addElement(new XoopsFormSelectLang(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
            break;
        case "group":
            $form->addElement(new XoopsFormSelectGroup(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', true, $field->getVar('field_default', 'e')));
            break;
        case "group_multi":
            $form->addElement(new XoopsFormSelectGroup(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', true, $field->getVar('field_default', 'e'), 5, true));
            break;
        case "theme":
            $form->addElement(new XoopsFormSelectTheme(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
            break;
        case "autotext":
            $form->addElement(new XoopsFormTextArea(_AM_XADDRESSES_FIELD_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
            break;
        }
    }
    // field_required
    if ($field->getVar('field_edit') || $field->isNew()) {
            $fieldRequiredRadio = new XoopsFormRadioYN(_AM_XADDRESSES_FIELD_REQUIRED, 'field_required', $field->getVar('field_required', 'e'));
            $fieldRequiredRadio->setDescription(_AM_XADDRESSES_FIELD_REQUIRED_DESC);
        $form->addElement($fieldRequiredRadio);
    }

    // Permissions

    
    // Permissions: field_view, field_edit, field_export
    if ($field->getVar('field_edit') || $field->isNew()) {
        if (!$field->isNew()) {
            //Load groups
            $viewableGroups = $groupPermHandler->getGroupIds('field_view', $field->getVar('field_id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $editableGroups = $groupPermHandler->getGroupIds('field_edit', $field->getVar('field_id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $exportableGroups = $groupPermHandler->getGroupIds('field_export', $field->getVar('field_id'), $GLOBALS['xoopsModule']->getVar('mid'));
        } else {
            $viewableGroups = array();
            $editableGroups = array();
            $exportableGroups = array();
        }
        $fieldViewableSelectGroup = new XoopsFormSelectGroup(_AM_XADDRESSES_FIELD_VIEWABLE, 'field_view', true, $viewableGroups, 5, true);
            $fieldViewableSelectGroup->setDescription(_AM_XADDRESSES_FIELD_VIEWABLE_DESC);
        $form->addElement($fieldViewableSelectGroup);

        $fieldEditableSelectGroup = new XoopsFormSelectGroup(_AM_XADDRESSES_FIELD_EDITABLE, 'field_edit', true, $editableGroups, 5, true);
            $fieldEditableSelectGroup->setDescription(_AM_XADDRESSES_FIELD_EDITABLE_DESC);
        $form->addElement($fieldEditableSelectGroup);
    
        $fieldExportableSelectGroup = new XoopsFormSelectGroup(_AM_XADDRESSES_FIELD_EXPORTABLE, 'field_export', true, $editableGroups, 5, true);
            $fieldExportableSelectGroup->setDescription(_AM_XADDRESSES_FIELD_EXPORTABLE_DESC);
        $form->addElement($fieldExportableSelectGroup);
    }

    // Permissions: field_search
    $searchableTypes = array(
        'textbox',
        'textarea',
        'select',
        'radio',
        'yesno',
        'date',
        'datetime',
        'timezone',
        'language');
    if (in_array($field->getVar('field_type'), $searchableTypes)) {
        $searchableGroups = $groupPermHandler->getGroupIds('field_search', $field->getVar('field_id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $fieldSearchableSelectGroup = new XoopsFormSelectGroup(_AM_XADDRESSES_FIELD_SEARCHABLE, 'field_search', true, $searchableGroups, 5, true);
            $fieldSearchableSelectGroup->setDescription(_AM_XADDRESSES_FIELD_SEARCHABLE_DESC);
        $form->addElement($fieldSearchableSelectGroup);
    }

    if ($formIsNew) {
        $form->addElement(new XoopsFormHidden('op', 'save_field') );
        // Submit button		
            $button_tray = new XoopsFormElementTray(_AM_XADDRESSES_ACTION, '' ,'');
            $submit = $field->isNew() ? _AM_XADDRESSES_FIELD_SUBMIT_AND_EDIT : _SUBMIT;
            $button_tray->addElement(new XoopsFormButton('', 'submit', $submit, 'submit'));
            $button_tray->addElement(new XoopsFormButton('', 'reset', _RESET, 'reset'));
                $cancel_button = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
                $cancel_button->setExtra("onclick='javascript:history.back();'");
            $button_tray->addElement($cancel_button);
        $form->addElement($button_tray);
    }
    return $form;
}







/**
 * Get {@link XoopsThemeForm} for adding/editing locations
 *
 * @param object $location {@link XaddressesLocation} to edit
 * @param mixed $action URL to submit to - or false for $_SERVER['REQUEST_URI']
 * @param object $form {@link XoopsThemeForm} object or null
 *
 * @return object
 */
function xaddresses_getLocationForm(&$location, $action = false, &$form = null)
{
    // Module classes/handlers
    $categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
    $locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
    $fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory', 'xaddresses');
    $fieldHandler =& xoops_getmodulehandler('field', 'xaddresses');
    xoops_load('formgooglemap', 'xaddresses');
    // Xoops classes/handlers
    $memberHandler =& xoops_gethandler('member');
    $groupPermHandler =& xoops_gethandler('groupperm');

    if ($action === false) {
        $action = $_SERVER['REQUEST_URI'];
    }
    include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
    if (!isset($form) || empty($form) || get_class($form) != 'XoopsThemeForm') {
        $formIsNew = true;
        $formTitle = $location->isNew() ? _AM_XADDRESSES_LOC_ADD : _AM_XADDRESSES_LOC_EDIT;
        $form = new XoopsThemeForm($formTitle, 'location_form', $action, 'post', true);
    } else {
        $formIsNew = false;
    }
    $form->setExtra('enctype="multipart/form-data"');

    // Get user groups
    if (is_object($GLOBALS['xoopsUser'])) {
        $groups = $GLOBALS['xoopsUser']->getGroups();
    } else {
    	$groups = XOOPS_GROUP_ANONYMOUS;
    }
    // Get ids of categories in which locations can be viewed/edited/submitted
    $viewableCategoriesIds = $groupPermHandler->getItemIds('in_category_view', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
    $editableCategoriesIds = $groupPermHandler->getItemIds('in_category_edit', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
    $submitableCategoriesIds = $groupPermHandler->getItemIds('in_category_submit', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
    // Get ids of fields that can be edited
    $editableFields = $groupPermHandler->getItemIds('field_edit', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
    // Get other permissions
    $permModifySubmitter = ($groupPermHandler->checkRight('others', 1, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
    $permModifyDate = ($groupPermHandler->checkRight('others', 2, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;



    // location title
        $formLocTitle = new XoopsFormText(_AM_XADDRESSES_LOC_TITLE, 'loc_title', 35, 255, $location->getVar('loc_title'));
        $formLocTitle->setDescription(_AM_XADDRESSES_LOC_TITLE_DESC);
    $form->addElement($formLocTitle, true);

    // location coordinates
    $value = array(
        'lat'=>$location->getVar('loc_lat'), 
        'lng'=>$location->getVar('loc_lng'), 
        'elevation'=>$location->getVar('loc_elevation'), 
        'zoom'=>$location->getVar('loc_zoom')
        );
        $formGoogleMap = new FormGoogleMap(_AM_XADDRESSES_LOC_COORDINATES, 'loc_googlemap', $value);
        $formGoogleMap->setDescription(_AM_XADDRESSES_LOC_COORDINATES_DESC);
    $form->addElement($formGoogleMap);


    // location category
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $criteria->add(new Criteria('cat_id', ' (' . implode(',', $editableCategoriesIds) . ')', 'IN'));
    $categoriesArray = $categoryHandler->getall($criteria);
    $categoriesTree = new XoopsObjectTree($categoriesArray, 'cat_id', 'cat_pid');
        //$categoryId = (!is_null($location->getVar('loc_cat_id')) ? $location->getVar('loc_cat_id') : $categoriesArray[1]->getVar('cat_id'));
        $categoryId = $location->getVar('loc_cat_id');
        $formLocCategory = new XoopsFormLabel(_AM_XADDRESSES_LOC_CAT, $categoriesTree->makeSelBox('loc_cat_id', 'cat_title', '--', $location->getVar('loc_cat_id'), false));
        $formLocCategory->setDescription(_AM_XADDRESSES_LOC_CAT_DESC);
    $form->addElement($formLocCategory, true);

//$categoriesTree->makeSelBox ($name, $fieldName, $prefix= '-', $selected= '', $addEmptyOption=false, $key=0, $extra= '')

    if (!$location->isNew()) {
        // location date
        if ($permModifyDate) {
            $formDateTime = new XoopsFormDateTime (_AM_XADDRESSES_LOC_DATE, 'loc_date', 15, $location->getVar('loc_date'), true);
        } else {
            $formatedDate = formatTimeStamp($location->getVar('loc_date'), _DATESTRING);
            $formDateTime = new XoopsFormLabel (_AM_XADDRESSES_LOC_DATE, $formatedDate, '');
        }
        $formDateTime->setDescription(_AM_XADDRESSES_LOC_DATE_DESC);
        $form->addElement($formDateTime, true);
        // location submitter
        if ($permModifySubmitter) {
            $formSubmitter = new XoopsFormSelectUser (_AM_XADDRESSES_LOC_SUBMITTER, 'loc_submitter', true, $location->getVar('loc_submitter'), 1, false);
        } else {
            $submitter =& $memberHandler->getUser($location->getVar('loc_submitter'));
            //$submitterLink = xoops_getLinkedUnameFromId($submitter->getVar('uid')); // DEPRECATED
            $locations[$i]['loc_submitter_linkeduname'] = XoopsUserUtility::getUnameFromId($submitter->getVar('uid'), false, true);
            $formSubmitter = new XoopsFormLabel (_AM_XADDRESSES_LOC_SUBMITTER, $submitterLink, '');
        }
        $formSubmitter->setDescription(_AM_XADDRESSES_LOC_SUBMITTER_DESC);
        $form->addElement($formSubmitter, true);
    }

    
    // Get extra fields categories
    $fieldsCategoriesArray = array();
    $fieldsCategoriesArray[0] = array(
        'cat_id' => 0,
        'cat_title' => _AM_XADDRESSES_FIELD_CATEGORY_NONE,
        'cat_description' => _AM_XADDRESSES_FIELD_CATEGORY_NONE_DESC,
        'cat_weight' => 0);
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $fieldsCategories = $fieldCategoryHandler->getall($criteria, null, false, true); //get fieldscategories as array
    $fieldsCategoriesArray = array_merge($fieldsCategoriesArray, $fieldsCategories);

    // Get all extra fields
    $fields = $locationHandler->loadFields();
    if (count($fields) > 0) {
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
    }

    if ($formIsNew) {
    $form->addElement(new XoopsFormHidden('loc_id', $location->getVar('loc_id')));
    $form->addElement(new XoopsFormHidden('op', 'save_location'));
        // Submit button
            $button_tray = new XoopsFormElementTray(_AM_XADDRESSES_ACTION, '' ,'');
            $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
            $button_tray->addElement(new XoopsFormButton('', 'reset', _RESET, 'reset'));
                $cancel_button = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
                $cancel_button->setExtra("onclick='javascript:history.back();'");
            $button_tray->addElement($cancel_button);
        $form->addElement($button_tray);
    }
    return $form;
}







/**
 * Get {@link XoopsThemeForm} for suggesting a location correction/modification
 *
 * @param object $location {@link XaddressesLocation} to edit
 * @param mixed $action URL to submit to - or false for $_SERVER['REQUEST_URI']
 * @param object $form {@link XoopsThemeForm} object or null
 *
 * @return object
 */
function xaddresses_getModifyForm(&$location, $action = false, &$form = null)
{
    // Module classes/handlers
    $categoryHandler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
    $locationHandler =& xoops_getModuleHandler('location', 'xaddresses');
    $fieldCategoryHandler =& xoops_getmodulehandler('fieldcategory', 'xaddresses');
    $fieldHandler =& xoops_getmodulehandler('field', 'xaddresses');
    xoops_load('formgooglemap', 'xaddresses');
    // Xoops classes/handlers
    $memberHandler =& xoops_gethandler('member');
    $groupPermHandler =& xoops_gethandler('groupperm');

    if ($action === false) {
        $action = $_SERVER['REQUEST_URI'];
    }
    include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
    if (!isset($form) || empty($form) || get_class($form) != 'XoopsThemeForm') {
        $formIsNew = true;
        $formTitle = _MA_XADDRESSES_LOC_MODIFY_SUGGESTMODIFY;
        $form = new XoopsThemeForm($formTitle, 'modify_form', $action, 'post', true);
    } else {
        $formIsNew = false;
    }
    $form->setExtra('enctype="multipart/form-data"');
    
    // Get user groups
    if (is_object($GLOBALS['xoopsUser'])) {
        $groups = $GLOBALS['xoopsUser']->getGroups();
    } else {
    	$groups = XOOPS_GROUP_ANONYMOUS;
    }
    // Get ids of categories in which locations can be viewed/edited/submitted
    $viewableCategoriesIds = $groupPermHandler->getItemIds('in_category_view', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
    $editableCategoriesIds = $groupPermHandler->getItemIds('in_category_edit', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
    $submitableCategoriesIds = $groupPermHandler->getItemIds('in_category_submit', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
    // Get ids of fields that can be edited
    $editableFields = $groupPermHandler->getItemIds('field_edit', $groups, $GLOBALS['xoopsModule']->getVar('mid') );
    // Get other permissions
    $permModifySubmitter = ($groupPermHandler->checkRight('others', 1, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;
    $permModifyDate = ($groupPermHandler->checkRight('others', 2, $groups, $GLOBALS['xoopsModule']->getVar('mid'))) ? true : false ;


    // location title
    $formLocTitle_element_tray = new XoopsFormElementTray(_AM_XADDRESSES_LOC_TITLE, '<br />');
    $formLocTitle_element_tray->setDescription("pippo" . _AM_XADDRESSES_LOC_TITLE_DESC);
    $formLocTitle_element_tray->setExtra("class='suggest'");
    //$formLocTitle = new XoopsFormText('', 'loc_title', 35, 255, $location->getVar('loc_title'));
        //$formLocTitle->setExtra("readonly='readonly'");
        //$formLocTitle->setExtra("disabled='disabled'");
        $formLocTitle = new XoopsFormLabel('', $location->getVar('loc_title'), 'loc_title');
    $formLocTitle_element_tray->addElement($formLocTitle);
        $formLocTitleModify = new XoopsFormText('', 'loc_title_modify', 35, 255, $location->getVar('loc_title'));
    $formLocTitle_element_tray->addElement($formLocTitleModify);
    $form->addElement($formLocTitle_element_tray, true);
/*
    $formLocDescriptiontext1 = new XoopsFormLabel('', $location->getVar('loc_title'), 'loc_title');
    $formLocDescriptiontext1->setDescription(_AM_XADDRESSES_LOC_TITLE_DESC);
    $formLocDescriptiontext1->setClass('suggest');
    $form->addElement($formLocDescriptiontext1, true);
    $formLocDescriptiontext2 = new XoopsFormText('', 'loc_title_modify', 35, 255, $location->getVar('loc_title'));
    $formLocDescriptiontext2->setDescription("2" . _AM_XADDRESSES_LOC_TITLE_DESC);
    $form->addElement($formLocDescriptiontext2, true);
*/

    // location coordinates
    $value = array(
        'lat'=>$location->getVar('loc_lat'), 
        'lng'=>$location->getVar('loc_lng'), 
        'elevation'=>$location->getVar('loc_elevation'), 
        'zoom'=>$location->getVar('loc_zoom')
        );
    $formGoogleMap_element_tray = new XoopsFormElementTray(_AM_XADDRESSES_LOC_COORDINATES, '<br />');
    $formGoogleMap_element_tray->setDescription(_AM_XADDRESSES_LOC_COORDINATES_DESC);
        $formGoogleMap = new FormGoogleMap('', 'loc_googlemap', $value);
        $formGoogleMap->setConfig(array('readonly' => true));
    $formGoogleMap_element_tray->addElement($formGoogleMap);
        $formGoogleMapModify = new FormGoogleMap('', 'loc_googlemap_modify', $value);
        $formGoogleMapModify->setClass('suggest');
    $formGoogleMap_element_tray->addElement($formGoogleMapModify);
    $form->addElement($formGoogleMap_element_tray);


    // location category
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $criteria->add(new Criteria('cat_id', ' (' . implode(',', $editableCategoriesIds) . ')', 'IN'));
    $criteria->setOrder('ASC');
    $categoriesArray = $categoryHandler->getall($criteria);
    $categoriesTree = new XoopsObjectTree($categoriesArray, 'cat_id', 'cat_pid');
        $categoryId = $location->getVar('loc_cat_id');

    $formLocCategory_element_tray = new XoopsFormElementTray(_AM_XADDRESSES_LOC_CAT, '<br />');
    $formLocCategory_element_tray->setDescription(_AM_XADDRESSES_LOC_CAT_DESC);
            $category = $categoryHandler->get($location->getVar('loc_cat_id'));
            //$formLocCategory = new XoopsFormText('', 'loc_title', 35, 255, $category->getVar('cat_title'));
            //$formLocCategory->setExtra("readonly='readonly'");
            //$formLocCategory->setExtra("disabled='disabled'");
            $formLocCategory = new XoopsFormLabel('', $category->getVar('cat_title'), 'loc_cat_id');
        $formLocCategory_element_tray->addElement($formLocCategory, true);
            $formLocCategoryModify = new XoopsFormLabel('', $categoriesTree->makeSelBox('loc_cat_id_modify', 'cat_title', '--', $categoryId, true));
        $formLocCategory_element_tray->addElement($formLocCategoryModify, true);
    $form->addElement($formLocCategory_element_tray);
    
    
    // Get extra fields categories
    $fieldsCategoriesArray = array();
    $fieldsCategoriesArray[0] = array(
        'cat_id' => 0,
        'cat_title' => _AM_XADDRESSES_FIELD_CATEGORY_NONE,
        'cat_description' => _AM_XADDRESSES_FIELD_CATEGORY_NONE_DESC,
        'cat_weight' => 0);
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $fieldsCategories = $fieldCategoryHandler->getall($criteria, null, false, true); //get fieldscategories as array
    $fieldsCategoriesArray = array_merge($fieldsCategoriesArray, $fieldsCategories);

    // Get all extra fields
    $fields = $locationHandler->loadFields();
    if (count($fields) > 0) {
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
            $element['output'] = $field->getOutputValue($location);
            $element['field'] = $field;
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
                    $formField_element_tray = new XoopsFormElementTray($element['field']->getVar('field_title'), '<br />');
                    $formField_element_tray->setDescription($element['field']->getVar('field_description'));
                    $formField_element_tray->addElement(new XoopsFormLabel('', $element['output']));
                    $element['element']->setCaption('');
                    $element['element']->setDescription('');
                    $element['element']->setName($element['element']->getName() . '_modify');
                    $formField_element_tray->addElement($element['element'], $element['required']);
                    $form->addElement($formField_element_tray);
                    unset($formField_element_tray);
                }
            }
            unset($elementsByCategory);
        }
    }
 
    if ($formIsNew) {
        $form->addElement(new XoopsFormHidden('loc_id', $location->getVar('loc_id')));
        $form->addElement(new XoopsFormHidden('op', 'save_modify'));
        // Submit button		
            $button_tray = new XoopsFormElementTray(_AM_XADDRESSES_ACTION, '' ,'');
            $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
            $button_tray->addElement(new XoopsFormButton('', 'reset', _RESET, 'reset'));
                $cancel_button = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
                $cancel_button->setExtra("onclick='javascript:history.back();'");
            $button_tray->addElement($cancel_button);
        $form->addElement($button_tray);
    }
    return $form;
}
?>