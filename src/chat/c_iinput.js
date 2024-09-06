var ChatLineMaxLength         = 350;
var ChatReloadTimeInSec       = 25;
var ChatStartUpTimeInSec      = 50;
var ChatPrevMessTextStr       = "";
var CFP_MMC = 10;
var CFP_MTS = 30;
var CFP_TAR    = new OS_C(CFP_MMC);
function F_CFPTS()
{
var CurrDate      = new Date();
var CurrTimeValue = CurrDate.getTime();
return(CurrTimeValue/1000.0);
}
function F_CFPSOK()
{
var CurrTime;
var MostTime;
var DiffTime;
if (CFP_TAR.Size() < CFP_TAR.MaxSize())
{
return(true);
}
CurrTime = F_CFPTS();
MostTime = CFP_TAR.OSF_GI(0);
DiffTime = CurrTime-MostTime;
if (DiffTime < 0)
{
top.Debug("FPR-time shifted back "+CFP_TAR.Size());
CFP_TAR.OSF_C();
return(true);
}
if (DiffTime < CFP_MTS)
{
top.Debug("FPR+"+CFP_TAR.Size());
return(false);
}
top.Debug("FPR-"+CFP_TAR.Size());
return(true);
}
function F_CFPMAR()
{
var CurrTime;
top.Debug("FPR(S)In:"+CFP_TAR.Size());
CurrTime = F_CFPTS();
if (CFP_TAR.Size() <= 0)
{
CFP_TAR.OSF_AI(CurrTime);
top.Debug("FPR(S)Out:"+CFP_TAR.Size());
return;
}
if (CFP_TAR.OSF_GI(CFP_TAR.Size()-1) > CurrTime)
{
top.Debug("FPR(S)-time shifted back");
CFP_TAR.OSF_C();
CFP_TAR.OSF_AI(CurrTime);
top.Debug("FPR(S)Out:"+CFP_TAR.Size());
return;
}
CFP_TAR.OSF_AI(CurrTime);
top.Debug("FPR(S)Out:"+CFP_TAR.Size());
}
function FSelNick(NickName)
{
top.InputFrame.document.InputForm.SendTo.value = NickName;
if (top.SetUpClientInfo.KeepPrivateFlag)
{
}
else
{
top.InputFrame.document.InputForm.MessPrivate.checked = false;
}
top.InputFrame.document.InputForm.MessText.focus();
}
function FSelNickPvt(NickName)
{
top.InputFrame.document.InputForm.SendTo.value = NickName;
top.InputFrame.document.InputForm.MessPrivate.checked = true;
top.InputFrame.document.InputForm.MessText.focus();
}
function FClickNick(NickName)
{
FSelNick(NickName);
}
function FClickNickPvt(NickName)
{
FSelNickPvt(NickName);
}
function FMuteFrom(NickName,NewMuteStatus)
{
var Nick;
Nick = NickSearchByName(NickName);
if (Nick != null)
{
if (Nick.MuteFromMe != NewMuteStatus)
{
Nick.MuteFromMe = NewMuteStatus;
NickNotifyUpdate(Nick);
MessageFullReDraw();
if (Nick.Text != "")
{
NickWindowDrawFull();
}
}
}
}
function FMPvtFrom(NickName,NewMuteStatus)
{
var Nick;
Nick = NickSearchByName(NickName);
if (Nick != null)
{
if (Nick.MPvtFromMe != NewMuteStatus)
{
Nick.MPvtFromMe = NewMuteStatus;
NickNotifyUpdate(Nick);
MessageFullReDraw();
if (Nick.Text != "")
{
NickWindowDrawFull();
}
}
}
}
function FMuteTo(NickName,NewMuteStatus)
{
var Nick;
Nick = NickSearchByName(NickName);
if (Nick != null)
{
if (Nick.MuteToMe != NewMuteStatus)
{
Nick.MuteToMe = NewMuteStatus;
NickNotifyUpdate(Nick);
MessageFullReDraw();
}
}
}
function ChatMessChangeCarbonCopy2Pvt(NewCarbonCopy2PvtStatus)
{
top.SetUpClientInfo.CarbonCopy2Pvt = NewCarbonCopy2PvtStatus;
FM_PR();
}
function ChatMessClearPrivat()
{
MessagePrivatDrawInit();
MessagePrivatDrawHeaderSign();
}
function FInsertImg (MetaImgNumber)
{
var Mess;
Mess  = document.InputForm.MessText.value;
Mess += M_CISPC;
Mess += MetaImgNumber;
Mess += M_CISPC;
if (Mess.length > ChatLineMaxLength)
{
alert(TextInputTooLongStr);
}
else if (FM_CIL(Mess) > M_MICPM)
{
alert( ""
+TextInputTooMayImgPrefixStr+M_MICPM+TextInputTooMayImgPostfixStr
);
}
else
{
document.InputForm.MessText.value = Mess;
document.InputForm.MessText.focus();
}
}
function ChatSendMessageFormSubmit()
{
var MessTo;
var MessText;
var MessPrivateFlag;
var SendItFlag;
MessTo   = document.InputForm.SendTo.value;
MessText = document.InputForm.MessText.value;
SendItFlag = true;
if (document.InputForm.MessPrivate.checked)
{
MessPrivateFlag = true;
}
else
{
MessPrivateFlag = false;
}
if (SendItFlag)
{
if (MessText == "")
{
alert(TextInputMessEmptyStr);
SendItFlag = false;
}
}
if (SendItFlag)
{
if (MessTo == top.SetUpInfo.MyNick)
{
alert(TextInputMessSelfStr);
SendItFlag = false;
}
}
if (SendItFlag)
{
if (MessTo == "")
{
if (MessPrivateFlag)
{
alert(TextInputMessNoPvtToNickStr);
SendItFlag = false;
}
}
}
if (SendItFlag)
{
if (ChatPrevMessTextStr != "")
{
if ((ChatPrevMessTextStr == MessText) && (MessTo == ""))
{
alert(TextInputMessAllDuplicateStr);
document.InputForm.MessText.value = "";
SendItFlag = false;
}
}
}
if (SendItFlag)
{
if (FM_CIL(MessText) > M_MICPM)
{
alert( ""
+TextInputTooMayImgPrefixStr+M_MICPM+TextInputTooMayImgPostfixStr
);
SendItFlag = false;
}
}
if (SendItFlag)
{
if (!F_CFPSOK())
{
alert( ""
+TextInputMaxSpeedPrefixStr
+CFP_MMC
+TextInputMaxSpeedItemsInStr
+CFP_MTS
+TextInputMaxSpeedPostfixStr
);
SendItFlag = false;
}
}
if (SendItFlag)
{
F_CFPMAR();
ChatSendMessage(MessTo,MessText,MessPrivateFlag);
if (MessTo == "")
{
ChatPrevMessTextStr = MessText;
}
else
{
ChatPrevMessTextStr = "";
}
if (top.SetUpClientInfo.KeepMessToFlag)
{
}
else
{
document.InputForm.SendTo.value = "";
}
document.InputForm.MessText.value = "";
if (top.SetUpClientInfo.KeepPrivateFlag)
{
}
else
{
document.InputForm.MessPrivate.checked = false;
}
}
document.InputForm.MessText.focus();
return(false);
}
