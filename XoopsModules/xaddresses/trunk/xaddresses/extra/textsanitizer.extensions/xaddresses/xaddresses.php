<?php
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class MytsXaddresses extends MyTextSanitizerExtension
{
    function encode($textarea_id)
    {
        $code = "<img src='" . XOOPS_URL . "/modules/xaddresses/images/map_button.gif' alt='" . _XOOPS_FORM_ALTMAP . "'  onclick='xoopsCodeMap(\"{$textarea_id}\",\"" . htmlspecialchars(_XOOPS_FORM_ENTERMAP, ENT_QUOTES) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $code.= "<img src='" . XOOPS_URL . "/modules/xaddresses/images/map_button.gif' alt='" . _XOOPS_FORM_ALTLOC . "'  onclick='xoopsCodeLoc(\"{$textarea_id}\",\"" . htmlspecialchars(_XOOPS_FORM_ENTERLOC, ENT_QUOTES) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $javascript = <<<EOF
            function xoopsCodeMap(id, enterMapPhrase)
            {
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                } else {
                    var text = prompt(enterMapPhrase, "");
                }
                var domobj = xoopsGetElementById(id);
                if ( text.length > 0 ) {
                    var result = "[map]" + text + "[/map]";
                    xoopsInsertText(domobj, result);
                }
                domobj.focus();
            }
            function xoopsCodeLoc(id, enterLocPhrase)
            {
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                } else {
                    var text = prompt(enterLocPhrase, "");
                }
                var domobj = xoopsGetElementById(id);
                if ( text.length > 0 ) {
                    var result = "[loc]" + text + "[/loc]";
                    xoopsInsertText(domobj, result);
                }
                domobj.focus();
            }
EOF;

        return array(
            $code ,
            $javascript);
    }

    function load(&$ts)
    {
        $ts->patterns[] = "/\[map\](.*?)\[\/map\]/es";
        $ts->replacements[] = __CLASS__ . "::decodemap( '\\1' )";

        $ts->patterns[] = "/\[mapcat\](.*?)\[\/mapcat\]/es";
        $ts->replacements[] = __CLASS__ . "::decodemapcat( '\\1' )";


        $ts->patterns[] = "/\[loc](.*)\[\/loc\]/sU";
        $ts->replacements[] = 'LOCATION \\1';
        $ts->patterns[] = "/\[location](.*)\[\/location\]/sU";
        $ts->replacements[] = 'LOCATION \\1';

        return true;
    }

    function decodemap($id)
    {
        $rp = "MAP {$id}";
        return $rp;
    }
    function decodemapcat($id)
    {
        $rp = "CATEGORY {$id}";
        return $rp;
    }
}
?>