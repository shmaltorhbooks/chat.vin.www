function ChatFillClearInputRequest()
{
top.NewMessFrame.location = "/chat/c_zero.htm";
top.NewMessFrame.document.open();
top.NewMessFrame.document.close();
}
function ChatFillClearOutputRequest()
{
top.SendMessFrame.location = "/chat/c_zero.htm";
top.SendMessFrame.document.open();
top.SendMessFrame.document.close();
}
function ChatCalcSendTime()
{
var SendDate      = new Date();
var SendTimeValue = SendDate.getTime();
return(SendTimeValue);
}
var ChatAdvtReloadTimeInSec = (2*60);
var ChatAdvtTimerId = null;
function ChatAdvtStartUpdateTimer()
{
if (ChatAdvtTimerId != null)
{
clearTimeout(ChatAdvtTimerId);
}
ChatAdvtTimerId = setTimeout("ChatAdvtUpdate()",ChatAdvtReloadTimeInSec*1000);
}
function ChatAdvtStopUpdateTimer()
{
if (ChatAdvtTimerId == null)
{
}
else
{
clearTimeout(ChatAdvtTimerId);
}
}
function ChatAdvtReStartUpdateTimer()
{
if (ChatAdvtTimerId == null)
{
}
else
{
clearTimeout(ChatAdvtTimerId);
ChatAdvtTimerId = null;
}
ChatAdvtStartUpdateTimer();
}
function ChatAdvtUpdate()
{
ChatAdvtTimerId = null;
top.ChatForceAdvtData();
ChatAdvtStartUpdateTimer();
}
function ChatAdvtForceUpdate()
{
ChatAdvtReStartUpdateTimer();
ChatForceAdvtDataAction();
}
var ChatAdvtIdleReloadTimeInSec = (4*60);
var ChatAdvtIdleTimerId = null;
function ChatAdvtIdleStartUpdateTimer()
{
if (ChatAdvtIdleTimerId != null)
{
clearTimeout(ChatAdvtIdleTimerId);
}
ChatAdvtIdleTimerId = setTimeout("ChatAdvtIdleUpdate()",ChatAdvtIdleReloadTimeInSec*1000);
}
function ChatAdvtIdleStopUpdateTimer()
{
if (ChatAdvtIdleTimerId == null)
{
}
else
{
clearTimeout(ChatAdvtIdleTimerId);
}
}
function ChatAdvtIdleReStartUpdateTimer()
{
if (ChatAdvtIdleTimerId == null)
{
}
else
{
clearTimeout(ChatAdvtIdleTimerId);
ChatAdvtIdleTimerId = null;
}
ChatAdvtIdleStartUpdateTimer();
}
function ChatAdvtIdleUpdate()
{
ChatAdvtIdleTimerId = null;
top.ChatForceAdvtIdleData();
ChatAdvtIdleStartUpdateTimer();
}
function ChatAdvtIdleForceUpdate()
{
ChatAdvtIdleReStartUpdateTimer();
ChatForceAdvtIdleDataAction();
}
function ChatForceAdvtDataReloadBanner()
{
top.TopFrame.location = "/chat/c_zero.htm";
top.TopFrame.document.bgColor = top.SetUpFrame.document.bgColor;
top.TopFrame.document.fgColor = top.SetUpFrame.document.fgColor;
top.TopFrame.location = "/chat/c_top.php";
}
function ChatAdvtReStartTimer()
{
ChatAdvtReStartUpdateTimer();
ChatAdvtIdleReStartUpdateTimer();
}
var ChatAdvtTriggerFlag = false;
function ChatForceAdvtDataAction()
{
ChatAdvtTriggerFlag = false;
ChatForceAdvtDataReloadBanner();
ChatAdvtReStartTimer();
}
function ChatCheckAdvtData()
{
if (ChatAdvtTriggerFlag)
{
ChatAdvtTriggerFlag = false;
ChatForceAdvtDataAction();
}
}
function ChatForceAdvtData()
{
ChatAdvtTriggerFlag = true;
}
function ChatForceAdvtIdleDataAction()
{
ChatForceAdvtDataReloadBanner();
ChatAdvtReStartTimer();
}
function ChatForceAdvtIdleData()
{
ChatForceAdvtIdleDataAction();
}
function ChatCheckAdvtIdleData()
{
ChatForceAdvtIdleDataAction();
}
function ChatMoveChatScroll()
{
if (top.SetUpClientInfo.DoNotScroll)
{
}
else
{
top.ChatFrame.document.bgColor
= top.ChatFrame.document.bgColor;
top.ChatFrame.scroll(0,999999);
}
}
function ChatMovePrivatScroll()
{
if (top.SetUpClientInfo.DoNotScroll)
{
}
else
{
top.PrivateFrame.document.bgColor
= top.PrivateFrame.document.bgColor;
top.PrivateFrame.scroll(0,999999);
}
}
function ChatSendFillGZIP(Form)
{
if (top.SetUpClientInfo.GZIPStreamFlag)
{
Form.GZIP.value = "1";
}
else
{
Form.GZIP.value = "";
}
}
function ChatSendLeave()
{
top.InputFrame.document.LeaveForm.SID.value    = top.SetUpInfo.SessionId;
top.InputFrame.document.LeaveForm.Nick.value   = top.SetUpInfo.MyNick;
top.InputFrame.document.LeaveForm.PreSID.value = top.SetUpInfo.PreSID;
ChatSendFillGZIP(top.InputFrame.document.LeaveForm);
top.InputFrame.document.LeaveForm.submit();
}
function ChatSendReLoad()
{
top.InputFrame.document.ReLoadForm.ReqSendTime.value = ChatCalcSendTime();
top.InputFrame.document.ReLoadForm.SID.value    = top.SetUpInfo.SessionId;
top.InputFrame.document.ReLoadForm.MID.value    = "0";
top.InputFrame.document.ReLoadForm.Nick.value   = top.SetUpInfo.MyNick;
top.InputFrame.document.ReLoadForm.PreSID.value = top.SetUpInfo.PreSID;
ChatSendFillGZIP(top.InputFrame.document.ReLoadForm);
top.InputFrame.document.ReLoadForm.submit();
}
function ChatSendUpdate()
{
top.InputFrame.document.UpdateForm.ReqSendTime.value = ChatCalcSendTime();
top.InputFrame.document.UpdateForm.SID.value    = top.SetUpInfo.SessionId;
top.InputFrame.document.UpdateForm.MID.value    = top.SetUpInfo.LastReadId;
top.InputFrame.document.UpdateForm.Nick.value   = top.SetUpInfo.MyNick;
top.InputFrame.document.UpdateForm.PreSID.value = top.SetUpInfo.PreSID;
ChatSendFillGZIP(top.InputFrame.document.UpdateForm);
top.InputFrame.document.UpdateForm.submit();
}
function ChatSendMessageAction(MessTo,MessText,MessPrivateFlag)
{
var MessPvt;
if (MessPrivateFlag)
{
MessPvt = "1";
}
else
{
MessPvt = "0";
}
top.InputFrame.document.SendMessForm.ReqSendTime.value = ChatCalcSendTime();
top.InputFrame.document.SendMessForm.SID.value      = top.SetUpInfo.SessionId;
top.InputFrame.document.SendMessForm.Nick.value     = top.SetUpInfo.MyNick;
top.InputFrame.document.SendMessForm.MessTo.value   = MessTo;
top.InputFrame.document.SendMessForm.MessText.value = MessText;
top.InputFrame.document.SendMessForm.MessPvt.value  = MessPvt;
top.InputFrame.document.SendMessForm.PreSID.value   = top.SetUpInfo.PreSID;
ChatSendFillGZIP(top.InputFrame.document.SendMessForm);
top.InputFrame.document.SendMessForm.submit();
ChatCheckAdvtData();
}
function ChatSendFullMessageAction(MessTo,MessText,MessPrivateFlag)
{
var MessPvt;
if (MessPrivateFlag)
{
MessPvt = "1";
}
else
{
MessPvt = "0";
}
top.InputFrame.document.SendFullForm.ReqSendTime.value = ChatCalcSendTime();
top.InputFrame.document.SendFullForm.SID.value      = top.SetUpInfo.SessionId;
top.InputFrame.document.SendFullForm.MID.value      = top.SetUpInfo.LastReadId;
top.InputFrame.document.SendFullForm.Nick.value     = top.SetUpInfo.MyNick;
top.InputFrame.document.SendFullForm.MessTo.value   = MessTo;
top.InputFrame.document.SendFullForm.MessText.value = MessText;
top.InputFrame.document.SendFullForm.MessPvt.value  = MessPvt;
top.InputFrame.document.SendFullForm.PreSID.value   = top.SetUpInfo.PreSID;
ChatSendFillGZIP(top.InputFrame.document.SendFullForm);
top.InputFrame.document.SendFullForm.submit();
ChatCheckAdvtData();
}
