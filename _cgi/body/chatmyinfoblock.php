<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
include_once(dirname(__FILE__)."/"."../inc/funcinfoview.inc.php");

function GetNickOnLineTimeInSec($RealTo)
 {
  $query  =  
  "select Session.*,".
  "       (UNIX_TIMESTAMP()+0-UNIX_TIMESTAMP(Session_StartTime)) As Session_SpentTimeInSec".
  " from Session".
  " where Session.User_NickName = '".addslashes($RealTo)."'".
  " and ChatRoom_Name is not NULL".
  " and ChatRoom_Name <> ''";

  $NickQuery = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  $NickData = mysql_fetch_array($NickQuery);

  if      (!$NickData)
   {
    $TimeOnLineInSec = "";
   }
  else
   {
    if ($NickData[Session_StartTime] != "")
     {
      $TimeOnLineInSec = $NickData[Session_SpentTimeInSec];
     }
    else
     {
      $TimeOnLineInSec = "";
     }
   }

  return($TimeOnLineInSec);
 }

echo "\n";
echo "<font size='-1'>";

if (!isset($Room) || ($Room == ""))
 {
  $Room = ChatConstDefaultChatRoom;
 }

if (!ChatFillConnectedUser_Vars ($Nick, $SID,$Room))
 {
  echo "<div align=center>"."���������:������ �������"."</div>";
  exit;
 }

$RealNick = ChatGetRealNick($Nick);

if ($RealNick == "")
 {
  echo "<div align=center>"."���������:��� �� ������"."</div>";
 }
else
 {
  $query  =  
  "select *".
  " from User".
  " where User.User_NickName = '".addslashes($RealNick)."'";

  $NickQuery = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  $NickData = mysql_fetch_array($NickQuery);

  if (!$NickData)
   {
    echo "<div align=center>"."���������:������ �� ����������"."</div>";
   }
  else
   {
    $TimeOnLineInSec = GetNickOnLineTimeInSec($RealNick);

    WriteInfoHead();

    /*
    //CallTo:NickInfoAnchorWrite
    WriteInfoStepRawData
     ("���",
      ""
     ."<SCRIPT>A.NIAW("
     ."'".addslashes(ChatLowToSpace($NickData[User_NickName]))."'"
     .","
     ."'".addslashes(ChatLowToSpace(Str2Attr($NickData[User_Color])))."'"
     .");</SCRIPT>"
     );
    */

    // Local text (not an anchor version)
    //CallTo:NickInfoTextWrite
    WriteInfoStepRawData
     ("���",
      ""
     ."<SCRIPT>A.NITW("
     ."'".addslashes(ChatLowToSpace($NickData[User_NickName]))."'"
     .","
     ."'".addslashes(ChatLowToSpace(Str2Attr($NickData[User_Color])))."'"
     .");</SCRIPT>"
     );

    if ($NickData[User_EMail] != "")
     {
      WriteInfoStep("EMail" ,$NickData[User_EMail]);
     }

    /*
    WriteInfoStepRawData
     ("����",
     "<font color='".htmlspecialchars(Str2Attr($NickData[User_Color]))."'>"
     .htmlspecialchars($NickData[User_Color])
     ."</font>");
    */

    if      ($NickData[User_Gender] == ChatConstUserGenderMale)
     {
      WriteInfoStep("���"          ,"�������");
     }
    else if ($NickData[User_Gender] == ChatConstUserGenderFemale)
     {
      WriteInfoStep("���"          ,"�������");
     }
    else
     {
      WriteInfoStep("���"          ,"?");
     }

    WriteInfoStep("���� ���-���*",ChatMakeViewDateTimeBySQLDateTime($NickData[User_RegDateTime]));
    WriteInfoStep("���� �����"   ,ChatMakeViewDateTimeBySQLDateTime($NickData[User_LogInDateTime]));
    WriteInfoStep("������ � ���" ,$NickData[User_LoginsCount]);
 // WriteInfoStep("������ ������",$NickData[User_PasswdErrCount]);
 // WriteInfoStep("��� �����"    ,$NickData[User_NickHash]);

    if ($NickData[User_AdminFlag] > 0)
     {
      WriteInfoStep("�����","������� ".$NickData[User_AdminFlag]);
     }

    if ($NickData[User_SpentTimeSec] > 0)
     {
      if (($TimeOnLineInSec != "") && ($TimeOnLineInSec > 0))
       {
        $FullTimeInSec = $TimeOnLineInSec + $NickData[User_SpentTimeSec];
       }
      else
       {
        $FullTimeInSec = $NickData[User_SpentTimeSec];
       }

      WriteInfoStep("����� � ����","".ChatViewSecoundsAsText($FullTimeInSec)."");
     }

    if (($TimeOnLineInSec != "") && ($TimeOnLineInSec > 0))
     {
      WriteInfoStep("OnLine",ChatViewSecoundsAsText($TimeOnLineInSec));
     }

    if ($NickData[User_WarnCount] > 0)
     {
 //   WriteInfoStep("����������.",$NickData[User_WarnCount]);
     }

    if ($NickData[User_KicksCount] > 0)
     {
 //   WriteInfoStep("Kickedout  ",$NickData[User_KicksCount]);
     }

    $query  =  
    "select *".
    " from UserMaxEvent".
    " where UserMaxEvent.User_NickName = '".addslashes($RealNick)."'";

    $MaxEventQuery = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
    $MaxEventData = mysql_fetch_array($MaxEventQuery);

    if ($MaxEventData)
     {
      $TotalMess = 0;
      $TotalMess += $MaxEventData[UserMaxEvent_SendMPvtCount];
      $TotalMess += $MaxEventData[UserMaxEvent_RecvMPvtCount];
      $TotalMess += $MaxEventData[UserMaxEvent_SendMessCount];
      $TotalMess += $MaxEventData[UserMaxEvent_RecvMessCount];

      WriteInfoStep("����.������",$MaxEventData[UserMaxEvent_SendMPvtCount]);
      WriteInfoStep("�����.����.",$MaxEventData[UserMaxEvent_RecvMPvtCount]);
      WriteInfoStep("����.����� ",$MaxEventData[UserMaxEvent_SendMessCount]);
      WriteInfoStep("�����.�����",$MaxEventData[UserMaxEvent_RecvMessCount]);
 //   WriteInfoStep("MaxEventId ",$MaxEventData[User_MaxEventId]);
      WriteInfoStep("�����      ",$TotalMess." �����.");
     }

    // --- Show Locks if any --- Begin

    $LockCheckNick = $RealNick;
    $LockCheckIP   = ChatNickIP($LockCheckNick);
    list($LockIPModel  ,$LockIPExpTime)   = ChatGetBanIPModelLowExt($LockCheckIP);
    list($LockNickModel,$LockNickExpTime) = ChatGetBanUserModelLowExt($LockCheckNick);

    if (!empty($LockIPModel))
     {
      include_once(dirname(__FILE__)."/"."../inc/support.inc.php");

      $LockModel   = $LockIPModel;
      $LockExpTime = $LockIPExpTime;
      $LockName    = "����. IP";

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
      WriteInfoStep($LockName,array($LockDesc,$LockExpTime));
     }

    if (!empty($LockNickModel))
     {
      include_once(dirname(__FILE__)."/"."../inc/support.inc.php");

      $LockModel   = $LockNickModel;
      $LockExpTime = $LockNickExpTime;
      $LockName    = "����. ����";

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
      WriteInfoStep($LockName,array($LockDesc,$LockExpTime));
     }

    // --- Show Locks if any --- End

    $query = ChatBuildNickRatingQuery(" User.User_NickName = '".addslashes($RealNick)."'");
    $RatingQuery = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
    $RatingData = mysql_fetch_array($RatingQuery);

    if ($RatingData)
     {
      if ($RatingData[User_Rating] != "")
       {
        if (($RatingData[User_Rating] > ChatMinRatingMyInfoShow) ||
            ($User_AdminFlag >= $MinAdmLevelToSeeStats))
         {
          WriteInfoStep("�������",$RatingData[User_Rating]);
         }
       }
     }

    WriteInfoTail();

    // Show active notes

    $query  =  
    "select *".
    " from UserNotes,User".
    " where (UserNotes.UserNotes_FromNickName = '".addslashes($RealNick)."'"." AND ".
    "        UserNotes.UserNotes_ToNickName = User.User_NickName)"." ".
    " order by UserNotes.UserNotes_FromNickName,UserNotes.UserNotes_GroupText,UserNotes.UserNotes_ToNickName";

    $NotesQuery = mysql_query($query) or die("Select Failed :". $query);

    echo "<script language='JavaScript'>";
    echo "\n";

    $CurrGroup = "";
    $NotesCount = 0;

    while($NotesData = mysql_fetch_array($NotesQuery))
     {
      $NotesCount++;

      if ($NotesCount == 1)
       {
        echo "\n";
        echo "document.write('<div align=center><b>������� � �����:</b></div>');";
        echo "\n";
       }

      if ($CurrGroup <> $NotesData[UserNotes_GroupText])
       {
        $CurrGroup = $NotesData[UserNotes_GroupText];
        $mess = addslashes(htmlspecialchars($CurrGroup));

        echo "document.write('<b>&gt;');";
        echo "document.write(\"".$mess."\");";
        echo "document.write('</b>');";
        echo "document.write('<br>');";
        echo "\n";
       }

      if ($NotesData[User_SelfNotes] != "")
       {
        $HasInfoFlag = "true";
       }
      else
       {
        $HasInfoFlag = "false";
       }

      $HasMyNoteFlag = "true";

      //CallTo:NickInfoAnchorWriteInNotesList
      echo "A.NIAWNL(".
            "'".addslashes(ChatLowToSpace($NotesData[User_NickName]))."'".",".
            "'".addslashes(ChatLowToSpace(Str2Attr($NotesData[User_Color])))."'".",".
            "'".addslashes(ChatLowToSpace($NotesData[User_Gender]))."'".",".
            "".$HasInfoFlag."".",".
            "".$HasMyNoteFlag."".");";

      echo "document.write('<br>');";
      echo "\n";
     }
    echo "</script>";
    echo "\n";

    echo "</font>";
    echo "\n";

    /*
    if ($NickData[User_SelfNotes] != "")
     {
      echo "\n";
      echo "<font size='-1'>";
      echo "<b>���� ���� ��� ����</b>";
      echo "<br>";
      echo "\n";

      echo htmlspecialchars($NickData[User_SelfNotes]);

      echo "\n";
      echo "<br>";
      echo "\n";
      echo "</font>";
      echo "\n";
     }
    */
   ?>
   <script language="JavaScript">
   function CheckClear()
    {
     if (confirm("�������� ���� ����������?"))
      {
       document.SelfNotesForm.SelfNotes.value = "";
 //    document.SelfNotesForm.Topic.value = "";
      }
    }
   </script>
   <font size='-1'>
   <FORM name="SelfNotesForm" method="post"
         STYLE="margin-top:1px;margin-bottom:1px;">
   <div align=center><a name='Info'><b>���� ���� ��� ����:</b></div>
   <TEXTAREA Name="SelfNotes" 
    TITLE="���������� � ��� ��� ������ ������������� (������ ���� ������ �������������)"
    ROWS=7 COLS=21
    STYLE="font-size: smaller"
    WRAP=VIRTUAL
    ><?
    echo htmlspecialchars($NickData[User_SelfNotes]);
    ?></TEXTAREA><br><nobr>
   <input type="submit" name="CmdChatNickSetSelfNote" value="��������" 
    STYLE="font-size: smaller"
          title='������� ������ ����� �������� ���������� � ����'>
   <input type="button" value="��������" 
    STYLE="font-size: smaller"
          onclick="return CheckClear()"
          title='�������� ������� � ����'><br></nobr>
   <input type="hidden" name="Nick"   value="<? echo htmlspecialchars($RealNick); ?>">
   <script language="JavaScript">
   document.write('<input type="hidden" name="SID" value="'+top.SetUpInfo.SessionId+'">');
   document.write('<input type="hidden" name="PreSID" value="'+top.SetUpInfo.PreSID+'">');
   </script>
   </FORM>
   </font>
   <font size="-1">
    <div align=right>
     [ <a href='#top' title='��������� � ������ ������ �������'>������</a> ]
    <div>
   </font>
   <?
   }
 }
?>
