<?
// Top (banner) panel content body
// Customise it as you want
?>
<html>
<head>
<META HTTP-EQUIV="Pragma" CONTENT="No-Cache">
<? include(dirname(__FILE__)."/"."s_codepg.php") ?>
<title>TOP BANNER PAGE. Place your title here</title>
</head>
<body id='TopFrameBody'>



<!-- Fixed part. Please keep it -->
<script language='javascript'>
//<!--
document.bgColor = top.SetUpFrame.document.bgColor;
document.fgColor = top.SetUpFrame.document.fgColor;
//-->
</script>
<script language='javascript'>
//<!--
//You may call this to change banner
function ChatAdvtTopBannerChange()
{
top.ChatAdvtReStartTimer();
document.location.reload();
}
//-->
</script>

<!-- /Fixed part -->
<nobr id='MainNobrBlock'>
<span id='MainBlock'>
<div align=left id='TopLine'>


<tr><td>
<a href="javascript:ChatAdvtTopBannerChange();"
title="If noy do not like this banner, press this button to reload"
id='ControlChangeBanner'
>
<img src="images/bm_ban3.JPG"
border=0 hspace=0 vspace=0
id='ControlChangeBannerImg'
align=absbottom></a>



<tr><td>
<font size="-1" id='TopLineFont'>



<div align=left id='_TopBanner'>
<? include(dirname(__FILE__)."/"."../chatadvt/chatclnt/chatclnt_top.htp"); ?>
<script language='javascript'>
//<!--
document.bgColor = top.SetUpFrame.document.bgColor;
document.fgColor = top.SetUpFrame.document.fgColor;
//-->
</script>




</span>
</font>
</div>
</span>
</body>
