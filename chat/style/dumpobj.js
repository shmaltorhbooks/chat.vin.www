function DumpObjPropsMustBeSkip(PropNameStr,ObjTypeSign)
 {
  if (ObjTypeSign.toUpperCase() == 'window'.toUpperCase())
   {
    if (PropNameStr.toUpperCase() == 'clipboarddata'.toUpperCase())
     {
      return(true);
     }

    if (PropNameStr.toUpperCase() == 'external'.toUpperCase())
     {
      return(true);
     }

    if (PropNameStr.toUpperCase() == 'history'.toUpperCase())
     {
      return(true);
     }

    if (PropNameStr.toUpperCase() == 'Image'.toUpperCase())
     {
      return(true);
     }

    if (PropNameStr.toUpperCase() == 'Option'.toUpperCase())
     {
      return(true);
     }

    if (PropNameStr.toUpperCase() == 'screen'.toUpperCase())
     {
      return(true);
     }
   }

  return(false);
 }

function DumpObjPropsStep(TargetDoc,Obj,ObjDrawName,ObjTypeSign)
 {
  var Result;
  var PropValueStr;
  var PropNameStr;

  if (ObjTypeSign == null)
   {
    ObjTypeSign = "";
   }

  Result = "";

  if      (Obj == null)
   {
    PropValueStr = '[null]';
   }
  else if (ItemTypeIsNumeric(Obj))
   {
    PropValueStr = Obj.toString();
   }
  else if (ItemTypeIsBoolean(Obj))
   {
    if (Obj)
     {
      PropValueStr = 'true';
     }
    else
     {
      PropValueStr = 'false';
     }
   }
  else if (ItemTypeIsString(Obj))
   {
    PropValueStr = '\''+Obj.toString()+'\'';
   }
  else if (ItemTypeIsFunction(Obj))
   {
    PropValueStr = '[function()]';
   }
  else if (ItemTypeIsArray(Obj) && (Obj.length <= 0))
   {
    PropValueStr = '[empty array]';
   }
  /*
  else if (ItemTypeIsArray(Obj))
   {
    // Dump Array
    PropValueStr = ''; // dumped localy
   }
  */
  else if (ItemTypeIsObject(Obj) || ItemTypeIsArray(Obj))
   {
    // Dump Object
    for (PropNameStr in Obj) 
     {
      if      (DumpObjPropsMustBeSkip(PropNameStr,ObjTypeSign))
       {
        PropValueStr = '[skipped]';
       }
      else
       {
        ObjPropValue = Obj[PropNameStr];

        if (ObjPropValue == null)
         {
          PropValueStr = '[null]';
         }
        else if (ItemTypeIsNumeric(ObjPropValue))
         {
          PropValueStr = ObjPropValue.toString();
         }
        else if (ItemTypeIsBoolean(ObjPropValue))
         {
          if (ObjPropValue)
           {
            PropValueStr = 'true';
           }
          else
           {
            PropValueStr = 'false';
           }
         }
        else if (ItemTypeIsString(ObjPropValue))
         {
          PropValueStr = '\''+ObjPropValue.toString()+'\'';
         }
        else if (ItemTypeIsFunction(ObjPropValue))
         {
          PropValueStr = '[function()]';
         }
        else if (ItemTypeIsArray(ObjPropValue) && (ObjPropValue.length <= 0))
         {
          PropValueStr = '[empty array]';
         }
        else
         {
          PropValueStr = '['+typeof(ObjPropValue)+']';
         }
       }

      Result += Str2HTMLOut(ObjDrawName) + "." + Str2HTMLOut(PropNameStr) + " = " + PropValueStr + "";
      Result += "\n";
     }

    PropValueStr = ''; // dumped localy
   }
  else
   {
    // Unknown type
    PropValueStr = typeof(Obj)+':'+'\''+Obj.toString()+'\'';
   }

  if (PropValueStr != '')
   {
    Result += Str2HTMLOut(ObjDrawName) + " = " + PropValueStr + "";
    Result += "\n";
   }

  TargetDoc.write(Result);
 }

function DumpObjProps(TargetDoc,Obj,ObjDrawName,ObjTypeSign)
 {
  var Result = "";

  if (ObjTypeSign == null)
   {
    ObjTypeSign = "";
   }

  DumpObjPropsStep(TargetDoc,Obj,ObjDrawName,ObjTypeSign);
 }

