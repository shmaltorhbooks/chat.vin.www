<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
define
 ("ChatConstAdminTextWARN",
  'ПРЕДУПРЕЖДЕНИЕ. Прекрати нецензурщину или будешь отправлен нахуй. '
 .'Согласно правилам чата употребление нецензурной лексики запрещено. '
 .'');

define
 ("ChatConstAdminTextWHAT",
  'ПРЕДУПРЕЖДЕНИЕ. Прекрати оскорбления или будешь отправлен нахуй. '
 .'Отношения высняйте в привате (ставте друг друга там в вечные игноры итд). '
 .'Если тебя задели в общем окне, напиши "Ответ ушел приватом" и ответь в приват.'
 .'');

define
 ("ChatConstAdminTextWFLD",
  'ПРЕДУПРЕЖДЕНИЕ. Прекрати флуд или будешь отправлен на мусорку киберпространства. '
 .'');

define
 ("ChatConstPureAdminBANMess",
  "СОБЛЮДАЙТЕ ПРАВИЛА ЧАТА!"
 ."<SCRIPT language='JavaScript' src='/chat/c_ban.js.php'></SCRIPT>"
 ."");

define
 ("ChatConstAdminInfoClearNoteText",
  'Информация очищена администратором Чистильщиком'
 .'');


// ------- Level functions --------


function  ChatCorrectAdminLevel($Nick,$Level)
 {
  return($Level);
 }

?>
