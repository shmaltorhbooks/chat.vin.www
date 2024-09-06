<?
include_once(dirname(__FILE__)."/"."../_cgi/inc/chatlinconst.inc.php"); // Default
?>


// [script language="JavaScript"]
<? /* --- Returns crypted password or "" on error --- */ ?>
function ChatLogDataMake(Password,AuxArray,PreSID,Nick)
 {
  var ResultDataSize  = <? echo ChatConstLogDataMaxSize; ?>;
  var ResultDataArray = new Array;
  var CharCode;
  var Result;
  var Index;
  var Limit;
  var Value;
  var PostUp;
  var PassIndex;
  var PassMax;
  var AccValue;

  if ((Password.length <= 0) ||
      (PreSID.length <= 0) ||
//    (Nick.length <= 0) ||
      (AuxArray.length <= 0))
   {
    return(""); // invalid data given
   }

  Result = "";

  Value = Password;
  Limit = Value.length;
  if (Limit > 0)
   {
    for (Index = 0;Index < ResultDataSize;Index++)
     {
      if (Index < Limit)
       {
        CharCode = ChatCharToCode(Value.charAt(Index));

        if (CharCode == 0)
         {
          return(""); // Fire exit - cannot handle this chracter
         }

        ResultDataArray[Index] = CharCode;
       }
      else
       {
        ResultDataArray[Index] = 0;
       }
     }
   }

  // Fix if ResultDataSize too small (Password chars > ResultDataSize) ignored
  ResultDataArray[ResultDataSize-1] = 0;

  // PostUp prepare
  PostUp = 0;


  Value = PreSID;
  Limit = Value.length;
  if (Limit > 0)
   {
    for (Index = 0;Index < Limit;Index++)
     {
      CharCode = ChatCharToCode(Value.charAt(Index));

      if (CharCode == 0)
       {
        return(""); // Fire exit - cannot handle this chracter
       }

      PostUp = (PostUp ^ CharCode) & 0xFF;
     }
   }


  Value = Nick;
  Limit = Value.length;
  if (Limit > 0)
   {
    for (Index = 0;Index < Limit;Index++)
     {
      CharCode = ChatCharToCode(Value.charAt(Index));

      if (CharCode == 0)
       {
        return(""); // Fire exit - cannot handle this chracter
       }

      PostUp = (PostUp ^ CharCode) & 0xFF;
     }
   }

  // Array process

  Value = PreSID;
  Limit = Value.length;
  if (Limit > 0)
   {
    for (Index = 0;Index < ResultDataSize;Index++)
     {
      CharCode = ChatCharToCode(Value.charAt(Index % Limit));

      if (CharCode == 0)
       {
        return(""); // Fire exit - cannot handle this chracter
       }

      ResultDataArray[Index] = (ResultDataArray[Index] ^ CharCode) & 0xFF;
     }
   }


  Value = Nick;
  Limit = Value.length;
  if (Limit > 0)
   {
    for (Index = 0;Index < ResultDataSize;Index++)
     {
      CharCode = ChatCharToCode(Value.charAt(Index % Limit));

      if (CharCode == 0)
       {
        return(""); // Fire exit - cannot handle this chracter
       }

      ResultDataArray[Index] = (ResultDataArray[Index] ^ CharCode) & 0xFF;
     }
   }

  Value = AuxArray;
  Limit = Value.length;
  if (Limit > 0)
   {
    for (Index = 0;Index < ResultDataSize;Index++)
     {
      CharCode = Value[Index % Limit];
      ResultDataArray[Index] = (ResultDataArray[Index] ^ CharCode) & 0xFF;
     }
   }

  // PostUp use
  AccValue = 0;
  PassMax  = <? echo ChatConstLogDataPostPassMax; ?>;

  for (PassIndex = 0;PassIndex < PassMax;PassIndex++)
   {
    for (Index = 0;Index < ResultDataSize;Index++)
     {
      ResultDataArray[Index] = (ResultDataArray[Index] ^ PostUp) & 0xFF;
//    ResultDataArray[Index] = (ResultDataArray[Index] ^ AccValue) & 0xFF;
//    AccValue = (AccValue + ResultDataArray[Index]) & 0xFF;

      PostUp = (PostUp & 0xFF) << 1;
      PostUp = (PostUp | ((PostUp & 0x100) >> 8)) & 0xFF;
     }

    if ((ResultDataSize % 8) == 0)
     {
      PostUp = (PostUp & 0xFF) << 1;
      PostUp = (PostUp | ((PostUp & 0x100) >> 8)) & 0xFF;
     }
   }

  Result = ResultDataArray.join("/");

  return(Result);
 }

// [/script]
