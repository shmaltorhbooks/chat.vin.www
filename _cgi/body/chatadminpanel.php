<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<body>
<? 
include_once(dirname(__FILE__)."/"."../inc/constant.inc.php"); 
include_once(dirname(__FILE__)."/"."../inc/functions.inc.php"); 
include_once(dirname(__FILE__)."/"."../inc/support.inc.php");
?>

<script language="JavaScript">
<!-- hide

function ChatAdminMess(Text)
 {
  document.writeln(Text+"<br>");
 }

function ChatAdminACmd(Level,Command,Desc,ArgDesc)
 {
  document.writeln("(Уровень "+Level+") "+Command+" - "+ArgDesc+" "+Desc+"<br>");
 }

function ChatAdminHelp(User_AdminFlag)
 {
  if (User_AdminFlag > 0)
   {
    ChatAdminMess(" = СПРАВКИ = ");
    ChatAdminACmd(1,"HELP","Перечень доступных команд","");
    ChatAdminACmd(1,"LADM","Вывести список всех у кого есть привилегии администратора","");

    ChatAdminMess(" = ОПЕРАЦИИ С НИКАМИ = ");
    ChatAdminACmd(1,"OUT ","Выбрасываение ника из чата [+/MLF]","");

    ChatAdminMess(" = СТАНДАРТНЫЕ ПРЕДУПРЕЖДЕНИЯ = ");
    ChatAdminACmd(1,"WARN","Предупредить ник (Нецензурные выражения)","");
    ChatAdminACmd(1,"WHAT","Предупредить ник (Оскорбления)","");
    ChatAdminACmd(1,"WFLD","Предупредить ник (Флуд)","");

    ChatAdminMess(" = РАБОТА С ЛОГАМИ = ");
    ChatAdminACmd(1,"MLM" ,"Записать лог общего окна чата","");
    ChatAdminACmd(1,"MLF" ,"Записать лог общего окна чата + приват Админ<->Целевой ник","");
    ChatAdminACmd(1,"MCAT","Вывести каталог (к-во) логов","{DD/MM/YYYY} {[+/-]К-во дней}");
    ChatAdminACmd(1,"MLST","Вывести логи за дату (+/-дни)","{DD/MM/YYYY} {[+/-]К-во дней}");
    ChatAdminACmd(1,"MVIEW","Вывести детальный лог за номером LogId Вам в приват","[LogId]");
    ChatAdminMess("*** Примечание: Для просмотра доступны логи админов до уровня "+User_AdminFlag+" включительно");
   }

  if (User_AdminFlag >= 2)
   {
    ChatAdminMess(" = ИНФОРМАЦИЯ О НИКАХ = ");
    ChatAdminACmd(2,"IP  ","Вывод IP ника","");
    ChatAdminACmd(2,"STAT","Вывод статистики чата","");

    ChatAdminMess(" = ПРОСМОТР ТАБЛИЦЫ БЛОКИРОВОК = ");
    ChatAdminACmd(2,"LBAN","Просмотр /BAN таблицы блокированых ников","");
    ChatAdminACmd(2,"LBIP","Просмотр /BIP таблицы блокированых IP","");
   }

  if (User_AdminFlag >= 3)
   {
    ChatAdminMess(" = РАБОТА С БЛОКИРОВКАМИ = ");
    ChatAdminACmd(3,"BAN","Блокировка ника [+/MLF]","{Type} {Time}");
    ChatAdminACmd(3,"BIP","Блокировка IP ника [+/MLF]","{Type} {Time}");
    ChatAdminACmd(3,"UNBAN","Снятие санкций на ник [*]","");
    ChatAdminACmd(3,"UNBIP","Снятие санкций на IP ника [*]","");
    ChatAdminMess(" Type:");
    ChatAdminMess(" '"+'<?=ChatConstBanModelNoRegIn    ?>'+"' Запрет регистрации");
    ChatAdminMess(" '"+'<?=ChatConstBanModelNoLogIn    ?>'+"' Запрет логина");
    ChatAdminMess(" '"+'<?=ChatConstBanModelNoStdMess  ?>'+"' Запрет общих сообщений (только приват)");

    if (User_AdminFlag >= 4)
     {
      ChatAdminMess(" '"+'<?=ChatConstBanModelNoAnyMess?>'+ "' Запрет любых сообщений (только просмотр)");
      ChatAdminMess(" '"+'<?=ChatConstBanModelNoAnyReq?>'+  "' Запрет любых запросов (полный блок)");
     }

    ChatAdminMess(" '"+"*"+"' (или не указано) = По умолчанию:"
                            +'<?=ChatConstBanModelNoRegIn?>'+" Для /BIP ,"
                            +'<?=ChatConstBanModelNoStdMess?>'+" Для /BAN");
    ChatAdminMess(" Time: Время на которое блокируется ник/IP в минутах. '*'(или не указано) = По умолчанию:10 минут");
    ChatAdminMess(" ПРИМЕРЫ:"
                 +" '/BAN' = '/BAN * *' = '/BAN "+'<?=ChatConstBanModelNoStdMess?>'+" 10'"
                 +" или "
                 +" '/BIP' = '/BIP * *' = '/BIP "+'<?=ChatConstBanModelNoRegIn?>'+" 10'");

    ChatAdminMess(" = РАСШИРЕНЫЕ ОПЕРАЦИИ С ЗАЛОГИНЕНЫМИ НИКАМИ = ");
    ChatAdminACmd(3,"LIP"  ,"Активные ники вывод всех IP (упорядочено по IP) [*]","");
    ChatAdminACmd(3,"LMD5" ,"Активные ники вывод всех образов паролей по MD5 (упорядоч.по MD5) [*]","");
  //ChatAdminACmd(3,"LMAIL","Активные ники вывод всех EMail адресов (упорядоч.по EMail) [*]","");
   }

  if (User_AdminFlag >= 4)
   {
    ChatAdminMess(" = РАСШИРЕННЫЕ ОПЕРАЦИИ С НИКАМИ(+) = ");
    ChatAdminACmd(4,"BANJS","Отсылка клиенту JavaScript предупреждения [+/MLF]","");
    ChatAdminACmd(4,"MUTE" ,"Выбрасывание ника из чата (просто удаление) без выдачи сообщения [+/MLF]","");
    ChatAdminACmd(4,"INFO" ,"Вывод Info пользователя [*]","");

    ChatAdminMess(" = ОПЕРАЦИИ С БАЗОЙ ПОЛЬЗОВАТЕЛЕЙ = ");
    ChatAdminACmd(4,"KILL" ,"Выбрасываение ника из чата и удаление из базы данных [*]","");
    ChatAdminACmd(4,"LOCK" ,"Блокировка регистрации ника (установка пустого пароля) [*]","");
    ChatAdminACmd(4,"REPSW","Установка нику фикc.пароля '"+'<?=$CmdAdminRemortPassword?>'+"' (для забывших пароль) [*]","");

    ChatAdminMess(" = ГРУППОВЫЕ ВЫБОРКИ = ");
    ChatAdminACmd(4,"SIP"  ,"Вывод всех с таким же IP как у указанного ника (не более "+'<?=$CmdAdminDefaultLimit?>'+")","");
    ChatAdminACmd(4,"SMAIL","Вывод всех с таким же EMail как у указанного ника (не более "+'<?=$CmdAdminDefaultLimit?>'+")","");
    ChatAdminACmd(4,"SMD5" ,"Вывод всех с таким же MD5 как у указанного ника (не более "+'<?=$CmdAdminDefaultLimit?>'+")","");
    ChatAdminACmd(4,"SMIP" ,"Вывод всех с таким же MD5 и IP как у указанного ника (не более "+'<?=$CmdAdminDefaultLimit?>'+")","");

    ChatAdminMess(" = ОТЛАДОЧНАЯ/КОНФИГУРАЦИОННАЯ ИНФОРМАЦИЯ = ");
    ChatAdminACmd(4,"DINF" ,"Текущая конфигурация движка чата","");
    ChatAdminACmd(4,"DPLS" ,"Справка о загруке mySql","");
   }

  if (User_AdminFlag >= 5)
   {
    ChatAdminMess(" = УПРАВЛЕНИЕ ПОЛНОМОЧИЯМИ = ");
    ChatAdminACmd(5,"ADM?","Дать привилегии администратора. Формат /ADM{LEVEL} [*]","");
    ChatAdminACmd(5,"ADM-","Убрать привилегии администратора (аналогично /ADM0) [*]","");
   }
 }

// -->
</script>

<b>Панель админа</b><br>

<script language="JavaScript">
ChatAdminHelp(5);
</script>

</body>
</html>
