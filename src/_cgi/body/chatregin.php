<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
include_once(dirname(__FILE__)."/".'../inc/funcvalidate.inc.php');

if (!isset($Room))
 {
  $Room = ChatConstDefaultChatRoom;
 }

if (ChatUserRegInLockedByIP())
 {
  $ChatErrorText = "����������� ����� ����� � ������ ������ �������� �����������";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (!ChatStrNickStrValid($Nick))
 {
  $ChatErrorText = "������������ ����������� ������� � ����";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (strcmp($Password,$PasswordAdd) != 0)
 {
  $ChatErrorText = "�������� ����� ����� ������ �� ���������";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (strlen(ChatNickToVisual($Nick)) == 0)
 {
  $ChatErrorText = "������ ��� ���������� ��� �� ��������";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (($EMail != "") && (!ChatCheckStrValidEMail($EMail)))
 {
  $ChatErrorText = "������������ EMail �����";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (($Color == "") || (!ChatCheckStrValidHTMLColor($Color)))
 {
  $ChatErrorText = "������������ ����";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (!ChatCheckStrValidGender($Gender))
 {
  $ChatErrorText = "������������ ���";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (!ChatStopWordProtectNickLogInOk($Nick,$Room))
 {
  $ChatErrorText = "����������: ������������ ����� � ����";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

/*
if (ChatNickExists($Nick))
 {
  // "�������" �������� �� ������������� ������������
  // ������� ����������� ������ ChatUserAdd
  // ������������ �� ����� �� �������� ��� ������� �����
  // ����������� ��� ������������� ����������� "�������������� ��� RegIn"
  $ChatErrorText = "������������ � ����� ����� ��� ����������";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }
*/

if (!ChatNickExists($Nick))
 {
  // ��������� "Refresh ��� RegIn" - ���� ������������ ��� ����
  // ��� �� ����� ��������� ��������, � �����, �� �����
  // �������� ���� �������� ����� ������� �������
  if (!ExtFromChatUserAddIsOk($ErrorMess,$Nick,$EMail))
   {
    ChatServerLogWriteWarning("Error calling ext_link(UserAddIsOk):".$ErrorMess);
    $ChatErrorText = "������ ����������:".$ErrorMess;
    include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
    exit;
   }
 }

// �������� ������������
if (ChatUserAdd($Nick,$Password,$EMail,$Color,$Gender,$SelfNotes))
 {
  // ������������ ������� �������� � ���
  // �������� ������� �������

  if (!ExtFromChatUserAdd($ErrorMess,$Nick,$Password,null,$EMail))
   {
    ChatServerLogWriteWarning("Error calling ext_link(UserAdd):".$ErrorMess);
    ChatUserDelete($Nick); // ignore result

    $ChatErrorText = "������ ����������:".$ErrorMess;
    include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
    exit;
   }
 }
else
 {
  // ������ ����� ������������ ��� ���������� ��� �� ����� ���� ��������
  
  // *** ��������� ���������� ������� ������� ������ *** Start

  // ��������� ������ � ���� �������� ������ - ���� ������ ��������
  // ��������� ��� �������� ���������� ��� �����
  // �� ������ ��������� ���. (��� ������� ��� ���� ������)
  // (��������� Ctrl+Refresh ����� ������ � ��������)

  // �������� "��� ����� �������"

  if (!ChatPasswordValid($Nick,$Password))
   {
    // ������ �� ������� - ������ ��� ��
    // ������� ���������� �����, � ����������� ������ ���� � �����
    $ChatErrorText = "������������ � ����� ����� ��� ����������";
    include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
    exit;
   }

  $RealNick = ChatGetRealNick($Nick);

  if      ($RealNick == "")
   {
    // ������ ���������� ������ (�������)
    $ChatErrorText = "������ ����������� ��������� ����";
    include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
    exit;
   }

  if (!ChatStrNickStrValid($RealNick))
   {
    $ChatErrorText = "������������ ����������� ������� � ����";
    include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
    exit;
   }

  $SID = ChatGetRoomSID($RealNick,$Room);

  if ($SID == "") // �������� ������ ��� (���) ���
   {
    // �� ��������� �������������� ��������� �����
    // ���� ������������ �� ����� �� ���������
    // ����������� �����������

    // ��� ��� ���� ������, ����� ����� ������������ "��������" ������� ������
    // ����� �������, ���� ���� ������ ��������� � ������������ �����
    // ���� ��� �������� ����� � ����� ����, �� RegIn �� �������� �����
    // ����������

    $ChatErrorText = "������������ � ����� ����� ��� ����������";
    include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
    exit;
   }
  else
   {
    // *** ���������� ���������� ��������� ***
    // ���������� ��� ��� ������ � ����������� ����������� ��������
   }

  // *** ��������� ���������� ������� ������� ������ *** End

 }

$RealNick = ChatGetRealNick($Nick);

if      ($RealNick == "")
 {
  $ChatErrorText = "������ ����������� ��������� ����";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

if (!ChatStrNickStrValid($RealNick))
 {
  $ChatErrorText = "������������ ����������� ������� � ����";
  include(dirname(__FILE__)."/"."../tpl/crenew.htp.php");
  exit;
 }

$SID = ChatGetRoomSID($RealNick,$Room);

if ($SID == "") // Not logged yet
 {
  if (defined("ChatDelayRegInMessage"))
   {
    $SessionFlags = ChatConstSessionFlagPreparing;
   }
  else
   {
    $SessionFlags = ChatConstSessionFlagActive;
   }

  $SID = ChatCreateNewSID();
  ChatEnterToRoom($RealNick,$SID,$Room,$Color,$Topic,$SessionFlags);
  ChatFillConnectedUser_Vars($RealNick,$SID,$Room);
  ChatUpdateUserInfoByLogin($RealNick);

  if ($Room != "")
   {
    if (defined("ChatDelayRegInMessage"))
     {
      // We will send it on first Update/Reload
     }
    else
     {
      ChatRegInMessSend($Room,$User_Gender,$User_Color,$RealNick);
     }
   }
 }
else
 {
  // Just keep connected
  ChatUpdateUserInRoom($RealNick,$SID,$Room,$Color,$Topic);
  ChatFillConnectedUser_Vars($RealNick,$SID,$Room);
 }

if (defined("ChatFastBootRegInAction"))
 {
  // FAST BOOT!
  $ChatFastBootFunctionBodyStr = ChatMakeFastBootStr($RealNick,$SID,$Room);
 }
else
 {
  $ChatFastBootFunctionBodyStr = "";
 }

//Boot using supplied ClientMode
if ($ClientMode == 2)
 {
  include(dirname(__FILE__)."/"."../tpl/cc_bod2.htp.php");
 }
else
 {
  include(dirname(__FILE__)."/"."../tpl/cc_bod.htp.php");
 }
?>
