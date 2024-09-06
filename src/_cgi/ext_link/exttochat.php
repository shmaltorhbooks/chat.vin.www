<? /* * */ if (!defined('ExtToChat')) { die('Invalid context!'); } /* * */ ?>
<?
// Note: ALL Arguments must be in RAW mode (i.e strislashed)
//       constant 'ExtToChat' must de defined inside parent file

define('ChatInside',1);
define('ChatRootDir',dirname(__FILE__)."/"."../..");
include_once(ChatRootDir."/"."_cgi/inc/setup.inc.php");
include_once(ChatRootDir."/"."_cgi/inc/constant.inc.php");
include_once(ChatRootDir."/"."_cgi/inc/request.inc.php");
include_once(ChatRootDir."/"."_cgi/inc/db_setup.inc.php");

// Ext->Chat:����������� �������������� ����������
// ���������� ����� ���������� ������� �������������� ���������� � ����� ������
// ���������� ������� ������ ��������� ���� DBLinkId (RecourceId)
// � ���� � ���� �������������� ���������� �� ����� ��, �� �� ���������
// ���� ���������� ���� ���������� �� DBLinkId �������� � ��������� �����
// (�.�. ���� ���������� � ���������� ������� ���������� ���� ��)
function ExtToChatDBLinkIdRegister($DBLinkId)
 {
  global $ExtToChatDBLinkId;
  $ExtToChatDBLinkId = $DBLinkId;
 }

// Ext->Chat: ���������� �� ���������� ������ ������������ �� ������� �������
// Return:TRUE-������������ ����� ���� ��������
function ExtToChatUserAddIsOk
          (&$ErrorMess,
            $UserNickName,
            $UserEMail)
 {
  $Result    = FALSE;
  $ErrorMess = "Nick not allowed to register";

  include(ChatRootDir."/"."_cgi/inc/db_open.inc.php");

  if      (addslashes($UserNickName) != $UserNickName)
   {
    $ErrorMess = "Invalid Nick! Cannot contain (',\\,\")";
   }
  else if (trim($UserNickName) != $UserNickName)
   {
    $ErrorMess = "Invalid Nick! Cannot contain left or right spaces";
   }
  else if (htmlspecialchars($UserNickName) != $UserNickName)
   {
    $ErrorMess = "Invalid Nick! Cannot contain (&,\",',<,>)";
   }
  else if (!ChatStrNickStrValid($UserNickName))
   {
    $ErrorMess = "Invalid Nick";
   }
  else if (strlen(ChatNickToVisual($UserNickName)) == 0)
   {
    $ErrorMess = "Invalid Nick -- equal to empty";
   }
  else if (strlen($UserNickName) > ChatConstUserNickNameMaxLength)
   {
    $ErrorMess = "Nick too long"." (more than ".ChatConstUserNickNameMaxLength." chars)";
   }
  else if (ChatNickExists($UserNickName))
   {
    $ErrorMess = "Nick allready exists";
   }
  else
   {
    $ErrorMess = "";
    $Result = TRUE;
   }

  if (!$Result)
   {
    $ErrorMess = "SmartChat[Error]:".$ErrorMess;
   }

  global $ExtToChatDBLinkId;
  global $ChatDBLinkId;

  if ($ChatDBLinkId != $ExtToChatDBLinkId)
   {
    // close if this system and caller uses different databases
    include(ChatRootDir."/"."_cgi/inc/db_close.inc.php");
   }

  return($Result);
 }

// Ext->Chat: ����� ������� ������� ��� ���������� ������������
// Return:TRUE-������������ �������� �������
// ����������:���� ������� $UserPassword � $UserPasswordMD5
//            ����� $UserPassword ����� ��������� ��� $UserPasswordMD5
function ExtToChatUserAdd
          (&$ErrorMess,
            $UserNickName,
            $UserPassword,
            $UserPasswordMD5,
            $UserEMail)
 {
  $Result    = FALSE;
  $ErrorMess = "Nick registration not allowed"; // BUG trap

  include(ChatRootDir."/"."_cgi/inc/db_open.inc.php");

  if (!defined("ChatConstDefUserColor"))
   {
    $UserColor = "seagreen";
   }
  else
   {
    $UserColor = ChatConstDefUserColor;
   }

  if      (addslashes($UserNickName) != $UserNickName)
   {
    $ErrorMess = "Invalid Nick! Cannot contain (',\\,\")";
   }
  else if (trim($UserNickName) != $UserNickName)
   {
    $ErrorMess = "Invalid Nick! Cannot contain left or right spaces";
   }
  else if (htmlspecialchars($UserNickName) != $UserNickName)
   {
    $ErrorMess = "Invalid Nick! Cannot contain (&,\",',<,>)";
   }
  else if (!ChatStrNickStrValid($UserNickName))
   {
    $ErrorMess = "Invalid Nick";
   }
  else if (strlen(ChatNickToVisual($UserNickName)) == 0)
   {
    $ErrorMess = "Invalid Nick -- equal to empty";
   }
  else if (strlen($UserNickName) > ChatConstUserNickNameMaxLength)
   {
    $ErrorMess = "Nick too long"." (more than ".ChatConstUserNickNameMaxLength." chars)";
   }
/*
  else if (addslashes($UserPassword) != $UserPassword)
   {
    // Note: block resync with forum (here md5 calc is done with slashed password)
    $ErrorMess = "Password invalid! Cannot contain (',\\,\")";
   }
*/
  else if (trim($UserPassword) != $UserPassword)
   {
    $ErrorMess = "Password invalid! Cannot contain left or right spaces";
   }
  else if (((is_null($UserPassword)) || ($UserPassword == "")) &&
           ((is_null($UserPasswordMD5)) || ($UserPasswordMD5 == "")))
   {
    $ErrorMess = "Password information not found, possible blank password";
   }
  else if (ChatNickExists($UserNickName))
   {
    $ErrorMess = "Nick allready exists";
   }
  else
   {
    if (is_null($UserPassword) || ($UserPassword == ""))
     {
      $UserPassword = "";

      if (is_null($UserPasswordMD5) || ($UserPasswordMD5 == ""))
       {
        $UserPasswordMD5 = "";
       }
     }
    else
     {
      $UserPasswordMD5 = md5($UserPassword);
     }

    if (!ChatUserAdd($UserNickName,$UserPassword,$UserEMail,$UserColor,ChatConstUserGenderUndefined,"",$UserPasswordMD5))
     {
      $ErrorMess = "Failed to register new nick. Try another username";
     }
    else
     {
      $ErrorMess = "";
      $Result = TRUE;
     }
   }

  if (!$Result)
   {
    $ErrorMess = "SmartChat[Error]:".$ErrorMess;
    /*
    // DEBUG
    $ErrorMess .= " Nick:"."'".$UserNickName."'";
    $ErrorMess .= " Password:"."'".$UserPassword."'";
    $ErrorMess .= " EMail:"."'".$UserEMail."'";
    */
   }

  global $ExtToChatDBLinkId;
  global $ChatDBLinkId;

  if ($ChatDBLinkId != $ExtToChatDBLinkId)
   {
    // close if this system and caller uses different databases
    include(ChatRootDir."/"."_cgi/inc/db_close.inc.php");
   }

  return($Result);
 }

// Ext->Chat: ����� ������� ������� ��� ��������� ���������� ������������
// TRUE-������������ �������� �������
// ����������:���� ������� $UserPasswordNew � $UserPasswordNewMD5
//            ����� $UserPasswordNew ����� ��������� ��� $UserPasswordNewMD5
// ����������:�������� null � �������� ��������������� ���������
//            ������������ ��� �����������, �������� �� �����������
//            ��� ���� �������� �� ��������, �� ����� ������� null
function ExtToChatUserUpdate
          (&$ErrorMess,
            $UserNickNameSrc,
            $UserNickNameNew,    // null ���� ���         �� ���������
            $UserPasswordNew,    // null ���� Password    �� ���������
            $UserPasswordNewMD5, // null ���� PasswordMD5 �� ���������
            $UserEMailNew)       // null ����             �� ���������
 {
  $Result    = FALSE;
  $ErrorMess = "Nick update not allowed";

  include(ChatRootDir."/"."_cgi/inc/db_open.inc.php");

  if      (!ChatStrNickStrValid($UserNickNameSrc))
   {
    $ErrorMess = "Invalid Source Nick";
   }
  else if ((!is_null($UserNickNameNew)) && (addslashes($UserNickNameNew) != $UserNickNameNew))
   {
    $ErrorMess = "Invalid New Nick! Cannot contain (',\\,\")";
   }
  else if ((!is_null($UserNickNameNew)) && (trim($UserNickNameNew) != $UserNickNameNew))
   {
    $ErrorMess = "Invalid New Nick! Cannot contain left or right spaces";
   }
  else if ((!is_null($UserNickNameNew)) && (htmlspecialchars($UserNickNameNew) != $UserNickNameNew))
   {
    $ErrorMess = "Invalid New Nick! Cannot contain (&,\",',<,>)";
   }
  else if ((!is_null($UserNickNameNew)) &&
           (!ChatStrNickStrValid($UserNickNameNew)))
   {
    $ErrorMess = "Invalid New Nick";
   }
  else if ((!is_null($UserNickNameNew)) &&
           (strlen(ChatNickToVisual($UserNickNameNew)) == 0))
   {
    $ErrorMess = "Invalid New Nick -- equal to empty";
   }
  else if ((!is_null($UserNickNameNew)) && (strlen($UserNickNameNew) > ChatConstUserNickNameMaxLength))
   {
    $ErrorMess = "New Nick too long"." (more than ".ChatConstUserNickNameMaxLength." chars)";
   }
/*
  else if ((!is_null($UserPasswordNew)) && 
           (addslashes($UserPasswordNew) != $UserPasswordNew))
   {
    // Note: block resync with forum (here md5 calc is done with slashed password)
    $ErrorMess = "Password invalid! Cannot contain (',\\,\")";
   }
*/
  else if ((!is_null($UserPasswordNew)) && 
           (trim($UserPassword) != $UserPassword))
   {
    $ErrorMess = "Password invalid! Cannot contain left or right spaces";
   }
  else if (!ChatNickExists($UserNickNameSrc))
   {
    $ErrorMess = "Source Nick not found";
   }
  else if ((!is_null($UserNickNameNew)) && 
           ($UserNickNameNew != "")     &&
           (!ChatNickEqual($UserNickNameSrc,$UserNickNameNew)))
   {
    $ErrorMess = "Source and new Nick must be visualy equal";
   }
  else if (!ChatUserRecall($UserNickNameSrc,$U_Nick,$U_PasswordMD5,$U_Color,$U_EMail,$U_Gender,$U_SelfNotes))
   {
    $ErrorMess = "Failed to recall nick information";
   }
  else
   {
    if (is_null($UserEMailNew))
     {
      $UserEMailNew = $U_EMail;
     }

    if ((is_null($UserNickNameNew))    ||
        ($UserNickNameNew == "")       ||
        ($UserNickNameNew == $U_Nick))
     {
      $UserNickNameNew = $U_Nick;
     }

    if (is_null($UserPasswordNew) || ($UserPasswordNew == ""))
     {
      $UserPasswordNew = "";

      if (is_null($UserPasswordNewMD5) || ($UserPasswordNewMD5 == ""))
       {
        $UserPasswordNewMD5 = "";
       }
     }
    else
     {
      $UserPasswordNewMD5 = md5($UserPasswordNew);
     }

    if (!ChatUserUpdate($U_Nick,$UserNickNameNew,"",$U_Color,$UserEMailNew,$U_Gender,$U_SelfNotes,$UserPasswordNewMD5))
     {
      $ErrorMess = "Failed to update nick";
     }
    else
     {
      $ErrorMess = "";
      $Result = TRUE;
     }
   }

  if (!$Result)
   {
    $ErrorMess = "SmartChat[Error]:".$ErrorMess;
   }

  global $ExtToChatDBLinkId;
  global $ChatDBLinkId;

  if ($ChatDBLinkId != $ExtToChatDBLinkId)
   {
    // close if this system and caller uses different databases
    include(ChatRootDir."/"."_cgi/inc/db_close.inc.php");
   }

  return($Result);
 }

// Ext->Chat: ����� ������� ������� ��� �������� ������������
// TRUE-������������ ������ �������
function ExtToChatUserDelete
          (&$ErrorMess,
            $UserNickName)
 {
  $Result    = FALSE;
  $ErrorMess = "Nick delete not allowed";

  include(ChatRootDir."/"."_cgi/inc/db_open.inc.php");

  if (!ChatUserDelete($UserNickName))
   {
    $ErrorMess = "Failed to delete nick";
   }
  else
   {
    $ErrorMess = "";
    $Result = TRUE;
   }

  if (!$Result)
   {
    $ErrorMess = "SmartChat[Error]:".$ErrorMess;
   }

  global $ExtToChatDBLinkId;
  global $ChatDBLinkId;

  if ($ChatDBLinkId != $ExtToChatDBLinkId)
   {
    // close if this system and caller uses different databases
    include(ChatRootDir."/"."_cgi/inc/db_close.inc.php");
   }

  return($Result);
 }

// Ext->Chat: ����� ������� ������� ��� ������� ���������� � ������������
// ������ ������� ���������� ���������� ���� � ������� 
//  &$ ������� ���������
//   $ ���� ���� ��� ��� ���������� ����� �������
// TRUE-���������� ��������� �������
//      (���������� ������������ � ���� ������ �� �������� �������!)
//      ������������ $UserExistsFlag ������ FALSE (��������� ������ 
//      �� ����������)
//      ������ $UserStats ����������� � ����
//       $UserStats['*']['value'][$Field1] = $Value1
//       $UserStats['*']['descr'][$Field1] = 'logical description of Field1' (optional)
//       $UserStats['*']['value'][$Field2] = $Value2
//       $UserStats['*']['descr'][$Field2] = 'logical description of Field2' (optional)
//      ������ $UserDBRecord ����������� � ����
//       $UserDBRec['*']['*'] = row of 'root' user table for system
//       $UserDBRec['*'][$AddOnTable] = row of any add on table (optional)

function ExtToChatUserGetStats
          (&$ErrorMess,
            $UserNickName,
           &$UserExistsFlag,
            $UserGetExistsFlag    = true,
           &$UserStats,
            $UserGetStatsFlag     = false,
           &$UserDBRecord,
            $UserGetDBRecordFlag  = false)
 {
  $Result    = FALSE;
  $ErrorMess = "Function not implemented";

  if ($UserGetExistsFlag)
   {
    $UserExistsFlag = false;
   }

  if ($UserGetStatsFlag)
   {
    $UserStats = array();
   }

  if ($UserGetDBRecordFlag)
   {
    $UserDBRecord = array();
   }

  // *** Action BEGIN
  // *** Action END

  return($Result);
 }


// Ext->Chat: ����� ������� ������� ��� ������� ���������� � ������� � �����
// ������ ������� ���������� ���������� ���� � ������� 
//  &$ ������� ���������
//   $ ���� ���� ��� ��� ���������� ����� �������
// TRUE-���������� ��������� �������
function ExtToChatSystemGetStats
          (&$ErrorMess,
           &$SystemStats,
            $SystemGetStatsFlag     = false,
           &$SystemData,
            $SystemGetDataFlag      = false)
 {
  $Result    = FALSE;
  $ErrorMess = "Function not implemented";

  if ($SystemGetStatsFlag)
   {
    $SystemStats = array();
   }

  if ($SystemGetDataFlag)
   {
    $SystemData = array();
   }

  // *** Action BEGIN
  // *** Action END

  return($Result);
 }

?>