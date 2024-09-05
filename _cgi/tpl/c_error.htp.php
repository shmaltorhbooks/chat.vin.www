<?
// Error message
include_once(dirname(__FILE__)."/"."cformio.php");

global $ChatErrorText;       FORM_IOPutVar('ChatErrorText',$ChatErrorText);

include(dirname(__FILE__)."/"."s_error.htp.php");
?>