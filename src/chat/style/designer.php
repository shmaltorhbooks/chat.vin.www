<SCRIPT>
<? include_once(dirname(__FILE__)."/".'str.js') ?>
<? include_once(dirname(__FILE__)."/".'log.js') ?>
<? include_once(dirname(__FILE__)."/".'css2xml.js') ?>
<? include_once(dirname(__FILE__)."/".'cookiestore.js') ?>
<? include_once(dirname(__FILE__)."/".'itemtype.js') ?>
<? include_once(dirname(__FILE__)."/".'dumpobj.js') ?>
</SCRIPT>

<SCRIPT>
// ----------------- Определение рута как того кто вызвал этот скрипт ---------

var Root = null;

Root = window.opener.top;

var RootNotFoundErrorMess = 'Запуск данной страница допустим только из окна чата';

if (Root != null)
 {
  if ((window.opener == null)   || 
      (window.opener == window))
   {
    alert(RootNotFoundErrorMess+'\n'+'Окно-родитель не найдено');
    Root = null;
   }
 }

if (Root != null)
 {
  if (window.opener.top.document.frames.length < 5)
   {
    alert(RootNotFoundErrorMess+'\n'+'Окно-родитель не содержит фреймов');
    Root = null;
   }
 }

if (Root != null)
 {
  if (Root["SetUpInfo"] == null)
   {
    alert(RootNotFoundErrorMess+'\n'+'Не найден SetUp чата в окне родителе');
    Root = null;
   }
 }

if (Root != null)
 {
  if (Root["SetUpClientInfo"] == null)
   {
    alert(RootNotFoundErrorMess+'\n'+'Не найден SetUp клиента в окне родителе');
    Root = null;
   }
 }

</SCRIPT>

<? /* -------------- Определение рута для режима дочернего окна ---------------
<SCRIPT>

var ServerRootURL = ''+document.location.protocol+'//'+document.location.host;
var ChatWindow = open(ServerRootURL);
var Root = null;

Done = false;

do
 {
  if (confirm
      ( 'Для изменения стиля чата,вам будет открыто новое окно'
       +'\nдля него будет использован URL:'+ServerRootURL
       +'\nвыполните вход (логин) в чат в дочернем окне'
       +'\nпосле чего CSS дочернего окна будут доступны'
       +'\nдля изменения в форме на этой странице.'
       +'\n'
       +'\nНажмите OK после входа в чат в дочернем окне.'
       +'\nНажмите Отмена для отмены операции.'
      )
     )
   {
    Root = ChatWindow; // Reassign

    if (Root.document.frames.length < 5)
     {
      alert
       ( 'Вход в чат в дочернем окне не выполнен.'
        +'\nВойдите в чат в дочернем окне'
        +'\nперед изменением стиля его оформления');

      Root = ChatWindow; // Reassign

      if (Root.document.frames.length < 5)
       {
        // Продолжим цикл ожидания
       }
      else
       {
        Done = true;
       }
     }
    else
     {
      Done = true;
     }
   }
  else
   {
    break;
   }
 }
while(!Done);

if (!Done)
 {
  Root = null;
 }

</SCRIPT>
*/ ?>


<?

// ------------- ЗАПОЛНЕНИЕ МАССИВА КОНТРОЛОВ ДЛЯ ПРИСВОЕНИЯ СТИЛЕЙ -----------

$ChatFrameStyleArray = array // Array of frames
 (
  '*' =>
  array('Id' => 'RootFrameSet',
        'Desc' => '{Корневой <FRAMESET>}',
        'Note' => 'Root frameset',
        'Children' => 
        array
         (
          // None
         )
       ),

  'SetUpFrame' =>
  array('Id' => 'SetUpFrame',
        'Desc' => 'SetUpFrame:Панель настроек',
        'Note' => 'SetUp frame',
        'Children' => 
        array
         (
          'SetUpFrameBody' => array
           (
            'Id' => 'SetUpFrameBody',
            'Note' => 'SetUpFrameBody',
            'Desc' => 'SetUpFrameBody'
           ),

          'SetUpFormFont' => array
           (
            'Id' => 'SetUpFormFont',
            'Note' => ' SetUpFormFont',
            'Desc' => ' SetUpFormFont'
           ),

          'SetUpForm' => array
           (
            'Id' => 'SetUpForm',
            'Note' => '· SetUpForm',
            'Desc' => '· SetUpForm'
           ),

          'TableCellFormTitle' => array
           (
            'Id' => 'TableCellFormTitle',
            'Note' => '·· TableCellFormTitle',
            'Desc' => '·· TableCellFormTitle'
           ),

          'SignFormTitle' => array
           (
            'Id' => 'SignFormTitle',
            'Note' => '··· SignFormTitle',
            'Desc' => '··· SignFormTitle'
           ),

          'TableCellSetUpItems' => array
           (
            'Id' => 'TableCellSetUpItems',
            'Note' => '·· TableCellSetUpItems',
            'Desc' => '·· TableCellSetUpItems'
           ),

          'SetUpItemsLine' => array
           (
            'Id' => 'SetUpItemsLine',
            'Note' => '··· SetUpItemsLine',
            'Desc' => '··· SetUpItemsLine'
           ),

          'ControlSetUpItem' => array
           (
            'Id' => 'ControlSetUpItem',
            'Note' => '···· ControlSetUpItem',
            'Desc' => '···· ControlSetUpItem'
           ),

          'TableCellSetUpVertSeparator' => array
           (
            'Id' => 'TableCellSetUpVertSeparator',
            'Note' => '·· TableCellSetUpVertSeparator',
            'Desc' => '·· TableCellSetUpVertSeparator'
           )
         )
       ),

  'InputFrame' =>
  array('Id' => 'InputFrame',
        'Desc' => 'InputFrame:Панель ввода',
        'Note' => 'Input form frame',
        'Children' => 
        array
         (
          'InputFrameBody' => array
           (
            'Id' => 'InputFrameBody',
            'Note' => 'InputFrameBody',
            'Desc' => 'InputFrameBody'
           ),

          'InputForm' => array
           (
            'Id' => 'InputForm',
            'Note' => ' InputForm',
            'Desc' => ' InputForm'
           ),

          'TableCellMessTo' => array
           (
            'Id' => 'TableCellMessTo',
            'Note' => '· TableCellMessTo',
            'Desc' => '· TableCellMessTo'
           ),

          'ControlMessTo' => array
           (
            'Id' => 'ControlMessTo',
            'Note' => '·· ControlMessTo',
            'Desc' => '·· ControlMessTo'
           ),

          'TableCellMessText' => array
           (
            'Id' => 'TableCellMessText',
            'Note' => '· TableCellMessText',
            'Desc' => '· TableCellMessText'
           ),

          'ControlMessText' => array
           (
            'Id' => 'ControlMessText',
            'Note' => '·· ControlMessText',
            'Desc' => '·· ControlMessText'
           ),

          'TableCellMessPvt' => array
           (
            'Id' => 'TableCellMessPvt',
            'Note' => '· TableCellMessPvt',
            'Desc' => '· TableCellMessPvt'
           ),

          'ControlMessPvt' => array
           (
            'Id' => 'ControlMessPvt',
            'Note' => '·· ControlMessPvt',
            'Desc' => '·· ControlMessPvt'
           ),

          'SignMessPvt' => array
           (
            'Id' => 'SignMessPvt',
            'Note' => '·· SignMessPvt',
            'Desc' => '·· SignMessPvt'
           ),

          'TableCellMessSendButton' => array
           (
            'Id' => 'TableCellMessSendButton',
            'Note' => '· TableCellMessSendButton',
            'Desc' => '· TableCellMessSendButton'
           ),

          'ControlMessSendButton' => array
           (
            'Id' => 'ControlMessSendButton',
            'Note' => '·· ControlMessSendButton',
            'Desc' => '·· ControlMessSendButton'
           ),

          'TableCellMetaImgBlock' => array
           (
            'Id' => 'TableCellMetaImgBlock',
            'Note' => '· TableCellMetaImgBlock',
            'Desc' => '· TableCellMetaImgBlock'
           ),

          'ControlAddMetaImg' => array
           (
            'Id' => 'ControlAddMetaImg',
            'Note' => '·· ControlAddMetaImg',
            'Desc' => '·· ControlAddMetaImg'
           ),

          'TableCellFormResetButton' => array
           (
            'Id' => 'TableCellFormResetButton',
            'Note' => '· TableCellFormResetButton',
            'Desc' => '· TableCellFormResetButton'
           ),

          'ControlFormResetButton' => array
           (
            'Id' => 'ControlFormResetButton',
            'Note' => '·· ControlFormResetButton',
            'Desc' => '·· ControlFormResetButton'
           ),

          'TableCellChatLeaveButton' => array
           (
            'Id' => 'TableCellChatLeaveButton',
            'Note' => '· TableCellChatLeaveButton',
            'Desc' => '· TableCellChatLeaveButton'
           ),

          'ControlChatLeaveButton' => array
           (
            'Id' => 'ControlChatLeaveButton',
            'Note' => '·· ControlChatLeaveButton',
            'Desc' => '·· ControlChatLeaveButton'
           )
         )
       ),

  'TopFrame' =>
  array('Id' => 'TopFrame',
        'Desc' => 'TopFrame:Панель баннеров и ссылок',
        'Note' => 'Top banners frame',
        'Children' => 
        array
         (
          'TopFrameBody' => array
           (
            'Id' => 'TopFrameBody',
            'Note' => 'TopFrameBody',
            'Desc' => 'TopFrameBody'
           ),

          'MainNobrBlock' => array
           (
            'Id' => 'MainNobrBlock',
            'Note' => ' MainNobrBlock',
            'Desc' => ' MainNobrBlock'
           ),

          'MainBlock' => array
           (
            'Id' => 'MainBlock',
            'Note' => '· MainBlock',
            'Desc' => '· MainBlock'
           ),

          'TopLine' => array
           (
            'Id' => 'TopLine',
            'Note' => '·· TopLine',
            'Desc' => '·· TopLine'
           ),

          'TopLineFont' => array
           (
            'Id' => 'TopLineFont',
            'Note' => '··· TopLineFont',
            'Desc' => '··· TopLineFont'
           ),

          'ControlChangeBanner' => array
           (
            'Id' => 'ControlChangeBanner',
            'Note' => '···· ControlChangeBanner',
            'Desc' => '···· ControlChangeBanner'
           ),

          'ControlChangeBannerImg' => array
           (
            'Id' => 'ControlChangeBannerImg',
            'Note' => '····· ControlChangeBannerImg',
            'Desc' => '····· ControlChangeBannerImg'
           ),

          'TopLineLinks' => array
           (
            'Id' => 'TopLineLinks',
            'Note' => '···· TopLineLinks',
            'Desc' => '···· TopLineLinks'
           ),

          'TopLink' => array
           (
            'Id' => 'TopLink',
            'Note' => '····· TopLink',
            'Desc' => '····· TopLink'
           ),

          'BottomLine' => array
           (
            'Id' => 'BottomLine',
            'Note' => '·· BottomLine',
            'Desc' => '·· BottomLine'
           ),

          'BottomLineFont' => array
           (
            'Id' => 'BottomLineFont',
            'Note' => '··· BottomLineFont',
            'Desc' => '··· BottomLineFont'
           ),

          'BottomLineLinks' => array
           (
            'Id' => 'BottomLineLinks',
            'Note' => '···· BottomLineLinks',
            'Desc' => '···· BottomLineLinks'
           ),

          'BottomLink' => array
           (
            'Id' => 'BottomLink',
            'Note' => '····· BottomLink',
            'Desc' => '····· BottomLink'
           )
         )
       ),

  'NickFrame' =>
  array('Id' => 'NickFrame',
        'Desc' => 'NickFrame:Многофункц.панель',
        'Note' => 'Multi func (nicks...) frame',
        'Children' => 
        array
         (
         )
       ),

  'ChatFrame' =>
  array('Id' => 'ChatFrame',
        'Desc' => 'ChatFrame:Общее окно чата',
        'Note' => 'Public chat frame',
        'Children' => 
        array
         (
          'ChatFrameBody' => array
           (
            'Id' => 'ChatFrameBody',
            'Note' => 'ChatFrameBody',
            'Desc' => 'ChatFrameBody'
           )
         )
       ),

  'PrivateFrame' =>
  array('Id' => 'PrivateFrame',
        'Desc' => 'PrivateFrame:Окно привата',
        'Note' => 'Private chat frame',
        'Children' => 
        array
         (
          'PrivateFrameBody' => array
           (
            'Id' => 'PrivateFrameBody',
            'Note' => 'PrivateFrameBody',
            'Desc' => 'PrivateFrameBody'
           ),
          'PrivateTitleSign' => array
           (
            'Id' => 'PrivateTitleSign',
            'Note' => ' PrivateTitleSign',
            'Desc' => ' PrivateTitleSign'
           )
         )
       ),
 );
?>

<SCRIPT>
<?

echo "var ChatControlItemFullIdNamesArray;";
echo "\n";
echo "ChatControlItemFullIdNamesArray = new Array;";
echo "\n";
echo "\n";

$Index = 0;

foreach ($ChatFrameStyleArray as $FrameName => $FrameInfo)
 {
  $FrameId   = $FrameInfo['Id'];
  $FrameDesc = $FrameInfo['Desc'];

  echo "ChatControlItemFullIdNamesArray[".$Index."] = new Array;";
  echo "\n";
  echo "ChatControlItemFullIdNamesArray[".$Index."]['ControlItemFullId'] = '".$FrameId."';";
  echo "\n";
  echo "ChatControlItemFullIdNamesArray[".$Index."]['ControlItemDesc']   = '".htmlspecialchars($FrameDesc)."';";
  echo "\n";
  echo "\n";
  $Index++;

  if (isset($FrameInfo['Children']) && is_array($FrameInfo['Children']))
   {
    foreach($FrameInfo['Children'] as $ChildId => $ChildInfo)
     {
      $ChildDesc = $ChildInfo['Desc'];

      $ChildDesc = str_replace('·',' ',$ChildDesc);
      $PosIndex = 0;

      while(($PosIndex < strlen($ChildDesc)) && ($ChildDesc[$PosIndex] == ' '))
       {
        $ChildDesc[$PosIndex] = '-';
        $PosIndex++;
       }

      if ($PosIndex > 0)
       {
        // at least one space replaced
        $ChildDesc[$PosIndex-1] = '>';
        $ChildDesc = '-'.$ChildDesc;
       }
      else
       {
        $ChildDesc = '>'.$ChildDesc;
       }

      $ChildDesc = $FrameDesc."".$ChildDesc;

      echo "ChatControlItemFullIdNamesArray[".$Index."] = new Array;";
      echo "\n";
      echo "ChatControlItemFullIdNamesArray[".$Index."]['ControlItemFullId'] = '".$FrameId.':'.$ChildId."';";
      echo "\n";
      echo "ChatControlItemFullIdNamesArray[".$Index."]['ControlItemDesc']   = '".htmlspecialchars($ChildDesc)."';";
      echo "\n";
      echo "\n";
      $Index++;
     }
   }
 }
?>
</SCRIPT>

<SCRIPT>

function StrCharIsValidIdChar(CharText)
 {
  if      ((CharText >= "A") || (CharText <= "Z"))
   {
    return(true);
   }
  else if ((CharText >= "a") || (CharText <= "z"))
   {
    return(true);
   }
  else if ((CharText >= "0") || (CharText <= "9"))
   {
    return(true);
   }
  else if ((CharText >= "_") || (CharText <= "_"))
   {
    return(true);
   }
  else
   {
    return(false);
   }
 }


function StrIsValidId(InputStr)
 {
  var Index;

  for (Index = 0;Index < InputStr.length;Index++)
   {
    if (!StrCharIsValidIdChar(InputStr.charAt(Index)))
     {
      return(false);
     }
   }

  return(true);
 }


</SCRIPT>

<? /* -[Begin]-- СОБСТВЕННО ФОРМА НАСТРОЙКИ СТИЛЯ --------------------- */ ?>
<form name="ChatCSSSetUpForm" onSubmit="ChatCSSEditFormSave(); return false">
<table cellspacing=0 cellpadding=0 border=0 class="text">
 <tr>
  <td>Имя стиля</td>
  <td>&nbsp;&nbsp;</td>
  <td>Стили сохраненные в сookie</td>
 </tr>
 <tr>
  <td>
   <input type=text Name="StyleName"
          maxlength=30 size=30 class="field">
  </td>
  <td></td>
  <td>
   <? /* ---
   <input type=text  Name="CookieStylesList" 
          maxlength=30  size=30 class="field">
         --- */ ?>
   <select Name="CookieStylesList" class="field">
   <SCRIPT>

   var ChatCookieStyleVarNamePrefix = "ChatStyle_";
   var ChatCookieStylesArray = CookieRecallAllByIndex(ChatCookieStyleVarNamePrefix);

   function ChatCookieStylesListMakeInnerHTML()
    {
     var Index;
     var Result;

     Result = '';

     for (Index = 0;Index < ChatCookieStylesArray.length;Index++)
      {
       Result += '<option value=\''+ChatCookieStylesArray[Index]['VarPostfix']+'\'>';
       Result += ChatCookieStylesArray[Index]['VarPostfix'];
       Result += '</option>';
       Result += '\n';
      }

     if (ChatCookieStylesArray.length <= 0)
      {
       Result += '<option value=\''+''+'\'>';
       Result += '[Нет стиля]';
       Result += '</option>';
       Result += '\n';
      }

     return(Result);
    }

   document.writeln(ChatCookieStylesListMakeInnerHTML());

   </SCRIPT>
   </select>
   &nbsp;
   <input type=button Name="CookieStyleLoad" onClick="ChatCSSEditFormLoad();"
          value="Загрузить" class="button">
  </td>
 </tr>
</table>

<hr class="button">

HTML элемент<br>

<? 
/* --- Old style
<input type=text Name="ControlItemFullId" maxlength=120 size=45 class="field"><br>
   --- */
?>

<select Name="ControlItemFullId" class="field" 
        onchange="ChatCSSEditFormControlFullIdChange(this.value);">
<SCRIPT>
function ChatControlItemFullIdNamesListMakeInnerHTML()
 {
  var Index;
  var Result;

  Result = '';

  for (Index = 0;Index < ChatControlItemFullIdNamesArray.length;Index++)
   {
    Result += '<option value=\''+ChatControlItemFullIdNamesArray[Index]['ControlItemFullId']+'\'>';
    Result += ChatControlItemFullIdNamesArray[Index]['ControlItemDesc'];
    Result += '</option>';
    Result += '\n';
   }

  return(Result);
 }

document.writeln(ChatControlItemFullIdNamesListMakeInnerHTML());

</SCRIPT>
</select>
<br>

CSS для выбранного HTML элемента<br>
<TEXTAREA name=ControlCSSData ROWS=5 COLS=100 class="field" 
          style="font-size:smaller"
          onchange="ChatCSSEditFormControlCSSDataChange(this.value)";></TEXTAREA><br>
<br>
<input type=submit Name="Save" value="Записать" class="button">
</form>


<SCRIPT>

if (ChatCookieStylesArray.length <= 0)
 {
  document.ChatCSSSetUpForm.CookieStylesList.disabled = true;
  document.ChatCSSSetUpForm.CookieStyleLoad.disabled = true;
 }

</SCRIPT>

<hr class="button">

<form name="ChatCSSStoreDataForm">
<input type=button Name="ExportToStore" 
       value="Экспорт текущего стиля в накопитель" class="button"
       onclick="ChatCSSStoreStyleExportToStore();">
&nbsp;
<input type=button Name="ImportFromStore" 
       value=" Импорт стиля из накопителя " class="button"
       onclick="ChatCSSStoreStyleImportFromStore();">
<br>
Накопитель данных стиля для импорта/экспорта:<br>
<TEXTAREA name=ChatCSSStoreData ROWS=10 COLS=100 class="field" style="font-size:smaller"></TEXTAREA>
</form>

<? /* -[End]---- СОБСТВЕННО ФОРМА НАСТРОЙКИ СТИЛЯ --------------------- */ ?>

<SCRIPT>

// +++++++++++++++++++++++ Items CSS Functions ++++++++++++++++++++++++++++++

var ChatNoneCSSSignStr = ""; // "<NONE>";

function ChatFindCSS(TargetDoc,ControlId)
 {
  var ItemData  = TargetDoc.all; // TargetDoc.all.item(ControlId);
  var ResultCSS = null;
  var ResultStr = "";
  var Index;

  if (ItemData == null) 
   {
   }
  else
   {
    if (ItemData.length != null) 
     {
      for (Index=0; Index < ItemData.length; Index++) 
       {
        if (ItemData[Index].id == ControlId)
         {
          ResultCSS = ItemData[Index].style; // .toString;
          break; // first match - enoughf
         }
       }
     } 
    else
     {
      if (ItemData.id == ControlId)
       {
        ResultCSS = ItemData.style; // .toString;
       }
     }
   }

  if (ResultCSS != null)
   {
    ResultStr = ResultCSS.cssText.toString();
   }
  else
   {
    ResultStr = ChatNoneCSSSignStr;
   }

  return(ResultStr);
 } 


function ChatApplyCSS(TargetDoc,ControlId,StyleText)
 {
  var ItemData = TargetDoc.all;// TargetDoc.all.item(ControlId);

  if (StyleText == ChatNoneCSSSignStr)
   {
    StyleText = "";
   }

  if (ItemData != null) 
   {
//  LogWriteLn('ItemData = TargetDoc.all.item('+ControlId+');');
//  LogWriteLn('=== '+StyleText);

    if (ItemData.length != null) 
     {
//    LogWriteLn('Length='+ItemData.length);

      for (Index=0; Index < ItemData.length; Index++) 
       {
//      LogWriteLn(ItemData[Index].id);
        if (ItemData[Index].id == ControlId)
         {
          if (ItemData[Index].style.cssText != StyleText)
           {
//          DumpObjProps(Log,ItemData[Index],'ItemData['+Index+']','');
            ItemData[Index].style.cssText = StyleText;
           }
         }
       }
     } 
    else
     {
      if (ItemData.id == ControlId)
       {
        if (ItemData.style.cssText != StyleText)
         {
          DumpObjProps(Log,ItemData,'ItemData','');
          ItemData.style.cssText = StyleText;
         }
       }
     }
   }
 }

</SCRIPT>

<SCRIPT>

function ChatCSSControlStyleSaveToBrowser(ControlItemFullId,NewStyleText)
 {
  var ItemDataArray;
  var FrameName;
  var ControlId;
  var StyleText;

  if (Root == null)
   {
//  alert(RootNotFoundErrorMess);
    return('');
   }

  ItemData = ControlItemFullId.split(':');

  FrameName = ItemData[0];
  if (ItemData.length == 2)
   {
    ControlId = ItemData[1];
   }
  else
   {
    ControlId = "";
   }

  FrameDoc = Root.frames[FrameName];

  if (FrameDoc == null)
   {
//  alert("Фрейм-источник ["+FrameName+"] не найден");
    return('');
   }

  if (ControlId == "")
   {
    /*
    // don't work
    if (FrameDoc.style.cssText != NewStyleText)
     {
      FrameDoc.style.cssText = NewStyleText; 
      StyleText = FrameDoc.style.cssText;
     }
    */
   }
  else
   {
    ChatApplyCSS(FrameDoc.document,ControlId,NewStyleText);
    StyleText = ChatFindCSS(FrameDoc.document,ControlId);
   }

  if (StyleText == null)
   {
    StyleText = '';
   }

  /*
  LogWrite  ('SAVE');
  LogWrite  (':');
  LogWrite  ('<');
  LogWrite  (ControlItemFullId);
  LogWrite  ('>');

  LogWrite  ('[');
  LogWrite  (FrameName);
  LogWrite  (']');

  LogWrite  ('{');
  LogWrite  (ControlId);
  LogWrite  ('}');

  LogWrite  ('\'');
  LogWrite  (NewStyleText);
  LogWrite  ('\'');

  LogWrite  ('->');

  LogWrite  ('\'');
  LogWrite  (StyleText);
  LogWrite  ('\'');

  LogWriteln();
  */

  return(StyleText);
 }


function ChatCSSControlStyleLoadFromBrowser(ControlItemFullId)
 {
  var ItemDataArray;
  var FrameName;
  var ControlId;
  var StyleText;

  if (Root == null)
   {
//  alert(RootNotFoundErrorMess);
    return('');
   }

  ItemData = ControlItemFullId.split(':');

  FrameName = ItemData[0];
  if (ItemData.length == 2)
   {
    ControlId = ItemData[1];
   }
  else
   {
    ControlId = "";
   }

  FrameDoc = Root.frames[FrameName];

  if (FrameDoc == null)
   {
//  alert("Фрейм-источник ["+FrameName+"] не найден");
    return('');
   }

  if (ControlId == "")
   {
//  StyleText = FrameDoc.style.cssText; // don't work
   }
  else
   {
    StyleText = ChatFindCSS(FrameDoc.document,ControlId);
   }

  if (StyleText == null)
   {
    StyleText = '';
   }

  /*
  LogWrite  ('LOAD');
  LogWrite  (':');
  LogWrite  ('<');
  LogWrite  (ControlItemFullId);
  LogWrite  ('>');

  LogWrite  ('[');
  LogWrite  (FrameName);
  LogWrite  (']');

  LogWrite  ('{');
  LogWrite  (ControlId);
  LogWrite  ('}');

  LogWrite  ('\'');
  LogWrite  (StyleText);
  LogWrite  ('\'');

  LogWriteln();
  */

  return(StyleText);
 }

</SCRIPT>

<SCRIPT>

function ChatCSSStyleLoadFromBrowser()
 {
  var Result;
  var Index;

  Result = new CSSBlockStyle('ImportedFromBrowser');

  for (Index = 0;Index < ChatControlItemFullIdNamesArray.length;Index++)
   {
    FullId  = ChatControlItemFullIdNamesArray[Index]['ControlItemFullId'];
    CSSText = ChatCSSControlStyleLoadFromBrowser(FullId);
    Result.AddItem(new CSSBlockStyleHTMLItem(FullId,CSSText));
   }

//Result.DumpToLog();

  return(Result);
 }

function ChatCSSStyleSaveToBrowser(SourceStyleData)
 {
  var Index;

  for (Index = 0;Index < SourceStyleData.ItemsArray.length;Index++)
   {
    FullId  = SourceStyleData.ItemsArray[Index].Name;
    CSSText = SourceStyleData.ItemsArray[Index].CSSText;
    ChatCSSControlStyleSaveToBrowser(FullId,CSSText);
   }
 }

</SCRIPT>

<SCRIPT>

function ChatCSSStoreStyleExportToStore()
 {
  var StyleData;
  var Result;

  StyleData = ChatCSSStyleLoadFromBrowser();
  Result = '';

  if (StyleData != null)
   {
//  StyleData.DumpToLog();
    Result = StyleData.toString();
   }

  document.ChatCSSStoreDataForm.ChatCSSStoreData.value = Result;
  LogWriteln('Стиль скопирован из броузера в накопитель');
 }

function ChatCSSStoreStyleImportFromStore()
 {
  var StyleData;
  var Income;

  Income = StrTrimSpaces(document.ChatCSSStoreDataForm.ChatCSSStoreData.value);

  if (Income != '') 
   {
    StyleData = new CSSBlockStyle('ImportedFromStore');
    if (StyleData.fromString(Income))
     {
//    StyleData.DumpToLog();
      ChatCSSStyleSaveToBrowser(StyleData);
      LogWriteln('Стиль скопирован из накопителя в броузер');
     }
    else
     {
      alert('Некорректный формат {строки} данных в накопителе');
     }
   }
  else
   {
    alert('В накопителе нет данных для импорта');
   }
 }

</SCRIPT>

<SCRIPT>

function ChatCSSEditFormControlFullIdChange(ControlItemFullId)
 {
  if (Root == null)
   {
    alert(RootNotFoundErrorMess);
    return('');
   }

//alert(ControlItemFullId);

  document.ChatCSSSetUpForm.ControlCSSData.value
   = ChatCSSControlStyleLoadFromBrowser(ControlItemFullId);
 }

function ChatCSSEditFormControlCSSDataChange(NewStyleText)
 {
  if (Root == null)
   {
    alert(RootNotFoundErrorMess);
    return('');
   }

  document.ChatCSSSetUpForm.ControlCSSData.value
   = ChatCSSControlStyleSaveToBrowser
      (document.ChatCSSSetUpForm.ControlItemFullId.value,NewStyleText);
 }

function ChatCSSEditFormSave()
 {
  var StyleData;
  var StyleSource;
  var StyleResult;
  var StyleName;

  // Save data to cookie
  StyleName = document.ChatCSSSetUpForm.StyleName.value;
  StyleName = StrTrimSpaces(StyleName);

  if (StyleName == '')
   {
    alert('Укажите имя стиля для записи в cookie');
    return(false);
   }

  if (!StrIsValidId(StyleName))
   {
    alert('Неверное имя стиля');
    return(false);
   }

  StyleSource = CookieRecall(ChatCookieStyleVarNamePrefix+StyleName);

  StyleData = ChatCSSStyleLoadFromBrowser();
  StyleResult = '';

  if (StyleData == null)
   {
    alert('Неуспешное получение данных стиля');
    return(false);
   }

  StyleData.Name = StyleName;
  StyleResult    = StyleData.toString();

  if (StyleResult != StyleSource)
   {
    if (StyleSource != '')
     {
      if (!confirm('Перезаписать стиль ['+StyleName+'] ?'))
       {
        return(false);
       }
     }
   }

  CookieStore(ChatCookieStyleVarNamePrefix+StyleName,StyleResult);
  LogWriteLn('Стиль ['+StyleName+'] записан из броузера в cookie!');
 }

function ChatCSSEditFormLoad()
 {
  var StyleData;
  var StyleSource;
  var StyleResult;
  var StyleName;

  // Save data to cookie
  StyleName = document.ChatCSSSetUpForm.CookieStylesList.value;
  StyleName = StrTrimSpaces(StyleName);

  if (StyleName == '')
   {
    alert('Неизвестное имя стиля для чтения из cookie');
    return(false);
   }

  StyleSource = CookieRecall(ChatCookieStyleVarNamePrefix+StyleName);

  if (StyleSource != '') 
   {
    StyleData = new CSSBlockStyle(StyleName);
    if (StyleData.fromString(StyleSource))
     {
//    StyleData.DumpToLog();
      ChatCSSStyleSaveToBrowser(StyleData);
      document.ChatCSSSetUpForm.StyleName.value = StyleData.Name;
      LogWriteLn('Стиль ['+StyleName+'] загружен из cookie в броузер');
     }
    else
     {
      alert('Некорректный формат {строки} данных в cookie');
     }
   }
  else
   {
    alert('В cookie нет данных для импорта по этому стилю');
   }
 }

</SCRIPT>

<SCRIPT>

// ++++++++++++++ CSS Stored value Debug dump functions +++++++++++++++++++++

/*
function ChatCSSDump(ValueText)
 {
  var CSSData = new CSSBlock("");
//var CSSData = new CSSBlockStyle("");

  LogClear();

  if (ValueText != "")
   {
    if (!CSSData.fromString(ValueText))
     {
      LogWriteLn('<Invalid text>');
     }
    else
     {
      LogWriteLn("%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%");
      CSSData.DumpToLog();
     }
   }
 }
*/

</SCRIPT>

<? include(dirname(__FILE__)."/".'log.php') ?>

<SCRIPT>

if (Root == null)
 {
  LogWriteln('ACTION CANCELED');
 }
else
 {
  LogWriteln('Preparing:');
 }

</SCRIPT>

