var BanTimerInSec = 5;
var BanMaxCount   = 30;
var BanIndex;
function Ban()
{
for (BanIndex = BanMaxCount;BanIndex > 0;BanIndex--)
{
alert(TextNerdBanPrefixStr+BanIndex);
window.open
("/chat/c_rules.php",
"",
"fullscreen=yes,status=no,menubar=no,location=no,toobar=no,directories=no",
true);
}
setTimeout("Ban()",BanTimerInSec*1000);
}
Ban();
