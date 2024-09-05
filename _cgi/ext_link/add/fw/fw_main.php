<?
/*
   ***************************** PHP Firewall *********************************
        Written by Serg Ageyev (boss@viasoft.com.ua), Vinnitsya, Ukraine
                              Version 1.0 (2003)
                             This is free software
   ****************************************************************************
*/
?>
<?
if (!defined('FirewallIncluded'))
 {
  include_once(dirname(__FILE__)."/".'lib/fw_setup.php');
  include_once(dirname(__FILE__)."/".'lib/fw_debug.php');
  include_once(dirname(__FILE__)."/".'lib/fw_log.php');
  include_once(dirname(__FILE__)."/".'lib/fw_utils.php');
  include_once(dirname(__FILE__)."/".'lib/fw_vartype.php');

  include_once(dirname(__FILE__)."/".'fw_protect.php');
  include_once(dirname(__FILE__)."/".'fw_ignore.php');
  include_once(dirname(__FILE__)."/".'fw_request.php');
  include_once(dirname(__FILE__)."/".'fw_varcheck.php');

  define('FirewallIncluded',1);
 }
?>
<?
// Example:
//FirewallProtectSelfGlobalsReAssign();
//FirewallProtectHTTPGlobalsReAssign();
//FirewallProtectPOSTFiles();
//FirewallProtectInvalidVarNames();

//FirewallIgnoreRegisterGlobals();
//FirewallIgnoreCookie();
//FirewallIgnoreGetVars();
//FirewallIgnoreSessionVars();

//FirewallCheckVars(VarsCheckSetUp()); // note:VarsCheckSetUp must return array
?>
