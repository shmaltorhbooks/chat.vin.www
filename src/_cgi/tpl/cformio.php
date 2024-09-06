<?
// Interface module to front-end FORMS
// Note: All string value already JavaScript slashed

include_once(dirname(__FILE__)."/"."../inc/funcstr.inc.php");

function FORM_IOPutVar($VarName,$Value)
 {
  global $FORM_IO;

  $FORM_IO[$VarName] = $Value;
 }

function FORM_IOStrVar($VarName,$DefValue = "")
 {
  global $FORM_IO;

  if (isset($FORM_IO))
   {
    if (isset($FORM_IO[$VarName]))
     {
      return(JSStr($FORM_IO[$VarName]));
     }
   }

  return($DefValue);
 }

function FORM_IOStrVarFld($VarName,$DefValue = "")
 {
  global $FORM_IO;

  if (isset($FORM_IO))
   {
    if (isset($FORM_IO[$VarName]))
     {
      return(JSFldStr($FORM_IO[$VarName]));
     }
   }

  return(JSFldStr($DefValue));
 }

function FORM_IONumVar($VarName,$DefValue = 0)
 {
  global $FORM_IO;

  if (isset($FORM_IO))
   {
    if (isset($FORM_IO[$VarName]))
     {
      if (is_integer($FORM_IO[$VarName]))
       {
        return(JSInt($FORM_IO[$VarName]));
       }
      else
       {
        $Result = JSInt($FORM_IO[$VarName],$DefValue);

        if (!is_null($Result))
         {
          return($Result);
         }
        else
         {
          return($DefValue);
         }
       }
     }
   }

  return($DefValue);
 }
?>