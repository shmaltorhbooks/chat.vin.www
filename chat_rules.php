<HTML>
<HEAD>
<? include(dirname(__FILE__)."/"."s_main.php"); ?>
<META http-equiv="Content-Type" content="text/html; charset=windows-1251">
<links href="/chatface/style.css" rel="stylesheet" type="text/css">
</HEAD>

<BODY  leftmargin="5" topmargin="0" marginwidth="5" marginheight="0" bgcolor="#ffffff" scroll="auto">

<table width="100%">

<tr><td width="100%">
<!--  Upper block -->
<? include_once(dirname(__FILE__)."/"."chatface/chat_up.php"); ?>
<!-- /Upper block -->
</td></tr>

<tr><td>
<!--  Main block -->

<table>
<tr>
<td width="20%" valign="top">
<!--  Main block menu -->
<? include_once(dirname(__FILE__)."/"."chatface/chat_menu.php"); ?>
<!-- /Main block menu -->
</td>
<td width="60%" valign="top">
<!--  Main block body -->

<?
// Правила чата
include_once(dirname(__FILE__)."/"."chatface/body/rules_up.htp");
include_once(dirname(__FILE__)."/"."chatface/body/rules.htp");
include_once(dirname(__FILE__)."/"."chatface/body/rules_bot.htp");
?>
<!-- /Main block body -->
</td>

<td width="20%" valign="top">
<!--  Main block right -->
<? include_once(dirname(__FILE__)."/"."chatface/chat_right.php"); ?>
<!-- /Main block right -->
</td>
</tr>
</table>

<!-- /Main block -->
</td></tr>


</table>

</BODY>
