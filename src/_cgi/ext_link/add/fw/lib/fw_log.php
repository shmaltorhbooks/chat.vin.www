<?
// --- Firewaill error loggin functions ---------------------------------------

include_once(dirname(__FILE__)."/".'fw_setup.php');
include_once(dirname(__FILE__)."/".'../usr/fw_usrlog.php');

function FirewallErrorLogWrite($ErrorStr)
 {
  FirewallUserErrorLogWrite($ErrorStr);
 }

function FirewallDieWithMessage($ErrorStr)
 {
  FirewallUserErrorLogWrite ($ErrorStr);
  FirewallUserDieWithMessage($ErrorStr);

  // Teoreticaly we will never reach control here!
  // GLOBAL BUG TRACKING FIX

  error_log("Firewall:UNHANDLED DIE WITH:".$ErrorStr." IP:".$GLOBALS["HTTP_SERVER_VARS"]["REMOTE_ADDR"],0);
  die("Unhandled error!");
 }

// === Add-On for Protection-level functions ==================================

function FirewallReportError($ErrorStr,$DieOnError = FirewallDieAtDataErrorReport)
 {
  if (!$DieOnError)
   {
    FirewallErrorLogWrite($ErrorStr);
   }
  else
   {
    FirewallDieWithMessage($ErrorStr);
   }
 }

function FirewallReportVarError($ErrorStr,$VarName = "",$DieOnError = FirewallDieAtVarErrors)
 {
  $ErrorStr = "CheckUp for var [".$VarName."] failed:".$ErrorStr;

  if (!$DieOnError)
   {
    FirewallErrorLogWrite($ErrorStr);
   }
  else
   {
    FirewallDieWithMessage($ErrorStr);
   }
 }

// +++ Firewaill error loggin functions +++++++++++++++++++++++++++++++++++++++
?>
