<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
include_once(dirname(__FILE__)."/".'../inc/funcvalidate.inc.php');

if (!ChatStrNickStrValid($Nick))
 {
  $ChatErrorText = "Используются запрещенные символы в нике";
  include(dirname(__FILE__)."/"."../tpl/cseset.htp.php");
  exit;
 }

if (!ChatStrNickStrValid($NickSrc))
 {
  $ChatErrorText = "Используются запрещенные символы в нике";
  include(dirname(__FILE__)."/"."../tpl/cseset.htp.php");
  exit;
 }

if (strcmp($Password,$PasswordAdd) != 0)
 {
  $ChatErrorText = "Значения полей ввода пароля не совпадают";
  include(dirname(__FILE__)."/"."../tpl/cseset.htp.php");
  exit;
 }

if (!ChatNickEqual($NickSrc,$Nick))
 {
  $ChatErrorText = "Изменения в НИКЕ не совпадают по начертанию. Вы можете заменить НИК только на сходный по начертанию";
  include(dirname(__FILE__)."/"."../tpl/cseset.htp.php");
  exit;
 }

if (strlen(ChatNickToVisual($Nick)) == 0)
 {
  $ChatErrorText = "Пустой или незначащий НИК не допустим";
  include(dirname(__FILE__)."/"."../tpl/cseset.htp.php");
  exit;
 }

if (($EMail != "") && (!ChatCheckStrValidEMail($EMail)))
 {
  $ChatErrorText = "Недопустимый EMail адрес";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (($Color == "") || (!ChatCheckStrValidHTMLColor($Color)))
 {
  $ChatErrorText = "Недопустимый цвет";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (!ChatCheckStrValidGender($Gender))
 {
  $ChatErrorText = "Недопустимый пол";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

$Room = "";

if (!ChatFillConnectedUser_Vars($NickSrc,$SID,$Room))
 {
  $ChatErrorText = "Неверный запрос";
  include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
  exit;
 }

//ChatDropSession($NickSrc, $SID);

if (!ChatUserUpdate($NickSrc,$Nick,$Password,$Color,$EMail,$Gender,$SelfNotes))
 {
  $ChatErrorText = "Ошибка обновления данных пользователя";
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
    $ChatErrorText = "Модуль интеграции:".$ErrorMess;
    include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
    exit;
   }
 }

ChatFillConnectedUser_Vars($Nick,$SID,$Room); // Refresh data
include(dirname(__FILE__)."/"."../tpl/csmset.htp.php");
?>
