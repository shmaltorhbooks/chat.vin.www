<?
// --- Firewall Setup [user - written rules] ----------------------------------

define ("FirewallDieErrorShowToUser"  ,FALSE); // Show error to User

define ("FirewallDieAtDataErrorReport",FALSE); // TRUE strictly recomented
define ("FirewallDieAtVarErrorReport" ,FALSE);

define ("FirewallReportVarNotes",FALSE); // TRUE-any var name setup defaults will be shown

       // --- Var Change reporting ---
define ("FirewallReportVarChange",TRUE);                   // TRUE-any var change will be shown
define ("FirewallReportVarChangeSkipSubmit",TRUE);         // TRUE-Skip submit var when print var change
define ("FirewallReportVarChangeSkipTextCRLFChange",TRUE); // TRUE-Skip text vars CL+LF transaltion to single LF show as change

// +++ Firewall Setup [user - written rules] ++++++++++++++++++++++++++++++++++
?>
