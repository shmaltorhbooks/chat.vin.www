<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
 //phpinfo();

 if (isset($ChatErrorText) && $ChatErrorText != "")
  {
  }
 else
  {
   $ChatErrorText = "Неверный запрос";
  }

 include(dirname(__FILE__)."/"."../tpl/c_error.htp.php");
 exit;
?>
