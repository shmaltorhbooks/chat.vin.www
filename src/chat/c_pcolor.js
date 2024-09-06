var ChatDefaultForeColor = "green";
function ChatForeColorCheck(Color)
{
var IDX;
IDX = 0;
while((top.ForeColorsArray[IDX] != Color) && (IDX < top.ForeColorsArray.length))
{
IDX++;
}
if (IDX < top.ForeColorsArray.length)
{
return(Color);
}
else
{
return(ChatDefaultForeColor);
}
}
