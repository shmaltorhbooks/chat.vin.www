function NL(Name,Color,Sex,TimeLogIn,Text,HasInfoFlag,HasMyNoteFlag)
{
top.InputFrame.NickLogIn(Name,Color,Sex,TimeLogIn,Text,HasInfoFlag,HasMyNoteFlag);
}
function NO(Name)
{
top.InputFrame.NickLogOff(Name);
}
function NS(Name,NewState)
{
top.InputFrame.NickLogNewStatus(Name,NewState);
}
function MC(Model,Time,From,To,Text,Color,EventId)
{
top.InputFrame.MessageReciveChat(Model,Time,From,To,Text,Color,EventId);
}
function MP(Model,Time,From,To,Text,Color,EventId)
{
top.InputFrame.MessageRecivePrivat(Model,Time,From,To,Text,Color,EventId);
}
function MPD(Model,Time,From,To,Text,Color,EventId)
{
top.InputFrame.MessageDrawPrivatDirect(Model,Time,From,To,Text,Color,EventId);
}
function MPText(Text)
{
top.InputFrame.MessageDrawPrivatText(Text);
}
function MCText(Text)
{
top.InputFrame.MessageDrawChatText(Text);
}
function UI(LastSendMessId,NewPreSID,ReqSendTime)
{
top.InputFrame.ChatFillUpdateDone(LastSendMessId);
top.InputFrame.ChatUpdatePreSID(NewPreSID);
top.InputFrame.ChatUpdateReqLag(ReqSendTime);
}
function US(NewPreSID,ReqSendTime)
{
top.InputFrame.ChatFillSendDone();
top.InputFrame.ChatUpdatePreSID(NewPreSID);
top.InputFrame.ChatUpdateReqLag(ReqSendTime);
}
function UF(LastSendMessId,NewPreSID,ReqSendTime)
{
top.InputFrame.ChatFillFullSendDone(LastSendMessId);
top.InputFrame.ChatUpdatePreSID(NewPreSID);
top.InputFrame.ChatUpdateReqLag(ReqSendTime);
}
function NITW(NickName,NickColor)
{
top.InputFrame.NI_ITW(NickName,NickColor);
}
function NIAW(NickName,NickColor)
{
top.InputFrame.NI_IAW(NickName,NickColor);
}
function NIAWNL(NickName,NickColor,Sex,HasInfoFlag,HasMyNoteFlag)
{
top.InputFrame.NI_IAWNL(NickName,NickColor,Sex,HasInfoFlag,HasMyNoteFlag);
}
function NIAWNH(NickName,NickColor,Sex,HasInfoFlag,HasMyNoteFlag)
{
top.InputFrame.NI_IAWHL(NickName,NickColor,Sex,HasInfoFlag,HasMyNoteFlag);
}
function NICTBB(TD)
{
top.InputFrame.NickInfoChatTopBannerBlock(TD);
}
