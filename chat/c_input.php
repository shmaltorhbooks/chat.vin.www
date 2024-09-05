<html>
<head>
<? include(dirname(__FILE__)."/"."s_codepg.php") ?>
</head>
<script language="JavaScript">
<!-- hide
<? include(dirname(__FILE__)."/"."t_tools.js") ?>
<? include(dirname(__FILE__)."/"."s_text.js") ?>
<? include(dirname(__FILE__)."/"."s_clback.js") ?>
<? include(dirname(__FILE__)."/"."c_pcolor.js") ?>
<? include(dirname(__FILE__)."/"."c_zero.js") ?>
<? include(dirname(__FILE__)."/"."c_obstrm.js") ?>
<? include(dirname(__FILE__)."/"."c_mess.js") ?>
<? include(dirname(__FILE__)."/"."c_nick.js") ?>
<? include(dirname(__FILE__)."/"."c_advert.js") ?>
// -->
</script>
<script language="JavaScript">
<!-- hide
<? include(dirname(__FILE__)."/"."c_iinput.js") ?>
// -->
</script>
<body id='InputFrameBody'>
<? include(dirname(__FILE__)."/"."s_text.php") ?>
<form name="InputForm" id='InputForm'
OnSubmit="return top.InputFrame.ChatSendMessageFormSubmit();">
<table cellspacing=0 cellpadding=1 size="100%" WIDTH=90% NOWRAP" align=left>
<tr>
<td valign=top id='TableCellMessTo'WIDTH=60>
<input type="text"
name="SendTo" id='ControlMessTo'
value="" size=10 maxlength=15
title='<? echo TextInputFormSendToTitleStr; ?>'
>
</td>
<td colspan=4 valign=top id='TableCellMessText' align=left>
<input type="text"
name="MessText" id='ControlMessText'
value="" size=72 maxlength=250
title='<? echo $TextInputFormMessTextTitleStr; ?>'
>
</td>
<td rowspan=2 valign=top align=left>
<font size="-1">
&nbsp;
<!--
<? echo $TextInputFontModeSignStr; ?>
<br>
-->
<!--
<input type="radio" name="MessFont" value="Arial"
style="margin-top:-2px;margin-bottom:-2px;padding-top:0px;padding-bottom:0px;border-top-width:0px;border-bottom-width:0px"
Title='<? echo $TextInputFontModeArialTitleStr; ?>'>&nbsp;<font family="Arial"><? echo $TextInputFontModeArialSignStr; ?></font>
<input type="radio" name="MessFont" value="Times"
style="margin-top:-2px;margin-bottom:-2px;padding-top:0px;padding-bottom:0px;border-top-width:0px;border-bottom-width:0px"
checked
Title='<? echo $TextInputFontModeTimesTitleStr; ?>'>&nbsp;<font family="Times New Roman"><? echo $TextInputFontModeTimesSignStr; ?></font>
<br>
-->
<!--
<input type="checkbox" name="MessBoldFlag"
style="margin-top:-2px;margin-bottom:-2px;padding-top:0px;padding-bottom:0px;border-top-width:0px;border-bottom-width:0px"
Title='<? echo $TextInputFontStyleBoldTitleStr; ?>'>&nbsp;<? echo $TextInputFontStyleBoldSignStr; ?>
<br>
-->
<!--
<input type="checkbox" name="MessIntalicFlag"
style="margin-top:-2px;margin-bottom:-2px;padding-top:0px;padding-bottom:0px;border-top-width:0px;border-bottom-width:0px"
Title='<? echo $TextInputFontStyleItalTitleStr; ?>'>&nbsp;<? echo $TextInputFontStyleItalSignStr; ?>
-->
</font>
</td>
</tr>
<tr>
<td valign=top id='TableCellMessPvt'>
<font size="-1">
<input type="checkbox" name="MessPrivate" id='ControlMessPvt'
title='<? echo $TextInputFlagPrivateTitleStr; ?>'
><span id='SignMessPvt'><? echo $TextInputFlagPrivateSignStr; ?></span>
</font>
</td>
<td id='TableCellMessSendButton' WIDTH=100>
<input type="submit" name="MessSendButton" id='ControlMessSendButton'
title='<? echo $TextInputSendButtonTitleStr; ?>'
value='<? echo $TextInputSendButtonTextStr; ?>'
>
</td>
<td align=left id='TableCellMetaImgBlock'>
<script language="JavaScript">
<!-- hide
// Draw Img icons
for (i = M_CIMV;i <= M_CIXV;i++)
{
document.write("<a href=\"javascript:FInsertImg("+i+")\" ");
document.write(" title='");
document.write(TextSmileItemTitleStr);
document.write("' ");
document.write(" id='ControlAddMetaImg' ");
document.write(">");
document.write(FM_IMLT(i));
document.write("</a>");
}
// -->
</script>
</td>
<td align=left id='TableCellFormResetButton'>
<input type="reset" name="ResetButton" id='ControlFormResetButton'
title='<? echo $TextInputResetButtonTitleStr; ?>'
value='<? echo $TextInputResetButtonTextStr; ?>'
>
</td>
<td align=left id='TableCellChatLeaveButton'>
<input type="button" name="ExitButton" id='ControlChatLeaveButton'
title='<? echo $TextInputLeaveButtonTitleStr; ?>'
value='<? echo $TextInputLeaveButtonTextStr; ?>'
OnClick="return top.InputFrame.ChatLeave();"
>
</td>
</tr>
</table>
</form>
<? include(dirname(__FILE__)."/"."c_forms.php") ?>
<script language="JavaScript">
<!-- hide
<? include(dirname(__FILE__)."/"."c_iasync.js") ?>
// -->
</script>
<script language="JavaScript">
<!-- hide
ChatBoot();
// -->
</script>
</body>
</html>
