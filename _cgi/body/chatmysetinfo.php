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
include(dirname(__FILE__)."/"."../tpl/cishead.htp.php"); // A=top
include(dirname(__FILE__)."/"."chatadv.php"); // Advertising

 //echo "Ваши данные. Ник :<b>".htmlspecialchars($Nick)."</b><br>";

 if (!isset($Room) || ($Room == ""))
  {
   $Room = ChatConstDefaultChatRoom;
  }

 if (strlen($SelfNotes) > ChatConstSelfNotesMaxLength)
  {
   echo "<div align=center><b>";
   echo "Обновление заметок"."<br>";
   echo "Текст усечен до "
       .number_format((1.0*ChatConstSelfNotesMaxLength)/1024,1)."K";
   echo "</b></div>";

   $SelfNotes = substr($SelfNotes,0,ChatConstSelfNotesMaxLength);
  }

 if (!ChatFillConnectedUser_Vars ($Nick, $SID,$Room))
  {
   echo "Обновление заметок"."<br>";
   echo "Результат:Ошибка запроса"."<br>";
   exit;
  }

 $RealNick  = ChatGetRealNick($Nick);
 $SelfNotes = trim($SelfNotes);

 $query  =  
 "update User Set User_SelfNotes = '".addslashes($SelfNotes)."' ".
 " where User_NickName = '".addslashes($RealNick)."'";

 $NotesQuery = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

 echo "<div align=center><b>Заметки обновлены</b></div>";
 echo "<div align=center><b>Справка о вас</b></div>";

 include(dirname(__FILE__)."/"."chatmyinfoblock.php");
?>
</body>
