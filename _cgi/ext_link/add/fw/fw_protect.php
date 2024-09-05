<?
/*
 ---------------------- MAIN:[FirewallProtect*] -------------------------------
*/
?>
<?
include_once(dirname(__FILE__)."/".'lib/fw_setup.php');
include_once(dirname(__FILE__)."/".'lib/fw_log.php');
include_once(dirname(__FILE__)."/".'lib/fw_debug.php');
include_once(dirname(__FILE__)."/".'lib/fw_utils.php');

// --- "Protect" Functions - Protect internal server context from net ---------

function FirewallProtectSelfGlobalsReAssign($DieOnError = FirewallDieAtDataErrorReport)
 {
  // *** Protect reassign of GLOBALS itself via GET or POST variables

  if      (!isset($GLOBALS))
   {
    FirewallDieWithMessage("GLOBALS not found"." -- cannot recover");
   }
  else if (!isset($GLOBALS[FirewallSelfGLOBALSName]))
   {
    /* // Keep silent here 
       // (prevent useless warn message, cause in some version of PHP 
       //  we trap here virtualy allways)
    FirewallErrorLogWrite
     ("GLOBALS not defined in itself"
     ." -- auto recovered");
    */

    // restore "lost" globals :-)
    // in some versions of PHP here we cannot find GLOBALS self reference
    // in some (undefined) circumates. Resore it there forcely

    $GLOBALS[FirewallSelfGLOBALSName] = $GLOBALS;
   }
  else if (!is_array($GLOBALS))
   {
    FirewallDieWithMessage
     ("GLOBALS seems to be not an array itself"
     ." -- cannot recover");
   }
  else if (!is_array($GLOBALS[FirewallSelfGLOBALSName]))
   {
    FirewallReportError("GLOBALS['GLOBALS'] self reference seems to be not an array",$DieOnError);
   }
  else if (count($GLOBALS) != count($GLOBALS[FirewallSelfGLOBALSName]))
   {
    FirewallReportError
     ("GLOBALS vs GLOBALS['GLOBALS'] Data size missmach"
     ." here:".count($GLOBALS)
     ." there:".count($GLOBALS[FirewallSelfGLOBALSName])
     ,$DieOnError);
   }

  $GLOBALS[FirewallSelfGLOBALSName] = $GLOBALS; // restore globals
 }


function FirewallProtectHTTPGlobalsReAssign($DieOnError = FirewallDieAtDataErrorReport)
 {
  // *** This function Protect $HTTP_{VAR}_VARS to be reassigned
  // Global vars allowed only be assign at server side
  // They MUST not be a part of any request directly
  // (for example, if register_globals is on, you can Reassign
  // $HTTP_{VAR}_VARS[{VAR_INDEX}] by adding 
  // construct {URL}?HTTP_{VAR}_VARS[{VAR_INDEX}]={VALUE} on your script
  // That can simulate $HTTP_{VAR}_VARS (4 ex.HTTP_SESSION_VARS)
  // in some cases (if they are not assigned, for example 
  // or if EGPCS order settings are wrong)

  // In this vars names (each var is array)
  $RealGlobalsNamesArray      = FirewallHTTPGlobalsNamesArray();

  // that values will be scaned
  $RealGlobalsVarsBlockNames = $RealGlobalsNamesArray;

  // Note: Adding "GLOBALS" inside request also permitted
  $RealGlobalsVarsBlockNames[count($RealGlobalsVarsBlockNames)] = FirewallSelfGLOBALSName;

  if ((!isset($GLOBALS)) || (!is_array($GLOBALS)))
   {
    // Cannot recover on this, sorry
    FirewallDieWithMessage
     (""
     ."Invalid GLOBALS context"
     ." -- cannot recover"
     );
   }

  foreach($RealGlobalsNamesArray as $RealGlobalName)
   {
    foreach($RealGlobalsVarsBlockNames as $CheckUpLockName)
     {
      if      (!isset($GLOBALS[$RealGlobalName]))
       {
       }
      else if (!is_array($GLOBALS[$RealGlobalName]))
       {
       }
      else
       {
        foreach($GLOBALS[$RealGlobalName] as $Key => $Value)
         {
          if ($Key == $CheckUpLockName)
           {
            // Entry in "$RealGlobalName"[$Key] is reassigned "$Key" data with $Value
            unset($GLOBALS[$RealGlobalName][$Key]);

            // Log print
            FirewallReportError
             (""
             ."Var "."[".$RealGlobalName."]"
             ." ReAssign's var "."[".$Key."]"
             ." with value "."[".$Value."]"
             ,$DieOnError);

            if (!is_array($GLOBALS[$Key]))
             {
              // Cannot recover on this, sorry
              FirewallDieWithMessage
               (""
               ."Var "."[".$RealGlobalName."]"
               ." ReAssign's var "."[".$Key."]"
               ." after all it was reset to scalar"
               ." -- cannot recover"
               );
             }

            if (!is_array($Value))
             {
              // Cannot recover on this, sorry
              if (is_array($GLOBALS[$Key]))
               {
                FirewallErrorLogWrite
                 (""
                 ."Var "."[".$RealGlobalName."]"
                 ." ReAssign's var "."[".$Key."]"
                 ." with single value "."[".$Value."]"
                 ." (ignored by server)"
                 );
               }
              else
               {
                FirewallDieWithMessage
                 (""
                 ."Var "."[".$RealGlobalName."]"
                 ." ReAssign's var "."[".$Key."]"
                 ." with single value "."[".$Value."]"
                 ." -- cannot recover"
                 );
               }
             }
            else // (is_array($Value))
             {
              // try to fix - remove from $Key spoofed values
              foreach($Value as $SubKey => $SubValue)
               {
                if (isset($GLOBALS[$Key][$SubKey]))
                 {
                  unset($GLOBALS[$Key][$SubKey]);
                  FirewallErrorLogWrite
                   (""
                   ."Var "."[".$RealGlobalName."]"
                   ." ReAssign's var "."[".$Key."]"
                   ." False added entry "."[".$SubKey."]"
                   ." => "."[".$SubValue."]"
                   ." removed");
                 }
                else
                 {
                  FirewallErrorLogWrite
                   (""
                   ."Var "."[".$RealGlobalName."]"
                   ." ReAssign's var "."[".$Key."]"
                   ." False added entry "."[".$SubKey."]"
                   ." => "."[".$SubValue."]"
                   ." (ignored by server)");
                 }
               }
             }
           }
         }
       }
     }
   }
 }


function FirewallCheckSinglePOSTFile
          ($FileVarName,
           $FileVarDataTmpName,
           $FileVarDataName,
           $FileVarDataType,
           $FileVarDataSize,
           $DieOnError = FirewallDieAtDataErrorReport)
 {
  if ($FileVarDataName == "")
   {
    // File realy NOT uploaded
    if      ($FileVarDataTmpName != 'none')
     {
      FirewallReportError
       (""
       ."HTTP_POST_FILES"
       ."[".$FileVarName."]['tmp_name']*"
       ." = '".$FileVarDataTmpName."'"
       ." is not set to 'none', with name='' (when no uploaded file)"
       ,$DieOnError);

      unset($GLOBALS['HTTP_POST_FILES'][$FileVarName]);
     }
    else if ($FileVarDataSize != 0)
     {
      FirewallReportError
       (""
       ."HTTP_POST_FILES"
       ."[".$FileVarName."]['size']*"
       ." = ".$FileVarDataSize.""
       ." is not set to 0, with name='' (when no uploaded file)"
       ,$DieOnError);

      unset($GLOBALS['HTTP_POST_FILES'][$FileVarName]);
     }
   }
  else
   {
    if      (!is_uploaded_file($FileVarDataTmpName))
     {
      FirewallReportError
       (""
       ."HTTP_POST_FILES"
       ."[".$FileVarName."]['tmp_name']*"
       ." = '".$FileVarDataTmpName."'"
       ." not recognized as uploaded file "
       ,$DieOnError);

      unset($GLOBALS['HTTP_POST_FILES'][$FileVarName]);
     }
    else if (FirewallValidPOSTTmpFileName($FileVarDataTmpName))
     {
      FirewallReportError
       (""
       ."HTTP_POST_FILES"
       ."[".$FileVarName."]['tmp_name']*"
       ." = '".$FileVarDataTmpName."'"
       ." invalid uploaded file name (as checked by firewall)"
       ,$DieOnError);

      unset($GLOBALS['HTTP_POST_FILES'][$FileVarName]);
     }
    else if (FirewallValidPOSTRemoteFileName($FileVarDataName))
     {
      FirewallReportError
       (""
       ."HTTP_POST_FILES"
       ."[".$FileVarName."]['name']*"
       ." = '".$FileVarDataName."'"
       ." dangerous remote file name (as checked by firewall) - do not accepted"
       ,$DieOnError);

      unset($GLOBALS['HTTP_POST_FILES'][$FileVarName]);
     }
   }
 }


function FirewallProtectPOSTFiles($DieOnError = FirewallDieAtDataErrorReport)
 {
  if (isset($GLOBALS['HTTP_POST_FILES']))
   {
    if (!is_array($GLOBALS['HTTP_POST_FILES']))
     {
      FirewallReportError
       (""
       ."HTTP_POST_FILES POST files seems to be not an array"
       ,$DieOnError);

      $GLOBALS['HTTP_POST_FILES'] = array();
     }
    else
     {
      foreach($GLOBALS['HTTP_POST_FILES'] as $FileVarName => $FileVarData)
       {
        if (!FirewallValidHTTPID($FileVarName))
         {
          FirewallReportError
           (""
           ."HTTP_POST_FILES"
           ."['".$FileVarName."']"
           ." not a valid HTTP control name "
           ,$DieOnError);

          unset($GLOBALS['HTTP_POST_FILES'][$FileVarName]);
         }
        else
         {
          if (!is_array($FileVarData))
           {
            FirewallReportError
             (""
             ."HTTP_POST_FILES"
             ."['".$FileVarName."']"
             ." is not an array"
             ,$DieOnError);

            unset($GLOBALS['HTTP_POST_FILES'][$FileVarName]);
           }
          else
           {
            $MinAttrs = array('name','type','size','tmp_name');

            $FilesCount = -1;

            // Note1: there may be number of files with same control name. 
            // Therefore $FileVarData['attr'] may be an array
            // Note2:$FileVarData = $GLOBALS['HTTP_POST_FILES'][$FileVarName]

            foreach($MinAttrs as $NeedAttr)
             {
              if (!isset($FileVarData[$NeedAttr]))
               {
                FirewallReportError
                 (""
                 ."HTTP_POST_FILES"
                 ."['".$FileVarName."']"."['".$NeedAttr."']"
                 ." atrribute is missing"
                 ,$DieOnError);

                unset($GLOBALS['HTTP_POST_FILES'][$FileVarName]);
                break;
               }
              else
               {
                $FoundFilesCount = count($FileVarData[$NeedAttr]);

                if ($FilesCount < 0)
                 {
                  $FilesCount = $FoundFilesCount;
                 }
                else
                 {
                  if ($FilesCount != $FoundFilesCount)
                   {
                    FirewallReportError
                     (""
                     ."HTTP_POST_FILES"
                     ."['".$FileVarName."']"."['".$NeedAttr."']"
                     ." has count(*) = (".$FoundFilesCount.")"
                     ." that not found at match prev attribute (".$FilesCount.")"
                     ,$DieOnError);

                    unset($GLOBALS['HTTP_POST_FILES'][$FileVarName]);
                    break;
                   }
                 }
               }
             }

            if (isset($GLOBALS['HTTP_POST_FILES'][$FileVarName]))
             {
              // item still set - all above passed ok
              if ($FilesCount <= 0)
               {
                FirewallReportError
                 (""
                 ."HTTP_POST_FILES"
                 ."['".$FileVarName."']"
                 ." file count is 0"
                 ." - do not expect this entry to be set at all"
                 ,$DieOnError);

                unset($GLOBALS['HTTP_POST_FILES'][$FileVarName]);
               }
              else
               {
                $MainFileAttr = "tmp_name";

                if (is_array($FileVarData[$MainFileAttr]))
                 {
                  for($FileIndex = 0; $FileIndex < count($FileVarData[$MainFileAttr]);$FileIndex++)
                   {
                    // Secondary check - check index sync for all Attrs
                    foreach($MinAttrs as $NeedAttr)
                     {
                      if (!isset($FileVarData[$NeedAttr][$FileIndex]))
                       {
                        // That $FileIndex MUST be exists for all Attrs
                        FirewallReportError
                         (""
                         ."HTTP_POST_FILES"
                         ."['".$FileVarName."']"."['".$NeedAttr."']"."[".$FileIndex."]"
                         ." index not found, but expected:"
                         ." Total files is (".$FoundFilesCount.")"
                         ,$DieOnError);

                        unset($GLOBALS['HTTP_POST_FILES'][$FileVarName]);
                        break;
                       }
                     }

                    if (!isset($GLOBALS['HTTP_POST_FILES'][$FileVarName]))
                     {
                      break; // this var marked invalid allready
                     }
                    else
                     {
                      FirewallCheckSinglePOSTFile
                       ($FileVarName,
                        $FileVarData['tmp_name'][$FileIndex],
                        $FileVarData['name'    ][$FileIndex],
                        $FileVarData['type'    ][$FileIndex],
                        $FileVarData['size'    ][$FileIndex],
                        $DieOnError);
                     }
                   }
                 }
                else
                 {
                  FirewallCheckSinglePOSTFile
                   ($FileVarName,
                    $FileVarData['tmp_name'],
                    $FileVarData['name'],
                    $FileVarData['type'],
                    $FileVarData['size'],
                    $DieOnError);
                 }
               }
             }
           }
         }
       }
     }
   }
 }

function FirewallProtectHTTPInvalidVarNames($DieOnError = FirewallDieAtDataErrorReport)
 {
  $NamesArray = FirewallHTTPGlobalsNamesArray();

  foreach($NamesArray as $ArrayName)
   {
    if (isset($GLOBALS[$ArrayName]))
     {
      if (is_array($GLOBALS[$ArrayName]))
       {
        foreach($GLOBALS[$ArrayName] as $VarName => $VarData)
         {
          if      (is_numeric($VarName))
           {
            // Silently ignore (for now)
           }
          else if (!FirewallValidPHPID($VarName))
           {
            FirewallReportError
             (""
             ."Invalid item: ".$ArrayName."[".$VarName."]"
             ,$DieOnError);

            unset($GLOBALS[$ArrayName][$VarName]);
           }
         }
       }
     }
   }
 }

// +++ "Protect" Functions - Protect internal server context from net +++++++++
?>
