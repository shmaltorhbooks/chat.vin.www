<?

// Note: Uses Firewall functions to check

function ChatCheckStrValidEMail($InStr)
 {
  return(FirewallVarTypeCheckLowValueType('EMailCheck',$InStr,FW_VarTypeEMail));
 }

function ChatCheckStrValidWebURL($InStr)
 {
  return(FirewallVarTypeCheckLowValueType('WebURLCheck',$InStr,FW_VarTypeWebURL));
 }

function ChatCheckStrValidGender($InStr)
 {
  switch($InStr)
   {
    case (ChatConstUserGenderMale):
    case (ChatConstUserGenderFemale):
    case (ChatConstUserGenderUndefined):
     {
      return(true);
     } break;

    default:
     {
      return(false);
     }
   }
 }

function ChatCheckStrValidHTMLColor($InStr)
 {
  $Valid = preg_match
            ( "/^"
              ."(?:(?:#[0-9|A-Z]{3,6})|(?:[a-z]{1,30}))"
              ."$/iD",
             $InStr);

  return($Valid);
 }

?>
