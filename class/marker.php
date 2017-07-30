<?php
defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");
xoops_load('formxoopsimage', 'xaddresses'); // load custom form class


class XaddressesMarker extends XoopsObject
{
// constructor
	function __construct()
	{
		$this->XoopsObject();
        $this->initVar('marker_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('marker_title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('marker_description', XOBJ_DTYPE_TXTAREA);
        $this->initVar('marker_image',XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('marker_shadow',XOBJ_DTYPE_TXTBOX, null, false);
	}
	function XaddressesMarker()
    {
        $this->__construct();
    }
    
    function getForm($marker_id, $action = false)
    {
		global $xoopsDB, $xoopsModuleConfig;
		if ($action === false) {
			$action = $_SERVER['REQUEST_URI'];
		}
        $title = $this->isNew() ? sprintf(_AM_XADDRESSES_ADD, _AM_XADDRESSES_MARKER) : sprintf(_AM_XADDRESSES_EDIT, _AM_XADDRESSES_MARKER);
        $form = new XoopsThemeForm($title, 'marker', $action, 'post');
        $form->addElement(new XoopsFormText(_AM_XADDRESSES_MARKER_TITLE, 'marker_title', 35, 255, $this->getVar('marker_title')));

        //description
        $form->addElement(new XoopsFormTextArea(_AM_XADDRESSES_MARKER_DESCRIPTION, 'marker_description', $this->getVar('marker_description', 'e')));

        //image
        $form->addElement(new FormXoopsImage(_AM_XADDRESSES_MARKER_IMAGE, 'marker_image', $this->getVar('marker_image'))); // custom form class

        //shadow
        $form->addElement(new FormXoopsImage(_AM_XADDRESSES_MARKER_SHADOW, 'marker_shadow', $this->getVar('marker_shadow'))); // custom form class

        
        $form->addElement(new XoopsFormCaptcha(), true);        
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormHidden('marker_id', $marker_id));
        // Submit button		
        $button_tray = new XoopsFormElementTray('' ,'');
        $button_tray->addElement(new XoopsFormButton('', 'post', _MD_XADDRESSES_VOTE_RATE, 'submit'));
        $form->addElement($button_tray);
    	return $form;
    }
}

class XaddressesMarkerHandler extends XoopsPersistableObjectHandler
{
	function __construct(&$db) 
	{
		parent::__construct($db, "xaddresses_marker", 'xaddressesmarker', 'marker_id', 'marker_title');
    }
}
?>