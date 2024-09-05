<?
// --- Firewall Error handlers [user can rewrite that] ------------------------

include_once(dirname(__FILE__)."/".'../lib/fw_setup.php');

// === External rotines - log report / die ===

function FirewallUserErrorLogWrite($ErrorStr)
 {
  error_log("Firewall:".$ErrorStr." IP:".$GLOBALS["HTTP_SERVER_VARS"]["REMOTE_ADDR"],0);
 }

function FirewallUserDieWithMessage($ErrorStr)
 {
  // Note:FirewallUserErrorLogWrite($ErrorStr) will be called before this

  if (!headers_sent())
   {
    if (!defined("FirewallDieErrorShowToUser") || (!FirewallDieErrorShowToUser))
     {
      header("HTTP/1.0 500 Internal Server error");
     }
    else
     {
      header("HTTP/1.0 500 Server Firewall block");
     }
   }

  if (!defined("FirewallDieErrorShowToUser") || (!FirewallDieErrorShowToUser))
   {
    echo "Internal server error!";
    echo "<br>\n";
   }
  else
   {
    echo "Server Firewall error:";
    echo "<br>\n";
    echo $ErrorStr;
    echo "<br>\n";
   }

  die;
 }

// +++ Firewall Error handlers [user can rewrite that] ++++++++++++++++++++++++
?>
