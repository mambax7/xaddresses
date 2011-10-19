<?php
defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

class XaddressesModify extends XoopsObject
{
// constructor
    function __construct()
    {
        $this->initVar('suggest_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('loc_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('suggest_loc_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('suggest_description', XOBJ_DTYPE_TXTAREA);
        $this->initVar('suggest_sender', XOBJ_DTYPE_INT, null, true);
        $this->initVar('suggest_ip', XOBJ_DTYPE_TXTBOX);
        $this->initVar('suggest_date', XOBJ_DTYPE_INT, 0);
    }

    function XaddressesModify()
    {
        $this->__construct();
    }

    /**
    * Get {@link XoopsThemeForm} for suggesting modifications
    *
    * @param int   $loc_id  location id
    * @param mixed $action  URL to submit to or false for $_SERVER['REQUEST_URI']
    *
    * @return object
    */
    function getForm($loc_id, $action = false)
    {
        global $xoopsModuleConfig;
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $title = $this->isNew() ? _XADDRESSES_AM_LOC_MODIFY_NEW : _XADDRESSES_AM_LOC_MODIFY_EDIT;

        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');

        $form = new XoopsThemeForm($title, 'modifyform', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        //description
        $editor_configs = array();
        $editor_configs['name'] = 'suggest_description';
        $editor_configs['value'] = $this->getVar('suggest_description', 'e');
        $editor_configs['rows'] = 20;
        $editor_configs['cols'] = 160;
        $editor_configs['width'] = '100%';
        $editor_configs['height'] = '400px';
        $editor_configs['editor'] = $xoopsModuleConfig['editor'];
        $form->addElement( new XoopsFormEditor(_XADDRESSES_AM_LOC_MODIFY_DESCRIPTION, 'suggest_description', $editor_configs), false);

        $form->addElement(new XoopsFormCaptcha(), true);        

        $form->addElement(new XoopsFormHidden('loc_id', $loc_id));
        $form->addElement(new XoopsFormHidden('op', 'save_modify'));

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



class XaddressesModifyHandler extends XoopsPersistableObjectHandler
{
    function XaddressesModifyHandler(&$db)
    {
        $this->__construct($db);
    }

    function __construct(&$db)
    {
        parent::__construct($db, "xaddresses_modify", "xaddressesmodify", "suggest_id");
    }
}
?>