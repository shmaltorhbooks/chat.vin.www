<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
include_once(dirname(__FILE__)."/".'../inc/funcvalidate.inc.php');

if (!ChatStrNickStrValid($Nick))
 {
  $ChatErrorText = "������������ ����������� ������� � ����";
  include(dirname(__FILE__)."/"."../tpl/cseset.htp.php");
  exit;
 }

if (!ChatStrNickStrValid($NickSrc))
 {
  $ChatErrorText = "������������ ����������� ������� � ����";
  include(dirname(__FILE__)."/"."../tpl/cseset.htp.php");
  exit;
 }

if (strcmp($Password,$PasswordAdd) != 0)
 {
  $ChatErrorText = "�������� ����� ����� ������ �� ���������";
  include(dirname(__FILE__)."/"."../tpl/cseset.htp.php");
  exit;
 }

if (!ChatNickEqual($NickSrc,$Nick))
 {
  $ChatErrorText = "��������� � ���� �� ��������� �� ����������. �� ������ �������� ��� ������ �� ������� �� ����������";
  include(dirname(__FILE__)."/"."../tpl/cseset.htp.php");
  exit;
 }

if (strlen(ChatNickToVisual($Nick)) == 0)
 {
  $ChatErrorText = "������ ��� ���������� ��� �� ��������";
  include(dirname(__FILE__)."/"."../tpl/cseset.htp.php");
  exit;
 }

if (($EMail != "") && (!ChatCheckStrValidEMail($EMail)))
 {
  $ChatErrorText = "������������ EMail �����";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (($Color == "") || (!ChatCheckStrValidHTMLColor($Color)))
 {
  $ChatErrorText = "������������ ����";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (!ChatCheckStrValidGender($Gender))
 {
  $ChatErrorText = "������������ ���";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

$Room = "";

if (!ChatFillConnectedUser_Vars($NickSrc,$SID,$Room))
 {
  $ChatErrorText = "�������� ������";
  include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
  exit;
 }

//ChatDropSession($NickSrc, $SID);

if (!ChatUserUpdate($NickSrc,$Nick,$Password,$Color,$EMail,$Gender,$SelfNotes))
 {
  $ChatErrorText = "������ ���������� ������ ������������";
  include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
  exit;
 }
else
 {
  if (!ExtFromChatUserUpdate
        ($ErrorMess,
         $NickSrc,
         $Nick,
         $Password,
          null,
         $EMail))
   {
    ChatServerLogWriteWarning("Error calling ext_link(UserUpdate):".$ErrorMess);
    $ChatErrorText = "������ ����������:".$ErrorMess;
    include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
    exit;
   }
 }

ChatFillConnectedUser_Vars($Nick,$SID,$Room); // Refresh data
include(dirname(__FILE__)."/"."../tpl/csmset.htp.php");
?>
