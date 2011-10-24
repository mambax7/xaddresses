<?php
defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");
xoops_load('formxoopsimage', 'xaddresses'); // load custom form class


class XaddressesLocationcategory extends XoopsObject
{
    function __construct()
    {
        $this->initVar('cat_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('cat_pid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cat_title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('cat_description', XOBJ_DTYPE_TXTAREA);
        $this->initVar('cat_dohtml', XOBJ_DTYPE_INT, 1, false); // For html form
        $this->initVar('cat_imgurl',XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cat_weight', XOBJ_DTYPE_INT);
        $this->initVar('cat_map_type', XOBJ_DTYPE_TXTBOX);
    }

    function XaddressesLocationcategory()
    {
        $this->__construct();
    }

    /**
    * Get {@link XoopsThemeForm} for adding/editing categories
    *
    * @param mixed $action URL to submit to - or false for $_SERVER['REQUEST_URI']
    * @param object $form {@link XoopsThemeForm} object or null
    *
    * @return object
    */
    function getForm($action = false, &$form = null)
    {
        global $xoopsModuleConfig;
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }

        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        if (!isset($form) || empty($form) || get_class($form) != 'XoopsThemeForm') {
            $formIsNew = true;
            $form_title = $this->isNew() ? _XADDRESSES_AM_CAT_ADD : _XADDRESSES_AM_CAT_EDIT;
            $form = new XoopsThemeForm($form_title, 'locationcategoryform', $action, 'post', true);
        } else {
            $formIsNew = false;
        }

        $form->setExtra('enctype="multipart/form-data"');

        $form->addElement(new XoopsFormText(_XADDRESSES_AM_CAT_TITLE, 'cat_title', 35, 255, $this->getVar('cat_title')), true);
        if (!$this->isNew()) {
            //Load groups
            $form->addElement(new XoopsFormHidden('cat_id', $this->getVar('cat_id')));
            $form->addElement(new XoopsFormHidden('cat_modified', true));
        }

        // Description
        $editor_configs = array();
        $editor_configs['name'] = 'cat_description';
        $editor_configs['value'] = $this->getVar('cat_description', 'e');
        $editor_configs['rows'] = 20;
        $editor_configs['cols'] = 160;
        $editor_configs['width'] = '100%';
        $editor_configs['height'] = '400px';
        $editor_configs['editor'] = $xoopsModuleConfig['editor'];
        $form->addElement( new XoopsFormEditor(_XADDRESSES_AM_CAT_DESCRIPTION, 'cat_description', $editor_configs), false);
        //$form->addElement(new XoopsFormTextArea(_XADDRESSES_AM_DESCRIPTION, 'cat_description', $this->getVar('cat_description', 'e')));

        // Image
        $form->addElement(new FormXoopsImage (_XADDRESSES_AM_CAT_IMG, 'cat_imgurl', 40, 255, $this->getVar('cat_imgurl'))); // custom form class

        // Parent category
        $xaddressescat_Handler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
		$xaddressescat_arr = $xaddressescat_Handler->getall($criteria);
		$mytree = new XoopsObjectTree($xaddressescat_arr, 'cat_id', 'cat_pid');
		$form->addElement(new XoopsFormLabel(_XADDRESSES_AM_CAT_PARENT, $mytree->makeSelBox('cat_pid', 'cat_title','--',$this->getVar('cat_pid'), true)));

        // Weight
        $form->addElement(new XoopsFormText(_XADDRESSES_AM_CAT_WEIGHT, 'cat_weight', 35, 35, $this->getVar('cat_weight', 'e')), true);

        // Map Setting
        $form->addElement(new XoopsFormLabel (_XADDRESSES_AM_CAT_MAP_SETTING, _XADDRESSES_AM_CAT_MAP_SETTING, ''));
        // Maptype
            $select_map_type = new XoopsFormSelect (_XADDRESSES_AM_CAT_MAP_TYPE, 'cat_map_type', $this->getVar('cat_map_type'), 1, false);
            $select_map_type->addOption('ROADMAP', 'ROADMAP');
            $select_map_type->addOption('SATELLITE', 'SATELLITE');
            $select_map_type->addOption('HYBRID', 'HYBRID');
            $select_map_type->addOption('TERRAIN', 'TERRAIN');
            $select_map_type->setDescription(_XADDRESSES_AM_CAT_MAP_TYPE_DESC);
        $form->addElement($select_map_type);

        // Permissions
        $form->addElement(new XoopsFormLabel (_XADDRESSES_AM_CAT_PERMISSIONS, _XADDRESSES_AM_CAT_PERMISSIONS, ''));
 
        $memberHandler = & xoops_gethandler('member');
        $groupPermHandler =& xoops_gethandler('groupperm');

        if ($this->isNew()) {
            $groupsViewIds = null;
            $groupsSubmitIds = null;
            $gropusEditIds = null;
            $groupsDeleteIds = null;
        } else {
            $groupsViewIds = $groupPermHandler->getGroupIds ('in_category_view', $this->getVar('cat_id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $groupsSubmitIds = $groupPermHandler->getGroupIds ('in_category_submit', $this->getVar('cat_id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $groupsEditIds = $groupPermHandler->getGroupIds ('in_category_edit', $this->getVar('cat_id'), $GLOBALS['xoopsModule']->getVar('mid'));
            $groupsDeleteIds = $groupPermHandler->getGroupIds ('in_category_delete', $this->getVar('cat_id'), $GLOBALS['xoopsModule']->getVar('mid'));
        }

        $formSelectGroupMaxLines = 5; // Max lines in groups select
        $anonymous = true;
        $countGroups = count($memberHandler->getGroupList());
        $FormSelectGroupLines = ($countGroups < $formSelectGroupMaxLines) ? $countGroups : $formSelectGroupMaxLines;
        $form->setExtra('enctype="multipart/form-data"');
        // XoopsFormSelectGroup ($caption, $name, $include_anon=false, $value=null, $size=1, $multiple=false)
            $groups_can_view_select = new XoopsFormSelectGroup(_XADDRESSES_AM_PERM_VIEW, 'in_category_view', $anonymous, $groupsViewIds, $FormSelectGroupLines, true);
            $groups_can_view_select->setDescription(_XADDRESSES_AM_PERM_VIEW_DESC);
        $form->addElement($groups_can_view_select);
            $groups_can_submit_select = new XoopsFormSelectGroup(_XADDRESSES_AM_PERM_SUBMIT, 'in_category_submit', $anonymous, $groupsSubmitIds, $FormSelectGroupLines, true);
            $groups_can_submit_select->setDescription(_XADDRESSES_AM_PERM_SUBMIT_DESC);
        $form->addElement($groups_can_submit_select);
            $groups_can_edit_select = new XoopsFormSelectGroup(_XADDRESSES_AM_PERM_EDIT, 'in_category_edit', $anonymous, $groupsEditIds, $FormSelectGroupLines, true);
            $groups_can_edit_select->setDescription(_XADDRESSES_AM_PERM_EDIT_DESC);
        $form->addElement($groups_can_edit_select);
            $groups_can_delete_select = new XoopsFormSelectGroup(_XADDRESSES_AM_PERM_DELETE, 'in_category_delete', $anonymous, $groupsDeleteIds, $FormSelectGroupLines, true);
            $groups_can_delete_select->setDescription(_XADDRESSES_AM_PERM_DELETE_DESC);
        $form->addElement($groups_can_delete_select);
        

        /*
        $permissions_element_try = new XoopsFormElementTray (_XADDRESSES_AM_CAT_PERMISSIONS,  "<br />", "");
        $permissions_element_try->addElement($groups_can_view_select);
        $permissions_element_try->addElement($groups_can_submit_select);
        $permissions_element_try->addElement($groups_can_edit_select);
        $permissions_element_try->addElement($groups_can_delete_select);

        $form->addElement($permissions_element_try);
*/



        if ($formIsNew) {
            // Captcha
            xoops_load('xoopscaptcha');
            $form->addElement(new XoopsFormCaptcha(), true);
            // Hidden Fields
            $form->addElement(new XoopsFormHidden('op', 'save_locationcategory') );
            // Submit button		
                $button_tray = new XoopsFormElementTray(_XADDRESSES_AM_ACTION, '' ,'');
                $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
                $button_tray->addElement(new XoopsFormButton('', 'reset', _RESET, 'reset'));
                    $cancel_button = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
                    $cancel_button->setExtra("onclick='javascript:history.back();'");
                $button_tray->addElement($cancel_button);
            $form->addElement($button_tray);
        }
        return $form;
    }
}



class XaddressesLocationcategoryHandler extends XoopsPersistableObjectHandler
{
    function XaddressesLocationcategoryHandler(&$db)
    {
        $this->__construct($db);
    }

    function __construct(&$db)
    {
        parent::__construct($db, "xaddresses_locationcategory", "xaddresseslocationcategory", "cat_id", 'cat_title');
    }
}
?>