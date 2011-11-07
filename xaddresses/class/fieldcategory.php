<?php
defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

class XaddressesFieldcategory extends XoopsObject
{
// constructor
    function __construct()
    {
        $this->initVar('cat_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('cat_title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('cat_description', XOBJ_DTYPE_TXTAREA);
        $this->initVar('cat_weight', XOBJ_DTYPE_INT);
    }

    function XaddressesFieldcategory()
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
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        if (!isset($form) || empty($form) || get_class($form) != 'XoopsThemeForm') {
            $formIsNew = true;
            $title = $this->isNew() ? _XADDRESSES_AM_FIELDCAT_NEW : _XADDRESSES_AM_FIELDCAT_EDIT;
            $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        } else {
            $formIsNew = false;
        }
        $form->addElement(new XoopsFormText(_XADDRESSES_AM_FIELDCAT_TITLE, 'cat_title', 35, 255, $this->getVar('cat_title')));
        if (!$this->isNew()) {
            //Load groups
            $form->addElement(new XoopsFormHidden('id', $this->getVar('cat_id')));
        }
        $form->addElement(new XoopsFormTextArea(_XADDRESSES_AM_FIELDCAT_DESCRIPTION, 'cat_description', $this->getVar('cat_description', 'e')));
        $form->addElement(new XoopsFormText(_XADDRESSES_AM_FIELDCAT_WEIGHT, 'cat_weight', 35, 35, $this->getVar('cat_weight', 'e')));

        if ($formIsNew) {
            // Captcha
            xoops_load('xoopscaptcha');
            $form->addElement(new XoopsFormCaptcha(), true);
            // Hidden Fields
            $form->addElement(new XoopsFormHidden('op', 'save_fieldcategory') );
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



class XaddressesFieldcategoryHandler extends XoopsPersistableObjectHandler
{
    function XaddressesFieldcategoryHandler(&$db)
    {
        $this->__construct($db);
    }

    function __construct(&$db)
    {
        parent::__construct($db, "xaddresses_fieldcategory", "xaddressesfieldcategory", "cat_id", 'cat_title');
    }
}
?>