<?
//Chat text customisation script
//All messages that send by chat client PHP part will be here
//-------------------------------------------------------------------
//chat\c_input.php:
//..TitleStr:Body of Title='...' attribute. Do not use "'" char inside this
//..TextStr:Body of value='...' attribute. Do not use "'" char inside this
//..SignStr:document.write() // HTML text
$TextInputFormSendToTitleStr    = '������� ��������� �����=��������� ����. ���� ���������� ���� \"��������\" ����� ���������� ������ ���������';
$TextInputFormMessTextTitleStr  = '������� ����� ������ ��������� � ������� ������ \"���������\" ��� ������� Enter';
$TextInputFontModeSignStr       = '�����:';
$TextInputFontModeArialTitleStr = '��������� ��������� ������� Arial';
$TextInputFontModeArialSignStr  = 'Arial';
$TextInputFontModeTimesTitleStr = '��������� ��������� ������� Times New Roman';
$TextInputFontModeTimesSignStr  = 'Times';
$TextInputFontStyleBoldTitleStr = '��������� ��������� ������ �������';
$TextInputFontStyleBoldSignStr  = '<b>������</b>';
$TextInputFontStyleItalTitleStr = '��������� ��������� ��������� �������';
$TextInputFontStyleItalSignStr  = '<i>���������</i>';
$TextInputFlagPrivateTitleStr   = '�������� ������ ��� �������� ������� ��������� ��������';
$TextInputFlagPrivateSignStr    = ' ��������'; // Lead space need here
$TextInputSendButtonTitleStr    = '������� ������ ����� ��������� ���� ���������';
$TextInputSendButtonTextStr     = '���������';
$TextInputResetButtonTitleStr   = '������� ������ ����� �������� ����� �����';
$TextInputResetButtonTextStr    = '�����';
$TextInputLeaveButtonTitleStr   = '������� ������ ����� ����� �� ����';
$TextInputLeaveButtonTextStr    = '�����';
//chat\c_setup.php:
$TextSetupPannelTitleSignStr    = '������ ����������'; // Inside </title>. Do not use HTML
$TextSetupPannelHeaderSignStr   = '���� ������ ������'; // Already inside <b><font  color="#D5F7FF">..</FONT></b>
$TextSetupNPModeNicksTitleStr   = '������� �� ������ ������ �������� �����';
$TextSetupNPModeNicksSignStr    = '�������� �����';
$TextSetupNPModeSmilesTitleStr  = '������� �� ������ ������ �������� �������������� �������';
$TextSetupNPModeSmilesSignStr   = '������ �������';
$TextSetupNPModeMySetUpTitleStr = '������� �� ������ ������ ��������� ���������';
$TextSetupNPModeMySetUpSignStr  = '���������...';
$TextSetupNPModeNickInfoTitleStr= '������� �� ������ ������ ���������� � �������� ����';
$TextSetupNPModeNickInfoSignStr = '������� � ����';
$TextSetupNPModeNickInfoAlertStr= '��� ���������� ������� ���-������� ��� ������� �������'; // alert('...'). Do not use "'" char inside
$TextSetupNPModeMyInfoTitleStr  = '������� �� ������ ������ ���������� � ���';
$TextSetupNPModeMyInfoSignStr   = '���� ����������';
$TextSetupNPModeMyForumTitleStr = '������� �� ������ ������ ���� ���������� � ������';
$TextSetupNPModeMyForumSignStr  = '��� �����';
$TextSetupNPModeNewsTitleStr    = '������� �� ������ ������ ������� ������';
$TextSetupNPModeNewsSignStr     = '������� ������';
$TextSetupNPModeChatNewsTitleStr= '������� �� ������ ������ ������� ����';
$TextSetupNPModeChatNewsSignStr = '������� ����';
$TextSetupNPModeMyColorsTitleStr= '������� �� ������ ������ ���������� ������ ���� (��� ��� ��� ����� HTML � CSS)';
$TextSetupNPModeMyColorsSignStr = '�������� ���!';
$TextSetupNPModeRulesTitleStr   = '������� �� ������ ������ ������� ����';
$TextSetupNPModeRulesSignStr    = '������� ����';
//chat/style/c_usestyle.php
$TextStylePannelTitleSignStr    = '��������� ����� ����'; // Inside </title>. Do not use HTML
$TextStylePannelHeaderSignStr   = '��������� ����� ����'; // Already inside <b>..</b>
$TextStyleSelSetToLoadSignStr   = '�������� ��������� ��� �������� �� cookie';
$TextStyleDesignerIsOnAlertStr  = '���� ��������� ������ ��� �������'; // alert('...');
$TextStyleDesignerRunSignStr    = '������ �������� ������'; // Already inside <b>..</b>
$TextStyleDesignerRunNoteSignStr= '�� ������ ����� ������������ HTML � CSS ��� ���� ����� ������������ �������� ������';
$TextStyleDesignerButtonTextStr = '������� ��������';
$TextStyleDesignerButtonTitleStr= '������� ������ ����� ������� �������� ������ � ����� ����';
//include main page
include(dirname(__FILE__)."/"."../s_main.php");
?>
