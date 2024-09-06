<?
define('ChatInside',1);

include_once(dirname(__FILE__)."/"."inc/setup.inc.php");
include_once(dirname(__FILE__)."/"."inc/chatlog.inc.php");

// Support functions

function   StatShowQueryResultCount($query,$prefix)
 {
  $resultlist = mysql_query($query);
  
  if (!$resultlist)
   {
    return("Error: Request ".$prefix." failed");
   }

  if ($resultdata = mysql_fetch_array($resultlist)) 
   {
    $mess = "".$prefix." : ".$resultdata[0]."";
   }
  else
   {
    return("Error: Request ".$prefix." returns empty recordset");
   }

  return($mess);
 }

function   StatTableRecCountShow($table)
 {
  $query = "select count(*) as ResultCount from ".$table."";
  $resultlist = mysql_query($query);
  
  if (!$resultlist)
   {
    return("Error: Table ".$table." : failed to select records count");
   }

  if ($resultdata = mysql_fetch_array($resultlist)) 
   {
    $mess = "Table ".$table." : ".$resultdata[ResultCount]." records";
   }
  else
   {
    $mess = "Table ".$table." : record count undefined";
   }

  echo $mess."<br>\n";
 }


// ***** MAIN BODY *****

include_once(dirname(__FILE__)."/"."inc/constant.inc.php");

include(dirname(__FILE__)."/"."inc/db_open.inc.php");

echo "<b>Chat statistics:</b>"."<br>\n";

StatTableRecCountShow("User");
StatTableRecCountShow("Mess");
StatTableRecCountShow("UserMaxEvent");
StatTableRecCountShow("Session");
StatTableRecCountShow("UserNotes");
StatTableRecCountShow("ChatRoom");
StatTableRecCountShow("MessAdmLog");
StatTableRecCountShow("MessAdmLogMess");
StatTableRecCountShow("UserIPDesc");
StatTableRecCountShow("BanIP");
StatTableRecCountShow("BanUser");

echo "<b>See you later!</b>"."<br>\n";

include(dirname(__FILE__)."/"."inc/db_close.inc.php");
?>
