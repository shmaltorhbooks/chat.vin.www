function ChatSetUpSetColorBack(ColorPairText)
{
var IDX;
var Limit;
var Frame;
var Pos;
var ForeColor;
var BackColor;
var TopHiColor;
var TopLoColor;
BackColor  = "";
ForeColor  = "";
TopHiColor = "";
TopLoColor = "";
BackColor = ColorPairText;
Pos = ColorPairText.indexOf("/");
if (Pos < 0)
{
}
else
{
BackColor = ColorPairText.substring(0,Pos);
ForeColor = ColorPairText.substring(Pos+1,ColorPairText.length);
ColorPairText = ForeColor;
Pos = ColorPairText.indexOf("/");
if (Pos < 0)
{
}
else
{
ForeColor = ColorPairText.substring(0,Pos);
TopHiColor = ColorPairText.substring(Pos+1,ColorPairText.length);
ColorPairText = TopHiColor;
Pos = ColorPairText.indexOf("/");
if (Pos < 0)
{
}
else
{
TopHiColor = ColorPairText.substring(0,Pos);
TopLoColor = ColorPairText.substring(Pos+1,ColorPairText.length);
}
}
}
if (BackColor == "")
{
BackColor = "white";
}
if (ForeColor == "")
{
if (BackColor.toUpperCase() == "black".toUpperCase())
{
ForeColor = "white";
}
else
{
ForeColor = "black";
}
}
if (TopHiColor == "")
{
TopHiColor = ForeColor;
}
if (TopLoColor == "")
{
TopLoColor = TopHiColor;
}
Limit = top.frames.length;
for (IDX = 0;IDX < Limit;IDX++)
{
Frame = top.frames[IDX];
Frame.document.bgColor = BackColor;
Frame.document.fgColor = ForeColor;
}
}
function ChatSetUpClientSetColorSheme(ColorSheme)
{
if (ColorSheme != "")
{
if (top.SetUpClientInfo.ColorSheme != ColorSheme)
{
top.SetUpClientInfo.ColorSheme = ColorSheme;
ChatSetUpSetColorBack(ColorSheme);
}
}
else
{
}
}
function ChatSetUpClientCurrColorSheme()
{
return(top.SetUpClientInfo.ColorSheme);
}
