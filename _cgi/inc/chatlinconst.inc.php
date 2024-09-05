<?
include_once(dirname(__FILE__)."/"."chatcodeconst.inc.php");

// --- --- --- Used by chatlindata.inc.php & chatlindata.js.php ---
// Global security item (move it to setup.inc.php)
define("ChatPrePassPrefix","123454321");

// min is (PasswordMaxLen + 1). Note:PasswordMaxLen is 15 now
define("ChatConstLogDataMaxSize",20);

// number of post iterations
define("ChatConstLogDataPostPassMax",1);

// *** Client SetUp (used by chatlindata.js.php) ***
// if defined, LogData login mode will be used
define("ChatConstLogDataActiveFlag",1);
// if defined, PlainText password field will be cleared at action
define("ChatConstLogDataPasswordClearPlainTextFlag",1);

?>
