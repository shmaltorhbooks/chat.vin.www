<?
// Success login in setup mode
include_once(dirname(__FILE__)."/"."cformio.php");

global $ChatErrorText;       $ChatErrorText = ""; // Normal flow.Must be empty!

global $TR_Color;
$TR_DrawColor = $TR_Color;
if ($TR_DrawColor == "")
 {
  $TR_DrawColor = $User_Color; // if no color in login form, use from DB
 }

global $ChatErrorText;       FORM_IOPutVar('ChatErrorText',$ChatErrorText);
global $User_Nick;           FORM_IOPutVar('Nick',$User_Nick);
global $User_Nick;           FORM_IOPutVar('NickSrc',$User_Nick);
global $User_Gender;         FORM_IOPutVar('Gender',$User_Gender);
global $User_EMail;          FORM_IOPutVar('EMail',$User_EMail);
                             FORM_IOPutVar('Color',$TR_DrawColor);
global $TR_Topic;            FORM_IOPutVar('Topic',$TR_Topic);
global $User_SelfNotes;      FORM_IOPutVar('SelfNotes',$User_SelfNotes);

global $SID;                 FORM_IOPutVar('SID',$SID);

include(dirname(__FILE__)."/"."ss_set.htp.php");
?>
