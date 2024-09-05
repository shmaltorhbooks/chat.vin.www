// +++++++++++++++ Log functions ++++++++++++++++ Begin

function LogClear()
 {
  document.LogDataForm.LogData.value = "";
 }

function LogWrite(Text)
 {
  if (Text == null)
   {
    Text = '';
   }

  document.LogDataForm.LogData.value += Text;
 }

function LogWriteLn(Text)
 {
  if (Text == null)
   {
    Text = '';
   }

  LogWrite(Text+"\n");
 }

function LogWriteln(Text) // alias to LogWriteLn
 {
  LogWriteLn(Text);
 }

function LogWriteDEBUG(Text)
 {
  if (Text == null)
   {
    Text = '';
   }

  if (false)
   {
    LogWrite(Text+"\n");
   }
 }

function LogClass()
 {
  this.write   = LogWrite;
  this.writeln = LogWriteLn;
 }

var Log = new LogClass;

// +++++++++++++++ Log functions ++++++++++++++++ End
