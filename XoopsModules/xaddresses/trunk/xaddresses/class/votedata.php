<?php
defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");


class XaddressesVotedata extends XoopsObject
{
// constructor
    function __construct()
    {
        $this->XoopsObject();
        $this->initVar("rating_id",XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("loc_id",XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("rating_user",XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("rating",XOBJ_DTYPE_OTHER,null,false,3);
        $this->initVar("rating_hostname",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("rating_timestamp",XOBJ_DTYPE_INT,null,false,10);
    }
    function XaddressesVotedata()
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
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        if (!isset($form) || empty($form) || get_class($form) != 'XoopsThemeForm') {
            $formIsNew = true;
            $title = $this->isNew() ? _AM_XADDRESSES_LOC_RATE_NEW : _AM_XADDRESSES_LOC_RATE_EDIT;
            $form = new XoopsThemeForm($title, 'rateform', $action, 'post');
        } else {
            $formIsNew = false;
        }

        $form->setExtra('enctype="multipart/form-data"');

            // vote
            $options = array(
                '11' => '--', 
                '10' => '10', 
                '9'  => '9', 
                '8'  => '8', 
                '7'  => '7', 
                '6'  => '6', 
                '5'  => '5', 
                '4'  => '4', 
                '3'  => '3', 
                '2'  => '2', 
                '1'  => '1', 
                '0'  => '0');
            $rating_select = new XoopsFormSelect(_AM_XADDRESSES_LOC_RATE_VOTE, 'rating');
            $rating_select->addOptionArray($options);
        $form->addElement($rating_select, true);

        $form->addElement(new XoopsFormCaptcha(), true);        

        $form->addElement(new XoopsFormHidden('loc_id', $loc_id));
        $form->addElement(new XoopsFormHidden('op', 'save_vote'));

        if ($formIsNew) {
            // Captcha
            xoops_load('xoopscaptcha');
            $form->addElement(new XoopsFormCaptcha(), true);
            // Hidden Fields
            $form->addElement(new XoopsFormHidden('loc_id', $loc_id));
            $form->addElement(new XoopsFormHidden('op', 'save_vote'));
            // Submit button        
                $button_tray = new XoopsFormElementTray(_AM_XADDRESSES_ACTION, '' ,'');
                $button_tray->addElement(new XoopsFormButton('', 'submit', _AM_XADDRESSES_LOC_RATE_RATE, 'submit'));
                $button_tray->addElement(new XoopsFormButton('', 'reset', _RESET, 'reset'));
                    $cancel_button = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
                    $cancel_button->setExtra("onclick='javascript:history.back();'");
                $button_tray->addElement($cancel_button);
            $form->addElement($button_tray);
        }
        return $form;
    }
}

class XaddressesVotedataHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "xaddresses_votedata", 'xaddressesvotedata', 'rating_id', 'loc_id');
    }
}
?>