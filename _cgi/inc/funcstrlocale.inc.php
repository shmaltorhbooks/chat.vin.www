<? 

// CP1251
// Locale is Russian/Ukrainian

// Case Conversions Up (CodePage 1251)
// E0-FF -> C0-DF
// A2H=¢ -> A1H=¡
// B8H=¸ -> A8H=¨
// BAH=º -> AAH=ª
// B3H=³ -> B2H=²
// BFH=¿ -> AFH=¯
// B4H=´ -> A5H=¥
function ChatStrToUp ($TargetStr)
 {
  // NOTE BEGIN *** EDIT THIS LINES IN Notepad only! 
  $search  = array("'([a-z]|[à-ÿ])'e"    ,"[¢]","[¸]","[º]","[³]","[¿]","[´]");
  $replace = array("chr(ord('\\1')-0x20)","¡"  ,"¨"  ,"ª"  ,"²"  ,"¯"  ,"¥");
  // NOTE END   ***

  $RetChar = preg_replace($search,$replace,$TargetStr);
  return $RetChar;
 }

function ChatStrToLow($TargetStr)
 {
  // NOTE BEGIN *** EDIT THIS LINES IN Notepad only! 
  $search  = array("'([A-Z]|[À-ß])'e"    ,"[¡]","[¨]","[ª]","[²]","[¯]","[¥]");
  $replace = array("chr(ord('\\1')+0x20)","¢"  ,"¸"  ,"º"  ,"³"  ,"¿"  ,"´");
  // NOTE END   ***

  $RetChar = preg_replace($search,$replace,$TargetStr);
  return $RetChar;
 }

// Kill filteger characters
function ChatNickPurgeSignToSpace($TargetNickStr,$ReplaceText = "")
 {
  // NOTE BEGIN *** EDIT THIS LINES IN Notepad only! 
  $search  = array("([_\-/.~^`\"\',;:!|/{/}/*//\\/#/&/%/</>/?/+/=])");
  $replace = array($ReplaceText);
  // NOTE END   ***

  $RetNick = preg_replace($search,$replace,$TargetNickStr);
  return $RetNick;
 }

// Switch nick to visual representation
function ChatNickToVisual ($TargetNickStr)
 {
  //                                                                                                                                                                       v- BUG HERE: Use '' instead of ""
  // NOTE BEGIN *** EDIT THIS LINES IN Notepad only! 
  $search  = array("[;]","[×]","[¥]","([À@])","([ÂÜÚ8])","([Ñ\(\[<])","[Ä]","([Å¨ÇªÝ3])","([6GÁ])","([1²¯L])","[Í]","[Ê]","[Ì]","([È¹É])","([Î0])","[Ð]","[ß]","[Ò]","[Õ]","([5$Z])","([Ó¡])","([\}\)>])");
  $replace = array(":"  ,"4"  ,"Ã"  , "A"     ,"B"       ,"C"         ,"D"  ,"E"         ,"G"      ,"I"       ,"H"  ,"K"  ,"M"  ,"N"      ,"O"     ,"P"  ,"R"  ,"T"  ,"X"  ,"S"      ,"Y"     ,"]"        );
  // NOTE END   ***

  $UpNick     = ChatNickPurgeSignToSpace($TargetNickStr);
  $UpNick     = ChatStrToUp($UpNick);
  $UpNick     = preg_replace($search,$replace,$UpNick);
  $VisualNick = ChatStrToSimple($UpNick);
  return($VisualNick);
 }

function  ChatNickCharAllowed ($NickChar)
 {
  $Normal = true;

  if      ($NickChar == "\"")
   {
    $Normal = false;
   }
  else if ($NickChar == "'")
   {
    $Normal = false;
   }
  else if ($NickChar == "\\")
   {
    $Normal = false;
   }
  else if ($NickChar == "&")
   {
    $Normal = false;
   }
  else if ($NickChar == ">")
   {
    $Normal = false;
   }
  else if ($NickChar == "<")
   {
    $Normal = false;
   }
  else
   {
    $Value = ord($NickChar{0});

    if      (($Value >= 0x20) && ($Value <= 0x7E))
     {
      // Lat (7 bit)
     }
    else if (($Value >= 0xE0) && ($Value <= 0xFF))
     {
      // Small russian
     }
    else if (($Value >= 0xC0) && ($Value <= 0xDF))
     {
      // Big russian
     }
    else if (($Value == 0xA2) || // '¢' Bel uml. y
             ($Value == 0xA1) || // '¡' Bel uml. y (up)
             ($Value == 0xB8) || // '¸' Rus dot. e 
             ($Value == 0xA8) || // '¨' Rus dot. e (up)
             ($Value == 0xBA) || // 'º' Ukr      e
             ($Value == 0xAA) || // 'ª' Ukr      e (up)
             ($Value == 0xB3) || // '³' Ukr dot. i
             ($Value == 0xB2) || // '²' Ukr dot. i (up)
             ($Value == 0xBF) || // '¿' Ukr dob. i
             ($Value == 0xAF) || // '¯' Ukr dob. i (up)
             ($Value == 0xB4) || // '´' Ukr hrd. g
             ($Value == 0xA5))   // '¥' Ukr hrd. g (up)
     {
     }
    else
     {
      $Normal = false;
     }
   }

  return($Normal);
 }


// Switch nick to visual representation
function ChatStopWordTranslate($SourceStr)
 {
  //                                                                                                                                                                       v- BUG HERE: Use '' instead of ""
  // NOTE BEGIN *** EDIT THIS LINES IN Notepad only! 
  $search  = array("4","¥","A","@","B","Ü","Ú","8","C","(","E","¨","ª","Ý","6","H","K","M","O","P","R","T","X","><","Y","¡");
  $replace = array("×","Ã","À","À","Â","Â","Â","Â","Ñ","Ñ","Å","Å","Å","Å","Á","Í","Ê","Ì","Î","Ð","ß","Ò","Õ","Õ" ,"Ó","Ó");
  // NOTE END   ***

  return(str_replace($search,$replace,$SourceStr));
 }

?>
