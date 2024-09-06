<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
include_once(dirname(__FILE__)."/"."setup.inc.php");
include_once(dirname(__FILE__)."/"."constant.inc.php");
include_once(dirname(__FILE__)."/"."functions.inc.php");
include_once(dirname(__FILE__)."/"."funcdatetime.inc.php");
include_once(dirname(__FILE__)."/"."chatstopword.inc.php");


function ChatNickHasMyNotes($FromName,$ToName)
 {
  $query  =  
  "select *".
  " from UserNotes".
  " where (UserNotes.UserNotes_FromNickName = '".addslashes($FromName)."'"." AND ".
  "        UserNotes.UserNotes_ToNickName = '".addslashes($ToName)."')";

  $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  if ($userdata = mysql_fetch_array($userslist)) 
   {
    return(true);
   }
  else
   {
    return(false);
   }
 }


function ChatNickIP       ($RealNick)
 {
  $UserIP = "";

  $query = "select * from UserMaxEvent where User_NickName = '".addslashes($RealNick)."'";
  $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  if ($userdata = mysql_fetch_array($userslist)) 
   {
    $UserIP = $userdata[User_LastRemoteIP];
   }

  return($UserIP);
 }


function ChatAdminLevelByNick  ($RealNick)
 {
  $ToAdminFlag = 0;

  if ($RealNick != "")
   {
    $query = "select * from User where User_NickName = '".addslashes($RealNick)."'";
    $admlist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
    if ($admdata = mysql_fetch_array($admlist)) 
     {
      $ToAdminFlag = $admdata[User_AdminFlag];
     }
   }

  if ($ToAdminFlag == "")
   {
    $ToAdminFlag = 0;
   }

  return($ToAdminFlag);
 }


function ChatUserIsAdmin  ($RealNick)
 {
  if (ChatAdminLevelByNick($RealNick) > 0)
   {
    return(true);
   }
  else
   {
    return(false);
   }
 }


function ChatGetBanUserModelLowExt($RealNick)
 {
  $Result             = "";
  $ResultExpTimeInSec = 0;
  $currTimeStamp      = ChatCurrTimeStamp();

  if ($RealNick == ChatConstPureAdminNick)
   {
   }
  else
   {
    $query = "Select COUNT(*) from BanUser";
    $messcount = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
    $messcountdata = mysql_fetch_array($messcount);

    if      (!$messcountdata)
     {
      // Empty table
     }
    else if (($messcountdata[0] == '') || ($messcountdata[0] == 0))
     {
      // Empty table
     }
    else
     {
      $query  =
      "select * ".
      " from BanUser " .
      " where User_NickName = '".addslashes($RealNick)."' ".
      "";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
      if ($userdata = mysql_fetch_array($userslist)) 
       {
        $Expiried = false;

        if      ($userdata[Ban_ExpiryDateTime] == "")
         {
          $Expiried = true;
         }
        else if (ChatMakeTimeStampBySQLDateTime($userdata[Ban_ExpiryDateTime])
                  <= $currTimeStamp)
         {
          $Expiried = true;
         }
        else
         {
          $Result = $userdata[Ban_Model];

          $ResultExpTimeInSec = 
            ChatMakeTimeStampBySQLDateTime($userdata[Ban_ExpiryDateTime])
            - $currTimeStamp;
         }

        if ($Expiried)
         {
          $query  =
          "delete ".
          " from BanUser " .
          " where User_NickName = '".addslashes($RealNick)."' ".
          "";

          mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
         }
       }
     }
   }

  return(array($Result,$ResultExpTimeInSec));
 }


function ChatGetBanIPModelLowExt($IP)
 {
  $Result             = "";
  $ResultExpTimeInSec = 0;
  $currTimeStamp      = ChatCurrTimeStamp();

  if ($IP == "")
   {
   }
  else
   {
    $query = "Select COUNT(*) from BanIP";
    $messcount = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
    $messcountdata = mysql_fetch_array($messcount);

    if      (!$messcountdata)
     {
      // Empty table
     }
    else if (($messcountdata[0] == '') || ($messcountdata[0] == 0))
     {
      // Empty table
     }
    else
     {
      $query  =
      "select * ".
      " from BanIP " .
      " where BanIP_IP = '".addslashes($IP)."' ".
      "";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
      if ($userdata = mysql_fetch_array($userslist)) 
       {
        $Expiried = false;

        if      ($userdata[Ban_ExpiryDateTime] == "")
         {
          $Expiried = true;
         }
        else if (ChatMakeTimeStampBySQLDateTime($userdata[Ban_ExpiryDateTime])
                  <= $currTimeStamp)
         {
          $Expiried = true;
         }
        else
         {
          $Result = $userdata[Ban_Model];

          $ResultExpTimeInSec = 
            ChatMakeTimeStampBySQLDateTime($userdata[Ban_ExpiryDateTime])
            - $currTimeStamp;
         }

        if ($Expiried)
         {
          $query  =
          "delete ".
          " from BanIP " .
          " where BanIP_IP = '".addslashes($IP)."' ".
          "";

          mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
         }
       }
     }
   }

  return(array($Result,$ResultExpTimeInSec));
 }


function ChatGetBanUserModelLow($RealNick)
 {
  list($Result,$ExpTime) = ChatGetBanUserModelLowExt($RealNick);

  return($Result);
 }


function ChatGetBanIPModelLow($IP)
 {
  list($Result,$ExpTime) = ChatGetBanIPModelLowExt($IP);

  return($Result);
 }


function ChatUserRegInLockedByIP($IP = "")
 {
  if (empty($IP))
   {
    global $HTTP_SERVER_VARS;
    $IP = $HTTP_SERVER_VARS["REMOTE_ADDR"];
   }

  $BanIPModel = ChatGetBanIPModelLow($IP);

  if (($BanIPModel == ChatConstBanModelNoRegIn) || 
      ($BanIPModel == ChatConstBanModelNoLogIn))
   {
    return(true);
   }
  else
   {
    return(false);
   }
 }


function ChatUserLogInLockedByIP($IP = "")
 {
  if (empty($IP))
   {
    global $HTTP_SERVER_VARS;
    $IP = $HTTP_SERVER_VARS["REMOTE_ADDR"];
   }

  $BanIPModel = ChatGetBanIPModelLow($IP);

  if ($BanIPModel == ChatConstBanModelNoLogIn)
   {
    return(true);
   }
  else
   {
    return(false);
   }
 }


function ChatUserLogInLockedByNick($RealNick)
 {
  if (ChatUserIsAdmin($RealNick))
   {
    return(false);
   }

  $BanUserModel = ChatGetBanUserModelLow($RealNick);

  if (($BanUserModel == ChatConstBanModelNoLogIn) ||
      ($BanUserModel == ChatConstBanModelNoAnyReq))
   {
    return(true);
   }
  else
   {
    return(false);
   }
 }


function ChatUserAnyReqLockedByNickOrIP($RealNick,$IP = "")
 {
  if (ChatUserIsAdmin($RealNick))
   {
    return(false);
   }

  if (empty($IP))
   {
    global $HTTP_SERVER_VARS;
    $IP = $HTTP_SERVER_VARS["REMOTE_ADDR"];
   }

  $BanIPModel = ChatGetBanIPModelLow($IP);

  if ($BanIPModel == ChatConstBanModelNoAnyReq)
   {
    return(true);
   }
  else
   {
    $BanUserModel = ChatGetBanUserModelLow($RealNick);

    if ($BanUserModel == ChatConstBanModelNoAnyReq)
     {
      return(true);
     }
    else
     {
      return(false);
     }
   }
 }


function ChatUserMessFromBanModel($RealNick,$IP = "")
 {
  if (ChatUserIsAdmin($RealNick))
   {
    return("");
   }

  if (empty($IP))
   {
    global $HTTP_SERVER_VARS;
    $IP = $HTTP_SERVER_VARS["REMOTE_ADDR"];
   }

  $BanIPModel   = ChatGetBanIPModelLow($IP);
  $BanUserModel = ChatGetBanUserModelLow($RealNick);

  if      ($BanIPModel   == ChatConstBanModelNoAnyReq)
   {
    return($BanIPModel);
   }
  else if ($BanUserModel == ChatConstBanModelNoAnyReq)
   {
    return($BanUserModel);
   }
  else if ($BanIPModel   == ChatConstBanModelNoAnyMess)
   {
    return($BanIPModel);
   }
  else if ($BanUserModel == ChatConstBanModelNoAnyMess)
   {
    return($BanUserModel);
   }
  else if ($BanIPModel   == ChatConstBanModelNoStdMess)
   {
    return($BanIPModel);
   }
  else if ($BanUserModel == ChatConstBanModelNoStdMess)
   {
    return($BanUserModel);
   }
  else
   {
    return("");
   }
 }


function ChatUserMessToBanModel($RealNick,$IP = "")
 {
  if (ChatUserIsAdmin($RealNick))
   {
    return("");
   }

  if (empty($IP))
   {
    $IP = ChatNickIP($RealNick);
   }

  if ($IP != "")
   {
    $BanIPModel = ChatGetBanIPModelLow($IP);
   }
  else
   {
    $BanIPModel = "";
   }

  $BanUserModel = ChatGetBanUserModelLow($RealNick);

  if      ($BanIPModel   == ChatConstBanModelNoAnyReq)
   {
    return($BanIPModel);
   }
  else if ($BanUserModel == ChatConstBanModelNoAnyReq)
   {
    return($BanUserModel);
   }
  else if ($BanIPModel   == ChatConstBanModelNoAnyMess)
   {
    return($BanIPModel);
   }
  else if ($BanUserModel == ChatConstBanModelNoAnyMess)
   {
    return($BanUserModel);
   }
  else if ($BanIPModel   == ChatConstBanModelNoStdMess)
   {
    return($BanIPModel);
   }
  else if ($BanUserModel == ChatConstBanModelNoStdMess)
   {
    return($BanUserModel);
   }
  else
   {
    return("");
   }
 }


// Flood protection


function ChatFloodProtectOverLimitAction($RealNick,$SID,$Room,$MessTo)
 {
  $Text  = ""
          .ChatMakeJSMessTimeBySQLDateTime
            (ChatMakeSQLDateTimeByTimeStamp(ChatCurrTimeStamp()))
          ."";

  $Text .= " "
          ."Ваше сообщение к '$MessTo' не доставлено."
          ." "
          ." ПРИЧИНА:превышен max трафик исходящих сообщений";

  ChatClientPrintPrivat(htmlspecialchars($Text)."<br>");
 }


function ChatStopWordProtectAction($RealNick,$SID,$Room,$MessTo)
 {
  $Text  = ""
          .ChatMakeJSMessTimeBySQLDateTime
            (ChatMakeSQLDateTimeByTimeStamp(ChatCurrTimeStamp()))
          ."";

  $Text .= " "
          ."АВТОЦЕНЗОР: Ваше сообщение не отправлено."
          ." "
          ." ПРИЧИНА:Использование запрещенных слов";

  ChatClientPrintPrivat(htmlspecialchars($Text)."<br>");
 }


function ChatFloodProtectSendOk($RealNick)
 {
  if (!defined("ChatFloodProtectAtServerActiveFlag"))
   {
    return(true); // No flood protection active - No flood :-)
   }

  $OldestTimeStamp = "";
  $MessCount       = 0;

  $query  = "";
  $query .= "select Mess_DateTime from Mess";
  $query .= " where Mess_FromNick='".addslashes($RealNick)."'";
  $query .= "   and Mess_Model in (0,9)";
  $query .= " order by Event_Id desc";
  $query .= " Limit ".ChatFloodProtectMaxMessCount."";

  $qres = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  while($qdata = mysql_fetch_array($qres))
   {
    $MessCount++;

    $FoundTimeStamp = ChatMakeTimeStampBySQLDateTime($qdata[Mess_DateTime]);
    if      (empty($OldestTimeStamp))
     {
      $OldestTimeStamp = $FoundTimeStamp;
     }
    else if (empty($FoundTimeStamp))
     {
      // NULL found (???) - ignore
     }
    else
     {
      if ($FoundTimeStamp < $OldestTimeStamp)
       {
        $OldestTimeStamp = $FoundTimeStamp;
       }
     }
   }

  if ($MessCount < ChatFloodProtectMaxMessCount)
   {
    return(true); // No flood
   }

  if (($OldestTimeStamp+ChatFloodProtectMinTimeInSec) <= ChatCurrTimeStamp())
   {
    return(true); // No flood (yet)
   }

  return(false); // We got'a flood here!
 }


/////////////////////////////////////////////////////////////////////
// Поcылка сообщения
function ChatOneMessSend
            ($MessChatRoom,
             $MessText,
             $U_Color,
             $RealFrom,
             $MessTo,
             $M_Model)
 {
  $ResultMessId = null;

  if ((($M_Model == 0) || ($M_Model == 9)) &&
       ($RealFrom != ChatConstPureAdminNick))
   {
    $FromBanModel = ChatUserMessFromBanModel($RealFrom);

    if ($FromBanModel == ChatConstBanModelNoAnyReq)
     {
      return(null); // full mute
     }

    if ($FromBanModel == ChatConstBanModelNoAnyMess)
     {
      return(null); // full mute
     }

    if ($FromBanModel == ChatConstBanModelNoStdMess)
     {
      $M_Model = 9; // privat only
     }
   }

  $M_TimeStamp  = ChatMakeSQLTimeStampByTimeStamp();
  $Message      = ChatLowToSpace($MessText);
  
  $RealTo       = ChatGetRealNick($MessTo);
  $RealSendTo   = $RealTo;
  $ToBanModel   = "";

  if ((($M_Model == 0) || ($M_Model == 9)) &&
       ($RealFrom != ChatConstPureAdminNick))
   {
    if ($RealTo != "")
     {
      $ToBanModel = ChatUserMessToBanModel($RealTo);

      if ($ToBanModel == ChatConstBanModelNoAnyReq)
       {
        $M_Model = 9; // privat only
       }

      if ($ToBanModel == ChatConstBanModelNoAnyMess)
       {
        $M_Model = 9; // privat only
       }

      if ($ToBanModel == ChatConstBanModelNoStdMess)
       {
        $M_Model = 9; // privat only
       }
     }
   }

  if ($RealTo == "")
   {
    if ($M_Model == 9)
     {
      $Message = "Приват не отправлен - ник ".$MessTo." не зарегистрирован в системе";
      $RealTo = $RealFrom;
     }
    else
     {
      $RealTo = $MessTo;
     }
   }

  if ($RealTo == ChatConstPureAdminNick)
   {
    if ($M_Model == 9)
     {
      $Message = "Приват не отправлен - ник ".$RealTo." зарезервирован для системных сообщений";
      $RealTo = $RealFrom;
     }
   }

  // CheckUp if Mess is empty

  $query = "Select COUNT(*) from Mess";
  $messcount = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  $messcountdata = mysql_fetch_array($messcount);

  if      (!$messcountdata)
   {
    ChatServerLogWriteWarning("Not found COUNT(*) from Mess."."AutoRepar");
    // Mess is unaccesible
    $PutEventId = ChatGetLastMessId()+1;
   }
  else if (($messcountdata[0] == '') || ($messcountdata[0] == 0))
   {
    ChatServerLogWriteWarning("Empty result on COUNT(*) from Mess."."AutoRepar");
    // Mess is empty
    $PutEventId = ChatGetLastMessId()+1;
   }
  else
   {
    $PutEventId = ""; // Do not update EventId in Mess
   }

  // Do update mess

  $query  = "";
  $query .= "insert into Mess";
  $query .= " (";
  $query .= "ChatRoom_Name,";
  if ($PutEventId != "")
   {
    $query .= "Event_Id,";
   }
  $query .= "Mess_Text,";
  $query .= "Mess_Color,";
  $query .= "Mess_FromNick,";
  $query .= "Mess_ToNick,";
  $query .= "Mess_Model,";
  $query .= "Mess_DateTime";
  $query .= ")";
  $query .= " values ";
  $query .= "(";
  $query .= "'".addslashes($MessChatRoom)."',";
  if ($PutEventId != "")
   {
    $query .= "".$PutEventId.",";
   }
  $query .= "'".addslashes($Message)."',";
  $query .= "'".addslashes($U_Color)."',";
  $query .= "'".addslashes($RealFrom)."',";
  $query .= "'".addslashes($RealTo)."',";
  $query .= "'".addslashes($M_Model)."',";
  $query .= "'".addslashes($M_TimeStamp)."'";
  $query .= ")";

  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  $query = "select LAST_INSERT_ID()";
  $datalist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  if ($datarow = mysql_fetch_array($datalist)) 
   {
    $ResultMessId = $datarow[0];
   }
  else
   {
    ChatSQLDie2Log("Cannot read insert ID",$query);
   }

  // Update statistics

  if ($RealSendTo != $RealFrom)
   {
    if      (($M_Model == 9) && ($RealSendTo != ""))
     {
      $query = "update UserMaxEvent"
              ." Set "
              ." UserMaxEvent_SendMPvtCount = (IFNULL(UserMaxEvent_SendMPvtCount,0) + 1) "
              ." where  User_NickName='".addslashes($RealFrom)."'";

      mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
     }
    else if ($M_Model == 0)
     {
      $query = "update UserMaxEvent"
              ." Set "
              ." UserMaxEvent_SendMessCount = (IFNULL(UserMaxEvent_SendMessCount,0) + 1) "
              ." where  User_NickName='".addslashes($RealFrom)."'";

      mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
     }

    if      (($M_Model == 9) && ($RealSendTo != ""))
     {
      $query = "update UserMaxEvent"
              ." Set "
              ." UserMaxEvent_RecvMPvtCount = (IFNULL(UserMaxEvent_RecvMPvtCount,0) + 1) "
              ." where  User_NickName='".addslashes($RealSendTo)."'";

      mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
     }
    else if (($M_Model == 0) && ($RealSendTo != ""))
     {
      $query = "update UserMaxEvent"
              ." Set "
              ." UserMaxEvent_RecvMessCount = (IFNULL(UserMaxEvent_RecvMessCount,0) + 1) "
              ." where  User_NickName='".addslashes($RealSendTo)."'";

      mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
     }
   }

  if ($M_Model == 9)
   {
    ChatPurgeTimeOutAtSendPvtMess();
   }
  else
   {
    ChatPurgeTimeOutAtSendStdMess();
   }

  return($ResultMessId);
 }

/////////////////////////////////////////////////////////////////////
// Получение идентификатора последнего сообщения в базе
function ChatGetLastMessId()
 {
  $query  =  "select MAX(Event_Id) from Mess";
  $messidlast = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  $messidlast = mysql_fetch_array($messidlast);

  if ((!$messidlast) || ($messidlast[0] == ''))
      
   {
    // Mess table is emply. 
    // Try to found max Event_Id supplied by user at early time
    // (SUPPORT of fully clear Mess table)

    ChatServerLogWriteWarning("Empty result on MAX(Event_Id) from Mess."."AutoRepar");

    $query  =  "select MAX(User_MaxEventId) from UserMaxEvent";
    $messidlast = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
    $messidlast = mysql_fetch_array($messidlast);

    if ((!$messidlast) || ($messidlast[0] == ''))
     {
      return(0); // first boot!
     }
   }

  return($messidlast[0]);
 }

function ChatSetLastReqEventId ($U_Nick,$U_MID)
 {
  global $HTTP_SERVER_VARS;
  $query =
  "Update UserMaxEvent".
  " Set User_MaxEventId = '".addslashes($U_MID).
  "',User_LastRemoteIP = '".addslashes($HTTP_SERVER_VARS["REMOTE_ADDR"]).
  "' where User_NickName = '".addslashes($U_Nick)."'";
  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
 }

function ChatGetUI_Text()
 {
  global $ReqSendTime;

  $Result = "";

  $Result .=
   "A.UI("
   .JSFldInt(ChatGetLastMessId()).","
   .JSFldStr(ChatHashMakeFast()).","
   .JSFldStr($ReqSendTime).""
   .");"
   ."\n";

  return($Result);
 }

function ChatGetUI()
 {
  echo ChatGetUI_Text();
 }

function  ChatGetUF_Text()
 {
  global $ReqSendTime;

  $Result = "";

  $Result .=
   "A.UF("
   .JSFldInt(ChatGetLastMessId()).","
   .JSFldStr(ChatHashMakeFast()).","
   .JSFldStr($ReqSendTime).""
   .");"
   ."\n";

  return($Result);
 }

function  ChatGetUF()
 {
  echo ChatGetUF_Text();
 }

function  ChatGetUS_Text()
 {
  global $ReqSendTime;

  $Result = "";

  $Result .=
   "A.US("
   .JSFldStr(ChatHashMakeFast()).","
   .JSFldStr($ReqSendTime).""
   .");"
   ."\n";

  return($Result);
 }

function  ChatGetUS()
 {
  echo ChatGetUS_Text();
 }

function ChatGetNLFull_Text($U_Name,$U_ChatRoom)
 {
  $Result = "";

  $query  =
  "select * ".
  " from Session,User " .
  " where Session.User_NickName = User.User_NickName ".
  "  and Session.ChatRoom_Name = '".addslashes($U_ChatRoom)."' ".
  "  and Session.Session_Flags <> '".addslashes(ChatConstSessionFlagPreparing)."' ".
  " order by User.User_LogInDateTime";

  $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  while ($userdata = mysql_fetch_array($userslist)) 
   {
    if ($userdata[User_SelfNotes] != "")
     {
      $HasInfoFlag = "true";
     }
    else
     {
      $HasInfoFlag = "false";
     }

    if (ChatNickHasMyNotes($U_Name,$userdata[User_NickName]))
     {
      $HasMyNoteFlag = "true";
     }
    else
     {
      $HasMyNoteFlag = "false";
     }

    $Result .=
     "A.NL("
     .JSFldStr($userdata[User_NickName]).","
     .JSFldStr($userdata[User_Color]).","
     .JSFldStr($userdata[User_Gender]).","
     .JSFldStr(ChatMakeJSMessTimeBySQLDateTime($userdata[User_LogInDateTime])).","
     .JSFldStr($userdata[Session_UserTopic]).","
     .$HasInfoFlag.","
     .$HasMyNoteFlag.""
     .");"
     ."\n";
   }

  return($Result);
 }

function ChatGetNLFull($U_Name,$U_ChatRoom)
 {
  echo ChatGetNLFull_Text($U_Name,$U_ChatRoom);
 }

function ChatSIDNickRoomValid($U_SID,$U_Name,$U_Room)
 {
  $query  =  
  "select * from Session".
  " where Session_Id='".addslashes($U_SID)."'".
  " and User_NickName='".addslashes($U_Name)."'".
  " and ChatRoom_Name='".addslashes($U_Room)."'";

  $messidlast = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  if (mysql_fetch_array($messidlast))
   {
    return (true);
   }
  else
   {
    return (false);
   }
 }

function ChatGetM_All_Text
         ($U_Name, $U_SID, $U_ChatRoom, $M_EventId,$ShowUserActivityFlag)
 {
  $Result = "";

  if ($M_EventId == 0)
   {
    $Result .= ChatLoginInfoWriteToUser_Text($U_Name,$U_ChatRoom,$U_SID);
   }

  $curdate = ChatCurrTimeStamp();
  $currTimeStamp  = ChatMakeSQLTimeStampByTimeStamp($curdate);
  
  // ChatConstMessTimeOutInMin мин назад
  $boundTimeStamp = 
   ChatMakeSQLTimeStampByTimeStamp($curdate-(ChatConstMessTimeOutInMin*60));

  if ($M_EventId != 0)
   {
    ChatSetLastReqEventId ($U_Name,$M_EventId);
    $query  = 
    "select *".
     " from Mess".
    " where Mess.Event_Id > '".addslashes($M_EventId)."'".
          " and ((Mess.ChatRoom_Name = '" . addslashes($U_ChatRoom) .  "'".
                " and Mess.Mess_Model <> '9')".
              " or (Mess.Mess_Model = '9'" .
                  " and (((Mess.Mess_FromNick = '" . addslashes($U_Name) . "') and (Mess.Mess_DateTime >= '" . addslashes($boundTimeStamp) . "'))".
                       " or Mess.Mess_ToNick = '" . addslashes($U_Name) . "' ) ) )".
    " order by Event_Id";
   }
  else
   {
    $query  = 
    "select *".
     " from Mess".
    " where (Mess.Mess_DateTime >= '" . addslashes($boundTimeStamp) . "'".
             " and Mess.ChatRoom_Name = '" . addslashes($U_ChatRoom) .  "'" .
             " and Mess.Mess_Model <> '9')".
          " or (Mess.Mess_Model = '9'".
              " and (((Mess.Mess_FromNick = '" . addslashes($U_Name) . "') and (Mess.Mess_DateTime >= '" . addslashes($boundTimeStamp) . "'))". 
                   " or Mess.Mess_ToNick = '" . addslashes($U_Name) . "'))".
    " order by Event_Id";
   }

  $messlist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  while ($messline = mysql_fetch_array($messlist)) 
   {
    if ($messline[Mess_Model] == 9)
     {
      $Result .=
        "A.MP("
        .JSFldInt(ChatConstMessModelPrivateForClient).","
        .JSFldStr(ChatMakeJSMessTimeBySQLDateTime($messline[Mess_DateTime])).","
        .JSFldStr($messline[Mess_FromNick]).","
        .JSFldStr($messline[Mess_ToNick]).","
        .JSFldStr($messline[Mess_Text]).","
        .JSFldStr($messline[Mess_Color]).","
        .JSFldInt($messline[Event_Id]).""
        .");"
        ."\n";
     }
    else
     {
      $Result .=
        "A.MC("
        .JSFldInt($messline[Mess_Model]).","
        .JSFldStr(ChatMakeJSMessTimeBySQLDateTime($messline[Mess_DateTime])).","
        .JSFldStr($messline[Mess_FromNick]).","
        .JSFldStr($messline[Mess_ToNick]).","
        .JSFldStr($messline[Mess_Text]).","
        .JSFldStr($messline[Mess_Color]).","
        .JSFldInt($messline[Event_Id]).""
        .");"
        ."\n";
     }

    if ($ShowUserActivityFlag)
     {
      if      ($messline[Mess_Model] == '1')
       {
        $query =
        "select *".
        " from Session,User " .
        " where Session.User_NickName = User.User_NickName".
        " and Session.User_NickName = '". addslashes($messline[Mess_ToNick])."'".
        " and Session.ChatRoom_Name = '". addslashes($U_ChatRoom) . "'";

        $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
        while($userdata  = mysql_fetch_array($userslist))
         {
          if ($userdata[User_SelfNotes] != "")
           {
            $HasInfoFlag = "true";
           }
          else
           {
            $HasInfoFlag = "false";
           }

          if (ChatNickHasMyNotes($U_Name,$userdata[User_NickName]))
           {
            $HasMyNoteFlag = "true";
           }
          else
           {
            $HasMyNoteFlag = "false";
           }

          $Result .=
           "A.NL("
           .JSFldStr($userdata[User_NickName]).","
           .JSFldStr($userdata[User_Color]).","
           .JSFldStr($userdata[User_Gender]).","
           .JSFldStr(ChatMakeJSMessTimeBySQLDateTime($userdata[User_LogInDateTime])).","
           .JSFldStr($userdata[Session_UserTopic]).","
           .$HasInfoFlag.","
           .$HasMyNoteFlag.""
           .");"
           ."\n";
          }
        }
      else if ($messline[Mess_Model] == '2')
       {
        $Result .=
        "A.NO("
        .JSFldStr($messline[Mess_ToNick]).""
        .");"
        ."\n";
       }
     }
   }

  return($Result);
 }

function ChatGetM_All
         ($U_Name, $U_SID, $U_ChatRoom, $M_EventId,$ShowUserActivityFlag)
 {
  echo ChatGetM_All_Text
         ($U_Name, $U_SID, $U_ChatRoom, $M_EventId,$ShowUserActivityFlag);
 }

function ChatNickExists ($U_Nick)
 {
  $NickHash = ChatNickToVisual ($U_Nick);
  $query =
  "select *".
  " from User".
  " where User_NickName = '".addslashes($U_Nick)."'".
  " or User_NickHash = '".addslashes($NickHash)."'";

  $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  if(mysql_fetch_array($userslist)) 
   {
    return(true);
   }
  else
   {
    return(false);
   }
 }

function ChatPasswordValid ($U_Nick, $U_Password)
 {
  $U_NickHash = ChatNickToVisual ($U_Nick);
  $query =
  "select *".
  " from User".
  " where (User.User_NickName = '".addslashes(ChatLowToSpace($U_Nick))."'".
  " or User_NickHash = '".addslashes($U_NickHash)."')".
  " and User.User_Password = '".addslashes(md5(ChatLowToSpace($U_Password)))."'";

  $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  if($userdata = mysql_fetch_array($userslist))
   {
    return (true);
   }
  else
   {
    return (false);
   }
 }

function ChatUpdateSesAct ($U_SID)
 {
  $currTimeStamp = ChatMakeSQLTimeStampByTimeStamp();

  $query =
   " update Session"
  ." Set Session_LastActDateTime = '".$currTimeStamp."' "
  ." where Session_Id = '".addslashes($U_SID)."'";

  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
 }


function ChatUpdateSessionFlags($U_SID,$NewSessionFlags)
 {
  $query =
   " update Session"
  ." Set Session_Flags = '".addslashes($NewSessionFlags)."' "
  ." where Session_Id = '".addslashes($U_SID)."'";

  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
 }


function ChatClearConnectedUser_Vars()
 {
  global 
   $User_Nick,
   $User_Password,
   $User_Color,
   $User_Topic,
   $User_Room,
   $User_EMail,
   $User_Gender,
   $User_LoginsCount,
   $User_AdminFlag,
   $User_SelfNotes,
   $User_Session_Flags,
   $User_Session_ChatRoom_Name;

  $User_Nick          = "";
  $User_Password      = "";
  $User_Color         = "";
  $User_Topic         = "";
  $User_Room          = "";
  $User_EMail         = "";
  $User_Gender        = "";
  $User_LoginsCount   = 0;
  $User_AdminFlag     = 0;
  $User_SelfNotes     = "";

  $User_Session_Flags         = 0;
  $User_Session_ChatRoom_Name = "";
 }

function ChatFillConnectedUser_Vars ($U_Nick, $U_SID, $U_Room)
 {
  global 
   $User_Nick,
   $User_Password,
   $User_Color,
   $User_Topic,
   $User_Room,
   $User_EMail,
   $User_Gender,
   $User_LoginsCount,
   $User_AdminFlag,
   $User_SelfNotes,
   $User_Session_Flags,
   $User_Session_ChatRoom_Name;
  global $SID;

  $RetFlag    = true;
  $U_NickHash = ChatNickToVisual ($U_Nick);

  $query =
  "select *".
  " from Session,User".
  " where Session.User_NickName = User.User_NickName".
  " and User.User_NickHash = '".addslashes($U_NickHash)."' ".
  " and Session.ChatRoom_Name = '".addslashes($U_Room)."'".
  " and Session.Session_Id = '".addslashes($U_SID)."'";

  $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  if ($userdata = mysql_fetch_array($userslist)) 
   {
    $User_Nick        = $userdata[User_NickName];
    $User_Password    = $userdata[User_Password];
    $User_Color       = $userdata[User_Color];
    $User_Topic       = $userdata[Session_UserTopic];
    $User_Room        = $userdata[ChatRoom_Name];
    $User_EMail       = $userdata[User_EMail];
    $User_Gender      = $userdata[User_Gender];
    $SID              = $userdata[Session_Id];       // BUG trap (ReAssign)
    $User_AdminFlag   = intval($userdata[User_AdminFlag]);
    $User_LoginsCount = intval($userdata[User_LoginsCount]);
    $User_SelfNotes   = $userdata[User_SelfNotes];

    $User_Session_Flags         = intval($userdata[Session_Flags]); // Numeric
    $User_Session_ChatRoom_Name = $userdata[ChatRoom_Name];
   }
  else
   {
    ChatClearConnectedUser_Vars();
    $RetFlag = false;
   }

  if ($RetFlag)
   {
    ChatUpdateSesAct($U_SID);
   }

  ChatPurgeTimeOutAtAnyRequest();

  return ($RetFlag);
 }


function ChatEnterToRoom 
          ($U_Nick,
           $U_SID,
           $U_Room,
           $U_Color,
           $U_Topic,
           $Session_Flags = ChatConstSessionFlagPreparing)
 {
  global $HTTP_SERVER_VARS;

  $timestamp = ChatMakeSQLTimeStampByTimeStamp();

  $query =
    "insert into Session "
   ."("
   ."Session_Id,"
   ."ChatRoom_Name,"
   ."User_NickName,"
   ."Session_LastActDateTime,"
   ."Session_StartTime,"
   ."Session_UserTopic,"
   ."Session_RemoteIP,"
   ."Session_Flags"
   .")"
   ." values "
   ."("
   ."'".addslashes($U_SID)."',"
   ."'".addslashes($U_Room)."',"
   ."'".addslashes($U_Nick)."',"
   ."'".$timestamp."',"
   ."'".$timestamp."',"
   ."'".addslashes($U_Topic)."',"
   ."'".addslashes($HTTP_SERVER_VARS["REMOTE_ADDR"])."',"
   ."'".addslashes($Session_Flags)."'"
   .")";

  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  if ($U_Color != "")
   {
    $query  = "Update User";
    $query .= " Set User_Color = '".addslashes($U_Color)."'";
    $query .= " where User_NickName = '".addslashes($U_Nick)."'";

    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
   }

  $query  = "Select * From UserMaxEvent";
  $query .= " where User_NickName = '".addslashes($U_Nick)."'";

  $usersmaxevent = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  if ($userdata = mysql_fetch_array($usersmaxevent)) 
   {
    // UserMaxEvent present ok
   }
  else
   {
    ChatServerLogWriteWarning("Not found UserMaxEvent for user '$U_Nick'."."AutoRepar");

    $query =
      "insert into UserMaxEvent "
     ."("
     ."User_NickName,"
     ."User_MaxEventId,"
     ."User_LastRemoteIP"
     .")"
     ." values "
     ."("
     ."'".addslashes($U_Nick)."',"
     ."'"."0"."',"
     ."'".addslashes($HTTP_SERVER_VARS["REMOTE_ADDR"])."'"
     .")";

    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
   }

  ChatPurgeTimeOutAtLogIn($U_Room);
 }


function ChatUpdateUserInRoom ($U_Nick,$U_SID,$U_Room,$U_Color,$U_Topic)
 {
  global $HTTP_SERVER_VARS;

  $timestamp = ChatMakeSQLTimeStampByTimeStamp();

  $query =
    "update Session "
   ." set "
   ." ChatRoom_Name="."'".addslashes($U_Room)."',"
   ." Session_LastActDateTime="."'".$timestamp."',"
   ." Session_UserTopic="."'".addslashes($U_Topic)."',"
   ." Session_RemoteIP="."'".addslashes($HTTP_SERVER_VARS["REMOTE_ADDR"])."'"
   ." where "
   ."("
   ." Session_Id="."'".addslashes($U_SID)."'"
   ." AND "
   ." User_NickName="."'".addslashes($U_Nick)."'"
   .")";

  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  if ($U_Color != "")
   {
    $query  = "Update User";
    $query .= " Set User_Color = '".addslashes($U_Color)."'";
    $query .= " where User_NickName = '".addslashes($U_Nick)."'";

    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
   }

  ChatPurgeTimeOutAtLogIn($U_Room);
 }


function ChatUpdateUserInfoByLogin($U_Nick)
 {
  global $User_LoginsCount;

  $timestamp = ChatMakeSQLTimeStampByTimeStamp();
  $User_LoginsCount++;

  $query = 
     "Update User"
    ." Set "
    ." User_LoginsCount = (User_LoginsCount+1),"
    ." User_LogInDateTime = '".$timestamp."'"
    ." Where User_NickName = '".addslashes($U_Nick)."'";

  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
 }


function ChatGetRealNick ($U_Nick)
 {
  $RetNick = "";
  $U_NickHash = ChatNickToVisual($U_Nick);

  $query = "select User_NickName from User where User.User_NickHash = '".addslashes($U_NickHash)."'";
  $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  if ($userdata = mysql_fetch_array($userslist)) 
   {
    $RetNick = $userdata[User_NickName];
   }

  return ($RetNick);
 }


function ChatDropSession ($U_Nick,$U_SID)
 {
  $query =
  "delete from Session where Session_Id = '".addslashes($U_SID)."' and User_NickName = '".addslashes($U_Nick)."'";
  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
 }


function ChatUserCalcStats ($U_Name,$U_SessionStartDateTime,$EndLastTimestamp = "")
 {
  if ($U_SessionStartDateTime != "")
   {
    $StartTimestamp = ChatMakeTimeStampBySQLDateTime($U_SessionStartDateTime);

    if ($StartTimestamp > 0)
     {
      if (($EndLastTimestamp == "") || ($EndLastTimestamp <= 0))
       {
        $EndTimestamp = ChatCurrTimeStamp();
       }
      else
       {
        $EndTimestamp = $EndLastTimestamp;
       }

      if ($StartTimestamp < $EndTimestamp)
       {
        $TimeInSec = $EndTimestamp - $StartTimestamp;

        $TimeInSec = $TimeInSec;

        if ($TimeInSec > 0)
         {
          $query =
          "update User".
          " Set User_SpentTimeSec = IFNULL(User_SpentTimeSec,0) + ".$TimeInSec."".
          " where User_NickName='".addslashes($U_Name)."'";

          mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
         }
       }
     }
   }
 }


function ChatPurgeMessages()
 {
  static $PurgeActiveFlag = false;

  if ($PurgeActiveFlag)
   {
    return; // allready here
   }

  $PurgeActiveFlag = true;

  $curdate = ChatCurrTimeStamp();

  if (defined("ChatFullRequestTrace"))
   {
    ChatServerLogWriteLog("PurgeMessages ".$curdate);
   }

  // --- Purge Greneral by timeout ---
  $timestamp = ChatMakeSQLTimeStampByTimeStamp($curdate-(ChatConstMessTimeOutInMin*60));
  $query = 
    "delete from Mess "
   ."where Mess_Model <> '9' and Mess_DateTime < '".$timestamp."'";
  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  // --- Any messages by huge timeout ---
  $timestamp = ChatMakeSQLTimeStampByTimeStamp($curdate-(ChatConstMessHugeTimeOutInDays*24*60*60));
  $query = 
    "delete from Mess "
   ."where Mess_DateTime < '".$timestamp."'";
  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  // --- Purge private detailed ---
  $NeedDeleteFlag = false;
  $timestamp = ChatMakeSQLTimeStampByTimeStamp($curdate-(ChatConstMessTimeOutInMin*60));

  // UserMaxEvent.User_MaxEventId 
  // Идентификатор события уже прочитанного пользоватлем

  $query = 
  "select Event_Id from Mess, UserMaxEvent".
  " where (Mess.Mess_Model = '9'".
  " and Mess.Mess_ToNick = UserMaxEvent.User_NickName".
  " and Mess.Event_Id <= UserMaxEvent.User_MaxEventId".
  " and Mess_DateTime < '".$timestamp."')".
// No more order by here - since its no matter what order to use with delete
//" order by Event_Id".
  " LIMIT ".ChatConstPurgeMessMaxDelCount;
  
  $DeleteEventList = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  $ExitFlag = false;
  $NeedDeleteFlag = false;
  $Index = 0;
  $query = "delete from Mess where Event_Id in (";

  while (!$ExitFlag)
   {
    if ($DeleteEvent = mysql_fetch_array($DeleteEventList))
     {
      if ($NeedDeleteFlag)
       {
        $query .= ",";
       }

      $query .= $DeleteEvent[0];
      $NeedDeleteFlag = true;
      $Index++;

      if ($Index >= ChatConstPurgeMessMaxDelCount)
       {
        // BUG Trap
        $ExitFlag = true;
       }
     }
    else
     {
      $ExitFlag = true;
     }
   }

  $query .= ")";

  if ($NeedDeleteFlag)
   {
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
   }

//ChatClientMessShow("PurgeMessages ".microtime());
  $PurgeActiveFlag = false;
 }


function ChatTimeOutBans()
 {
  static $PurgeActiveFlag = false;

  if ($PurgeActiveFlag)
   {
    return; // allready here
   }

  $PurgeActiveFlag = true;

  $curdate = ChatCurrTimeStamp();

  if (defined("ChatFullRequestTrace"))
   {
    ChatServerLogWriteLog("PurgeBans ".$curdate);
   }

  // --- Purge expiried BanIP's ---
  $timestamp = ChatMakeSQLTimeStampByTimeStamp($curdate);
  $query = 
    "delete from BanIP "
   ."where Ban_ExpiryDateTime < '".$timestamp."'";
  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  // --- Purge expiried BanUser's ---
  $timestamp = ChatMakeSQLTimeStampByTimeStamp($curdate);
  $query = 
    "delete from BanUser "
   ."where Ban_ExpiryDateTime < '".$timestamp."'";
  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

//ChatClientMessShow("PurgeBans ".microtime());
  $PurgeActiveFlag = false;
 }


function ChatTimeOutUsers()
 {
  static $PurgeActiveFlag = false;

  if ($PurgeActiveFlag)
   {
    return; // allready here
   }

  $PurgeActiveFlag = true;

  $curdate = ChatCurrTimeStamp();

  if (defined("ChatFullRequestTrace"))
   {
    ChatServerLogWriteLog("PurgeUsers ".$curdate);
   }

  $timestamp = 
   ChatMakeSQLTimeStampByTimeStamp($curdate-(ChatConstUserActivityTimeOutInMin*60));

  $query = 
  "select * from Session, User".
  " where User.User_NickName = Session.User_NickName".
  " and Session.Session_LastActDateTime < '".$timestamp."'";
  $DropSessList = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  while ($DropSess = mysql_fetch_array($DropSessList)) 
   {
    if ($DropSess[ChatRoom_Name] != "")
     {
      ChatUserCalcStats
       ($DropSess[User_NickName],
        $DropSess[Session_StartTime],
        ChatMakeTimeStampBySQLDateTime($DropSess[Session_LastActDateTime]));
     }

    ChatDropSession
     ($DropSess[User_NickName],
      $DropSess[Session_Id]);

    if ($DropSess[ChatRoom_Name] != "")
     {
      if ($DropSess[Session_Flags] == ChatConstSessionFlagPreparing)
       {
        // Сессия так и не была активирована полностью
       }
      else
       {
        ChatDropMessSend
         ($DropSess[ChatRoom_Name],
          $DropSess[User_Gender],
          $DropSess[User_Color],
          $DropSess[User_NickName]);
       }
     }
   }

  ChatTimeOutBans(); // Force

//ChatClientMessShow("PurgeUsers ".microtime());
  $PurgeActiveFlag = false;
 }


function ChatPurgeTimeOutAtAnyRequest()
 {
  if (ChatProbabilityMatch(ChatConstProbPurgeTimeOutMessAnyReq))
   {
    ChatPurgeMessages();
   }

  if (ChatProbabilityMatch(ChatConstProbPurgeTimeOutUsersAnyReq))
   {
    ChatTimeOutUsers();
   }
 }


function ChatPurgeTimeOutAtSendStdMess()
 {
  if (ChatProbabilityMatch(ChatConstProbPurgeTimeOutMessSendStdMess))
   {
    ChatPurgeMessages();
   }

  if (ChatProbabilityMatch(ChatConstProbPurgeTimeOutUsersSendStdMess))
   {
    ChatTimeOutUsers();
   }
 }


function ChatPurgeTimeOutAtSendPvtMess()
 {
  if (ChatProbabilityMatch(ChatConstProbPurgeTimeOutMessSendPvtMess))
   {
    ChatPurgeMessages();
   }

  if (ChatProbabilityMatch(ChatConstProbPurgeTimeOutUsersSendPvtMess))
   {
    ChatTimeOutUsers();
   }
 }


function ChatPurgeTimeOutAtLogIn($U_Room = "")
 {
  if ($U_Room != "")
   {
    if (ChatProbabilityMatch(ChatConstProbPurgeTimeOutMessChatLogIn))
     {
      ChatPurgeMessages();
     }

    if (ChatProbabilityMatch(ChatConstProbPurgeTimeOutUsersChatLogIn))
     {
      ChatTimeOutUsers();
     }
   }
  else
   {
    if (ChatProbabilityMatch(ChatConstProbPurgeTimeOutMessSetsLogIn))
     {
      ChatPurgeMessages();
     }

    if (ChatProbabilityMatch(ChatConstProbPurgeTimeOutUsersSetsLogIn))
     {
      ChatTimeOutUsers();
     }
   }
 }


function ChatPurgeTimeOutAtLogOut($U_Room = "")
 {
  if ($U_Room != "")
   {
    if (ChatProbabilityMatch(ChatConstProbPurgeTimeOutMessChatLogOut))
     {
      ChatPurgeMessages();
     }

    if (ChatProbabilityMatch(ChatConstProbPurgeTimeOutUsersChatLogOut))
     {
      ChatTimeOutUsers();
     }
   }
  else
   {
    if (ChatProbabilityMatch(ChatConstProbPurgeTimeOutMessSetsLogOut))
     {
      ChatPurgeMessages();
     }

    if (ChatProbabilityMatch(ChatConstProbPurgeTimeOutUsersSetsLogOut))
     {
      ChatTimeOutUsers();
     }
   }
 }


function ChatUserRecall($U_NickSrc,&$U_Nick,&$U_PasswordMD5,&$U_Color,&$U_EMail,&$U_Gender,&$U_SelfNotes)
 {
  $NickHash = ChatNickToVisual ($U_NickSrc);
  $query =
  "select *".
  " from User".
  " where User_NickName = '".addslashes($U_NickSrc)."'".
  " or User_NickHash = '".addslashes($NickHash)."'";

  $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  if($userdata = mysql_fetch_array($userslist)) 
   {
    $U_Nick        = $userdata['User_NickName'];
    $U_PasswordMD5 = $userdata['User_Password'];
    $U_Color       = $userdata['User_Color'];
    $U_EMail       = $userdata['User_EMail'];
    $U_Gender      = $userdata['User_Gender'];
    $U_SelfNotes   = $userdata['User_SelfNotes'];

    return(true);
   }
  else
   {
    return(false);
   }
 }


function ChatUserAdd    ($U_Nick,$U_Password,$U_EMail,$U_Color,$U_Gender,$U_SelfNotes = "",$U_PasswordMD5 = "")
 {
  global $HTTP_SERVER_VARS;

  $U_AdmFlag = 0;

  if (ChatNickExists ($U_Nick))
   {
    return(false);
   }
  else
   {
    $U_NickHash = ChatNickToVisual ($U_Nick);

    if (strlen($U_SelfNotes) > ChatConstSelfNotesMaxLength)
     {
      $U_SelfNotes = substr($U_SelfNotes,0,ChatConstSelfNotesMaxLength);
     }

    $currTimeStamp = ChatMakeSQLTimeStampByTimeStamp();

    if ($U_Password != "")
     {
      $U_PasswordMD5 = md5(ChatLowToSpace($U_Password));
     }

    $query = 
    "Insert into User".
    " (User_NickName, User_Password, User_EMail, User_Color, User_Gender,".
     " User_RegDateTime, User_LogInDateTime, User_LoginsCount,".
     " User_StatStartDateTime,User_StatScoreBonus,User_StatPreLoginsCount,".
     " User_PasswdErrCount, User_NickHash, User_SelfNotes, User_AdminFlag) ".
    " values ('".
    addslashes(ChatLowToSpace($U_Nick))."','".
    addslashes(ChatLowToSpace($U_PasswordMD5))."','".
    addslashes(ChatLowToSpace($U_EMail))."','".
    addslashes(ChatLowToSpace($U_Color))."','".
    addslashes(ChatLowToSpace($U_Gender))."','".
    $currTimeStamp."','".
    $currTimeStamp."',".
    "0,".
    "'".$currTimeStamp."',".
    "0,".
    "0,".
    "0,'".
    addslashes($U_NickHash)."','".
    addslashes(ChatLowToSpace($U_SelfNotes))."',".
    $U_AdmFlag.")";

    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    $query =
     "insert into UserMaxEvent "
     ."("
     ."User_NickName,"
     ."User_MaxEventId,"
     ."User_LastRemoteIP"
     .")"
     ." values "
     ."("
     ."'".addslashes(ChatLowToSpace($U_Nick))."',"
     ."0".","
     ."'".addslashes($HTTP_SERVER_VARS["REMOTE_ADDR"])."'"
     .")";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
    return (true);
   }
 }

function ChatUserUpdate($U_NickSrc,$U_Nick,$U_Password,$U_Color,$U_EMail,$U_Gender,$U_SelfNotes = "",$U_PasswordMD5 = "")
 {
  if (!ChatNickEqual($U_NickSrc,$U_Nick))
   {
    $NickHash = ChatNickToVisual ($U_Nick);
   }
  else
   {
    $NickHash = "";
   }

  if (strlen($U_SelfNotes) > ChatConstSelfNotesMaxLength)
   {
    $U_SelfNotes = substr($U_SelfNotes,0,ChatConstSelfNotesMaxLength);
   }

  $query = "Update User Set";
  $ItemInsert = false;

  if ($U_Nick     != "")
   {
    if ($ItemInsert)
     {
      $query .= ",";
      $ItemInsert = false;
     }
    $query .= " User_NickName = '".addslashes($U_Nick)."'";
    $ItemInsert = true;
   }

  if ($NickHash     != "")
   {
    if ($ItemInsert)
     {
      $query .= ",";
      $ItemInsert = false;
     }
    $query .= " User_NickHash = '".addslashes($NickHash)."'";
    $ItemInsert = true;
   }

  if (($U_Password != "") || ($U_PasswordMD5 != ""))
   {
    if ($ItemInsert)
     {
      $query .= ",";
      $ItemInsert = false;
     }

    if ($U_Password != "")
     {
      $query .= " User_Password = '".addslashes(md5($U_Password))."'";
     }
    else
     {
      $query .= " User_Password = '".addslashes($U_PasswordMD5)."'";
     }

    $ItemInsert = true;
   }

  if ($U_Color    != "")
   {
    if ($ItemInsert)
     {
      $query .= ",";
      $ItemInsert = false;
     }
    $query .= " User_Color = '".addslashes($U_Color)."'";
    $ItemInsert = true;
   }

  if ($ItemInsert)
   {
    $query .= ",";
    $ItemInsert = false;
   }

  $query .= " User_EMail = '".addslashes($U_EMail)."'";
  $ItemInsert = true;

  if ($U_Gender   != "")
   {
    if ($ItemInsert)
     {
      $query .= ",";
      $ItemInsert = false;
     }
    $query .= " User_Gender = '".addslashes($U_Gender)."'";
    $ItemInsert = true;
   }

  if ($ItemInsert)
   {
    $query .= ",";
    $ItemInsert = false;
   }

  $query .= " User_SelfNotes = '".addslashes($U_SelfNotes)."'";
  $ItemInsert = true;

  $query .= " where User_NickName = '".addslashes($U_NickSrc)."'";
  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  if ($U_Nick != $U_NickSrc)
   {
    $query = "Update UserMaxEvent Set User_NickName = '".addslashes($U_Nick)."' where User_NickName = '".addslashes($U_NickSrc)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    $query = "Update Session Set User_NickName = '".addslashes($U_Nick)."' where User_NickName = '".addslashes($U_NickSrc)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    $query = "Update Mess Set Mess_FromNick = '".addslashes($U_Nick)."' where Mess_FromNick = '".addslashes($U_NickSrc)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    $query = "Update Mess Set Mess_ToNick = '".addslashes($U_Nick)."' where Mess_ToNick = '".addslashes($U_NickSrc)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    $query = "Update UserNotes Set UserNotes_FromNickName = '".addslashes($U_Nick)."' where UserNotes_FromNickName = '".addslashes($U_NickSrc)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    $query = "Update UserNotes Set UserNotes_ToNickName = '".addslashes($U_Nick)."' where UserNotes_ToNickName = '".addslashes($U_NickSrc)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    $query = "Update MessAdmLog Set MessAdmLog_FromNickName = '".addslashes($U_Nick)."' where MessAdmLog_FromNickName = '".addslashes($U_NickSrc)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    $query = "Update MessAdmLog Set MessAdmLog_ToNickName = '".addslashes($U_Nick)."' where MessAdmLog_ToNickName = '".addslashes($U_NickSrc)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    $query = "Update MessAdmLogMess Set Mess_FromNick = '".addslashes($U_Nick)."' where Mess_FromNick = '".addslashes($U_NickSrc)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    $query = "Update MessAdmLogMess Set Mess_ToNick = '".addslashes($U_Nick)."' where Mess_ToNick = '".addslashes($U_NickSrc)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    $query = "Update BanUser Set User_NickName = '".addslashes($U_Nick)."' where User_NickName = '".addslashes($U_NickSrc)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
   }

  return(true);
 }

function ChatUserDelete($ToNick)
 {
  if ($ToNick != "")
   {
    $query = "delete from User where User_NickName = '".addslashes($ToNick)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    $query = "delete from UserMaxEvent where User_NickName = '".addslashes($ToNick)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    $query = "delete from Session where User_NickName = '".addslashes($ToNick)."'";
    mysql_query($query); // ignore

    $query = "delete from Mess where (Mess_FromNick = '".addslashes($ToNick)."' AND Mess_Model = '9')";
    mysql_query($query); // ignore

    $query = "delete from Mess where (Mess_ToNick = '".addslashes($ToNick)."' AND Mess_Model = '9')";
    mysql_query($query); // ignore

    $query = "delete from UserNotes where UserNotes_FromNickName = '".addslashes($ToNick)."'";
    mysql_query($query); // ignore

    $query = "delete from UserNotes where UserNotes_ToNickName = '".addslashes($ToNick)."'";
    mysql_query($query); // ignore

    // MessADMLog keeped untouched

    $query = "delete from BanUser where User_NickName = '".addslashes($ToNick)."'";
    mysql_query($query); // ignore
   }

  return(true);
 }

function ChatGetRoomSID($RealNick,$RealRoom)
 {
  $query  =
  "select Session_Id".
  " from Session" .
  " where ".
  "     Session.User_NickName = '".addslashes($RealNick)."' ".
  " and Session.ChatRoom_Name = '".addslashes($RealRoom)."'";

  $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  if ($userdata = mysql_fetch_array($userslist)) 
   {
    return($userdata[Session_Id]);
   }
  else
   {
    return("");
   }
 }


function ChatLoginInfoWriteToUser_Text($Nick,$Room,$SID,$UserTopic = "")
 {
  $Result = "";

  $query = 
   "select * from User,UserMaxEvent"
  ." where "
  ." (UserMaxEvent.User_NickName = User.User_NickName)"
  ." AND "
  ." (User.User_NickName = '".addslashes($Nick)."')";

  $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  if ($userdata = mysql_fetch_array($userslist)) 
   {
    global $TextMainBootWelcomeMessStr;
    global $TextMainBootLoginsMessPrefixStr;
    global $TextMainBootLoginsMessPostfixStr;

    $mess = ''
            .$TextMainBootWelcomeMessStr
            .' ' 
            .$TextMainBootLoginsMessPrefixStr
            .$userdata[User_LoginsCount]
            .$TextMainBootLoginsMessPostfixStr;

    $query = ChatBuildNickRatingQuery(" User.User_NickName = '".addslashes($Nick)."'");
    $RatingQuery = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
    $RatingData = mysql_fetch_array($RatingQuery);

    if ($RatingData)
     {
      if ($RatingData[User_Rating] >= ChatMinRatingToDisplayAtLogin)
       {
        $mess .= " Рейтинг вашей активности:".$RatingData[User_Rating]."";
       }
     }
    
    $Result .= ChatClientPrintPrivat_Text($mess."<br>");

    if ($userdata[User_LoginsCount] <= 1)
     {
      $mess  = "";
      $mess .= " Если вы впервые в нашем чате, прочитайте пожалуйста ";
      $mess .= "<a href='/rules.htm' target='_blank'>правила</a>";
      $mess .= " (открывается в новом окне).";

      $Result .= ChatClientPrintPrivat_Text($mess."<br>");

      $mess  = "";
      $mess .= " О том как пользоваться чатом вы можете узнать прочитав ";
      $mess .= "<a href='/softuse.htm' target='_blank'>инструкции</a>";
      $mess .= " (открывается в новом окне).";

      $Result .= ChatClientPrintPrivat_Text($mess."<br>");

      $mess  = "";
      $mess .= " Желаем вам успехов!";

//    $Result .= ChatClientPrintPrivat_Text($mess."<br>");
     }

    if ($userdata[User_PasswdErrCount] > 0)
     {
      $mess = "ПРИМЕЧАНИЕ: За время вашего отсутствия".
              " было выполнено".$userdata[User_PasswdErrCount].
              " попытки регистрации с неверным паролем";

      $Result .= ChatClientPrintPrivat_Text($mess."<br>");
     }

    if (!ChatStopWordProtectNickLogInOk($Nick,$Room))
     {
      $mess = "АВТОЦЕНЗОР: В вашем нике обнаружены некорректные слова".
              " вы можете писать только приватно";

      $Result .= ChatClientPrintPrivat_Text($mess."<br>");
     }

    if ($UserTopic != "")
     {
      if (!ChatStopWordProtectNickTopicLogInOk($Nick,$Room,$UserTopic))
       {
        $mess = "АВТОЦЕНЗОР: В вашей теме дня обнаружены некорректные слова".
                " тема дня игнорировна";

        $Result .= ChatClientPrintPrivat_Text($mess."<br>");
       }
     }

    // Show locks

    global $HTTP_SERVER_VARS;

    $LockCheckNick = $Nick;
    $LockCheckIP   = $HTTP_SERVER_VARS["REMOTE_ADDR"];
    list($LockIPModel  ,$LockIPExpTime)   = ChatGetBanIPModelLowExt($LockCheckIP);
    list($LockNickModel,$LockNickExpTime) = ChatGetBanUserModelLowExt($LockCheckNick);

    if (!empty($LockIPModel))
     {
      include_once(dirname(__FILE__)."/"."support.inc.php");

      $LockModel   = $LockIPModel;
      $LockExpTime = $LockIPExpTime;
      $LockName    = "Для вашего IP адреса действует блокировка:";

      $LockDesc = ChatAdminBankModelDesc($LockModel);

      if (!empty($LockDesc))
       {
        list($LockDescEN,$LockDescRU) = explode(":",$LockDesc);
        $LockDesc = $LockDescRU;
       }

      if (empty($LockDesc))
       {
        $LockDesc = $LockModel;
       }

      $LockExpTime = ChatViewSecoundsAsText($LockExpTime);

      $mess = $LockName."[".$LockDesc."]"." (время до снятия:".$LockExpTime.")";
      $Result .= ChatClientPrintPrivat_Text($mess."<br>");
     }

    if (!empty($LockNickModel))
     {
      include_once(dirname(__FILE__)."/"."support.inc.php");

      $LockModel   = $LockNickModel;
      $LockExpTime = $LockNickExpTime;
      $LockName    = "Для вашего ника действует блокировка:";

      $LockDesc = ChatAdminBankModelDesc($LockModel);

      if (!empty($LockDesc))
       {
        list($LockDescEN,$LockDescRU) = explode(":",$LockDesc);
        $LockDesc = $LockDescRU;
       }

      if (empty($LockDesc))
       {
        $LockDesc = $LockModel;
       }

      $LockExpTime = ChatViewSecoundsAsText($LockExpTime);

      $mess = $LockName."[".$LockDesc."]"." (время до снятия:".$LockExpTime.")";
      $Result .= ChatClientPrintPrivat_Text($mess."<br>");
     }
   }

  return($Result);
 }


function ChatLogInMessSend
            ($Room,$U_Gender,$U_Color,$RealNick)
 {
  if      ($U_Gender == ChatConstUserGenderMale)
   {
    ChatOneMessSend
     ($Room,"подключился к чату", $U_Color,"Только что", 
      $RealNick, ChatConstMessModelJoin);
   }
  else if ($U_Gender == ChatConstUserGenderFemale)
   {
    ChatOneMessSend
     ($Room,"подключилась к чату", $U_Color,"Только что", 
      $RealNick, ChatConstMessModelJoin);
   }
  else
   {
    ChatOneMessSend
     ($Room,"подключилось к чату", $U_Color,"Только что", 
      $RealNick, ChatConstMessModelJoin);
   }
 }


function ChatRegInMessSend
            ($Room,$U_Gender,$U_Color,$RealNick)
 {
  if      ($U_Gender == ChatConstUserGenderMale)
   {
    ChatOneMessSend
     ($Room,"зарегистрировался и присоединился к нам - `11`", 
      $U_Color,"Только что", 
      $RealNick, ChatConstMessModelJoin);
   }
  else if ($U_Gender == ChatConstUserGenderFemale)
   {
    ChatOneMessSend
     ($Room,"зарегистрировалась чтобы пообщаться с нами - `14`", 
      $U_Color,"Только что", 
      $RealNick, ChatConstMessModelJoin);
   }
  else
   {
    ChatOneMessSend
     ($Room,"зарегистрировалось и пожаловало к нам в чат - `1`", 
      $U_Color,"Только что", 
      $RealNick, ChatConstMessModelJoin);
   }
 }


function ChatLeaveMessSend
            ($Room,$U_Gender,$U_Color,$Nick)
 {
  if      ($U_Gender == ChatConstUserGenderMale)
   {
    ChatOneMessSend 
     ($Room,"в неизвестном направлении", $U_Color,"От нас ушел", 
      $Nick, ChatConstMessModelLeave);
    }
   else if ($U_Gender == ChatConstUserGenderFemale)
    {
     ChatOneMessSend 
      ($Room,"в неизвестном направлении", $U_Color,"От нас ушла", 
       $Nick, ChatConstMessModelLeave);
    }
   else
    {
     ChatOneMessSend 
      ($Room,"в неизвестном направлении", $U_Color,"От нас ушло", 
       $Nick, ChatConstMessModelLeave);
    }
 }

function ChatDropMessSend
            ($Room,$U_Gender,$U_Color,$Nick)
 {
  if      ($U_Gender == ChatConstUserGenderMale)
   {
    ChatOneMessSend
        ($Room,
         "пропал в киберпространстве",
         $U_Color,
         "Собеседник",
         $Nick,
         ChatConstMessModelLeave);
   }
  else if ($U_Gender == ChatConstUserGenderFemale)
   {
    ChatOneMessSend
        ($Room,
         "пропала в киберпространстве",
         $U_Color,
         "Собеседница",
         $Nick,
         ChatConstMessModelLeave);
   }
  else
   {
    ChatOneMessSend
        ($Room,
         "пропало в киберпространстве",
         $U_Color,
         "Наше",
         $Nick,
         ChatConstMessModelLeave);
   }
 }


function ChatMakeFastBootStr($RealNick,$SID,$Room)
 {
  $FastBootResultStr = "";

  // Загрузка списка пользователей
  $FastBootResultStr .= ChatGetNLFull_Text($RealNick,$Room);

  // загрузка всех сообщений за последние N мин и все приватных
  $FastBootResultStr .= ChatGetM_All_Text($RealNick,$SID,$Room,0,false);

  // Получение идентификатора последнего сообщения в базе
  $FastBootResultStr .= ChatGetUI_Text();

  return($FastBootResultStr);
 }

function ChatBuildNickRatingQuery($AddWhereCondition)
 {
  $Result = "
   select 
    User.User_NickName,
    ROUND(
      (0
       +(IFNULL(User.User_SpentTimeSec,0) / (60*60))  * 50 -- очков за каждый час в чате
       +(IFNULL(User.User_LoginsCount,0))             * 35 -- очков за каждый логин

       +(IFNULL(UserMaxEvent_SendMessCount,0))        *  2  -- очко за отп.сообщ
       +(IFNULL(UserMaxEvent_RecvMessCount,0))        *  3  -- очко за пол.сообщ
       +(IFNULL(UserMaxEvent_SendMPvtCount,0))        *  1  -- очко за отп.приват
       +(IFNULL(UserMaxEvent_RecvMPvtCount,0))        *  2  -- очко за пол.приват

       -(IFNULL(User.User_WarnCount,0))               *  250  -- {-} за каждое предупреждение
       -(IFNULL(User.User_KicksCount,0))              *  750  -- {-} за каждое предупреждение

       +(POW(IFNULL(User.User_AdminFlag,0),1.5))      *  7500  -- за админуровень*
       +(TO_DAYS(NOW())-TO_DAYS(IFNULL(User.User_RegDateTime,NOW())))   *  5  -- очко за каждый день после регистрации
       -(TO_DAYS(NOW())-TO_DAYS(IFNULL(User.User_LogInDateTime,NOW()))) *  10  -- {-} за каждый день от момента последнего входа

      )/100
     )+IFNULL(User_StatScoreBonus,0) as User_Rating,
    (IFNULL(User.User_SpentTimeSec,0) / (60*60)) as User_SpentTimeInHours,
    (IFNULL(User.User_SpentTimeSec,0) / (60*60*24)) as User_SpentTimeInDays,
     (0
      +(IFNULL(UserMaxEvent_SendMessCount,0))  *  1  -- очко за отп.сообщ
      +(IFNULL(UserMaxEvent_RecvMessCount,0))  *  1  -- очко за пол.сообщ
      +(IFNULL(UserMaxEvent_SendMPvtCount,0))  *  1  -- очко за отп.приват
      +(IFNULL(UserMaxEvent_RecvMPvtCount,0))  *  1  -- очко за пол.приват
     ) as User_MessTrafic,
     (0
      +(IFNULL(UserMaxEvent_SendMessCount,0))  *  1  -- очко за отп.сообщ
      +(IFNULL(UserMaxEvent_RecvMessCount,0))  *  1  -- очко за пол.сообщ
      +(IFNULL(UserMaxEvent_SendMPvtCount,0))  *  1  -- очко за отп.приват
      +(IFNULL(UserMaxEvent_RecvMPvtCount,0))  *  1  -- очко за пол.приват
     ) / (IFNULL(User.User_SpentTimeSec,0) / (60)) as User_MessPerMin,
     IFNULL(User.User_SpentTimeSec,0) /
     (0
      +(IFNULL(UserMaxEvent_SendMessCount,0))  *  1  -- очко за отп.сообщ
      +(IFNULL(UserMaxEvent_RecvMessCount,0))  *  1  -- очко за пол.сообщ
      +(IFNULL(UserMaxEvent_SendMPvtCount,0))  *  1  -- очко за отп.приват
      +(IFNULL(UserMaxEvent_RecvMPvtCount,0))  *  1  -- очко за пол.приват
     ) as User_SecFor1Mess,
     User_LoginsCount,
     User_RegDateTime,
     TO_DAYS(NOW())-TO_DAYS(IFNULL(User_RegDateTime,NOW())) as User_LifetimeDays,
     TO_DAYS(NOW())-TO_DAYS(IFNULL(User_LogInDateTime,NOW())) as User_MuteDays,
     User_WarnCount,
     User_KicksCount,
     UserMaxEvent_SendMessCount,
     UserMaxEvent_RecvMessCount,
     UserMaxEvent_SendMPvtCount,
     UserMaxEvent_RecvMPvtCount
   from User,UserMaxEvent
   where (User.User_NickName = UserMaxEvent.User_NickName) ";

  if ($AddWhereCondition != "")
   {
    $Result .= " and (".$AddWhereCondition.")";
   }

  return($Result);
 }

?>
