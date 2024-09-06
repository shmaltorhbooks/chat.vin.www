<?
include_once(dirname(__FILE__)."/"."stopwordlist.inc.php");
include_once(dirname(__FILE__)."/"."stopwordstr.inc.php");

function ChatStopWordProtectPresent($MessText)
 {
  global $StopWordList;

  $TextStr = " ".ChatStopWordTextPreparePreFormatSoft($MessText)." ";

  foreach ($StopWordList as $StopWord)
   {
    if (!strstr($TextStr,$StopWord))
     {
      // not found
     }
    else
     {
      return(true);
     }
   }

  return(false);
 }

function ChatStopWordProtectSendOk($RealNick,$Room,$MessTo,$MessText,$MessModel)
 {
  if ($MessModel == ChatConstMessModelPrivate)
   {
    return(true);
   }

  if (ChatStopWordProtectPresent($MessText))
   {
    return(false);
   }

  return(true);
 }

function ChatStopWordProtectNickLogInOk($RealNick,$Room)
 {
  if (ChatStopWordProtectPresent($RealNick))
   {
    return(false);
   }

  return(true);
 }

function ChatStopWordProtectNickTopicLogInOk($RealNick,$Room,$Topic)
 {
  if (ChatStopWordProtectPresent($Topic))
   {
    return(false);
   }

  return(true);
 }

function ChatStopWordProtectNickSelfNotesOk($RealNick,$SelfNotes)
 {
  if (ChatStopWordProtectPresent($SelfNotes))
   {
    return(false);
   }

  return(true);
 }

?>