<?php
defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

class XaddressesBroken extends XoopsObject
{
// constructor
    function __construct()
    {
        $this->initVar('report_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('loc_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('report_description', XOBJ_DTYPE_TXTAREA);
        $this->initVar('report_sender', XOBJ_DTYPE_INT, null, true);
        $this->initVar('report_ip', XOBJ_DTYPE_TXTBOX);
        $this->initVar('report_date', XOBJ_DTYPE_INT, 0);
    }

    function XaddressesBroken()
    {
        $this->__construct();
    }

    /**
    * Get {@link XoopsThemeForm} for adding/editing categories
    *
    * @param int   $loc_id  location id
    * @param mixed $action URL to submit to - or false for $_SERVER['REQUEST_URI']
    * @param object $form {@link XoopsThemeForm} object or null
    *
    * @return object
    */
    function getForm($loc_id, $action = false, &$form = null)
    {
        global $xoopsModuleConfig;
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        if (!isset($form) || empty($form) || get_class($form) != 'XoopsThemeForm') {
            $formIsNew = true;
            $title = $this->isNew() ? _AM_XADDRESSES_LOC_BROKEN_NEW : _AM_XADDRESSES_LOC_BROKEN_EDIT;
            $form = new XoopsThemeForm($title, 'brokenform', $action, 'post', true);
        } else {
            $formIsNew = false;
        }

        $form->setExtra('enctype="multipart/form-data"');

        //description
        $editor_configs = array();
        $editor_configs['name'] = 'report_description';
        $editor_configs['value'] = $this->getVar('report_description', 'e');
        $editor_configs['rows'] = 20;
        $editor_configs['cols'] = 160;
        $editor_configs['width'] = '100%';
        $editor_configs['height'] = '400px';
        $editor_configs['editor'] = $xoopsModuleConfig['editor'];
            $brokenDescriptionTextarea = new XoopsFormEditor(_AM_XADDRESSES_LOC_BROKEN_DESCRIPTION, 'report_description', $editor_configs);
            $brokenDescriptionTextarea->setDescription(_AM_XADDRESSES_LOC_BROKEN_DESCRIPTION_DESC);
        $form->addElement($brokenDescriptionTextarea, false);

        $form->addElement(new XoopsFormCaptcha(), true);        

        if ($formIsNew) {    
            // Captcha
            xoops_load('xoopscaptcha');
            $form->addElement(new XoopsFormCaptcha(), true);
            // Hidden Fields
            $form->addElement(new XoopsFormHidden('loc_id', $loc_id));
            $form->addElement(new XoopsFormHidden('op', 'save_broken'));
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
}



class XaddressesBrokenHandler extends XoopsPersistableObjectHandler
{
    function XaddressesBrokenHandler(&$db)
    {
        $this->__construct($db);
    }

    function __construct(&$db)
    {
        parent::__construct($db, "xaddresses_broken", "xaddressesbroken", "report_id");
    }
}
?>