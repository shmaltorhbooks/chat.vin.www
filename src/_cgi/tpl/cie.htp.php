<?
// Login error (for chat or setup)
include_once(dirname(__FILE__)."/"."cformio.php");

global $ChatErrorText;       FORM_IOPutVar('ChatErrorText',$ChatErrorText);
global $TR_Nick;             FORM_IOPutVar('Nick',$TR_Nick);
global $TR_Color;            FORM_IOPutVar('Color',$TR_Color);
global $TR_Topic;            FORM_IOPutVar('Topic',$TR_Topic);

include(dirname(__FILE__)."/"."sie.htp.php");
?>
