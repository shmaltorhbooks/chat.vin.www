top.Debug("c_mess.js loading");
var M_StdAMS          = 100;
var M_StdA            = new OS_C(M_StdAMS);
var M_PvtAMS        = 300;
var M_PvtA          = new OS_C(M_PvtAMS);
var M_ModM = 0;
var M_ModJ    = 1;
var M_ModL   = 2;
var M_ModU     = 3;
var M_MMDC = "green";
var M_MJDC    = "orange";
var M_MLDC   = "orange";
var M_MUDC     = "blue";
var M_CIMV      = 1;
var M_CIXV      = 14;
var M_CI1MV  = 20;
var M_CI1XV  = 39;
var M_CI2MV  = 50;
var M_CI2XV  = 79;
var M_CI3MV  = 80;
var M_CI3XV  = 89;
var M_CISPC   = "`";
var M_MICPM = 10;
var M_CSTML = 75;
var M_CSTST  = ' ';
var M_CSTSC = ' ';
var M_SUONCH = false;
var M_SUTH  = true;
var M_ANS           = "Admin";
var M_UCSL   = 10;
function FM_S2O(InputStr)
{
var OutStr;
OutStr = InputStr;
OutStr = top.Str2Out(OutStr);
return(OutStr);
}
var MessageMakeEventId = 777;
function Message(Model,Time,From,To,Text,Color,PrivatFlag)
{
this.Model     = Model;
this.Time      = Time;
this.From      = From;
this.To        = To;
this.Text      = Text;
this.Color     = Color;
this.HCHE = null;
this.EventId   = MessageMakeEventId;
MessageMakeEventId++;
}
function MessageArrayAppend(MA,Message)
{
MA.OSF_AI(Message);
}
function FM_IMLT(INM)
{
var RS;
RS = "<img src=\"/chat/images/b"+INM+".gif\" "
+" border=0  vspace=0>";
return(RS);
}
function FM_SPT(From,To)
{
this.From = From;
this.To   = To;
}
var FM_SPTA = new Array
(
new FM_SPT('C','&copy;'),
new FM_SPT('(C)','&copy;'),
new FM_SPT('R','&reg;'),
new FM_SPT('(R)','&reg;'),
new FM_SPT('TM','&trade;')
);
function FM_PILT(MiddleStr)
{
var    RS;
var    INM;
INM = parseInt(MiddleStr,10);
RS = "";
if      (isNaN(INM))
{
for (i = 0;i < FM_SPTA.length;i++)
{
if (MiddleStr.toUpperCase() == FM_SPTA[i].From.toUpperCase())
{
RS = FM_SPTA[i].To;
i = FM_SPTA.length;
}
}
}
else if ((INM >= M_CIMV) &&
(INM <= M_CIXV))
{
RS = FM_IMLT(INM);
}
else if ((INM >= M_CI1MV) &&
(INM <= M_CI1XV))
{
RS = FM_IMLT(INM);
}
else if ((INM >= M_CI2MV) &&
(INM <= M_CI2XV))
{
RS = FM_IMLT(INM);
}
else if ((INM >= M_CI3MV) &&
(INM <= M_CI3XV))
{
RS = FM_IMLT(INM);
}
else
{
}
return(RS);
}
function FM_PILO(RS,ImgCount)
{
this.RS = RS;
this.ImgCount  = ImgCount;
}
function FM_PILS(InputStr)
{
var FromPos;
var FoundPos;
var NextPos;
var RS;
var MidStr;
var ImgStr;
var ImgCount;
FromPos   = 0;
FoundPos  = InputStr.indexOf(M_CISPC);
RS = "";
ImgCount  = 0;
if (FoundPos < 0)
{
RS = InputStr;
}
else
{
while(FoundPos >= 0)
{
RS += InputStr.substring(FromPos,FoundPos);
NextPos    = InputStr.indexOf
(M_CISPC,
FoundPos+M_CISPC.length);
if (NextPos < 0)
{
FromPos  = FoundPos;
FoundPos = -1;
}
else
{
MidStr = InputStr.substring
(FoundPos+M_CISPC.length,NextPos);
if (MidStr == "")
{
RS += M_CISPC;
FoundPos = NextPos+M_CISPC.length;
FromPos  = FoundPos;
}
else
{
ImgStr = FM_PILT(MidStr);
if (ImgStr == "")
{
RS += InputStr.substring(FoundPos,NextPos);
FoundPos = NextPos;
FromPos  = FoundPos;
}
else if (ImgCount < M_MICPM)
{
RS += ImgStr;
FoundPos = NextPos+M_CISPC.length;
FromPos  = FoundPos;
ImgCount++;
}
else
{
RS += InputStr.substring(FoundPos,NextPos);
FoundPos = NextPos;
FromPos  = FoundPos;
ImgCount++;
}
}
}
if (FoundPos >= 0)
{
FoundPos = InputStr.indexOf(M_CISPC,FoundPos);
}
}
RS += InputStr.substring(FromPos,InputStr.length);
}
var RRR = new FM_PILO(RS,ImgCount);
return(RRR);
}
function FM_PIL(InputStr)
{
return(FM_PILS(InputStr).RS);
}
function FM_CIL(InputStr)
{
return(FM_PILS(InputStr).ImgCount);
}
function FM_SST(InputStr)
{
var RS;
var IDX;
var CurrChar;
var SolidLength;
RS   = "";
SolidLength = 0;
for (IDX = 0;IDX < InputStr.length;IDX++)
{
CurrChar = InputStr.charAt(IDX);
if ((M_CSTST.indexOf(CurrChar) >= 0) ||
(CurrChar == M_CISPC))
{
SolidLength = 0;
}
else
{
if (SolidLength >= M_CSTML)
{
RS += M_CSTSC;
SolidLength = 0;
}
else
{
SolidLength++;
}
}
RS += CurrChar;
}
return(RS);
}
function FM_DINS_MT()
{
var RRR;
RRR = "";
return(RRR);
}
function FM_DINS(TD)
{
TD.write(FM_DINS_MT());
}
function FM_DINE_MT()
{
var RRR;
RRR = "";
RRR += ('<br>');
RRR += ('\n');
return(RRR);
}
function FM_DINE(TD)
{
TD.write(FM_DINE_MT());
}
function FM_NAS_MT(NickName,NS2P)
{
var RRR;
RRR = "";
RRR += ('<a href=\"');
if (NS2P)
{
RRR += ('javascript:top.InputFrame.FSelNickPvt(\'');
}
else
{
RRR += ('javascript:top.InputFrame.FSelNick(\'');
}
RRR += (top.Str2Sel(NickName));
RRR += ('\');\"');
RRR += (' ');
if (M_SUONCH)
{
RRR += ('OnClick=\'');
RRR += ('window.event.returnValue = false;');
if (NS2P)
{
RRR += ('top.InputFrame.FClickNickPvt("');
}
else
{
RRR += ('top.InputFrame.FClickNick("');
}
RRR += (top.Str2Sel(NickName));
RRR += ('"');
RRR += (');');
RRR += ('window.event.returnValue = false;');
RRR += ('\'');
RRR += (' ');
}
if (M_SUTH)
{
RRR += ('Target=\'');
RRR += ('DebugMessFrame');
RRR += ('\'');
RRR += (' ');
}
RRR += ('>');
return(RRR);
}
function FM_NAS(TD)
{
TD.write(FM_NAS_MT());
}
function FM_NAE_MT()
{
var RRR;
RRR = "";
RRR += ('</a>');
return(RRR);
}
function FM_NAE(TD)
{
TD.write(FM_NAE_MT());
}
function FM_MHT_MT(Mess,NS2P)
{
var MC;
var FromMe;
var ToMe;
var MessDrawText;
var RRR;
RRR = "";
MC = Mess.Color;
FromMe    = false;
ToMe      = false;
if (top.SetUpInfo.MyNick.toUpperCase() == Mess.To.toUpperCase())
{
ToMe    = true;
}
if (top.SetUpInfo.MyNick.toUpperCase() == Mess.From.toUpperCase())
{
FromMe  = true;
}
if (Mess.Time == "")
{
FromMe  = true;
}
if (Mess.Model == M_ModM)
{
RRR += FM_DINS_MT();
if (MC == "")
{
MC = M_MMDC;
}
MC = ChatForeColorCheck(MC);
RRR += ('<font color=\'');
RRR += (top.Str2Attr(MC));
RRR += ('\'>');
if (ToMe)
{
RRR += ('');
}
else
{
RRR += ('');
}
if (ToMe)
{
RRR += ('<u>');
}
RRR += (top.Str2Out(Mess.Time));
if (ToMe)
{
RRR += ('</u>');
}
if (Mess.Time == "")
{
}
else
{
RRR += ('&nbsp;');
}
if (!FromMe)
{
RRR += FM_NAS_MT(Mess.From,NS2P);
}
else
{
RRR += ('<b><u>');
}
RRR += ('<font color=\'');
RRR += (top.Str2Attr(MC));
RRR += ('\'>');
RRR += (top.Str2Out(Mess.From));
RRR += ('</font>');
if (!FromMe)
{
RRR += FM_NAE_MT();
}
else
{
RRR += ('</u></b>');
}
if (Mess.To != "")
{
RRR += ('-&gt;');
if (!ToMe)
{
RRR += FM_NAS_MT(Mess.To,NS2P);
}
else
{
RRR += ('<b><u>');
}
RRR += ('<font color=\'');
RRR += (top.Str2Attr(MC));
RRR += ('\'>');
RRR += (top.Str2Out(Mess.To));
RRR += ('</font>');
if (!ToMe)
{
RRR += FM_NAE_MT();
}
else
{
RRR += ('</u></b>');
}
}
RRR += ('<font color=\'');
RRR += (top.Str2Attr(MC));
RRR += ('\'>');
RRR += (':');
RRR += ('&nbsp;');
RRR += ('&nbsp;');
if (M_ANS.toUpperCase() == Mess.From.toUpperCase())
{
RRR += (FM_PIL(Mess.Text));
}
else
{
MessDrawText = Mess.Text;
if ((Mess.Text.length >= M_UCSL) &&
(Mess.Text.toUpperCase() == Mess.Text))
{
MessDrawText  = "";
MessDrawText += Mess.Text.substring(0,1).toUpperCase();
MessDrawText += Mess.Text.substring(1,Mess.Text.length).toLowerCase();
}
MessDrawText = FM_SST(MessDrawText);
RRR += (FM_PIL(FM_S2O(MessDrawText)));
}
RRR += ('</font>');
RRR += ('</font>');
RRR += FM_DINE_MT();
}
else if ((Mess.Model == M_ModJ) ||
(Mess.Model == M_ModL))
{
RRR += FM_DINS_MT();
if (MC == "")
{
if      (Mess.Model == M_ModJ)
{
MC = M_MJDC;
}
else if (Mess.Model == M_ModL)
{
MC = M_MLDC;
}
else
{
MC = M_MMDC;
}
}
MC = ChatForeColorCheck(MC);
RRR += ('<font color=\'');
RRR += (top.Str2Attr(MC));
RRR += ('\'>');
RRR += ('');
RRR += (top.Str2Out(Mess.Time));
RRR += ('&nbsp;');
if (Mess.From != "")
{
RRR += ('<font color=\'');
RRR += (top.Str2Attr(MC));
RRR += ('\'>');
RRR += (FM_PIL(FM_S2O(Mess.From)));
RRR += ('&nbsp;');
RRR += ('</font>');
}
if (Mess.To != "")
{
RRR += ('&nbsp;');
if (!ToMe)
{
RRR += FM_NAS_MT(Mess.To,false);
}
else
{
RRR += ('<b><u>');
}
RRR += ('<font color=\'');
RRR += (top.Str2Attr(MC));
RRR += ('\'>');
RRR += (top.Str2Out(Mess.To));
RRR += ('</font>');
if (!ToMe)
{
RRR += ('</a>');
}
else
{
RRR += ('</u></b>');
}
}
if (Mess.Text != "")
{
MessDrawText = Mess.Text;
RRR += ('<font color=\'');
RRR += (top.Str2Attr(MC));
RRR += ('\'>');
RRR += ('&nbsp;');
RRR += (FM_PIL(FM_S2O(MessDrawText)));
RRR += ('</font>');
}
RRR += ('</font>');
RRR += FM_DINE_MT();
}
else if (Mess.Model == M_ModU)
{
RRR += FM_DINS_MT();
if (MC == "")
{
MC = M_MUDC;
}
MC = ChatForeColorCheck(MC);
RRR += ('<font color=\'');
RRR += (top.Str2Attr(MC));
RRR += ('\'>');
RRR += ('');
RRR += (top.Str2Out(Mess.Time));
RRR += ('&nbsp;');
if (Mess.From != "")
{
RRR += ('<font color=\'');
RRR += (top.Str2Attr(MC));
RRR += ('\'>');
RRR += (FM_PIL(FM_S2O(Mess.From)));
RRR += ('&nbsp;');
RRR += ('</font>');
}
if (Mess.To != "")
{
RRR += ('&nbsp;');
RRR += ('<a href="http://');
RRR += (Mess.To);
RRR += ('">');
RRR += ('<font color=\'');
RRR += (top.Str2Attr(MC));
RRR += ('\'>');
RRR += (top.Str2Out(Mess.To));
RRR += ('</font>');
RRR += ('</a>');
}
if (Mess.Text != "")
{
RRR += ('<font color=\'');
RRR += (top.Str2Attr(MC));
RRR += ('\'>');
RRR += ('&nbsp;');
RRR += (FM_PIL(FM_S2O(Mess.Text)));
RRR += ('</font>');
}
RRR += ('</font>');
RRR += FM_DINE_MT();
}
else
{
}
return(RRR);
}
function MessageDrawInDoc(TD,Mess,NS2P)
{
if (Mess.HCHE == null)
{
Mess.HCHE = FM_MHT_MT(Mess,NS2P);
}
TD.write(Mess.HCHE);
}
function MessageNeedCarbonCopy2Pvt(Mess)
{
if (top.SetUpClientInfo.CarbonCopy2Pvt)
{
if (Mess.Model == M_ModM)
{
if ((Mess.To.toUpperCase()   == top.SetUpInfo.MyNick.toUpperCase()) ||
(Mess.From.toUpperCase() == top.SetUpInfo.MyNick.toUpperCase()))
{
return(true);
}
}
}
return(false);
}
function MessageDrawInDocCarbonCopy2Pvt(TD,Mess,NS2P)
{
if (Mess.HCHE == null)
{
Mess.HCHE = FM_MHT_MT(Mess,NS2P);
}
TD.write("<i>"+Mess.HCHE+"</i>");
}
function MessagePassedFilter(Mess,InPrivatFlag)
{
var FromNickData;
var ToNickData;
var Mess;
var SkipMess;
var MuteFrom;
var MuteTo;
SkipMess = false;
FromNickData = null;
ToNickData   = null;
if (Mess == null)
{
return true;
}
if      (Mess.Model == M_ModM)
{
if (Mess.From != "")
{
FromNickData = NickSearchByName(Mess.From);
}
else
{
FromNickData = null;
}
if (Mess.To != "")
{
ToNickData = NickSearchByName(Mess.To);
}
else
{
ToNickData = null;
}
}
else if (Mess.Model == M_ModJ)
{
if (Mess.To != "")
{
ToNickData = NickSearchByName(Mess.To);
}
else
{
ToNickData = null;
}
FromNickData = ToNickData;
}
else if (Mess.Model == M_ModL)
{
if (Mess.To != "")
{
ToNickData = NickSearchByName(Mess.To);
}
else
{
ToNickData = null;
}
FromNickData = ToNickData;
}
else
{
}
if (ToNickData != null)
{
if (InPrivatFlag)
{
MuteTo = ToNickData.MuteToMe;
}
else
{
MuteTo = ToNickData.MuteToMe;
}
if (MuteTo)
{
SkipMess = true;
}
}
if (FromNickData != null)
{
if (InPrivatFlag)
{
MuteFrom = FromNickData.MPvtFromMe;
}
else
{
MuteFrom = FromNickData.MuteFromMe;
}
if (MuteFrom)
{
SkipMess = true;
}
}
if (!SkipMess)
{
return(true);
}
else
{
return(false);
}
}
function MessageArrayDrawInDoc(TD,MA,NS2P)
{
var IDX;
var Mess;
IDX = 0;
while(IDX < MA.Size())
{
Mess = MA.OSF_GI(IDX);
if (MessagePassedFilter(Mess,NS2P))
{
MessageDrawInDoc(TD,Mess,NS2P);
}
IDX++;
}
}
function MessageChatReDrawDocSetColor(TD,BodyId)
{
TD.write('<html>');
TD.write('<body');
TD.write(' BGCOLOR=\"'+top.SetUpFrame.document.bgColor+'\"');
TD.write(' TEXT=\"'+top.SetUpFrame.document.fgColor+'\"');
if (BodyId != null)
{
TD.write(' ID=\"'+BodyId+'\"');
}
TD.write('>');
TD.writeln('');
}
function MessageChatDrawHeaderSign()
{
var TargetFrame;
var TD;
TargetFrame = top.ChatFrame;
TD   = TargetFrame.document;
}
function MessageChatDrawInit()
{
var TargetFrame;
var TD;
TargetFrame = top.ChatFrame;
TD   = TargetFrame.document;
TD.close();
TD.open("text/html");
MessageChatReDrawDocSetColor(TD,'ChatFrameBody');
}
function MessagePrivatDrawHeaderSign()
{
var TargetFrame;
var TD;
TargetFrame = top.PrivateFrame;
TD   = TargetFrame.document;
TD.write  ("<div id='PrivateTitleSign'>");
TD.write  (TextPrivateTitlePrefixStr);
TD.write  ("<b><u>");
TD.write  (top.Str2Out(top.SetUpInfo.MyNick));
TD.write  ("</u></b>");
TD.write  (TextPrivateTitlePostfixStr);
TD.writeln("</div>");
}
function MessagePrivatDrawInit()
{
var TargetFrame;
var TD;
TargetFrame = top.PrivateFrame;
TD   = TargetFrame.document;
TD.close();
TD.open("text/html");
MessageChatReDrawDocSetColor(TD,'PrivateFrameBody');
}
function MessageFullReDrawAction(ReDrawChat,ReDrawPrivat)
{
var ChatIndex;
var ChatMess;
var ChatTargetFrame;
var ChatTargetDoc;
var PrivatIndex;
var PrivatMess;
var PrivatTargetFrame;
var PrivatTargetDoc;
var Mess;
var NS2P;
var TD;
if (ReDrawPrivat)
{
MessagePrivatDrawInit();
MessagePrivatDrawHeaderSign();
}
if (ReDrawChat)
{
MessageChatDrawInit();
MessageChatDrawHeaderSign();
}
PrivatTargetFrame = top.PrivateFrame;
PrivatTargetDoc   = PrivatTargetFrame.document;
ChatTargetFrame   = top.ChatFrame;
ChatTargetDoc     = ChatTargetFrame.document;
ChatIndex = 0;
PrivatIndex = 0;
while((PrivatIndex < M_PvtA.Size()) ||
(ChatIndex < M_StdA.Size()))
{
ChatMess = null;
PrivatMess = null;
if (ChatIndex < M_StdA.Size())
{
ChatMess = M_StdA.OSF_GI(ChatIndex);
}
if (PrivatIndex < M_PvtA.Size())
{
PrivatMess = M_PvtA.OSF_GI(PrivatIndex);
}
if ((ChatMess != null) && (PrivatMess != null))
{
if (ChatMess.EventId < PrivatMess.EventId)
{
PrivatMess = null;
}
else
{
ChatMess = null;
}
}
if (PrivatMess != null)
{
Mess = PrivatMess;
NS2P = true;
TD = PrivatTargetDoc;
if (ReDrawPrivat)
{
if (MessagePassedFilter(Mess,NS2P))
{
MessageDrawInDoc(TD,Mess,NS2P);
}
}
PrivatIndex++;
}
if (ChatMess != null)
{
Mess = ChatMess;
NS2P = false;
TD = ChatTargetDoc;
if (MessagePassedFilter(Mess,NS2P))
{
if (ReDrawChat)
{
MessageDrawInDoc(TD,Mess,NS2P);
}
if (ReDrawPrivat)
{
if (MessageNeedCarbonCopy2Pvt(Mess))
{
TD = PrivatTargetDoc;
MessageDrawInDocCarbonCopy2Pvt(TD,Mess,NS2P);
}
}
}
ChatIndex++;
}
}
if (ReDrawChat)
{
top.ChatMoveChatScroll();
}
if (ReDrawPrivat)
{
top.ChatMovePrivatScroll();
}
}
function FM_CR()
{
MessageFullReDrawAction(true,false);
}
function FM_PR()
{
MessageFullReDrawAction(false,true);
}
function MessageFullReDraw()
{
MessageFullReDrawAction(true,true);
}
top.Debug("c_mess.js loaded");
