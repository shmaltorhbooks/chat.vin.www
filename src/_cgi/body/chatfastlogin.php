<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
if (!ChatStrNickStrValid($Nick))
 {
  $Nick = trim(ChatNickPurgeSignToSpace($Nick));

  if (!ChatStrNickStrValid($Nick))
   {
    $ChatErrorText = "������������ ����������� ������� � ����";
    include(dirname(__FILE__)."/"."../tpl/cie.htp.php");
    exit;
   }
 }

if (!ChatNickExists($Nick))
 {
  // ������������ � ���� ��� ������
  include(dirname(__FILE__)."/"."../tpl/cr_new.htp.php");
  exit;
 }

if (strlen(ChatNickToVisual($Nick)) == 0)
 {
  $ChatErrorText = "������ ��� ���������� ��� �� ��������";
  include(dirname(__FILE__)."/"."../tpl/cie.htp.php");
  exit;
 }

if (!ChatPasswordValid($Nick,$Password))
 {
  ChatErrorLog("Invalid password to [$Nick] supplied");
  $ChatErrorText = "�������� ������";
  include(dirname(__FILE__)."/"."../tpl/cie.htp.php");
  exit;
 }

$RealNick = ChatGetRealNick($Nick);

if      ($RealNick == "")
 {
  $ChatErrorText = "������ ����������� ��������� ����";
  include(dirname(__FILE__)."/"."../tpl/cie.htp.php");
  exit;
 }

if (!ChatStrNickStrValid($RealNick))
 {
  $ChatErrorText = "������������ ����������� ������� � ����";
  include(dirname(__FILE__)."/"."../tpl/cie.htp.php");
  exit;
 }

if (!ChatUserIsAdmin($RealNick))
 {
  if (ChatUserLogInLockedByNick($RealNick))
   {
    $ChatErrorText = "���� � ��� � ����� ���� �������� ������";
    include(dirname(__FILE__)."/"."../tpl/cie.htp.php");
    exit;
   }

  if (ChatUserLogInLockedByIP())
   {
    $ChatErrorText = "���� � ��� � ����� IP ������ �������� ������";
    include(dirname(__FILE__)."/"."../tpl/cie.htp.php");
    exit;
   }
 }

// All done - go west!

if (!isset($Room))
 {
  $Room = ""; // Teoreticaly we can use this _cgi to enter to Sets
 }

$SID = ChatGetRoomSID($RealNick,$Room);

if ($SID == "") // Not logged yet
 {
  $SID = ChatCreateNewSID();

  if (defined("ChatDelayLogInMessage"))
   {
    $SessionFlags = ChatConstSessionFlagPreparing;
   }
  else
   {
    $SessionFlags = ChatConstSessionFlagActive;
   }

  ChatEnterToRoom($RealNick,$SID,$Room,$Color,$Topic,$SessionFlags);
  ChatFillConnectedUser_Vars($RealNick,$SID,$Room);
  ChatUpdateUserInfoByLogin($RealNick);

  if ($Room != "")
   {
    if (defined("ChatDelayLogInMessage"))
     {
      // We will send it on first Update/Reload
     }
    else
     {
      ChatLogInMessSend($Room,$User_Gender,$User_Color,$RealNick);
     }
   }
 }
else
 {
  // Just keep connected
  ChatUpdateUserInRoom($RealNick,$SID,$Room,$Color,$Topic);
  ChatFillConnectedUser_Vars($RealNick,$SID,$Room);
 }

if ($Room == "")
 {
  include(dirname(__FILE__)."/"."../tpl/cs_set.htp.php");
 }
else
 {
  if (defined("ChatFastBootLogInAction"))
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
 }
?>
