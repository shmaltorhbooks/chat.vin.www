<? /* * */ if (!defined('ChatInside')) { die('Invalid context!'); } /* * */ ?>
<?
// Note: ALL Arguments must be in RAW mode (i.e stripslashed)

// Chat->Ext:����������� �������������� ����������
// ���������� ����� ���������� ������� �������������� ���������� � ����� ������
// ���������� ������� ������ ��������� ���� DBLinkId (RecourceId)
// � ���� � ���� �������������� ���������� �� ����� ��, �� �� ���������
// ���� ���������� ���� ���������� �� DBLinkId �������� � ��������� �����
// (�.�. ���� ���������� � ���������� ������� ���������� ���� ��)
function ExtFromChatDBLinkIdRegister($DBLinkId)
 {
  global $ExtFromChatDBLinkId;
  $ExtFromChatDBLinkId = $DBLinkId;
 }

// Chat->Ext: ���������� �� ���������� ������ ������������ �� ������� �������
// Return:TRUE-������������ ����� ���� ��������
function ExtFromChatUserAddIsOk
          (&$ErrorMess,
            $UserNickName,
            $UserEMail)
 {
  return(true); // Dummy
 }

// Chat->Ext: ����� ������� ������� ��� ���������� ������������
// Return:TRUE-������������ �������� �������
// ����������:���� ������� $UserPassword � $UserPasswordMD5
//            ����� $UserPassword ����� ��������� ��� $UserPasswordMD5
function ExtFromChatUserAdd
          (&$ErrorMess,
            $UserNickName,
            $UserPassword,
            $UserPasswordMD5,
            $UserEMail)
 {
  return(true); // Dummy
 }

// Chat->Ext: ����� ������� ������� ��� ��������� ���������� ������������
// TRUE-������������ �������� �������
// ����������:���� ������� $UserPasswordNew � $UserPasswordNewMD5
//            ����� $UserPasswordNew ����� ��������� ��� $UserPasswordNewMD5
// ����������:�������� null � �������� ��������������� ���������
//            ������������ ��� �����������, �������� �� �����������
//            ��� ���� �������� �� ��������, �� ����� ������� null
function ExtFromChatUserUpdate
          (&$ErrorMess,
            $UserNickNameSrc,
            $UserNickNameNew,    // null ���� ���         �� ���������
            $UserPasswordNew,    // null ���� Password    �� ���������
            $UserPasswordNewMD5, // null ���� PasswordMD5 �� ���������
            $UserEMailNew)       // null ����             �� ���������
 {
  return(true); // Dummy
 }

// Chat->Ext: ����� ������� ������� ��� �������� ������������
// TRUE-������������ ������ �������
function ExtFromChatUserDelete
          (&$ErrorMess,
            $UserNickName)
 {
  return(true); // Dummy
 }

// Chat->Ext: ����� ������� ������� ��� ������� ���������� � ������������
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


// Chat->Ext: ����� ������� ������� ��� ������� ���������� � ������� � �����
// ������ ������� ���������� ���������� ���� � ������� 
//  &$ ������� ���������
//   $ ���� ���� ��� ��� ���������� ����� �������
// TRUE-���������� ��������� �������
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