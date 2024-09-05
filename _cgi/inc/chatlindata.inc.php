<?
include_once(dirname(__FILE__)."/"."chatlinconst.inc.php");
include_once(dirname(__FILE__)."/"."chatlindatafunc.inc.php");

// Returns uncrypted password
function ChatLogDataParse($LogDataArray,$AuxArray,$PreSID,$Nick)
 {
  $ResultDataSize  = ChatConstLogDataMaxSize;
  $ResultDataArray = Array();

  if ((count($LogDataArray) <= 0) ||
      (strlen($PreSID) <= 0) ||
//    (strlen($Nick) <= 0) ||
      (count($AuxArray) <= 0))
   {
    return(""); // invalid data given
   }

  if (count($LogDataArray) != $ResultDataSize)
   {
    return(""); // invalid log array size
   }

  // Data prepare

  for ($Index = 0;$Index < $ResultDataSize;$Index++)
   {
    $ResultDataArray[$Index] = $LogDataArray[$Index];
   }

  // PostUp prepare
  $PostUp = 0;

  $Value = $PreSID;
  $Limit = strlen($Value);
  if ($Limit > 0)
   {
    for ($Index = 0;$Index < $Limit;$Index++)
     {
      $CharCode = ord(substr($Value,$Index,1));
      $PostUp = ($PostUp ^ $CharCode) & 0xFF;
     }
   }

  $Value = $Nick;
  $Limit = strlen($Value);
  if ($Limit > 0)
   {
    for ($Index = 0;$Index < $Limit;$Index++)
     {
      $CharCode = ord(substr($Value,$Index,1));
      $PostUp = ($PostUp ^ $CharCode) & 0xFF;
     }
   }

  // PostUp process (in reverce order)

  $AccValue = 0;
  $PassMax  = ChatConstLogDataPostPassMax;

  for ($PassIndex = 0;$PassIndex < $PassMax;$PassIndex++)
   {
    for ($Index = 0;$Index < $ResultDataSize;$Index++)
     {
      $ResultDataArray[$Index] = ($ResultDataArray[$Index] ^ $PostUp) & 0xFF;
//    $ResultDataArray[$Index] = ($ResultDataArray[$Index] ^ $AccValue) & 0xFF;

//    $AccValue = ($AccValue + $ResultDataArray[$Index]) & 0xFF;

      $PostUp = ($PostUp & 0xFF) << 1;
      $PostUp = ($PostUp | (($PostUp & 0x100) >> 8)) & 0xFF;
     }

    if (($ResultDataSize % 8) == 0)
     {
      $PostUp = ($PostUp & 0xFF) << 1;
      $PostUp = ($PostUp | (($PostUp & 0x100) >> 8)) & 0xFF;
     }
   }

  // Array process (in reverce order)

  $Value = $AuxArray;
  $Limit = count($Value);
  if ($Limit > 0)
   {
    for ($Index = 0;$Index < $ResultDataSize;$Index++)
     {
      $CharCode = $Value[$Index % $Limit];
      $ResultDataArray[$Index] = ($ResultDataArray[$Index] ^ $CharCode) & 0xFF;
     }
   }

  $Value = $Nick;
  $Limit = strlen($Value);
  if ($Limit > 0)
   {
    for ($Index = 0;$Index < $ResultDataSize;$Index++)
     {
      $CharCode = ord(substr($Value,$Index % $Limit,1));
      $ResultDataArray[$Index] = ($ResultDataArray[$Index] ^ $CharCode) & 0xFF;
     }
   }

  $Value = $PreSID;
  $Limit = strlen($Value);
  if ($Limit > 0)
   {
    for ($Index = 0;$Index < $ResultDataSize;$Index++)
     {
      $CharCode = ord(substr($Value,$Index % $Limit,1));
      $ResultDataArray[$Index] = ($ResultDataArray[$Index] ^ $CharCode) & 0xFF;
     }
   }

  $Result = "";

  $Index = 0;
  while(($ResultDataArray[$Index] != 0) && ($Index < $ResultDataSize))
   {
    if ($ResultDataArray[$Index] < ChatConstCharASCIIConvLowBound)
     {
      return(""); // invalid char found
     }

    if ($ResultDataArray[$Index] > ChatConstCharASCIIConvUpBound)
     {
      return(""); // invalid char found
     }

    $Result .= chr($ResultDataArray[$Index]);
    $Index++;
   }

  if ($Index >= $ResultDataSize)
   {
    // end of line marker (0) not found
    // Note: LogDataArray MUST be at leat 1 item longer
    // that Password max length
    return(""); 
   }

  // Only zero must be remain
  while($Index < $ResultDataSize)
   {
    if ($ResultDataArray[$Index] != 0)
     {
      return(""); // Non zero found after zero
     }

    $Index++;
   }

  return($Result);
 }
?>
