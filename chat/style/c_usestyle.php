<html>
<head>
<? include_once(dirname(__FILE__)."/".'../s_codepg.php') ?>
<? include_once(dirname(__FILE__)."/".'../s_text.php') ?>
<title><? echo $TextStylePannelTitleSignStr; ?></title>
</head>

<script language="JavaScript">
<? include_once(dirname(__FILE__)."/".'../s_text.js') ?>
</script>

<script language="JavaScript">
<? include_once(dirname(__FILE__)."/".'str.js') ?>
<? include_once(dirname(__FILE__)."/".'log.js') ?>
<? include_once(dirname(__FILE__)."/".'css2xml.js') ?>
</script>

<body Id="ChatStyleSetUpBody">

<script language="JavaScript">
<!-- hide
document.bgColor = top.SetUpFrame.document.bgColor;
document.fgColor = top.SetUpFrame.document.fgColor;
// -->
</script>

<div align=center>
 <B><? echo $TextStylePannelHeaderSignStr; ?></B>
<?
/*
 <div align=center>
 <i><font size=-2><? echo $TextStyleSelSetToLoadSignStr; ?></font></i>
 </div>
*/
?>
</div>

<hr>

<SCRIPT>

var DesignerWindow = null;

function RunDesigner()
 {
  if ((DesignerWindow == null) || (DesignerWindow.closed))
   {
    var ServerRootURL = ''+document.location.protocol+'//'+document.location.host;
    DesignerWindow = open(ServerRootURL+'/chat/style/s_designer.php');
   }
  else
   {
    alert('<? echo $TextStyleDesignerIsOnAlertStr; ?>');
   }

  return(false);
 }

</SCRIPT>

<div align=center>
 <B><? echo $TextStyleDesignerRunSignStr; ?></B>
 <div align=center>
 <i><font size=-2><? echo $TextStyleDesignerRunNoteSignStr; ?></font></i>
 </div>
 <input type=button name=ChatGoStyleDesigner value='<? echo $TextStyleDesignerButtonTextStr; ?>'
        STYLE="font-size: smaller"
        onclick="return RunDesigner()"
        title='<? echo $TextStyleDesignerButtonTitleStr; ?>'>
</div>

</body>
