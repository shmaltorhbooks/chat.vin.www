<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?

define("ChatConstCmdAdminRemortPassword","333");
define("ChatConstAdminListDefLimit",25);

define("ChatAdminMessAdmLogKeepDaysCount",120);
define("ChatAdminMessAdmLogMessKeepDaysCount",30);

define("ChatConstBanIPDefTime"   ,10);
define("ChatConstBanIPDefMode"   ,ChatConstBanModelNoStdMess);

define("ChatConstBanUserDefTime" ,10);
define("ChatConstBanUserDefMode" ,ChatConstBanModelNoStdMess);

define("ChatAdminMinBanTimeForeValueInSec",120); // Минимум на 2 мин вперед

define("ChatAdminMaxBanTimeForLevel1InMin",10);
define("ChatAdminMaxBanTimeForLevel2InMin",30);
define("ChatAdminMaxBanTimeForLevel3InMin",2*60);
define("ChatAdminMaxBanTimeForLevel4InMin",8*60);
define("ChatAdminMaxBanTimeForLevel5InMin",30*24*60);

define("ChatAdminUserSelfNotesMaxLinesCount",20);
define("ChatAdminUserSelfNotesLinesMaxLength",200);
?>
