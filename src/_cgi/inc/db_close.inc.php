<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
include_once(dirname(__FILE__)."/"."db_setup.inc.php");
include_once(dirname(__FILE__)."/"."chatlog.inc.php");

global $ChatDBLinkId;

if (isset($ChatDBLinkId))
 {
  mysql_close($ChatDBLinkId);
 }
?>
