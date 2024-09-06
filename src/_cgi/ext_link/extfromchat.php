<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
// Note: ALL Arguments must be in RAW mode (i.e stripslashed)

// Chat->Ext:Регистрация идентификатора соединения
// Вызывается когда вызывающая система инициализирует соединение с базой данных
// Вызываемая система должна запомнить этот DBLinkId (RecourceId)
// И если в сама инициализирует соединение со своей БД, то не закрывать
// свое соединение если полученный ей DBLinkId совпадет с указанным здесь
// (т.е. если вызывающая и вызываемая система используют одну БД)
function ExtFromChatDBLinkIdRegister($DBLinkId)
 {
  global $ExtFromChatDBLinkId;
  $ExtFromChatDBLinkId = $DBLinkId;
 }

// Chat->Ext: Разрешение на добавление нового пользователя от внешней системы
// Return:TRUE-Пользователь может быть добавлен
function ExtFromChatUserAddIsOk
          (&$ErrorMess,
            $UserNickName,
            $UserEMail)
 {
  return(true); // Dummy
 }

// Chat->Ext: Вызов внешней системы при добавлении пользователя
// Return:TRUE-Пользователь добавлен успешно
// Примечание:Если указано $UserPassword и $UserPasswordMD5
//            Тогда $UserPassword имеет приоритет над $UserPasswordMD5
function ExtFromChatUserAdd
          (&$ErrorMess,
            $UserNickName,
            $UserPassword,
            $UserPasswordMD5,
            $UserEMail)
 {
  return(true); // Dummy
 }

// Chat->Ext: Вызов внешней системы при изменении информации пользователя
// TRUE-Пользователь обновлен успешно
// Примечание:Если указано $UserPasswordNew и $UserPasswordNewMD5
//            Тогда $UserPasswordNew имеет приоритет над $UserPasswordNewMD5
// Примечание:Передача null в качестве неизменившегося параметра
//            используется для оптимизации, протокол не гарантирует
//            что если параметр не меняется, то будет передан null
function ExtFromChatUserUpdate
          (&$ErrorMess,
            $UserNickNameSrc,
            $UserNickNameNew,    // null если ник         не изменился
            $UserPasswordNew,    // null если Password    не изменился
            $UserPasswordNewMD5, // null если PasswordMD5 не изменился
            $UserEMailNew)       // null если             не изменился
 {
  return(true); // Dummy
 }

// Chat->Ext: Вызов внешней системы при удалении пользователя
// TRUE-Пользователь удален успешно
function ExtFromChatUserDelete
          (&$ErrorMess,
            $UserNickName)
 {
  return(true); // Dummy
 }

// Chat->Ext: Вызов внешней системы для запроса информации о пользователе
// Каждая единица запрошеной информации идет в порядке 
//  &$ целевая перменная
//   $ флаг того что эту информацию нужно вернуть
// TRUE-Информация запрошена успешно
//      (отсутствие пользователя в базе данных не является ошибкой!)
//      возвращается $UserExistsFlag равный FALSE (остальные данные 
//      не опрееделны)
//      Массив $UserStats сформирован в виде
//       $UserStats['*']['value'][$Field1] = $Value1
//       $UserStats['*']['descr'][$Field1] = 'logical description of Field1' (optional)
//       $UserStats['*']['value'][$Field2] = $Value2
//       $UserStats['*']['descr'][$Field2] = 'logical description of Field2' (optional)
//      Массив $UserDBRecord сформирован в виде
//       $UserDBRec['*']['*'] = row of 'root' user table for system
//       $UserDBRec['*'][$AddOnTable] = row of any add on table (optional)

function ExtFromChatUserGetStats
          (&$ErrorMess,
            $UserNickName,
           &$UserExistsFlag,
            $UserGetExistsFlag    = true,
           &$UserStats,
            $UserGetStatsFlag     = false,
           &$UserDBRecord,
            $UserGetDBRecordFlag  = false)
 {
  $ErrorMess = "";

  if ($UserGetExistsFlag)
   {
    $UserExistsFlag = null; // undefined
   }

  if ($UserGetStatsFlag)
   {
    $UserStats = array();
   }

  if ($UserGetDBRecordFlag)
   {
    $UserDBRecord = array();
   }

  return(true);
 }


// Chat->Ext: Вызов внешней системы для запроса информации о системе в целом
// Каждая единица запрошеной информации идет в порядке 
//  &$ целевая перменная
//   $ флаг того что эту информацию нужно вернуть
// TRUE-Информация запрошена успешно
function ExtFromChatSystemGetStats
          (&$ErrorMess,
           &$SystemStats,
            $SystemGetStatsFlag    = false,
           &$SystemData,
            $SystemGetDataFlag     = false)
 {
  $ErrorMess = "";

  if ($SystemGetStatsFlag)
   {
    $SystemStats = array();
   }

  if ($SystemGetDataFlag)
   {
    $SystemData = array();
   }

  return(true);
 }

?>