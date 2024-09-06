<?
//Chat text customisation script
//All messages that send by chat client PHP part will be here
//-------------------------------------------------------------------
//chat\c_input.php:
//..TitleStr:Body of Title='...' attribute. Do not use "'" char inside this
//..TextStr:Body of value='...' attribute. Do not use "'" char inside this
//..SignStr:document.write() // HTML text
$TextInputFormSendToTitleStr    = 'Адресат сообщения Пусто=Отправить всем. Если установлен флаг \"Приватно\" будет отправлено личное сообщение';
$TextInputFormMessTextTitleStr  = 'Введите текст своего сообщения и нажмите кнопку \"Отправить\" или нажмите Enter';
$TextInputFontModeSignStr       = 'Шрифт:';
$TextInputFontModeArialTitleStr = 'Отправить сообщение шрифтом Arial';
$TextInputFontModeArialSignStr  = 'Arial';
$TextInputFontModeTimesTitleStr = 'Отправить сообщение шрифтом Times New Roman';
$TextInputFontModeTimesSignStr  = 'Times';
$TextInputFontStyleBoldTitleStr = 'Отправить сообщение Жирным шрифтом';
$TextInputFontStyleBoldSignStr  = '<b>Жирный</b>';
$TextInputFontStyleItalTitleStr = 'Отправить сообщение Наклонным шрифтом';
$TextInputFontStyleItalSignStr  = '<i>Наклонный</i>';
$TextInputFlagPrivateTitleStr   = 'Включите флажок для отправки личного сообщения адресату';
$TextInputFlagPrivateSignStr    = ' Приватно'; // Lead space need here
$TextInputSendButtonTitleStr    = 'Нажмите кнопку чтобы отправить ваше сообщение';
$TextInputSendButtonTextStr     = 'Отправить';
$TextInputResetButtonTitleStr   = 'Нажмите кнопку чтобы очистить форму ввода';
$TextInputResetButtonTextStr    = 'Сброс';
$TextInputLeaveButtonTitleStr   = 'Нажмите кнопку чтобы выйти из чата';
$TextInputLeaveButtonTextStr    = 'ВЫХОД';
//chat\c_setup.php:
$TextSetupPannelTitleSignStr    = 'Панель управления'; // Inside </title>. Do not use HTML
$TextSetupPannelHeaderSignStr   = 'МЕНЮ ПРАВОЙ ПАНЕЛИ'; // Already inside <b><font  color="#D5F7FF">..</FONT></b>
$TextSetupNPModeNicksTitleStr   = 'Вывести на правую панель перечень ников';
$TextSetupNPModeNicksSignStr    = 'Перечень ников';
$TextSetupNPModeSmilesTitleStr  = 'Вывести на правую панель перечень дополнительных смайлов';
$TextSetupNPModeSmilesSignStr   = 'Панель смайлов';
$TextSetupNPModeMySetUpTitleStr = 'Вывести на правую панель настройки программы';
$TextSetupNPModeMySetUpSignStr  = 'Настройки...';
$TextSetupNPModeNickInfoTitleStr= 'Вывести на правую панель информацию о выбраном нике';
$TextSetupNPModeNickInfoSignStr = 'Справка о нике';
$TextSetupNPModeNickInfoAlertStr= 'Вам необходимо выбрать ник-адресат для запроса справки'; // alert('...'). Do not use "'" char inside
$TextSetupNPModeMyInfoTitleStr  = 'Вывести на правую панель информацию о вас';
$TextSetupNPModeMyInfoSignStr   = 'Ваша информация';
$TextSetupNPModeMyForumTitleStr = 'Вывести на правую панель вашу информацию с форума';
$TextSetupNPModeMyForumSignStr  = 'Ваш Форум';
$TextSetupNPModeNewsTitleStr    = 'Вывести на правую панель новости города';
$TextSetupNPModeNewsSignStr     = 'Новости города';
$TextSetupNPModeChatNewsTitleStr= 'Вывести на правую панель новости Чата';
$TextSetupNPModeChatNewsSignStr = 'Новости Чата';
$TextSetupNPModeMyColorsTitleStr= 'Вывести на правую панель управление стилем Чата (для тех кто знает HTML и CSS)';
$TextSetupNPModeMyColorsSignStr = 'Раскрась чат!';
$TextSetupNPModeRulesTitleStr   = 'Вывести на правую панель правила Чата';
$TextSetupNPModeRulesSignStr    = 'Правила Чата';
//chat/style/c_usestyle.php
$TextStylePannelTitleSignStr    = 'Настройки стиля чата'; // Inside </title>. Do not use HTML
$TextStylePannelHeaderSignStr   = 'Настройки стиля чата'; // Already inside <b>..</b>
$TextStyleSelSetToLoadSignStr   = 'Выберите настройки для загрузки из cookie';
$TextStyleDesignerIsOnAlertStr  = 'Окно дизайнера стилей уже открыто'; // alert('...');
$TextStyleDesignerRunSignStr    = 'Запуск дизанера стилей'; // Already inside <b>..</b>
$TextStyleDesignerRunNoteSignStr= 'Вы должны уметь использовать HTML и CSS для того чтобы использовать дизайнер стилей';
$TextStyleDesignerButtonTextStr = 'Открыть дизайнер';
$TextStyleDesignerButtonTitleStr= 'Нажмите кнопку чтобы открыть дизайнер стилей в новом окне';
//include main page
include(dirname(__FILE__)."/"."../s_main.php");
?>
