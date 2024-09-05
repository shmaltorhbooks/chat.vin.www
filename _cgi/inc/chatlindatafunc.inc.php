<?
include_once(dirname(__FILE__)."/"."chatlinconst.inc.php");

// Makes Aux crypt array 
// $PrePass must be transfered from client ($PrePass 4 example may be a $Hash)
function ChatPrePassArrayMake($PrePass)
 {
  if (!defined("ChatPrePassPrefix"))
   {
    $PrePassPrefix = "****";
   }
  else
   {
    $PrePassPrefix = ChatPrePassPrefix;
   }

  $Result  = array();
  $CRCData = crc32($PrePass.$PrePassPrefix);

  $Index = 0;

  do
   {
    $Result[$Index] = ($CRCData & 0xFF);
    $CRCData = $CRCData >> 8;
    $CRCData = $CRCData & 0xFFFFFF; // clear most upper 8 bits

    $Index++;
   }
  while($CRCData != 0);

  return($Result);
 }


function ChatPrePassValueJSDraw_Text($PrePass)
 {
  echo "new Array(".join(",",ChatPrePassArrayMake($PrePass)).")";
 }
?>
