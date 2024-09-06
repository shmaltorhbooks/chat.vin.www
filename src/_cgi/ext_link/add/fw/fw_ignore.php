<?
/*
 ---------------------- MAIN:[FirewallIgnore*] --------------------------------
*/
?>
<?
include_once(dirname(__FILE__)."/".'lib/fw_setup.php');
include_once(dirname(__FILE__)."/".'lib/fw_log.php');
include_once(dirname(__FILE__)."/".'lib/fw_debug.php');
include_once(dirname(__FILE__)."/".'lib/fw_utils.php');

// --- "Ignore" functions - protect server from process unused ----------------

function FirewallIgnoreRegisterGlobals($DieOnError = FirewallDieAtDataErrorReport)
 {
  // *** This functions unregister global vars
  //     regardless of "register_globals" set on or off

  $RealGlobalsNamesArray = FirewallHTTPGlobalsNamesArray();
  $RealGlobalsNamesArray[count($RealGlobalsNamesArray)] = FirewallSelfGLOBALSName;

//FirewallDebugPrintVar($GLOBALS,"UNREGISTERING GLOBALS:");
//FirewallDebugPrintVar($GLOBALS["GLOBALS"],"UNREGISTERING GLOBALS(DATA):");

  foreach($GLOBALS as $Key => $Value)
   {
    if (!in_array($Key,$RealGlobalsNamesArray,FALSE))
     {
      unset($GLOBALS[$Key]);
     }
   }
 }


function FirewallIgnoreCookie()
 {
  // *** This functions will ignore all incoming cookie

  if (isset($GLOBALS["HTTP_COOKIE_VARS"]))
   {
    $GLOBALS["HTTP_COOKIE_VARS"] = array();
   }

  if (isset($GLOBALS["HTTP_SERVER_VARS"]["HTTP_COOKIE"]))
   {
    unset($GLOBALS["HTTP_SERVER_VARS"]["HTTP_COOKIE"]);
   }
 }

function FirewallIgnoreGetVars()
 {

  // *** This functions will ignore all GET vars

  if (isset($GLOBALS["HTTP_GET_VARS"]))
   {
    $GLOBALS["HTTP_GET_VARS"] = array();
   }

  if (isset($GLOBALS["HTTP_SERVER_VARS"]["QUERY_STRING"]))
   {
    $GLOBALS["HTTP_SERVER_VARS"]["QUERY_STRING"] = "";
   }

  if (isset($GLOBALS["HTTP_SERVER_VARS"]["REQUEST_URI"]))
   {
    list($GLOBALS["HTTP_SERVER_VARS"]["REQUEST_URI"],) 
     = explode('?',$GLOBALS["HTTP_SERVER_VARS"]["REQUEST_URI"],2);
   }
 }

function FirewallIgnoreSessionVars($DieOnError = FirewallDieAtDataErrorReport)
 {

  // *** This functions will ignore all SESSION vars (and can trap if any found)

  if (isset($GLOBALS["HTTP_SESSION_VARS"]))
   {
    if (count($GLOBALS["HTTP_SESSION_VARS"]) != 0)
     {
      FirewallReportError
       (""
       ."Session vars found (do not expected at 'IgnoreSessionVars')"
       ,$DieOnError);
     }

    $GLOBALS["HTTP_SESSION_VARS"] = array();
   }
 }

// +++ Firewall protection functions ++++++++++++++++++++++++++++++++++++++++++
?>
