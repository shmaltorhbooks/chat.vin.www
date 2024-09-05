<?
// --- Firewaill setup functions ----------------------------------------------

include_once(dirname(__FILE__)."/".'../usr/fw_usrsetup.php');

define("FirewallSelfGLOBALSName","GLOBALS");

// === Firewall Setup (add-on) ===

function FirewallHTTPGlobalsNamesArray()
 {
  return
   (array
     (
      "HTTP_POST_VARS",
      "HTTP_GET_VARS",
      "HTTP_COOKIE_VARS",
      "HTTP_SERVER_VARS",
      "HTTP_ENV_VARS",
      "HTTP_POST_FILES",
      "HTTP_SESSION_VARS"
     )
   );
 }

// Assign defaults, if that constants not assigned at usr/fw_setup.php

if (!defined("FirewallDieErrorShowToUser"))
 {
  define("FirewallDieErrorShowToUser",FALSE);
 }

if (!defined("FirewallDieAtDataErrorReport"))
 {
  define("FirewallDieAtDataErrorReport",TRUE);
 }

if (!defined("FirewallDieAtVarErrorReport"))
 {
  define("FirewallDieAtVarErrorReport",TRUE);
 }

if (!defined("FirewallReportVarNotes"))
 {
  define("FirewallReportVarNotes",FALSE);
 }

if (!defined("FirewallReportVarChange"))
 {
  define("FirewallReportVarChange",FALSE);
 }

if (!defined("FirewallReportVarChangeSkipSubmit"))
 {
  define("FirewallReportVarChangeSkipSubmit",TRUE);
 }

// +++ Firewaill setup functions ++++++++++++++++++++++++++++++++++++++++++++++
?>
