top.Debug("c_nick.js loading");
var N_A               = new Array;
var N_SM         = "M";
var N_SU    = "U";
var N_SF       = "F";
var N_STO        = 0;
var N_STP      = 1;
var N_STC  = 2;
var N_STF   = 9;
var N_PMN     = 1;
var N_PMW      = 2;
var N_PMC  = 3;
var N_PMI  = 4;
var N_PMS    = 5;
var N_PMYS   = 6;
var N_PMYF   = 7;
var N_PMYC  = 8;
var N_PMR     = 9;
var N_PM          = N_PMN;
var N_PNFIN  = "";
function Nick(Name,Color,Sex,TimeLogIn,Text,HasInfoFlag,HasMyNoteFlag)
{
this.Name          = Name;
this.Color         = Color;
this.Sex           = Sex;
this.Status        = N_STO;
this.TimeLogIn     = TimeLogIn;
this.Text          = Text;
this.MuteFromMe    = false;
this.MPvtFromMe    = false;
this.MuteToMe      = false;
this.HasInfoFlag   = HasInfoFlag;
this.HasMyNoteFlag = HasMyNoteFlag;
this.HCHE     = null;
}
function F_NUINF(Nick,Name,Color,Sex,TimeLogIn,Text,HasInfoFlag,HasMyNoteFlag)
{
Nick.Name       = Name;
Nick.Color      = Color;
Nick.Sex        = Sex;
Nick.Status     = N_STO;
Nick.TimeLogIn  = TimeLogIn;
Nick.Text       = Text;
this.HasInfoFlag   = HasInfoFlag;
this.HasMyNoteFlag = HasMyNoteFlag;
this.HCHE     = null;
}
function F_NMHT_MT(Nick)
{
var RRR;
var SexIcoImg;
var NickColor;
var CheckStyle;
var TD;
TD = top.NickFrame.document;
CheckStyle = ''
+'margin-top:-3px;margin-bottom:-3px;'
+'margin-left:0px;margin-right:0px;'
+'padding-top:0px;padding-bottom:0px;'
+'padding-left:0px;padding-right:0px;'
+'border-top-width:0px;border-bottom-width:0px;'
+'border-left-width:0px;border-right-width:0px;'
+'';
RRR = "";
RRR += ('<tr>');
RRR += ('<td align=center>');
if (Nick.Name.toUpperCase() == top.SetUpInfo.MyNick.toUpperCase())
{
RRR += ('&nbsp;');
}
else
{
RRR += ('<input type="checkbox" ');
RRR += (' style="');
RRR += (CheckStyle);
RRR += ('" ');
RRR += (' ');
RRR += ('OnClick=\'top.InputFrame.FMuteFrom("');
RRR += (top.Str2Sel(Nick.Name));
RRR += ('"');
RRR += (',');
RRR += ('this.checked');
RRR += (');');
RRR += ('\'');
RRR += (' ');
RRR += ('Title=\''+TextNickMuteFromTitlePrefixStr);
RRR += (top.Str2Out(Nick.Name));
RRR += (TextNickMuteFromTitlePostfixStr);
RRR += ('\'');
if (Nick.MuteFromMe)
{
RRR += (' ');
RRR += ('CHECKED');
}
RRR += ('>');
}
RRR += ('</td>');
RRR += ('<td align=center>');
if (Nick.Name.toUpperCase() == top.SetUpInfo.MyNick.toUpperCase())
{
RRR += ('&nbsp;');
}
else
{
RRR += ('<input type="checkbox" ');
RRR += (' style="');
RRR += (CheckStyle);
RRR += ('" ');
RRR += (' ');
RRR += ('OnClick=\'top.InputFrame.FMPvtFrom("');
RRR += (top.Str2Sel(Nick.Name));
RRR += ('"');
RRR += (',');
RRR += ('this.checked');
RRR += (');');
RRR += ('\'');
RRR += (' ');
RRR += ('Title=\''+TextNickMPvtFromTitlePrefixStr);
RRR += (top.Str2Out(Nick.Name));
RRR += (TextNickMPvtFromTitlePostfixStr);
RRR += ('\'');
if (Nick.MPvtFromMe)
{
RRR += (' ');
RRR += ('CHECKED');
}
RRR += ('>');
}
RRR += ('</td>');
RRR += ('<td align=center>');
if (Nick.Name.toUpperCase() == top.SetUpInfo.MyNick.toUpperCase())
{
RRR += ('&nbsp;');
}
else
{
RRR += ('<input type="checkbox" ');
RRR += (' style="');
RRR += (CheckStyle);
RRR += ('" ');
RRR += (' ');
RRR += ('OnClick=\'top.InputFrame.FMuteTo("');
RRR += (top.Str2Sel(Nick.Name));
RRR += ('"');
RRR += (',');
RRR += ('this.checked');
RRR += (');');
RRR += ('\'');
RRR += (' ');
RRR += ('Title=\''+TextNickMuteToTitlePrefixStr);
RRR += (top.Str2Out(Nick.Name));
RRR += (TextNickMuteToTitlePostfixStr);
RRR += ('\'');
if (Nick.MuteToMe)
{
RRR += (' ');
RRR += ('CHECKED');
}
RRR += ('>');
}
RRR += ('</td>');
RRR += ('<td>');
SexIcoImg = "/chat/images/nick";
if      (Nick.Sex == N_SM)
{
SexIcoImg += "m";
}
else if (Nick.Sex == N_SF)
{
SexIcoImg += "f";
}
else
{
SexIcoImg += "u";
}
RRR += ('<img width=11 height=12');
RRR += (' hspace=0 vspace=0 border=0');
RRR += (' src=\"'+SexIcoImg+'.gif\">');
RRR += ('</td>');
RRR += ('<td align=left nowrap>');
NickColor = Nick.Color;
NickColor = ChatForeColorCheck(NickColor);
if (Nick.Name.toUpperCase() == top.SetUpInfo.MyNick.toUpperCase())
{
RRR += ('<font color=\'');
RRR += (top.Str2Attr(NickColor));
RRR += ('\'');
RRR += ('>');
RRR += ('<b><u>');
RRR += (top.Str2Out(Nick.Name));
RRR += ('</u></b>');
RRR += ('</font>');
}
else
{
RRR += ('<a href=\'javascript:top.InputFrame.FSelNick(');
RRR += ('"');
RRR += (top.Str2Sel(Nick.Name));
RRR += ('"');
RRR += (');');
RRR += ('\'');
RRR += (' ');
RRR += ('title=\''+TextNickItemTitlePrefixStr);
RRR += (top.Str2Out(Nick.Name));
RRR += (TextNickItemTitlePostfixStr);
RRR += ('\'');
RRR += ('>');
RRR += ('<font color=\'');
RRR += (top.Str2Attr(NickColor));
RRR += ('\'');
RRR += ('>');
RRR += (top.Str2Out(Nick.Name));
RRR += ('</font>');
if (Nick.HasInfoFlag)
{
IcoImg = "/chat/images/nickinfo";
RRR += ('<a href=\'javascript:top.InputFrame.F_NPSMF(top.InputFrame.N_PMI,');
RRR += ('"');
RRR += (top.Str2Sel(Nick.Name));
RRR += ('"');
RRR += (');');
RRR += ('\'');
RRR += (' ');
RRR += ('title=\''+TextNickInfoTitlePrefixStr);
RRR += (top.Str2Out(Nick.Name));
RRR += (TextNickInfoTitlePostfixStr);
RRR += ('\'');
RRR += ('>');
RRR += ('<img width=13 height=13');
RRR += (' hspace=0 vspace=0 border=0');
RRR += (' src=\"'+IcoImg+'.gif\">');
RRR += ('</a>');
}
if (Nick.HasMyNoteFlag)
{
IcoImg = "/chat/images/nicknote";
RRR += ('<a href=\'javascript:top.InputFrame.F_NPSMF(top.InputFrame.N_PMI,');
RRR += ('"');
RRR += (top.Str2Sel(Nick.Name));
RRR += ('"');
RRR += (');');
RRR += ('\'');
RRR += (' ');
RRR += ('title=\''+TextNickNoteTitlePrefixStr);
RRR += (top.Str2Out(Nick.Name));
RRR += (TextNickNoteTitlePostfixStr);
RRR += ('\'');
RRR += ('>');
RRR += ('<img width=13 height=13');
RRR += (' hspace=0 vspace=0 border=0');
RRR += (' src=\"'+IcoImg+'.gif\">');
RRR += ('</a>');
}
RRR += ('</a>');
}
RRR += ('</td>');
RRR += ('</tr>');
if      (Nick.Text == "")
{
}
else if (Nick.MuteFromMe || Nick.MPvtFromMe)
{
}
else
{
RRR += ('<tr>');
RRR += ('<td colspan=5 align=left nowrap>');
RRR += ('<font color=\'');
RRR += (top.Str2Attr(NickColor));
RRR += ('\'');
RRR += (' size=\'-1\'');
RRR += ('>');
RRR += (top.Str2Out(Nick.Text));
RRR += ('</font>');
RRR += ('</td>');
RRR += ('</tr>');
}
RRR += ('\n <!-- *** --> \n');
return(RRR);
}
function F_NMUHT(Nick)
{
Nick.HCHE = F_NMHT_MT(Nick);
}
function NickSearchByName(NickName)
{
var NickIndex;
NickIndex = NickName.toUpperCase();
if (N_A[NickIndex] == null)
{
return(null);
}
else
{
if (N_A[NickIndex].Name.toUpperCase() != NickIndex)
{
alert
( ""
+TextNickHashErrorAlertPrefixStr
+"'"+NickIndex+"'"
+" != "
+"'"+N_A[NickIndex].Name.toUpperCase()+"'");
return(null);
}
return(N_A[NickIndex]);
}
}
function NickAppend(Nick)
{
var NickIndex;
NickIndex = Nick.Name.toUpperCase();
N_A[NickIndex] = Nick;
F_NMUHT(N_A[NickIndex]);
}
function NickDeleteByName(NickName)
{
var NickIndex;
NickIndex = NickName.toUpperCase();
N_A[NickIndex] = null;
}
function NickNotifyUpdate(Nick)
{
F_NMUHT(Nick);
}
function NickNotifyUpdateByName(NickName)
{
var Nick;
Nick = NickSearchByName(NickName);
if (Nick != null)
{
NickNotifyUpdate(Nick);
}
}
function NickWindowDrawHead(V_TAN)
{
var TD;
TD = top.NickFrame.document;
TD.write('<html>');
TD.write('<head>');
TD.write('</head>');
TD.write('<body');
TD.write(' BGCOLOR=\"'+top.SetUpFrame.document.bgColor+'\"');
TD.write(' TEXT=\"'+top.SetUpFrame.document.fgColor+'\"');
TD.write(' id=\"NickFrameBody\"');
TD.write('>');
TD.writeln('');
top.InputFrame.NickInfoChatTopBannerBlock(TD);
TD.writeln('');
TD.write(TextNickPanelHeaderPrefixStr);
TD.write(V_TAN);
TD.write(TextNickPanelHeaderPostfixStr);
TD.writeln('');
TD.write('<form name=\"NickForm\">');
TD.write('<table cellspacing=0 cellpadding=0 border=0 size=\"100%\">');
TD.writeln('');
TD.write('<tr>');
TD.write('<td align=center width=\"1%\" nowrap>'+TextNickPanelMuteFromHeadStr+'</td>');
TD.write('<td align=center width=\"1%\" nowrap>'+TextNickPanelMPvtFromHeadStr+'</td>');
TD.write('<td align=center width=\"1%\" nowrap>'+TextNickPanelMuteToHeadStr  +'</td>');
TD.write('<td align=center width=\"1%\" nowrap>'+TextNickPanelGenderHeadStr  +'</td>');
TD.write('<td align=left                nowrap>'+TextNickPanelNickNameHeadStr+'</td>');
TD.write('</tr>');
TD.writeln('');
}
function NickWindowDrawNick(Nick,IDX)
{
var TD;
TD = top.NickFrame.document;
if (Nick.HCHE == null)
{
F_NMUHT(Nick);
}
TD.write(Nick.HCHE);
}
function NickWindowDrawPreFootMyInfo()
{
var TD;
TD = top.NickFrame.document;
TD.write('<br>');
TD.writeln('ReqLag:' +top.SetUpInfo.ReqLagTime+"s"         + '<br>');
}
function NickWindowDrawFoot(V_TMN,V_TFN,V_TUN)
{
var TD;
TD = top.NickFrame.document;
TD.write('</table>');
TD.write('<br>');
TD.write('<table>');
TD.write('<tr>');
TD.write('<td>');
SexIcoImg = "m";
TD.write('<img width=11 height=12 src=\"/chat/images/nick'+SexIcoImg+'.gif\">');
TD.write('</td>');
TD.write('<td>');
TD.write(TextNickTotalGMalesStr);
TD.write('</td>');
TD.write('<td>');
TD.write(V_TMN);
TD.write('</td>');
TD.write('</tr>');
TD.write('<tr>');
TD.write('<td>');
SexIcoImg = "f";
TD.write('<img width=11 height=12 src=\"/chat/images/nick'+SexIcoImg+'.gif\">');
TD.write('</td>');
TD.write('<td>');
TD.write(TextNickTotalGFemalesStr);
TD.write('</td>');
TD.write('<td>');
TD.write(V_TFN);
TD.write('</td>');
TD.write('</tr>');
TD.write('<tr>');
TD.write('<td>');
SexIcoImg = "u";
TD.write('<img width=11 height=12 src=\"/chat/images/nick'+SexIcoImg+'.gif\">');
TD.write('</td>');
TD.write('<td>');
TD.write(TextNickTotalGUndefinedStr);
TD.write('</td>');
TD.write('<td>');
TD.write(V_TUN);
TD.write('</td>');
TD.write('</tr>');
TD.write('<tr>');
TD.write('<td>');
TD.write('');
TD.write('</td>');
TD.write('<td>');
TD.write(TextNickTotalAllStr);
TD.write('</td>');
TD.write('<td>');
TD.write(V_TMN+V_TFN+V_TUN);
TD.write('</td>');
TD.write('</tr>');
TD.write('</table>');
TD.write('</form>');
NickWindowDrawPreFootMyInfo();
TD.write('</body>');
TD.write('</html>');
TD.writeln('');
}
function NickWindowDrawFullInfo()
{
var IDX;
var TD;
var V_TAN;
TD = top.NickFrame.document;
TD.open("text/html");
V_TAN = 0;
V_TMN      = 0;
V_TFN    = 0;
V_TUN = 0;
for (IDX in N_A)
{
if (N_A[IDX] == null)
{
continue;
}
if (N_A[IDX].Status >= N_STF)
{
}
else
{
V_TAN++;
if      (N_A[IDX].Sex == N_SM)
{
V_TMN++;
}
else if (N_A[IDX].Sex == N_SF)
{
V_TFN++;
}
else
{
V_TUN++;
}
}
}
NickWindowDrawHead(V_TAN);
for (IDX in N_A)
{
if (N_A[IDX] == null)
{
continue;
}
if (N_A[IDX].Status >= N_STF)
{
}
else
{
NickWindowDrawNick(N_A[IDX],IDX);
}
}
NickWindowDrawFoot
(V_TMN,
V_TFN,
V_TUN);
TD.writeln("</body>");
TD.close();
}
function NickWindowDrawFull()
{
if (N_PM == N_PMN)
{
NickWindowDrawFullInfo();
}
}
function NickWindowDrawSmiles()
{
var i;
var TD;
var LineLength;
var IDX;
var FIDX;
var TIDX;
TD = top.NickFrame.document;
TD.open("text/html");
TD.write('<html>');
TD.write('<head>');
TD.write('</head>');
TD.write('<body');
TD.write(' BGCOLOR=\"'+top.SetUpFrame.document.bgColor+'\"');
TD.write(' TEXT=\"'+top.SetUpFrame.document.fgColor+'\"');
TD.write(' id=\"NickFrameBody\"');
TD.write('>');
TD.writeln('');
top.InputFrame.NickInfoChatTopBannerBlock(TD);
TD.writeln('');
TD.write(TextSmilesPanelHeaderStr);
TD.writeln('');
LineLength = 5;
TD.write(TextSmilesPanelMainSubHeaderStr);
IDX     = 0;
FIDX = M_CIMV;
TIDX   = M_CIXV;
for (i = FIDX;i <= TIDX;i++)
{
TD.write("<a href=\"javascript:top.InputFrame.FInsertImg("+i+")\" ");
TD.write("title='"+TextSmileItemTitleStr+"'");
TD.write(">");
TD.write(FM_IMLT(i));
TD.write("</a>");
IDX++;
if (((IDX % LineLength) == 0) && ((i+1) <= TIDX))
{
TD.write('<br>');
TD.writeln('');
}
}
TD.write('<br>');
TD.writeln('');
TD.write(TextSmilesPanelAdd1SubHeaderStr);
IDX = 0;
FIDX = M_CI1MV;
TIDX   = M_CI1XV;
for (i = FIDX;i <= TIDX;i++)
{
TD.write("<a href=\"javascript:top.InputFrame.FInsertImg("+i+")\" ");
TD.write("title='"+TextSmileItemTitleStr+"'");
TD.write(">");
TD.write(FM_IMLT(i));
TD.write("</a>");
IDX++;
if (((IDX % LineLength) == 0) && ((i+1) <= TIDX))
{
TD.write('<br>');
TD.writeln('');
}
}
TD.write('<br>');
TD.writeln('');
TD.write(TextSmilesPanelAdd2SubHeaderStr);
IDX = 0;
FIDX = M_CI2MV;
TIDX   = M_CI2XV;
for (i = FIDX;i <= TIDX;i++)
{
TD.write("<a href=\"javascript:top.InputFrame.FInsertImg("+i+")\" ");
TD.write("title='"+TextSmileItemTitleStr+"'");
TD.write(">");
TD.write(FM_IMLT(i));
TD.write("</a>");
IDX++;
if (((IDX % LineLength) == 0) && ((i+1) <= TIDX))
{
TD.write('<br>');
TD.writeln('');
}
}
TD.write('<br>');
TD.writeln('');
TD.write(TextSmilesPanelAdd3SubHeaderStr);
IDX = 0;
FIDX = M_CI3MV;
TIDX   = M_CI3XV;
for (i = FIDX;i <= TIDX;i++)
{
TD.write("<a href=\"javascript:top.InputFrame.FInsertImg("+i+")\" ");
TD.write("title='"+TextSmileItemTitleStr+"'");
TD.write(">");
TD.write(FM_IMLT(i));
TD.write("</a>");
IDX++;
if (((IDX % LineLength) == 0) && ((i+1) <= TIDX))
{
TD.write('<br>');
TD.writeln('');
}
}
TD.write('<br>');
TD.writeln('');
TD.write('</body>');
TD.write('</html>');
TD.writeln('');
TD.close();
}
function F_WDCOP(ColorName,BackColor,ForeColor,TopHiColor,TopLoColor)
{
TD = top.NickFrame.document;
if (ForeColor == "")
{
ForeColor = "black";
}
if (TopHiColor == "")
{
TopHiColor = ForeColor;
}
TD.write('<OPTION VALUE="'+BackColor+'/'+ForeColor+'/'+TopHiColor+'/'+TopLoColor+'"');
TD.write(' STYLE="background-color: '+BackColor+'; color: '+ForeColor+'"');;
TD.write('>'+ColorName+'');
TD.writeln();
}
function NickWindowDrawMySetUp()
{
var TD;
var ColorShemeIndex;
var CurrColorSheme;
TD = top.NickFrame.document;
TD.open("text/html");
TD.write('<html>');
TD.write('<head>');
TD.write('</head>');
TD.write('<body');
TD.write(' BGCOLOR=\"'+top.SetUpFrame.document.bgColor+'\"');
TD.write(' TEXT=\"'+top.SetUpFrame.document.fgColor+'\"');
TD.write(' id=\"NickFrameBody\"');
TD.write('>');
TD.writeln('');
top.InputFrame.NickInfoChatTopBannerBlock(TD);
TD.writeln('');
TD.write(TextSetupPanelHeaderStr);
TD.writeln('');
TD.write('<form name="ChatLocalSetsForm" style="margin-top:1px">');
TD.writeln('');
TD.write('<font size="-1">');
TD.writeln('');
TD.write(TextSetupPanelBackColorSignStr);
TD.write('<SELECT name="ColorSheme"');
TD.write('        onChange="top.ChatSetUpClientSetColorSheme(this.value)"');
TD.write('>');
for (ColorShemeIndex = 0;ColorShemeIndex < ChatBackColorItemsArray.length;ColorShemeIndex++)
{
F_WDCOP
(
ChatBackColorItemsArray[ColorShemeIndex].ColorName,
ChatBackColorItemsArray[ColorShemeIndex].BackColor,
ChatBackColorItemsArray[ColorShemeIndex].ForeColor,
ChatBackColorItemsArray[ColorShemeIndex].TopHiColor,
ChatBackColorItemsArray[ColorShemeIndex].TopLoColor
);
}
TD.write('</SELECT>');
TD.writeln('');
TD.write('<br>');
TD.writeln('');
TD.write('<input type="checkbox" name="SoundOnPrivate" ');
TD.write('       onClick="top.SetUpClientInfo.SoundOnPrivate=this.checked;"');
if (top.SetUpClientInfo.SoundOnPrivate)
{
TD.write(' CHECKED ');
}
TD.write('       Title=\''+TextSetupPanelSoundOnPrivateTitleStr+'\'');
TD.write('>');
TD.write('&nbsp;'+TextSetupPanelSoundOnPrivateSignStr+'<br>');
TD.writeln('');
TD.write('<input type="checkbox" name="SoundOnNewUser" ');
TD.write('       onClick="top.SetUpClientInfo.SoundOnNewUser=this.checked;"');
if (top.SetUpClientInfo.SoundOnNewUser)
{
TD.write(' CHECKED ');
}
TD.write('       Title=\''+TextSetupPanelSoundOnNewUserTitleStr+'\'');
TD.write('>');
TD.write('&nbsp;'+TextSetupPanelSoundOnNewUserSignStr+'<br>');
TD.writeln('');
TD.write('<input type="checkbox" name="SoundOnMyMess" ');
TD.write('       onClick="top.SetUpClientInfo.SoundOnMyMess=this.checked;"');
if (top.SetUpClientInfo.SoundOnMyMess)
{
TD.write(' CHECKED ');
}
TD.write('       Title=\''+TextSetupPanelSoundOnMyMessTitleStr+'\'');
TD.write('>');
TD.write('&nbsp;'+TextSetupPanelSoundOnMyMessSignStr+'<br>');
TD.writeln('');
TD.write('<hr width="95%" align=center>');
TD.writeln('');
TD.write('<input type="checkbox" name="KeepPrivateFlag" ');
TD.write('       onClick="top.SetUpClientInfo.KeepPrivateFlag=this.checked;"');
if (top.SetUpClientInfo.KeepPrivateFlag)
{
TD.write(' CHECKED ');
}
TD.write('       Title=\''+TextSetupPanelKeepPrivateTitleStr+'\'');
TD.write('>');
TD.write('&nbsp;'+TextSetupPanelKeepPrivateSignStr+'<br>');
TD.writeln('');
TD.write('<input type="checkbox" name="KeepMessToFlag" ');
TD.write('       onClick="top.SetUpClientInfo.KeepMessToFlag=this.checked;"');
if (top.SetUpClientInfo.KeepMessToFlag)
{
TD.write(' CHECKED ');
}
TD.write('       Title=\''+TextSetupPanelKeepMessToTitleStr+'\'');
TD.write('>');
TD.write('&nbsp;'+TextSetupPanelKeepMessToSignStr+'<br>');
TD.writeln('');
TD.write('<input type="checkbox" name="GZIPStreamFlag" ');
TD.write('       onClick="top.SetUpClientInfo.GZIPStreamFlag=this.checked;"');
if (top.SetUpClientInfo.GZIPStreamFlag)
{
TD.write(' CHECKED ');
}
TD.write('       Title=\''+TextSetupPanelGZIPStreamTitleStr+'\'');
TD.write('>');
TD.write('&nbsp;'+TextSetupPanelGZIPStreamSignStr+'<br>');
TD.writeln('');
TD.write('<input type="checkbox" name="DoNotScroll" ');
TD.write('       onClick="top.SetUpClientInfo.DoNotScroll=this.checked;"');
if (top.SetUpClientInfo.DoNotScroll)
{
TD.write(' CHECKED ');
}
TD.write('       Title=\''+TextSetupPanelDoNotScrollTitleStr+'\'');
TD.write('>');
TD.write('&nbsp;'+TextSetupPanelDoNotScrollSignStr+'<br>');
TD.writeln('');
TD.write('<input type="checkbox" name="CarbonCopy2Pvt" ');
TD.write('       onClick="top.InputFrame.ChatMessChangeCarbonCopy2Pvt(this.checked);"');
if (top.SetUpClientInfo.CarbonCopy2Pvt)
{
TD.write(' CHECKED ');
}
TD.write('       Title=\''+TextSetupPanelCarbonCopy2PvtTitleStr+'\'');
TD.write('>');
TD.write('&nbsp;'+TextSetupPanelCarbonCopy2PvtSignStr+'<br>');
TD.writeln('');
TD.write('<input type="button" name="ClearPrivatFrameButton"');
TD.write('       value=\''+TextSetupPanelClearPvtButtonTextStr+'\'');
TD.write('       STYLE="font-size: smaller"');
TD.write('       onClick="top.InputFrame.ChatMessClearPrivat();"');
TD.write('       Title=\''+TextSetupPanelClearPvtButtonTitleStr+'\'');
TD.write('>');
TD.write('<br>');
TD.writeln('');
TD.write('</font>');
TD.writeln('');
TD.write('</form>');
TD.writeln('');
CurrColorSheme = top.ChatSetUpClientCurrColorSheme();
if (CurrColorSheme == "")
{
if (TD.ChatLocalSetsForm.ColorSheme.selectedIndex != ChatBackColorItemsArrayDefIndex)
{
TD.ChatLocalSetsForm.ColorSheme.selectedIndex = ChatBackColorItemsArrayDefIndex;
}
}
else
{
if (TD.ChatLocalSetsForm.ColorSheme.value != CurrColorSheme)
{
TD.ChatLocalSetsForm.ColorSheme.value = CurrColorSheme;
}
}
TD.write('</body>');
TD.write('</html>');
TD.writeln('');
TD.close();
}
function F_NPSMF(NewMode,TNCK)
{
var SkipActionFlag;
SkipActionFlag = false;
if (TNCK.toUpperCase() == top.SetUpInfo.MyNick.toUpperCase())
{
TNCK = "";
}
if (NewMode == N_PM)
{
if (NewMode == N_PMI)
{
if (N_PNFIN == TNCK)
{
SkipActionFlag = true;
}
}
else
{
SkipActionFlag = true;
}
}
if (!SkipActionFlag)
{
N_PM = NewMode;
N_PNFIN = "";
if      (NewMode == N_PMN)
{
NickWindowDrawFull();
}
else if (NewMode == N_PMW)
{
TD = top.NickFrame.document;
TD.location = "/chat/c_news.php";
}
else if (NewMode == N_PMC)
{
TD = top.NickFrame.document;
TD.location = "/chat/c_cnews.php";
}
else if (NewMode == N_PMI)
{
N_PNFIN = TNCK;
if (TNCK == "")
{
TD = top.NickFrame.document;
TD.open("text/html");
TD.write('<body');
TD.write(' BGCOLOR=\"'+top.SetUpFrame.document.bgColor+'\"');
TD.write(' TEXT=\"'+top.SetUpFrame.document.fgColor+'\"');
TD.write(' id=\"NickFrameBody\"');
TD.write('>');
TD.write(TextMyInfoPanelHeaderStr);
TD.write('</body>');
TD.close();
top.InputFrame.document.MyInfoForm.SID.value    = top.SetUpInfo.SessionId;
top.InputFrame.document.MyInfoForm.Nick.value   = top.SetUpInfo.MyNick;
top.InputFrame.document.MyInfoForm.PreSID.value = top.SetUpInfo.PreSID;
top.ChatSendFillGZIP(top.InputFrame.document.MyInfoForm);
top.InputFrame.document.MyInfoForm.submit();
}
else
{
TD = top.NickFrame.document;
TD.open("text/html");
TD.write('<body');
TD.write(' BGCOLOR=\"'+top.SetUpFrame.document.bgColor+'\"');
TD.write(' TEXT=\"'+top.SetUpFrame.document.fgColor+'\"');
TD.write(' id=\"NickFrameBody\"');
TD.write('>');
TD.write(TextNickInfoPanelHeaderPrefixStr);
TD.write(top.Str2Out(TNCK).bold());
TD.write(TextNickInfoPanelHeaderPostfixStr);
TD.write('</body>');
TD.close();
top.InputFrame.document.NickInfoForm.SID.value    = top.SetUpInfo.SessionId;
top.InputFrame.document.NickInfoForm.Nick.value   = top.SetUpInfo.MyNick;
top.InputFrame.document.NickInfoForm.PreSID.value = top.SetUpInfo.PreSID;
top.InputFrame.document.NickInfoForm.MessTo.value = TNCK;
top.ChatSendFillGZIP(top.InputFrame.document.NickInfoForm);
top.InputFrame.document.NickInfoForm.submit();
}
}
else if (NewMode == N_PMS)
{
NickWindowDrawSmiles();
}
else if (NewMode == N_PMYS)
{
NickWindowDrawMySetUp();
}
else if (NewMode == N_PMYF)
{
TD = top.NickFrame.document;
TD.open("text/html");
TD.write('<body');
TD.write(' BGCOLOR=\"'+top.SetUpFrame.document.bgColor+'\"');
TD.write(' TEXT=\"'+top.SetUpFrame.document.fgColor+'\"');
TD.write(' id=\"NickFrameBody\"');
TD.write('>');
TD.write(TextForumPanelHeaderStr);
TD.write('</body>');
TD.close();
top.InputFrame.document.MyForumInfoForm.SID.value    = top.SetUpInfo.SessionId;
top.InputFrame.document.MyForumInfoForm.Nick.value   = top.SetUpInfo.MyNick;
top.InputFrame.document.MyForumInfoForm.PreSID.value = top.SetUpInfo.PreSID;
top.ChatSendFillGZIP(top.InputFrame.document.MyForumInfoForm);
top.InputFrame.document.MyForumInfoForm.submit();
}
else if (NewMode == N_PMYC)
{
TD = top.NickFrame.document;
TD.location = "/chat/style/c_usestyle.php";
}
else if (NewMode == N_PMR)
{
TD = top.NickFrame.document;
TD.location = "/chat/c_rules.php";
}
else
{
N_PM = N_PMN;
NickWindowDrawFull();
}
}
}
function F_NPSM(NewMode)
{
var TNCK;
TNCK = top.InputFrame.document.InputForm.SendTo.value;
F_NPSMF(NewMode,TNCK);
}
function NI_ITW(NickName,NickColor)
{
var TD;
TD = top.NickFrame.document;
NickColor = ChatForeColorCheck(NickColor);
TD.write('<font color=\'');
TD.write(top.Str2Attr(NickColor));
TD.write('\'');
TD.write('>');
TD.write(top.Str2Out(NickName));
TD.write('</font>');
}
function NI_IAW(NickName,NickColor)
{
var TD;
TD = top.NickFrame.document;
TD.write('<a href=\'javascript:top.InputFrame.FSelNick(');
TD.write('"');
TD.write(top.Str2Sel(NickName));
TD.write('"');
TD.write(');');
TD.write('\'');
TD.write(' ');
TD.write('title=\'');
TD.write(TextNickAnchorTitlePrefixStr);
TD.write(top.Str2Out(NickName));
TD.write(TextNickAnchorTitlePostfixStr);
TD.write('\'');
TD.write('>');
NickColor = ChatForeColorCheck(NickColor);
TD.write('<font color=\'');
TD.write(top.Str2Attr(NickColor));
TD.write('\'');
TD.write('>');
TD.write(top.Str2Out(NickName));
TD.write('</font>');
TD.write('</a>');
}
function NI_IAWNL(NickName,NickColor,Sex,HasInfoFlag,HasMyNoteFlag)
{
var TD;
var IcoImg;
TD = top.NickFrame.document;
IcoImg = "/chat/images/nick";
if      (Sex == N_SM)
{
IcoImg += "m";
}
else if (Sex == N_SF)
{
IcoImg += "f";
}
else
{
IcoImg += "u";
}
TD.write('<img width=11 height=12 border=0 src=\"'+IcoImg+'.gif\">');
TD.write(' ');
NI_IAW (NickName,NickColor);
if (HasInfoFlag)
{
IcoImg = "/chat/images/nickinfo";
TD.write('<a href=\'javascript:top.InputFrame.F_NPSMF(top.InputFrame.N_PMI,');
TD.write('"');
TD.write(top.Str2Sel(NickName));
TD.write('"');
TD.write(');');
TD.write('\'');
TD.write(' ');
TD.write('title=\'');
TD.write(TextNickNotesInfoTitlePrefixStr);
TD.write(top.Str2Out(NickName));
TD.write(TextNickNotesInfoTitlePostfixStr);
TD.write('\'');
TD.write('>');
TD.write('<img width=13 height=13 border=0 src=\"'+IcoImg+'.gif\">');
TD.write('</a>');
}
if (HasMyNoteFlag)
{
IcoImg = "/chat/images/nicknote";
TD.write('<a href=\'javascript:top.InputFrame.F_NPSMF(top.InputFrame.N_PMI,');
TD.write('"');
TD.write(top.Str2Sel(NickName));
TD.write('"');
TD.write(');');
TD.write('\'');
TD.write(' ');
TD.write(TextNickNotesNotesTitlePrefixStr);
TD.write(top.Str2Out(NickName));
TD.write(TextNickNotesNotesTitlePostfixStr);
TD.write('\'');
TD.write('>');
TD.write('<img width=13 height=13 border=0 src=\"'+IcoImg+'.gif\">');
TD.write('</a>');
}
}
function NI_IAWHL(NickName,NickColor,Sex,HasInfoFlag,HasMyNoteFlag)
{
var TD;
var IcoImg;
TD = top.NickFrame.document;
IcoImg = "/chat/images/nick";
if      (Sex == N_SM)
{
IcoImg += "m";
}
else if (Sex == N_SF)
{
IcoImg += "f";
}
else
{
IcoImg += "u";
}
TD.write('<img width=11 height=12 border=0 src=\"'+IcoImg+'.gif\">');
TD.write(' ');
NI_IAW (NickName,NickColor);
if (HasInfoFlag)
{
IcoImg = "/chat/images/nickinfo";
TD.write('<a href=\'');
TD.write('#Info');
TD.write('\'');
TD.write(' ');
TD.write('title=\'');
TD.write(TextNickHeadInfoTitleStr);
TD.write('\'');
TD.write('>');
TD.write('<img width=13 height=13 border=0 src=\"'+IcoImg+'.gif\">');
TD.write('</a>');
}
if (HasMyNoteFlag)
{
IcoImg = "/chat/images/nicknote";
TD.write('<a href=\'');
TD.write('#MyNote');
TD.write('\'');
TD.write(' ');
TD.write('title=\'');
TD.write(TextNickHeadNotesTitleStr);
TD.write('\'');
TD.write('>');
TD.write('<img width=13 height=13 border=0 src=\"'+IcoImg+'.gif\">');
TD.write('</a>');
}
}
top.Debug("c_nick.js loaded");
