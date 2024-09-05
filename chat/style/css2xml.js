// +++++++++++++++ CSS functions ++++++++++++++++ Begin

// Single HTMLItem style
// "Name" are HTML id, body are CSSText
// Prefix       :CSSBlockStyleHTMLItem
// Container for:<NONE>
var CSSBlockStyleHTMLItemHeadPrefixStr  = ".";
var CSSBlockStyleHTMLItemHeadSpliterStr = " {";
var CSSBlockStyleHTMLItemBodyPostfixStr = "}";

 
function CSSBlockStyleHTMLItemDumpToLog()
 {
  // Expects new start at line
  LogWriteLn(this.DumpPfx+" HTML Item Style Name:'"+this.Name+"'");
  LogWriteLn(this.DumpPfx+" HTML Item Style Data:'"+this.CSSText+"'");
 }

function CSSBlockStyleHTMLItemToString()
 {
  var Result;

  Result  = "";
  Result += this.HeadPrefix;
  Result += StrTrimSpaces(this.Name);
  Result += this.HeadSpliter;
  Result += StrTrimSpaces(this.CSSText);
  Result += this.BodyPostfix;

  return(Result);
 }


function CSSBlockStyleHTMLItemFromString(SourceStr)
 {
  var HeadValues  = SourceStr.split(this.HeadSpliter);
  var Head;
  var Body;

  SourceStr = StrTrimSpaces(SourceStr);

  if (HeadValues.length != 2)
   {
    return(false);
   }
  else
   {
    Head = StrTrimSpaces(HeadValues[0].toString());
    Body = StrTrimSpaces(HeadValues[1].toString());

    if (this.HeadPrefix.length > 0)
     {
      if (Head.length < this.HeadPrefix.length)
       {
        return(false);
       }

      if (Head.substr(0,this.HeadPrefix.length) != this.HeadPrefix)
       {
        return(false);
       }
      else
       {
        Head = Head.substr
                (this.HeadPrefix.length,
                 Head.length-this.HeadPrefix.length);

        Head = StrTrimSpaces(Head);
       }
     }

    if (Head.length <= 0)
     {
      return(false);
     }

    if (this.BodyPostfix.length > 0)
     {
      if (Body.length < this.BodyPostfix.length)
       {
        return(false);
       }

      if (Body.substr
           (Body.length-this.BodyPostfix.length,this.BodyPostfix.length) 
            != this.BodyPostfix)
       {
        return(false);
       }
      else
       {
        Body = Body.substr(0,Body.length-this.BodyPostfix.length);
        Body = StrTrimSpaces(Body);
       }
     }

    this.Name    = Head;
    this.CSSText = Body;
    return(true);
   }
 }


function CSSBlockStyleHTMLItem(Name,CSSText)
 {
  this.Name         = Name;
  this.CSSText      = CSSText;
  this.ToString     = CSSBlockStyleHTMLItemToString;
  this.FromString   = CSSBlockStyleHTMLItemFromString;
  this.toString     = CSSBlockStyleHTMLItemToString;   // alias
  this.fromString   = CSSBlockStyleHTMLItemFromString; // alias
  this.HeadPrefix   = CSSBlockStyleHTMLItemHeadPrefixStr;
  this.HeadSpliter  = CSSBlockStyleHTMLItemHeadSpliterStr;
  this.BodyPostfix  = CSSBlockStyleHTMLItemBodyPostfixStr;
  this.DumpToLog    = CSSBlockStyleHTMLItemDumpToLog;
  this.DumpPfx      = "+***+++";
 }


// Single style 
// "Name" are style name, Items are styles for HTML elements
// Prefix       :CSSBlockStyle
// Container for:CSSBlockStyleHTMLItem
var CSSBlockStyleHeadPrefixStr   = "<STYLE Name=\"";
var CSSBlockStyleHeadSpliterStr  = "\" BODYSEPARATOR=\"CRLF\">";
var CSSBlockStyleBodySpliterStr  = "\n";
var CSSBlockStyleBodyPostfixStr  = "</STYLE>";

 
function CSSBlockStyleDumpToLog()
 {
  var Index;

  // Expects new start at line
  LogWrite  (this.DumpPfx+" Style Name:'"+this.Name+"' ");
  LogWrite  (" Body:"+this.ItemsArray.length+" items");
  LogWriteLn("");

  for (Index = 0;
       Index < this.ItemsArray.length;
       Index++)
   {
    LogWriteLn(this.DumpPfx+" Item N:"+(Index+1)+" ");
    this.ItemsArray[Index].DumpToLog();
   }

  LogWriteLn(this.DumpPfx+" Done "+this.DumpPfx);
 }

function CSSBlockStyleAddItem(Item)
 {
  var Index;

  Index = 0;
  while(Index < this.ItemsArray.length)
   {
    if (this.ItemsArray[Index].Name == Item.Name)
     {
      this.ItemsArray[Index] = Item;
      return;
     }

    Index++;
   }

  this.ItemsArray[Index] = Item;
 }


function CSSBlockStyleToString()
 {
  var Result;
  var Index;

  Result  = "";
  Result += this.HeadPrefix;
  Result += StrTrimSpaces(this.Name);
  Result += this.HeadSpliter;

  for (Index=0; Index < this.ItemsArray.length;Index++)
   {
    if (Index > 0)
     {
      Result += this.BodySpliter;
     }

    Result += StrTrimSpaces(this.ItemsArray[Index].toString());
   }

  Result += this.BodyPostfix;

  return(Result);
 }


function CSSBlockStyleFromString(SourceStr)
 {
  var HeadValues  = SourceStr.split(this.HeadSpliter);
  var Head;
  var Body;

  SourceStr = StrTrimSpaces(SourceStr);

  LogWriteDEBUG(this.DumpPfx+"Parse:'"+"CSSBlockStyle"+"'");

  LogWriteDEBUG(this.DumpPfx+"HeadP:'"+this.HeadPrefix+"'");
  LogWriteDEBUG(this.DumpPfx+"HeadS:'"+this.HeadSpliter+"'");
  LogWriteDEBUG(this.DumpPfx+"BodyS:'"+this.BodySpliter+"'");
  LogWriteDEBUG(this.DumpPfx+"BodyX:'"+this.BodyPostfix+"'");

  if (HeadValues.length != 2)
   {
    LogWriteDEBUG(this.DumpPfx+"Invalid data count");
    return(false);
   }
  else
   {
    Head = StrTrimSpaces(HeadValues[0].toString());
    Body = StrTrimSpaces(HeadValues[1].toString());

    if (this.HeadPrefix.length > 0)
     {
      if (Head.length < this.HeadPrefix.length)
       {
        return(false);
       }

      if (Head.substr(0,this.HeadPrefix.length) != this.HeadPrefix)
       {
        return(false);
       }
      else
       {
        Head = Head.substr
                (this.HeadPrefix.length,
                 Head.length-this.HeadPrefix.length);

        Head = StrTrimSpaces(Head);
       }
     }

    if (Head.length <= 0)
     {
      return(false);
     }

    if (this.BodyPostfix.length > 0)
     {
      if (Body.length < this.BodyPostfix.length)
       {
        return(false);
       }

      if (Body.substr
           (Body.length-this.BodyPostfix.length,this.BodyPostfix.length) 
            != this.BodyPostfix)
       {
        return(false);
       }
      else
       {
        Body = Body.substr(0,Body.length-this.BodyPostfix.length);
        Body = StrTrimSpaces(Body);
       }
     }

    this.Name = Head;

    var BodyValues = Body.split(this.BodySpliter);
    var Index;

    LogWriteDEBUG(this.DumpPfx+"Body Count"+BodyValues.length);

    for (Index = 0;Index < BodyValues.length;Index++)
     {
      if (StrTrimSpaces(BodyValues[Index]) == "")
       {
        LogWriteDEBUG(this.DumpPfx+"Empty Body - skipped");
       }
      else
       {
        LogWriteDEBUG(this.DumpPfx+"Body parse In");
        var BodyItem = new CSSBlockStyleHTMLItem("");

        if (!BodyItem.FromString(StrTrimSpaces(BodyValues[Index])))
         {
          LogWriteDEBUG(this.DumpPfx+"Body parse - Failed");
          LogWriteDEBUG(this.DumpPfx+"Body parse - FailData:'"+StrTrimSpaces(BodyValues[Index])+"'");
          return(false);
         }
        else
         {
          LogWriteDEBUG(this.DumpPfx+"Body parse - Ok");
          this.AddItem(BodyItem);
         }
       }
     } 

    LogWriteDEBUG(this.DumpPfx+"Body parse - FullOk");
    return(true);
   }
 }


function CSSBlockStyle(Name)
 {
  this.Name         = Name;
  this.ItemsArray   = new Array;
  this.AddItem      = CSSBlockStyleAddItem;
  this.ToString     = CSSBlockStyleToString;
  this.FromString   = CSSBlockStyleFromString;
  this.toString     = CSSBlockStyleToString;   // alias
  this.fromString   = CSSBlockStyleFromString; // alias
  this.HeadPrefix   = CSSBlockStyleHeadPrefixStr;
  this.HeadSpliter  = CSSBlockStyleHeadSpliterStr;
  this.BodySpliter  = CSSBlockStyleBodySpliterStr;
  this.BodyPostfix  = CSSBlockStyleBodyPostfixStr;
  this.DumpToLog    = CSSBlockStyleDumpToLog;
  this.DumpPfx      = "+***";
 }

/* Sample styles string:
<STYLE Name="SampleStyle" BODYSEPARATOR="CRLF"> 
.head {fount-size:10; border-color:red;}
.body {fount-size:20; border-color:red;}
</STYLE>
*/

// Block of styles
// "Name" are block name, Items are styles for number of HTML elements
// Prefix       :CSSBlock
// Container for:CSSBlockStyle
var CSSBlockHeadPrefixStr   = "<STYLEBLOCK Name=\"";
var CSSBlockHeadSpliterStr  = "\" STYLESEPARATOR=\"TAG:NEXTSTYLE\">";
var CSSBlockBodySpliterStr  = "<NEXTSTYLE/>";
var CSSBlockBodyPostfixStr  = "</STYLEBLOCK>";

/* Sample block string:
<STYLEBLOCK Name="SampleStyleBlock" STYLESEPARATOR="TAG:NEXTSTYLE">
 <STYLE Name="SampleStyle" BODYSEPARATOR="CRLF"> 
  .head {fount-size:10; border-color:red;}
  .body {fount-size:20; border-color:red;}
 </STYLE> 
<NEXTSTYLE/>
 <STYLE Name="Style2" BODYSEPARATOR="CRLF"> 
  .head2 {fount-size:10; border-color:red;}
  .body2 {fount-size:20; border-color:red;}
  .text2 {fount-size:20; border-color:red;}
 </STYLE> 
</STYLEBLOCK>
*/

 
function CSSBlockDumpToLog()
 {
  var Index;

  // Expects new start at line
  LogWrite  (this.DumpPfx+" Block Name:'"+this.Name+"' ");
  LogWrite  (" Body:"+this.ItemsArray.length+" items");
  LogWriteLn("");

  for (Index = 0;
       Index < this.ItemsArray.length;
       Index++)
   {
    LogWriteLn(this.DumpPfx+" Item N:"+(Index+1)+" ");
    this.ItemsArray[Index].DumpToLog();
   }

  LogWriteLn(this.DumpPfx+" Done "+this.DumpPfx);
 }

function CSSBlockAddItem(Item)
 {
  var Index;

  Index = 0;
  while(Index < this.ItemsArray.length)
   {
    if (this.ItemsArray[Index].Name == Item.Name)
     {
      this.ItemsArray[Index] = Item;
      return;
     }

    Index++;
   }

  this.ItemsArray[Index] = Item;
 }


function CSSBlockToString()
 {
  var Result;
  var Index;

  Result  = "";
  Result += this.HeadPrefix;
  Result += StrTrimSpaces(this.Name);
  Result += this.HeadSpliter;

  for (Index=0; Index < this.ItemsArray.length;Index++)
   {
    if (Index > 0)
     {
      Result += this.BodySpliter;
     }

    Result += StrTrimSpaces(this.ItemsArray[Index].toString());
   }

  Result += this.BodyPostfix;

  return(Result);
 }


function CSSBlockFromString(SourceStr)
 {
  var HeadValues  = SourceStr.split(this.HeadSpliter);
  var Head;
  var Body;

  SourceStr = StrTrimSpaces(SourceStr);

  LogWriteDEBUG(this.DumpPfx+"Parse:'"+"CSSBlock"+"'");

  LogWriteDEBUG(this.DumpPfx+"HeadP:'"+this.HeadPrefix+"'");
  LogWriteDEBUG(this.DumpPfx+"HeadS:'"+this.HeadSpliter+"'");
  LogWriteDEBUG(this.DumpPfx+"BodyS:'"+this.BodySpliter+"'");
  LogWriteDEBUG(this.DumpPfx+"BodyX:'"+this.BodyPostfix+"'");

  if (HeadValues.length != 2)
   {
    LogWriteDEBUG(this.DumpPfx+"Invalid data count");
    return(false);
   }
  else
   {
    Head = StrTrimSpaces(HeadValues[0].toString());
    Body = StrTrimSpaces(HeadValues[1].toString());

    if (this.HeadPrefix.length > 0)
     {
      if (Head.length < this.HeadPrefix.length)
       {
        return(false);
       }

      if (Head.substr(0,this.HeadPrefix.length) != this.HeadPrefix)
       {
        return(false);
       }
      else
       {
        Head = Head.substr
                (this.HeadPrefix.length,
                 Head.length-this.HeadPrefix.length);

        Head = StrTrimSpaces(Head);
       }
     }

    if (Head.length <= 0)
     {
      return(false);
     }

    if (this.BodyPostfix.length > 0)
     {
      if (Body.length < this.BodyPostfix.length)
       {
        return(false);
       }

      if (Body.substr
           (Body.length-this.BodyPostfix.length,this.BodyPostfix.length) 
            != this.BodyPostfix)
       {
        return(false);
       }
      else
       {
        Body = Body.substr(0,Body.length-this.BodyPostfix.length);
        Body = StrTrimSpaces(Body);
       }
     }

    this.Name = Head;

    var BodyValues = Body.split(this.BodySpliter);
    var Index;

    LogWriteDEBUG(this.DumpPfx+"Body Count"+BodyValues.length);

    for (Index = 0;Index < BodyValues.length;Index++)
     {
      if (StrTrimSpaces(BodyValues[Index]) == "")
       {
        LogWriteDEBUG(this.DumpPfx+"Empty Body - skipped");
       }
      else
       {
        LogWriteDEBUG(this.DumpPfx+"Body parse In");
        var BodyItem = new CSSBlockStyle("");

        if (!BodyItem.FromString(StrTrimSpaces(BodyValues[Index])))
         {
          LogWriteDEBUG(this.DumpPfx+"Body parse - Failed");
          LogWriteDEBUG(this.DumpPfx+"Body parse - FailData:'"+StrTrimSpaces(BodyValues[Index])+"'");
          return(false);
         }
        else
         {
          LogWriteDEBUG(this.DumpPfx+"Body parse - Ok");
          this.AddItem(BodyItem);
         }
       }
     } 

    LogWriteDEBUG(this.DumpPfx+"Body parse - DoneOk");
    return(true);
   }
 }


function CSSBlock(Name)
 {
  this.Name         = Name;
  this.ItemsArray   = new Array;
  this.AddItem      = CSSBlockAddItem;
  this.ToString     = CSSBlockToString;
  this.FromString   = CSSBlockFromString;
  this.toString     = CSSBlockToString;   // alias
  this.fromString   = CSSBlockFromString; // alias
  this.HeadPrefix   = CSSBlockHeadPrefixStr;
  this.HeadSpliter  = CSSBlockHeadSpliterStr;
  this.BodySpliter  = CSSBlockBodySpliterStr;
  this.BodyPostfix  = CSSBlockBodyPostfixStr;
  this.DumpToLog    = CSSBlockDumpToLog;
  this.DumpPfx      = "+";
 }

// +++++++++++++++ CSS functions ++++++++++++++++ End
