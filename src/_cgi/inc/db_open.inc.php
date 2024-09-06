<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
include_once(dirname(__FILE__)."/"."db_setup.inc.php");
include_once(dirname(__FILE__)."/"."chatlog.inc.php");

global $ChatDBLinkId; // Need to be default link for last engine part

$ChatDBLinkId =
//   mysql_pconnect(ChatConstSetupDBHost,ChatConstSetupDBUser,ChatConstSetupDBPassword);
     mysql_connect (ChatConstSetupDBHost,ChatConstSetupDBUser,ChatConstSetupDBPassword);

if (!$ChatDBLinkId)
 {
  ChatSQLDie2Log("Unable to connect to db","");
  // BUG TRAP
  $ChatDBLinkId = null;
 }
else
 {
  if (!mysql_select_db(ChatConstSetupDBName,$ChatDBLinkId))
   {
    ChatSQLDie2Log("Unable to select database","");
    // BUG TRAP
    mysql_close($ChatDBLinkId);
    $ChatDBLinkId = null;
   }
 }
?>
