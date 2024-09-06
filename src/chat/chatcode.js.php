<?
// ******* Client Chat-To-Code mapper **********
?>

<?
include_once(dirname(__FILE__)."/"."../_cgi/inc/chatcodeconst.inc.php"); // Default
?>

// [script language="JavaScript"]
<? /* --- Returns 0 if code unsupported --- */ ?>
function ChatCharToCode(CharValue)
 {
  var MapStringFrom = <? 
  // PHP Code
  echo "\"";
  for ($CharIndex = ChatConstCharASCIIConvLowBound;
       $CharIndex <= ChatConstCharASCIIConvUpBound;
       $CharIndex++)
   {
    if ((chr($CharIndex) == "\"") || 
        (chr($CharIndex) == "\\"))
     {
      echo "\\";
     }
    echo chr($CharIndex);
   }
  echo "\"";?>;

  var MapStringTo   = <? 
  // PHP Code
  echo "\"";
  for ($CharIndex = ChatConstCharASCIIConvLowBound;
       $CharIndex <= ChatConstCharASCIIConvUpBound;
       $CharIndex++)
   {
    echo "".sprintf("%02x",$CharIndex);
   }
  echo "\"";?>;

  if (CharValue.length != 1)
   {
    return(0);
   }

  var MapIndexFrom = MapStringFrom.indexOf(CharValue);
  if (MapIndexFrom == -1)
   {  
    return(0);
   }

  var MapDataTo = MapStringTo.substring(MapIndexFrom*2,MapIndexFrom*2+2);
  var MapValueTo = parseInt(MapDataTo,16);

  if (isNaN(MapValueTo))
   {
    return(0);
   }

  if (MapValueTo == 0)
   {
    return(0);
   }

  return(MapValueTo);
 }

// [/script]
