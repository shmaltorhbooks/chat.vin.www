<?

function ChatStopWordTextPreparePreFormatSpacesSoft($MessStr)
 {
  $search   = array ("{^\s+}","{\s+$}","{\s+}");
  $replace  = array (" "," "," ");
  $RetMess  = preg_replace($search,$replace,$MessStr);
  return $RetMess;
 }

function ChatStopWordTextPreparePreFormatSpacesHard($MessStr)
 {
  $search   = array ("{^\s+}","{\s+$}","{\s+}");
  $replace  = array ("","","");
  $RetMess  = preg_replace($search,$replace,$MessStr);
  return $RetMess;
 }

function ChatStopWordTextPreparePreFormatSoft($InStr)
 {
  $Result = $InStr;
  $Result = preg_replace("/[\\x00-\\x1F]/"," ",$Result);
  $Result = ChatStrToUp($Result);
  $Result = ChatStopWordTranslate($Result);
  $Result = ChatNickPurgeSignToSpace($Result," ");
  $Result = ChatStopWordTextPreparePreFormatSpacesSoft($Result);
  $Result = ChatStrToLow($Result);

  return ($Result);
 }

function ChatStopWordTextPreparePreFormatHard($InSoftStr)
 {
  $Result = $InSoftStr;
  $Result = ChatStopWordTextPreparePreFormatSpacesHard($Result);

  return ($Result);
 }

?>
