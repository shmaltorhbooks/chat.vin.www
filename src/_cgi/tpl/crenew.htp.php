<?
// Failed to register nick
include_once(dirname(__FILE__)."/"."cformio.php");

global $ChatErrorText;       FORM_IOPutVar('ChatErrorText',$ChatErrorText);
global $TR_Nick;             FORM_IOPutVar('Nick',$TR_Nick);
global $TR_Gender;           FORM_IOPutVar('Gender',$TR_Gender);
global $TR_EMail;            FORM_IOPutVar('EMail',$TR_EMail);
global $TR_Color;            FORM_IOPutVar('Color',$TR_Color);
global $TR_Topic;            FORM_IOPutVar('Topic',$TR_Topic);
global $TR_SelfNotes;        FORM_IOPutVar('SelfNotes',$TR_SelfNotes);

include(dirname(__FILE__)."/"."srenew.htp.php");
?>
