<html>
<head>
<META HTTP-EQUIV="Pragma" CONTENT="No-Cache">
<? include(dirname(__FILE__)."/"."../../chat/s_codepg.php") ?>
<BGSOUND name="ChatSoundItem" id="ChatSoundItem" SRC="">
</head>


<script language="JavaScript">
<!-- hide
<? include(dirname(__FILE__)."/"."../../chat/t_tools.js") ?>
<? include(dirname(__FILE__)."/"."../../forecols.js") ?>
// -->
</script>

<script language="JavaScript">
<!-- hide

// +++++++++++++ Global vars ++++++++++++++++++++++++++++++++++++++++++++++++

var ChatSendURL_cgiName      = "/_cgi/dreamchat.php";
var ChatSoundArg            = "/chat/c_messin.mid";

//Session SetUpInfo Object
function SetUpInfoObject(MyNick,SessionId,LastReadId,PreSID)
 {
  this.MyNick     = MyNick;
  this.SessionId  = SessionId;
  this.LastReadId = LastReadId;
  this.PreSID     = PreSID;
  this.ReqLagTime = 0.0;
 }

<?
include_once(dirname(__FILE__)."/"."../inc/funcstr.inc.php");
include_once(dirname(__FILE__)."/"."cformio.php");
?>

// PHP Code
var SetUpInfo = new SetUpInfoObject
 (
  <?echo JSFldStr($User_Nick) ?>,                  // MyNick
  <?echo JSFldStr($SID)       ?>,                  // SessionId
   0,                                              // LastReadId
  <?echo JSFldStr(ChatHashMakeFast()) ?>           // PreSID
 );
// PHP Code

function SetUpClientObject
          (ColorSheme,
           SoundOnPrivate,SoundOnNewUser,SoundOnMyMess,
           KeepPrivateFlag,KeepMessToFlag,
           GZIPStreamFlag)
 {
  this.ColorSheme      = ColorSheme;
  this.SoundOnPrivate  = SoundOnPrivate;
  this.SoundOnNewUser  = SoundOnNewUser;
  this.SoundOnMyMess   = SoundOnMyMess;
  this.KeepPrivateFlag = KeepPrivateFlag;
  this.KeepMessToFlag  = KeepMessToFlag;
  this.GZIPStreamFlag  = GZIPStreamFlag;
  this.DoNotScroll     = false;
  this.CarbonCopy2Pvt  = false;
 }

// Local setup load
var SetUpClientInfo = new SetUpClientObject
 (
  ""   , // ColorSheme
  false, // SoundOnPrivate
  false, // SoundOnNewUser
  false, // SoundOnMyMess
  false, // KeepPrivateFlag
  false, // KeepMessToFlag
  <? 
   if (!empty($GZIP))
    { 
     echo "true";
    }
   else
    {
     echo "false";
    } 
  ?> // GZIPStreamFlag
 );
// Local setup

<? include(dirname(__FILE__)."/"."../../chat/c_setup.js") ?>

function ChatStartSound (SoundFile)
 {
  ChatSoundItem.src = SoundFile;
 }

function ChatStopSound()
 {
  ChatSoundItem.src = "";
 }

function ChatSound()
 {
  ChatStartSound(ChatSoundArg); // Autostop since file is small;)
 }

// -->
</script>

<script language="JavaScript">
<!-- hide

// +++++++++++++ Debug trace ++++++++++++++++++++++++++++++++++++++++++++++++

var DebugActiveFlag = 
 <?
  if (defined("ChatClientDebugTrace"))
   {
    echo "true";
   }
  else
   {
    echo "false";
   }
 ?>
; // Debug(Text) prints messages

function Debug (Mess)
 {
  if (DebugActiveFlag)
   {
    top.DebugMessFrame.document.write("[:");
    top.DebugMessFrame.document.write(Mess);
    top.DebugMessFrame.document.writeln(":] ");
   }
 }

// -->
</script>


<script language="JavaScript">
<!-- hide
<? include(dirname(__FILE__)."/"."../../chat/c_tsend.js") ?>
<? include(dirname(__FILE__)."/"."../../chat/c_tgate.js") ?>
// -->
</script>

<script language="JavaScript">
<!-- hide
<? /* include(dirname(__FILE__)."/"."../../chat/s_text.js") */ ?>
// -->
</script>

<? include(dirname(__FILE__)."/"."../../chat/s_text.php") ?>

<script language="JavaScript">
<!-- hide
document.writeln("<title>");
document.write('<? echo $TextMainWindowTitleTagPrefixStr; ?>');
document.write(Str2Out(top.SetUpInfo.MyNick));
document.write('<? echo $TextMainWindowTitleTagPostfixStr; ?>');
document.writeln("</title>");
// -->
</script>

<script language="JavaScript">
<!-- hide

//FastBootCode:
function ChatFastBootActive()
 {
  <?
  if (isset($ChatFastBootFunctionBodyStr) && $ChatFastBootFunctionBodyStr != "")
   {
    echo "return(true);";
   }
  else
   {
    echo "return(false);";
   }
  echo "\n";
  ?>
 }

function ChatFastBoot()
 {
  var A=top;
  <?
  if (isset($ChatFastBootFunctionBodyStr) && $ChatFastBootFunctionBodyStr != "")
   {
    echo "\n";
    echo $ChatFastBootFunctionBodyStr;
   }
  echo "\n";
  ?>
 }

// -->
</script>

<script language="JavaScript">
<!-- hide
//BootCode: OnLoad="top.InputFrame.ChatBoot()";
// -->
</script>

<frameset id='RootFrameSet' rows=
 <?
  if (defined("ChatClientDebugTrace"))
   {
    echo "\"50,95,*,60\"";
   }
  else
   {
    echo "\"0,95,*,60\"";
   }
 ?>
>
 <frameset cols="*,*,*,80%">
  <frame src="/chat/c_zero.php"  name=NewMessFrame   SCROLLING=yes noresize border=0>
  <frame src="/chat/c_zero.php"  name=SendMessFrame  SCROLLING=yes noresize border=0>
  <frame src="/chat/c_zero.php"  name=FormsFrame     SCROLLING=yes noresize border=0>
  <frame src="/chat/c_zero.php"  name=DebugMessFrame SCROLLING=yes noresize border=0>
 </frameset>
 <frameset cols="477,20%">
  <frame src="/chat/c_top.php" name=TopFrame id='TopFrame'
   marginheight=0
   marginwidth=2
   SCROLLING=no
  >
  <frame src="/chat/c_setup.php"
         name=SetUpFrame id='SetUpFrame'
         marginheight=0
         marginwidth=0
         SCROLLING=no
  >
 </frameset>
 <frameset cols="78%,22%">
  <frameset rows="75%,25%">
   <frame src="/chat/c_logo.php" name=ChatFrame id='ChatFrame'
    marginheight=1
    marginwidth=1
    scrolling=auto
   >
   <frame src="/chat/c_zero.php" name=PrivateFrame id='PrivateFrame'
    marginheight=1
    marginwidth=1
    scrolling=auto
   >
  </frameset>
  <frame src="/chat/c_zero.php" name=NickFrame id='NickFrame'
    marginheight=1
    marginwidth=1
  >
 </frameset>
 <frame src="/chat/c_input.php" name=InputFrame id='InputFrame'
  marginheight=1
  marginwidth=1
 >
</frameset>
