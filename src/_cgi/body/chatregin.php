<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
include_once(dirname(__FILE__)."/".'../inc/funcvalidate.inc.php');

if (!isset($Room))
 {
  $Room = ChatConstDefaultChatRoom;
 }

if (ChatUserRegInLockedByIP())
 {
  $ChatErrorText = "Регистрация НОВЫХ ников с вашего адреса временно блокирована";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (!ChatStrNickStrValid($Nick))
 {
  $ChatErrorText = "Используются запрещенные символы в нике";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (strcmp($Password,$PasswordAdd) != 0)
 {
  $ChatErrorText = "Значения полей ввода пароля не совпадают";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (strlen(ChatNickToVisual($Nick)) == 0)
 {
  $ChatErrorText = "Пустой или незначащий НИК не допустим";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
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

if (!ChatStopWordProtectNickLogInOk($Nick,$Room))
 {
  $ChatErrorText = "АВТОЦЕНЗОР: Недопустимые слова в нике";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

/*
if (ChatNickExists($Nick))
 {
  // "Быстрая" проверка на существование пользователя
  // Реально выполняется внутри ChatUserAdd
  // Теоритически мы можем не вызывать эту функцию здесь
  // Последующий код автоматически обработавет "Восстановление при RegIn"
  $ChatErrorText = "Пользователь с таким ником уже существует";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }
*/

if (!ChatNickExists($Nick))
 {
  // Поддержка "Refresh при RegIn" - если пользователь уже есть
  // Его не нужно добавлять повторно, и ессно, не нужно
  // вызывать ядля проверки этого внешнюю систему
  if (!ExtFromChatUserAddIsOk($ErrorMess,$Nick,$EMail))
   {
    ChatServerLogWriteWarning("Error calling ext_link(UserAddIsOk):".$ErrorMess);
    $ChatErrorText = "Модуль интеграции:".$ErrorMess;
    include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
    exit;
   }
 }

// Добавить пользователя
if (ChatUserAdd($Nick,$Password,$EMail,$Color,$Gender,$SelfNotes))
 {
  // Пользователь успешно добавлен в чат
  // Вызываем внешнюю систему

  if (!ExtFromChatUserAdd($ErrorMess,$Nick,$Password,null,$EMail))
   {
    ChatServerLogWriteWarning("Error calling ext_link(UserAdd):".$ErrorMess);
    ChatUserDelete($Nick); // ignore result

    $ChatErrorText = "Модуль интеграции:".$ErrorMess;
    include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
    exit;
   }
 }
else
 {
  // Ошибка такой пользователь уже существует или не может быть добавлен
  
  // *** Поддержка повторного прихода запроса логина *** Start

  // Проверяем пароль и ищем активную сессию - если пароль подходит
  // выполняем все действия дальнейшии как будто
  // мы только добавиили ник. (при условии что есть сессия)
  // (Поддержка Ctrl+Refresh после логина в броузере)

  // Проверки "как перед логином"

  if (!ChatPasswordValid($Nick,$Password))
   {
    // Пароль не валиден - значит это не
    // Попытка повторного входа, а регистрация нового ника в форме
    $ChatErrorText = "Пользователь с таким ником уже существует";
    include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
    exit;
   }

  $RealNick = ChatGetRealNick($Nick);

  if      ($RealNick == "")
   {
    // Ошибка повторного поиска (Странно)
    $ChatErrorText = "Ошибка определения контекста ника";
    include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
    exit;
   }

  if (!ChatStrNickStrValid($RealNick))
   {
    $ChatErrorText = "Используются запрещенные символы в нике";
    include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
    exit;
   }

  $SID = ChatGetRoomSID($RealNick,$Room);

  if ($SID == "") // Активной сессии еще (уже) нет
   {
    // Не разрешаем восстановление контекста здесь
    // Хотя теоритически мы могли бы разрешить
    // продолжение регистрации

    // Это для того случая, когда новый пользователь "случайно" угадает пароль
    // Таким образом, даже если пароль совпадает с существующим ником
    // Если нет активной сесии у этого ника, то RegIn не позволит этого
    // обнаружить

    $ChatErrorText = "Пользователь с таким ником уже существует";
    include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
    exit;
   }
  else
   {
    // *** Продолжаем выполнение контекста ***
    // Последущий код для логина и регистрации практически одинаков
   }

  // *** Поддержка повторного прихода запроса логина *** End

 }

$RealNick = ChatGetRealNick($Nick);

if      ($RealNick == "")
 {
  $ChatErrorText = "Ошибка определения контекста ника";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (!ChatStrNickStrValid($RealNick))
 {
  $ChatErrorText = "Используются запрещенные символы в нике";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

$SID = ChatGetRoomSID($RealNick,$Room);

if ($SID == "") // Not logged yet
 {
  if (defined("ChatDelayRegInMessage"))
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
    if (defined("ChatDelayRegInMessage"))
     {
      // We will send it on first Update/Reload
     }
    else
     {
      ChatRegInMessSend($Room,$User_Gender,$User_Color,$RealNick);
     }
   }
 }
else
 {
  // Just keep connected
  ChatUpdateUserInRoom($RealNick,$SID,$Room,$Color,$Topic);
  ChatFillConnectedUser_Vars($RealNick,$SID,$Room);
 }

if (defined("ChatFastBootRegInAction"))
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
