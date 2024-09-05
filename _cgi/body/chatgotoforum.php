<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
if (!isset($Room) || ($Room == ""))
 {
  $Room = ChatConstDefaultChatRoom;
 }

if (!ChatFillConnectedUser_Vars ($Nick, $SID,$Room))
 {
  $ChatErrorText = "Неверный запрос";
  include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
  exit;
 }

$RealNick = ChatGetRealNick($Nick);

if      ($RealNick == "")
 {
  $ChatErrorText = "Ошибка определения контекста ника";
  include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
  exit;
 }

if (!ChatUserRecall($RealNick,$ChatDBNick,$ChatDBPasswordMD5,$ChatDBNickColor,$ChatDBEMail,$ChatDBGender,$ChatDBSelfNotes))
 {
  $ChatErrorText = "Ошибка загрузки данных перехода";
  include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
  exit;
 }

define('ExtToForum',1);
define('ChatForumDir',dirname(__FILE__)."/"."../..");

$ExtUserName        = $ChatDBNick;
$ExtUserPasswordMD5 = $ChatDBPasswordMD5;
$ExtRedirect        = "";

include(ChatForumDir."/"."forum/ext_link/extlogin2.php");

// Teoreticaly, we will never reach this
exit;
?>
