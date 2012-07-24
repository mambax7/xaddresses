<?php
defined('XOOPS_ROOT_PATH') or die('Restricted access');
xoops_loadLanguage('formfilemanager', 'xaddresses');

function checkModule($dirname) {
    $moduleHandler =& xoops_gethandler('module');
    $module =& $moduleHandler->getByDirname($dirname);
    if (is_dir(XOOPS_ROOT_PATH . '/modules/' . $dirname)) {
        if ((is_object($module)) && ($module instanceof XoopsModule)) {
            if ($module->isactive()) {
                return round($module->getVar('version') / 100, 2);
            } else {
                return _AM_XADDRESSES_IMPORT_MOD_NOTACTIVE;
            }
        } else {
            return _AM_XADDRESSES_IMPORT_MOD_NOTINSTALLED;
        }
    } else {
        return _AM_XADDRESSES_IMPORT_MOD_NOTPRESENT;
    }
}

class FormFileManager extends XoopsFormElementTray
{
    var $_caption;
    var $_name;
    var $_value;
    /**
     * FormFileManager::FormFileManager()
     *
     * @param mixed $caption
     * @param mixed $name
     * @param string $value 
     */
    function __construct($caption, $name, $value = NULL)
    {
        global $xoopsModuleConfig;
        $this->setCaption($caption);
        $this->setName($name);
        $this->_caption = $caption;
        $this->_name = $name;
        $this->_value = $value;

            $element_text = new XoopsFormText(_FORMFILEMANAGER_FILEURL, $this->_name, 70, 255, $this->_value);
        $this->addElement($element_text);

        if (($xoopsModuleConfig['useajaxfilemanager'] == 1) && (is_dir(XOOPS_ROOT_PATH . '/modules/ajaxfilemanager')) && checkModule('ajaxfilemanager')){
                $filemanagerbutton = new XoopsFormButton ('', $this->_name . 'button', _FORMFILEMANAGER_FILEMANAGER, "button");
                $filemanagerbutton->setExtra ("onclick='openWithSelfMain(&quot;" . XOOPS_URL . "/modules/ajaxfilemanager/ajaxfilemanager/ajaxfilemanager.php?editor=form&config=ajaxfilemanager&amp;view=thumbnail&amp;language=" . "en" . "&amp;elementId=" . $this->_name . "&quot;,&quot;filemanager&quot;,800,430);'");
            $this->addElement($filemanagerbutton);
        }
    }

    function FormFileManager($caption, $name, $value = NULL)
    {
        $this->__construct($caption, $name, $value);
    }
}
?>