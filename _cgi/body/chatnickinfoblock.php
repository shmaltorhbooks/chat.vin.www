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
  echo "<div align=center>"."Результат:Ошибка запроса"."</div>";
  exit;
 }

$RealTo = ChatGetRealNick($MessTo);
$RealNick = ChatGetRealNick($Nick);

$MinAdmLevelToSeeStats = 2;
$MinAdmLevelToSeeInfo  = 2;

if      ($RealTo == "")
 {
  echo "<div align=center>"."Результат:Ник не найден"."</div>";
 }
else if ($RealNick == "")
 {
  echo "<div align=center>"."Результат:Ваш ник не найден"."</div>";
 }
else if ($RealTo == $RealNick)
 {
  echo "Результат:Этот ник совпадает с вашим!"."<br>";
 }
else
 {
  $query  =  
  "select *".
  " from User".
  " where User.User_NickName = '".addslashes($RealTo)."'";

  $NickQuery = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
  $NickData = mysql_fetch_array($NickQuery);

  if (!$NickData)
   {
    echo "<div align=center>"."Результат:Данные не обнаружены"."</div>";
   }
  else
   {
    $TimeOnLineInSec = GetNickOnLineTimeInSec($RealTo);

    WriteInfoHead();

    if ($NickData[User_SelfNotes] != "")
     {
      $HasInfoFlag = "true";
     }
    else
     {
      $HasInfoFlag = "false";
     }

    if (ChatNickHasMyNotes($RealNick,$RealTo))
     {
      $HasMyNoteFlag = "true";
     }
    else
     {
      $HasMyNoteFlag = "false";
     }

    //CallTo:NickInfoAnchorWriteInNickInfoHead
    WriteInfoStepRawData
     ("Ник",
      ""
     ."<SCRIPT>A.NIAWNH("
     ."'".addslashes(ChatLowToSpace($NickData[User_NickName]))."'"
     .","
     ."'".addslashes(ChatLowToSpace(Str2Attr($NickData[User_Color])))."'"
     .","
     ."'".addslashes(ChatLowToSpace(Str2Attr($NickData[User_Gender])))."'"
     .","
     .$HasInfoFlag
     .","
     .$HasMyNoteFlag
     .");</SCRIPT>"
     );

    if ($NickData[User_EMail] != "")
     {
 //   WriteInfoStep("EMail" ,$NickData[User_EMail]);
     }

    /*
    WriteInfoStepRawData
     ("Цвет",
     "<font color='".htmlspecialchars(Str2Attr($NickData[User_Color]))."'>"
     .htmlspecialchars($NickData[User_Color])
     ."</font>");
    */

    /*
    // Показывается иконкой возле ника
    if      ($NickData[User_Gender] == ChatConstUserGenderMale)
     {
      WriteInfoStep("Пол"          ,"Мужской");
     }
    else if ($NickData[User_Gender] == ChatConstUserGenderFemale)
     {
      WriteInfoStep("Пол"          ,"Женский");
     }
    else
     {
      WriteInfoStep("Пол"          ,"?");
     }
    */

    if ($User_AdminFlag >= $MinAdmLevelToSeeInfo)
     {
      WriteInfoStep("Дата рег-ции" ,ChatMakeViewDateTimeBySQLDateTime($NickData[User_RegDateTime]));
      WriteInfoStep("Дата входа"   ,ChatMakeViewDateTimeBySQLDateTime($NickData[User_LogInDateTime]));
      WriteInfoStep("Входов в чат" ,$NickData[User_LoginsCount]);
     }

   // WriteInfoStep("Ошибок пароля",$NickData[User_PasswdErrCount]);
   // WriteInfoStep("Хеш имени"    ,$NickData[User_NickHash]);

    if ($NickData[User_AdminFlag] > 0)
     {
      WriteInfoStep("Админ","Уровень ".$NickData[User_AdminFlag]);
     }

    if ($NickData[User_SpentTimeSec] > 0)
     {
      if ($User_AdminFlag >= $MinAdmLevelToSeeInfo)
       {
        if (($TimeOnLineInSec != "") && ($TimeOnLineInSec > 0))
         {
          $FullTimeInSec = $TimeOnLineInSec + $NickData[User_SpentTimeSec];
         }
        else
         {
          $FullTimeInSec = $NickData[User_SpentTimeSec];
         }

        WriteInfoStep("Всего в чате","".ChatViewSecoundsAsText($FullTimeInSec)."");
       }
     }

    if (($TimeOnLineInSec != "") && ($TimeOnLineInSec > 0))
     {
      WriteInfoStep("OnLine",ChatViewSecoundsAsText($TimeOnLineInSec));
     }

    if ($User_AdminFlag >= $MinAdmLevelToSeeInfo)
     {
      if ($NickData[User_WarnCount] > 0)
       {
        WriteInfoStep("Предупрежд.",$NickData[User_WarnCount]);
       }

      if ($NickData[User_KicksCount] > 0)
       {
        WriteInfoStep("Kickedout  ",$NickData[User_KicksCount]);
       }
     }

    $query  =  
    "select *".
    " from UserMaxEvent".
    " where UserMaxEvent.User_NickName = '".addslashes($RealTo)."'";

    $MaxEventQuery = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
    $MaxEventData = mysql_fetch_array($MaxEventQuery);

    if ($MaxEventData)
     {
      $TotalMess = 0;
      $TotalMess += $MaxEventData[UserMaxEvent_SendMPvtCount];
      $TotalMess += $MaxEventData[UserMaxEvent_RecvMPvtCount];
      $TotalMess += $MaxEventData[UserMaxEvent_SendMessCount];
      $TotalMess += $MaxEventData[UserMaxEvent_RecvMessCount];

      if ($User_AdminFlag >= $MinAdmLevelToSeeStats)
       {
        WriteInfoStep("Отпр.приват",$MaxEventData[UserMaxEvent_SendMPvtCount]);
        WriteInfoStep("Получ.прив.",$MaxEventData[UserMaxEvent_RecvMPvtCount]);
        WriteInfoStep("Отпр.общих ",$MaxEventData[UserMaxEvent_SendMessCount]);
        WriteInfoStep("Получ.общих",$MaxEventData[UserMaxEvent_RecvMessCount]);
 //     WriteInfoStep("MaxEventId ",$MaxEventData[User_MaxEventId]);
        WriteInfoStep("Всего      ",$TotalMess." сообщ.");
       }
     }

    // --- Show Locks if any --- Begin

    $LockCheckNick = $RealTo;
    $LockCheckIP   = ChatNickIP($LockCheckNick);
    list($LockIPModel  ,$LockIPExpTime)   = ChatGetBanIPModelLowExt($LockCheckIP);
    list($LockNickModel,$LockNickExpTime) = ChatGetBanUserModelLowExt($LockCheckNick);

    if (!empty($LockIPModel))
     {
      include_once(dirname(__FILE__)."/"."../inc/support.inc.php");

      $LockModel   = $LockIPModel;
      $LockExpTime = $LockIPExpTime;
      $LockName    = "Блок. IP";

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
      $LockName    = "Блок. ника";

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

    $query = ChatBuildNickRatingQuery(" User.User_NickName = '".addslashes($RealTo)."'");
    $RatingQuery = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
    $RatingData = mysql_fetch_array($RatingQuery);

    if ($RatingData)
     {
      if ($RatingData[User_Rating] != "")
       {
        if (($RatingData[User_Rating] > ChatMinRatingToUserInfoShow) ||
            ($User_AdminFlag >= $MinAdmLevelToSeeStats))
         {
          WriteInfoStep("Рейтинг",$RatingData[User_Rating]);
         }
       }
     }

    WriteInfoTail();

    echo "</font>";
    echo "\n";
   ?>
   <script language="JavaScript">
   function CheckClear()
    {
     if (confirm("Очистить заметки?"))
      {
       document.NickNotesForm.NickNotes.value = "";
       document.NickNotesForm.Topic.value = "";
      }
    }
   </script>
   <font size='-1'>
   <FORM name="NickNotesForm" method="post" 
         STYLE="margin-top:1px;margin-bottom:1px;">
   <div align=center><a name='MyNote'></a><b>Ваши заметки по нику:</b></div>
   <TEXTAREA Name="NickNotes" 
    TITLE="Ваши личные заметки по этому нику (доступны только вам)"
    ROWS=7 COLS=21
    STYLE="font-size: smaller;"
    WRAP=VIRTUAL
    ><?
    $query  =  
    "select *".
    " from UserNotes".
    " where (UserNotes.UserNotes_FromNickName = '".addslashes($RealNick)."'"." AND ".
    "        UserNotes.UserNotes_ToNickName = '".addslashes($RealTo)."')";

    $NotesQuery = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
    $NotesData = mysql_fetch_array($NotesQuery);

    if ($NotesData)
     {
      echo htmlspecialchars($NotesData[UserNotes_MyText]);
     }
    
    ?></TEXTAREA><br><nobr>
   Группа (необязательно):<br><nobr>
   <input type="text" 
          name="Topic" 
    <?
    if ($NotesData)
     {
      echo "value=\"".trim(htmlspecialchars($NotesData[UserNotes_GroupText])."\"\n");
     }
    ?>
          size=26 maxlength=15
          title='Группа в которую поместить заметку (необязательный параметр)'
          STYLE="font-size: smaller"
          ><br><nobr>
   <input type="submit" name="CmdChatNickSetNote" value="Записать" 
    STYLE="font-size: smaller"
          title='Нажмите кнопку чтобы обновить заметки'>
   <input type="button" value="Очистить" 
    STYLE="font-size: smaller"
          onclick="return CheckClear()"
          title='Очистить заметки'><br></nobr>
   <input type="hidden" name="Nick"   value="<? echo htmlspecialchars($RealNick); ?>">
   <input type="hidden" name="MessTo" value="<? echo htmlspecialchars($RealTo); ?>">
   <script language="JavaScript">
   document.write('<input type="hidden" name="SID" value="'+top.SetUpInfo.SessionId+'">');
   document.write('<input type="hidden" name="PreSID" value="'+top.SetUpInfo.PreSID+'">');
   </script>
   </FORM>
   </font>
    <?

    if ($NickData[User_SelfNotes] != "")
     {
      echo "\n";
      echo "<font size='-1'>";
      echo "<div align=center>";
      echo "<a name='Info'></a>";
      echo "<b>Инфо этого ника о себе</b>";
      echo "</div>";
      echo "\n";

      echo htmlspecialchars($NickData[User_SelfNotes]);

      echo "\n";
      echo "<br>";
      echo "\n";
      echo "</font>";
      echo "\n";
     }
    ?>
    <font size="-1">
     <div align=right>
      [ <a href='#top' title='Вернуться к началу экрана справки'>наверх</a> ]
     <div>
    </font>
    <?
   }
 }
?>
