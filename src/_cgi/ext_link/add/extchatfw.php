<?
if (!defined("FirewallPassed"))
 {
  include_once(dirname(__FILE__)."/".'fw/fw_main.php');
 }

function ChatFirewallFillRequestSetUpSimpleArray()
 {
  $Src = array();

  return($Src);
 }


function ChatFirewallPass()
 {
  $GlobalsVarSaveArray = array();

  if (!defined("ChatFirewallPassed"))
   {
    define("ChatFirewallPassed",1);

    global $ChatFirewallStartTime;
    $ChatFirewallStartTime = FirewallMicroTimeStamp();

    // vars to save accros calls: 

    FirewallGlobalsSaveVar($GlobalsVarSaveArray,'ChatFirewallStartTime');

    // --- Process Start ---

    // Single politics for all vars and requests

    FirewallProtectSelfGlobalsReAssign();
    FirewallProtectHTTPGlobalsReAssign();
    FirewallProtectPOSTFiles();
    FirewallProtectHTTPInvalidVarNames();
    FirewallIgnoreRegisterGlobals(); // clears all global var set for now

    $SimpleSetUp = ChatFirewallFillRequestSetUpSimpleArray();
    $SetUp       = FirewallVarCheckLowFillSetupBySimpleSetupArray($SimpleSetUp);

//  FirewallVarCheck($SetUp);

    // --- Process End ---

    FirewallGlobalsRestore($GlobalsVarSaveArray);

    global $ChatFirewallEndTime;
    $ChatFirewallEndTime = FirewallMicroTimeStamp();

    global $ChatFirewallProcessTimeInSec;
    $ChatFirewallProcessTimeInSec = $ChatFirewallEndTime-$ChatFirewallStartTime;
   }
 }
?>
<?
// Call to firewall
if (!defined("ExtFirewallPassed"))
 {
  ChatFirewallPass();
 }
define("ExtFirewallPassed",1);
?>
