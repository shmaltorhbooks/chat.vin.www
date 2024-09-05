<?
  define("ChatConstMessTimeOutInMin"        ,10); // Time to store recived mess in database
  define("ChatConstUserActivityTimeOutInMin",15); // If no resuests drop session
  define("ChatConstSIDMaxLength"            ,20);
  define("ChatConstUserNickNameMaxLength"   ,15);

  define("ChatConstMessHugeTimeOutInDays",90); // Any message no more than

  define("ChatConstDefaultChatRoom","Главная");

  define("ChatConstUserGenderMale"     ,"M");
  define("ChatConstUserGenderFemale"   ,"F");
  define("ChatConstUserGenderUndefined","U"); // May not be used here (default)

  define("ChatConstDefUserColor","seagreen"); // Default user color if undefined

  define("ChatConstMessModelChat"   ,0);
  define("ChatConstMessModelJoin"   ,1);
  define("ChatConstMessModelLeave"  ,2);
  define("ChatConstMessModelURL"    ,3);
  define("ChatConstMessModelPrivate",9);

  // Признак (от клиента) что взведен флаг привата "MessPvt"
  define("ChatConstClientMessPvtSignPrivate",1);
  define("ChatConstClientMessPvtSignChat",0);

  // Сообщения в приват клиенту отсылаются в только с этим режимом
  define("ChatConstMessModelPrivateForClient",0);

  // Ограничения на информацию пользователя
  define("ChatConstSelfNotesMaxLength",10240); // In Bytes
  define("ChatConstNickNotesMaxLength",10240); // In Bytes

  // Ник вообщения от которого пропускают этап HTML экрана
  define("ChatConstPureAdminNick","Admin");

  // Максимум сообщений которые удаляются за 1 раз при очистке доставленного привата
  define("ChatConstPurgeMessMaxDelCount",20); // Note:PurgeProb >= 1/(value)

  define("ChatConstProbMaxValue",100); // Вероятность задается в процентах

  // Вероятности вызова функции очистки при разных
  // событиях сервера 
  // (при загрузке в 80 человек 100 запросов проходит за 10 секунд
  //  т.е 1 процент AnyReq равен примерно 10 секундам времени)
  define("ChatConstProbPurgeTimeOutUsersAnyReq"     ,  0.1);
  define("ChatConstProbPurgeTimeOutUsersSendPvtMess",  2.5);
  define("ChatConstProbPurgeTimeOutUsersSendStdMess",  2.5);
  define("ChatConstProbPurgeTimeOutUsersSetsLogIn"  ,  1.0);
  define("ChatConstProbPurgeTimeOutUsersSetsLogOut" ,  1.0);
  define("ChatConstProbPurgeTimeOutUsersChatLogIn"  ,100.0);
  define("ChatConstProbPurgeTimeOutUsersChatLogOut" ,100.0);

  define("ChatConstProbPurgeTimeOutMessAnyReq"      ,  0.1);
  define("ChatConstProbPurgeTimeOutMessSendPvtMess" ,  5.5); //!Every 20+...th
  define("ChatConstProbPurgeTimeOutMessSendStdMess" ,  1.0);
  define("ChatConstProbPurgeTimeOutMessSetsLogIn"   ,  1.0);
  define("ChatConstProbPurgeTimeOutMessSetsLogOut"  ,  1.0);
  define("ChatConstProbPurgeTimeOutMessChatLogIn"   , 10.0);
  define("ChatConstProbPurgeTimeOutMessChatLogOut"  , 10.0);

  define("ChatConstProbPurgeMessAdmLogAtLogWrite"   ,  4.0); //every 25 fires
  define("ChatConstProbPurgeTimeOutBanChatLogIn"    ,  1.0); //Каждые 100 логинов

  // Флаги сеансов

  define("ChatConstSessionFlagPreparing",1); // Waits for first update request
  define("ChatConstSessionFlagActive",2);

  // Типы Ban_Model
  define("ChatConstBanModelNoRegIn"  ,"R"); // Запрет регистрации
  define("ChatConstBanModelNoLogIn"  ,"L"); // Запрет логина
  define("ChatConstBanModelNoStdMess","P"); // Запрет общих сообщений (только приват)
  define("ChatConstBanModelNoAnyMess","V"); // Запрет любых сообщений (только просмотр)
  define("ChatConstBanModelNoAnyReq" ,"F"); // Запрет любых запросов (полный блок)

  // Ограничения Server-Side FloodProtection
  // Должны быть "мягче" (т.е значения должны быть больше или равны)
  // Чем на клиентской стороне (см. /chat/c_input.js)
  // Как минимум на 20% 
  // (так как в результате задержек в сети сообщения мугут приходить "блоками")
  define("ChatFloodProtectMaxMessCount",15);
  define("ChatFloodProtectMinTimeInSec",30);
  // Если этот флаг определен - на сервере подключается проверка флуда
  define("ChatFloodProtectAtServerActiveFlag",1);

  define('ChatMinRatingToDisplayAtLogin',50);
  define('ChatMinRatingToUserInfoShow',50);
  define('ChatMinRatingMyInfoShow',-999999); // Show me virtual any my rating

  // Начало текста приватного сообщения (признак админкоманды)
  define('ChatMessTextAdminCommandPrefixChar','/');

?>
