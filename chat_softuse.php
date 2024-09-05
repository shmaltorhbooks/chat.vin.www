<HTML>
<HEAD>
<? include(dirname(__FILE__)."/"."s_main.php"); ?>
<META http-equiv="Content-Type" content="text/html; charset=windows-1251">
<links href="/chatface/style.css" rel="stylesheet" type="text/css">
</HEAD>

<BODY  leftmargin="5" topmargin="0" marginwidth="5" marginheight="0" bgcolor="#ffffff" scroll="auto">

<table width="100%" border=1>




<tr>
<td >
<!--  Upper block -->
<? include_once(dirname(__FILE__)."/"."chatface/chat_up.php"); ?>
<!-- /Upper block -->
</td>


<tr>
<td width="60%">

<!--  Main block -->

<table>


<tr>
<td width="20%" valign="top">
<!--  Main block menu -->
<? include_once(dirname(__FILE__)."/"."chatface/chat_menu.php"); ?>
<!-- /Main block menu -->
</td>


<td  width="60%" valign="top">
<!--  Main block body -->

<?
// Инструкция пользователя
include_once(dirname(__FILE__)."/"."chatface/body/softuse.htp");
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


<tr><td >
<!--  Bottom block -->
<? include_once(dirname(__FILE__)."/"."chatface/chat_bot.php"); ?>
<!-- /Bottom block -->
</td></tr>

</table>
</BODY>
