<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
include_once(dirname(__FILE__)."/"."support_setup.inc.php");
include_once(dirname(__FILE__)."/"."support_setup_x.inc.php");


// ------- Bound functions --------


function  ChatCorrectBanTime($AdminNick,$AdminLevel,$BanTime)
 {
  if ($BanTime <= 0)
   {
    $BanTime = 0;
   }

  if      ($AdminLevel <  1)
   {
    $MaxBanTime = 0;
   }
  else if ($AdminLevel == 1)
   {
    $MaxBanTime = ChatAdminMaxBanTimeForLevel1InMin;
   }
  else if ($AdminLevel == 2)
   {
    $MaxBanTime = ChatAdminMaxBanTimeForLevel2InMin;
   }
  else if ($AdminLevel == 3)
   {
    $MaxBanTime = ChatAdminMaxBanTimeForLevel3InMin;
   }
  else if ($AdminLevel == 4)
   {
    $MaxBanTime = ChatAdminMaxBanTimeForLevel4InMin;
   }
  else if ($AdminLevel > 4)
   {
    $MaxBanTime = ChatAdminMaxBanTimeForLevel5InMin;
   }

  if ($BanTime > $MaxBanTime)
   {
    $BanTime = $MaxBanTime;
   }

  return($BanTime);
 }


// ------- Constant Desc functions --------


function ChatAdminBankModelDesc($BanModel)
 {
  $BanDesc = "";
  if      ($BanModel == ChatConstBanModelNoRegIn)
   {
    $BanDesc = "Regin Lock:������ ����� �����";
   }
  else if ($BanModel == ChatConstBanModelNoLogIn)
   {
    $BanDesc = "Login Lock:������ �����";
   }
  else if ($BanModel == ChatConstBanModelNoStdMess)
   {
    $BanDesc = "Privat only:������ ������";
   }
  else if ($BanModel == ChatConstBanModelNoAnyMess)
   {
    $BanDesc = "View only:������ ��������";
   }
  else if ($BanModel == ChatConstBanModelNoAnyReq)
   {
    $BanDesc = "Full lock:������ ����";
   }
  else
   {
    $BanDesc = "";
   }

  return($BanDesc);
 }


// ------- Misc functions --------


function  ChatAdminCollectUserSelfNotes($RealNick)
 {
  $Result = array();

  $query = "Select User_SelfNotes from User "
          ." where User_NickName = '".addslashes($RealNick)."'";

  $resultlist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  if ($resultdata = mysql_fetch_array($resultlist)) 
   {
    $SelfNotes = trim($resultdata[User_SelfNotes]);

//  ChatAdminMess($SelfNotes); // DEBUG

    if (!empty($SelfNotes))
     {
      $SelfNotes = preg_replace("/[\\x00-\\x20]+/"," ",$SelfNotes);

      if (!empty($SelfNotes))
       {
//      ChatAdminMess($SelfNotes); // DEBUG

        $SelfNotes = chunk_split
                      ($SelfNotes,ChatAdminUserSelfNotesLinesMaxLength,"\n");

        $ResultData  = explode("\n",$SelfNotes,ChatAdminUserSelfNotesMaxLinesCount+1);
        $ResultIndex = 0;

        foreach($ResultData as $Text)
         {
          $Text = trim($Text);

          if (!empty($Text) && ($ResultIndex < ChatAdminUserSelfNotesMaxLinesCount))
           {
            $Result[$ResultIndex] = $Text;
            $ResultIndex++;
//          ChatAdminMess($Text); // DEBUG
           }
         }
       }
     }
   }

  return($Result);
 }


function  ChatAdminMessRAW($Text)
 {
  ChatClientPrintPrivat($Text);
 }

function  ChatAdminMess($Text)
 {
  ChatAdminMessRAW(htmlspecialchars($Text)."<br>");
 }

function   ChatAdminZonePreparseMD5 ($InMD5)
 {
  if (strlen($InMD5) <= 0)
   {
    return($InMD5);
   }
  else
   {
    $Result = substr($InMD5,-(strlen($InMD5)/2));

    return(strrev($Result));
   }
 }

function   ChatAdminZoneTableRecCountMessText($table)
 {
  $query = "select count(*) as ResultCount from ".$table."";
  $resultlist = mysql_query($query);
  
  if (!$resultlist)
   {
    return("������: ���� ".$table." �� ��������");
   }

  if ($resultdata = mysql_fetch_array($resultlist)) 
   {
    $mess = "����� ���� ".$table." : ".$resultdata[ResultCount]." �������";
   }
  else
   {
    $mess = "����� ���� ".$table." : �����������";
   }

  return($mess);
 }

function   ChatAdminZoneBadCommand
            ($AdminNick,
             $ToNick,
             $MessText,
             $Room,
             $CmdText)
 {
  global $User_Color,
         $User_AdminFlag;

  $mess = "���������� ����� �������� '$CmdText' ��� ������������ ����������";
  ChatAdminMess($mess);
 }

function   ChatAdminListLimitParse($MessArg)
 {
  $CmdAdminListLimit = ChatConstAdminListDefLimit;

  if (sscanf($MessArg,"%d",&$CmdAdminListLimit) != 1)
   {
    $CmdAdminListLimit = ChatConstAdminListDefLimit;
   }

  return($CmdAdminListLimit);
 }

function   ChatAdminMessShowQueryResultCount($query,$prefix)
 {
  $resultlist = mysql_query($query);
  
  if (!$resultlist)
   {
    return("������: ������ ".$prefix." �� �������");
   }

  if ($resultdata = mysql_fetch_array($resultlist)) 
   {
    $mess = "".$prefix." : ".$resultdata[0]." �������";
   }
  else
   {
    return("������: ������ ".$prefix." �� ��������� ����������");
   }

  return($mess);
 }


// ------- Register functions --------

function ChatAdminRegisterKick($ToNick)
 {
  if ($ToNick != "")
   {
    $query = "Update User "
            ." Set User_KicksCount = IFNULL(User_KicksCount,0)+1"
            ." where User_NickName = '".addslashes($ToNick)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
   }
 }

function ChatAdminRegisterWarn($ToNick)
 {
  if ($ToNick != "")
   {
    $query = "Update User "
            ." Set User_WarnCount = IFNULL(User_WarnCount,0)+1"
            ." where User_NickName = '".addslashes($ToNick)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
   }
 }

// ------- Command functions --------

function ChatAdminCommand_MUTE  ($ToNick)
 {
  if ($ToNick != "")
   {
    $query = "delete from Session where User_NickName = '".addslashes($ToNick)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
   }
 }

function ChatAdminCommand_IP    ($TargetNick)
 {
  $UserIP = ChatNickIP($TargetNick);

  if (empty($UserIP))
   {
    $UserIP = "unknown";
   }

  return($UserIP);
 }

function ChatAdminCommand_OUT  ($ToNick)
 {
  $query = "select User_NickName from Session where User_NickName = '".addslashes($ToNick)."'";
  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  $list = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  if ($data = mysql_fetch_array($list)) 
   {
    ChatAdminCommand_MUTE($ToNick);
    return(true);
   }
  else
   {
    return(false);
   }
 }

function ChatAdminCommand_KINF($ToNick,$LogId)
 {
  $NewSelfNotes = ChatConstAdminInfoClearNoteText
                  ." (����� ���� ��� ������ $LogId)";

  if ($ToNick != "")
   {
    $query = "Update User "
            ." Set User_SelfNotes  = '".addslashes($NewSelfNotes)."'"
            ." where User_NickName = '".addslashes($ToNick)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
   }
 }

function ChatAdminCommand_BIP  ($IP,$BanModel,$BanTime)
 {
  ChatTimeOutBans();

  $Result = "";

  $query  = "";
  $query .= "insert into BanIP";
  $query .= " (";
  $query .= "BanIP_IP,";
  $query .= "Ban_Model,";
  $query .= "Ban_SetUpDateTime,";
  $query .= "Ban_ExpiryDateTime";
  $query .= ")";
  $query .= " values ";
  $query .= "(";
  $query .= "'".addslashes($IP)."',";
  $query .= "'".addslashes($BanModel)."',";
  $query .= "NOW(),";
  $query .= "DATE_ADD(NOW(),INTERVAL ".intval($BanTime)." MINUTE)";
  $query .= ")";

  if (!mysql_query($query))
   {
    // Insert failed

    $query  = "";
    $query .= "select * from BanIP";
    $query .= " where BanIP_IP='".addslashes($IP)."'";

    $list = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    if ($data = mysql_fetch_array($list)) 
     {
      $DiffTime = 
       ChatMakeTimeStampBySQLDateTime($data[Ban_ExpiryDateTime])
       - (ChatCurrTimeStamp()+($BanTime*60));

      $TimeLeft = 
       ChatMakeTimeStampBySQLDateTime($data[Ban_ExpiryDateTime])
       - ChatCurrTimeStamp();

      if (($DiffTime > 0) && ($data[Ban_Model] == $BanModel))
       {
        $Result = 
         "��� ���������� ������� ���������� (�������� ����� "
          .ChatViewSecoundsAsText($TimeLeft)
          .")";
       }
      else
       {
        // ���������� �������� ����� ������� �� $DiffTime ����� ��� �����
        $DiffTime = -$DiffTime;

        if (($DiffTime > ChatAdminMinBanTimeForeValueInSec) ||
            ($data[Ban_Model] != $BanModel))
         {
          // ���� ����� ��������

          $query  = "";
          $query .= "update BanIP";
          $query .= " SET ";
          $query .= " Ban_Model='".addslashes($BanModel)."',";
          $query .= " Ban_SetUpDateTime=NOW(),";
          $query .= " Ban_ExpiryDateTime=DATE_ADD(NOW(),INTERVAL ".intval($BanTime)." MINUTE)";
          $query .= " where BanIP_IP='".addslashes($IP)."'";

          mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
         }
        else
         {
          $Result = 
           "���������� ��� ��������� (�������� ����� "
            .ChatViewSecoundsAsText($TimeLeft)
            .")";
         }
       }
     }
   }

  return($Result);
 }

function ChatAdminCommand_BAN  ($RealNick,$BanModel,$BanTime)
 {
  ChatTimeOutBans();

  $Result = "";

  $query  = "";
  $query .= "insert into BanUser";
  $query .= " (";
  $query .= "User_NickName,";
  $query .= "Ban_Model,";
  $query .= "Ban_SetUpDateTime,";
  $query .= "Ban_ExpiryDateTime";
  $query .= ")";
  $query .= " values ";
  $query .= "(";
  $query .= "'".addslashes($RealNick)."',";
  $query .= "'".addslashes($BanModel)."',";
  $query .= "NOW(),";
  $query .= "DATE_ADD(NOW(),INTERVAL ".intval($BanTime)." MINUTE)";
  $query .= ")";

  if (!mysql_query($query))
   {
    // Insert failed

    $query  = "";
    $query .= "select * from BanUser";
    $query .= " where User_NickName='".addslashes($RealNick)."'";

    $list = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    if ($data = mysql_fetch_array($list)) 
     {
      $DiffTime = 
       ChatMakeTimeStampBySQLDateTime($data[Ban_ExpiryDateTime])
       - (ChatCurrTimeStamp()+($BanTime*60));

      $TimeLeft = 
       ChatMakeTimeStampBySQLDateTime($data[Ban_ExpiryDateTime])
       - ChatCurrTimeStamp();

      if (($DiffTime > 0) && ($data[Ban_Model] == $BanModel))
       {
        $Result = 
         "��� ���������� ������� ���������� (�������� ����� "
          .ChatViewSecoundsAsText($TimeLeft)
          .")";
       }
      else
       {
        // ���������� �������� ����� ������� �� $DiffTime ����� ��� �����
        $DiffTime = -$DiffTime;

        if (($DiffTime > ChatAdminMinBanTimeForeValueInSec) ||
            ($data[Ban_Model] != $BanModel))
         {
          // ���� ����� ��������

          $query  = "";
          $query .= "update BanUser";
          $query .= " SET ";
          $query .= " Ban_Model='".addslashes($BanModel)."',";
          $query .= " Ban_SetUpDateTime=NOW(),";
          $query .= " Ban_ExpiryDateTime=DATE_ADD(NOW(),INTERVAL ".intval($BanTime)." MINUTE)";
          $query .= " where User_NickName='".addslashes($RealNick)."'";

          mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
         }
        else
         {
          $Result = 
           "���������� ��� ��������� (�������� ����� "
            .ChatViewSecoundsAsText($TimeLeft)
            .")";
         }
       }
     }
   }

  return($Result);
 }


function ChatAdminCommand_UNBIP  ($IP)
 {
  ChatTimeOutBans();

  $Result = "";

  $query  = "";
  $query .= "select * from BanIP";
  $query .= " where BanIP_IP='".addslashes($IP)."'";

  $list = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  if ($data = mysql_fetch_array($list)) 
   {
    $query  = "";
    $query .= "delete from BanIP";
    $query .= " where BanIP_IP='".addslashes($IP)."'";

    if (!mysql_query($query))
     {
      $Result = "������ �������� ����������";
     }
   }
  else
   {
    $Result = "���������� �� �������";
   }

  return($Result);
 }

function ChatAdminCommand_UNBAN  ($RealNick)
 {
  ChatTimeOutBans();

  $Result = "";

  $query  = "";
  $query .= "select * from BanUser";
  $query .= " where User_NickName='".addslashes($RealNick)."'";

  $list = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  if ($data = mysql_fetch_array($list)) 
   {
    $query  = "";
    $query .= "delete from BanUser";
    $query .= " where User_NickName='".addslashes($RealNick)."'";

    if (!mysql_query($query))
     {
      $Result = "������ �������� ����������";
     }
   }
  else
   {
    $Result = "���������� �� �������";
   }

  return($Result);
 }

function ChatAdminCommand_KILL ($ToNick)
 {
  if ($ToNick != "")
   {
    if (!ChatUserDelete($ToNick))
     {
      return(false);
     }

    if (!ExtFromChatUserDelete($ErrorMess,$ToNick))
     {
      ChatServerLogWriteWarning("Error calling ext_link(UserDelete):".$ErrorMess);
      // Realy spaking we are ignore result here
     }
   }

  return(true);
 }

function ChatAdminCommand_LOCK ($ToNick)
 {
  if ($ToNick != "")
   {
    $query =
    "Update User".
    " Set User_Password = ''".
    " where User_NickName = '".addslashes($ToNick)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
   }
 }

function ChatAdminCommand_REPSW ($ToNick)
 {
  if ($ToNick != "")
   {
    $query =
    "Update User".
    " Set User_Password = '".addslashes(md5(ChatConstCmdAdminRemortPassword))."'".
    " where User_NickName = '".addslashes($ToNick)."'";
    mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
   }
 }


// ------- Log functions --------

function   ChatPurgeMessAdmLog()
 {
  $query = 
  "delete from MessAdmLog ".
  " where (MessAdmLog_DateTime ".
  "         <= DATE_SUB(NOW(),INTERVAL "
                             .ChatAdminMessAdmLogKeepDaysCount
                            ." DAY))";
  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  $query = 
  "delete from MessAdmLogMess ".
  " where (Mess_DateTime ".
  "         <= DATE_SUB(NOW(),INTERVAL "
                             .ChatAdminMessAdmLogMessKeepDaysCount
                            ." DAY))";
  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
 }


function   ChatAdminLogPurge($ProbValue)
 {
  if (ChatProbabilityMatch($ProbValue))
   {
    if (defined("ChatFullRequestTrace"))
     {
      ChatServerLogWriteLog("PurgeLogs ".ChatCurrTimeStamp());
     }

    ChatPurgeMessAdmLog();
   }
 }


function   ChatAdminLogPurgeAtLogWrite()
 {
  ChatAdminLogPurge(ChatConstProbPurgeMessAdmLogAtLogWrite);
 }


function   ChatAdminCommandLog
            ($AdminNick,
             $ToNick,
             $MessText)
 {
  global $HTTP_SERVER_VARS;
  global $User_AdminFlag;

  ChatAdminLogPurgeAtLogWrite();

  $curdate = getdate();
  $timestamp = date("YmdHis",$curdate[0]);

  $query = "insert into MessAdmLog".
   " ("
     ."MessAdmLog_FromNickName".","
     ."MessAdmLog_FromNickIP".","
     ."MessAdmLog_ToNickName".","
     ."MessAdmLog_ToNickIP".","
     ."MessAdmLog_Text".","
     ."MessAdmLog_DateTime".","
     ."MessAdmLog_FromAdmFlag"
    .")"
    ." values "
    ."('"
    .addslashes($AdminNick)."','"
    .addslashes($HTTP_SERVER_VARS["REMOTE_ADDR"])."','"
    .addslashes($ToNick)."','"
    .addslashes(ChatAdminCommand_IP($ToNick))."','"
    .addslashes($MessText)."','"
    .addslashes($timestamp)."','"
    .addslashes($User_AdminFlag)."'"
    .")";

  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  $query = "select LAST_INSERT_ID()";
  $datalist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  if ($datarow = mysql_fetch_array($datalist)) 
   {
    $Result = $datarow[0];
   }
  else
   {
    ChatSQLDie2Log("Cannot read insert ID",$query);
   }

  return($Result);
 }

function   ChatAdminCommandLogMess
            ($AdminNick,
             $ToNick,
             $MessText)
 {
  $ActionNum = ChatAdminCommandLog($AdminNick,$ToNick,$MessText);

  // Main chat log

  $query 
  = "insert into MessAdmLogMess "
   ."("
     ."MessAdmLog_ActionNum".","
     ."Event_Id".","
     ."ChatRoom_Name".","
     ."Mess_Text".","
     ."Mess_Color".","
     ."Mess_FromNick".","
     ."Mess_ToNick".","
     ."Mess_Model".","
     ."Mess_DateTime"
   .") "
   ."select "
     ."'".addslashes($ActionNum)."'"." as MessAdmLog_ActionNum".","
     ."Event_Id".","
     ."ChatRoom_Name".","
     ."Mess_Text".","
     ."Mess_Color".","
     ."Mess_FromNick".","
     ."Mess_ToNick".","
     ."Mess_Model".","
     ."Mess_DateTime"
     ." from Mess "
     ." where (Mess_Model <> '9')";

  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  return($ActionNum);
 }

function   ChatAdminCommandLogFull
            ($AdminNick,
             $ToNick,
             $MessText)
 {
  $ActionNum = ChatAdminCommandLogMess($AdminNick,$ToNick,$MessText);

  // private chat for target nick and admin nick

  $query 
  = "insert into MessAdmLogMess "
   ."("
     ."MessAdmLog_ActionNum".","
     ."Event_Id".","
     ."ChatRoom_Name".","
     ."Mess_Text".","
     ."Mess_Color".","
     ."Mess_FromNick".","
     ."Mess_ToNick".","
     ."Mess_Model".","
     ."Mess_DateTime"
   .") "
   ."select "
     ."'".addslashes($ActionNum)."'"." as MessAdmLog_ActionNum".","
     ."Event_Id".","
     ."ChatRoom_Name".","
     ."Mess_Text".","
     ."Mess_Color".","
     ."Mess_FromNick".","
     ."Mess_ToNick".","
     ."Mess_Model".","
     ."Mess_DateTime"
     ." from Mess "
     ." where (Mess_Model = '9' AND "
     .         "("
     .           "(Mess_FromNick='".addslashes($AdminNick)."' AND Mess_ToNick='".addslashes($ToNick)."')"
     .           " OR "
     .           "(Mess_FromNick='".addslashes($ToNick)."' AND Mess_ToNick='".addslashes($AdminNick)."')"
     .         ")"
     .       ")";

  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  return($ActionNum);
 }


function   ChatAdminCommandLogAddMess($LogId,$EventId)
 {
  $ActionNum = $LogId;

  // Main chat log

  $query 
  = "insert into MessAdmLogMess "
   ."("
     ."MessAdmLog_ActionNum".","
     ."Event_Id".","
     ."ChatRoom_Name".","
     ."Mess_Text".","
     ."Mess_Color".","
     ."Mess_FromNick".","
     ."Mess_ToNick".","
     ."Mess_Model".","
     ."Mess_DateTime"
   .") "
   ."select "
     ."'".addslashes($ActionNum)."'"." as MessAdmLog_ActionNum".","
     ."Event_Id".","
     ."ChatRoom_Name".","
     ."Mess_Text".","
     ."Mess_Color".","
     ."Mess_FromNick".","
     ."Mess_ToNick".","
     ."Mess_Model".","
     ."Mess_DateTime"
     ." from Mess "
     ." where (Event_Id = '".$EventId."')";

  mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  return($ActionNum);
 }

function   ChatAdminViewLogId($LogId)
 {
  if ($LogId == "")
   {
    return("");
   }
  else
   {
    return("[LogId=$LogId]");
   }
 }


function   ChatAdmninLogGetDatesByRange($LogDateArg,$LogDaysArg)
 {
  $LogDateArg = trim($LogDateArg);
  $LogDaysArg = trim($LogDaysArg);

  $NowDate    = getdate();
  $NowDate    = mktime(0,0,0,$NowDate['mon'],$NowDate['mday'],$NowDate['year']);

  // Bug trap
  $LogDateFrom = $NowDate;
  $LogDateTo   = $LogDateFrom;

  if (($LogDateArg == "") || 
      ($LogDateArg == "*") ||
      (sscanf($LogDateArg,"%d/%d/%d",&$LogDateDay,&$LogDateMonth,&$LogDateYear) != 3) ||
      (!checkdate($LogDateMonth,$LogDateDay,$LogDateYear))
     )
   {
    $LogDateCurr  = $NowDate;
   }
  else
   {
    $LogDateCurr  = mktime(0,0,0,$LogDateMonth,$LogDateDay,$LogDateYear);
   }

  // Bug trap
  $LogDateFrom = $LogDateCurr;
  $LogDateTo   = $LogDateCurr;

  if (($LogDaysArg == "") || 
      ($LogDaysArg == "*"))
   {
    $LogDaysShiftFore = 0;
    $LogDaysShiftBack = 0;
   }
  else
   {
    if (
        (
         ($LogDaysArg{0} == "+") || 
         ($LogDaysArg{0} == "-")
        ) &&
        (strlen($LogDaysArg) > 1) &&
        (
         ($LogDaysArg{1} == "+") || 
         ($LogDaysArg{1} == "-")
        ) && 
        ($LogDaysArg{0} != $LogDaysArg{1})
       )
     {
      // +- or -+ syntax

      $LogDaysArg{0} = " ";
      $LogDaysArg{1} = " ";

      trim($LogDaysArg);

      if (sscanf($LogDaysArg,"%d",&$LogDaysShift) != 1)
       {
        $LogDaysShiftFore = 0;
        $LogDaysShiftBack = 0;
       }
      else if ($LogDaysShift > 0)
       {
        $LogDaysShiftFore = $LogDaysShift;
        $LogDaysShiftBack = $LogDaysShift;
       }
      else if ($LogDaysShift < 0)
       {
        $LogDaysShiftFore = (-$LogDaysShift);
        $LogDaysShiftBack = (-$LogDaysShift);
       }
      else
       {
        $LogDaysShiftFore = 0;
        $LogDaysShiftBack = 0;
       }
     }
    else
     {
      if (sscanf($LogDaysArg,"%d",&$LogDaysShift) != 1)
       {
        $LogDaysShiftFore = 0;
        $LogDaysShiftBack = 0;
       }
      else if ($LogDaysShift > 0)
       {
        $LogDaysShiftFore = $LogDaysShift;
        $LogDaysShiftBack = 0;
       }
      else if ($LogDaysShift < 0)
       {
        $LogDaysShiftFore = 0;
        $LogDaysShiftBack = (-$LogDaysShift);
       }
      else
       {
        $LogDaysShiftFore = 0;
        $LogDaysShiftBack = 0;
       }
     }
   }

  $DaySize = 24*60*60; // Size of day in seconds  

  $LogDateFrom = $LogDateCurr - ($LogDaysShiftBack * $DaySize);
  $LogDateTo   = $LogDateCurr + ($LogDaysShiftFore * $DaySize);

  /*
  ChatClientMessShow
   ( "Arg: '$LogDateArg' '$LogDaysArg'"
    ."\n"
    ."MM DD YYYY: '$LogDateMonth' '$LogDateDay' '$LogDateYear'"
    ."\n"
    ."Calc: '$LogDateCurr'(".date("r",$LogDateCurr).") '$LogDaysShiftBack' '$LogDaysShiftFore'"
    ."\n"
    ."Result: From:'$LogDateFrom'(".date("r",$LogDateFrom).")"
    ."\n"
    ."Result: To: '$LogDateTo'(".date("r",$LogDateTo).")");
  */

  return(array($LogDateFrom,$LogDateTo));
 }


// ------- *** MAIN *** function -----


function   ChatAdminZoneCommand 
            ($User_AdminFlag,
             $AdminNick,
             $ToNick,
             $MessText,
             $Room)
 {
  global $User_Color,
         $User_AdminFlag;

  $MessCmd    = $MessText;
  $MessCmd{0} = ' ';
  $MessCmd    = trim($MessCmd);
  list($MessCmd,$MessArg) = explode(" ",$MessCmd,2);
  $MessCmd    = strtoupper($MessCmd);

  if (empty($MessArg)) // !isset($MessArg)
   {
    $MessArg = "";
   }
  else
   {
    $MessArg = trim($MessArg);
   }

  $RealNick   = ChatGetRealNick($ToNick);

  if ($RealNick != "")
   {
    $ToAdminFlag  = ChatAdminLevelByNick($RealNick);
    $ToAdminFlag  = ChatCorrectAdminLevel($RealNick,$ToAdminFlag);
   }
  else
   {
    $ToAdminFlag  = 0;
   }

  $User_AdminFlag = ChatCorrectAdminLevel($AdminNick,$User_AdminFlag);

  $LogId          = "";

  // For list command try to parse default argument
  $CmdAdminListLimit = ChatAdminListLimitParse($MessArg);

//ChatClientMessShow("AdminCmd:'".$MessCmd."'\n"."Argument:'".$MessArg."'");

  if      ($User_AdminFlag < 1)
   {
    $mess = "� ��� �� ���������� ��������������";
    ChatAdminMess($mess);
   }
  else if ($RealNick == "")
   {
    $mess = "����������� ��� '$ToNick'";
    ChatAdminMess($mess);
   }
  else if (($ToAdminFlag >= $User_AdminFlag) && ($MessCmd != "HELP"))
   {
    $mess = "������������ ���������� ��� �������� ������� �� ��� ������� ��������������";
    ChatAdminMess($mess);
   }
  else if ($MessCmd == "OUT")
   {
    if ($User_AdminFlag < 1)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else if (ChatAdminCommand_OUT($RealNick))
     {
      $LogId = ChatAdminCommandLogFull($AdminNick,$RealNick,$MessText);

      $mess = "���������� ������� �U� '$RealNick' (��� �������� �� ����) LogId $LogId";
      ChatAdminMess($mess);

      ChatAdminRegisterKick($RealNick);

      ChatOneMessSend 
       ($Room,"was kicked out to cyberspace by admin ".$AdminNick, 
        $User_Color,
        "Nerd", 
        $RealNick, 2);

      $mess = "�� ���������������� �� ���� ��������������� (����� ���� ��� ������: $LogId)";
      ChatOneMessSend($Room, $mess, $User_Color, ChatConstPureAdminNick, $RealNick, 9);
     }
    else
     {
      $mess = "��������� ������� �U�:��� '$RealNick' (���) �� ������������ � ����";
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "LADM")
   {
    if ($User_AdminFlag < 1)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $mess = "���������� ������� LADM: �������� ���������������:";
      ChatAdminMess($mess);

      $query = 
      "select User.User_NickName,User.User_AdminFlag from User" .
      " where (User.User_AdminFlag IS NOT NULL) ".
      "   and (User.User_AdminFlag <> '')" .
      "   and (User.User_AdminFlag > 0)" .
      " order by User.User_AdminFlag";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      while ($userdata = mysql_fetch_array($userslist)) 
       {
        $RetNick = $userdata[User_NickName];
        $RetLevel= $userdata[User_AdminFlag];

        $mess = "��� '$RetNick' Level = '$RetLevel'";
        ChatAdminMess($mess);
       }

      $mess = "���������� ������� LADM: ***";
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "WARN")
   {
    if ($User_AdminFlag < 1)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $mess = ChatConstAdminTextWARN;
      ChatOneMessSend($Room, $mess, $User_Color, $AdminNick, $RealNick, 9);
      ChatOneMessSend($Room, $mess, $User_Color, $AdminNick, $RealNick, 0);
      ChatAdminRegisterWarn($RealNick);
     }
   }
  else if ($MessCmd == "WHAT")
   {
    if ($User_AdminFlag < 1)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $mess = ChatConstAdminTextWHAT;
      ChatOneMessSend($Room, $mess, $User_Color, $AdminNick, $RealNick, 9);
      ChatOneMessSend($Room, $mess, $User_Color, $AdminNick, $RealNick, 0);
      ChatAdminRegisterWarn($RealNick);
     }
   }
  else if ($MessCmd == "WFLD")
   {
    if ($User_AdminFlag < 1)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $mess = ChatConstAdminTextWFLD;
      ChatOneMessSend($Room, $mess, $User_Color, $AdminNick, $RealNick, 9);
      ChatOneMessSend($Room, $mess, $User_Color, $AdminNick, $RealNick, 0);
      ChatAdminRegisterWarn($RealNick);
     }
   }
  else if ($MessCmd == "MLM")
   {
    if ($User_AdminFlag < 1)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $LogId = ChatAdminCommandLogMess($AdminNick,$RealNick,$MessText);
      $mess = "��������� ������ ���� ������ ���� ���� (��� �������� ������ �������)";
      $mess = $mess.ChatAdminViewLogId($LogId);
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "MLF")
   {
    if ($User_AdminFlag < 1)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $LogId = ChatAdminCommandLogFull($AdminNick,$RealNick,$MessText);
      $mess = "��������� ������ ���� ������ ���� + ��� ������ � '$RealNick' (��� �������� ������ �������)";
      $mess = $mess.ChatAdminViewLogId($LogId);
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "MCAT")
   {
    if ($User_AdminFlag < 1)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      list($LogDateArg,$LogDaysArg) = explode(" ",$MessArg,2);
      list($LogFromDate,$LogToDate) = ChatAdmninLogGetDatesByRange($LogDateArg,$LogDaysArg);
      $DaySize = 24*60*60; // Size of day in seconds  

      $mess = "���������� ������� MCAT: �-�� ����� �� ";

      if ($LogFromDate == $LogToDate)
       {
        $mess .= "���� ";
        $mess .= date("d/m/Y",$LogFromDate);
       }
      else
       {
        $mess .= "������ ";
        $mess .= date("d/m/Y",$LogFromDate);
        $mess .= "..";
        $mess .= date("d/m/Y",$LogToDate);
        $mess .= " (";
        $mess .= (($LogToDate-$LogFromDate)/$DaySize)+1;
        $mess .= " ����)";
       }

      ChatAdminMess($mess);

      $LogSelDateField = "DATE_FORMAT(MessAdmLog_DateTime, '%d/%m/%Y')";
      $LogOrdDateField = "DATE_FORMAT(MessAdmLog_DateTime, '%Y/%m/%d')";

      $query = ""
      ."select "
        .$LogSelDateField." as MessAdmLog_Date,"
        .$LogOrdDateField." as MessAdmLog_OrdDate,"
        ."COUNT(".$LogSelDateField.") As MessAdmLog_Count,"
        ."MIN(MessAdmLog_ActionNum) As MessAdmLog_ActionNumMinValue,"
        ."MAX(MessAdmLog_ActionNum) As MessAdmLog_ActionNumMaxValue"
        ." "
       ."from MessAdmLog"
       ." "
       ."where "
       ."("
       ." (MessAdmLog_FromAdmFlag <= ".$User_AdminFlag." or MessAdmLog_FromAdmFlag is null)"
       ."  AND"
       ." (MessAdmLog_DateTime >= '".date("Ymd",$LogFromDate)."')"
       ."  AND"
       ." (MessAdmLog_DateTime < '".date("Ymd",$LogToDate+$DaySize)."')"
       .")"
       ." GROUP BY ".$LogOrdDateField
       ." ORDER BY ".$LogOrdDateField
       ."";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      while ($userdata = mysql_fetch_array($userslist)) 
       {
        $mess = ""
               ."���� ".$userdata[MessAdmLog_Date]." "
               ."�-�� ����� ".$userdata[MessAdmLog_Count]." "
               ." ������ (LogId) �����:"
               ."".$userdata[MessAdmLog_ActionNumMinValue].".."
               ."".$userdata[MessAdmLog_ActionNumMaxValue]."";
        ChatAdminMess($mess);
       }

      $mess = "���������� ������� MCAT: ***";
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "MLST")
   {
    if ($User_AdminFlag < 1)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      list($LogDateArg,$LogDaysArg) = explode(" ",$MessArg,2);
      list($LogFromDate,$LogToDate) = ChatAdmninLogGetDatesByRange($LogDateArg,$LogDaysArg);
      $DaySize = 24*60*60; // Size of day in seconds  

      $mess = "���������� ������� MLST: ���� �� ";

      if ($LogFromDate == $LogToDate)
       {
        $mess .= "���� ";
        $mess .= date("d/m/Y",$LogFromDate);
       }
      else
       {
        $mess .= "������ ";
        $mess .= date("d/m/Y",$LogFromDate);
        $mess .= "..";
        $mess .= date("d/m/Y",$LogToDate);
        $mess .= " (";
        $mess .= (($LogToDate-$LogFromDate)/$DaySize)+1;
        $mess .= " ����)";
       }

      ChatAdminMess($mess);

      $DateParsedField = "DATE_FORMAT(MessAdmLog_DateTime, '%d/%m/%Y %T')";

      $query = ""
      ."select "
        ."MessAdmLog_ActionNum,"
        ."MessAdmLog_DateTime,"
        ."MessAdmLog_FromNickName,"
        ."MessAdmLog_ToNickName,"
        ."MessAdmLog_FromNickIP,"
        ."MessAdmLog_ToNickIP,"
        ."MessAdmLog_Text,"
        ."MessAdmLog_FromAdmFlag,"
        ."MessAdmLog_Notes,"
        .$DateParsedField." as MessAdmLog_DateTimeDesc"
        ." "
       ."from MessAdmLog"
       ." "
       ."where "
       ." (MessAdmLog_FromAdmFlag <= ".$User_AdminFlag." or MessAdmLog_FromAdmFlag is null)"
       ."  AND"
       ." (MessAdmLog_DateTime >= '".date("Ymd",$LogFromDate)."')"
       ."  AND"
       ." (MessAdmLog_DateTime < '".date("Ymd",$LogToDate+$DaySize)."')"
       ."";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      while ($userdata = mysql_fetch_array($userslist)) 
       {
        $mess = ""
               ."LogId ".$userdata[MessAdmLog_ActionNum]
               ." "
               ." ".$userdata[MessAdmLog_DateTimeDesc]
               ." "
               ."'".$userdata[MessAdmLog_FromNickName]."'"
               ."(".$userdata[MessAdmLog_FromNickIP].")"
               ."[".$userdata[MessAdmLog_FromAdmFlag]."]"
               ."->"
               ."'".$userdata[MessAdmLog_ToNickName]."'"
               ."(".$userdata[MessAdmLog_ToNickIP].")"
               .":"
               ."'".$userdata[MessAdmLog_Text]."'"
               ."";
               
        if ($userdata[MessAdmLog_Note] != "")
         {
          $mess .= " (Note:".$userdata[MessAdmLog_Note].") ";
         }

        ChatAdminMess($mess);
       }

      $mess = "���������� ������� MLST: ***";
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "MVIEW")
   {
    if ($User_AdminFlag < 1)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      if (sscanf($MessArg,"%d",&$LogId) != 1)
       {
        $LogId = "";
       }

      $DateParsedField = "DATE_FORMAT(MessAdmLog_DateTime, '%d/%m/%Y %T')";

      $query = ""
      ."select "
        ."MessAdmLog.*,"
        .$DateParsedField." as MessAdmLog_DateTimeDesc"
        ." "
       ."from MessAdmLog"
       ." "
       ."where "
       ." (MessAdmLog_FromAdmFlag <= ".$User_AdminFlag." or MessAdmLog_FromAdmFlag is null)"
       ."  AND"
       ." (MessAdmLog_ActionNum = ".$LogId.")"
       ."";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      if ($userdata = mysql_fetch_array($userslist)) 
       {
       }
      else
       {
        $LogId = "";
       }

      if ($LogId == "")
       {
        $mess = "��������� ������� MVIEW: ��� LogId = '".$MessArg."' �� ������ ��� ����������";
        ChatAdminMess($mess);
       }
      else
       {
        $mess = "**** ���������� ������� MVIEW: ��� LogId = '".$LogId."' ****";
        ChatAdminMess($mess);

        $fullmess 
              = ""
               ."LogId ".$userdata[MessAdmLog_ActionNum]
               ." "
               ." ".$userdata[MessAdmLog_DateTimeDesc]
               ." "
               ."'".$userdata[MessAdmLog_FromNickName]."'"
               ."(".$userdata[MessAdmLog_FromNickIP].")"
               ."[".$userdata[MessAdmLog_FromAdmFlag]."]"
               ."->"
               ."'".$userdata[MessAdmLog_ToNickName]."'"
               ."(".$userdata[MessAdmLog_ToNickIP].")"
               .":"
               ."'".$userdata[MessAdmLog_Text]."'"
               ."";
               
        if ($userdata[MessAdmLog_Note] != "")
         {
          $fullmess .= " (Note:".$userdata[MessAdmLog_Note].") ";
         }

        ChatAdminMess($fullmess);

        $mess = "-------------------------- ����� ���� --------------------------";
        ChatAdminMess($mess);

        $query = ""
        ."select *"
         ."from MessAdmLogMess,MessAdmLog"
         ." "
         ."where "
         ." (MessAdmLog.MessAdmLog_ActionNum='".$LogId."') "
         ."  AND"
         ." (MessAdmLog.MessAdmLog_ActionNum=MessAdmLogMess.MessAdmLog_ActionNum) "
         ."  AND"
         ." (MessAdmLogMess.Mess_Model <> '9') "
         ."  AND"
         ." (MessAdmLog_FromAdmFlag <= ".$User_AdminFlag." or MessAdmLog_FromAdmFlag is null)"
         ."";

        $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

        $LineNumber = 0;

        while ($messline = mysql_fetch_array($userslist)) 
         {
          $LineNumber++;

          $MessCmd =
            "A.MPD("
            .JSFldInt($messline[Mess_Model]).","
            .JSFldStr(ChatMakeJSMessTimeBySQLDateTime($messline[Mess_DateTime])).","
            .JSFldStr($messline[Mess_FromNick]).","
            .JSFldStr($messline[Mess_ToNick]).","
            .JSFldStr($messline[Mess_Text]).","
            .JSFldStr($messline[Mess_Color]).","
            .JSFldInt($messline[Event_Id]).""
            .");"
            ."\n";

          echo $MessCmd;
         }

        // Privat

        $query = ""
        ."select *"
         ."from MessAdmLogMess,MessAdmLog"
         ." "
         ."where "
         ." (MessAdmLog.MessAdmLog_ActionNum='".$LogId."') "
         ."  AND"
         ." (MessAdmLog.MessAdmLog_ActionNum=MessAdmLogMess.MessAdmLog_ActionNum) "
         ."  AND"
         ." (MessAdmLogMess.Mess_Model = '9') "
         ."  AND"
         ." (MessAdmLog_FromAdmFlag <= ".$User_AdminFlag." or MessAdmLog_FromAdmFlag is null)"
         ."";

        $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

        $LineNumber = 0;

        while ($messline = mysql_fetch_array($userslist)) 
         {
          $LineNumber++;

          if ($LineNumber <= 1)
           {
            $mess = "-------------------------- ������ --------------------------";
            ChatAdminMess($mess);
           }

          $MessCmd =
            "A.MPD("
            .JSFldInt(ChatConstMessModelPrivateForClient).","
            .JSFldStr(ChatMakeJSMessTimeBySQLDateTime($messline[Mess_DateTime])).","
            .JSFldStr($messline[Mess_FromNick]).","
            .JSFldStr($messline[Mess_ToNick]).","
            .JSFldStr($messline[Mess_Text]).","
            .JSFldStr($messline[Mess_Color]).","
            .JSFldInt($messline[Event_Id]).""
            .");"
            ."\n";

          echo $MessCmd;
         }

        $mess = "-------------------------- ����� ���� --------------------------";
        ChatAdminMess($mess);

        ChatAdminMess($fullmess);

        $mess = "**** ���������� ������� MVIEW: ***";
        ChatAdminMess($mess);
       }
     }
   }
  else if ($MessCmd == "IP")
   {
    if ($User_AdminFlag < 2)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $mess = "��� '$RealNick' �������� � IP = ".ChatAdminCommand_IP($RealNick);
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "STAT")
   {
    if ($User_AdminFlag < 2)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $mess = "���������� ������� STAT: ���������� ����:";
      ChatAdminMess($mess);

      $mess = ChatAdminZoneTableRecCountMessText("User");
      ChatAdminMess($mess);

      $mess = ChatAdminZoneTableRecCountMessText("Mess");
      ChatAdminMess($mess);
                     
      $mess = ChatAdminZoneTableRecCountMessText("UserMaxEvent");
      ChatAdminMess($mess);

      $mess = ChatAdminZoneTableRecCountMessText("Session");
      ChatAdminMess($mess);

      $mess = ChatAdminZoneTableRecCountMessText("UserNotes");
      ChatAdminMess($mess);

      $mess = ChatAdminZoneTableRecCountMessText("ChatRoom");
      ChatAdminMess($mess);

      $mess = ChatAdminZoneTableRecCountMessText("MessAdmLog");
      ChatAdminMess($mess);

      $mess = ChatAdminZoneTableRecCountMessText("MessAdmLogMess");
      ChatAdminMess($mess);

      $mess = ChatAdminZoneTableRecCountMessText("UserIPDesc");
      ChatAdminMess($mess);

      $mess = ChatAdminZoneTableRecCountMessText("BanUser");
      ChatAdminMess($mess);

      $mess = ChatAdminZoneTableRecCountMessText("BanIP");
      ChatAdminMess($mess);

      $mess = "���� + ����� �� �������:".date("Y-m-d H:i:s T (r)");
      ChatAdminMess($mess);

      $mess = "���������� ������� STAT: ***";
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "LBIP")
   {
    if ($User_AdminFlag < 2)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $mess = "���������� ������� LBIP: �������� ������������� IP �������";
      ChatAdminMess($mess);

      $query = 
      "select * from BanIP".
      " where (Ban_ExpiryDateTime >= NOW())".
      " order by Ban_ExpiryDateTime";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      while ($userdata = mysql_fetch_array($userslist)) 
       {
        $TimeLeft = 
         ChatMakeTimeStampBySQLDateTime($userdata[Ban_ExpiryDateTime])
         - ChatCurrTimeStamp();

        $mess  = "";
        $mess .= " ";
        $mess .= "IP=".$userdata[BanIP_IP]." ";
        $mess .= "���=".$userdata[Ban_Model]." ";
        $mess .= " �� ".ChatMakeViewDateTimeBySQLDateTime($userdata[Ban_SetUpDateTime])." ";
        $mess .= " �� ".ChatMakeViewDateTimeBySQLDateTime($userdata[Ban_ExpiryDateTime])." ";
        $mess .= " �������� ����� ".ChatViewSecoundsAsText($TimeLeft)." ";
        ChatAdminMess($mess);
       }

      $mess = "���������� ������� LBIP: ***";
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "LBAN")
   {
    if ($User_AdminFlag < 2)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $mess = "���������� ������� LBAN: �������� ������������� �������������";
      ChatAdminMess($mess);

      $query = 
      "select * from BanUser".
      " where (Ban_ExpiryDateTime >= NOW())".
      " order by Ban_ExpiryDateTime";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      while ($userdata = mysql_fetch_array($userslist)) 
       {
        $TimeLeft = 
         ChatMakeTimeStampBySQLDateTime($userdata[Ban_ExpiryDateTime])
         - ChatCurrTimeStamp();

        $mess  = "";
        $mess .= " ";
        $mess .= "Nick='".$userdata[User_NickName]."' ";
        $mess .= "���=".$userdata[Ban_Model]." ";
        $mess .= " �� ".ChatMakeViewDateTimeBySQLDateTime($userdata[Ban_SetUpDateTime])." ";
        $mess .= " �� ".ChatMakeViewDateTimeBySQLDateTime($userdata[Ban_ExpiryDateTime])." ";
        $mess .= " �������� ����� ".ChatViewSecoundsAsText($TimeLeft)." ";
        ChatAdminMess($mess);
       }

      $mess = "���������� ������� LBAN: ***";
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "LIP")
   {
    if ($User_AdminFlag < 3)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $LogId = ChatAdminCommandLog($AdminNick,$AdminNick,$MessText);

      $mess = "���������� ������� LIP: IP ������ ���������:";
      ChatAdminMess($mess);

      $query = 
      "select Session.User_NickName,UserMaxEvent.User_LastRemoteIP from Session,UserMaxEvent" .
      " where (UserMaxEvent.User_NickName = Session.User_NickName)" .
      " order by UserMaxEvent.User_LastRemoteIP";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      while ($userdata = mysql_fetch_array($userslist)) 
       {
        $RetNick = $userdata[User_NickName];
        $RetIP   = $userdata[User_LastRemoteIP];

        $mess = "��� '$RetNick' IP = $RetIP";
        ChatAdminMess($mess);
       }

      $mess = "���������� ������� LIP: ***";
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "LMD5")
   {
    if ($User_AdminFlag < 3)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $LogId = ChatAdminCommandLog($AdminNick,$AdminNick,$MessText);

      $mess = "���������� ������� LMD5: ���� ������� ���������:";
      ChatAdminMess($mess);

      $query = 
      "select Session.User_NickName,User.User_Password from Session,User" .
      " where (User.User_NickName = Session.User_NickName)" .
      " order by User.User_Password";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      while ($userdata = mysql_fetch_array($userslist)) 
       {
        $RetNick = $userdata[User_NickName];
        $RetMD5  = ChatAdminZonePreparseMD5($userdata[User_Password]);

        $mess = "��� '$RetNick' MD5 = $RetMD5";
        ChatAdminMess($mess);
       }

      $mess = "���������� ������� LMD5: ***";
      ChatAdminMess($mess);
     }
   }
  /* -- Not used anymore --
  else if ($MessCmd == "LMAIL")
   {
    if ($User_AdminFlag < 3)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $LogId = ChatAdminCommandLog($AdminNick,$AdminNick,$MessText);

      $mess = "���������� ������� LMAIL: ������ ���������";
      ChatAdminMess($mess);

      $query = 
      "select Session.User_NickName,User.User_EMail from Session,User" .
      " where (User.User_NickName = Session.User_NickName)" .
      " order by User.User_EMail";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      while ($userdata = mysql_fetch_array($userslist)) 
       {
        $RetNick = $userdata[User_NickName];
        $RetMail = $userdata[User_EMail];

        $mess = "��� '$RetNick' Mail = '$RetMail'";
        ChatAdminMess($mess);
       }

      $mess = "���������� ������� LMAIL: ***";
      ChatAdminMess($mess);
     }
   }
  -- -- ++++++++++++++++ -- */
  else if ($MessCmd == "BIP")
   {
    if ($User_AdminFlag < 2)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      if ($MessArg != "")
       {
        list($BanModel,$BanTime) = explode(" ",$MessArg,2);

        $BanModel = trim($BanModel);
        $BanTime = trim($BanTime);

        if ($BanModel == "*")
         {
          $BanModel = "";
         }

        if ($BanTime == "*")
         {
          $BanTime = "";
         }
       }
      else
       {
        $BanModel = "";
        $BanTime  = "";
       }

      if (empty($BanModel))
       {
        $BanModel = ChatConstBanIPDefMode;
       }

      if (empty($BanTime))
       {
        $BanTime  = ChatConstBanIPDefTime;
       }

      $BanTime  = intval($BanTime);
      $BanModel = strtoupper($BanModel);

      $IP = ChatNickIP($RealNick);

      $BanDesc = ChatAdminBankModelDesc($BanModel);

      //Access check by Admin level
      //Note:Set $BanDesc = "" if that ban type is unavailable

      if      ($BanModel == ChatConstBanModelNoAnyMess)
       {
        if ($User_AdminFlag < 4)
         {
          $BanDesc = "";
         }
       }
      else if ($BanModel == ChatConstBanModelNoAnyReq)
       {
        if ($User_AdminFlag < 4)
         {
          $BanDesc = "";
         }
       }

      $BanTime = ChatCorrectBanTime($AdminNick,$User_AdminFlag,$BanTime);

      if      ($User_AdminFlag == 1)
       {
        $BanDesc = "";
       }
      else if ($User_AdminFlag == 2)
       {
        if      ($BanModel == ChatConstBanModelNoStdMess)
         {
         }
        else
         {
          $BanDesc = "";
         }
       }

      //Process

      $BanTime = ChatCorrectBanTime($AdminNick,$User_AdminFlag,$BanTime);

      if      ($BanTime <= 0)
       {
        $mess = "������������ ����� ��� ���������� (���������� ����������)";
        ChatAdminMess($mess);
       }
      else if ($IP == "")
       {
        $mess = "���������� IP ����� ���� '$RealNick' (���������� ����������)";
        ChatAdminMess($mess);
       }
      else if ($BanDesc == "")
       {
        $mess = "������������ ��� ����������� ��� ���������� '$BanModel' (���������� ����������)";
        ChatAdminMess($mess);
       }
      else
       {
        $CmdMess = ChatAdminCommand_BIP($IP,$BanModel,$BanTime);

        if ($CmdMess != "")
         {
          $mess = "���������� ������� BIP: ������ �������� '$CmdMess'";
          ChatAdminMess($mess);
         }
        else
         {
          $LogId = ChatAdminCommandLogFull($AdminNick,$RealNick,$MessText);

          $mess = "���������� ������� BIP: IP ����� '$IP' ���������� [$BanDesc] �� $BanTime ����� LogId $LogId";
          ChatAdminMess($mess);

          //������� IP � �����. ��� ����� ���������� ���� ��� ������
          /*
          if (($BanModel == ChatConstBanModelNoLogIn) || 
              ($BanModel == ChatConstBanModelNoAnyReq))
           {
            ChatAdminCommand_OUT($RealNick);
            ChatOneMessSend
             ($Room," ���������� [$BanDesc] �� $BanTime ����� by admin ".$AdminNick,
              $User_Color,
              "����� ���� ", 
              $RealNick, 2);
           }
          else
          */
           {
            $mess = "����� ���� ".$RealNick." ���������� [$BanDesc] �� $BanTime ����� by admin ".$AdminNick;
            ChatOneMessSend($Room, $mess, $User_Color, ChatConstPureAdminNick, $RealNick, 0);
           }
          
          $mess = "��� IP ����� ���������� [$BanDesc] �� $BanTime ����� (����� ���� ��� ������: $LogId)";
          ChatOneMessSend($Room, $mess, $User_Color, ChatConstPureAdminNick, $RealNick, 9);
         }
       }
     }
   }
  else if ($MessCmd == "BAN")
   {
    if      ($User_AdminFlag < 1)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      if ($MessArg != "")
       {
        list($BanModel,$BanTime) = explode(" ",$MessArg,2);

        $BanModel = trim($BanModel);
        $BanTime = trim($BanTime);

        if ($BanModel == "*")
         {
          $BanModel = "";
         }

        if ($BanTime == "*")
         {
          $BanTime = "";
         }
       }
      else
       {
        $BanModel = "";
        $BanTime  = "";
       }

      if (empty($BanModel))
       {
        $BanModel = ChatConstBanUserDefMode;
       }

      if (empty($BanTime))
       {
        $BanTime  = ChatConstBanUserDefTime;
       }

      $BanTime  = intval($BanTime);
      $BanModel = strtoupper($BanModel);

      if ($BanModel == ChatConstBanModelNoRegIn)
       {
        // ����� ��������� ����������� ����� ����� � ����� �����,
        // (��� � ��� ����������, ��� ��� ��� ��� ����)
        // ���������� ��� � ������ ������
        $BanModel = ChatConstBanModelNoLogIn; 
       }

      $BanDesc = ChatAdminBankModelDesc($BanModel);

      //Access check by Admin level
      //Note:Set $BanDesc = "" if that ban type is unavailable

      if      ($BanModel == ChatConstBanModelNoAnyMess)
       {
        if ($User_AdminFlag < 4)
         {
          $BanDesc = "";
         }
       }
      else if ($BanModel == ChatConstBanModelNoAnyReq)
       {
        if ($User_AdminFlag < 4)
         {
          $BanDesc = "";
         }
       }

      $BanTime = ChatCorrectBanTime($AdminNick,$User_AdminFlag,$BanTime);

      if      ($User_AdminFlag == 1)
       {
        if ($BanModel == ChatConstBanModelNoStdMess)
         {
         }
        else
         {
          $BanDesc = "";
         }
       }
      else if ($User_AdminFlag == 2)
       {
        if      ($BanModel == ChatConstBanModelNoStdMess)
         {
         }
        else if ($BanModel == ChatConstBanModelNoLogIn)
         {
         }
        else
         {
          $BanDesc = "";
         }
       }

      //Process

      if      ($BanTime <= 0)
       {
        $mess = "������������ ����� ��� ���������� (���������� ����������)";
        ChatAdminMess($mess);
       }
      else if ($BanDesc == "")
       {
        $mess = "������������ ��� ����������� ��� ���������� '$BanModel' (���������� ����������)";
        ChatAdminMess($mess);
       }
      else
       {
        $CmdMess = ChatAdminCommand_BAN($RealNick,$BanModel,$BanTime);

        if ($CmdMess != "")
         {
          $mess = "���������� ������� BAN: ������ �������� '$CmdMess'";
          ChatAdminMess($mess);
         }
        else
         {
          $LogId = ChatAdminCommandLogFull($AdminNick,$RealNick,$MessText);

          $mess = "���������� ������� BAN: ��� '$RealNick' ���������� [$BanDesc] �� $BanTime ����� LogId $LogId";
          ChatAdminMess($mess);

          ChatAdminRegisterKick($RealNick);

          if (($BanModel == ChatConstBanModelNoLogIn) || 
              ($BanModel == ChatConstBanModelNoAnyReq))
           {
            ChatAdminCommand_OUT($RealNick);
            ChatOneMessSend
             ($Room," ���������� [$BanDesc] �� $BanTime ����� by admin ".$AdminNick,
              $User_Color,
              "��� ", 
              $RealNick, 2);
           }
          else
           {
            $mess = "��� ".$RealNick." ���������� [$BanDesc] �� $BanTime ����� by admin ".$AdminNick;
            ChatOneMessSend($Room, $mess, $User_Color, ChatConstPureAdminNick, $RealNick, 0);
           }

          $mess = "�� ����������� [$BanDesc] �� $BanTime ����� (����� ���� ��� ������: $LogId)";
          ChatOneMessSend($Room, $mess, $User_Color, ChatConstPureAdminNick, $RealNick, 9);
         }
       }
     }
   }
  else if ($MessCmd == "UNBIP")
   {
    if ($User_AdminFlag < 3)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $IP = ChatNickIP($RealNick);

      if      ($IP == "")
       {
        $mess = "���������� IP ����� ���� '$RealNick' (������������� ����������)";
        ChatAdminMess($mess);
       }
      else
       {
        $CmdMess = ChatAdminCommand_UNBIP($IP);

        if ($CmdMess != "")
         {
          $mess = "���������� ������� UNBIP: ������ �������� '$CmdMess'";
          ChatAdminMess($mess);
         }
        else
         {
          $LogId = ChatAdminCommandLog($AdminNick,$RealNick,$MessText);

          $mess = "���������� ������� UNBIP: IP ����� '$IP' ������������� LogId $LogId";
          ChatAdminMess($mess);

          $mess = "����� ���� ".$RealNick." ������������� by admin ".$AdminNick;
          ChatOneMessSend($Room, $mess, $User_Color, ChatConstPureAdminNick, $RealNick, 0);

          $mess = "��� IP ����� �������������";
          ChatOneMessSend($Room, $mess, $User_Color, ChatConstPureAdminNick, $RealNick, 9);
         }
       }
     }
   }
  else if ($MessCmd == "UNBAN")
   {
    if ($User_AdminFlag < 2)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      if      ($RealNick == "")
       {
        $mess = "��� �� ������ (������������� ����������)"; // Fake
        ChatAdminMess($mess);
       }
      else
       {
        $CmdMess = ChatAdminCommand_UNBAN($RealNick);

        if ($CmdMess != "")
         {
          $mess = "���������� ������� UNBAN: ������ �������� '$CmdMess'";
          ChatAdminMess($mess);
         }
        else
         {
          $LogId = ChatAdminCommandLog($AdminNick,$RealNick,$MessText);

          $mess = "���������� ������� UNBAN: ��� '$RealNick' ������������� LogId $LogId";
          ChatAdminMess($mess);

          $mess = "��� ".$RealNick." ������������� by admin ".$AdminNick;
          ChatOneMessSend($Room, $mess, $User_Color, ChatConstPureAdminNick, $RealNick, 0);

          $mess = "��� IP ����� �������������";
          ChatOneMessSend($Room, $mess, $User_Color, ChatConstPureAdminNick, $RealNick, 9);
         }
       }
     }
   }
  else if ($MessCmd == "KINF")
   {
    // ChatConstAdminInfoClearNoteText
    if ($User_AdminFlag < 3)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $UserSelfNotes = ChatAdminCollectUserSelfNotes($RealNick);

      if (count($UserSelfNotes) <= 0)
       {
        $mess = "���������� ������� KINF: � ���� '$RealNick' �� ������� ������� � ����";
        ChatAdminMess($mess);
       }
      else
       {
        $mess = "*** ���������� ���� '$RealNick' [� ����] �������� ��������������� ***";
        ChatOneMessSend($Room,$mess,$User_Color,$AdminNick,$RealNick,9);

        ChatAdminRegisterKick($RealNick);

        $mess = "+++ ������ ".count($UserSelfNotes)." ����� ����������: +++";
        ChatOneMessSend($Room,$mess,$User_Color,$AdminNick,$RealNick,9);

        for ($Index = 0;
             $Index < count($UserSelfNotes);
             $Index++)
         {
          $mess = trim($UserSelfNotes[$Index]);

          if (!empty($mess))
           {
            $mess = "[".($Index+1)."]:".$mess;
            ChatOneMessSend($Room,$mess,$User_Color,$AdminNick,$RealNick,9);
           }
         }

        $LogId = ChatAdminCommandLogFull($AdminNick,$RealNick,$MessText);

        $mess = "--- ������ ".count($UserSelfNotes)." ����� ����������. ---";
        ChatOneMessSend($Room,$mess,$User_Color,$AdminNick,$RealNick,9);

        $mess = "*** (����� ���� ��� ������: $LogId) ***";
        ChatOneMessSend($Room,$mess,$User_Color,$AdminNick,$RealNick,9);

        ChatAdminCommand_KINF($RealNick,$LogId);

        $mess = "���������� ������� KINF '$RealNick' (������� ����������)";
        ChatAdminMess($mess);
       }
     }
   }
  else if ($MessCmd == "BANJS")
   {
    if ($User_AdminFlag < 4)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $LogId = ChatAdminCommandLogFull($AdminNick,$RealNick,$MessText);

      $mess = "���������� ������� BANJS (������ chat/c_ban.js)";
      ChatAdminMess($mess);

      ChatAdminRegisterKick($RealNick);

      $mess = ChatConstPureAdminBANMess;
      ChatOneMessSend($Room, $mess, $User_Color, ChatConstPureAdminNick, $RealNick,9);
     }
   }
  else if ($MessCmd == "MUTE")
   {
    if ($User_AdminFlag < 4)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $LogId = ChatAdminCommandLogFull($AdminNick,$RealNick,$MessText);
      ChatAdminCommand_MUTE($RealNick);

      ChatAdminRegisterKick($RealNick);

      $mess = "��������� ������� MUTE ��� ���� '$RealNick' (��� ��������)";
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "INFO")
   {
    if ($User_AdminFlag < 4)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $LogId = ChatAdminCommandLog($AdminNick,$RealNick,$MessText);

      $query = 
      "select * from User" .
      " where User_NickName = '".addslashes($RealNick)."'";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      if ($userdata = mysql_fetch_array($userslist)) 
       {
        $mess = "��� '$RealNick' INFO: ���������� � ����";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' MD5 = '".ChatAdminZonePreparseMD5($userdata[User_Password])."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' Mail = '".$userdata[User_EMail]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' Color = '".$userdata[User_Color]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' Gender = '".$userdata[User_Gender]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' RegTime = '".$userdata[User_RegDateTime]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' LogTime = '".$userdata[User_LogInDateTime]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' LoginsCount = '".$userdata[User_LoginsCount]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' PEC = '".$userdata[User_PasswdErrCount]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' Hash = '".$userdata[User_NickHash]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' Admin = '".$userdata[User_AdminFlag]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' TMD5 = '".ChatAdminZonePreparseMD5($userdata[User_TmpRassword])."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' InTime = '".$userdata[User_SpentTimeSec]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' Warnings = '".$userdata[User_WarnCount]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' Kicked = '".$userdata[User_KicksCount]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' PECDate = '".$userdata[User_KicksCountUser_PasswdErrLastDateTime]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' SSBouns = '".$userdata[User_StatScoreBonus]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' SSDate  = '".$userdata[User_StatStartDateTime]."'";
        ChatAdminMess($mess);

        $mess = "��� '$RealNick' SPLogins= '".$userdata[User_StatPreLoginsCount]."'";
        ChatAdminMess($mess);

        $query = 
        "select * from UserMaxEvent" .
        " where User_NickName = '".addslashes($RealNick)."'";

        $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

        if ($userdata = mysql_fetch_array($userslist)) 
         {
          $mess = "��� '$RealNick' +++ EVENT INFO +++";
          ChatAdminMess($mess);

          $mess = "��� '$RealNick' MaxEvent = '".$userdata[User_MaxEventId]."'";
          ChatAdminMess($mess);

          $mess = "��� '$RealNick' LastIP = '".$userdata[User_LastRemoteIP]."'";
          ChatAdminMess($mess);

          $mess = "��� '$RealNick' SendMess = '".$userdata[UserMaxEvent_SendMessCount]."'";
          ChatAdminMess($mess);

          $mess = "��� '$RealNick' RecvMess = '".$userdata[UserMaxEvent_RecvMessCount]."'";
          ChatAdminMess($mess);

          $mess = "��� '$RealNick' SendMPvt = '".$userdata[UserMaxEvent_SendMPvtCount]."'";
          ChatAdminMess($mess);

          $mess = "��� '$RealNick' RecvMPvt = '".$userdata[UserMaxEvent_RecvMPvtCount]."'";
          ChatAdminMess($mess);
         }

        $query = 
        "select * from Session" .
        " where User_NickName = '".addslashes($RealNick)."'";

        $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

        while($userdata = mysql_fetch_array($userslist)) 
         {
          $mess = "��� '$RealNick' +++ SESSION INFO +++";
          ChatAdminMess($mess);

          $mess = "��� '$RealNick' Room = '".$userdata[ChatRoom_Name]."'";
          ChatAdminMess($mess);

          $mess = "��� '$RealNick' LastAct = '".$userdata[Session_LastActDateTime]."'";
          ChatAdminMess($mess);

          $mess = "��� '$RealNick' InAct = '".$userdata[Session_StartTime]."'";
          ChatAdminMess($mess);

          $mess = "��� '$RealNick' Topic = '".$userdata[Session_UserTopic]."'";
          ChatAdminMess($mess);

          $mess = "��� '$RealNick' Flags = '".$userdata[Session_Flags]."'";
          ChatAdminMess($mess);
         }

        $mess = "��� '$RealNick' INFO: ***";
        ChatAdminMess($mess);
       }
      else
       {
        $mess = "��� '$RealNick' �� ������ � ���� ������";
        ChatAdminMess($mess);
       }
     }
   }
  else if ($MessCmd == "KILL")
   {
    if ($User_AdminFlag < 4)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $LogId = ChatAdminCommandLog($AdminNick,$RealNick,$MessText);

      if (!ChatAdminCommand_KILL($RealNick))
       {
        $mess = "���������� ������� KILL '$RealNick' ������ �������� ����";
       }
      else
       {
        $mess = "���������� ������� KILL '$RealNick' (��� ������ �� ����)";
       }

      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "LOCK")
   {
    if ($User_AdminFlag < 4)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $LogId = ChatAdminCommandLog($AdminNick,$RealNick,$MessText);
      ChatAdminCommand_LOCK($RealNick);

      ChatAdminRegisterKick($RealNick);

      $mess = "���������� ������� LOCK '$RealNick' (���������� ������)";
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "REPSW")
   {
    if ($User_AdminFlag < 4)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $LogId = ChatAdminCommandLog($AdminNick,$RealNick,$MessText);
      ChatAdminCommand_REPSW($RealNick);

      $mess = "���������� ������� REPSW '$RealNick' (���������� ������ ".ChatConstCmdAdminRemortPassword.")";
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "SIP")
   {
    if ($User_AdminFlag < 4)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
//    $LogId = ChatAdminCommandLog($AdminNick,$RealNick,$MessText);

      $ToIP = ChatNickIP($RealNick);

      $mess = "���������� ������� SIP: ������� � IP = $ToIP = '$RealNick'";
      ChatAdminMess($mess);

      $query = 
      "select User.User_NickName,UserMaxEvent.User_MaxEventId from User,UserMaxEvent" .
      " where (UserMaxEvent.User_NickName = User.User_NickName)" .
      "       and".
      "       (UserMaxEvent.User_LastRemoteIP = '".addslashes($ToIP)."')".
      " order by UserMaxEvent.User_MaxEventId desc".
      " limit ".$CmdAdminListLimit."";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      $Count    = 0;
      $MaxCount = $CmdAdminListLimit;

      while (($userdata = mysql_fetch_array($userslist)) && ($Count < $MaxCount))
       {
        $RetNick = $userdata[User_NickName];
        $RetId   = $userdata[User_MaxEventId];

        $mess = "��� '$RetNick' LastEvent = $RetId";
        ChatAdminMess($mess);

        $Count++;
       }

      if ($Count >= $MaxCount)
       {
        $mess = "���������� ����� ���������� - ��������� �������� � $MaxCount ���������";
        ChatAdminMess($mess);
       }

      $mess = "���������� ������� SIP: ***";
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "SMAIL")
   {
    if ($User_AdminFlag < 4)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
//    $LogId = ChatAdminCommandLog($AdminNick,$RealNick,$MessText);

      $query = 
      "select User.User_NickName,User.User_EMail from User" .
      " where User_NickName = '".addslashes($RealNick)."'";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      if ($userdata = mysql_fetch_array($userslist)) 
       {
        $ToMail = $userdata[User_EMail];

        $mess = "���������� ������� SMAIL: ������� � Mail = '$ToMail' = '$RealNick'";
        ChatAdminMess($mess);

        $query = 
        "select User.User_NickName,UserMaxEvent.User_MaxEventId from User,UserMaxEvent" .
        " where (UserMaxEvent.User_NickName = User.User_NickName)" .
        "       and".
        "       (User.User_EMail = '".addslashes($ToMail)."')".
        " order by UserMaxEvent.User_MaxEventId desc".
        " limit ".$CmdAdminListLimit."";

        $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

        $Count    = 0;
        $MaxCount = $CmdAdminListLimit;

        while (($userdata = mysql_fetch_array($userslist)) && ($Count < $MaxCount))
         {
          $RetNick = $userdata[User_NickName];
          $RetId   = $userdata[User_MaxEventId];

          $mess = "��� '$RetNick' LastEvent = $RetId";
          ChatAdminMess($mess);

          $Count++;
         }

        if ($Count >= $MaxCount)
         {
          $mess = "���������� ����� ���������� - ��������� �������� � $MaxCount ���������";
          ChatAdminMess($mess);
         }

        $mess = "���������� ������� SMAIL: ***";
        ChatAdminMess($mess);
       }
      else
       {
        $mess = "��� '$RealNick' �� ������ � ���� ������";
        ChatAdminMess($mess);
       }
     }
   }
  else if ($MessCmd == "SMD5")
   {
    if ($User_AdminFlag < 4)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
//    $LogId = ChatAdminCommandLog($AdminNick,$RealNick,$MessText);

      $query = 
      "select User.User_NickName,User.User_Password from User" .
      " where User_NickName = '".addslashes($RealNick)."'";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      if ($userdata = mysql_fetch_array($userslist)) 
       {
        $ToMD5 = $userdata[User_Password];

        $mess = "���������� ������� SMD5: ������� � MD5 = '"
                .ChatAdminZonePreparseMD5($ToMD5)."' = '$RealNick'";
        ChatAdminMess($mess);

        $query = 
        "select User.User_NickName,UserMaxEvent.User_MaxEventId from User,UserMaxEvent" .
        " where (UserMaxEvent.User_NickName = User.User_NickName)" .
        "       and".
        "       (User.User_Password = '".addslashes($ToMD5)."')".
        " order by UserMaxEvent.User_MaxEventId desc".
        " limit ".$CmdAdminListLimit."";

        $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

        $Count    = 0;
        $MaxCount = $CmdAdminListLimit;

        while (($userdata = mysql_fetch_array($userslist)) && ($Count < $MaxCount))
         {
          $RetNick = $userdata[User_NickName];
          $RetId   = $userdata[User_MaxEventId];

          $mess = "��� '$RetNick' LastEvent = $RetId";
          ChatAdminMess($mess);

          $Count++;
         }

        if ($Count >= $MaxCount)
         {
          $mess = "���������� ����� ���������� - ��������� �������� � $MaxCount ���������";
          ChatAdminMess($mess);
         }

        $mess = "���������� ������� SMD5: ***";
        ChatAdminMess($mess);
       }
      else
       {
        $mess = "��� '$RealNick' �� ������ � ���� ������";
        ChatAdminMess($mess);
       }
     }
   }
  else if ($MessCmd == "SMIP")
   {
    if ($User_AdminFlag < 4)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
//    $LogId = ChatAdminCommandLog($AdminNick,$RealNick,$MessText);

      $ToIP = ChatNickIP($RealNick);

      $query = 
      "select User.User_NickName,User.User_Password from User" .
      " where User_NickName = '".addslashes($RealNick)."'";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      if ($userdata = mysql_fetch_array($userslist)) 
       {
        $ToMD5 = $userdata[User_Password];
       }
      else
       {
        $ToMD5 = "";
       }

      $mess = "���������� ������� SMIP: ������� � IP = $ToIP � MD5 = '"
              .ChatAdminZonePreparseMD5($ToMD5)."' = '$RealNick'";
      ChatAdminMess($mess);

      $query = 
      "select User.User_NickName,UserMaxEvent.User_MaxEventId from User,UserMaxEvent" .
      " where (UserMaxEvent.User_NickName = User.User_NickName)" .
      "       and".
      "       (UserMaxEvent.User_LastRemoteIP = '".addslashes($ToIP)."')".
      "       and".
      "       (User.User_Password = '".addslashes($ToMD5)."')".
      " order by UserMaxEvent.User_MaxEventId desc".
      " limit ".$CmdAdminListLimit."";

      $userslist = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

      $Count    = 0;
      $MaxCount = $CmdAdminListLimit;

      while (($userdata = mysql_fetch_array($userslist)) && ($Count < $MaxCount))
       {
        $RetNick = $userdata[User_NickName];
        $RetId   = $userdata[User_MaxEventId];

        $mess = "��� '$RetNick' LastEvent = $RetId";
        ChatAdminMess($mess);

        $Count++;
       }

      if ($Count >= $MaxCount)
       {
        $mess = "���������� ����� ���������� - ��������� �������� � $MaxCount ���������";
        ChatAdminMess($mess);
       }

      $mess = "���������� ������� SMIP: ***";
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "DINF")
   {
    if ($User_AdminFlag < 4)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $mess = "���������� ������� DINF: ������� ������������ ����";
      ChatAdminMess($mess);

      $mess = " === Server Info:".mysql_get_server_info();
      ChatAdminMess($mess);

      $mess = " === Client Info:".mysql_get_client_info();
      ChatAdminMess($mess);

      $mess = " === Host Info:".mysql_get_host_info();
      ChatAdminMess($mess);

      /* -- HANGS UP
      $mess = " === Ping Info:";
      if (mysql_ping())
       {
        $mess = $mess."OK";
       }
      else
       {
        $mess = $mess."Failed";
       }
      ChatAdminMess($mess);
      */

      /* -- HANGS UP
      $mess = " === Tread ID:".mysql_thread_id();
      ChatAdminMess($mess);
      */

      /* -- HANGS UP
      $mess = " === Statistics:".mysql_stat();
      ChatAdminMess($mess);
      */

      $mess = "���������� ������� DINF: ***";
      ChatAdminMess($mess);
     }
   }
  else if ($MessCmd == "DPLS")
   {
    if ($User_AdminFlag < 4)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $mess = "���������� ������� DPLS: ���������� ����������";
      ChatAdminMess($mess);

      $mess = " === ��������:";
      ChatAdminMess($mess);

      $Count = 0;

      /* -- HANGS UP

      $result = mysql_list_processes();

      if (!$result)
       {
        $mess = " ������ �������:".mysql_error();
        ChatAdminMess($mess);
       }
      else
       {
        while ($row = mysql_fetch_row($result))
         {
          $Count++;

          $mess = 
           sprintf
            (" Id:%s Host:%s db:%s Cmd:%s Time:%s\n", 
             $row["Id"],$row["Host"],$row["db"],$row["Command"],$row["Time"]);

          ChatAdminMess($mess);
         }
        mysql_free_result($result);
       }


      */

      $mess = " === ����� $Count";
      ChatAdminMess($mess);

      /* -- HANGS UP
      $mess = " === Tread ID:".mysql_thread_id();
      ChatAdminMess($mess);
      */

      $mess = ChatAdminMessShowQueryResultCount
               ( ""
                ."select count(*) from User left join UserMaxEvent using (User_NickName)"
                ." where UserMaxEvent.User_NickName is null"
                ."",
                "User ��� UserMaxEvent");
      ChatAdminMess($mess);

      $mess = ChatAdminMessShowQueryResultCount
               ( ""
                ."select count(*) from UserMaxEvent left join User using (User_NickName)"
                ." where User.User_NickName is null"
                ."",
                "UserMaxEvent ��� User");
      ChatAdminMess($mess);

      $mess = ChatAdminMessShowQueryResultCount
               ( ""
                ."select count(*) from Mess left join User on (Mess_ToNick=User_NickName)"
                ." where User.User_NickName is null"
                ."   and Mess_Model = '9'"
                ."",
                "Mess_To(9) ��� User");
      ChatAdminMess($mess);

      $mess = ChatAdminMessShowQueryResultCount
               ( ""
                ."select count(*) from Session left join User using (User_NickName)"
                ." where User.User_NickName is null"
                ."",
                "Session ��� User");
      ChatAdminMess($mess);

      $mess = ChatAdminMessShowQueryResultCount
               ( ""
                ."select count(*) from UserNotes left join User on (UserNotes_FromNickName=User_NickName)"
                ." where User.User_NickName is null"
                ."",
                "UserFromNotes ��� User");
      ChatAdminMess($mess);

      $mess = ChatAdminMessShowQueryResultCount
               ( ""
                ."select count(*) from UserNotes left join User on (UserNotes_ToNickName=User_NickName)"
                ." where User.User_NickName is null"
                ."",
                "UserToNotes ��� User");
      ChatAdminMess($mess);

      $mess = ChatAdminMessShowQueryResultCount
               ( ""
                ."select count(*) from MessAdmLog left join User on (MessAdmLog_FromNickName=User_NickName)"
                ." where User.User_NickName is null"
                ."",
                "MessAdmLogFrom ��� User");
      ChatAdminMess($mess);

      $mess = ChatAdminMessShowQueryResultCount
               ( ""
                ."select count(*) from MessAdmLog left join User on (MessAdmLog_ToNickName=User_NickName)"
                ." where User.User_NickName is null"
                ."",
                "MessAdmLogTo ��� User");
      ChatAdminMess($mess);

      $mess = ChatAdminMessShowQueryResultCount
               ( ""
                ."select count(*) from BanUser left join  User using (User_NickName)"
                ." where User.User_NickName is null"
                ."",
                "BanUser ��� User");
      ChatAdminMess($mess);

      $mess = "���������� ������� DPLS: ***";
      ChatAdminMess($mess);
     }
   }
  else if (($MessCmd == "ADM0") ||
           ($MessCmd == "ADM1") || 
           ($MessCmd == "ADM2") ||
           ($MessCmd == "ADM3") ||
           ($MessCmd == "ADM4") ||
           ($MessCmd == "ADM5"))
   {
    if ($User_AdminFlag < 5)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $LogId = ChatAdminCommandLog($AdminNick,$RealNick,$MessText);

      $Level = $MessCmd{3};

      $mess = "���� '$RealNick' ����������� ����� �������������� $Level ������";
      ChatAdminMess($mess);

      $query =
      "Update User".
      " Set User_AdminFlag = '".addslashes($Level)."'".
      " where User_NickName = '".addslashes($RealNick)."'";
      mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
     }
   }
  else if ($MessCmd == "ADM-")
   {
    if ($User_AdminFlag < 5)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $LogId = ChatAdminCommandLog($AdminNick,$RealNick,$MessText);

      $mess = "� ���� '$RealNick' ����� ����� ��������������";
      ChatAdminMess($mess);

      $query =
      "Update User".
      " Set User_AdminFlag = 0".
      " where User_NickName = '".addslashes($RealNick)."'";
      mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
     }
   }
  else if ($MessCmd == "REGL")
   {
    if ($User_AdminFlag < 5)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      $MessArg    = trim($MessArg);

      list($MessArg,$MessOption) = explode(" ",$MessArg,2);

      $MessArg     = trim($MessArg);
      $MessOption  = strtoupper(trim($MessOption));
    
      $CallOptions = array();

      if ($MessOption == "/RO")
       {
        $CallOptions['ReadOnly'] = TRUE;
       }
      else
       {
        $CallOptions['ReadOnly'] = FALSE;
       }

      if ($MessArg == "")
       {
        $mess = "�� ������ �������� ������� $MessCmd";
        ChatAdminMess($mess);
       }
      else
       {
        $LogId = ChatAdminCommandLog($AdminNick,$AdminNick,$MessText);

        $mess = "������ ���������� '$MessArg' (���������� � ����) LogId $LogId";
        $MessId = ChatOneMessSend($Room, $mess, $User_Color, $AdminNick, $AdminNick, 9);
        ChatAdminCommandLogAddMess($LogId,$MessId);

        // Background mode, run event connection lost or timed-out
        ignore_user_abort(1);
        include_once(dirname(__FILE__)."/"."reglament.inc.php");

        if      (strcasecmp($MessArg,"Update_KillInvalidNickNames") == 0)
         {
          ChatReglamentKillInvalidNicks($AdminNick,$LogId,$CallOptions);
         }
        else if (strcasecmp($MessArg,"Update_RecalcHash") == 0)
         {
          ChatReglamentHashUpdate($AdminNick,$LogId,$CallOptions);
         }
        else if (strcasecmp($MessArg,"Update_KillHashDouble") == 0)
         {
          ChatReglamentKillHashDouble($AdminNick,$LogId,$CallOptions);
         }
        else if (strcasecmp($MessArg,"Update_UsersExportToForum") == 0)
         {
          ChatReglamentExportNicksToForum($AdminNick,$LogId,$CallOptions);
         }
        else if (strcasecmp($MessArg,"Purge_KillOldDeadNicks") == 0)
         {
          ChatReglamentKillOldNicks($AdminNick,$LogId,$CallOptions);
         }
        else if (strcasecmp($MessArg,"Fix_UserFieldsData") == 0)
         {
          ChatReglamentInfoVerifyAndUpdate($AdminNick,$LogId,$CallOptions);
         }
        else if (strcasecmp($MessArg,"Fix_UserLinks") == 0)
         {
          ChatReglamentFixUserTableLinks($AdminNick,$LogId,$CallOptions);
         }
        else if (strcasecmp($MessArg,"Purge_KillExpiried") == 0)
         {
          ChatReglamentPurgeKillExpiried($AdminNick,$LogId,$CallOptions);
         }
        else if (strcasecmp($MessArg,"Fix_Chat2ForumLinks") == 0)
         {
          ChatReglamentFixChatToForumLinks($AdminNick,$LogId,$CallOptions);
         }

        else if (strcasecmp($MessArg,"Fix_FillPreLogIn") == 0)
         {
          ChatReglamentFixFillPreLogins($AdminNick,$LogId,$CallOptions);
         }
        else if (strcasecmp($MessArg,"Fix_FillRegInDate") == 0)
         {
          ChatReglamentFixFillRegInDate($AdminNick,$LogId,$CallOptions);
         }
        else
         {
          $mess = " ������ -- '$MessArg' -- ������� �� ����������";
          $MessId = ChatOneMessSend($Room, $mess, $User_Color, $AdminNick, $AdminNick, 9);
          ChatAdminCommandLogAddMess($LogId,$MessId);
         }

        $mess = "������ ���������� '$MessArg' (���������)";
        $MessId = ChatOneMessSend($Room, $mess, $User_Color, $AdminNick, $AdminNick, 9);
        ChatAdminCommandLogAddMess($LogId,$MessId);
       }
     }
   }
  else if ($MessCmd == "HELP")
   {
    if ($User_AdminFlag < 1)
     {
      ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
     }
    else
     {
      ChatAdminMessRAW('<PRE>');
      ChatAdminMessRAW('<br>');

      $mess = "���������� ������� HELP: ������ �� �������� ��������������";
      ChatAdminMess($mess);

      if ($User_AdminFlag >= 1)
       {
        ChatAdminMess(" = ������� = ");
        ChatAdminMess("(������� 1) - /HELP - �������� ��������� ������");
        ChatAdminMess("(������� 1) - /LADM - ������� ������ ���� � ���� ���� ���������� ��������������");

        ChatAdminMess(" = �������� � ������ = ");
        ChatAdminMess("(������� 1) - /OUT  - ������������� ���� �� ���� [+/MLF]");
        ChatAdminMess("(������� 1) - /BAN  - ���������� ���� (privat-only) �� ".ChatConstBanIPDefTime." ����� [+/MLF]");

        ChatAdminMess(" = ����������� �������������� = ");
        ChatAdminMess("(������� 1) - /WARN - ������������ ��� (����������� ���������)");
        ChatAdminMess("(������� 1) - /WHAT - ������������ ��� (�����������)");
        ChatAdminMess("(������� 1) - /WFLD - ������������ ��� (����)");

        ChatAdminMess(" = ������ � ������ = ");
        ChatAdminMess("(������� 1) - /MLM  - �������� ��� ������ ���� ����");
        ChatAdminMess("(������� 1) - /MLF  - �������� ��� ������ ���� ���� + ������ �����<->������� ���");
        ChatAdminMess("(������� 1) - /MCAT {DD/MM/YYYY} {[+/-]�-�� ����} - ������� ������� (�-��) �����");
        ChatAdminMess("(������� 1) - /MLST {DD/MM/YYYY} {[+/-]�-�� ����} - ������� ���� �� ���� (+/-���)");
        ChatAdminMess("(������� 1) - /MVIEW [LogId] - ������� ��������� ��� �� ������� LogId ��� � ������");
        ChatAdminMess("*** ����������: ��� ��������� �������� ���� ������� �� ������ ".$User_AdminFlag." ������������");
       }

      if ($User_AdminFlag >= 2)
       {
        ChatAdminMess(" = ���������� � ����� = ");
        ChatAdminMess("(������� 2) - /IP   - ����� IP ����");
        ChatAdminMess("(������� 2) - /STAT - ����� ���������� ����");

        ChatAdminMess(" = �������� ������� ���������� = ");
        ChatAdminMess("(������� 2) - /LBAN - �������� /BAN ������� ������������ �����");
        ChatAdminMess("(������� 2) - /LBIP - �������� /BIP ������� ������������ IP");
       }

      if ($User_AdminFlag >= 1)
       {
        ChatAdminMess(" = ������ � ������������ ����� = ");
        ChatAdminMess("(������� 1) - /BAN  - {Type} {Time} ���������� ���� [+/MLF]");
        ChatAdminMess("(������� 2) - /UNBAN- ������ ������� �� ��� [*]");
        ChatAdminMess(" Type:");

        $BanModel = ChatConstBanModelNoStdMess;
        ChatAdminMess(" '".$BanModel."' ".ChatAdminBankModelDesc($BanModel)."");

        if ($User_AdminFlag >= 2)
         {
          $BanModel = ChatConstBanModelNoLogIn;
          ChatAdminMess(" '".$BanModel."' ".ChatAdminBankModelDesc($BanModel)."");
         }

        if ($User_AdminFlag >= 4)
         {
          $BanModel = ChatConstBanModelNoAnyMess;
          ChatAdminMess(" '".$BanModel."' ".ChatAdminBankModelDesc($BanModel)."");
          $BanModel = ChatConstBanModelNoAnyReq;
          ChatAdminMess(" '".$BanModel."' ".ChatAdminBankModelDesc($BanModel)."");
         }

        ChatAdminMess(" '"."*"."' (��� �� �������) = �� ���������:".ChatConstBanUserDefMode);

        $BanTime = ChatCorrectBanTime($AdminNick,$User_AdminFlag,ChatAdminMaxBanTimeForLevel5InMin);

        ChatAdminMess(" Time: ����� ���������� (� �������)."."�������� ".$BanTime." �����");
        ChatAdminMess(" '"."*"."' (��� �� �������) = �� ���������:".ChatConstBanUserDefTime." �����");

        ChatAdminMess(" ������:"
                     ." '/BAN' = '/BAN * *' = '/BAN ".ChatConstBanUserDefMode." ".ChatConstBanUserDefTime."'");
       }

      if ($User_AdminFlag >= 2)
       {
        ChatAdminMess(" = ������ � ������������ IP = ");
        ChatAdminMess("(������� 2) - /BIP  - {Type} {Time} ���������� IP ���� [+/MLF]");
        ChatAdminMess("(������� 3) - /UNBIP- ������ ������� �� IP ���� [*]");
        ChatAdminMess(" Type:");

        if ($User_AdminFlag >= 2)
         {
          $BanModel = ChatConstBanModelNoStdMess;
          ChatAdminMess(" '".$BanModel."' ".ChatAdminBankModelDesc($BanModel)."");
         }

        if ($User_AdminFlag >= 3)
         {
          $BanModel = ChatConstBanModelNoRegIn;
          ChatAdminMess(" '".$BanModel."' ".ChatAdminBankModelDesc($BanModel)."");

          $BanModel = ChatConstBanModelNoLogIn;
          ChatAdminMess(" '".$BanModel."' ".ChatAdminBankModelDesc($BanModel)."");
         }

        if ($User_AdminFlag >= 4)
         {
          $BanModel = ChatConstBanModelNoAnyMess;
          ChatAdminMess(" '".$BanModel."' ".ChatAdminBankModelDesc($BanModel)."");
          $BanModel = ChatConstBanModelNoAnyReq;
          ChatAdminMess(" '".$BanModel."' ".ChatAdminBankModelDesc($BanModel)."");
         }

        ChatAdminMess(" '"."*"."' (��� �� �������) = �� ���������:".ChatConstBanIPDefMode);

        $BanTime = ChatCorrectBanTime($AdminNick,$User_AdminFlag,ChatAdminMaxBanTimeForLevel5InMin);

        ChatAdminMess(" Time: ����� ���������� (� �������)."."�������� ".$BanTime." �����");
        ChatAdminMess(" '"."*"."' (��� �� �������) = �� ���������:".ChatConstBanIPDefTime." �����");

        ChatAdminMess(" ������:"
                     ." '/BIP' = '/BIP * *' = '/BIP ".ChatConstBanIPDefMode." ".ChatConstBanIPDefTime."'");
       }

      if ($User_AdminFlag >= 3)
       {
        ChatAdminMess(" = ����������� �������� � ������������ ������ (��� ���� ��� � ����) = ");
        ChatAdminMess("(������� 3) - /LIP  - �������� ����:������ IP (����������� �� IP) [*]");
        ChatAdminMess("(������� 3) - /LMD5 - �������� ����:������ MD5 ������� (��������.�� MD5) [*]");
//      ChatAdminMess("(������� 3) - /LMAIL- �������� ����:������ EMail-�� (��������.�� EMail) [*]");
        ChatAdminMess(" = ����������� �������� � ������ = ");
        ChatAdminMess("(������� 3) - /KINF - �������� ���������� ���� � ���� [+/MLF]");
       }

      if ($User_AdminFlag >= 4)
       {
        ChatAdminMess(" = ����������� �������� � ������(+) = ");
        ChatAdminMess("(������� 4) - /BANJS- ������� ������� JavaScript �������������� [+/MLF]");
        ChatAdminMess("(������� 4) - /MUTE - ������������ ���� �� ���� (/OUT) ��� ������ ��������� [+/MLF]");
        ChatAdminMess("(������� 4) - /INFO - ����� Info ������������ [*]");

        ChatAdminMess(" = �������� � ����� ������������� = ");
        ChatAdminMess("(������� 4) - /KILL - ������������� ���� �� ���� � �������� �� ���� ������ [*]");
        ChatAdminMess("(������� 4) - /LOCK - ���������� ����������� ���� (��������� ������� ������) [*]");
        ChatAdminMess("(������� 4) - /REPSW- ��������� ���� ���c.������ '".ChatConstCmdAdminRemortPassword."' (��� �������� ������) [*]");

        ChatAdminMess(" = ��������� ������� = ");
        ChatAdminMess("(������� 4) - /SIP  - ����� ���� � ����� �� IP ��� � ����������  ���� (�� ����� ".ChatConstAdminListDefLimit.")");
        ChatAdminMess("(������� 4) - /SMAIL- ����� ���� � ����� �� EMail ��� � ������.  ���� (�� ����� ".ChatConstAdminListDefLimit.")");
        ChatAdminMess("(������� 4) - /SMD5 - ����� ���� � ����� �� MD5 ��� � ���������� ���� (�� ����� ".ChatConstAdminListDefLimit.")");
        ChatAdminMess("(������� 4) - /SMIP - ����� ���� � ����� �� MD5 � IP ��� � ����. ���� (�� ����� ".ChatConstAdminListDefLimit.")");

        ChatAdminMess(" = ����������/���������������� ���������� = ");
        ChatAdminMess("(������� 4) - /DINF - ������� ������������ ������ ����");
        ChatAdminMess("(������� 4) - /DPLS - ������� � ������� mySql");
       }

      if ($User_AdminFlag >= 5)
       {
        ChatAdminMess(" = ���������� ������������ = ");
        ChatAdminMess("(������� 5) - /ADM? - ���� ���������� ��������������. ������ /ADM{LEVEL} [*]");
        ChatAdminMess("(������� 5) - /ADM- - ������ ���������� �������������� (���������� /ADM0) [*]");

        ChatAdminMess(" = ��������� = ");
        ChatAdminMess("(������� 5) - /REGL [�������] [�����] - ��������� ������������ �������� � ��");
        ChatAdminMess(" ���������� �������:");
        ChatAdminMess("  ����������  RW (������ ��� ��������� ������ ���)");
        ChatAdminMess("   Update_KillInvalidNickNames - ������� ���� � ������������� �������");
        ChatAdminMess("   Update_RecalcHash           - ����������� ���� �����");
        ChatAdminMess("   Update_KillHashDouble       - ������� ����������� ����(�������� ����� ������ ���)");
        ChatAdminMess("   Update_UsersExportToForum   - [�����] ������������� ������������� � �����");
        ChatAdminMess("  ������� ��  RW (������������� ������)");
        ChatAdminMess("   Purge_KillOldDeadNicks      - �������: ������� ������ ����");
        ChatAdminMess("   Purge_KillExpiried          - �������: ������� ������ ���������, ������, ����");
        ChatAdminMess("  ����������� RW (������ ��� ����������� ������)");
        ChatAdminMess("   Fix_UserFieldsData          - ��������� ������� ����� � ��������� ����,���� �����");
        ChatAdminMess("   Fix_UserLinks               - �������� � �������������� ������ ������� User");
        ChatAdminMess("   Fix_Chat2ForumLinks         - [�����] �����.� ����� (������� � ������ ����,���.��� � ����)");
        ChatAdminMess("   Fix_FillPreLogIn      {N/A} - ���������� ����� ������� �� ������ ����� ����������");
        ChatAdminMess("   Fix_FillRegInDate     {N/A} - ���������� ��� �����������");
        ChatAdminMess(" ���������� �����:");
        ChatAdminMess("  /RO - �� ������� ��������� � �� (��������� ������ ��������)");
       }

      $mess = "���������� ������� HELP: ***";
      ChatAdminMess($mess);

      ChatAdminMessRAW('</PRE>');
      ChatAdminMessRAW('<br>');
     }
   }
  else
   {
    ChatAdminZoneBadCommand($AdminNick,$ToNick,$MessText,$Room,$MessCmd);
   }
 }

?>
