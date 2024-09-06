<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
if (!isset($Room) || ($Room == ""))
 {
  $Room = ChatConstDefaultChatRoom;
 }

if (!ChatFillConnectedUser_Vars ($Nick, $SID,$Room))
 {
  $ChatErrorText = "Неверный запрос";
  include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
  exit;
 }

$RealNick = ChatGetRealNick($Nick);

if      ($RealNick == "")
 {
  $ChatErrorText = "Ошибка определения контекста ника";
  include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
  exit;
 }

if ($User_Session_ChatRoom_Name == "")
 {
  // Юзер находится в режиме настроек, а вызывает запрос обновления чата
  $ChatErrorText = "Неверный порядок запроса";
  include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
  exit;
 }

include(dirname(__FILE__)."/"."../tpl/cfsin.htp.php");
ChatClientMessStreamJavaScriptSetMode(True);

if (defined("ChatDelayRegInMessage") || defined("ChatDelayLogInMessage"))
 {
  // Нужна проверка на режим сеанса для выдачи сообщения
  if ($User_Session_Flags == ChatConstSessionFlagPreparing)
   {
    // Сеанс еще не активирован
    if      (($User_LoginsCount  > 1) && defined("ChatDelayLogInMessage"))
     {
      // Логинов больше одного - считаем это логином
      ChatLogInMessSend($Room,$User_Gender,$User_Color,$RealNick);
     }
    else if (($User_LoginsCount == 1) && defined("ChatDelayRegInMessage"))
     {
      // Первый логин. Похоже это была регистрация
      ChatRegInMessSend($Room,$User_Gender,$User_Color,$RealNick);
     }

    ChatUpdateSessionFlags($SID,ChatConstSessionFlagActive);
   }
 }

$query  =  
"select User_Color".
" from User".
" where User.User_NickName = '".addslashes($RealNick)."'";

$QueryColor = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
$QueryColor = mysql_fetch_array($QueryColor);

if ($QueryColor)
 {
  $U_Color = $QueryColor[0];
 }
else
 {
  $U_Color = ""; // Default to client
 }

if ($MessPvt == ChatConstClientMessPvtSignPrivate)
 {
  $M_Model = ChatConstMessModelPrivate; // Приватное сообщение
 }
else
 {
  $M_Model = ChatConstMessModelChat; // Обычное сообщение
 }

include_once(dirname(__FILE__)."/"."../inc/chatstopword.inc.php");

if (($M_Model == ChatConstMessModelPrivate) && 
    ($User_AdminFlag > 0) && 
    ($MessText != "") &&
    ($MessText{0} == ChatMessTextAdminCommandPrefixChar))
 {
  include_once(dirname(__FILE__)."/"."../inc/support.inc.php");

  ChatAdminZoneCommand 
   ($User_AdminFlag,$RealNick,$MessTo,$MessText,$Room);
 }
else
 {
  if (!ChatStopWordProtectNickLogInOk($RealNick,$Room))
   {
    $M_Model = ChatConstMessModelPrivate; // Принудительный приват
   }

  if      (!ChatFloodProtectSendOk($RealNick))
   {
    // Flood
    ChatFloodProtectOverLimitAction($RealNick,$SID,$Room,$MessTo);
   }
  else if (!ChatStopWordProtectSendOk($RealNick,$Room,$MessTo,$MessText,$M_Model))
   {
    // StopWord
    ChatStopWordProtectAction($RealNick,$SID,$Room,$MessTo);
   }
  else
   {
    ChatOneMessSend
     ($Room,$MessText,$U_Color,$RealNick,$MessTo,$M_Model);
   }
 }

ChatGetM_All($RealNick,$SID,$Room,$MID,true);
ChatGetUF();

ChatClientMessStreamJavaScriptSetMode(False);
include(dirname(__FILE__)."/"."../tpl/cfsout.htp.php");
?>
