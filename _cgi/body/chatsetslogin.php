<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
if (!ChatStrNickStrValid($Nick))
 {
  $Nick = trim(ChatNickPurgeSignToSpace($Nick));

  if (!ChatStrNickStrValid($Nick))
   {
    $ChatErrorText = "Используются запрещенные символы в нике";
    include(dirname(__FILE__)."/"."../tpl/cie.htp.php");
    exit;
   }
 }

if      (!ChatNickExists($Nick))
 {
  // пользователя в базе нет такого
  $ChatErrorText = "Такой НИК не зарегистрирован";
  include(dirname(__FILE__)."/"."../tpl/cr_new.htp.php");
  exit;
 }

if (!ChatPasswordValid ($Nick,$Password))
 {
  ChatErrorLog("Invalid password to [$Nick] supplied");
  $ChatErrorText = "Неверный пароль";
  include(dirname(__FILE__)."/"."../tpl/cie.htp.php");
  exit;
 }

$RealNick = ChatGetRealNick($Nick);

if      ($RealNick == "")
 {
  $ChatErrorText = "Ошибка определения контекста ника";
  include(dirname(__FILE__)."/"."../tpl/cie.htp.php");
  exit;
 }

if (!ChatStrNickStrValid($RealNick))
 {
  $ChatErrorText = "Используются запрещенные символы в нике";
  include(dirname(__FILE__)."/"."../tpl/cie.htp.php");
  exit;
 }

if (!ChatUserIsAdmin($RealNick))
 {
  if (ChatUserLogInLockedByNick($RealNick))
   {
    $ChatErrorText = "Вход в чат с этого ника временно закрыт";
    include(dirname(__FILE__)."/"."../tpl/cie.htp.php");
    exit;
   }

  if (ChatUserLogInLockedByIP())
   {
    $ChatErrorText = "Вход в чат с этого IP адреса временно закрыт";
    include(dirname(__FILE__)."/"."../tpl/cie.htp.php");
    exit;
   }
 }

// All done - go west!

$Room = "";

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
 }
else
 {
  // Just keep connected
  ChatUpdateUserInRoom($RealNick,$SID,$Room,$Color,$Topic);
  ChatFillConnectedUser_Vars($RealNick,$SID,$Room);
 }

include(dirname(__FILE__)."/"."../tpl/cs_set.htp.php");
?>
