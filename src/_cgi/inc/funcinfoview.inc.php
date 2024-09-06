<?

function Str2Attr($InStr)
 {
  $Result = $InStr;
  $Result = str_replace("\"","",$Result);
  $Result = str_replace("\\","",$Result);
  $Result = str_replace("'","",$Result);
  $Result = str_replace("<","",$Result);
  $Result = str_replace(">","",$Result);
  $Result = str_replace("&","",$Result);
  $Result = str_replace("%","",$Result);
  return ($Result);
 }

function WriteInfoHead()
 {
  echo "\n";
  echo "<table width=100% border=1>";
  echo "\n";
 }

function WriteInfoStepRawData($Name,$Data)
 {
  if (is_array($Data))
   {
    if (count($Data) > 0)
     {
      $Limit = count($Data);

      for ($Index = 0;$Index < $Limit;$Index++)
       {
        echo "<tr>";

        if ($Index == 0)
         {
          echo "<td width='30%' nowrap rowspan='".$Limit."'>";
           echo "<font size='-1'>";
           echo htmlspecialchars($Name);
           echo "</font>";
          echo "</td>";
         }

         echo "<td>";
          echo "<font size='-1'>";
          echo $Data[$Index];
          echo "</font>";
         echo "</td>";
        echo "</tr>";
        echo "\n";
       }
     }
   }
  else
   {
    echo "<tr>";
     echo "<td width='30%' nowrap>";
      echo "<font size='-1'>";
      echo htmlspecialchars($Name);
      echo "</font>";
     echo "</td>";
     echo "<td>";
      echo "<font size='-1'>";
      echo $Data;
      echo "</font>";
     echo "</td>";
    echo "</tr>";
    echo "\n";
   }
 }

function WriteInfoStep($Name,$Data)
 {
  if (is_array($Data))
   {
    if (count($Data) > 0)
     {
      foreach ($Data as $IndexValue => $DataValue) 
       {
        if (trim($DataValue) == "")
         {
          $DataValue = "&nbsp;";
         }
        else
         {
          $DataValue = htmlspecialchars($DataValue);
         }

        $DataOut[$IndexValue] = $DataValue;
       }

      WriteInfoStepRawData($Name,$DataOut);
     }
   }
  else
   {
    if (trim($Data) == "")
     {
      WriteInfoStepRawData($Name,"&nbsp;");
     }
    else
     {
      WriteInfoStepRawData($Name,htmlspecialchars($Data));
     }
   }
 }

function WriteInfoStepFilled($Name,$Data)
 {
  if (is_array($Data))
   {
    if (count($Data) > 0)
     {
      foreach ($Data as $IndexValue => $DataValue) 
       {
        if      (trim($DataValue) == "")
         {
         }
        else if (trim($DataValue) == "0")
         {
         }
        else
         {
          $DataOut[$IndexValue] = $DataValue;
         }
       }

      WriteInfoStep($Name,$DataOut);
     }
   }
  else
   {
    if      (trim($Data) == "")
     {
     }
    else if (trim($Data) == "0")
     {
     }
    else
     {
      WriteInfoStep($Name,$Data);
     }
   }
 }

function WriteInfoSubHeadRawData($Data)
 {
  echo "<tr>";
   echo "<td colspan=2>";
    echo "<font size='-1'>";
    echo $Data;
    echo "</font>";
   echo "</td>";
  echo "</tr>";
  echo "\n";
 }

function WriteInfoSubHead($Data)
 {
  WriteInfoSubHeadRawData(htmlspecialchars($Data));
 }

function WriteInfoTail()
 {
  echo "</table>";
  echo "\n";
 }

?>
