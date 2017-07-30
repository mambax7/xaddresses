<?php
defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

class XaddressesLocation extends XoopsObject
{
// constructor
    function __construct($fields)
    {
        $this->initVar('loc_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('loc_cat_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('loc_title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('loc_lat', XOBJ_DTYPE_DECIMAL);
        $this->initVar('loc_lng', XOBJ_DTYPE_DECIMAL);
        $this->initVar('loc_elevation', XOBJ_DTYPE_DECIMAL);
        $this->initVar('loc_zoom', XOBJ_DTYPE_INT);
        $this->initVar('loc_submitter', XOBJ_DTYPE_INT, null, true); // sumbmitter id
        $this->initVar('loc_date', XOBJ_DTYPE_INT, 0);
        // Flags
        $this->initVar('loc_status', XOBJ_DTYPE_INT, 0); // 0 = location not enabled, 1 = location enabled/ok
        $this->initVar('loc_suggested', XOBJ_DTYPE_INT, 0); // 0 = normal location, 1 = suggested location
        // Infos
        $this->initVar('loc_comments', XOBJ_DTYPE_INT, null, false, 11); // number of comments
		$this->initVar('loc_rating', XOBJ_DTYPE_OTHER, null, false, 10); // rating
        $this->initVar('loc_votes', XOBJ_DTYPE_INT, null, false, 11); // number of votes
        // Extra fields
        $this->init($fields);
    }

    function XaddressesLocation($fields)
    {
        $this->__construct($fields);
    }

    /**
    * Initiate variables
    * @param array $fields field information array of {@link XoopsProfileField} objects
    */
    function init($fields)
    {
        if (is_array($fields) && count($fields) > 0) {
            foreach (array_keys($fields) as $key ) {
                $this->initVar($key, $fields[$key]->getVar('field_valuetype'), $fields[$key]->getVar('field_default', 'n'), $fields[$key]->getVar('field_required'), $fields[$key]->getVar('field_maxlength'));
            }
        }
    }
}



class XaddressesLocationHandler extends XoopsPersistableObjectHandler
{
    /**
    * holds reference to {@link XaddressesFieldHandler} object
    */
    var $_fHandler;

    /**
    * Array of {@link XaddressesField} objects
    *     
    * @var array
    */
    var $_fields = array();

    function XaddressesLocationHandler(&$db)
    {
        $this->__construct($db);
    }

    function __construct(&$db)
    {
        parent::__construct($db, 'xaddresses_location', 'xaddresseslocation', 'loc_id');
        $this->_fHandler = xoops_getmodulehandler('field', 'xaddresses');
    }

    /**
     * create a new {@link XaddressesLocation}
     *      
     * @param bool $isNew Flag the new objects as "new"?
     *      
     * @return object {@link XaddressesLocation}
     */
    function &create($isNew = true)
    {
        $obj = new $this->className($this->loadFields());
        $obj->handler =& $this;
        if ($isNew === true) {
            $obj->setNew();
        }
        return $obj;
    }

    /**
     * Get a {@link XaddressesLocation}
     *      
     * @param   int $loc_id location id
     * @param   boolean $createOnFailure create a new {@link XaddressesLocation} if none is feteched
     *      
     * @return  object {@link XaddressesLocation}
     */
    function &get($loc_id, $createOnFailure = true)
    {
        $obj = parent::get($loc_id);
        if (!is_object($obj) && $createOnFailure) {
            $obj = $this->create();
        }
        return $obj;
    }

    /**
    * Create new {@link XaddressesField} object
    *     
    * @param bool $isNew
    *     
    * @return object
    */
    function &createField($isNew = true)
    {
        $return =& $this->_fHandler->create($isNew);
        return $return;
    }

    /**
    * Load field information
    *
    * @return array of {@link XaddressesField} objects
    */
    function loadFields()
    {
        if (count($this->_fields) == 0) {
            $this->_fields = $this->_fHandler->loadFields();
        }
        return $this->_fields;
    }

    /**
    * Fetch fields
    *
    * @param object $criteria {@link CriteriaElement} object
    * @param bool $id_as_key return array with field IDs as key?
    * @param bool $as_object return array of objects?
    *
    * @return array
    **/
    function getFields($criteria, $id_as_key = true, $as_object = true)
    {
        return $this->_fHandler->getObjects($criteria, $id_as_key, $as_object);
    }

    /**
    * Insert a field in the database
    *
    * @param object $field
    * @param bool $force
    *
    * @return bool
    */
    function insertField(&$field, $force = false)
    {
        return $this->_fHandler->insert($field, $force);
    }

    /**
    * Delete a field from the database
    *
    * @param object $field
    * @param bool $force
    *
    * @return bool
    */
    function deleteField(&$field, $force = false)
    {
        return $this->_fHandler->delete($field, $force);
    }

    /**
    * Save a new field in the database
    *
    * @param array $vars array of variables, taken from $module->loadInfo('profile')['field']
    * @param int $categoryid ID of the category to add it to
    * @param int $type valuetype of the field
    * @param int $moduleid ID of the module, this field belongs to
    * @param int $weight
    *
    * @return string
    **/
    function saveField($vars, $weight = 0)
    {
        $field =& $this->createField();
        $field->setVar('field_name', $vars['name']);
        $field->setVar('field_valuetype', $vars['valuetype']);
        $field->setVar('field_type', $vars['type']);
        $field->setVar('field_weight', $weight);
        if (isset($vars['title'])) {
            $field->setVar('field_title', $vars['title']);
        }
        if (isset($vars['description'])) {
            $field->setVar('field_description', $vars['description']);
        }
        if (isset($vars['required'])) {
            $field->setVar('field_required', $vars['required']); //0 = no, 1 = yes
        }
        if (isset($vars['maxlength'])) {
            $field->setVar('field_maxlength', $vars['maxlength']);
        }
        if (isset($vars['default'])) {
            $field->setVar('field_default', $vars['default']);
        }
        if (isset($vars['notnull'])) {
            $field->setVar('field_notnull', $vars['notnull']);
        }
        if (isset($vars['show'])) {
            $field->setVar('field_show', $vars['show']);
        }
        if (isset($vars['edit'])) {
            $field->setVar('field_edit', $vars['edit']);
        }
        if (isset($vars['config'])) {
            $field->setVar('field_config', $vars['config']);
        }
        if (isset($vars['options'])) {
            $field->setVar('field_options', $vars['options']);
        } else {
            $field->setVar('field_options', array() );
        }
        if ($this->insertField($field)) {
            $msg = '&nbsp;&nbsp;Field <b>' . $vars['name'] . '</b> added to the database';
        } else {
            $msg = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert field <b>' . $vars['name'] . '</b> into the database. '.implode(' ', $field->getErrors()) . $this->db->error() . '</span>';
        }
        unset($field);
        return $msg;
    }

    /**
     * insert a new object in the database
     *
     * @param object $obj reference to the object
     * @param bool $force whether to force the query execution despite security settings
     * @param bool $checkObject check if the object is dirty and clean the attributes
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */

    function insert(&$obj, $force = false, $checkObject = true)
    {
        $locationVars = $this->getLocationVars();
        /*foreach ($locationVars as $var) {
            unset($obj->vars[$var]);
        }
        if (count($obj->vars) == 0) {
            return true;
        }*/
        return parent::insert($obj, $force, $checkObject);
    }


    /**
     * Get array of standard variable names (location table)
     *
     * @return array
     */
    function getLocationVars()
    {
        return $this->_fHandler->getLocationVars();
    }

    /**
     * Search locations
     *
     * @param object    $criteria   CriteriaElement
     * @param array     $searchVars Fields to be fetched
     * @param array     $groups     for Usergroups is selected (only admin!)
     *
     * @return array
     */
    function search($criteria, $searchVars = array(), $groups = null)
    {
        $locationVars = $this->getLocationVars();

        $searchVars_standard = array_intersect($searchVars, $locationVars);
        $searchVars_extra = array_diff($searchVars, $locationVars);
        $sv = array('u.uid, u.uname, u.email, u.user_viewemail');
        if (!empty($searchVars_standard)) {
            $sv[0] .= ",u." . implode(", u.", $searchVars_standard);
        }
        if (!empty($searchVars_extra)) {
            $sv[] = "p." . implode(", p.", $searchVars_extra);
        }

        $sql_select = "SELECT " . (empty($searchVars) ? "u.*, p.*" : implode(", ", $sv));
        $sql_from = " FROM " . $this->db->prefix("users") . " AS u LEFT JOIN " . $this->table . " AS p ON u.uid=p.profile_id" .
                    (empty($groups) ? "" : " LEFT JOIN " . $this->db->prefix("groups_users_link") . " AS g ON u.uid=g.uid");
        $sql_clause = " WHERE 1=1";
        $sql_order = "";

        $limit = $start = 0;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql_clause .= " AND " . $criteria->render();
            if ($criteria->getSort() != '') {
                $sql_order = ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }

        if (!empty($groups)) {
            $sql_clause .= " AND g.groupid IN (" . implode(", ", $groups) . ")";
        }

        $sql_locations = $sql_select . $sql_from . $sql_clause . $sql_order;
        $result = $this->db->query($sql_locations, $limit, $start);

        if (!$result) {
            return array(array(), array(), 0);
        }
        $user_handler = xoops_gethandler('user');
        $locationVars = $this->getLocationVars();
        $users = array();
        $profiles = array();
        while ($myrow = $this->db->fetchArray($result)) {
            $profile = $this->create(false);
            $user = $user_handler->create(false);

            foreach ($myrow as $name => $value) {
                if (in_array($name, $locationVars)) {
                   $user->assignVar($name, $value);
                } else {
                    $profile->assignVar($name, $value);
                }
            }
            $profiles[$myrow['uid']] = $profile;
            $users[$myrow['uid']] = $user;
        }

        $count = count($users);
        if ((!empty($limit) && $count >= $limit) || !empty($start)) {
            $sql_count = "SELECT COUNT(*)" . $sql_from . $sql_clause;
            $result = $this->db->query($sql_count);
            list($count) = $this->db->fetchRow($result);
        }

        return array($users, $profiles, (int)$count);
    }
}
?>