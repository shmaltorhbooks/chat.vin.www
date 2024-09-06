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

if ($MID == 0)
 {
  // *** Работаем как Update ***
  // Загрузка списка пользователей
  ChatGetNLFull($RealNick,ChatConstDefaultChatRoom);
  // загрузка всех сообщений за последние N мин и все приватных
  ChatGetM_All($RealNick, $SID, ChatConstDefaultChatRoom, 0, false);
  // Получение идентификатора последнего сообщения в базе
  ChatGetUI();
 }
else
 {
  // загрузка всех сообщений за последние >$MID
  ChatGetM_All($RealNick,$SID,ChatConstDefaultChatRoom,$MID,true);
  ChatGetUI();
 }

ChatClientMessStreamJavaScriptSetMode(False);
include(dirname(__FILE__)."/"."../tpl/cfsout.htp.php");
?>
