<?php
defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");
xoops_load ('formfilemanager', 'xaddresses'); // load custom form class
xoops_load ('formmultiplefilemanager', 'xaddresses'); // load custom form class
xoops_load ('formxoopsimage', 'xaddresses'); // load custom form class
xoops_load ('formmultiplexoopsimage', 'xaddresses'); // load custom form class
xoops_load ('formkmleditor', 'xaddresses'); // load custom form class

class XaddressesField extends XoopsObject
{
    function __construct()
    {
        $this->initVar('field_id', XOBJ_DTYPE_INT, null);
        $this->initVar('cat_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('field_type', XOBJ_DTYPE_TXTBOX);
        $this->initVar('field_valuetype', XOBJ_DTYPE_INT, null, true);
        $this->initVar('field_name', XOBJ_DTYPE_TXTBOX, null, true);
        $this->initVar('field_title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('field_description', XOBJ_DTYPE_TXTAREA);
        $this->initVar('field_required', XOBJ_DTYPE_INT, 0); // 0 = no, 1 = yes
        $this->initVar('field_length', XOBJ_DTYPE_INT, 0);
        $this->initVar('field_maxlength', XOBJ_DTYPE_INT, 0);
        $this->initVar('field_weight', XOBJ_DTYPE_INT, 0);
        $this->initVar('field_default', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('field_notnull', XOBJ_DTYPE_INT, 1);
        $this->initVar('field_edit', XOBJ_DTYPE_INT, 0);
        $this->initVar('field_show', XOBJ_DTYPE_INT, 0);
        $this->initVar('field_config', XOBJ_DTYPE_INT, 0);
        $this->initVar('field_options', XOBJ_DTYPE_ARRAY, array() );
        $this->initVar('field_extras', XOBJ_DTYPE_ARRAY, array() );
    }

    function XaddressesField()
    {
        $this->__construct();
    }

    /**
     * Extra treatment dealing with non latin encoding
     * Tricky solution
     */
    function setVar($key, $value, $not_gpc = false)
    {error_log(print_r($value),true);
        if ($key == 'field_options' && is_array($value)) {
            foreach (array_keys($value) as $idx) {
                $value[$idx] = base64_encode($value[$idx]);
            }
        }
        parent::setVar($key, $value, $not_gpc);
    }

    function getVar($key, $format = 's')
    {
        $value = parent::getVar($key, $format);
        if ($key == 'field_options' && !empty($value)) {
            foreach (array_keys($value) as $idx) {
                $value[$idx] = base64_decode($value[$idx]);
            }
        }
        return $value;
    }

    /**
    * Returns a {@link XoopsFormElement} for editing the value of this field
    *
    * @param XaddressesLocation $location {@link XaddressesLocation} object to edit the value of
    *
    * @return XoopsFormElement
    **/
    function getEditElement($location)
    {
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');

        $value = $location->getVar($this->getVar('field_name'), 'e');
        if (is_null($value)) {
            $value = $this->getVar('field_default');
        }
        $caption = $this->getVar('field_title');
        $caption = defined($caption) ? constant($caption) : $caption;
        $name = $this->getVar('field_name', 'e');
        $options = $this->getVar('field_options');
        if (is_array($options)) {
            //asort($options);
            foreach (array_keys($options) as $key) {
                $optval = defined($options[$key]) ? constant($options[$key]) : $options[$key];
                $optkey = defined($key) ? constant($key) : $key;
                unset($options[$key]);
                $options[$optkey] = $optval;
            }
        }
        $extras = $this->getVar('field_extras');
        if (is_array($extras)) {
            //asort($extras);
            foreach (array_keys($extras) as $key) {
                $extval = defined($extras[$key]) ? constant($extras[$key]) : $extras[$key];
                $extkey = defined($key) ? constant($key) : $key;
                unset($extras[$key]);
                $extras[$extkey] = $extval;
            }
        }
        switch ($this->getVar('field_type')) {
            default:
            case "autotext":
                //autotext is not for editing
                $element = new XoopsFormLabel($caption, $this->getOutputValue($location));
                break;
            case "textbox":
                $element = new XoopsFormText($caption, $name, $this->getVar('field_length'), $this->getVar('field_maxlength'), $value);
                break;
            case "textarea":
                //$element = new XoopsFormTextArea($caption, $name, $value, 4, 30);
                $element = new XoopsFormTextArea($caption, $name, $value, $extras['rows'], $extras['cols']);
                break;
            case "dhtml":
                //$element = new XoopsFormDhtmlTextArea($caption, $name, $value, 10, 30);
                $element = new XoopsFormDhtmlTextArea($caption, $name, $value, $extras['rows'], $extras['cols']);
                break;
            case "select":
                $element = new XoopsFormSelect($caption, $name, $value);
                // If options do not include an empty element, then add a blank option to prevent any default selection
                if (!in_array('', array_keys($options))) {
                    $element->addOption('', _NONE);

                    $eltmsg = empty($caption) ? sprintf(_FORM_ENTER, $name) : sprintf( _FORM_ENTER, $caption);
                    $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
                    $element->customValidationCode[] = "\nvar hasSelected = false; var selectBox = myform.{$name};" .
                        "for (i = 0; i < selectBox.options.length; i++  ) { if ( selectBox.options[i].selected == true && selectBox.options[i].value != '' ) { hasSelected = true; break; } }" .
                        "if ( !hasSelected ) { window.alert(\"{$eltmsg}\"); selectBox.focus(); return false; }";
                }
                $element->addOptionArray($options);
                break;
            case "select_multi":
                $element = new XoopsFormSelect($caption, $name, $value, 5, true);
                $element->addOptionArray($options);
                break;
            case "radio":
                $element = new XoopsFormRadio($caption, $name, $value);
                $element->addOptionArray($options);
                break;
            case "checkbox":
                $element = new XoopsFormCheckBox($caption, $name, $value);
                $element->addOptionArray($options);
                break;
            case "yesno":
                $element = new XoopsFormRadioYN($caption, $name, $value);
                break;
            case "group":
                $element = new XoopsFormSelectGroup($caption, $name, true, $value);
                break;
            case "group_multi":
                $element = new XoopsFormSelectGroup($caption, $name, true, $value, 5, true);
                break;
            case "language":
                $element = new XoopsFormSelectLang($caption, $name, $value);
                break;
            case "date":
                $element = new XoopsFormTextDateSelect($caption, $name, 15, $value);
                break;
            case "longdate":
                $element = new XoopsFormTextDateSelect($caption, $name, 15, str_replace("-", "/", $value) );
                break;
            case "datetime":
                $element = new XoopsFormDatetime($caption, $name, 15, $value);
                break;
            case "list":
                $element = new XoopsFormSelectList($caption, $name, $value, 1, $options[0]);
                break;
            case "timezone":
                $element = new XoopsFormSelectTimezone($caption, $name, $value);
                $element->setExtra("style='width: 280px;'");
                break;
            case "rank":
                include_once $GLOBALS['xoops']->path('class/xoopslists.php');
                $element = new XoopsFormSelect($caption, $name, $value);
                $ranks = XoopsLists::getUserRankList();
                $element->addOption(0, "--------------");
                $element->addOptionArray($ranks);
                break;
            case 'theme':
                $element = new XoopsFormSelect($caption, $name, $value);
                $element->addOption("0", _XADDRESSES_MA_SITEDEFAULT);
                $handle = opendir(XOOPS_THEME_PATH . '/');
                $dirlist = array();
                while (false !== ($file = readdir($handle))) {
                    if (is_dir(XOOPS_THEME_PATH . '/' . $file) && !preg_match("/^[.]{1,2}$/", $file) && strtolower($file) != 'cvs' ) {
                        if (file_exists(XOOPS_THEME_PATH . "/" . $file . "/theme.html") && in_array($file, $GLOBALS['xoopsConfig']['theme_set_allowed'])) {
                            $dirlist[$file] = $file;
                        }
                    }
                }
                closedir($handle);
                if (!empty($dirlist)) {
                    asort($dirlist);
                    $element->addOptionArray($dirlist);
                }
                break;
            case "file":
                $element = new FormFileManager ($caption, $name, $value); // custom form class
                break;
            case "multiplefile":
                $element = new FormMultipleFileManager ($caption, $name, $value); // custom form class
                break;
            case "image":
                $element = new FormXoopsImage ($caption, $name, $this->getVar('field_length'), $this->getVar('field_maxlength'), $value); // custom form class
                break;
            case "multipleimage":
                $element = new FormMultipleXoopsImage ($caption, $name, $this->getVar('field_length'), $this->getVar('field_maxlength'), $value); // custom form class
                break;
            case "kmlmap":
                $element = new FormKmlEditor ($caption, $name, $value); // custom form class
                break;
        }
        if ($this->getVar('field_description') != "") {
            $element->setDescription($this->getVar('field_description') );
        }
        return $element;
    }

    /**
    * Returns a value for output of this field
    *
    * @param XoopsUser $user {@link XoopsUser} object to get the value of
    * @param profileProfile $location object to get the value of
    *
    * @return mixed
    **/
    function getOutputValue($location)
    {
        if (file_exists($file = $GLOBALS['xoops']->path('modules/xaddresses/language/' . $GLOBALS['xoopsConfig']['language'] . '/modinfo.php'))) {
            include_once $file;
        } else {
            include_once $GLOBALS['xoops']->path('modules/xaddresses/language/english/modinfo.php');
        }

        $value = in_array($this->getVar('field_name'), $this->getLocationVars()) ? $user->getVar($this->getVar('field_name')) : $location->getVar($this->getVar('field_name'));

        switch ($this->getVar('field_type')) {
            default:
            case "textbox":
                if ($this->getVar('field_name') == 'url' && $value != '') {
                    return '<a href="' . formatURL($value) . '" rel="external">' . $value . '</a>';
                } else {
                    return $value;
                }
                break;
            case "textarea":
            case "dhtml":
            case 'theme':
            case "language":
            case "list":
                return $value;
                break;
            case "image":
            case "file":
                return $value;
                break;
            case "multipleimage":
            case "multiplefile":
                return $value;
                break;
            case "kmlmap":
                return $value;
                break;
            case "select":
            case "radio":
                $options = $this->getVar('field_options');
                if (isset($options[$value])) {
                    $value = htmlspecialchars(defined($options[$value]) ? constant($options[$value]) : $options[$value]);
                } else {
                    $value = "";
                }
                return $value;
                break;
            case "select_multi":
            case "checkbox":
                $options = $this->getVar('field_options');
                $ret = array();
                if (count($options) > 0) {
                    foreach (array_keys($options) as $key) {
                        if (in_array($key, $value)) {
                            $ret[$key] = htmlspecialchars( defined($options[$key]) ? constant($options[$key]) : $options[$key]);
                        }
                    }
                }
                return $ret;
                break;
            case "group":
                //change to retrieve groups and return name of group
                return $value;
                break;
            case "group_multi":
                //change to retrieve groups and return array of group names
                return "";
                break;
            case "longdate":
                //return YYYY/MM/DD format - not optimal as it is not using local date format, but how do we do that
                //when we cannot convert it to a UNIX timestamp?
                return str_replace("-", "/", $value);
            case "date":
                return formatTimestamp($value, 's');
                break;
            case "datetime":
                if (!empty($value)) {
                       return formatTimestamp($value, 'm');
                   } else {
                       return $value = _MI_XADDRESSES_NEVER_LOGGED_IN;
                   }
                break;
/*
            case "autotext":
                $value = $user->getVar($this->getVar('field_name'), 'n'); //autotext can have HTML in it
                $value = str_replace("{X_UID}", $user->getVar("uid"), $value);
                $value = str_replace("{X_URL}", XOOPS_URL, $value );
                $value = str_replace("{X_UNAME}", $user->getVar("uname"), $value);
                return $value;
                break;

            case "rank":
                $userrank = $user->rank();
                $user_rankimage = "";
                if (isset($userrank['image']) && $userrank['image'] != "") {
                    $user_rankimage = '<img src="'.XOOPS_UPLOAD_URL . '/' . $userrank['image'] . '" alt="' . $userrank['title'] . '" /><br />';
                }
                return $user_rankimage.$userrank['title'];
                break;
*/
            case "yesno":
                return $value ? _YES : _NO;
                break;
            case "timezone":
                include_once $GLOBALS['xoops']->path('class/xoopslists.php');
                $timezones = XoopsLists::getTimeZoneList();
                $value = empty($value) ? "0" : strval($value);
                return $timezones[str_replace('.0', '', $value)];
                break;
        }
    }

    /**
    * Returns a value ready to be saved in the database
    *
    * @param mixed $value Value to format
    *
    * @return mixed
    */
    function getValueForSave($value)
    {
        include_once XOOPS_ROOT_PATH . '/class/uploader.php';
        switch ($this->getVar('field_type')) {
            default:
            case "textbox":
            case "textarea":
            case "dhtml":
            case "yesno":
            case "timezone":
            case 'theme':
            case "language":
            case "list":
            case "select":
            case "radio":
            case "select_multi":
            case "checkbox":
            case "group":
            case "group_multi":
            case "longdate":
            case "image":
            case "file":
            case "kmlmap":
                return $value;
            case "multipleimage":
            case "multiplefile":
                $ret = array();
                foreach ($value as $val) {
                    if ($val != '') $ret[] = $val;
                }
                return $ret; // IN_PROGRESS
            case "date":
                if ($value != "") {
                    return strtotime($value);
                }
                return $value;
                break;
            case "datetime":
                if (!empty($value)) {
                    return strtotime($value['date']) + intval($value['time']);
                }
                return $value;
                break;
        }
    }

    /**
     * Get names of user variables
     *
     * @return array
     */
    function getLocationVars()
    {
        $locationHandler = xoops_getmodulehandler('location', 'xaddresses');
        return $locationHandler->getLocationVars();
    }
}



class XaddressesFieldHandler extends XoopsPersistableObjectHandler
{
    function XaddressesFieldHandler(&$db)
    {
        $this->__construct($db);
    }

    function __construct(&$db)
    {
        parent::__construct($db, 'xaddresses_field', "xaddressesfield", "field_id", 'field_title');
    }

    /**
    * Read field information from cached storage
    *
    * @param bool   $force_update   read fields from database and not cached storage
    *
    * @return array
    */
    function loadFields($force_update = false)
    {
        static $fields = array();
        if (!empty($force_update) || count($fields) == 0) {
            $this->table_link = $this->db->prefix('xaddresses_locationcategory');
            $criteria = new Criteria('o.field_id', 0, "!=");
            $criteria->setSort('l.cat_weight ASC, o.field_weight');
            $field_objs = $this->getByLink($criteria, array('o.*'), true, 'cat_id', 'cat_id');
            foreach (array_keys($field_objs) as $i ) {
                $fields[$field_objs[$i]->getVar('field_name')] = $field_objs[$i];
            }
        }
        return $fields;
    }

    /**
    * save an location field in the database
    *
    * @param object $field reference to the object
    * @param bool $force whether to force the query execution despite security settings
    * @param bool $checkObject check if the object is dirty and clean the attributes
    * @return bool FALSE if failed, TRUE if already present and unchanged or successful
    */
    function insert(&$field, $force = false)
    {
        $locationHandler =& xoops_getmodulehandler('location', 'xaddresses');
        $field->setVar('field_name', str_replace(' ', '_', $field->getVar('field_name')));
        $field->cleanVars();
        $defaultstring = "";
        switch ($field->getVar('field_type')) {
            case "datetime":
            case "date":
                $field->setVar('field_valuetype', XOBJ_DTYPE_INT);
                $field->setVar('field_maxlength', 10);
                break;
            case "longdate":
                $field->setVar('field_valuetype', XOBJ_DTYPE_MTIME);
                break;
            case "yesno":
                $field->setVar('field_valuetype', XOBJ_DTYPE_INT);
                $field->setVar('field_maxlength', 1);
                break;
            case "textbox":
                if ($field->getVar('field_valuetype') != XOBJ_DTYPE_INT) {
                    $field->setVar('field_valuetype', XOBJ_DTYPE_TXTBOX);
                }
                break;
            case "autotext":
                if ($field->getVar('field_valuetype') != XOBJ_DTYPE_INT) {
                    $field->setVar('field_valuetype', XOBJ_DTYPE_TXTAREA);
                }
                break;
            case "group_multi":
            case "select_multi":
            case "checkbox":
                $field->setVar('field_valuetype', XOBJ_DTYPE_ARRAY);
                break;
            case "language":
            case "timezone":
            case "theme":
                $field->setVar('field_valuetype', XOBJ_DTYPE_TXTBOX);
                break;
            case "image":
                $field->setVar('field_valuetype', XOBJ_DTYPE_TXTBOX);
                break;
            case "file":
                $field->setVar('field_valuetype', XOBJ_DTYPE_TXTBOX);
                break;
// IN_PROGRESS
            case "multipleimage":
                $field->setVar('field_valuetype', XOBJ_DTYPE_ARRAY);
                break;
            case "multiplefile":
                $field->setVar('field_valuetype', XOBJ_DTYPE_ARRAY);
                break;
// IN_PROGRESS
            case "dhtml":
            case "textarea":
                $field->setVar('field_valuetype', XOBJ_DTYPE_TXTAREA);
                break;
            case "kmlmap":
                $field->setVar('field_valuetype', XOBJ_DTYPE_TXTAREA);
                break;
        }

        if ($field->getVar('field_valuetype') == "") {
            $field->setVar('field_valuetype', XOBJ_DTYPE_TXTBOX);
        }

        if (!in_array($field->getVar('field_name'), $this->getLocationVars())) {
            if ($field->isNew()) {
                //add column to table
                $changetype = "ADD";
            } else {
                //update column information
                $changetype = "CHANGE `" . $field->getVar('field_name', 'n') . "`";
            }
            $maxlengthstring = $field->getVar('field_maxlength') > 0 ? "(" . $field->getVar('field_maxlength') . ")" : "";
            $lengthstring = $field->getVar('field_length') > 0 ? "(" . $field->getVar('field_length') . ")" : "";
            $notnullstring = " NOT NULL";
            //set type
            switch ($field->getVar('field_valuetype')) {
                default:
                case XOBJ_DTYPE_ARRAY:
                case XOBJ_DTYPE_UNICODE_ARRAY:
                    $type = "mediumtext";
                    $maxlengthstring = "";
                    $field->setVar('field_maxlength', null);
                    break;
                case XOBJ_DTYPE_UNICODE_EMAIL:
                case XOBJ_DTYPE_UNICODE_TXTBOX:
                case XOBJ_DTYPE_UNICODE_URL:
                case XOBJ_DTYPE_EMAIL:
                case XOBJ_DTYPE_TXTBOX:
                case XOBJ_DTYPE_URL:
                    $type = "varchar";
                    // varchars must have a length
                    if (!$lengthstring) {
                        //so set it to max if length is not set - or should it fail?
                        $lengthstring = "(40)";
                        $field->setVar('field_length', 40);
                    }
                    // varchars must have a maxlength
                    if (!$maxlengthstring) {
                        //so set it to max if maxlength is not set - or should it fail?
                        $maxlengthstring = "(255)";
                        $field->setVar('field_maxlength', 255);
                    }
                    //if ( $field->getVar('field_default')  ) {
                        $defaultstring = " DEFAULT " . $this->db->quote($field->cleanVars['field_default']);
                    //}
                    break;
                case XOBJ_DTYPE_INT:
                    $type = "int";
                    if ($field->getVar('field_default') || $field->getVar('field_default') !== '') {
                        $defaultstring = " DEFAULT '" . intval($field->cleanVars['field_default']) . "'";
                        $field->setVar('field_default', intval($field->cleanVars['field_default']));
                    }
                    break;
                case XOBJ_DTYPE_DECIMAL:
                    $type = "decimal(14,6)";
                    if ($field->getVar('field_default') || $field->getVar('field_default') !== '') {
                        $defaultstring = " DEFAULT '" . doubleval($field->cleanVars['field_default']) . "'";
                        $field->setVar('field_default', doubleval($field->cleanVars['field_default']));
                    }
                    break;
                case XOBJ_DTYPE_FLOAT:
                    $type = "float(15,9)";
                    if ($field->getVar('field_default') || $field->getVar('field_default') !== '') {
                        $defaultstring = " DEFAULT '" . floatval($field->cleanVars['field_default']) . "'";
                        $field->setVar('field_default', floatval($field->cleanVars['field_default']));
                    }
                    break;
                case XOBJ_DTYPE_OTHER:
                case XOBJ_DTYPE_UNICODE_TXTAREA:
                case XOBJ_DTYPE_TXTAREA:
                    $type = "text";
                    $maxlengthstring = "";
                    $field->setVar('field_maxlength', null);
                    $notnullstring = "";
                    break;
                case XOBJ_DTYPE_MTIME:
                    $type = "date";
                    $maxlengthstring = "";
                    $field->setVar('field_maxlength', null);
                    break;
            }

            $sql = "ALTER TABLE `" . $locationHandler->table . "`";
            $sql.= " " . $changetype;
            $sql.= " " . "`" . $field->cleanVars['field_name'] . "`";
            $sql.= " " . $type . $maxlengthstring . $notnullstring . $defaultstring;
            if (!$this->db->query($sql)) {
                return false;
            }
        }

        //change this to also update the cached field information storage
        $field->setDirty();
        if (!parent::insert($field, $force)) {
            return false;
        }
        return $field->getVar('field_id');

    }

    /**
    * delete a location field from the database
    *
    * @param object $field reference to the object to delete
    * @param bool $force
    * @return bool FALSE if failed.
    **/
    function delete(&$field, $force = false)
    {
        $locationHandler =& xoops_getmodulehandler('location', 'xaddresses');
        // remove column from table
        $sql = "ALTER TABLE " . $locationHandler->table;
        $sql.= " DROP `" . $field->getVar('field_name', 'n') . "`";
        if ($this->db->query($sql)) {
            //change this to update the cached field information storage
            if (!parent::delete($field, $force)) {
                return false;
            }

            if ($field->getVar('field_show') || $field->getVar('field_edit')) {
                $moduleHandler =& xoops_gethandler('module');
                $module = $moduleHandler->getByDirname('xaddresses');
                if (is_object($module)) {
                    // Remove group permissions
                    $groupPermHandler =& xoops_gethandler('groupperm');
                    $criteria = new CriteriaCompo(new Criteria('gperm_modid', $module->getVar('mid')));
                    $criteria->add(new Criteria('gperm_itemid', $field->getVar('field_id')));
                    return $groupPermHandler->deleteAll($criteria);
                }
            }
        }
        return false;
    }

    /**
     * Get array of standard variable names (xaddresses_addresses)
     *
     * @return array
     */
    function getLocationVars()
    {
        return array('loc_id', 'loc_cat_id', 'loc_title', 'loc_lat', 'loc_lng', 'loc_zoom', 'loc_submitter', 'loc_status', 'loc_date', 'loc_comments', 'loc_rating', 'loc_votes');
    }
}
?>