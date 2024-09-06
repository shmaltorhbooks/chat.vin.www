<?
// Try login non-existent nick
// (must be shown registraction form with prompt to regin new nick)
include_once(dirname(__FILE__)."/"."cformio.php");

global $ChatErrorText;       $ChatErrorText = ""; // Normal flow.Must be empty!

global $ChatErrorText;       FORM_IOPutVar('ChatErrorText',$ChatErrorText);
global $TR_Nick;             FORM_IOPutVar('Nick',$TR_Nick);
global $TR_Color;            FORM_IOPutVar('Color',$TR_Color);
global $TR_Topic;            FORM_IOPutVar('Topic',$TR_Topic);
// Other var will keep default values ("")

include(dirname(__FILE__)."/"."sr_new.htp.php");
?>
