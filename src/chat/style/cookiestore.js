// +++++++++++++++ Cookie store functions ++++++++++++++++ Begin

var CookieStorePath             = null;
var CookieStoreDomain           = null;
var CookieStoreSecureFlag       = null;
var CookieStoreExpiryTimeInDays = null;

var CookieStoreFullUpdateFlag   = false;

function CookieEnabled()
 {
  // return(document.cookieEnabled);
  return(true);
 }


function CookieSetUp(ExpiryTimeInDays,Path,Domain,SecureFlag)
 {
  CookieStorePath             = Path;
  CookieStoreDomain           = Domain;
  CookieStoreSecureFlag       = SecureFlag;
  CookieStoreExpiryTimeInDays = ExpiryTimeInDays;
 }

function CookieMakeSetUpAppendTail()
 {
  var Result;

  Result = "";

  if (CookieStoreExpiryTimeInDays)
   {
    var ExpiryTime = new Date;
    ExpiryTime.setTime
     (ExpiryTime.getTime() + (CookieStoreExpiryTimeInDays*24*60*60*1000));

    Result += "; expires=" + ExpiryTime.toGMTString();
   }

  if (CookieStorePath)
   {
    Result += "; path=" + CookieStorePath;
   }

  if (CookieStoreDomain)
   {
    Result += "; domain=" + CookieStoreDomain;
   }

  if (CookieStoreSecureFlag)
   {
    Result += "; secure";
   }

  return(Result);
 }

function CookieMakeSetUpDeleteTail()
 {
  var Result;

  Result = "";

  if (true) // allways append this
   {
    var ExpiryTime = new Date;
    ExpiryTime.setTime
     (ExpiryTime.getTime() - (365*24*60*60*1000)); // 1 year at past

    Result += "; expires=" + ExpiryTime.toGMTString();
   }

  if (CookieStorePath)
   {
    Result += "; path=" + CookieStorePath;
   }

  if (CookieStoreDomain)
   {
    Result += "; domain=" + CookieStoreDomain;
   }

  if (CookieStoreSecureFlag)
   {
    Result += "; secure";
   }

  return(Result);
 }


// Low functions return 'null' if VarValue is not set


function CookieLowDelete(VarName)
 {
  if (CookieEnabled())
   {
    CookieStr = ''+VarName+'='+'';

    CookieStr += CookieMakeSetUpDeleteTail();
    document.cookie = CookieStr; // rewrite only var
   }
 }


function CookieLowStore(VarName,VarValue)
 {
  if (VarValue == null)
   {
    CookieLowDelete(VarName);
   }

  if (CookieEnabled())
   {
    if (CookieStoreFullUpdateFlag)
     {
      // Full metod - rewrite full cookie string

      var CookieStr = document.cookie;

      var CookieValuesArray = CookieStr.split(';');

      for (var Index = 0;Index < CookieValuesArray.length;Index++)
       {
        var CookieValueItemsArray = CookieValuesArray[Index].split('=');

        if (CookieValueItemsArray.length == 2)
         {
          var Name  = CookieValueItemsArray[0];

          if (Name.toUpperCase() == VarName.toUpperCase())
           {
            var Value = escape(StrTrimSpaces(VarValue));

            CookieValuesArray[Index] = ''+VarName+'='+VarValue;
           }
         }
       }

      CookieStr = CookieValuesArray.join('; ');

      CookieStr += CookieMakeSetUpAppendTail();
      document.cookie = CookieStr; // rewrite full string
     }
    else
     {
      // Fast metod - only append seted var
      // (join data to single string will be done by browser)

      CookieStr = ''+VarName+'='+escape(VarValue);

      CookieStr += CookieMakeSetUpAppendTail();
      document.cookie = CookieStr; // rewrite only var
     }
   }
 }


function CookieLowRecall(VarName)
 {
  if (CookieEnabled())
   {
    var CookieStr = document.cookie;

    var CookieValuesArray = CookieStr.split(';');

    for (var Index = 0;Index < CookieValuesArray.length;Index++)
     {
      var CookieValueItemsArray = StrTrimSpaces(CookieValuesArray[Index]).split('=');

      if      (CookieValueItemsArray.length == 2)
       {
        var Name  = StrTrimSpaces(CookieValueItemsArray[0]);
        var Value = StrTrimSpaces(CookieValueItemsArray[1]);
       }
      else if (CookieValueItemsArray.length == 1)
       {
        var Name  = StrTrimSpaces(CookieValueItemsArray[0]);
        var Value = '';
       }
      else
       {
        var Name  = '';
        var Value = '';
       }

      if (Name != '')
       {
        if (Name.toUpperCase() == VarName.toUpperCase())
         {
          return(StrTrimSpaces(unescape(StrTrimSpaces(Value))));
         }
       }

     }

    return(null);
   }
  else
   {
    return(null);
   }
 }

// 'Std' functions returns '' if value is not set, and treats assign '' as delete

function CookieDelete(VarName)
 {
  CookieLowDelete(VarName);
 }


function CookieStore(VarName,VarValue)
 {
  if      (VarValue == null)
   {
    CookieLowDelete(VarName);
   }
  else if (StrTrimSpaces(VarValue) == '')
   {
    CookieLowDelete(VarName);
   }
  else
   {
    CookieLowStore(VarName,VarValue);
   }
 }


function CookieRecall(VarName)
 {
  VarValue = CookieLowRecall(VarName);

  if (VarValue == null)
   {
    return('');
   }
  else if (StrTrimSpaces(VarValue) == '')
   {
    return('');
   }
  else
   {
    return(VarValue);
   }
 }

function CookieRecallAllByKey(VarNamePrefix)
 {
  // if VarNamePrefix supplied, only var that VarName has VarNamePrefix
  // will be recallred from cookie

  var Result;
  var CookieValueItemsArray;
  var CookieStr;
  var CookieValuesArray;
  var Name;
  var Value;
  var Postfix;

  Result = new Array; // Empty array

  if (CookieEnabled())
   {
    CookieStr = document.cookie;
    CookieValuesArray = CookieStr.split(';');

    for (var Index = 0;Index < CookieValuesArray.length;Index++)
     {
      CookieValueItemsArray = StrTrimSpaces(CookieValuesArray[Index]).split('=');

      if      (CookieValueItemsArray.length == 2)
       {
        Name  = StrTrimSpaces(CookieValueItemsArray[0]);
        Value = StrTrimSpaces(CookieValueItemsArray[1]);
       }
      else if (CookieValueItemsArray.length == 1)
       {
        Name  = StrTrimSpaces(CookieValueItemsArray[0]);
        Value = '';
       }
      else
       {
        Name  = '';
        Value = '';
       }

      if (Name != '')
       {
        Postfix = Name;

        if ((VarNamePrefix == null) || (VarNamePrefix == ''))
         {
          // do not restrict
         }
        else 
         {
          if      (VarNamePrefix.length > Name.length)
           {
            // not match
            Name = '';
           }
          else if (VarNamePrefix != Name.substr(0,VarNamePrefix.length))
           {
            // not match
            Name = '';
           }
          else
           {
            Postfix = Name.substr(VarNamePrefix.length,Name.length-VarNamePrefix.length);
           }
         }
       }

      if (Name != '')
       {
        Result[Name] = StrTrimSpaces(unescape(StrTrimSpaces(Value)));
       }
     }
   }

  return(Result);
 }

function CookieRecallAllByIndex(VarNamePrefix)
 {
  // if VarNamePrefix supplied, only var that VarName has VarNamePrefix
  // will be recallred from cookie

  var Result;
  var CookieValueItemsArray;
  var CookieStr;
  var CookieValuesArray;
  var Name;
  var Value;
  var Postfix;

  Result = new Array; // Empty array

  if (CookieEnabled())
   {
    CookieStr = document.cookie;
    CookieValuesArray = CookieStr.split(';');

    for (var Index = 0;Index < CookieValuesArray.length;Index++)
     {
      CookieValueItemsArray = StrTrimSpaces(CookieValuesArray[Index]).split('=');

      if      (CookieValueItemsArray.length == 2)
       {
        Name  = StrTrimSpaces(CookieValueItemsArray[0]);
        Value = StrTrimSpaces(CookieValueItemsArray[1]);
       }
      else if (CookieValueItemsArray.length == 1)
       {
        Name  = StrTrimSpaces(CookieValueItemsArray[0]);
        Value = '';
       }
      else
       {
        Name  = '';
        Value = '';
       }

      if (Name != '')
       {
        Postfix = Name;

        if ((VarNamePrefix == null) || (VarNamePrefix == ''))
         {
          // do not restrict
         }
        else 
         {
          if      (VarNamePrefix.length > Name.length)
           {
            // not match
            Name = '';
           }
          else if (VarNamePrefix != Name.substr(0,VarNamePrefix.length))
           {
            // not match
            Name = '';
           }
          else
           {
            Postfix = Name.substr(VarNamePrefix.length,Name.length-VarNamePrefix.length);
           }
         }
       }

      if (Name != '')
       {
        Pos = Result.length;

        Result[Pos] = new Array;
        Result[Pos]["VarName"]    = StrTrimSpaces(Name);
        Result[Pos]["VarValue"]   = StrTrimSpaces(unescape(StrTrimSpaces(Value)));
        Result[Pos]["VarPostfix"] = StrTrimSpaces(Postfix);
       }
     }
   }

  return(Result);
 }

// +++++++++++++++ Cookie store functions ++++++++++++++++ End
