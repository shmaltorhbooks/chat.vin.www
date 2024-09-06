<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<html>
<head>
<META HTTP-EQUIV="Pragma" CONTENT="No-Cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
<META http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<body>

<script language="JavaScript">
<!-- hide
document.bgColor = top.SetUpFrame.document.bgColor;
document.fgColor = top.SetUpFrame.document.fgColor;
// -->
</script>

<?
include(dirname(__FILE__)."/"."../tpl/cishead.htp.php"); // A=top
include(dirname(__FILE__)."/"."chatadv.php"); // Advertising

//echo "Ваши данные. Ник :<b>".htmlspecialchars($Nick)."</b><br>";
echo "<div align=center><b>Справка о вас</b></div>";

include(dirname(__FILE__)."/"."chatmyinfoblock.php");
?>
</body>
