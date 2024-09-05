<?

// HASH SIGN SUPPORT BEGIN

//DEBUG
//include_once(dirname(__FILE__)."/"."chatlog.inc.php");

function ChatHashPrintIntSpec($Value,$Radix)
 {
  $Result = "";

  $Value = (int) $Value;
  $Radix = (int) $Radix;

  if ($Value < 0)
   {
    $Value = -$Value;
   }

  while($Value > 0)
   {
    $Result = chr(ord('A')+($Value % $Radix)).$Result;
    $Value  = $Value / $Radix;
    $Value  = (int)$Value;
   }

  if ($Result == "")
   {
    return("0");
   }
  else
   {
    return($Result);
   }
 }


define("ChatHashSpecRadix",19);
define("ChatHashIPMagicMask",0xFFAAFFAA);

define("ChatHashRandomPartMaxLength",5);
define("ChatHashRandomPartMaxValue",29999);

function ChatHashGetRandomStr()
 {
  list($usec, $sec) = explode(" ",microtime()); 
  $Value = 0;
  $Value += rand(0,ChatHashRandomPartMaxValue);
  $Value += (int)(((float)$usec) * 1000000); // usec in microsec
  $Value += (int)$sec;

  if ($Value < 0)
   {
    $Value = -$Value;
   }

  // To String
  $Value .= "";

  if (strlen($Value) > ChatHashRandomPartMaxLength)
   {
    $Value = substr(strrev($Value),0,ChatHashRandomPartMaxLength);
   }
  else
   {
    $Value = sprintf("%0".ChatHashRandomPartMaxLength."s",$Value);
   }

  return($Value);
 } 



define("ChatHashFullTimeoutInSec",3600);
define("ChatHashFastTimeoutInSec",15*60);

define("ChatHashTypeFull","A");
define("ChatHashTypeFast","B");


function ChatHashMake($IP,$LastTime,$HashType=ChatHashTypeFull)
 {
  $RandomStr = ChatHashGetRandomStr();
  $IPStr     = strrev(ChatHashPrintIntSpec(ip2long($IP) ^ ChatHashIPMagicMask,ChatHashSpecRadix));
  $TimeStr   = strrev(sprintf("%u",$LastTime));

  $Result    = $RandomStr.$HashType.$IPStr.$TimeStr;

//ChatClientMessShow("$RandomStr + $HashType + $IPStr + $TimeStr");

  $TmpResult = $Result.ChatHashPrintIntSpec(crc32($Result),ChatHashSpecRadix);
  $Result    = $Result.ChatHashPrintIntSpec(crc32($TmpResult),ChatHashSpecRadix);

  return($Result);
 }


function ChatHashCheckIsValid($HashValue,$IP,$CurrTime,$HashType=ChatHashTypeFull)
 {
  $SrcHashValue = $HashValue;

  if (strlen($HashValue) <= ChatHashRandomPartMaxLength)
   {
    return(FALSE);
   }
  else
   {
    $InRandomStr = substr($HashValue,0,ChatHashRandomPartMaxLength);
    $HashValue = substr
               ($HashValue,
                ChatHashRandomPartMaxLength,
                strlen($HashValue)-ChatHashRandomPartMaxLength);
   }

  if (strlen($HashValue) <= strlen($HashType))
   {
    return(FALSE);
   }
  else
   {
    $InHashType = substr($HashValue,0,strlen($HashType));
    $HashValue = substr
               ($HashValue,
                strlen($HashType),
                strlen($HashValue)-strlen($HashType));
   }

  if ($InHashType != $HashType)
   {
    return(FALSE);
   }

  $IPStr = strrev(ChatHashPrintIntSpec(ip2long($IP) ^ ChatHashIPMagicMask,ChatHashSpecRadix));

  if (strlen($HashValue) <= strlen($IPStr))
   {
    return(FALSE);
   }

  if (substr($HashValue,0,strlen($IPStr)) != $IPStr)
   {
    return(FALSE);
   }

  $InTailStr = substr($HashValue,strlen($IPStr),strlen($HashValue)-strlen($IPStr));
  $InTimeStr = preg_replace("/[A-Z]/","",$InTailStr);

  $Result = $InRandomStr.$InHashType.$IPStr.$InTimeStr;

//ChatClientMessShow("$InRandomStr + $InHashType + $IPStr + $InTimeStr");

  $TmpResult = $Result.ChatHashPrintIntSpec(crc32($Result),ChatHashSpecRadix);
  $Result    = $Result.ChatHashPrintIntSpec(crc32($TmpResult),ChatHashSpecRadix);

  if ($Result == $SrcHashValue)
   {
    $TimeStr = strrev($InTimeStr);

    $MaxTimeValue  = (int)$TimeStr;
    $CurrTimeValue = (int)$CurrTime;

//  echo $MaxTimeValue.":".$CurrTimeValue;

    if ($MaxTimeValue > $CurrTimeValue)
     {
      return(TRUE);
     }
    else
     {
      return(FALSE); // Timeout
     }
   }
  else
   {
    return(FALSE);
   }
 }


function ChatHashMakeFull()
 {
  global $HTTP_SERVER_VARS;

  return(ChatHashMake
          ($HTTP_SERVER_VARS["REMOTE_ADDR"],
            time()+ChatHashFullTimeoutInSec));
 }


function ChatHashCheckFullIsValid($HashValue)
 {
  global $HTTP_SERVER_VARS;

  return(ChatHashCheckIsValid
          ($HashValue,
           $HTTP_SERVER_VARS["REMOTE_ADDR"],
            time()));
 }


function ChatHashMakeFast()
 {
  global $HTTP_SERVER_VARS;

  return(ChatHashMake
          ($HTTP_SERVER_VARS["REMOTE_ADDR"],
            time()+ChatHashFastTimeoutInSec,
            ChatHashTypeFast));
 }


function ChatHashCheckFastIsValid($HashValue)
 {
  global $HTTP_SERVER_VARS;

  return(ChatHashCheckIsValid
          ($HashValue,
           $HTTP_SERVER_VARS["REMOTE_ADDR"],
            time(),
            ChatHashTypeFast));
 }


// HASH SIGN SUPPORT END

?>
