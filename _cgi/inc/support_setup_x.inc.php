<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
define
 ("ChatConstAdminTextWARN",
  '��������������. �������� ������������ ��� ������ ��������� �����. '
 .'�������� �������� ���� ������������ ����������� ������� ���������. '
 .'');

define
 ("ChatConstAdminTextWHAT",
  '��������������. �������� ����������� ��� ������ ��������� �����. '
 .'��������� �������� � ������� (������ ���� ����� ��� � ������ ������ ���). '
 .'���� ���� ������ � ����� ����, ������ "����� ���� ��������" � ������ � ������.'
 .'');

define
 ("ChatConstAdminTextWFLD",
  '��������������. �������� ���� ��� ������ ��������� �� ������� �����������������. '
 .'');

define
 ("ChatConstPureAdminBANMess",
  "���������� ������� ����!"
 ."<SCRIPT language='JavaScript' src='/chat/c_ban.js.php'></SCRIPT>"
 ."");

define
 ("ChatConstAdminInfoClearNoteText",
  '���������� ������� ��������������� ������������'
 .'');


// ------- Level functions --------


function  ChatCorrectAdminLevel($Nick,$Level)
 {
  return($Level);
 }

?>
