<?
 include_once(dirname(__FILE__)."/"."db_setup.inc.php");

  // defined -> Errors will be showed at client browser alert(); dialog
//define("ChatSQLDieDebugTrace",1);

  // defined -> client frame debug mode will be activated
//define("ChatClientDebugTrace",1);

  // defined -> All requests traced to log (for privacy vars only length)
//define("ChatFullRequestTrace",1);

  // defined -> RegIn Message wil be shown only at first Update/Reload Request
  define("ChatDelayRegInMessage",1);

  // defined -> LogIn Message wil be shown only at first Update/Reload Request
//define("ChatDelayLogInMessage",1);

  // defined -> LogIn will peform fast boot (load messages at chat start)
  //            Note:Recomended not to use ChatDelayLogInMessage in that case
  define("ChatFastBootLogInAction",1);

  // defined -> RegIn will peform fast boot (load messages at chat start)
  //            Note:Recomended not to use ChatDelayRegInMessage in that case
//define("ChatFastBootRegInAction",1);
?>
