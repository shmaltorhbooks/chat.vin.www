<?

// --- Firewaill Debug print functions ----------------------------------------

function FirewallDebugPrintArray($Array,$Name = "")
 {
  echo "<table width='100%' border='1'>";
  echo "\n";

  if (!empty($Name))
   {
    echo "<tr>";

    echo "<td colspan='3'>";
    echo "<b>".htmlspecialchars($Name).":"."</b>\n";
    echo "</td>";

    echo "</tr>";
    echo "\n";
   }

  $ItemsCount = 0;
  foreach($Array as $Key => $Value)
   {
    if      (!isset($Value))
     {
      $Value = "<i>undefined</i>";
     }
    else if (is_array($Value))
     {
      $Value = "Array[<i>".count($Value)." items</i>]";
     }
    else
     {
      $Value = "[".htmlspecialchars($Value)."]";
     }

    echo "<tr>";

    echo "<td>";
    echo "<u>".htmlspecialchars($Key)."</u>";
    echo "</td>";

    echo "<td>";
    echo " => ";
    echo "</td>";

    echo "<td>";
    echo $Value;
    echo "</td>";
    echo "</tr>";
    echo "\n";

    $ItemsCount++;
   }

  if ($ItemsCount > 1)
   {
    echo "<tr>";

    echo "<td colspan='3'>";
    echo "<i>Total items:".$ItemsCount."</i>"."\n";
    echo "</td>";

    echo "</tr>";
    echo "\n";
   }

  echo "</table>";
  echo "\n";

  echo "</br>";
  echo "\n";
 }


function FirewallDebugPrintScalar($Data,$Name = "")
 {
  echo "<table width='100%' border='1'>";
  echo "\n";

  if (!empty($Name))
   {
    echo "<tr>";

    echo "<td>";
    echo "<b>".htmlspecialchars($Name).":"."</b>\n";
    echo "</td>";

    echo "</tr>";
    echo "\n";
   }

  $Value = $Data;

  //Just print
   {
    if (!isset($Value))
     {
      $Value = "<i>undefined</i>";
     }
    else
     {
      $Value = "[".htmlspecialchars($Value)."]";
     }

    echo "<tr>";
    echo "<td>";
    echo $Value;
    echo "</td>";
    echo "</tr>";
    echo "\n";
   }

  echo "</table>";
  echo "\n";

  echo "</br>";
  echo "\n";
 }

function FirewallDebugPrintVar($Data,$Name = "")
 {
  if (is_array($Data))
   {
    FirewallDebugPrintArray($Data,$Name);
   }
  else
   {
    FirewallDebugPrintScalar($Data,$Name);
   }
 }

// +++ Firewaill Debug print functions ++++++++++++++++++++++++++++++++++++++++

?>
