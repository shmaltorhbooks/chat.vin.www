function StrReplace     (InputStr,FromStr,ToStr)
{
var FromPos;
var FoundPos;
var RS;
FromPos   = 0;
FoundPos  = InputStr.indexOf(FromStr);
RS = "";
if (FoundPos < 0)
{
RS = InputStr;
}
else
{
while(FoundPos >= 0)
{
RS += InputStr.substring(FromPos,FoundPos);
RS += ToStr;
FoundPos  += FromStr.length;
FromPos    = FoundPos;
FoundPos   = InputStr.indexOf(FromStr,FoundPos);
}
RS += InputStr.substring(FromPos,InputStr.length);
}
return(RS);
}
function Str2Out (InputStr)
{
var OutStr;
OutStr = InputStr;
OutStr = StrReplace(OutStr,"&","&amp;");
OutStr = StrReplace(OutStr,"<","&lt;");
OutStr = StrReplace(OutStr,">","&gt;");
OutStr = StrReplace(OutStr,"  "," &nbsp;");
return(OutStr);
}
function Str2Sel (InputStr)
{
var OutStr;
OutStr = InputStr;
OutStr = StrReplace(OutStr,"\\","\\\\");
OutStr = StrReplace(OutStr,"\"","\\\"");
OutStr = StrReplace(OutStr,"\'","\\\'");
OutStr = StrReplace(OutStr,"&","&amp;");
OutStr = StrReplace(OutStr,"<","&lt;");
OutStr = StrReplace(OutStr,">","&gt;");
OutStr = StrReplace(OutStr,"\"","&quot;");
OutStr = StrReplace(OutStr,"#" ,"&#35;");
OutStr = StrReplace(OutStr,"\'","&#39;");
OutStr = StrReplace(OutStr,"\\","&#92;");
OutStr = StrReplace(OutStr,"/" ,"&#47;");
OutStr = StrReplace(OutStr,"%" ,"\\045");
return(OutStr);
}
function Str2Attr (InputStr)
{
var OutStr;
OutStr = InputStr;
OutStr = StrReplace(OutStr,"\'","");
OutStr = StrReplace(OutStr,"\"","");
OutStr = StrReplace(OutStr,"\\","");
OutStr = StrReplace(OutStr,"<","");
OutStr = StrReplace(OutStr,">","");
OutStr = StrReplace(OutStr,"&","");
OutStr = StrReplace(OutStr,"%","");
return(OutStr);
}
