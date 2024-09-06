<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
if (!isset($Room) || ($Room == ""))
 {
  $Room = ChatConstDefaultChatRoom;
 }

if (!ChatFillConnectedUser_Vars($Nick,$SID,$Room))
 {
  $User_Nick = addslashes($Nick);
  $User_Color = "";
  $ChatErrorText = "Неверный запрос";
  include(dirname(__FILE__)."/"."../tpl/cfbye.htp.php");
  exit;
 }

// CheckUp Session

 $query = 
 "select * from Session, User".
 " where User.User_NickName = Session.User_NickName".
 " and Session.Session_Id = '".addslashes($SID)."'".
 " and Session.User_NickName = '".addslashes($Nick)."'";

 $DropSessList = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);

 if ($DropSess = mysql_fetch_array($DropSessList)) 
  {
   if ($DropSess[ChatRoom_Name] != "")
    {
     ChatUserCalcStats ($Nick,$DropSess[Session_StartTime]);
    }

   $LastRoom  = $DropSess[ChatRoom_Name];
   $LastFlags = $DropSess[Session_Flags];
  }

ChatDropSession($Nick, $SID);

if ($LastRoom != "")
 {
  if ($LastFlags == ChatConstSessionFlagPreparing)
   {
    // Сессия так и не была активирована полностью
   }
  else
   {
    ChatLeaveMessSend($LastRoom,$User_Gender,$User_Color,$Nick);
   }
 }

ChatPurgeTimeOutAtLogOut($LastRoom);

include(dirname(__FILE__)."/"."../tpl/c_bye.htp.php");
?>
