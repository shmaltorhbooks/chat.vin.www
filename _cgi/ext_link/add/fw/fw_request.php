<?
/*
 ---------------------- MAIN:[FirewallRequest*] -------------------------------
*/
?>
<?
include_once(dirname(__FILE__)."/".'lib/fw_setup.php');
include_once(dirname(__FILE__)."/".'lib/fw_log.php');
include_once(dirname(__FILE__)."/".'lib/fw_debug.php');
include_once(dirname(__FILE__)."/".'lib/fw_utils.php');

// --- "Request" control funtions - check witch script can be served ----------

function FirewallRequestAccept($DieOnError = FirewallDieAtDataErrorReport)
 {
  // *** Call this functions in begin of scripts that can be called by user

  if (defined("FirewallRequestIsAcceptedFlag"))
   {
    FirewallReportError
     (""
     ."Request allready accepted - Allready in internal zone"
     ,$DieOnError);
   }

  define("FirewallRequestIsAcceptedFlag");
 }


function FirewallRequestAcceptDone()
 {

  // *** Returns TRUE if request allready accepted

  if (defined("FirewallRequestIsAcceptedFlag"))
   {
    return(TRUE);
   }
  else
   {
    return(FALSE);
   }
 }

function FirewallRequestDeny($DieOnError = FirewallDieAtDataErrorReport)
 {
  // *** Call this functions in begin of modules or scripts 
  //     that must not be called by user

  if (!defined("FirewallRequestIsAcceptedFlag"))
   {
    FirewallReportError
     (""
     ."Request do not accepted - this file cannot be served directly"
     ,$DieOnError);
   }
 }


// +++ "Request" control funtions - check witch script can be served ++++++++++
?>
