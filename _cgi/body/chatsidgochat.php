<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
include_once(dirname(__FILE__)."/".'../inc/funcvalidate.inc.php');

$Room = "";

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

if (!ChatFillConnectedUser_Vars ($NickSrc, $SID,$Room))
 {
  $ChatErrorText = "Неверный запрос";
  include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
  exit;
 }

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

// Nick name is now $Nick
$RealNick = ChatGetRealNick($Nick); // Refresh data
ChatDropSession($RealNick, $SID);

$Room = ChatConstDefaultChatRoom;

$SID = ChatGetRoomSID($RealNick,$Room);

if ($SID == "") // Not logged yet
 {
  if (defined("ChatDelayLogInMessage"))
   {
    $SessionFlags = ChatConstSessionFlagPreparing;
   }
  else
   {
    $SessionFlags = ChatConstSessionFlagActive;
   }

  $SID = ChatCreateNewSID();
  ChatEnterToRoom($RealNick,$SID,$Room,$Color,$Topic,$SessionFlags);
  ChatFillConnectedUser_Vars($RealNick,$SID,$Room);
  ChatUpdateUserInfoByLogin($RealNick);

  if ($Room != "")
   {
    if (defined("ChatDelayLogInMessage"))
     {
      // We will send it on first Update/Reload
     }
    else
     {
      ChatLogInMessSend($Room,$User_Gender,$User_Color,$RealNick);
     }
   }
 }
else
 {
  // Just keep connected
  ChatUpdateUserInRoom($RealNick,$SID,$Room,$Color,$Topic);
  ChatFillConnectedUser_Vars($RealNick,$SID,$Room);
 }

if (defined("ChatFastBootLogInAction"))
 {
  // FAST BOOT!
  $ChatFastBootFunctionBodyStr = ChatMakeFastBootStr($RealNick,$SID,$Room);
 }
else
 {
  $ChatFastBootFunctionBodyStr = "";
 }

//Boot using supplied ClientMode
if ($ClientMode == 2)
 {
  include(dirname(__FILE__)."/"."../tpl/cc_bod2.htp.php");
 }
else
 {
  include(dirname(__FILE__)."/"."../tpl/cc_bod.htp.php");
 }
?>
