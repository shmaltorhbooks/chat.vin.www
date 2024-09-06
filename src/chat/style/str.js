// +++++++++++++++ Str functions ++++++++++++++++ Begin

function StrCharIsSpace(CharText)
 {
  if      (CharText == " ")
   {
    return(true);
   }
  else if (CharText == "\n")
   {
    return(true);
   }
  else if (CharText == "\r")
   {
    return(true);
   }
  else if (CharText == "\t")
   {
    return(true);
   }
  else
   {
    return(false);
   }
 }


function StrTrimRightSpaces(Text)
 {
  while((Text.length > 0) && (StrCharIsSpace(Text.charAt(Text.length-1))))
   {
    Text = Text.substr(0,Text.length-1);
   }

  return(Text);
 }


function StrTrimLeftSpaces(Text)
 {
  while((Text.length > 0) && (StrCharIsSpace(Text.charAt(0))))
   {
    Text = Text.substr(1,Text.length-1);
   }

  return(Text);
 }


function StrTrimSpaces(Text)
 {
  return(StrTrimLeftSpaces(StrTrimRightSpaces(Text)));
 }


function StrReplace(InputStr,FromStr,ToStr)
 {
  var FromPos;
  var FoundPos;
  var ResultStr;

  FromPos   = 0;
  FoundPos  = InputStr.indexOf(FromStr);
  ResultStr = "";

  if (FoundPos < 0)
   {
    ResultStr = InputStr; // NotFound
   }
  else
   {
    while(FoundPos >= 0)
     {
      ResultStr += InputStr.substring(FromPos,FoundPos);
      ResultStr += ToStr;

      FoundPos  += FromStr.length;

      FromPos    = FoundPos;
      FoundPos   = InputStr.indexOf(FromStr,FoundPos);
     }

    ResultStr += InputStr.substring(FromPos,InputStr.length);
   }

  return(ResultStr);
 }


function Str2HTMLOut(InputStr) // as htmlspecialchars
 {
  var OutStr;

  OutStr = InputStr;
  OutStr = StrReplace(OutStr,"&","&amp;");
  OutStr = StrReplace(OutStr,"<","&lt;");
  OutStr = StrReplace(OutStr,">","&gt;");
  OutStr = StrReplace(OutStr,"\"","&quot;");

  return(OutStr);
 }


// +++++++++++++++ Str functions ++++++++++++++++ End
