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

if ($MID == 0)
 {
  // *** �������� ��� Update ***
  // �������� ������ �������������
  ChatGetNLFull($RealNick,ChatConstDefaultChatRoom);
  // �������� ���� ��������� �� ��������� N ��� � ��� ���������
  ChatGetM_All($RealNick, $SID, ChatConstDefaultChatRoom, 0, false);
  // ��������� �������������� ���������� ��������� � ����
  ChatGetUI();
 }
else
 {
  // �������� ���� ��������� �� ��������� >$MID
  ChatGetM_All($RealNick,$SID,ChatConstDefaultChatRoom,$MID,true);
  ChatGetUI();
 }

ChatClientMessStreamJavaScriptSetMode(False);
include(dirname(__FILE__)."/"."../tpl/cfsout.htp.php");
?>
