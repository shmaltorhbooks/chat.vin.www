<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
if (!isset($Room) || ($Room == ""))
 {
  $Room = ChatConstDefaultChatRoom;
 }

if (!ChatFillConnectedUser_Vars ($Nick, $SID,$Room))
 {
  $ChatErrorText = "�������� ������";
  include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
  exit;
 }

$RealNick = ChatGetRealNick($Nick);

if      ($RealNick == "")
 {
  $ChatErrorText = "������ ����������� ��������� ����";
  include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
  exit;
 }

if ($User_Session_ChatRoom_Name == "")
 {
  // ���� ��������� � ������ ��������, � �������� ������ ���������� ����
  $ChatErrorText = "�������� ������� �������";
  include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
  exit;
 }

include(dirname(__FILE__)."/"."../tpl/cfsin.htp.php");
ChatClientMessStreamJavaScriptSetMode(True);

if (defined("ChatDelayRegInMessage") || defined("ChatDelayLogInMessage"))
 {
  // ����� �������� �� ����� ������ ��� ������ ���������
  if ($User_Session_Flags == ChatConstSessionFlagPreparing)
   {
    // ����� ��� �� �����������
    if      (($User_LoginsCount  > 1) && defined("ChatDelayLogInMessage"))
     {
      // ������� ������ ������ - ������� ��� �������
      ChatLogInMessSend($Room,$User_Gender,$User_Color,$RealNick);
     }
    else if (($User_LoginsCount == 1) && defined("ChatDelayRegInMessage"))
     {
      // ������ �����. ������ ��� ���� �����������
      ChatRegInMessSend($Room,$User_Gender,$User_Color,$RealNick);
     }

    ChatUpdateSessionFlags($SID,ChatConstSessionFlagActive);
   }
 }

$query  =  
"select User_Color".
" from User".
" where User.User_NickName = '".addslashes($RealNick)."'";

$QueryColor = mysql_query($query) or ChatSQLDie2Log("Select Failed",$query);
$QueryColor = mysql_fetch_array($QueryColor);

if ($QueryColor)
 {
  $U_Color = $QueryColor[0];
 }
else
 {
  $U_Color = ""; // Default to client
 }

if ($MessPvt == ChatConstClientMessPvtSignPrivate)
 {
  $M_Model = ChatConstMessModelPrivate; // ��������� ���������
 }
else
 {
  $M_Model = ChatConstMessModelChat; // ������� ���������
 }

include_once(dirname(__FILE__)."/"."../inc/chatstopword.inc.php");

if (($M_Model == ChatConstMessModelPrivate) && 
    ($User_AdminFlag > 0) && 
    ($MessText != "") &&
    ($MessText{0} == ChatMessTextAdminCommandPrefixChar))
 {
  include_once(dirname(__FILE__)."/"."../inc/support.inc.php");

  ChatAdminZoneCommand 
   ($User_AdminFlag,$RealNick,$MessTo,$MessText,$Room);
 }
else
 {
  if (!ChatStopWordProtectNickLogInOk($RealNick,$Room))
   {
    $M_Model = ChatConstMessModelPrivate; // �������������� ������
   }

  if      (!ChatFloodProtectSendOk($RealNick))
   {
    // Flood
    ChatFloodProtectOverLimitAction($RealNick,$SID,$Room,$MessTo);
   }
  else if (!ChatStopWordProtectSendOk($RealNick,$Room,$MessTo,$MessText,$M_Model))
   {
    // StopWord
    ChatStopWordProtectAction($RealNick,$SID,$Room,$MessTo);
   }
  else
   {
    ChatOneMessSend
     ($Room,$MessText,$U_Color,$RealNick,$MessTo,$M_Model);
   }
 }

ChatGetM_All($RealNick,$SID,$Room,$MID,true);
ChatGetUF();

ChatClientMessStreamJavaScriptSetMode(False);
include(dirname(__FILE__)."/"."../tpl/cfsout.htp.php");
?>
