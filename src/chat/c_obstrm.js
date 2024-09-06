top.Debug("c_obstrm.js loading");
function OS_S()
{
return(this.OSD_IA.length);
}
function OS_C()
{
this.OSD_IA          = new Array;
this.OSD_IABI = 0;
}
function OS_MS()
{
return(this.OSD_IAMS);
}
function OS_AI(Item)
{
var ItemIndex;
if (this.OSD_IA.length < this.OSD_IAMS)
{
ItemIndex = this.OSD_IA.length;
this.OSD_IA[ItemIndex] = Item;
}
else
{
ItemIndex = this.OSD_IABI;
this.OSD_IA[ItemIndex] = Item;
this.OSD_IABI++;
this.OSD_IABI = this.OSD_IABI % this.OSD_IAMS;
}
return(this.OSD_IA.length-1);
}
function OS_GI(ItemIndex)
{
if (ItemIndex < 0)
{
return(null);
}
if (ItemIndex < this.OSD_IA.length)
{
ItemIndex = (ItemIndex + this.OSD_IABI) % this.OSD_IAMS;
return(this.OSD_IA[ItemIndex]);
}
else
{
return(null);
}
}
function OS_PI(ItemIndex,Item)
{
if (ItemIndex < 0)
{
return(null);
}
if (ItemIndex == this.OSD_IA.length)
{
this.OSF_AI(Item);
return(Item);
}
if (ItemIndex < this.OSD_IA.length)
{
ItemIndex = (ItemIndex + this.OSD_IABI) % this.OSD_IAMS;
this.OSD_IA[ItemIndex] = Item;
return(Item);
}
else
{
return(null);
}
}
function OS_C(StreamMaxSize)
{
if (StreamMaxSize <= 0)
{
StreamMaxSize = 1;
}
this.OSD_IA          = new Array;
this.OSD_IAMS   = StreamMaxSize;
this.OSD_IABI = 0;
this.Size    = OS_S;
this.MaxSize = OS_MS;
this.OSF_C   = OS_C;
this.OSF_GI = OS_GI;
this.OSF_PI = OS_PI;
this.OSF_AI = OS_AI;
}
top.Debug("c_obstrm.js loaded");
