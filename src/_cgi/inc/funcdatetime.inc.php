<?
include_once(dirname(__FILE__)."/"."constant.inc.php");
include_once(dirname(__FILE__)."/"."functions.inc.php");


 // Date&Time manupulation
 // {PHP}TimeStamp,JSMessTime,SQLDateTime,SQLTimeStamp conversion


function ChatCurrTimeStamp()
 {
  return(time());
 }

function ChatMakeSQLTimeStampByTimeStamp($TimeStamp = "")
 {
  if ($TimeStamp == "")
   {
    $TimeStamp = ChatCurrTimeStamp();
   }

  return(date("YmdHis",$TimeStamp));
 }


function ChatMakeSQLDateTimeByTimeStamp($TimeStamp = "")
 {
  if ($TimeStamp == "")
   {
    $TimeStamp = ChatCurrTimeStamp();
   }

  return(date("Y-m-d H:i:s",$TimeStamp));
 }


function ChatMakeJSMessTimeBySQLTimeStamp($TimeStamp)
 {
  //  YYYYMMDDHHMMSS
  // '20030804192817'
  //  0000000000011111111
  //  0123456789012345678
  
  $r_Time = $TimeStamp{8}.$TimeStamp{9}.":".$TimeStamp{10}.$TimeStamp{11};
  return($r_Time);
 }


function ChatMakeJSMessTimeBySQLDateTime($DateTime)
 {
  //  YYYY MM DD HH MM SS
  // '2003-08-04 19:28:17'
  //  0000000000011111111
  //  0123456789012345678
  
  $r_Time = $DateTime{11}.$DateTime{12}.":".$DateTime{14}.$DateTime{15};
  return($r_Time);
 }


function ChatMakeTimeStampBySQLTimeStamp($TimeStamp)
 {
  return(strtotime(ChatStringSplitByPicture($TimeStamp,"____-__-__ __:__:__")));
 }


function ChatMakeTimeStampBySQLDateTime($DateTime)
 {
  return(strtotime($DateTime));
 }


function ChatMakeViewDateTimeByTimeStamp($TimeStamp)
 {
  if ($TimeStamp == "")
   {
    $TimeStamp = ChatCurrTimeStamp();
   }

  return(date("Y-m-d H:i:s",$TimeStamp));
 }


function ChatMakeViewDateTimeBySQLTimeStamp($TimeStamp)
 {
  return(ChatStringSplitByPicture($TimeStamp,"____-__-__ __:__:__"));
 }


function ChatMakeViewDateTimeBySQLDateTime($DateTime)
 {
  return($DateTime);
 }


function ChatViewSecoundsAsText($SecCount)
 {
  if ($SecCount == "")
   {
    return("");
   }
  else
   {
    $MinCount  = (int)($SecCount  / 60);
    $SecCount  = (int)($SecCount  % 60);

    $HourCount = (int)($MinCount  / 60);
    $MinCount  = (int)($MinCount  % 60);

    $DaysCount = (int)($HourCount / 24);
    $HourCount = (int)($HourCount % 24);

    if ($DaysCount > 0)
     {
      if ($Result != "")
       {
        $Result .= " ";
       }

      $Result .= $DaysCount."сут";
     }

    if ($HourCount > 0)
     {
      if ($Result != "")
       {
        $Result .= " ";
       }

      $Result .= $HourCount."час";
     }

    if ($MinCount > 0)
     {
      if ($Result != "")
       {
        $Result .= " ";
       }

      $Result .= $MinCount."мин";
     }

    if ($DaysCount <= 0)
     {
      // Выводим секунды если еще не пошли сутки
      if ($SecCount > 0)
       {
        if ($Result != "")
         {
          $Result .= " ";
         }

        $Result .= $SecCount."сек";
       }
     }

    return($Result);
   }
 }


?>
