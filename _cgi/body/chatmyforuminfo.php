<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<html>
<head>
<META HTTP-EQUIV="Pragma" CONTENT="No-Cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
<META http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<body>

<script language="JavaScript">
<!-- hide
document.bgColor = top.SetUpFrame.document.bgColor;
document.fgColor = top.SetUpFrame.document.fgColor;
// -->
</script>

<?
include(dirname(__FILE__)."/"."chatadv.php"); // Advertising

echo "<div align=center><b>Ваш данные на форуме</b></div>";

include_once(dirname(__FILE__)."/"."../inc/funcinfoview.inc.php");

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

$RealNick = ChatGetRealNick($Nick);

if ($RealNick == "")
 {
  echo "<div align=center>"."Результат:Ник не найден"."</div>";
  exit;
 }

if (!ExtFromChatUserGetStats
     ($ErrorMess,
      $RealNick,
      $UserExistsFlag,
       true,
      $UserStats,
       true,
      $UserDBRecord,
       true))
 {
  echo "<div align=center>"."Результат:Ошибка запроса данных форума"."</div>";
  echo "<div align=center>"."Форум:".$ErrorMess.""."</div>";
  exit;
 }

if (!$UserExistsFlag)
 {
  echo "<div align=center>"."Результат:Ник не найден на форуме"."</div>";
  exit;
 }

echo "\n";

/*
echo "<PRE>";
print_r($UserStats);
print_r($UserDBRecord);
echo "</PRE>";
*/

/*
Field			Type
user_id			mediumint(8)
user_active		tinyint(1)
username		varchar(25)
user_password		varchar(32)
user_session_time	int(11)
user_session_page	smallint(5)
user_lastvisit		int(11)
user_regdate		int(11)
user_level		tinyint(4)
user_posts		mediumint(8) unsigned
user_timezone		decimal(5
user_style		tinyint(4)
user_lang		varchar(255)
user_dateformat		varchar(14)
user_new_privmsg	smallint(5) unsigned
user_unread_privmsg	smallint(5) unsigned
user_last_privmsg	int(11)
user_emailtime		int(11)
user_viewemail		tinyint(1)
user_attachsig		tinyint(1)
user_allowhtml		tinyint(1)
user_allowbbcode	tinyint(1)
user_allowsmile		tinyint(1)
user_allowavatar	tinyint(1)
user_allow_pm		tinyint(1)
user_allow_viewonline	tinyint(1)
user_notify		tinyint(1)
user_notify_pm		tinyint(1)
user_popup_pm		tinyint(1)
user_rank		int(11)
user_avatar		varchar(100)
user_avatar_type	tinyint(4)
user_email		varchar(255)
user_icq		varchar(15)
user_website		varchar(100)
user_from		varchar(100)
user_sig		text
user_sig_bbcode_uid	varchar(10)
user_aim		varchar(255)
user_yim		varchar(255)
user_msnm		varchar(255)
user_occ		varchar(100)
user_interests		varchar(255)
user_actkey		varchar(32)
user_newpasswd		varchar(32)
*/

$ForumUserData  = $UserDBRecord['*'];
$ForumUserStats = array();

if (count($UserStats) > 0)
 {
  if (count($UserStats['*']) > 0)
   {
    $ForumUserStats = $UserStats['*'];
   }
 }

WriteInfoHead();

WriteInfoStepRawData
 ("Ник",
  ""
 ."<u>"
 .htmlspecialchars($ForumUserData['username'])
 ."</u>"
 );

if ($ForumUserData['user_email'] != "")
 {
  if ($ForumUserData['user_viewemail'])
   {
    WriteInfoStep("EMail" ,"<Приватно>");
   }
  else
   {
    WriteInfoStep("EMail" ,$ForumUserData['user_email']);
   }
 }

WriteInfoStepFilled("Уровень      ",$ForumUserData['user_level']);

if ($ForumUserData['user_regdate'] > 0)
 {
  WriteInfoStep("Дата рег-ции*",ChatMakeViewDateTimeByTimeStamp($ForumUserData['user_regdate']));
 }

if ($ForumUserData['user_lastvisit'] > 0)
 {
  WriteInfoStep("Дата входа   ",ChatMakeViewDateTimeByTimeStamp($ForumUserData['user_lastvisit']));
 }

if ($ForumUserData['user_emailtime'] > 0)
 {
  WriteInfoStep("Дата Email-а ",ChatMakeViewDateTimeByTimeStamp($ForumUserData['user_emailtime']));
 }

WriteInfoStepFilled("Язык         ",$ForumUserData['user_lang']);
WriteInfoStepFilled("Новых личн.с.",$ForumUserData['user_new_privmsg']);
WriteInfoStepFilled("Непрочит. лс.",$ForumUserData['user_unread_privmsg']);
WriteInfoStepFilled("Звание       ",$ForumUserData['user_rank']);

if ($ForumUserData['user_posts'] > 0)
 {
  WriteInfoStepFilled("Написано     ",$ForumUserData['user_posts']." сообщ.");
 }

WriteInfoTail();
echo "\n";

if ($ForumUserData['user_lastvisit'] > 0)
 {
  if (count($ForumUserStats) > 0)
   {
    echo "<div align=center>"."<b>"."Новое на форуме"."</b>"."</div>";
   }

  echo "<div align=center>"."<font size=-2>"."<i>";
  echo "Изменения после вашего прошлого входа на форум:"."";
  echo "</i>"."</font>"."</div>";
  echo "\n";

  if ((count($ForumUserStats) <= 0) || (count($ForumUserStats['NewPosts']) <= 0))
   {
    echo "<div align=center>"."На форуме нет новых тем"."</div>";
    echo "\n";
   }
  else
   {
    echo "<div align=left>";
    echo "\n";

    echo "<br>";
    echo "\n";

    foreach($ForumUserStats['NewPosts'] as $ForumId => $ForumData)
     {
//    echo "".htmlspecialchars($ForumData['forum_name'])."";
      echo "".$ForumData['forum_name'].""; // Allready &-amped in phpBB
      echo "\n";

      echo "<ul style='margin-top:5px;margin-bottom:5px;'>";
      echo "\n";

      foreach($ForumData['topics_array'] as $TopicId => $TopicData)
       {
//      echo "<li>".htmlspecialchars($TopicData['topic_title']);
        echo "<li>".$TopicData['topic_title']; // Allready &-amped in phpBB
        echo "\n";
       }

      echo "</ul>";
      echo "\n";
     }

    echo "</div>";
    echo "\n";

    echo "<br>";
    echo "\n";
   }
 }
else
 {
  echo "<div align=center>"."Вы еще не заходили на форум"."</div>";
 }

?>
<font size='-1'>
<div align=center>
<FORM name="ChatGoToForumForm" method="post"
      STYLE="margin-top:1px;margin-bottom:1px;"
      Target="_blank">
<input type=submit name=ChatGoToForum value="Зайти на форум"
       STYLE="font-size: smaller"
       title='Нажмите кнопку чтобы открыть форум в новом окне'>
<input type="hidden" name="Nick"   value="<? echo htmlspecialchars($RealNick); ?>">
<script language="JavaScript">
document.write('<input type="hidden" name="SID" value="'+top.SetUpInfo.SessionId+'">');
document.write('<input type="hidden" name="PreSID" value="'+top.SetUpInfo.PreSID+'">');
</script>
</FORM>
</div>
</font>
<font size="-1">
 <div align=right>
  [ <a href='#top' title='Вернуться к началу экрана справки'>наверх</a> ]
 <div>
</font>
<?
?>
</body>
