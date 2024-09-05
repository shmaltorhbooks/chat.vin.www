<?
 // Main customisation script
 // Chat specific messages is here
 // -----------------------------------------

//Note: Do not place http:// prefix here
//Example: 'www.chat.vin.com.ua'
$TextMainChatServerNameStr             = 'chat.net'; 

//_cgi\tpl\cc_bod.htp.php
//<title>...</title> text/ Do place "'" here
//will be inside document.write('...');
$TextMainWindowTitleTagPrefixStr       = ''; // Nick
$TextMainWindowTitleTagPostfixStr      = ' - '.$TextMainChatServerNameStr;
$TextMainBootWelcomeMessStr            = 'Welcome to chat!';
$TextMainBootLoginsMessPrefixStr       = 'This is your '; // LoginsCount
$TextMainBootLoginsMessPostfixStr      = ' login.';
?>
