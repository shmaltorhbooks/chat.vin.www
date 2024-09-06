<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
include_once(dirname(__FILE__)."/".'funcvalidate.inc.php');

define("ChatConstReglamentTimeLimitInMin",180);
define("ChatConstReglamentReportInterval",2500);
define("ChatConstReglamentIdleHugeTimeoutInMonth",12);


function ChatReglamentSetTimeLimit()
 {
  set_time_limit(ChatConstReglamentTimeLimitInMin*60); // In seconds
 }

function ChatReglamentPrint($AdminNick,$LogId,$MessStr)
 {
//echo $MessStr."<br>";
//return;

  $MessColor = ChatConstDefUserColor;
  $MessId = 
   ChatOneMessSend
    (ChatConstDefaultChatRoom,
     $MessStr,
     $MessColor,
     $AdminNick,$AdminNick,
     9);

  ChatAdminCommandLogAddMess($LogId,$MessId);

  // Use messages to show process indicator, because we run in deatached mode
  // ChatClientPrintPrivat($MessStr);
 }

function ChatReglamentSQLDie2Log($AdminNick,$LogId,$Mess,$query)
 {
  $FullMess = 
   "��������� ���������� ����������: ".$Mess." ".mysql_error()." ".$query;

  ChatReglamentPrint
   ($AdminNick,
    $LogId,
    $FullMess);

  ChatSQLDie2Log($Mess,$query);
 }

function ChatReglamentKillInvalidNicks($AdminNick,$LogId,$CallOptions)
 {
  ChatReglamentSetTimeLimit();

  $ReadOnlyFlag = $CallOptions['ReadOnly'];

  if ($ReadOnlyFlag)
   {
    $ROSign = " [R/O]";
   }
  else
   {
    $ROSign = "";
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** �������� ������������ ����� ***".$ROSign);

  $query = "Select User_NickName,User_NickHash from User";
  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  $L_Index = 0;
  $DeletedNiks = 0;
  $UpdatedNiks = 0;

  while ($userdata = mysql_fetch_array($userslist))
   {
    $NickHash = ChatNickToVisual($userdata[User_NickName]);

    if      (($userdata[User_NickName] == "") ||
             (!ChatStrNickStrValid($userdata[User_NickName])) ||
             ($NickHash == ""))
     {
      if (!$ReadOnlyFlag)
       {
        ChatUserDelete($userdata[User_NickName]);
       }

      $DeletedNiks++;

      ChatReglamentPrint
       ($AdminNick,$LogId,
        "[".$userdata[User_NickName]."]"
       ." ���������� - ������");
     }

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  if ($DeletedNiks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$DeletedNiks." ����� ����� �������");
   }

  if ($UpdatedNiks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$UpdatedNiks." ����� ����� ���������");
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** �����:".$L_Index." ������� ����������".$ROSign);
 }


function ChatReglamentHashUpdate($AdminNick,$LogId,$CallOptions)
 {
  ChatReglamentSetTimeLimit();

  $ReadOnlyFlag = $CallOptions['ReadOnly'];

  if ($ReadOnlyFlag)
   {
    $ROSign = " [R/O]";
   }
  else
   {
    $ROSign = "";
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** ���������� Hash ����� ***".$ROSign);

  $L_Index = 0;
  $DeletedNiks = 0;
  $UpdatedNiks = 0;

  $query = "Select User_NickName,User_NickHash from User";
  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    $NickHash = ChatNickToVisual($userdata[User_NickName]);

    if      ($NickHash == "")
     {
      if (!$ReadOnlyFlag)
       {
        ChatUserDelete($userdata[User_NickName]);
       }

      $DeletedNiks++;

      ChatReglamentPrint
       ($AdminNick,$LogId,
        "[".$userdata[User_NickName]."]"
       ." ����������[������ Hash] - ������");
     }
    else if ($NickHash != $userdata[User_NickHash])
     {
      if (!$ReadOnlyFlag)
       {
        $query = 
          "Update User "
         ." Set User_NickHash = '".addslashes($NickHash)."'"
         ." where User_NickName = '".addslashes($userdata[User_NickName])."'";
       }

      mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

      ChatReglamentPrint
       ($AdminNick,$LogId,
        "[".$userdata[User_NickName]."]"
       ." �������� ���");

      $UpdatedNiks++;
     }

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }
  
  if ($DeletedNiks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$DeletedNiks." ����� ����� �������");
   }

  if ($UpdatedNiks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$UpdatedNiks." ����� ����� ���������");
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** �����:".$L_Index." ������� ����������".$ROSign);
 }


function ChatRegmamentNickSimplfy($Nick)
 {
  $Result = $Nick;
  $Result = trim($Nick);
  $Result = str_replace('&' ,'+',$Result);
  $Result = str_replace('+' ,'+',$Result);
  $Result = str_replace('+' ,'+',$Result);
  $Result = str_replace('\'','+',$Result);
  $Result = str_replace('\"','+',$Result);
  $Result = str_replace('\\','+',$Result);
  $Result = str_replace('>' ,'+',$Result);
  $Result = str_replace('<' ,'+',$Result);
  $Result = trim($Result);
  return($Result);
 }


function ChatReglamentInfoVerifyAndUpdate($AdminNick,$LogId,$CallOptions)
 {
  ChatReglamentSetTimeLimit();

  $ReadOnlyFlag = $CallOptions['ReadOnly'];

  if ($ReadOnlyFlag)
   {
    $ROSign = " [R/O]";
   }
  else
   {
    $ROSign = "";
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** ���������� ���������� ����� ***".$ROSign);

  $L_Index = 0;
  $FailedNiks  = 0;
  $DeletedNiks = 0;
  $UpdatedNiks = 0;

  $query = "Select * from User";
  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    $Failed = false;

    $UserNickName     = $userdata[User_NickName];
    $UserColor        = $userdata[User_Color];
    $UserEMail        = $userdata[User_EMail];
    $UserGender       = $userdata[User_Gender];
    $UserSelfNotes    = $userdata[User_SelfNotes];

    $NewUserNickName  = ArgAsStr($userdata[User_NickName]);
    $NewUserColor     = ArgAsStr($userdata[User_Color]);
    $NewUserEMail     = ArgAsStr($userdata[User_EMail]);
    $NewUserGender    = ArgAsStr($userdata[User_Gender]);
    $NewUserSelfNotes = ArgAsText($userdata[User_SelfNotes]);

    if (!$Failed)
     {
      if ((trim($NewUserNickName) == "") || 
          (!ChatStrNickStrValid($NewUserNickName)))
       {
        $NewUserNickName = trim(ChatRegmamentNickSimplfy($NewUserNickName));
       }
     }

    if (!$Failed)
     {
      if (ChatNickToVisual($UserNickName) != 
          ChatNickToVisual($NewUserNickName))
       {
        // If we try to update this nick, it's hash will be changed
        // we cannot do this in this procedure

        ChatReglamentPrint
         ($AdminNick,$LogId,
          "[".$UserNickName."]"
         ." ����������[����������� ���������� ����] - ����������� ���� - ���������� �������� �����");

        $FailedNiks++;
        $Failed = true;
       }
     }

    if (!$Failed)
     {
      if ((trim($NewUserNickName) == "") ||  
          (!ChatStrNickStrValid($NewUserNickName)))
       {
        ChatReglamentPrint
         ($AdminNick,$LogId,
          "[".$UserNickName."]"
         ." ����������[������������ ����� ��� ���� [".$NewUserNickName."]] - ���������� �������� �����");

        $FailedNiks++;
        $Failed = true;
       }
     }

    if (!$Failed)
     {
      if (ChatNickToVisual($NewUserNickName) != $userdata[User_NickHash])
       {
        ChatReglamentPrint
         ($AdminNick,$LogId,
          "[".$UserNickName."]"
         ." ������������ ���� - ��������� �������� ����");

        $FailedNiks++;
        $Failed = true;
       }
     }

    if (!$Failed)
     {
      $UpdateFlag = false;

      if (strlen($NewUserSelfNotes) > ChatConstSelfNotesMaxLength)
       {
        $NewUserSelfNotes = trim(substr($NewUserSelfNotes,0,ChatConstSelfNotesMaxLength));
       }

      if (!ChatCheckStrValidGender($NewUserGender))
       {
        $NewUserGender = ChatConstUserGenderUndefined;
       }

      if (!ChatCheckStrValidHTMLColor($NewUserColor))
       {
        $NewUserColor = ChatConstDefUserColor;
       }

      if (($NewUserEMail != "") && (!ChatCheckStrValidEMail($NewUserEMail)))
       {
        $NewUserEMail = "";
       }

      if ($UserNickName != $NewUserNickName)
       {
        ChatReglamentPrint
         ($AdminNick,$LogId,
          "[".$UserNickName."]"
         ." ������ ���� Nick"
         ." ������ [".$UserNickName."]"
         ." �� [".$NewUserNickName."]"
         );
        $UpdateFlag = true;
       }

      if ($UserColor != $NewUserColor)
       {
        ChatReglamentPrint
         ($AdminNick,$LogId,
          "[".$UserNickName."]"
         ." ������ ���� Color"
         ." ������ [".$UserColor."]"
         ." �� [".$NewUserColor."]"
         );
        $UpdateFlag = true;
       }

      if ($UserEMail != $NewUserEMail)
       {
        ChatReglamentPrint
         ($AdminNick,$LogId,
          "[".$UserNickName."]"
         ." ������ ���� EMail"
         ." ������ [".$UserEMail."]"
         ." �� [".$NewUserEMail."]"
         );
        $UpdateFlag = true;
       }

      if ($UserGender != $NewUserGender)
       {
        ChatReglamentPrint
         ($AdminNick,$LogId,
          "[".$UserNickName."]"
         ." ������ ���� Gender"
         ." ������ [".$UserGender."]"
         ." �� [".$NewUserGender."]"
         );
        $UpdateFlag = true;
       }

      if ($UserSelfNotes != $NewUserSelfNotes)
       {
        ChatReglamentPrint
         ($AdminNick,$LogId,
          "[".$UserNickName."]"
         ." ������ ���� SelfNotes"
         ." ������ strlen[".strlen($UserSelfNotes)."]"
         ." �� strlen[".strlen($NewUserSelfNotes)."]"
         );
        $UpdateFlag = true;
       }

      if ($UpdateFlag)
       {
        if (!$ReadOnlyFlag)
         {
          ChatUserUpdate
           ($UserNickName,
            $NewUserNickName,
            null,
            $NewUserColor,
            $NewUserEMail,
            $NewUserGender,
            $NewUserSelfNotes,
            null);
         }

        $UpdatedNiks++;
       }
     }

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }
  
  if ($DeletedNiks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$DeletedNiks." ����� ����� �������");
   }

  if ($UpdatedNiks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$UpdatedNiks." ����� ����� ���������");
   }

  if ($FailedNiks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$FailedNiks." ����� � �������� (����� ������ ���.����������)");
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** �����:".$L_Index." ������� ����������".$ROSign);
 }


function ChatReglamentKillHashDouble($AdminNick,$LogId,$CallOptions)
 {
  ChatReglamentSetTimeLimit();

  $ReadOnlyFlag = $CallOptions['ReadOnly'];

  if ($ReadOnlyFlag)
   {
    $ROSign = " [R/O]";
   }
  else
   {
    $ROSign = "";
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** �������� ���������� (������) ����� � �����-���������� ***".$ROSign);

  // ��� ��������� ��� ��� ��� � ���������

  /*

  $query = 
   "Select User_NickHash, User_NickName".
   " from User".
   " order by User_NickHash, User_LogInDateTime desc";

  */  

  $L_Index     = 0;
  $DeletedNiks = 0;
  $UpdatedNiks = 0;

  $query = 
  "select User.User_NickHash, User.User_NickName, UserMaxEvent.User_MaxEventId".
  " from User,UserMaxEvent".
  " where (UserMaxEvent.User_NickName = User.User_NickName)".
  " order by User_NickHash,UserMaxEvent.User_MaxEventId desc, User.User_LogInDateTime desc";
  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  $LastNickHash = "";
  $LastNickName = "";

  while ($userdata = mysql_fetch_array($userslist))
   {
    if ($userdata[User_NickHash] == "")
     {
      if (!$ReadOnlyFlag)
       {
        ChatUserDelete($userdata[User_NickName]);
       }

      $DeletedNiks++;

      ChatReglamentPrint
       ($AdminNick,$LogId,
        "[".$userdata[User_NickName]."]"
       ." ����������[������ Hash] - ������");
     }
    else
     {
      if (strcmp($LastNickHash, $userdata[User_NickHash]) != 0)
       {
        // Hash �������� ���� �� �������� � ����������.
        // ������ ������� ��� �������� ���������� Hash 
        // ���� ��� �������� ������� �� ��������� ����� 
        // ��������� � ���

        $LastNickName = $userdata[User_NickName];
       }
      else
       {
        // Hash �������� ���� �������� � ����������.
        // �������� - �������� ������ ������ ���, ��������� ����������

        if (!$ReadOnlyFlag)
         {
          ChatUserDelete($userdata[User_NickName]);
         }

        $DeletedNiks++;

        ChatReglamentPrint
         ($AdminNick,$LogId,
          "[".$userdata[User_NickName]."]"
         ." ����������:�������� ����[".$LastNickName."]"
         ." - ������");
       }

      $LastNickHash = $userdata[User_NickHash];
     }

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  if ($DeletedNiks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$DeletedNiks." ����� ����� �������");
   }

  if ($UpdatedNiks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$UpdatedNiks." ����� ����� ���������");
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** �����:".$L_Index." ������� ����������".$ROSign);
 }


function ChatReglamentKillOldNicks($AdminNick,$LogId,$CallOptions)
 {
  ChatReglamentSetTimeLimit();

  $ReadOnlyFlag = $CallOptions['ReadOnly'];

  if ($ReadOnlyFlag)
   {
    $ROSign = " [R/O]";
   }
  else
   {
    $ROSign = "";
   }

  $L_Index     = 0;
  $DeletedNiks = 0;
  $UpdatedNiks = 0;

  // Step1

  ChatReglamentPrint
   ($AdminNick,$LogId,"*** �������� ��������� ����� *** ".$ROSign);

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� ����� � 1 ������� � �� ��������� ���������� � ������� "
    .ChatConstReglamentIdleHugeTimeoutInMonth
    ." ��� [1]");

  $query = 
    "select User.User_NickName "
   ." from User left join UserMaxEvent using(User_NickName)"
   ." where "
   ." ( "
   ."  (User_LoginsCount <= 1) and"
   ."  (User_MaxEventId <= 0 or User_MaxEventId is null) and"
   ."  (User_LastRemoteIP = '' or User_LastRemoteIP is null) and"
   ."  (User_EMail = '' or User_EMail is null) and"
   ."  (LEAST(User_LogInDateTime,User_RegDateTime)"
   ."   <= DATE_SUB(NOW(),INTERVAL "
                         .ChatConstReglamentIdleHugeTimeoutInMonth
                         ." MONTH))"
   ." )"
   ."";
  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    if (!$ReadOnlyFlag)
     {
      ChatUserDelete($userdata[User_NickName]);
     }

    $DeletedNiks++;

    ChatReglamentPrint
     ($AdminNick,$LogId,
      "[".$userdata[User_NickName]."]"
     ." - ������");

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� [���������] ����� � 1 ������� (EventId = 0) 1..10 ���, Nick:XXXNNN [2]"
   );

  $query = 
    "select User.User_NickName"
   ."       from User left join UserMaxEvent using(User_NickName)"
   ." where "
   ."  (User_LoginsCount <= 1) and"
   ."  (User_MaxEventId <= 0 or User_MaxEventId is null) and"
   ."  (LEAST(User_LogInDateTime,User_RegDateTime) >= DATE_SUB(NOW(),INTERVAL 10 MONTH)) and"
   ."  (LEAST(User_LogInDateTime,User_RegDateTime) <= DATE_SUB(NOW(),INTERVAL 1 MONTH)) and"
   ."  (IF ( (User.User_NickName RLIKE '^.{2,20}[0-9]{1,3}$') or"
   ."        (User.User_NickName RLIKE '^[0-9]{2,3}.*$')"
   ."        ,"
   ."        1,"
   ."        0) = 1)"
   ."";
  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    if (!$ReadOnlyFlag)
     {
      ChatUserDelete($userdata[User_NickName]);
     }

    $DeletedNiks++;

    ChatReglamentPrint
     ($AdminNick,$LogId,
      "[".$userdata[User_NickName]."]"
     ." - ������");

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� ����� � 1 ������� � �� ��������� ���������� � ������� "
    .ChatConstReglamentIdleHugeTimeoutInMonth
    ." ��� [3]");

  $query = 
    "select User.User_NickName"
   ."       from User left join UserMaxEvent using(User_NickName)"
   ." where "
   ."  (User_LoginsCount <= 1) and"
   ."  (User_EMail = '' or User_EMail is null) and"
   ."  (GREATEST(User_LogInDateTime,User_RegDateTime) <= "
   ."            DATE_SUB(NOW(),INTERVAL ".ChatConstReglamentIdleHugeTimeoutInMonth." MONTH))"
   ."";
  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    if (!$ReadOnlyFlag)
     {
      ChatUserDelete($userdata[User_NickName]);
     }

    $DeletedNiks++;

    ChatReglamentPrint
     ($AdminNick,$LogId,
      "[".$userdata[User_NickName]."]"
     ." - ������");

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� ����� � 1 ������� � �� ���������� ������������� ������ [4]");

  $query = 
    "select User.User_NickName"
   ."       from User"
   ." where "
   ."  (User_LoginsCount <= 1) and"
   ."  (User_Color = '' or User_Color is null or User_Gender = '' or User_Gender is null)"
   ."";
  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    if (!$ReadOnlyFlag)
     {
      ChatUserDelete($userdata[User_NickName]);
     }

    $DeletedNiks++;

    ChatReglamentPrint
     ($AdminNick,$LogId,
      "[".$userdata[User_NickName]."]"
     ." - ������");

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  // Report

  if ($DeletedNiks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$DeletedNiks." ����� ����� �������");
   }

  if ($UpdatedNiks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$UpdatedNiks." ����� ����� ���������");
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** �����:".$L_Index." ������� ����������".$ROSign);
 }

function ChatReglamentFixChatToForumLinks($AdminNick,$LogId,$CallOptions)
 {
  ChatReglamentSetTimeLimit();

  $ReadOnlyFlag = $CallOptions['ReadOnly'];

  if ($ReadOnlyFlag)
   {
    $ROSign = " [R/O]";
   }
  else
   {
    $ROSign = "";
   }

  $L_Index       = 0;
  $AddRecords    = 0;
  $UpdateRecords = 0;
  $DelRecords    = 0;
  $FailedRecords = 0;

  // Step1

  ChatReglamentPrint($AdminNick,$LogId,"*** ������������� ������ � ������� *** ".$ROSign);

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� ���������� �� ������ [1]");

  ExtFromChatSystemGetStats
   ($ErrorMess,
    $SystemStats,
     false,
    $SystemData,
     true);

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� ������� ����� �� ������ � ����");

  foreach ($SystemData['*']['userlist'] as $NickName => $NickData)
   {
    $L_Index++;

    if (!ChatNickExists($NickName))
     {
      // Nick do not exist in chat - Delete nick on forum

      if ($ReadOnlyFlag)
       {
        ChatReglamentPrint($AdminNick,$LogId,"���:'".$NickName."' �� ������ � ���� - ������ ���� ������ �� ������");
        $DelRecords++;
       }
      else
       {
        if (!ExtFromChatUserDelete($ErrorMess,$NickName))
         {
          ChatReglamentPrint($AdminNick,$LogId,"���:'".$NickName."' �� ������ � ����. ������ �������� �� ������:".$ErrorMess);
          $FailedRecords++;
         }
        else
         {
          ChatReglamentPrint($AdminNick,$LogId,"���:'".$NickName."' �� ������ � ���� - ������ �� ������");
          $DelRecords++;
         }
       }

      if (($L_Index % ChatConstReglamentReportInterval) == 0)
       {
        ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
       }
     }
   }

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- ���������:");

  if ($AddRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$AddRecords." ������� ���������");
   }

  if ($UpdateRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$UpdateRecords." ������� ���������");
   }

  if ($DelRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$DelRecords." ������� �������");
   }

  if ($FailedRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$FailedRecords." ������� � ��������");
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** �����:".$L_Index." ������� ����������".$ROSign);
 }

function ChatReglamentExportNicksToForum($AdminNick,$LogId,$CallOptions)
 {
  ChatReglamentSetTimeLimit();

  $ReadOnlyFlag = $CallOptions['ReadOnly'];

  if ($ReadOnlyFlag)
   {
    $ROSign = " [R/O]";
   }
  else
   {
    $ROSign = "";
   }

  $L_Index       = 0;
  $FailedNi�ks   = 0;
  $AddNi�ks      = 0;
  $UpdateNi�ks   = 0;
  $SkipNi�ks     = 0;

  // Step1

  ChatReglamentPrint($AdminNick,$LogId,"*** ������� ����� � ����� *** ".$ROSign);

  $query = 
    "select * "
   ." from User"
   ."";
  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    $ExtResult = true;

    $AddNickFlag    = false;
    $UpdateNickFlag = false;

    if ($ExtResult)
     {
      $ExtResult = ExtFromChatUserGetStats
                    ($ErrorMess,
                      $userdata[User_NickName],
                      $UserExistsFlag,
                       true,
                      $UserStats,
                       false,
                      $UserDBRecord,
                       true);

      if      (!$ExtResult)
       {
        $FailedNi�ks++;
        ChatReglamentPrint($AdminNick,$LogId,"���:'".$userdata[User_NickName]."' ������ �������:".$ErrorMess);
       }
      else
       {
        if      (!$UserExistsFlag)
         {
          $AddNickFlag = true;
         }
        else
         {
          // Nick exist - verify filling

          if ($ExtResult)
           {
            if ((!is_array($UserDBRecord)) ||
                (count($UserDBRecord) <= 0) || 
                (count($UserDBRecord['*']) <= 0))
             {
              $ErrorMess = "����������� ������ ������ �� ������";
              $ExtResult = false;
             }
           }

          if ($ExtResult)
           {
            if (!isset($UserDBRecord['*'][username]))
             {
              $ErrorMess = "����������� ������ ������ ���� username";
              $ExtResult = false;
             }
            else
             {
              $ForumUserNickName = $UserDBRecord['*'][username];
             }
           }

          if ($ExtResult)
           {
            if (!isset($UserDBRecord['*'][user_email]))
             {
              $ErrorMess = "����������� ������ ������ ���� user_email";
              $ExtResult = false;
             }
            else
             {
              $ForumUserEMail = $UserDBRecord['*'][user_email];
             }
           }

          if ($ExtResult)
           {
            if (!isset($UserDBRecord['*'][user_password]))
             {
              $ErrorMess = "����������� ������ ������ ���� user_password(MD5)";
              $ExtResult = false;
             }
            else
             {
              $ForumUserPasswordMD5 = $UserDBRecord['*'][user_password];
             }
           }

          if (!$ExtResult)
           {
            $FailedNi�ks++;
            ChatReglamentPrint($AdminNick,$LogId,"���:'".$userdata[User_NickName]."' ������ ������:".$ErrorMess);
           }
          else
           {
            if ($userdata[User_NickName] != $ForumUserNickName)
             {
              $UpdateNickFlag = true;
              ChatReglamentPrint($AdminNick,$LogId,
               "���:'".$userdata[User_NickName]."' ���������� Nick"
              ." (���:'".$userdata[User_NickName]."'/�����:'".$ForumUserNickName."')");
             }

            if ($userdata[User_EMail] != $ForumUserEMail)
             {
              $UpdateNickFlag = true;
              ChatReglamentPrint($AdminNick,$LogId,
               "���:'".$userdata[User_NickName]."' ���������� EMail"
              ." (���:'".$userdata[User_EMail]."'/�����:'".$ForumUserEMail."')");
             }

            if ($userdata[User_Password] != $ForumUserPasswordMD5)
             {
              $UpdateNickFlag = true;
              ChatReglamentPrint($AdminNick,$LogId,
               "���:'".$userdata[User_NickName]."' ���������� PMD5"
              ." (���:'".$userdata[User_Password]."'/�����:'".$ForumUserPasswordMD5."')");
             }
           }
         }
       }
     }

    if ($ExtResult)
     {
      if      ($AddNickFlag)
       {
        if ($ReadOnlyFlag)
         {
          $ExtResult = ExtFromChatUserAddIsOk
                        ($ErrorMess,
                         $userdata[User_NickName],
                         $userdata[User_EMail]);
         }
        else
         {
          $ExtResult = ExtFromChatUserAdd
                        ($ErrorMess,
                         $userdata[User_NickName],
                          null,
                         $userdata[User_Password],
                         $userdata[User_EMail]);
         }

        if (!$ExtResult)
         {
          $FailedNi�ks++;
          ChatReglamentPrint($AdminNick,$LogId,"���:'".$userdata[User_NickName]."' ������ ����������:".$ErrorMess);
         }
        else
         {
          $AddNi�ks++;
         }
       }
      else if ($UpdateNickFlag)
       {
        if ($ReadOnlyFlag)
         {
          $ExtResult = true; // ignore
          $ErrorMess = "";
         }
        else
         {
          $ExtResult = ExtFromChatUserUpdate
                        ($ErrorMess,
                         $ForumUserNickName,
                         $userdata[User_NickName],
                          null,
                         $userdata[User_Password], // MD5, actualy
                         $userdata[User_EMail]);
         }

        if (!$ExtResult)
         {
          $FailedNi�ks++;
          ChatReglamentPrint($AdminNick,$LogId,"���:'".$userdata[User_NickName]."' ������ ����������:".$ErrorMess);
         }
        else
         {
          $UpdateNi�ks++;
         }
       }
      else
       {
        $SkipNi�ks++;
       }
     }

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  // Report

  if ($AddNi�ks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$AddNi�ks." ����� ���������");
   }

  if ($UpdateNi�ks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$UpdateNi�ks." ����� ���������");
   }

  if ($SkipNi�ks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$SkipNi�ks." ����� ��� ���������");
   }

  if ($FailedNi�ks > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$FailedNi�ks." ����� � ��������");
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** �����:".$L_Index." ������� ����������".$ROSign);
 }


function ChatReglamentFixUserTableLinks($AdminNick,$LogId,$CallOptions)
 {
  ChatReglamentSetTimeLimit();

  $ReadOnlyFlag = $CallOptions['ReadOnly'];

  if ($ReadOnlyFlag)
   {
    $ROSign = " [R/O]";
   }
  else
   {
    $ROSign = "";
   }

  $L_Index       = 0;
  $AddRecords    = 0;
  $UpdateRecords = 0;
  $DelRecords    = 0;
  $FailedRecords = 0;

  // Step1

  ChatReglamentPrint($AdminNick,$LogId,"*** �������������� ������ �� User *** ".$ROSign);

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- ���������� '���������' UserMaxEvent ��� User [1]");

  $query = ""
    ."select "
    ."  User.User_NickName as User_NickName,"
    ."  0 as User_MaxEventId"
    ." from User left join UserMaxEvent using (User_NickName)"
    ." where UserMaxEvent.User_NickName is NULL";

  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    ChatReglamentPrint
     ($AdminNick,
      $LogId,
      "������������ UserMaxEvent ��� ".
      "[".$userdata[User_NickName]."]");

    if ($ReadOnlyFlag)
     {
     }
    else
     {
      $query = ""
       ."insert into UserMaxEvent "
       ." ("
       ."  User_NickName,"
       ."  User_MaxEventId"
       ." )"
       ." values "
       ." ("
       ."  ".SQLFldStr($userdata[User_NickName]).","
       ."  ".SQLFldInt(0).""
       ." )"
       ."";

      mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);
     }

    $AddRecords++;

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� '���������' UserMaxEvent ��� User [2]");

  $query = ""
    ."select "
    ."  UserMaxEvent.User_NickName as User_NickName"
    ." from UserMaxEvent left join User using (User_NickName)"
    ." where User.User_NickName is NULL";

  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    ChatReglamentPrint
     ($AdminNick,
      $LogId,
      "������ UserMaxEvent ��� ".
      "[".$userdata[User_NickName]."]");

    if ($ReadOnlyFlag)
     {
     }
    else
     {
      $query = ""
       ."delete from UserMaxEvent "
       ." where User_NickName = ".SQLFldStr($userdata[User_NickName]).""
       ."";

      mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);
     }

    $DelRecords++;

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� '���������' MessTo(9) ��� User [3]");

  $query = ""
    ."select "
    ."  Mess.Event_Id as Event_Id,"
    ."  Mess.Mess_ToNick as User_NickName"
    ." from Mess left join User on (Mess_ToNick = User_NickName)"
    ." where User.User_NickName is NULL"
    ."  and Mess_Model = 9";

  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    ChatReglamentPrint
     ($AdminNick,
      $LogId,
      "������ MessTo(9) ��� ".
      "{".$userdata[Event_Id]."}".
      "[".$userdata[User_NickName]."]");

    if ($ReadOnlyFlag)
     {
     }
    else
     {
      $query = ""
       ."delete from Mess "
       ." where Event_Id = ".SQLFldInt($userdata[Event_Id]).""
       ."";

      mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);
     }

    $DelRecords++;

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� '���������' MessFrom(9) ��� User [4]");

  $query = ""
    ."select "
    ."  Mess.Event_Id as Event_Id,"
    ."  Mess.Mess_FromNick as User_NickName"
    ." from Mess left join User on (Mess_FromNick = User_NickName)"
    ." where User.User_NickName is NULL"
    ."  and Mess_Model = 9";

  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    ChatReglamentPrint
     ($AdminNick,
      $LogId,
      "������ MessFrom(9) ��� ".
      "{".$userdata[Event_Id]."}".
      "[".$userdata[User_NickName]."]");

    if ($ReadOnlyFlag)
     {
     }
    else
     {
      $query = ""
       ."delete from Mess "
       ." where Event_Id = ".SQLFldInt($userdata[Event_Id]).""
       ."";

      mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);
     }

    $DelRecords++;

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� '���������' UserNotes(From) ��� User [5]");

  $query = ""
    ."select "
    ."  UserNotes.UserNotes_FromNickName as UserNotes_FromNickName,"
    ."  UserNotes.UserNotes_ToNickName   as UserNotes_ToNickName,"
    ."  UserNotes.UserNotes_FromNickName as User_NickName"
    ." from UserNotes left join User on (UserNotes_FromNickName = User_NickName)"
    ." where User.User_NickName is NULL"
    ."";

  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    ChatReglamentPrint
     ($AdminNick,
      $LogId,
      "������ UserNotes(From) ��� ".
      "[".$userdata[UserNotes_FromNickName]."]".
      "{".$userdata[UserNotes_ToNickName]."}");

    if ($ReadOnlyFlag)
     {
     }
    else
     {
      $query = ""
       ."delete from UserNotes "
       ." where UserNotes_FromNickName = ".SQLFldStr($userdata[UserNotes_FromNickName]).""
       ."   and UserNotes_ToNickName = ".SQLFldStr($userdata[UserNotes_ToNickName]).""
       ."";

      mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);
     }

    $DelRecords++;

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� '���������' UserNotes(To) ��� User [6]");

  $query = ""
    ."select "
    ."  UserNotes.UserNotes_FromNickName as UserNotes_FromNickName,"
    ."  UserNotes.UserNotes_ToNickName   as UserNotes_ToNickName,"
    ."  UserNotes.UserNotes_ToNickName as User_NickName"
    ." from UserNotes left join User on (UserNotes_ToNickName = User_NickName)"
    ." where User.User_NickName is NULL"
    ."";

  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    ChatReglamentPrint
     ($AdminNick,
      $LogId,
      "������ UserNotes(From) ��� ".
      "{".$userdata[UserNotes_FromNickName]."}".
      "[".$userdata[UserNotes_ToNickName]."]");

    if ($ReadOnlyFlag)
     {
     }
    else
     {
      $query = ""
       ."delete from UserNotes "
       ." where UserNotes_FromNickName = ".SQLFldStr($userdata[UserNotes_FromNickName]).""
       ."   and UserNotes_ToNickName = ".SQLFldStr($userdata[UserNotes_ToNickName]).""
       ."";

      mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);
     }

    $DelRecords++;

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� '���������' BanUser ��� User [7]");

  $query = ""
    ."select "
    ."  BanUser.User_NickName as User_NickName"
    ." from BanUser left join User using (User_NickName)"
    ." where User.User_NickName is NULL";

  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    ChatReglamentPrint
     ($AdminNick,
      $LogId,
      "������ BanUser ��� ".
      "[".$userdata[User_NickName]."]");

    if ($ReadOnlyFlag)
     {
     }
    else
     {
      $query = ""
       ."delete from BanUser "
       ." where User_NickName = ".SQLFldStr($userdata[User_NickName]).""
       ."";

      mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);
     }

    $DelRecords++;

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� '���������' Session ��� User [8]");

  $query = ""
    ."select "
    ."  Session.User_NickName as User_NickName,"
    ."  Session.Session_Id as Session_Id"
    ." from Session left join User using (User_NickName)"
    ." where User.User_NickName is NULL";

  $userslist = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);

  while ($userdata = mysql_fetch_array($userslist))
   {
    ChatReglamentPrint
     ($AdminNick,
      $LogId,
      "������ Session ��� ".
      "{".$userdata[Session_Id]."}".
      "[".$userdata[User_NickName]."]");

    if ($ReadOnlyFlag)
     {
     }
    else
     {
      $query = ""
       ."delete from Session "
       ." where Session_Id = ".SQLFldStr($userdata[Session_Id]).""
       ."";

      mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);
     }

    $DelRecords++;

    $L_Index++;
    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- ���������:");

  if ($AddRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$AddRecords." ������� ���������");
   }

  if ($UpdateRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$UpdateRecords." ������� ���������");
   }

  if ($DelRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$DelRecords." ������� �������");
   }

  if ($FailedRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$FailedRecords." ������� � ��������");
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** �����:".$L_Index." ������� ����������".$ROSign);
 }


function ChatReglamentPurgeKillExpiried($AdminNick,$LogId,$CallOptions)
 {
  ChatReglamentSetTimeLimit();

  $ReadOnlyFlag = $CallOptions['ReadOnly'];

  if ($ReadOnlyFlag)
   {
    $ROSign = " [R/O]";
   }
  else
   {
    $ROSign = "";
   }

  $L_Index       = 0;
  $AddRecords    = 0;
  $UpdateRecords = 0;
  $DelRecords    = 0;
  $FailedRecords = 0;

  // Step1

  ChatReglamentPrint($AdminNick,$LogId,"*** �������� ���������� ���������� *** ".$ROSign);

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� ��������� ������ (�����������) [1]");

  ChatTimeOutBans();
  ChatTimeOutUsers();
  ChatPurgeMessAdmLog();
  ChatPurgeMessages();

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- �������� ��������� ��������� (������������) [2]");

  $curdate = ChatCurrTimeStamp();

  do
   {
    // --- Purge private detailed ---
    $NeedDeleteFlag = false;

    $timestamp = ChatMakeSQLTimeStampByTimeStamp($curdate-(ChatConstMessTimeOutInMin*60));

    // UserMaxEvent.User_MaxEventId 
    // ������������� ������� ��� ������������ ������������

    $query = 
    "select Event_Id from Mess, UserMaxEvent".
    " where (Mess.Mess_Model = '9'".
    " and Mess.Mess_ToNick = UserMaxEvent.User_NickName".
    " and Mess.Event_Id <= UserMaxEvent.User_MaxEventId".
    " and Mess_DateTime < '".$timestamp."')".
    " LIMIT ".ChatConstPurgeMessMaxDelCount;
    
    $DeleteEventList = mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);
    $ExitFlag = false;
    $NeedDeleteFlag = false;
    $Index = 0;
    $query = "delete from Mess where Event_Id in (";

    if ($ReadOnlyFlag)
     {
      $ExitFlag = true;
     }

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

        $DelRecords++;
        $L_Index++;

        if (($L_Index % ChatConstReglamentReportInterval) == 0)
         {
          ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
         }

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
      mysql_query($query) or ChatReglamentSQLDie2Log($AdminNick,$LogId,"Select Failed",$query);
     }
   }
  while($NeedDeleteFlag);

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- ���������:");

  if ($AddRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$AddRecords." ������� ���������");
   }

  if ($UpdateRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$UpdateRecords." ������� ���������");
   }

  if ($DelRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$DelRecords." ������� �������");
   }

  if ($FailedRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$FailedRecords." ������� � ��������");
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** �����:".$L_Index." ������� ����������".$ROSign);
 }


function ChatReglamentFixFillPreLogins($AdminNick,$LogId,$CallOptions)
 {
  ChatReglamentSetTimeLimit();

  $ReadOnlyFlag = $CallOptions['ReadOnly'];

  if ($ReadOnlyFlag)
   {
    $ROSign = " [R/O]";
   }
  else
   {
    $ROSign = "";
   }

  $L_Index       = 0;
  $AddRecords    = 0;
  $UpdateRecords = 0;
  $DelRecords    = 0;
  $FailedRecords = 0;

  //include_once(dirname(__FILE__)."/".'z_fix_set_pre_stat_loginscount.php');
  if (!is_array($ChatNickPreStatLoginsCountArray))
   {
    return;
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** ���������� StatPreLogInCount {Fix} ���������� *** ".$ROSign);
 
  foreach ($ChatNickPreStatLoginsCountArray as $ActionNick => $ActionValue)
   {
    if ($ReadOnlyFlag)
     {
      $query = "Select User_StatPreLoginsCount from User where User_NickName = '".addslashes($ActionNick)."'";
     }
    else
     {
      $query = "Update User Set User_StatPreLoginsCount = '".addslashes($ActionValue)."' where User_NickName = '".addslashes($ActionNick)."'";
     }

    $querydata = mysql_query($query);

    if      (!$querydata)
     {
      ChatReglamentPrint
       ($AdminNick,$LogId,
        "[".$ActionNick."]"
       ." - ������ ���������� �������");

      $FailedRecords++;
     }
    else
     {
      if (mysql_affected_rows() < 1)
       {
        ChatReglamentPrint
         ($AdminNick,$LogId,
          "[".$ActionNick."]"
         ." - �� ������ (������������)");

        $FailedRecords++;
       }
      else
       {
        $UpdateRecords++;
       }

      if ($ReadOnlyFlag)
       {
        mysql_free_result($querydata);
       }
     }

    $L_Index++;

    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- ���������:");

  if ($AddRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$AddRecords." ������� ���������");
   }

  if ($UpdateRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$UpdateRecords." ������� ���������");
   }

  if ($DelRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$DelRecords." ������� �������");
   }

  if ($FailedRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$FailedRecords." ������� � ��������");
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** �����:".$L_Index." ������� ����������".$ROSign);
 }


function ChatReglamentFixFillRegInDate($AdminNick,$LogId,$CallOptions)
 {
  ChatReglamentSetTimeLimit();

  $ReadOnlyFlag = $CallOptions['ReadOnly'];

  if ($ReadOnlyFlag)
   {
    $ROSign = " [R/O]";
   }
  else
   {
    $ROSign = "";
   }

  $L_Index       = 0;
  $AddRecords    = 0;
  $UpdateRecords = 0;
  $DelRecords    = 0;
  $FailedRecords = 0;

  //include_once(dirname(__FILE__)."/".'z_fix_set_stat_start_date.php');
  if (!is_array($ChatNickCalcOldReginDateArray))
   {
    return;
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** ���������� RegInDate {Fix} ���������� *** ".$ROSign);

  foreach ($ChatNickCalcOldReginDateArray as $ActionNick => $ActionValue)
   {
    $ActionValue = str_replace('/','',$ActionValue);

    if ($ReadOnlyFlag)
     {
      $query = "Select User_RegDateTime from User where User_NickName = '".addslashes($ActionNick)."'";
     }
    else
     {
      $query = "Update User Set User_RegDateTime = '".addslashes($ActionValue)."' where User_NickName = '".addslashes($ActionNick)."'";
     }

//  echo $query."<br>";

    $querydata = mysql_query($query);

    if      (!$querydata)
     {
      ChatReglamentPrint
       ($AdminNick,$LogId,
        "[".$ActionNick."]"
       ." - ������ ���������� �������");

      $FailedRecords++;
     }
    else
     {
      if (mysql_affected_rows() < 1)
       {
        ChatReglamentPrint
         ($AdminNick,$LogId,
          "[".$ActionNick."]"
         ." - �� ������ (������������)");

        $FailedRecords++;
       }
      else
       {
        $UpdateRecords++;
       }

      if ($ReadOnlyFlag)
       {
        mysql_free_result($querydata);
       }
     }

    $L_Index++;

    if (($L_Index % ChatConstReglamentReportInterval) == 0)
     {
      ChatReglamentPrint($AdminNick,$LogId,$L_Index." ������� ����������");
     }
   }

  ChatReglamentPrint
   ($AdminNick,$LogId,
    "--- ���������:");

  if ($AddRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$AddRecords." ������� ���������");
   }

  if ($UpdateRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$UpdateRecords." ������� ���������");
   }

  if ($DelRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$DelRecords." ������� �������");
   }

  if ($FailedRecords > 0)
   {
    ChatReglamentPrint($AdminNick,$LogId,$FailedRecords." ������� � ��������");
   }

  ChatReglamentPrint($AdminNick,$LogId,"*** �����:".$L_Index." ������� ����������".$ROSign);
 }


?>
