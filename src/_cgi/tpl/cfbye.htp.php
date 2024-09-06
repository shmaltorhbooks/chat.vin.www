<?
// Chat leave (with error message)
include_once(dirname(__FILE__)."/"."cformio.php");

global $ChatErrorText;       FORM_IOPutVar('ChatErrorText',$ChatErrorText);
global $User_Nick;           FORM_IOPutVar('Nick',$User_Nick);
global $User_Color;          FORM_IOPutVar('Color',$User_Color);

include(dirname(__FILE__)."/"."sfbye.htp.php");
?>
