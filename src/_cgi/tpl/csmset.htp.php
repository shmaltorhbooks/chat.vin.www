<?
// Successfull user setup update
include_once(dirname(__FILE__)."/"."cformio.php");

global $ChatErrorText;       $ChatErrorText = ""; // Normal flow.Must be empty!
global $ChatMessText;

if ((!isset($ChatMessText)) || ($ChatMessText == ""))
 {
  $ChatMessText = "OK";
 }

global $ChatErrorText;       FORM_IOPutVar('ChatErrorText',$ChatErrorText);
global $ChatMessText;        FORM_IOPutVar('ChatMessText',$ChatMessText);
global $User_Nick;           FORM_IOPutVar('Nick',$User_Nick);
global $User_Nick;           FORM_IOPutVar('NickSrc',$User_Nick);
global $User_Gender;         FORM_IOPutVar('Gender',$User_Gender);
global $User_EMail;          FORM_IOPutVar('EMail',$User_EMail);
global $User_Color;          FORM_IOPutVar('Color',$User_Color);
global $TR_Topic;            FORM_IOPutVar('Topic',$TR_Topic);
global $User_SelfNotes;      FORM_IOPutVar('SelfNotes',$User_SelfNotes);

global $SID;                 FORM_IOPutVar('SID',$SID);

include(dirname(__FILE__)."/"."ssmset.htp.php");
?>
