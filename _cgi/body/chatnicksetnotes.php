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

if (!isset($Room) || ($Room == ""))
 {
  $Room = ChatConstDefaultChatRoom;
 }

if (!ChatFillConnectedUser_Vars ($Nick, $SID,$Room))
 {
  echo "������ ���������� � ���� :<b>".htmlspecialchars($MessTo)."</b><br>";
  echo "���������:������ �������"."<br>";
  exit;
 }

$RealTo = ChatGetRealNick($MessTo);

if ($RealTo == "")
 {
  echo "������ ���������� � ���� :<b>".htmlspecialchars($MessTo)."</b><br>";
  echo "���������:��� �� ������"."<br>";
 }
else
 {
  $RealNick = ChatGetRealNick($Nick);

  $query  =  
  "delete".
  " from UserNotes".
  " where (UserNotes.UserNotes_FromNickName = '".addslashes($RealNick)."'"." AND ".
  "        UserNotes.UserNotes_ToNickName = '".addslashes($RealTo)."')";

  $NotesQuery = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

  $NotesGroup = $Topic; // This name of field used

  $NotesGroup = trim($NotesGroup);
  $NickNotes  = trim($NickNotes);

  if (($NickNotes == "") && ($NotesGroup == ""))
   {
    echo "<div align=center><b>������� �������</b></div>";
   }
  else
   {
    if (strlen($NickNotes) > ChatConstNickNotesMaxLength)
     {
      echo "<div align=center><b>";
      echo "����� ������ �� "
          .number_format((1.0*ChatConstNickNotesMaxLength)/1024,1)."K";
      echo "</b></div>";

      $NickNotes = substr($NickNotes,0,ChatConstNickNotesMaxLength);
     }

    $query  =  
    "insert into UserNotes".
    " (UserNotes_FromNickName,UserNotes_ToNickName,UserNotes_MyText,UserNotes_GroupText)".
    " values".
    " ('".addslashes($RealNick)."'".",".
    "  '".addslashes($RealTo)."'".",".
    "  '".addslashes($NickNotes)."'".",".
    "  '".addslashes($NotesGroup)."'".")".
    "";

    $NotesQuery = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

    echo "<div align=center><b>������� ��������</b></div>";
   }

  include(dirname(__FILE__)."/"."chatnickinfoblock.php");
 }
?>
</body>
