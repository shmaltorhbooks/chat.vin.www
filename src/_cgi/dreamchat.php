<?
define('ChatInside',1);

include_once(dirname(__FILE__)."/"."inc/setup.inc.php");
include_once(dirname(__FILE__)."/"."inc/constant.inc.php");
include_once(dirname(__FILE__)."/"."inc/chatlog.inc.php");
include_once(dirname(__FILE__)."/"."inc/funcstr.inc.php");

include_once(dirname(__FILE__)."/"."ext_link/extchatload.php");

include     (dirname(__FILE__)."/"."../s_main.php");

//include     ("home/chat/www/s_main.php");
// ***** Fast checks *****



// HASH PRE-CHECK START

global $HTTP_POST_VARS;

include_once(dirname(__FILE__)."/"."inc/hash.inc.php");

$HashCheckValid = FALSE;

$PreSID = ArgAsStr(ArgRawValue($HTTP_POST_VARS['PreSID']));
$Nick   = ArgAsStr(ArgRawValue($HTTP_POST_VARS['Nick']));
$SID    = ArgAsStr(ArgRawValue($HTTP_POST_VARS['SID']));

if      (isset($HTTP_POST_VARS['CmdFastLogIn']) || 
         isset($HTTP_POST_VARS['CmdSetsLogIn']) || 
         isset($HTTP_POST_VARS['CmdChatRegIn']))
 {
  // Interactive StartUp
  if (isset($PreSID))
   {
    $HashCheckValid = ChatHashCheckFullIsValid($PreSID);
   }
 }
else if (isset($HTTP_POST_VARS['CmdSIDGoChat']) ||
         isset($HTTP_POST_VARS['CmdSIDUpdateSet']))
 {
  // Interactive, But SID protected
  // May be will use ...Fast... SID model later
  if (isset($PreSID))
   {
    $HashCheckValid = ChatHashCheckFullIsValid($PreSID);
   }
 }
else // Live-Time requets
 {
  if (isset($PreSID))
   {
    $HashCheckValid = ChatHashCheckFastIsValid($PreSID);
   }
 }

if (!$HashCheckValid)
 {
  ChatServerLogWriteWarning("Invalid PreSID Used. Nick(SL)=[".$Nick."] PreSID(SL)=[".$PreSID."]");

  $ChatErrorText  = "Ќедопустимый контекст запроса";
  include(dirname(__FILE__)."/"."body/chaterror.php");
  exit; // Fire exit(BUG TRAP)
 }

// Fast IP Check

include_once(dirname(__FILE__)."/"."inc/iphardcheck.inc.php");

$Nick   = ArgAsStr(ArgRawValue($HTTP_POST_VARS['Nick']));
$SID    = ArgAsStr(ArgRawValue($HTTP_POST_VARS['SID']));

if (!ChatIPHardAllowed($HTTP_SERVER_VARS["REMOTE_ADDR"]))
 {
  ChatServerLogWriteWarning("Blocked IP Used. Nick(SL)=[".$Nick."] SID(SL)=[".$SID."]");

  $ChatErrorText  = "<br>";
  $ChatErrorText .= "¬аш IP адрес находитс€ в <b><font color=black>черном списке</font></b>."."<br>";

  $ChatErrorText .= ""."<br>";
  $ChatErrorText .= "¬еро€тные причины:"."<br>";
  $ChatErrorText .= "1.јдрес €вл€етс€(€вл€лс€) источником атак на сервер."."<br>";
  $ChatErrorText .= "2.— этого сетевого адреса происходили систематические нарушени€ правил чата."."<br>";
  $ChatErrorText .= ""."<br>";

  $ChatErrorText .= "  сожалению, вы не можете пользоватьс€ услугами нашего чата."."<br>";
  $ChatErrorText .= "≈сли вы обычный пользователь и считаете что блокированы "."<br>";
  $ChatErrorText .= "ошибочно, обратитесть к администратору чата по адресу:"."<br>";
  $ChatErrorText .= " chat@firmradio.net"."<br>";

  include_once(dirname(__FILE__)."/"."tpl/c_error.htp.php");
  exit; // Fire exit(BUG TRAP)
 }

// ***** MAIN BODY *****

// -------------- Argument prepare --------------- Start

include_once(dirname(__FILE__)."/"."tpl/cformio.php");

$ArgFieldNamesAsStrArray = array
  (
   'PreSID'      => 'STR',
   'Room'        => 'STR',
   'SID'         => 'STR',
   'Nick'        => 'STR',
   'NickSrc'     => 'STR',
   'Password'    => 'STR',
   'PasswordAdd' => 'STR',
   'Color'       => 'STR',
   'Topic'       => 'STR',
   'Gender'      => 'STR',
   'EMail'       => 'STR',
   'MessText'    => 'STR',
   'MessTo'      => 'STR',
   'ReqSendTime' => 'STR',
   'LogData'     => 'STR',
   'LogDataAdd'  => 'STR',

   'MessPvt'     => 'INT',
   'MID'         => 'INT',
   'GZIP'        => 'INT',
   'ClientMode'  => 'INT',

   'NickNotes'   => 'TEXT',
   'SelfNotes'   => 'TEXT'
  );

foreach($ArgFieldNamesAsStrArray as $ArgName => $ArgType)
 {
  if (isset($HTTP_POST_VARS[$ArgName]))
   {
    $ArgNameJS = 'TR_'.$ArgName;
    $ArgValue  = $HTTP_POST_VARS[$ArgName];

    if      ($ArgType == 'TEXT')
     {
      $ArgValue   = ArgAsText(ArgRawValue($ArgValue));
      $ArgValueJS = JSStr($ArgValue);
      $ArgFldJS   = JSFldStr($ArgValue);
     }
    else if ($ArgType == 'INT')
     {
      $ArgValue   = ArgAsInt(ArgRawValue($ArgValue));
      $ArgValueJS = JSInt($ArgValue);
      $ArgFldJS   = JSFldInt($ArgValue);
     }
    else // 'STR'
     {
      $ArgValue   = ArgAsStr(ArgRawValue($ArgValue));
      $ArgValueJS = JSStr($ArgValue);
      $ArgFldJS   = JSFldStr($ArgValue);
     }

    $$ArgName   = $ArgValue;
    $$ArgNameJS = $ArgValueJS;

    FORM_IOPutVar($ArgNameJS,$ArgValue);
   }
 }

// -------------- Argument prepare --------------- Done

if (trim($Room) == "")
 {
  $Room = ChatConstDefaultChatRoom;
 }

if (!isset($ReqSendTime))
 {
  $ReqSendTime = "";
 }

if (!empty($GZIP))
 {
  ob_start("ob_gzhandler");
 }

include_once(dirname(__FILE__)."/"."inc/chatlindata.inc.php");

if (defined("ChatConstLogDataActiveFlag"))
 {
  $ChatErrorText  = "Ќедопустимые параметры авторизации";

  if      (isset($HTTP_POST_VARS['CmdSIDGoChat']))
   {
    $ClearNick = $NickSrc;
   }
  else if (isset($HTTP_POST_VARS['CmdSIDUpdateSet']))
   {
    $ClearNick = $NickSrc;
   }
  else
   {
    $ClearNick = $Nick;
   }

  if (defined("ChatConstLogDataPasswordClearPlainTextFlag"))
   {
    if ((!empty($Password)) || (!empty($PasswordAdd)))
     {
      ChatServerLogWriteWarning
       ("Password supplied on crypt login CNick(SL)=[".$ClearNick."]"
       ." strlen(Pass/PassAdd)=[".strlen($Password)."/".strlen($PasswordAdd)."]"
       );
      include(dirname(__FILE__)."/"."body/chaterror.php");
      exit; // Fire exit(BUG TRAP)
     }
   }

  $AuxArray = ChatPrePassArrayMake($PreSID);

  if (!empty($LogData))
   {
    $ClearPassword = ChatLogDataParse(explode("/",$LogData),$AuxArray,$PreSID,$ClearNick);

    if (empty($ClearPassword))
     {
      ChatServerLogWriteWarning
       ("Error uncrypt Password data CNick(SL)=[".$ClearNick."]"
       ." strlen(LogData)=[".strlen($LogData)."]"
       );

      if (empty($Password))
       {
        include(dirname(__FILE__)."/"."body/chaterror.php");
        exit; // Fire exit(BUG TRAP)
       }
     }
   }

  if (!empty($LogDataAdd))
   {
    $ClearPasswordAdd = ChatLogDataParse(explode("/",$LogDataAdd),$AuxArray,$PreSID,$ClearNick);

    if (empty($ClearPasswordAdd))
     {
      ChatServerLogWriteWarning
       ("Error uncrypt PasswordAdd data CNick(SL)=[".$ClearNick."]"
       ." strlen(LogDataAdd)=[".strlen($LogDataAdd)."]"
       );

      if (empty($PasswordAdd))
       {
        include(dirname(__FILE__)."/"."body/chaterror.php");
        exit; // Fire exit(BUG TRAP)
       }
     }
   }

  if (!empty($Password))
   {
    if ($Password != $ClearPassword)
     {
      ChatServerLogWriteWarning
       ("Password uncrypt not match CNick(SL)=[".$ClearNick."]"
       ." strlen(Pass/Clear)=[".strlen($Password)."/".strlen($ClearPassword)."]"
       );

      $ClearPassword = $Password;
     }
   }

  if (!empty($PasswordAdd))
   {
    if ($PasswordAdd != $ClearPasswordAdd)
     {
      ChatServerLogWriteWarning
       ("Password uncrypt not match CNick(SL)=[".$ClearNick."]"
       ." strlen(PassAdd/ClearAdd)=[".strlen($PasswordAdd)."/".strlen($ClearPasswordAdd)."]"
       );

      $ClearPasswordAdd = $PasswordAdd;
     }
   }

  if (!empty($LogData))
   {
    $Password    = $ClearPassword;
   }

  if (!empty($LogDataAdd))
   {
    $PasswordAdd = $ClearPasswordAdd;
   }
 }

$ChatErrorText  = "ќбщий сбой обработки запроса"; // 'CatchUp' Error

/* ѕроверка ника на совпадение с зарезервированым */

if (strtoupper($Nick) == strtoupper(ChatConstPureAdminNick))
 {
  ChatServerLogWriteWarning("Try to use reserved nick. Nick(SL)=[".$Nick."]");

  $ChatErrorText  = "»спользование зарезервированного ника";
  include(dirname(__FILE__)."/"."body/chaterror.php");
  exit; // Fire exit(BUG TRAP)
 }

include(dirname(__FILE__)."/"."inc/db_open.inc.php");

include_once(dirname(__FILE__)."/"."ext_link/extfromchat.php");
ExtFromChatDBLinkIdRegister($ChatDBLinkId);

include_once(dirname(__FILE__)."/"."inc/request.inc.php");

ChatClearConnectedUser_Vars();

if      (isset($HTTP_POST_VARS['CmdChatUpdate']))
 {
  ChatServerLogWriteReqLog("CmdChatUpdate:$CmdChatUpdate");
  include(dirname(__FILE__)."/"."body/chatupdate.php");
 }
else if (isset($HTTP_POST_VARS['CmdChatSend']))
 {
  ChatServerLogWriteReqLog("CmdChatSend:$CmdChatSend");
  include(dirname(__FILE__)."/"."body/chatsend.php");
 }
else if (isset($HTTP_POST_VARS['CmdChatSendFull']))
 {
  ChatServerLogWriteReqLog("CmdChatSendFull:$CmdChatSendFull");
  include(dirname(__FILE__)."/"."body/chatsendfull.php");
 }
else if (isset($HTTP_POST_VARS['CmdChatLeave']))
 {
  ChatServerLogWriteReqLog("CmdChatLeave:$CmdChatLeave");
  include(dirname(__FILE__)."/"."body/chatleave.php");
 }
else if (isset($HTTP_POST_VARS['CmdChatReLoad']))
 {
  ChatServerLogWriteReqLog("CmdChatReLoad:$CmdChatReLoad");
  include(dirname(__FILE__)."/"."body/chatreload.php");
 }
else if (isset($HTTP_POST_VARS['CmdFastLogIn']))
 {
  ChatServerLogWriteReqLog("CmdFastLogIn:$CmdFastLogIn");
  include(dirname(__FILE__)."/"."body/chatfastlogin.php");
 }
else if (isset($HTTP_POST_VARS['CmdSetsLogIn']))
 {
  ChatServerLogWriteReqLog("CmdSetsLogIn:$CmdSetsLogIn");
  include(dirname(__FILE__)."/"."body/chatsetslogin.php");
 }
else if (isset($HTTP_POST_VARS['CmdSIDGoChat']))
 {
  ChatServerLogWriteReqLog("CmdSIDGoChat:$CmdSIDGoChat");
  include(dirname(__FILE__)."/"."body/chatsidgochat.php");
 }
else if (isset($HTTP_POST_VARS['CmdSIDUpdateSet']))
 {
  ChatServerLogWriteReqLog("CmdSIDUpdateSet:$CmdSIDUpdateSet");
  include(dirname(__FILE__)."/"."body/chatsidupdateset.php");
 }
else if (isset($HTTP_POST_VARS['CmdChatRegIn']))
 {
  ChatServerLogWriteReqLog("CmdChatRegIn:$CmdChatRegIn");
  include(dirname(__FILE__)."/"."body/chatregin.php");
 }
else if (isset($HTTP_POST_VARS['CmdChatNickInfo']))
 {
  ChatServerLogWriteReqLog("CmdChatNickInfo:$CmdChatNickInfo");
  include(dirname(__FILE__)."/"."body/chatnickinfo.php");
 }
else if (isset($HTTP_POST_VARS['CmdChatNickSetNote']))
 {
  ChatServerLogWriteReqLog("CmdChatNickSetNote:$CmdChatNickSetNote");
  include(dirname(__FILE__)."/"."body/chatnicksetnotes.php");
 }
else if (isset($HTTP_POST_VARS['CmdChatMyInfo']))
 {
  ChatServerLogWriteReqLog("CmdChatMyInfo:$CmdChatMyInfo");
  include(dirname(__FILE__)."/"."body/chatmyinfo.php");
 }
else if (isset($HTTP_POST_VARS['CmdChatNickSetSelfNote']))
 {
  ChatServerLogWriteReqLog("CmdChatNickSetSelfNote:$CmdChatNickSetSelfNote");
  include(dirname(__FILE__)."/"."body/chatmysetinfo.php");
 }
else if (isset($HTTP_POST_VARS['CmdChatMyForumInfo']))
 {
  ChatServerLogWriteReqLog("CmdChatMyForumInfo:$CmdChatMyForumInfo");
  include(dirname(__FILE__)."/"."body/chatmyforuminfo.php");
 }
else if (isset($HTTP_POST_VARS['ChatGoToForum']))
 {
  ChatServerLogWriteReqLog("CmdChatGoToForum:$ChatGoToForum");
  include(dirname(__FILE__)."/"."body/chatgotoforum.php");
 }
else
 {
  ChatServerLogWriteReqLog("Unknown:?");
  include(dirname(__FILE__)."/"."body/chaterror.php");
 }

include(dirname(__FILE__)."/"."inc/db_close.inc.php");
ExtFromChatDBLinkIdRegister(null);
?>
