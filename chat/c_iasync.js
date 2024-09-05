var ChatTimerId = null;
function ChatFillStartUpdateTimer()
{
var    TimeOutValue;
if (ChatTimerId != null)
{
clearTimeout(ChatTimerId);
}
if (top.SetUpInfo.LastReadId == 0)
{
TimeOutValue = ChatStartUpTimeInSec*1000;
top.Debug('UpdateTimerOn:'+ChatStartUpTimeInSec+'s');
}
else
{
TimeOutValue = ChatReloadTimeInSec*1000;
top.Debug('UpdateTimerOn:'+ChatReloadTimeInSec+'s');
}
ChatTimerId = setTimeout("ChatFillUpdate()",TimeOutValue);
}
function ChatFillStopUpdateTimer()
{
if (ChatTimerId == null)
{
}
else
{
clearTimeout(ChatTimerId);
}
top.Debug('UpdateTimerOff');
}
function ChatFillReStartUpdateTimer()
{
if (ChatTimerId == null)
{
}
else
{
clearTimeout(ChatTimerId);
ChatTimerId = null;
}
top.Debug('UpdateTimerOff(Restart)');
ChatFillStartUpdateTimer();
}
function ChatFillUpdate()
{
top.Debug('UpdateTimer!');
ChatTimerId = null;
NickLogUpdateInit();
if (top.SetUpInfo.LastEventId == 0)
{
top.ChatSendReLoad();
}
else
{
top.ChatSendUpdate();
}
ChatFillStartUpdateTimer();
}
function ChatFillReLoad()
{
ChatFillStopUpdateTimer();
NickLogUpdateInit();
top.ChatSendReLoad();
ChatFillStartUpdateTimer();
}
function ChatFastReLoadActive()
{
return(top.ChatFastBootActive());
}
function ChatFastReLoad()
{
ChatFillStopUpdateTimer();
NickLogUpdateInit();
top.ChatFastBoot();
ChatFillStartUpdateTimer();
}
function ChatSendMessage(MessTo,MessText,MessPrivateFlag)
{
NickLogUpdateInit();
top.ChatSendFullMessageAction(MessTo,MessText,MessPrivateFlag);
}
function ChatLeave()
{
if (confirm(TextExitConfirmStr))
{
top.ChatAdvtIdleStopUpdateTimer();
top.ChatAdvtStopUpdateTimer();
ChatFillStopUpdateTimer();
top.ChatFillClearInputRequest();
top.ChatFillClearOutputRequest();
top.ChatSendLeave();
}
}
var NickLogUpdateNeedReDraw = false;
function NickLogUpdateInit()
{
NickLogUpdateNeedReDraw = false;
}
function NickLogUpdateDone()
{
if (NickLogUpdateNeedReDraw)
{
NickWindowDrawFull();
}
NickLogUpdateNeedReDraw = false;
}
function NickSelectAppend(Nick)
{
}
function NickSelectDelete(Nick)
{
}
function ChatSoundNewNick()
{
if (top.SetUpClientInfo.SoundOnNewUser)
{
top.ChatSound();
}
}
function ChatSoundPrivateMessToMe()
{
if (top.SetUpClientInfo.SoundOnPrivate)
{
top.ChatSound();
}
}
function ChatSoundChatMessToMe()
{
if (top.SetUpClientInfo.SoundOnMyMess)
{
top.ChatSound();
}
}
function NickLogIn(Name,Color,Sex,TimeLogIn,Text,HasInfoFlag,HasMyNoteFlag)
{
var CurrentNick;
CurrentNick = NickSearchByName(Name);
if (CurrentNick == null)
{
var NewNick = new Nick(Name,Color,Sex,TimeLogIn,Text,HasInfoFlag,HasMyNoteFlag);
NickAppend(NewNick);
NickSelectAppend(NewNick);
ChatSoundNewNick();
}
else
{
F_NUINF(CurrentNick,Name,Color,Sex,TimeLogIn,Text,HasInfoFlag,HasMyNoteFlag);
}
NickLogUpdateNeedReDraw = true;
}
function NickLogOff(Name)
{
var CurrentNick;
CurrentNick = NickSearchByName(Name);
if (CurrentNick == null)
{
}
else
{
NickSelectDelete(CurrentNick);
CurrentNick.Status = N_STF;
}
NickLogUpdateNeedReDraw = true;
}
function NickLogNewStatus(Name,NewState)
{
if (NewState >= N_STF)
{
NickLogOff(Name);
}
else
{
var CurrentNick;
CurrentNick = NickSearchByName(Name);
if (CurrentNick == null)
{
}
else
{
CurrentNick.Status = NewState;
}
}
NickLogUpdateNeedReDraw = true;
}
function MessageReciveChat   (Model,Time,From,To,Text,Color,EventId)
{
var Mess;
Mess = new Message(Model,Time,From,To,Text,Color,false);
if (top.SetUpInfo.LastReadId >= EventId)
{
}
else
{
top.SetUpInfo.LastReadId = EventId;
MessageArrayAppend(M_StdA,Mess);
Mess = new Message(Model,Time,From,To,Text,Color,false);
if (MessagePassedFilter(Mess,false))
{
var TargetFrame;
var TD;
TargetFrame = top.ChatFrame;
TD   = TargetFrame.document;
Mess = new Message(Model,Time,From,To,Text,Color,false);
MessageDrawInDoc(TD,Mess,false);
top.ChatMoveChatScroll();
if (MessageNeedCarbonCopy2Pvt(Mess))
{
var TargetFrame;
var TD;
TargetFrame = top.PrivateFrame;
TD   = TargetFrame.document;
Mess = new Message(Model,Time,From,To,Text,Color,false);
MessageDrawInDocCarbonCopy2Pvt(TD,Mess,false);
top.ChatMovePrivatScroll();
}
if (Model == M_ModM)
{
if (To.toUpperCase() == top.SetUpInfo.MyNick.toUpperCase())
{
ChatSoundChatMessToMe();
}
}
}
else
{
top.Debug("MuteChat");
}
}
}
function MessageRecivePrivat (Model,Time,From,To,Text,Color,EventId)
{
var Mess;
Mess = new Message(Model,Time,From,To,Text,Color,true);
if (top.SetUpInfo.LastReadId >= EventId)
{
}
else
{
top.SetUpInfo.LastReadId = EventId;
MessageArrayAppend(M_PvtA,Mess);
Mess = new Message(Model,Time,From,To,Text,Color,true);
if (MessagePassedFilter(Mess,true))
{
var TargetFrame;
var TD;
TargetFrame = top.PrivateFrame;
TD   = TargetFrame.document;
Mess = new Message(Model,Time,From,To,Text,Color,true);
MessageDrawInDoc(TD,Mess,true);
top.ChatMovePrivatScroll();
if (Model == M_ModM)
{
if (To.toUpperCase() == top.SetUpInfo.MyNick.toUpperCase())
{
ChatSoundPrivateMessToMe();
}
}
}
else
{
top.Debug("MutePrivate");
}
}
}
function ChatFillUpdateDone (LastSendMessId)
{
if (top.SetUpInfo.LastReadId >= LastSendMessId)
{
top.Debug("CFUDone:* In:"+LastSendMessId+" Curr:"+top.SetUpInfo.LastReadId);
}
else
{
top.Debug("CFUDone:> In:"+LastSendMessId+" Curr:"+top.SetUpInfo.LastReadId);
top.SetUpInfo.LastReadId = LastSendMessId;
}
NickLogUpdateDone();
}
function ChatFillSendDone()
{
}
function ChatFillFullSendDone(LastSendMessId)
{
ChatFillUpdateDone(LastSendMessId);
ChatFillSendDone();
}
function ChatUpdatePreSID(NewPreSID)
{
top.SetUpInfo.PreSID = NewPreSID;
}
function ChatUpdateReqLag(ReqSendTime)
{
var CurrDate      = new Date();
var CurrTimeValue = CurrDate.getTime();
var SendTimeValue;
var ReqSpendTime;
SendTimeValue = parseInt(ReqSendTime,10);
if (isNaN(SendTimeValue))
{
SendTimeValue = CurrTimeValue;
}
ReqSpendTime = CurrTimeValue-SendTimeValue;
if (ReqSpendTime < 0)
{
ReqSpendTime = 0;
}
top.SetUpInfo.ReqLagTime = Math.round(ReqSpendTime/100)/10.0;
}
function MessageDrawPrivatDirect(Model,Time,From,To,Text,Color,EventId)
{
var TargetFrame;
var TD;
TargetFrame = top.PrivateFrame;
TD   = TargetFrame.document;
Mess = new Message(Model,Time,From,To,Text,Color,true);
MessageDrawInDoc(TD,Mess,true);
top.ChatMovePrivatScroll();
}
function MessageDrawPrivatText(Text)
{
var TargetFrame;
var TD;
TargetFrame = top.PrivateFrame;
TD   = TargetFrame.document;
TD.write(Text);
top.ChatMovePrivatScroll();
}
function MessageDrawChatText(Text)
{
var TargetFrame;
var TD;
TargetFrame = top.ChatFrame;
TD   = TargetFrame.document;
TD.write(Text);
top.ChatMoveChatScroll();
}
function ChatBoot()
{
top.Debug("Boot");
top.ChatFillClearInputRequest();
top.ChatFillClearOutputRequest();
MessagePrivatDrawHeaderSign();
if (ChatFastReLoadActive())
{
top.Debug("FastReloadIn");
ChatFastReLoad();
top.Debug("FastReloadDone");
}
else
{
top.Debug("StartReloadIn");
ChatFillReLoad();
top.Debug("StartReloadDone");
}
top.ChatAdvtStartUpdateTimer();
top.ChatAdvtIdleStartUpdateTimer();
}
