<?
include_once(dirname(__FILE__)."/".'fw_setup.php');
include_once(dirname(__FILE__)."/".'fw_log.php');
include_once(dirname(__FILE__)."/".'fw_debug.php');

// --- Firewall Support functions ---------------------------------------------

function FirewallValidPHPID($VarName)
 {
  if      (trim($VarName) == "")
   {
    return(false);
   }
  else if (!preg_match("/^[_|a-z|A-Z|\\x80-\\xFF]{1,1}[_|0-9|a-z|A-Z|\\x80-\\xFF]*$/D",$VarName))
   {
    return(false);
   }
  else
   {
    return(true);
   }
 }

function FirewallValidHTTPID($VarName)
 {
  if      (trim($VarName) == "")
   {
    return(false);
   }
  else if (!preg_match("/^[_\\-|0-9|a-z|A-Z|\\x80-\\xFF|\\x5B|\\x5D]*$/D",$VarName))
   {
    return(false);
   }
  else
   {
    return(true);
   }
 }

function FirewallValidPOSTTmpFileName($FileName)
 {
  if      (trim($FileName) == "")
   {
    return(false);
   }
  else if (!preg_match
            ("/^"
            ."(?:[a-z|A-Z]{1,1}:){0,1}"        // Windows drive (optional)
            ."(?:~\\/\\\\){0,1}"               // "~" or "/" or "\" as root dir
            .               "[_\\-|0-9|a-z|A-Z|\\x80-\\xFF]+" // first file name item
            ."(?:[\\/\\\\\\.][_\\-|0-9|a-z|A-Z|\\x80-\\xFF]+)*"
            ."$/D",
            $FileName))
   {
    return(false);
   }
  else if (preg_match
            ("/^"
            ."(\\s)*"                                       // allow even whitespace
            ."(?:http(?:\\s)*:|ftp(?:\\s)*:|\\/dev\\/){1,}" // disalow that!
            .".*" // anything else at tail
            ."$/iD",
            $FileName))
   {
    return(false);
   }
  else
   {
    return(true);
   }
 }

function FirewallValidPOSTRemoteFileName($FileName)
 {
  // Note: that's remote system file name. 
  // So we can expect many intresting chars here
  // Realy, we must block only commonly used path separators here "/\:~"
  // and invalid file name characters such as < 19H

  if      (trim($FileName) == "")
   {
    return(false);
   }
  else if (!preg_match
            ("/^"
            .        "[_\\-\\!\\x20|0-9|a-z|A-Z|\\x80-\\xFF]+" // first file name item
            ."(?:[\\.][_\\-\\!\\x20|0-9|a-z|A-Z|\\x80-\\xFF]+)*"
            ."$/D",
            $FileName))
   {
    // But for now, we use more strict checks
    return(false);
   }
  else if (preg_match
            ("/"
            ."["
              ."\\x00-\\x19"
              ."|\\~|\\/|\\\\"
              ."|\\||\\>|\\<"
              ."|\\;|\\:|\\,|\\!"
            ."]" // disalow that!
            ."/D",
            $FileName))
   {
    return(false);
   }
  else
   {
    return(true);
   }
 }

function FirewallPRegExpMatch($Text,$PRegExp)
 {
  if (preg_match($PRegExp,$Text))
   {
    return(true);
   }
  else
   {
    return(false);
   }
 }

function FirewallERegExpMatch($Text,$ERegExp)
 {
  if (ereg($ERegExp,$Text))
   {
    return(true);
   }
  else
   {
    return(false);
   }
 }

function FirewallRegExpMatch($Text,$RegExp)
 {
  if ((strlen($RegExp) > 0) && ($RegExp[0] == '/'))
   {
    return(FirewallPRegExpMatch($Text,$RegExp));
   }
  else
   {
    return(FirewallERegExpMatch($Text,$RegExp));
   }
 }


function FirewallMicroTimeStamp()
 { 
  // from PHP example
  list($usec, $sec) = explode(" ",microtime()); 
  return ((float)$usec + (float)$sec); 
 } 


function FirewallGlobalsSaveVar(&$GlobalsVarSaveArray,$GlobalVarName)
 {
  if (isset($GLOBALS[$GlobalVarName]))
   {
    $GlobalsVarSaveArray[$GlobalVarName] = $GLOBALS[$GlobalVarName];
   }
 }


function FirewallGlobalsRestore($GlobalsVarSaveArray)
 {
  foreach($GlobalsVarSaveArray as $VarName => $VarValue)
   {
    $GLOBALS[$VarName] = $VarValue;
   }
 }


// +++ Firewall Support functions +++++++++++++++++++++++++++++++++++++++++++++

?>
