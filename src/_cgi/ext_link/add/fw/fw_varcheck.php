<?
/*
 ---------------------- MAIN:[FirewallVarCheck*] ------------------------------
*/
?>
<?
include_once(dirname(__FILE__)."/".'lib/fw_setup.php');
include_once(dirname(__FILE__)."/".'lib/fw_log.php');
include_once(dirname(__FILE__)."/".'lib/fw_debug.php');
include_once(dirname(__FILE__)."/".'lib/fw_utils.php');
include_once(dirname(__FILE__)."/".'lib/fw_vartype.php');


function FirewallVarCheckLowFillSetupBySimpleSetupArray($Src)
 {
  // *** Fill SetUp Array[VarName][VariantId][TypedAttr] by simple array of
  //    Src[N][TypedAttr], where 'Name' TypedAttr exists and holds var name
  //    if multiple vars exists with same 'Name' TypedAttr exists
  //     it will be stored with multiple VariantId (0,1,..)
  //    else 
  //     it will be sored with single VariantId = 0

  $Result = array();

  foreach($Src as $SrcData)
   {
    if (isset($Result[$SrcData['Name']]))
     {
      $Result[$SrcData['Name']][ ] = $SrcData; // Append
     }
    else
     {
      $Result[$SrcData['Name']][0] = $SrcData; // First item (for mutable data)
     }

    if (($SrcData['Name'] != "") && ($SrcData['Name'][0] == '/'))
     {
      // Name stored as Regular expression
      // Add-on (cashe) item store

      if (isset($Result['*'][$SrcData['Name']]))
       {
        $Result['*'][$SrcData['Name']][ ] = $SrcData; // Append
       }
      else
       {
        $Result['*'][$SrcData['Name']][0] = $SrcData; // First item (for mutable data)
       }
     }
   }

  return($Result);
 }


function FirewallVarCheckLowValueByTypeAttr($VarName,$VarValue,&$VarErrorText,$VarFrom,$VarTypeAttrItem)
 {

  // *** Checks var value by single TypeAttr[TypedItem] item
  //     Return new var value of null on error. 
  //     Note: 'Name' TypedAttr ignored

  if ($VarTypeAttrItem['IsArray'])
   {
    if (!is_array($VarValue))
     {
      $VarErrorText = "Value expected to be an array";
      return(null);
     }
    else
     {
      $VarValueResult = array();

      foreach($VarValue as $VarValueIndex => $VarValueData)
       {
        if (($VarTypeAttrItem['ArrayIndex'] == '') || 
            (strtoupper($VarTypeAttrItem['ArrayIndex']) == 'SINT') ||
            (strtoupper($VarTypeAttrItem['ArrayIndex']) == 'UINT'))
         {
          if (!is_numeric($VarValueIndex))
           {
            $VarErrorText = "invalid (non numeric) array index [".$VarValueIndex."]";
            return(null);
           }
         }
        else
         {
          if (FirewallVarTypeCheck
               ($VarName,
                $VarValueIndex,
                $VarErrorText,
                $VarTypeAttrItem['ArrayIndex']))
           {
            $VarErrorText = "invalid (non [".$VarTypeAttrItem['ArrayIndex']."]) array index [".$VarValueIndex."]";
            return(null);
           }
         }

        $VarValueData =
          FirewallVarTypeCheck
           ($VarName,
            $VarValueData,
            $VarErrorText,
            $VarTypeAttrItem['Type'],
            $VarTypeAttrItem['MaxLength']);

        if (is_null($VarValueData))
         {
          $VarErrorText .= "{array index ".$VarValueIndex."}";
          return(null);
         }
        else
         {
          $VarValueResult[$VarValueIndex] = $VarValueData;
         }
       }

      return($VarValueResult);
     }
   }
  else if ($VarTypeAttrItem['IsList'])
   {
    if      (!is_scalar($VarValue))
     {
      $VarErrorText = "Value expected to be a list";
      return(null);
     }
    else if ($VarTypeAttrItem['ListSep'] == "")
     {
      $VarErrorText = "List separator not defined";
      return(null);
     }
    else
     {
      $VarValueResult = array();
      $VarValueSource = explode($VarTypeAttrItem['ListSep'],trim($VarValue));

      foreach($VarValueSource as $VarValueIndex => $VarValueData)
       {
        $VarValueData = trim($VarValueData);

        if ($VarValueData != "")
         {
          $VarValueData =
            FirewallVarTypeCheck
             ($VarName,
              $VarValueData,
              $VarErrorText,
              $VarTypeAttrItem['Type'],
              $VarTypeAttrItem['MaxLength']);
         }
        else
         {
          // empty ok (will be dropped later)
         }

        if      (is_null($VarValueData))
         {
          $VarErrorText .= "{list item N ".($VarValueIndex+1)."}";
          return(null);
         }
        else if (trim($VarValueData) != "")
         {
          $VarValueResult[$VarValueIndex] = trim($VarValueData);
         }
        else
         {
          // simple dropped
         }
       }

      $VarValue = trim(implode($VarTypeAttrItem['ListSep'],$VarValueResult));

      if ($VarTypeAttrItem['MaxLength'] > 0)
       {
        if (strlen($VarValue) > $VarTypeAttrItem['MaxLength'])
         {
          $VarValue = substr($VarValue,0,$VarTypeAttrItem['MaxLength']);
          $VarValue = trim($VarValue);

          // if lset item after trimming is list separator - drop it
          if (substr($VarValue,-1) == $VarTypeAttrItem['ListSep'])
           {
            $VarValue = substr($VarValue,0,-1);
            $VarValue = trim($VarValue);
           }
         }
       }

      return($VarValue);
     }
   }
  else
   {
    if (!is_scalar($VarValue))
     {
      $VarErrorText = "Value expected to be scalar";
      return(null);
     }
    else
     {
      return
       (FirewallVarTypeCheck // Returns new Var value, null on error
         ($VarName,
          $VarValue,
          $VarErrorText,
          $VarTypeAttrItem['Type'],
          $VarTypeAttrItem['MaxLength']));
     }
   }
 }

function FirewallVarCheckLowVarBySetupArray($VarName,$VarValue,&$VarErrorText,$VarFrom,$VarSetUpArray,&$VarTypeAttrItem)
 {

  // *** Checks var value by SetUpArray (supported var with multiple types) ***
  //     Return new var value of null on error.
  //     Note:
  //     if VarSetUpArray[VarName] not found
  //      this function trys to use 'default' rule (VarName = '')
  //      if no typed item(s) found for VarName and no default rule, 
  //      error will be returned

  $VarTypeAttrItem = NULL; // Output to note caller witch type realy was been used
  $VarSetUp = NULL;

  if (is_null($VarSetUp))
   {
    if (isset($VarSetUpArray[$VarName]))
     {
      $VarSetUp = $VarSetUpArray[$VarName];
     }
   }

  if (is_null($VarSetUp))
   {
    // Exact rule not found. Try to use Regular expression var name

    if (isset($VarSetUpArray['*']) && is_array($VarSetUpArray['*']))
     {
      foreach($VarSetUpArray['*'] as $VarRegExpName => $VarRegExpSetUpValue)
       {
        if (preg_match($VarRegExpName,$VarName))
         {
          $VarSetUp = $VarRegExpSetUpValue;

          if (FirewallReportVarNotes)
           {
            $VarNoteText  = "Var [".$VarName."]";
            $VarNoteText .= " - regexp ['".$VarRegExpName."'] setup used";
            FirewallErrorLogWrite($VarNoteText);
           }

          break;
         }
       }
     }
   }

  if (is_null($VarSetUp))
   {
    // general default (empty '' var name)

    if (isset($VarSetUpArray['']))
     {
      $VarSetUp = $VarSetUpArray[''];

      if (FirewallReportVarNotes)
       {
        $VarNoteText  = "Var [".$VarName."]";
        $VarNoteText .= " - default [''] setup used";
        FirewallErrorLogWrite($VarNoteText);
       }
     }
   }

  if      (count($VarSetUp) == 1)
   {
    $VarTypeAttrItem = $VarSetUp[0];

    $VarErrorText = "";
    $VarResult    = null;
    $VarResult    = FirewallVarCheckLowValueByTypeAttr
                     ($VarName,$VarValue,$VarErrorText,$VarFrom,
                      $VarTypeAttrItem);

    if (!is_null($VarResult))
     {
      return($VarResult);
     }
   }
  else if (count($VarSetUp) > 1)
   {
    foreach($VarSetUp as $VarTypeAttrItem)
     {
      $VarErrorText = "";
      $VarResult    = null;
      $VarResult    = FirewallVarCheckLowValueByTypeAttr
                       ($VarName,$VarValue,$VarErrorText,$VarFrom,
                        $VarTypeAttrItem);

      if (!is_null($VarResult))
       {
        return($VarResult);
       }
     }
   }
  else
   {
    // No var type defined (even for default var name!)
    $VarErrorText = "Value type not recognized";
   }

  return(null);
 }


function FirewallVarCheckLowVarArrayBlock(&$VarArray,$VarArrayName,&$VarArrayErrorText,$VarFrom,$VarSetUpArray,$DieOnError=FirewallDieAtVarErrors)
 {
  // *** Checks VarArray item (where var array is one of 'HTTP_*_VARS')
  //     Return TRUE if ok, FALSE if failed.

  $Result = TRUE;
  
  $VarArrayErrorText = ""; // Not up of here, bust collected, may be used later?

  foreach($VarArray as $VarName => $VarValue)
   {
    $VarValueSrc = $VarValue;
    $VarValue    = FirewallVarCheckLowVarBySetupArray($VarName,$VarValue,$VarErrorText,$VarFrom,$VarSetUpArray,$VarTypeAttrItem);
    
    if (is_null($VarValue))
     {
      if ($VarArrayErrorText !=  "")
       {
        $VarArrayErrorText .= "\t";
       }

      $VarFullErrorText  = "Var ".$VarArrayName."[".$VarName."] Error:'".$VarErrorText."'";
      $VarArrayErrorText .= $VarFullErrorText;
      $Result = FALSE;

      FirewallReportError($VarFullErrorText,$DieOnError);

      if (!$Result)
       {
        // uncomment this line if you want to stop at first error;
        // break;
       }
     }
    else
     {
      $VarArray[$VarName] = $VarValue;

      $ReportChange = FALSE;

      if (defined("FirewallReportVarChange") && FirewallReportVarChange)
       {
        $ReportChange = TRUE;

        if ($VarTypeAttrItem['Type'] == FW_VarTypeSubmit)
         {
          if (defined("FirewallReportVarChangeSkipSubmit") && FirewallReportVarChangeSkipSubmit)
           {
            $ReportChange = FALSE;
           }
         }
       }

      if ($ReportChange)
       {
        if      (is_scalar($VarValue) && is_scalar($VarValueSrc))
         {
          $From = $VarValueSrc;
          $To   = $VarValue;

          if (trim($From) != trim($To))
           {
            $VarReportChanged = TRUE;

            if (defined("FirewallReportVarChangeSkipTextCRLFChange") && FirewallReportVarChangeSkipTextCRLFChange)
             {
              $OutTo = FirewallVarTypeCheck
                        ($VarName,
                         $From,
                         $DummyVarErrorText,
                         FW_VarTypeText,
                         0, // Maximum raw length,0-None
                         FW_VarTransCRLFToUnix | FW_VarTransTrimSpaces);

              if (trim($To) == trim($OutTo))
               {
                // $OutTo(==$To) can be obtained from $From
                // via just a simple translation
                // ignore this here (as a trivial case, just as noise)
                $VarReportChanged = FALSE;
               }
             }

            if ($VarReportChanged)
             {
              $VarChangeText  = "Var ".$VarArrayName."[".$VarName."]";
              $VarChangeText .= "=";
              $VarChangeText .= "'".addcslashes(trim($From),"\0..\37")."'";
              $VarChangeText .= "=>";
              $VarChangeText .= "'".addcslashes(trim($To),"\0..\37")."'";
              FirewallErrorLogWrite($VarChangeText);
             }
           }
         }
        else if (is_array($VarValue) && is_array($VarValueSrc))
         {
          foreach($VarValueSrc as $Index => $From)
           {
            $To = $VarValue[$Index];

            if (!isset($To))
             {
              $VarChangeText  = "Var ".$VarArrayName."[".$VarName."]"."[".$Index."]";
              $VarChangeText .= "'".addcslashes(trim($From),"\0..\37")."'";
              $VarChangeText .= " => NULL (dropped)";
              FirewallErrorLogWrite($VarChangeText);
             }
            else
             {
              if (trim($From) != trim($To))
               {
                $VarReportChanged = TRUE;

                if (defined("FirewallReportVarChangeSkipTextCRLFChange") && FirewallReportVarChangeSkipTextCRLFChange)
                 {
                  $OutTo = FirewallVarTypeCheck
                            ($VarName,
                             $From,
                             $DummyVarErrorText,
                             FW_VarTypeText,
                             0, // Maximum raw length,0-None
                             FW_VarTransCRLFToUnix | FW_VarTransTrimSpaces);

                  if (trim($To) == trim($OutTo))
                   {
                    // $OutTo(==$To) can be obtained from $From
                    // via just a simple translation
                    // ignore this here (as a trivial case, just as noise)
                    $VarReportChanged = FALSE;
                   }
                 }

                if ($VarReportChanged)
                 {
                  $VarChangeText  = "Var ".$VarArrayName."[".$VarName."]"."[".$Index."]";
                  $VarChangeText .= "'".addcslashes(trim($From),"\0..\37")."'";
                  $VarChangeText .= "=>";
                  $VarChangeText .= "'".addcslashes(trim($To),"\0..\37")."'";
                  FirewallErrorLogWrite($VarChangeText);
                 }
               }
             }
           }
         }
        else
         {
          $VarChangeText = "Var ".$VarArrayName."[".$VarName."] Changes type:Cannot log change";
          FirewallReportError($VarChangeText,$DieOnError);
         }
       }
     }
   }
 
  return($Result);
 }


function FirewallVarCheck($VarSetUpArray,$DieOnError=FirewallDieAtVarErrors)
 {
  if (isset($GLOBALS['HTTP_GET_VARS']))
   {
    if (!FirewallVarCheckLowVarArrayBlock
          ($GLOBALS['HTTP_GET_VARS'],
            'HTTP_GET_VARS',
           $VarArrayErrorText,
            FW_VarFromGETSign,
           $VarSetUpArray))
     {
      // Error found, but allready shown
     }
   }

  if (isset($GLOBALS['HTTP_POST_VARS']))
   {
    if (!FirewallVarCheckLowVarArrayBlock
          ($GLOBALS['HTTP_POST_VARS'],
            'HTTP_POST_VARS',
           $VarArrayErrorText,
            FW_VarFromPOSTSign,
            $VarSetUpArray))
     {
      // Error found, but allready shown
     }
   }

  if (isset($GLOBALS['HTTP_COOKIE_VARS']))
   {
    if (!FirewallVarCheckLowVarArrayBlock
          ($GLOBALS['HTTP_COOKIE_VARS'],
            'HTTP_COOKIE_VARS',
           $VarArrayErrorText,
            FW_VarFromCOOKIESign,
           $VarSetUpArray))
     {
      // Error found, but allready shown
     }
   }

  if (isset($GLOBALS['HTTP_SESSION_VARS']))
   {
    if (!FirewallVarCheckLowVarArrayBlock
          ($GLOBALS['HTTP_SESSION_VARS'],
            'HTTP_SESSION_VARS',
           $VarArrayErrorText,
            FW_VarFromSESSIONSign,
            $VarSetUpArray))
     {
      // Error found, but allready shown
     }
   }
 }

// Add-On utility function
// Returns new Var value, null on error
function FirewallVarCheckLowSingleValueType
          (
           $VarName,            // Used for error reporting here
           $VarValue,
           // CheckUp's
           $VarType       =FW_VarTypeText,          // See "FW_VarType*"
           $VarMaxLength  =FW_DefMaxLength,         // Maximum raw length,0-None
           $VarTransFlags =FW_VarTransDefTextFlags, // How value can be changed
           $VarFrom       =NULL,                    // [GPCs] (GET,POST,COOKIE,sESSION) Note:'s' meeans session)
           $VarTypePattern=NULL,                    // RegExp for PREG
           $VarAllowedFrom=FW_VarFromAnySign,       // [GPCs|*]
           $VarAllowedVals=NULL,                    // Allowed values (Can be an array)
           $VarAllowEmpty =TRUE,                    // Allowed value to be ""
           $DieOnError    =FirewallDieAtVarErrors
          )
 {
  $Result = FirewallVarTypeCheck // Returns new Var value, null on error
             (
              $VarName,
              $VarValue,
              $VarErrorText,
              $VarType,
              $VarMaxLength,
              $VarTransFlags,
              $VarFrom,
              $VarTypePattern,
              $VarAllowedFrom,
              $VarAllowedVals,
              $VarAllowEmpty
             );

  if (is_null($Result))
   {
    FirewallReportVarError($VarErrorText,$VarName,$DieOnError);
   }

  return($Result);
 }

?>
