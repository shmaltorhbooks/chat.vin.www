<?

// Input request arguments (from GET|POST)
// Если аргумент == NULL, то все Arg* функции возвращают null

function ArgRawValue($InStr)    // удаляет слеши из агрумента   
 {
   if (is_null($InStr))
   {
    return(null);
   }
  $Result = $InStr;
  $Result = stripslashes($Result);
  return ($Result);
 }

function ArgAsStrLow($InStr)     // преобразовывает все спецсимволы в пробелы 
 {
    
  if (is_null($InStr))
   {
    return(null);
   }

  $Result = $InStr;
  $Result = preg_replace("/[\\x00-\\x1F]/"," ",$Result);
  return ($Result);
 }

function ArgAsStr($InStr)        // удаляет все лидириующие и завершающие пробелы  
 {
    
  if (is_null($InStr))
   {
    return(null);
   }

  return (trim(ArgAsStrLow($InStr)));
 }

function ArgAsInt($InStr,$DefErrValue = 0)        // Note: Возвращает DefErrValue если $InStr не является INT-числом
 {
  if (is_null($InStr))
   {
    return(null);
   }

  $Result = $InStr;
  $Result = ArgAsStr($Result);

  if (is_numeric($Result))
   {
    $Result = intval($Result);

    if (is_null($Result))
     {
      $Result = $DefErrValue;
     }
   }
  else
   {
    $Result = $DefErrValue;
   }

  return ($Result);
 }

function ArgAsText($InStr)                        // Преобразовывает строку в текстовый вид, удаляет символы конца строки, спецсимволы, лидирующие и завершающие пробелы
 {
  if (is_null($InStr))
   {
    return(null);
   }

  $Result = $InStr;
  // First pass (convert any \n\r combination to \n)
  $Result = str_replace("\r\n","\n",$Result);
  $Result = str_replace("\n\r","\n",$Result);
  $Result = str_replace("\r"  ,"\n",$Result);
  // Advanced pass (convert any low codes to space)
  $Result = preg_replace("/[\\x0d-\\x0d]/","",$Result);
  $Result = preg_replace("/[\\x00-\\x09]/"," ",$Result);
  $Result = preg_replace("/[\\x0b-\\x1F]/"," ",$Result);
  // Trim leading-trailing spaces
  $Result = trim($InStr);
  return ($Result);
 }

// SQL* SQL data and ready to use values

function SQLStr($InStr)                          // добавляет к строке слэши для использования в базе
 {
  if (is_null($InStr))
   {
    return(null);
   }

  $Result = $InStr;
  $Result = addslashes($Result);
  return ($Result);
 }

function SQLInt($InStr,$DefErrValue = 0)         // возвращает число, либо если аргумент не есть число, то $DefErrValue (probably 0)
 {
  if (is_null($InStr))
   {
    return(null);
   }

  $Result = $InStr;
  $Result = trim($Result);

  if (is_numeric($Result))
   {
    $Result = intval($Result);

    if (is_null($Result))
     {
      $Result = $DefErrValue;
     }
   }
  else
   {
    $Result = $DefErrValue;
   }

  return ($Result);
 }

function SQLFldStr($InStr)                      // возвращает число в кавычках, если аргумент не есть число  то $DefErrValue (probably 0)
 {
  return("'".SQLStr($InStr,$DefErrValue)."'");
 }

function SQLFldInt($InStr,$DefErrValue = 0)      // возвращает число, либо если аргумент не есть число, то $DefErrValue (probably 0)
 {
  return(SQLInt($InStr,$DefErrValue));
 }

// JS* JavaScript data and ready to use values

function JSStr($InStr,$DefErrValue = '')        // возвращает аругмент в котором все спецсимволы экранированы слэшами
 {
  if (is_null($InStr))
   {
    return($DefErrValue);
   }

  $Result = $InStr;
  $Result = addslashes($Result);
  $Result = addcslashes($Result,"\0..\37");

  return ($Result);
 }

function JSInt($InStr,$DefErrValue = 0)         // удаляет пробелы из начала и конца строки
 {
  if (is_null($InStr))
   {
    return($DefErrValue);
   }

  $Result = $InStr;
  $Result = trim($Result);

  if (is_numeric($Result))
   {
    $Result = intval($Result);

    if (is_null($Result))
     {
      $Result = $DefErrValue;
     }
   }
  else
   {
    $Result = $DefErrValue;
   }

  return ($Result);
 }

function JSFldStr($InStr,$DefErrValue = '')
 {
  $Result = JSStr($InStr,$DefErrValue);

  $Result = str_replace("<","'+'<'+'",$Result);
  $Result = str_replace(">","'+'>'+'",$Result);

  return("'".$Result."'");
 }

function JSFldInt($InStr,$DefErrValue = 0)          // удаляет пробелы из начала и конца строки
 {
  return(JSInt($InStr,$DefErrValue));
 }

?>
