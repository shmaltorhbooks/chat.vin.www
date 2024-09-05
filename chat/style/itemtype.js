function ItemTypeIsNumeric(Obj)
 {
  TypeText = typeof(Obj);

  if (TypeText.toUpperCase() == 'number'.toUpperCase())
   {
    return(true);
   }
  else
   {
    return(false);
   }
 }

function ItemTypeIsArray(Obj)
 {
  TypeText = typeof(Obj);

  if (TypeText.toUpperCase() == 'object'.toUpperCase())
   {
    if      (Obj.length == null)
     {
      return(false);
     }
    else if (Obj.sort == null)
     {
      return(false);
     }
    else if (Obj.join == null)
     {
      return(false);
     }
    else if (Obj.reverse == null)
     {
      return(false);
     }
    else
     {
      return(true);
     }
   }
  else
   {
    return(false);
   }
 }

function ItemTypeIsObject(Obj)
 {
  TypeText = typeof(Obj);

  if (TypeText.toUpperCase() == 'object'.toUpperCase())
   {
    if (ItemTypeIsArray(Obj))
     {
      return(false);
     }
    else
     {
      return(true);
     }
   }
  else
   {
    return(false);
   }
 }

function ItemTypeIsString(Obj)
 {
  TypeText = typeof(Obj);

  if (TypeText.toUpperCase() == 'string'.toUpperCase())
   {
    return(true);
   }
  else
   {
    return(false);
   }
 }

function ItemTypeIsFunction(Obj)
 {
  TypeText = typeof(Obj);

  if (TypeText.toUpperCase() == 'function'.toUpperCase())
   {
    return(true);
   }
  else
   {
    return(false);
   }
 }

function ItemTypeIsBoolean(Obj)
 {
  TypeText = typeof(Obj);

  if (TypeText.toUpperCase() == 'boolean'.toUpperCase())
   {
    return(true);
   }
  else
   {
    return(false);
   }
 }
