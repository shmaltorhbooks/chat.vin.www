<?
include_once(dirname(__FILE__)."/".'fw_setup.php');
include_once(dirname(__FILE__)."/".'fw_log.php');
include_once(dirname(__FILE__)."/".'fw_debug.php');
include_once(dirname(__FILE__)."/".'fw_utils.php');

// --- Firewall var type checking functions -----------------------------------

// Default (relatively safe) translation
define("FW_VarTransCRLFToUnix"    ,0x0001); // Any CR/LF combination to \n
define("FW_VarTrans00ToSpace"     ,0x0002); // 0x00 code changed to ' '
define("FW_VarTransControlToSpace",0x0004); // Any code < 0x20 changed to ' ' (for Text '\n','\r','\t' preserved)
define("FW_VarTransTrimSpaces"    ,0x0008); // trim left & right spaces
define("FW_VarTransTruncateLength",0x0010); // If length is more that max-trim it
// Additional translation
define("FW_VarTransAddSafeAll"    ,0x0100); // convert a lot of bogus chars
define("FW_VarTransAddSafeSQL"    ,0x0200); // convert "\"->"/","\"","'"->"`" // Non-work at Interbase
define("FW_VarTransAddSafeJS"     ,0x0400); // convert "\"->"/","'","""->"`"
// Default values
define("FW_VarTransDefTextFlags"  ,0x00FF); // All Default translations
define("FW_VarTransXLTTextFlags"  ,0x00FF); // All Default translations

define("FW_VarTypeAnyData"        ,"ANY");     // regular expression can used
define("FW_VarTypeSignedInt"      ,"SINT");    // integer (decimal)
define("FW_VarTypeUnsignedInt"    ,"UINT");    // integer (decimal) unsigned (no sign allowed!)
define("FW_VarTypeFloat"          ,"FLOAT");   // floating point.
define("FW_VarTypeStr"            ,"STR");     // single string (one line!)
define("FW_VarTypeText"           ,"TEXT");    // text block (many lines)
define("FW_VarTypeFileName"       ,"FILENAME");// relative file name. no "..",";",":",">","<","?","*" no ".","\","/" at begin
define("FW_VarTypeEMail"          ,"EMAIL");   // only valid email characters
define("FW_VarTypeWebShortURL"    ,"WEBURL");  // only "http(s)://data" URL
define("FW_VarTypeWebURL"         ,"URL");     // any  "http(s)://data?text" URL
define("FW_VarTypeAnyURI"         ,"ANYURI");  // any "{protocol}://data?text" URL
define("FW_VarTypeSubmit"         ,"SUBMIT");  // if not empty, changed to DefaultValue or "GO"

// Additional string types (from less restricted to more restricted)
define("FW_VarTypeStrBase36"      ,"R36STR");  // only radix36 digits (any case)
define("FW_VarTypeStrIdName"      ,"ID_STR");  // [letter|_]{letter|_|digit} (any case)
define("FW_VarTypeStrHex"         ,"HEXSTR");  // only hex digits     (any case)
// Additional string types with translation (from less restricted to more restricted)
define("FW_VarTypeStrXLTSSql"     ,"STR.SSQL");// * single string with no SQL bogus items
define("FW_VarTypeStrXLTSafe"     ,"STR.SAFE");// * single string with no bogus items at all
define("FW_VarTypeStrXLTItem"     ,"STR.ITEM");// * single sint or radix36str

define("FW_DefMaxLength"          ,(32*1024)-1); // fine for signed short int
define("FW_DefVarTypeSubmitText"  ,"1");         // if submit filed is set and no default suppied, change to this value
define("FW_DefVarTypeStrItemText" ,"1");         // if non empty found, but not passed check and no default suppied, change to this value

define("FW_VarFromGETSign"        ,"G");
define("FW_VarFromPOSTSign"       ,"P");
define("FW_VarFromCOOKIESign"     ,"C");
define("FW_VarFromSESSIONSign"    ,"s");
define("FW_VarFromAnySign"        ,"*"); // for $VarAllowedFrom only

// === VarType RegExp constants ===

define("FW_URLRFCURLOneWordRegExp"     ,"(?:[0-9|A-Z|a-z|\\x80-\\xFF|\\_]{1,})");
define("FW_URLRFCURLWordSepRegExp"     ,"(?:[\\-]{1,}|[\\.]{1})");
define("FW_URLRFCURLDomainSuffixRegExp","(?:\\.[A-Z|a-z]{2,14})"); // 4 letters max

define("FW_URLServOneWordRegExp"      ,FW_URLRFCURLOneWordRegExp);
define("FW_URLServWordSepRegExp"      ,FW_URLRFCURLWordSepRegExp);
define("FW_URLServDomainSubNameRegExp","(?:".FW_URLServOneWordRegExp."{1}"."(?:".FW_URLServWordSepRegExp.FW_URLServOneWordRegExp."){0,}){1}");
define("FW_URLServDomainSuffixRegExp" ,FW_URLRFCURLDomainSuffixRegExp); // 4 letters max
define("FW_URLServDomainRegExp"       ,"(".FW_URLServDomainSubNameRegExp.FW_URLServDomainSuffixRegExp."){1}");
define("FW_URLPathOneWordRegExp"      ,FW_URLRFCURLOneWordRegExp);
define("FW_URLPathWordSepRegExp"      ,"(?:[\\/|\\.]{1}|[\\-]{1,})");
define("FW_URLPathSepRegExp"          ,"(?:(?:\\/|\\/\\~){1})");

define("FW_URLEMailUserOneWordRegExp" ,FW_URLRFCURLOneWordRegExp);
define("FW_URLEMailUserWordSepRegExp" ,FW_URLRFCURLWordSepRegExp);
define("FW_URLEMailUserNameRegExp"    ,"(?:".FW_URLEMailUserOneWordRegExp."{1}"."(?:".FW_URLEMailUserWordSepRegExp.FW_URLEMailUserOneWordRegExp."){0,}){1}");

define("FW_URLServRegExp"             ,FW_URLServDomainRegExp);
define("FW_URLPathRegExp"             ,"(\\/|(:?".FW_URLPathSepRegExp."{1}"."(?:".FW_URLPathOneWordRegExp."{1}"."(?:".FW_URLPathWordSepRegExp.FW_URLPathOneWordRegExp."){0,})){1}(\\/){0,1}){0,1}");
define("FW_URLQueryRegExp"            ,"(\\?[\\x20-\\xFF]+){0,1}");
define("FW_URLServPathDocRegExp"      ,FW_URLServRegExp.FW_URLPathRegExp);

define("FW_URLEMailRegExp"            ,FW_URLEMailUserNameRegExp."@".FW_URLServDomainRegExp);
define("FW_URLBlockedCharsRegExp"     ,"[\\\\|\\\"|']+");


function FirewallVarTypeCheckLowValueType // Returns TRUE if OK, FALSE if Failed
          (
           $VarName,                       // Used for error reporting here
          &$VarValueRaw,                   // RAW:Allready with strip'ed slashes
           $VarType       =FW_VarTypeText  // See "FW_VarType*"
          )
 {
  $VarErrorText = ""; // realy, not used here (do not send outside of this)

  $Valid = True;

  // Low-level check

  if ($Valid)
   {
    $Valid = False;

    switch(strtoupper($VarType))
     {
      case(FW_VarTypeAnyData):       // "ANY"     // regular expression can used
       {
        $Valid = True; // No checks here
       } break;

      case(FW_VarTypeText):          // "TEXT"    // text block (many lines)
       {
        $Valid = FirewallRegExpMatch($VarValueRaw,"/^[\\x20-\\xFF|\\n|\\r|\\t]*$/D");
       } break;

      default:
       {
        $Valid = FirewallRegExpMatch($VarValueRaw,"/^[\\x20-\\xFF]*$/D");
       }
     }
   }

  // Detailed check

  if ($Valid)
   {
    $Valid = False;

    switch(strtoupper($VarType))
     {
      case(FW_VarTypeAnyData):       // "ANY"     // regular expression can used
       {
        $Valid = True; // No checks here
       } break;

      case(FW_VarTypeSignedInt):     // "SINT"    // integer (decimal)
       {
        $Valid = FirewallRegExpMatch($VarValueRaw,"/^[+|\\-]{0,1}[0-9]{1,}$/D");
       } break;

      case(FW_VarTypeUnsignedInt):   // "UINT"    // integer (decimal) unsigned (no sign allowed!)
       {
        $Valid = FirewallRegExpMatch($VarValueRaw,"/^[0-9]*$/D");
       } break;

      case(FW_VarTypeFloat):         // "FLOAT"   // floating point.
       {
        $Valid = FirewallRegExpMatch
                  ($VarValueRaw,
                   "/^(|[+|\\-|0-9]?[0-9]*)(|\\.[0-9]+)(|(E|e)([+|\\-|0-9]?[0-9]*))$/D");
       } break;

      case(FW_VarTypeStr):           // "STR"     // single string (one line!)
       {
        $Valid = FirewallRegExpMatch($VarValueRaw,"/^[\\x20-\\xFF]*$/D");
       } break;

      case(FW_VarTypeText):          // "TEXT"    // text block (many lines)
       {
        $Valid = FirewallRegExpMatch($VarValueRaw,"/^[\\x20-\\xFF|\\n|\\r|\\t]*$/D");
       } break;

      case(FW_VarTypeFileName):      // "FILENAME // no "..",";",":",">","<","?","*" no ".","\","/" at begin
       {
        $Valid = FirewallRegExpMatch // only realtive file name allowed
                  ($VarValueRaw,
                   "/^"
                    ."("
                     ."(?:~){0,1}"
                     ."[_\\-|0-9|a-z|A-Z|\\x80-\\xFF]+"
                    ."){1,1}" // First file name item
                    ."([\\/\\\\\\.][_\\-|0-9|a-z|A-Z|\\x80-\\xFF]+)*"
                    ."$/D");
       } break;

      case(FW_VarTypeStrIdName):     // "ID_STR"  // [letter|_]{letter|_|digit}
       {
        $Valid = FirewallRegExpMatch($VarValueRaw,"/^[_|a-z|A-Z]+[_|a-z|A-Z|0-9]*$/D");
       } break;

      case(FW_VarTypeStrHex):        // "HEXSTR"  // only hex digits     (any case)
       {
        $Valid = FirewallRegExpMatch($VarValueRaw,"/^[0-9|a-f|A-F]*$/D");
       } break;

      case(FW_VarTypeStrBase36):     // "R36STR"  // only radix36 digits (any case)
       {
        $Valid = FirewallRegExpMatch($VarValueRaw,"/^[0-9|a-z|A-Z]*$/D");
       } break;

      case(FW_VarTypeEMail):         // "EMAIL"   // only valid email characters
       {
        $Valid = FirewallRegExpMatch
                  ($VarValueRaw,
                   "/^"
                    .FW_URLEMailRegExp
                    ."$/D");
       } break;

      case(FW_VarTypeWebShortURL):   // "WEBURL"  // only "http(s)://data" URL
       {
        $Valid = FirewallRegExpMatch
                  ($VarValueRaw,
                   "/^"
                    ."(http:\\/\\/|https:\\/\\/){0,1}"
                    .FW_URLServPathDocRegExp
                    ."$/D");
       } break;

      case(FW_VarTypeWebURL):        // "URL"     // any  "http(s)://data?text" URL
       {
        $Valid = FirewallRegExpMatch
                  ($VarValueRaw,
                   "/^"
                    ."(?i:http:\\/\\/|https:\\/\\/){0,1}" // case-insensitive
                    .FW_URLServPathDocRegExp
                    .FW_URLQueryRegExp
                    ."$/D");
       } break;

      case(FW_VarTypeAnyURI):        // "ANYURI"  // any "{protocol}://data?text" URL
       {
        $Valid = False;

        if (FirewallRegExpMatch($VarValueRaw,
            "/^"
             ."([a-z|A-Z]{1,8}:\\/\\/){0,1}"
             .FW_URLServPathDocRegExp
             .FW_URLQueryRegExp
             ."$/D"
           ))
         {
          if (FirewallRegExpMatch($VarValueRaw,
              "/"
               .FW_URLBlockedCharsRegExp // post protection
               ."/"
             ))
           {
            // FOUND BLOCKED CHARS
           }
          else
           {
            $Valid = True;
           }
         }
       } break;

      case(FW_VarTypeSubmit):
       {
        $Valid = True; // Do not use additional checks
       } break;

      default:
       {
        $VarErrorText = "Value type not recognised:[".$VarType."]";
        $Valid = False;
       }
     }
   }

  return($Valid);
 }


function FirewallVarTypeCheck // Returns new Var value, null on error
          (
           $VarName,            // Used for error reporting here
           $VarValue,
          &$VarErrorText,       // Where error messages will be stored
           // CheckUp's
           $VarType       =FW_VarTypeText,          // See "FW_VarType*"
           $VarMaxLength  =FW_DefMaxLength,         // Maximum raw length,0-None
           $VarTransFlags =FW_VarTransDefTextFlags, // How value can be changed
           $VarFrom       =NULL,                    // [GPCs] (GET,POST,COOKIE,sESSION) Note:'s' meeans session)
           $VarTypePattern=NULL,                    // RegExp for PREG
           $VarAllowedFrom=FW_VarFromAnySign,       // [GPCs|*]
           $VarAllowedVals=NULL,                    // Allowed values (Can be an array)
           $VarAllowEmpty =TRUE                     // Allowed value to be ""
          )
 {
  $VarErrorText = "";

  // --- PrePreChecks ---

  if (!isset($VarValue))
   {
    // Called for unset value - do not support this protocol!

    $VarErrorText = "Invalid var value context (call for unset var value)";
    return(null);
   }

  if      (!is_scalar($VarValue))
   {
    $VarErrorText = "Invalid var type context (non scalar type)";
    return(null);
   }
  else if (!is_string($VarValue))
   {
    $VarValue = strval($VarValue);
   }

  if ($VarValue == "")
   {
    if ((isset($VarAllowEmpty)) && (!$VarAllowEmpty))
     {
      $VarErrorText = "Empty not allowed";
      return(null);
     }
    else
     {
      // Fast exit:
      // if empty allowed, and empty found - leave it here as-is
      return("");
     }
   }

  // --- Defaults ---

  if (!isset($VarTransFlags))
   {
    $VarTransFlags = 0;
   }

  if (!isset($VarType))
   {
    $VarType = FW_VarTypeStr;
   }

  if (!isset($VarAllowedFrom))
   {
    $VarAllowedFrom = FW_VarFromAnySign;
   }

  if (!isset($VarFrom) || (trim($VarFrom) == ""))
   {
    $VarFrom = FW_VarFromAnySign;
   }

  $VarTypeAux = $VarType;

  if (($VarType == FW_VarTypeStrXLTSSql) ||
      ($VarType == FW_VarTypeStrXLTSafe) ||
      ($VarType == FW_VarTypeStrXLTItem))
   {
    $VarTransFlags = $VarTransFlags | FW_VarTransXLTTextFlags;

    if      ($VarType == FW_VarTypeStrXLTSSql)
     {
      $VarTransFlags = $VarTransFlags | FW_VarTransAddSafeSQL;
     }
    else if ($VarType == FW_VarTypeStrXLTSafe)
     {
      $VarTransFlags = $VarTransFlags | FW_VarTransAddSafeAll;
     }

    $VarType = FW_VarTypeStr; // Localy change VarType to be more general
   }

  // Process "Magic Quotes" READ

  if (get_magic_quotes_gpc())
   {
    $VarValue = str_replace("''","\\'",$VarValue); // 4 sybase quotes (anyway)
    $VarValue = stripslashes($VarValue);
   }

  // --- Translate var ---

  if ($VarTransFlags & FW_VarTransCRLFToUnix)
   {
    $VarValue = str_replace("\r\n","\n",$VarValue);
    $VarValue = str_replace("\n\r","\n",$VarValue);
    $VarValue = str_replace("\r"  ,"\n",$VarValue);
   }

  if ($VarTransFlags & FW_VarTrans00ToSpace)
   {
    $VarValue = str_replace("\x00"," ",$VarValue);
   }

  if ($VarTransFlags & FW_VarTransControlToSpace)
   {
    if (strcasecmp($VarType,FW_VarTypeText) == 0)
     {
      $VarValue = preg_replace("/[\\x00-\\x08]/"," ",$VarValue);
      $VarValue = preg_replace("/[\\x0b-\\x0c]/"," ",$VarValue);
      $VarValue = preg_replace("/[\\x0e-\\x1F]/"," ",$VarValue);
     }
    else
     {
      $VarValue = preg_replace("/[\\x00-\\x1F]/"," ",$VarValue);
     }
   }

  if ($VarTransFlags & FW_VarTransTrimSpaces)
   {
    $VarValue = trim($VarValue);
   }

  if ($VarTransFlags & FW_VarTransTruncateLength)
   {
    if (isset($VarMaxLength) && ($VarMaxLength > 0))
     {
      if (strlen($VarValue) > $VarMaxLength)
       {
        $VarValue = substr($VarValue,0,$VarMaxLength);
       }
     }
   }

  // PreChecks

  if ($VarValue == "")
   {
    if ((isset($VarAllowEmpty)) && (!$VarAllowEmpty))
     {
      $VarErrorText = "Empty not allowed";
      return(null);
     }
    else
     {
      // Fast exit:
      // if empty allowed, and empty found - leave it here as-is
      return("");
     }
   }

  if (isset($VarAllowedVals))
   {
    if (!is_array($VarAllowedVals))
     {
      if ($VarValue != $VarAllowedVals)
       {
        $VarErrorText = "Value not allowed";
        return(null);
       }
     }
    else if (!in_array($VarValue,$VarAllowedVals,FALSE))
     {
      $VarErrorText = "Value not in allowed list";
      return(null);
     }
   }

  if (isset($VarMaxLength) && ($VarMaxLength > 0))
   {
    if (strlen($VarValue) > $VarMaxLength)
     {
      $VarErrorText = "Value too long (strlen > ".$VarMaxLength.")";
      return(null);
     }
   }

  if (isset($VarAllowedFrom) && ($VarAllowedFrom != FW_VarFromAnySign))
   {
    if (stristr($VarAllowedFrom,$VarFrom) == false)
     {
      $VarErrorText = "Value not allowed from this context:[".$VarFrom."]";
      return(null);
     }
   }

  // --- Typed Checks ---

  if ($VarValue != "")
   {
    if (!FirewallVarTypeCheckLowValueType($VarName,$VarValue,$VarType))
     {
      $VarErrorText = "Value is not valid [".$VarType."]";
      return(null);
     }
   }

  // --- Add-On typed checks for simple item types ---

  switch(strtoupper($VarType))
   {
    case(FW_VarTypeSubmit):
     {
      if (FirewallRegExpMatch($VarValue,"/^[_|0-9|a-z|A-Z|\\x80-\\xFF]+$/D"))
       {
        // Keep it
       }
      else
       {
        // if we are in this function - that Submit value is set here;
        // So, return FW_DefVarTypeSubmitText anyway
        $VarValue = FW_DefVarTypeSubmitText;
       }
     } break;

    default:
     {
     }
   }

  // --- Add-On typed checks for full item types ---

  switch(strtoupper($VarTypeAux))
   {
    case(FW_VarTypeStrXLTItem):
     {
      if      (FirewallRegExpMatch($VarValue,"/^[+|\\-]{0,1}[0-9]{1,}$/D"))
       {
        // Keep it
       }
      else if (FirewallRegExpMatch($VarValue,"/^[_|0-9|a-z|A-Z|\\x80-\\xFF]*$/D"))
       {
        // Keep it
       }
      else
       {
        if ($VarValue != "")
         {
          $VarValue = FW_DefVarTypeStrItemText;
         }
       }
     } break;

    default:
     {
     }
   }

  // --- RegExp last Checks ---

  if (isset($VarTypePattern) && ($VarTypePattern > 0))
   {
    if (!FirewallRegExpMatch($VarValue,$VarTypePattern))
     {
      $VarErrorText = "Value not passed aux pattern";
      return(null);
     }
   }

  // --- Post process ---

  if ($VarTransFlags & FW_VarTransAddSafeAll)
   {
    $VarValue = str_replace("\\x00"," ",$VarValue); // str terminator (catch-up)

    $VarValue = str_replace("\\","/"  ,$VarValue); // SQL special escape
    $VarValue = str_replace("'" ,"`"  ,$VarValue); // SQL alpha boundary
    $VarValue = str_replace("\"","`"  ,$VarValue); // JS  alpha boundary
    $VarValue = str_replace("--","=-=",$VarValue); // sql comment
    $VarValue = str_replace("/*","[*" ,$VarValue); // sql comment begin
    $VarValue = str_replace("*/","*]" ,$VarValue); // sql comment end
    $VarValue = str_replace("//","::" ,$VarValue); // sql comment
    $VarValue = str_replace("<" ,"{"  ,$VarValue); // HTML tags begin
    $VarValue = str_replace(">" ,"}"  ,$VarValue); // HTML tags eng
    $VarValue = str_replace("|" ,":"  ,$VarValue); // Command Pipe
//  $VarValue = str_replace("..","=.=",$VarValue); // up directory
    $VarValue = preg_replace("/&([a-z]{1,10};)/i" ,"& \\1",$VarValue); // HTML spec chars
    $VarValue = preg_replace("/&(\\#[0-9|a-f]{1,10};)/i" ,"& \\1",$VarValue); // HTML spec chars
    $VarValue = preg_replace("/%([0-9|a-f]{1})/i" ,"% \\1",$VarValue); // URL suffer
    $VarValue = preg_replace("/%(u[0-9|a-f]{1})/i" ,"% u\\1",$VarValue); // URL suffer (unicode)
    $VarValue = str_replace(";" ,","  ,$VarValue); // command separator  
   }

  if ($VarTransFlags & FW_VarTransAddSafeSQL)
   {
    // Do not change length here
    $VarValue = str_replace("\\","/"  ,$VarValue);
    $VarValue = str_replace("'" ,"`"  ,$VarValue);
   }

  if ($VarTransFlags & FW_VarTransAddSafeJS)
   {
    // Do not change length here
    $VarValue = str_replace("\\","/"  ,$VarValue);
    $VarValue = str_replace("'" ,"`"  ,$VarValue);
    $VarValue = str_replace("\"","`"  ,$VarValue);
   }

  if ($VarTransFlags & FW_VarTransAddSafeAll)
   {
    $VarValue = ltrim($VarValue);

    while((strlen($VarValue) > 0) && ($VarValue[0] == '~'))
     {
      $VarValue = substr($VarValue,1);
      $VarValue = ltrim($VarValue);
     }

    while((strlen($VarValue) > 0) && ($VarValue[0] == '/'))
     {
      $VarValue = substr($VarValue,1);
      $VarValue = ltrim($VarValue);
     }

    // if value begin with Javascript (in cause anybody put it in URL)

    do
     {
      $VarValueBefore = $VarValue;
      $VarValue = preg_replace("/^\\s*javascript\\s*:/i","javascript ",$VarValue);
      $VarValue = ltrim($VarValue);
     }
    while($VarValueBefore != $VarValue);
   }

  if ($VarTransFlags & FW_VarTransTrimSpaces)
   {
    // Port-process (if FW_VarTransAdd* will change some thing to space)
    $VarValue = trim($VarValue);
   }

  if ($VarTransFlags & FW_VarTransAddSafeAll)
   {
    // Length can be changes up here to be more that source length
    if (isset($VarMaxLength) && ($VarMaxLength > 0))
     {
      if (strlen($VarValue) > $VarMaxLength)
       {
        // Note:First pass check (Uphere) will pass, so
        //      Source length of var was been valid.
        //      Just strip silently all that out of length here
        $VarValue = substr($VarValue,0,$VarMaxLength);
       }
     }
   }

  // Process "Magic Quotes" WRITE

  if (get_magic_quotes_gpc())
   {
    $VarValue = addslashes($VarValue);
    $opt = ini_get("magic_quotes_sybase");

    if (($opt) || (intval($opt) != 0))
     {
      $VarValue = str_replace("\\'","''",$VarValue); // 4 sybase quotes
     }
   }

  return($VarValue);
 }


// +++ Firewall var type checking functions +++++++++++++++++++++++++++++++++++
?>
