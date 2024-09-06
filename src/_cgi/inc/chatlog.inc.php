<?
 // Log functions

 include (dirname(__FILE__)."/"."setup.inc.php");

 // Note: if ChatSQLDieDebugTrace defined in "inc/setup.inc.php"
 // Errors will be fully displayed to clien-side
 // Including (if possible) popup alert(...)

 function ChatErrorLog($ErrorStr)
  {
   global $HTTP_SERVER_VARS;

   error_log("Chat:".$ErrorStr." IP:".$HTTP_SERVER_VARS["REMOTE_ADDR"],0);
  }

 // Mode of output stream on client-side
 $ChatClientMessStreamJavaScriptSetMode = false; // Output allready in JavaScript

 function ChatClientMessStreamJavaScriptSetMode($InJavaScriptFlag)
  {
   global $ChatClientMessStreamJavaScriptSetMode;

   $ChatClientMessStreamJavaScriptSetMode = $InJavaScriptFlag;
  }

 function ChatClientRunJavaScript($JavaScriptExpression)
  {
   global $ChatClientMessStreamJavaScriptSetMode;

   if ($ChatClientMessStreamJavaScriptSetMode)
    {
    }
   else
    {
     echo "<SCRIPT  language='JavaScript'>"."\n";
    }

   echo $JavaScriptExpression;

   if ($ChatClientMessStreamJavaScriptSetMode)
    {
    }
   else
    {
     echo "</SCRIPT>"."\n";
    }
  }

 function ChatClientPrintPrivat_Text($Text)
  {
   $Text = str_replace("\\","\\\\",$Text);
   $Text = str_replace("\"","\\\"",$Text);
   $Text = str_replace("\n","\\n",$Text);

   return("top.MPText(\"".$Text."\");"."\n");
  }

 function ChatClientPrintPrivat($Text)
  {
   ChatClientRunJavaScript(ChatClientPrintPrivat_Text($Text));
  }

 function ChatClientPrintChat_Text($Text)
  {
   $Text = str_replace("\\","\\\\",$Text);
   $Text = str_replace("\"","\\\"",$Text);
   $Text = str_replace("\n","\\n",$Text);

   return("top.MCText(\"".$Text."\");"."\n");
  }

 function ChatClientPrintChat($Text)
  {
   ChatClientRunJavaScript(ChatClientPrintChat_Text($Text));
  }

 function ChatClientMessShow($FullErrorMessStr)
  {
   $FullErrorMessStr = str_replace("\\","\\\\",$FullErrorMessStr);
   $FullErrorMessStr = str_replace("\"","\\\"",$FullErrorMessStr);
   $FullErrorMessStr = str_replace("\n","\\n",$FullErrorMessStr);

   ChatClientRunJavaScript("alert(\"".$FullErrorMessStr."\");"."\n");
  }

 function ChatSQLDie2LogLowShowAlert($ErrorStr,$SQLErrorStr,$QueryStr)
  {
   global $HTTP_SERVER_VARS;

   if ($ErrorStr == "")
    {
     $ErrorStr = "SQL EXECUTION ERROR";
    }

   if ($SQLErrorStr == "")
    {
     $SQLErrorStr = "FAILED";
    }

   if ($QueryStr == "")
    {
     $QueryStr = "[N/A]";
    }

   $FullErrorMessStr = "Chat:".$ErrorStr."\nSQL:{$SQLErrorStr}\nQUERY:{$QueryStr}\nIP:".$HTTP_SERVER_VARS["REMOTE_ADDR"];
   ChatClientMessShow($FullErrorMessStr);
  }

 function ChatSQLDie2LogLow($ErrorStr,$SQLErrorStr,$QueryStr)
  {
   global $HTTP_SERVER_VARS;
   global $ChatClientMessStreamJavaScriptSetMode;

   if ($ErrorStr == "")
    {
     $ErrorStr = "SQL EXECUTION ERROR";
    }

   if ($SQLErrorStr == "")
    {
     $SQLErrorStr = "FAILED";
    }

   if ($QueryStr == "")
    {
     $QueryStr = "[N/A]";
    }

   $FullErrorMessStr = $ErrorStr." SQL:{$SQLErrorStr} QUERY:{$QueryStr}";
   ChatErrorLog($FullErrorMessStr);

   if (defined("ChatSQLDieDebugTrace"))
    {
     ChatSQLDie2LogLowShowAlert($ErrorStr,$SQLErrorStr,$QueryStr);
    }

   if ($ChatClientMessStreamJavaScriptSetMode)
    {
     echo "</SCRIPT>"."\n"; // Close active Javascript block before die
     $ChatClientMessStreamJavaScriptSetMode = false;
    }

   die($ErrorStr);
  }

 function ChatSQLDie2Log($ErrorStr,$QueryStr)
  {
   ChatSQLDie2LogLow($ErrorStr,mysql_error(),$QueryStr);
  }

 function ChatServerLogWriteWarning($WarningStr)
  {
   ChatErrorLog("Warning!:".$WarningStr);
  }

 function ChatServerLogWriteLog($TextStr)
  {
   ChatErrorLog("Log:".$TextStr);
  }

 function ChatServerLogWriteReqLog($ReqNameStr)
  {
   if (!defined("ChatFullRequestTrace"))
    {
     return; // Fast exit - No action
    }

   // Request vars
   global $Room;
   global $Nick;
   global $NickSrc;
   global $Password;
   global $PasswordAdd;
   global $Color;
   global $Topic;
   global $EMail;
   global $Room;
   global $SID;
   global $MessText;
   global $MessTo;
   global $MessPvt;
   global $MID;
   global $NickNotes;
   global $SelfNotes;
   global $GZIP;

   $Log  = "";
   $Log .= $ReqNameStr;

   if (isset($Room))        { $Log .= ",Room:"       .$Room;                }

   if (isset($Nick))        { $Log .= ",Nick:"        .$Nick;                }
   if (isset($NickSrc))     { $Log .= ",NickSrc:"     .$NickSrc;             }
   if (isset($Password))    { $Log .= ",Password:"    .strlen($Password);    }
   if (isset($PasswordAdd)) { $Log .= ",PasswordAdd:" .strlen($PasswordAdd); }
   if (isset($Color))       { $Log .= ",Color:"       .$Color;               }
   if (isset($Topic))       { $Log .= ",Topic:"       .$Topic;               }
   if (isset($EMail))       { $Log .= ",EMail:"       .strlen($EMail);       }
   if (isset($Room))        { $Log .= ",Room:"        .$Room;                }
   if (isset($SID))         { $Log .= ",SID:"         .$SID;                 }
   if (isset($MessText))    { $Log .= ",MessText:"    .strlen($MessText);    }
   if (isset($MessTo))      { $Log .= ",MessTo:"      .$MessTo;              }
   if (isset($MessPvt))     { $Log .= ",MessPvt:"     .$MessPvt;             }
   if (isset($MID))         { $Log .= ",MID:"         .$MID;                 }

   if (isset($NickNotes))   { $Log .= ",NickNotes:"   .strlen($NickNotes);   }
   if (isset($SelfNotes))   { $Log .= ",SelfNotes:"   .strlen($SelfNotes);   }

   if (isset($GZIP))        { $Log .= ",GZIP:"        .$GZIP;                }

   if (isset($LogData))     { $Log .= ",LogData:"     .strlen($LogData);     }
   if (isset($LogDataAdd))  { $Log .= ",LogDataAdd:"  .strlen($LogDataAdd);  }

   ChatServerLogWriteLog($Log);
  }

?>
