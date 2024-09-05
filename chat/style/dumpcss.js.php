<?
// Need:
//  PHP SCOPE: 'log.php' need to be included before this file
//  JAVA SCRIPT SCOPE: var Root must be set to 'root' for documents
?>

<SCRIPT>
<? include_once(dirname(__FILE__)."/".'str.js.php') ?>
<? include_once(dirname(__FILE__)."/".'log.js.php') ?>
<? include_once(dirname(__FILE__)."/".'css2xml.js.php') ?>
</SCRIPT>

<SCRIPT>

// ++++++++ Dump HTML hierarhy ++++++++++++++++++++++++++++++++++++++++++++++

function HTMLDataDumpItem(Item,Level)
 {
  var LevelStr = "";
  var Index;
  var Childs;

  for (Index = 0;Index < Level;Index++)
   {
//  LevelStr += "++";
    LevelStr += "  ";
   }

  if (Level > 0)
   {
//  LevelStr += " ";
   }

  LogWrite(LevelStr+"ID='"+Item.id+"'");
  var ItemId = Item.id;

  if (Item.Name != null)
   {
    LogWrite(" NAME='"+Item.Name+"'");
   }

  if (Item.tagName != null)
   {
    LogWrite(" TAG='"+Item.tagName+"'");
   }

  if (Item.tagName == '!')
   {
    LogWriteln(" Comment[Skip]");
    return;
   }

  Childs = Item.children;

  if (Childs == null)
   {
    LogWrite(" Childs N/A");
   }
  else
   {
    if      (Childs.length == null)
     {
      LogWriteln(" Childs count: N/A");
     }
    else if (Childs.length == 0)
     {
      LogWriteln(" No Childs");
     }
    else
     {
      var ChildIndex;

      LogWrite(" Childs count:'"+Childs.length+"'");

      if (ItemId != '')
       {
        if (ItemId.substr(0,1) == '_')
         {
          LogWriteln(" Protected[Skip]");
          return;
         }
       }

      LogWriteln(" Dump:");

      LogWriteLn(LevelStr+' {');

      for (ChildIndex = 0;ChildIndex < Childs.length;ChildIndex++)
       {
//      LogWriteLn(LevelStr+' [ChildIndex:'+ChildIndex+'] [id=\''+Childs[ChildIndex].id+'\']');
        HTMLDataDumpItem(Childs[ChildIndex],Level+1);
//      LogWriteLn(LevelStr+' [Childs done]');
       }

      LogWriteLn(LevelStr+' }');
     }
   }
 }

function HTMLDataDumpToLog(ControlName)
 {
  var Parent = document.all[ControlName+''];

  if (Parent != null)
   {
    LogWriteln('%%%%%%%%%%%%% '+ControlName+ ' %%%%%%%%%%%%%');
    LogWriteln();
    HTMLDataDumpItem(Parent,0);
   }
 }


function HTMLDataDumpFromControl(Control)
 {
  var Parent = Control;

  if (Parent != null)
   {
    LogWriteln('%%%%%%%%%%%%% [CONTROL DUMP] %%%%%%%%%%%%%');
    LogWriteln();
    HTMLDataDumpItem(Parent,0);
   }
 }


</SCRIPT>

<SCRIPT>

// +++++++++++++++++++++ HTML/CSS hierarhy dump +++++++++++++++++++++++++++++

var HTMLCSSIdDataDumpDoneArray = new Array;
var HTMLCSSIdDataDumpModeSign  = 'A'; // [P]ath,[D]esc,[A]rray

function HTMLCSSIdDataDumpItem(Item,Level,LevelPrefixStr)
 {
  var Index;
  var Childs;
  var ItemId = Item.id;
  var ChildsLevelPrefixStr;

  ChildsLevelPrefixStr = LevelPrefixStr;

  if (Item.tagName != null)
   {
   }

  if (Item.tagName == '!')
   {
    return; // comment
   }

  if (ItemId != '')
   {
    if (ItemId.substr(0,1) == '_')
     {
      return; // protected item
     }
   }

  if (ItemId != '')
   {
    if (ChildsLevelPrefixStr != '')
     {
      ChildsLevelPrefixStr += "/";
     }

    ChildsLevelPrefixStr += ItemId;

    var Index;
    var Found;

    Found = false;

    for (Index=0;
         Index < HTMLCSSIdDataDumpDoneArray.length;
         Index++)
     {
      if (HTMLCSSIdDataDumpDoneArray[Index].FullName == ChildsLevelPrefixStr)
       {
        Found = true;
        break;
       }
     }

    if (!Found)
     {
      if (HTMLCSSIdDataDumpModeSign == 'P')
       {
        LogWriteln(ChildsLevelPrefixStr);
       }
      else
       {
        var ShowLevel;

        ShowLevel = (ChildsLevelPrefixStr.split('/').length)-1;

        DescStr = '';

        for (var LevelIndex = 2;LevelIndex <= ShowLevel;LevelIndex++)
         {
//        DescStr += '-';
//        DescStr += '';
          DescStr += '·';
         }

        if (ShowLevel > 0)
         {
          DescStr += ' ';
         }

        DescStr += ItemId;

        if (HTMLCSSIdDataDumpModeSign == 'D')
         {
          LogWriteln(DescStr);
         }
        else // 'A' - [A]rray
         {
/*
          'SetUpFrameBody' => array
           (
            'Id' => 'SetUpFrameBody',
            'Desc' => '++SetUpFrameBody',
            'Note' => '++SetUpFrameBody'
           )
*/

          LogWrite('          ');
          LogWrite('\''+ItemId+'\'');
          LogWrite(' => array');
          LogWriteln();

          LogWrite('           ');
          LogWrite('(');
          LogWriteln();
                  
          LogWrite('            ');
          LogWrite('\'Id\' => \''+ItemId+'\'');
          LogWrite(',');
          LogWriteln();

          LogWrite('            ');
          LogWrite('\'Note\' => \''+DescStr+'\'');
          LogWrite(',');
          LogWriteln();

          LogWrite('            ');
          LogWrite('\'Desc\' => \''+DescStr+'\'');
          LogWriteln();

          LogWrite('           ');
          LogWrite('),');
          LogWriteln();
                    
          LogWriteln();
         }
       }

      Index = HTMLCSSIdDataDumpDoneArray.length;

      HTMLCSSIdDataDumpDoneArray[Index] = new Array;
      HTMLCSSIdDataDumpDoneArray[Index].FullName   = ChildsLevelPrefixStr;
      HTMLCSSIdDataDumpDoneArray[Index].MatchCount = 1;
     }
    else
     {
      HTMLCSSIdDataDumpDoneArray[Index].MatchCount++;
     }
   }

  Childs = Item.children;

  if (Childs == null)
   {
   }
  else
   {
    if      (Childs.length == null)
     {
     }
    else if (Childs.length == 0)
     {
     }
    else
     {
      var ChildIndex;

      for (ChildIndex = 0;ChildIndex < Childs.length;ChildIndex++)
       {
        HTMLCSSIdDataDumpItem(Childs[ChildIndex],Level+1,ChildsLevelPrefixStr);
       }
     }
   }
 }

function HTMLCSSIdDataDumpToLog(ControlName,RootPrefix)
 {
  var Parent = document.all[ControlName+''];

  if (RootPrefix == null)
   {
    RootPrefix = "";
   }

  if (Parent != null)
   {
    HTMLCSSIdDataDumpDoneArray = new Array;

//  LogWriteln('// %%%%%%%%%%%%% '+ControlName+ ' %%%%%%%%%%%%% //');
//  LogWriteln();
    HTMLCSSIdDataDumpItem(Parent,0,RootPrefix);
   }
 }


function HTMLCSSIdDataDumpFromControl(Control,RootPrefix)
 {
  var Parent = Control;

  if (RootPrefix == null)
   {
    RootPrefix = "";
   }

  if (Parent != null)
   {
    HTMLCSSIdDataDumpDoneArray = new Array;

//  LogWriteln('// %%%%%%%%%%%%% [CONTROL DUMP] %%%%%%%%%%%%% //');
//  LogWriteln();
    HTMLCSSIdDataDumpItem(Parent,0,RootPrefix);
   }
 }


</SCRIPT>

<SCRIPT>

// Root var must be set outside as source for dumping vars
// for exmaple:
// var Root = window.top;

if (Root == null)
 {
  LogWriteln('// ACTION CANCELED //');
 }
else
 {
  /*
  HTMLDataDumpFromControl(document.body);
  HTMLDataDumpFromControl(Root.document.body);
  */

  /*
  LogWriteln('// SetUpFrame');
  //HTMLDataDumpFromControl(Root.SetUpFrame.document.body);
  */

  LogWriteln('// SetUpFrame');
  HTMLCSSIdDataDumpFromControl(Root.SetUpFrame.document.body);
  LogWriteln();

  LogWriteln('// InputFrame');
  HTMLCSSIdDataDumpFromControl(Root.InputFrame.document.body);
  LogWriteln();

  LogWriteln('// TopFrame');
  HTMLCSSIdDataDumpFromControl(Root.TopFrame.document.body);
  LogWriteln();

  LogWriteln('// ChatFrame');
  HTMLCSSIdDataDumpFromControl(Root.ChatFrame.document.body);
  LogWriteln();

  LogWriteln('/*');
  LogWriteln('// [HTML]ChatFrame');
  HTMLDataDumpFromControl(Root.ChatFrame.document.body);
  LogWriteln('*/');
  LogWriteln();

  LogWriteln('// PrivateFrame');
  HTMLCSSIdDataDumpFromControl(Root.PrivateFrame.document.body);
  LogWriteln();

  /*
  LogWriteln('Nick');
  HTMLDataDumpFromControl(Root.NickFrame.document.body);

  LogWriteln('Chat');
  HTMLDataDumpFromControl(Root.ChatFrame.document.body);

  LogWriteln('Private');
  HTMLDataDumpFromControl(Root.PrivateFrame.document.body);
  */
 }

</SCRIPT>

