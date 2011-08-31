<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
class Xaddresses_modfielddata extends XoopsObject
{
// constructor
	function __construct()
	{
		$this->XoopsObject();
		$this->initVar("modiddata",XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("fid",XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("loc_id",XOBJ_DTYPE_INT,null,false,11);
		$this->initVar("moddata",XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
	}
	function Xaddresses_modfielddata()
    {
        $this->__construct();
    }
}

class XaddressesXaddresses_modfielddataHandler extends XoopsPersistableObjectHandler
{
	function __construct(&$db) 
	{
		parent::__construct($db, "xaddresses_modfielddata", 'Xaddresses_modfielddata', 'modiddata', 'moddata');
    }
}
?>