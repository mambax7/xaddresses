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
    }

    function XaddressesLocationcategory()
    {
        $this->__construct();
    }

    /**
    * Get {@link XoopsThemeForm} for adding/editing categories
    *
    * @param mixed $action URL to submit to or false for $_SERVER['REQUEST_URI']
    *
    * @return object
    */
    function getForm($action = false)
    {
        global $xoopsModuleConfig;
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $form_title = $this->isNew() ? _XADDRESSES_AM_CAT_ADD : _XADDRESSES_AM_CAT_EDIT;

        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');

        $form = new XoopsThemeForm($form_title, 'locationcategoryform', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $form->addElement(new XoopsFormText(_XADDRESSES_AM_CAT_TITLE, 'cat_title', 35, 255, $this->getVar('cat_title')));
        if (!$this->isNew()) {
            //Load groups
            $form->addElement(new XoopsFormHidden('cat_id', $this->getVar('cat_id')));
            $form->addElement(new XoopsFormHidden('cat_modified', true));
        }

        //description
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

        //image
        $form->addElement(new FormXoopsImage (_XADDRESSES_AM_CAT_IMG, 'cat_imgurl', $this->getVar('cat_imgurl'))); // custom form class

        //parent category
        $xaddressescat_Handler =& xoops_getModuleHandler('locationcategory', 'xaddresses');
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
		$xaddressescat_arr = $xaddressescat_Handler->getall($criteria);
		$mytree = new XoopsObjectTree($xaddressescat_arr, 'cat_id', 'cat_pid');
		$form->addElement(new XoopsFormLabel(_XADDRESSES_AM_CAT_PARENT, $mytree->makeSelBox('cat_pid', 'cat_title','--',$this->getVar('cat_pid'), true)));

        //weight
        $form->addElement(new XoopsFormText(_XADDRESSES_AM_CAT_WEIGHT, 'cat_weight', 35, 35, $this->getVar('cat_weight', 'e')));

        $form->addElement(new XoopsFormHidden('op', 'save_locationcategory') );

        // Submit button		
            $button_tray = new XoopsFormElementTray(_XADDRESSES_AM_ACTION, '' ,'');
            $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
            $button_tray->addElement(new XoopsFormButton('', 'reset', _RESET, 'reset'));
                $cancel_button = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
                $cancel_button->setExtra("onclick='javascript:history.back();'");
            $button_tray->addElement($cancel_button);
        $form->addElement($button_tray);
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