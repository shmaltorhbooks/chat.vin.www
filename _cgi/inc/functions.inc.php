<?
include_once(dirname(__FILE__)."/"."funcstrlocale.inc.php");

// Kill all spaces
function ChatStrToSimple($MessStr)
 {
  $search  = array ("{^\s+}","{\s+$}","{\s+}");
  $replace = array ("","","");
  $RetMess  = preg_replace($search,$replace,$MessStr);
  return $RetMess;
 }

function ChatLowToSpace($InStr)
 {
  return (preg_replace("/[\\x00-\\x1F]/"," ",$InStr));
 }

function ChatNickEqual   ($FirstNick, $SecondNick)
 {
  $VisualFN = ChatNickToVisual($FirstNick);
  $VisualSN = ChatNickToVisual($SecondNick);

  if (strcmp($VisualFN,$VisualSN) == 0)
   {
    /* DEBUG
    echo "Source    : ". $FirstNick ." == ". $SecondNick ."\n";
    echo "Visual    : ". $VisualFN  ." == ". $VisualSN   ."\n";
    */
    return (true);
   }
  else
   {
    /* DEBUG
    echo "Source    : ". $FirstNick ." != ". $SecondNick ."\n";
    echo "Visual    : ". $VisualFN  ." != ". $VisualSN   ."\n";
    */
    return (false);
   }
 }

function  ChatStrNickStrValid ($SourceStr)
 {
  $Limit = strlen($SourceStr);
  $Index = 0;
  $Valid = true;

  while(($Index < $Limit) && ($Valid))
   {
    if (!ChatNickCharAllowed($SourceStr{$Index}))
     {
      $Valid = false;
     }
    else
     {
      $Index++;
     }
   }

  if ($Valid)
   {
    if (trim($SourceStr) == "")
     {
      $Valid = false;
     }
   }

  return($Valid);
 }


function ChatCreateNewSID ()
 {
  // генерация типа случайного SID
  $retSID = md5(uniqid(rand(),1));
  $retSID = substr($retSID,0,ChatConstSIDMaxLength);
  return($retSID);
 }


function ChatProbabilityMatch($PercentValue)
 {
  $AddScale = 100; // 320 is Ok for ChatConstProbMaxValue=100 (32000max)

  $Value = rand(0,(int)(ChatConstProbMaxValue*(int)$AddScale));
  $Value = (float)(((float) $Value) / ((float)$AddScale));

  if ($Value <= $PercentValue)
   {
    return(TRUE);
   }
  else
   {
    return(FALSE);
   }
 }


function ChatStringSplitByPicture ($SrcStr,$PictuceStr)
 {
  if ($SrcStr == "")
   {
    return("");
   }

  if ($PictuceStr == "")
   {
    return($SrcStr);
   }

  $Length = strlen($PictuceStr);
  $Result = "";

  $SrcIndex = 0;
  for ($Index = 0;$Index < $Length;$Index++)
   {
    if ($SrcIndex >= strlen($SrcStr))
     {
      // No more source chars. just exit from for
      $Index = $Length;
     }
    else
     {
      if      ($PictuceStr{$Index} == "*") 
       {
        // Skip source char
        $SrcIndex++;
       }
      else if ($PictuceStr{$Index} == "!") 
       {
        // Stop conversion. ignore ramain of line
        $Index = $Length;
        $SrcIndex = strlen($SrcStr);
       }
      else if ($PictuceStr{$Index} == "_")
       {
        // Copy source char
        if ($SrcIndex < strlen($SrcStr))
         {
          $Result .= $SrcStr{$SrcIndex};
         }
        $SrcIndex++;
       }
      else
       {
        // Put picture char, if source still available
        if ($SrcIndex < strlen($SrcStr))
         {
          $Result .= $PictuceStr{$Index};
         }
       }
     }
   }

  return($Result);
 }


?>
