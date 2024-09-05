<html>
<head>
<? include(dirname(__FILE__)."/"."s_codepg.php") ?>
<? include(dirname(__FILE__)."/"."s_text.php") ?>
<title><? echo $TextSetupPannelTitleSignStr; ?></title>
<script language="JavaScript">
<!-- hide
// OnReload clear buffer
top.ChatFillClearInputRequest();
top.ChatFillClearOutputRequest();
// -->
</script>
<script language="JavaScript">
<!-- hide
function CallNickPanelSetModeNickInfo()
{
if (top.InputFrame.document.InputForm.SendTo.value != "")
{
top.InputFrame.F_NPSM(top.InputFrame.N_PMI);
}
else
{
alert('<? echo $TextSetupNPModeNickInfoAlertStr; ?>');
}
}
// -->
</script>
</head>
<body id='SetUpFrameBody' bgcolor=#dcf0ca background="london-c3.jpg">
<font size="-1" id='SetUpFormFont'>
<form name="SetUpForm" id='SetUpForm'>
<nobr>
<table cellspacing=0 cellpadding=0 align=center >

<tr>
<td >
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td valign=top align=left nowrap id='TableCellSetUpItems'>

<span id='SetUpItemsLine'>
<a  href="javascript:top.InputFrame.F_NPSM(top.InputFrame.N_PMN);"
Title='<? echo $TextSetupNPModeNicksTitleStr; ?>'
id='ControlSetUpItem'
target="NickFrame">
<font face="tahoma" size="-1" color="#999999"><? echo $TextSetupNPModeNicksSignStr; ?></a><br>

<a  href="javascript:top.SetUpFrame.CallNickPanelSetModeNickInfo();"
Title='<? echo $TextSetupNPModeNickInfoTitleStr; ?>'
id='ControlSetUpItem'
target="NickFrame">
<font face="tahoma" size="-1" color="#999999"> <? echo $TextSetupNPModeNickInfoSignStr; ?></a><br>


</span>
</font>
</td>
<td id='TableCellSetUpVertSeparator'>
&nbsp;
&nbsp;
&nbsp;
&nbsp;
&nbsp;
<td valign=top align=left nowrap id='TableCellSetUpItems' >
<font face="tahoma" size="-1" color="#999999">
<span id='SetUpItemsLine'>
<a  href="javascript:top.InputFrame.F_NPSM(top.InputFrame.N_PMS);"
Title='<? echo $TextSetupNPModeSmilesTitleStr; ?>'
id='ControlSetUpItem'
target="NickFrame">
<font face="tahoma" size="-1" color="#999999"><? echo $TextSetupNPModeSmilesSignStr; ?></a><br>

<a  href="javascript:top.InputFrame.F_NPSMF(top.InputFrame.N_PMI,'');"
Title='<? echo $TextSetupNPModeMyInfoTitleStr; ?>'
target="NickFrame">
<font face="tahoma" size="-1" color="#999999"><? echo $TextSetupNPModeMyInfoSignStr; ?></a><br>



</span>
</font>
</td>
<td id='TableCellSetUpVertSeparator'>
&nbsp;
&nbsp;
&nbsp;
&nbsp;
&nbsp;
</td>
<td valign=top align=left nowrap id='TableCellSetUpItems'>
<font face="tahoma" size="-1" color="#999999">
<span id='SetUpItemsLine'>

<a  href="javascript:top.InputFrame.F_NPSM(top.InputFrame.N_PMYC);"
Title='<? echo $TextSetupNPModeMyColorsTitleStr; ?>'
id='ControlSetUpItem'
target="NickFrame">
<font face="tahoma" size="-1" color="#999999"><? echo $TextSetupNPModeMyColorsSignStr; ?></a><br>

<a  href="javascript:top.InputFrame.F_NPSM(top.InputFrame.N_PMYS);"
Title='<? echo $TextSetupNPModeMySetUpTitleStr; ?>'
id='ControlSetUpItem'
target="NickFrame">
<font face="tahoma" size="-1" color="#999999"><? echo $TextSetupNPModeMySetUpSignStr; ?></a><br>
</span>
</font>
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
</table>
</nobr>
</form>
</font>
</body>
</html>
