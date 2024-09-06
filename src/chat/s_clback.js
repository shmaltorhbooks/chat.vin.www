//Chat background colors customisation script
//You may update background colors to watever you like
//-------------------------------------------------------------------
<!-- Fixed part. Please keep it -->
function ChatBackColorItem(ColorName,BackColor,ForeColor,TopHiColor,TopLoColor)
{
this.ColorName  = ColorName;
this.BackColor  = BackColor;
this.ForeColor  = ForeColor;
this.TopHiColor = TopHiColor;
this.TopLoColor = TopLoColor;
}
<!-- /Fixed part -->
//-------------------------------------------------------------------
var ChatBackColorItemsArray = new Array
(
// -------------------- (ColorName    ,BackColor      ,ForeColor,TopHiColor  ,TopLoColor)
new ChatBackColorItem("Black"      ,"white"        ,""       ,"lightblue" ,"#191970" ),
new ChatBackColorItem("Gray"       ,"whitesmoke"   ,""       ,"lightblue" ,"#191970" ),
new ChatBackColorItem("Silver"     ,"aliceblue"    ,""       ,"lightblue" ,"#191970" ),
new ChatBackColorItem("Green"      ,"honeydew"     ,""       ,"lightgreen","#197019" ),
new ChatBackColorItem("LightYellow","lightyellow"  ,""       ,"lightgreen","#197019" ),
new ChatBackColorItem("Lavender"   ,"lavenderblush",""       ,"lightpink" ,"#701919" ),
new ChatBackColorItem("Pink"       ,"mistyrose"    ,""       ,"lightpink" ,"#701919" ),
new ChatBackColorItem("DeepGray"   ,"#10222E"      ,"white"  ,"white"     ,"darkgray"),
new ChatBackColorItem("DeepBlue"   ,"#05002E"      ,"white"  ,"white"     ,"darkgray"),
new ChatBackColorItem("DeepGreen"  ,"#001504"      ,"white"  ,"white"     ,"darkgray"),
new ChatBackColorItem("Black"      ,"black"        ,"white"  ,"white"     ,"darkgray")
);
var ChatBackColorItemsArrayDefIndex = 0; // IDX of default color
