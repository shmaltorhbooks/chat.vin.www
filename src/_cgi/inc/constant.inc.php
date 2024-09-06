<?
  define("ChatConstMessTimeOutInMin"        ,10); // Time to store recived mess in database
  define("ChatConstUserActivityTimeOutInMin",15); // If no resuests drop session
  define("ChatConstSIDMaxLength"            ,20);
  define("ChatConstUserNickNameMaxLength"   ,15);

  define("ChatConstMessHugeTimeOutInDays",90); // Any message no more than

  define("ChatConstDefaultChatRoom","�������");

  define("ChatConstUserGenderMale"     ,"M");
  define("ChatConstUserGenderFemale"   ,"F");
  define("ChatConstUserGenderUndefined","U"); // May not be used here (default)

  define("ChatConstDefUserColor","seagreen"); // Default user color if undefined

  define("ChatConstMessModelChat"   ,0);
  define("ChatConstMessModelJoin"   ,1);
  define("ChatConstMessModelLeave"  ,2);
  define("ChatConstMessModelURL"    ,3);
  define("ChatConstMessModelPrivate",9);

  // ������� (�� �������) ��� ������� ���� ������� "MessPvt"
  define("ChatConstClientMessPvtSignPrivate",1);
  define("ChatConstClientMessPvtSignChat",0);

  // ��������� � ������ ������� ���������� � ������ � ���� �������
  define("ChatConstMessModelPrivateForClient",0);

  // ����������� �� ���������� ������������
  define("ChatConstSelfNotesMaxLength",10240); // In Bytes
  define("ChatConstNickNotesMaxLength",10240); // In Bytes

  // ��� ��������� �� �������� ���������� ���� HTML ������
  define("ChatConstPureAdminNick","Admin");

  // �������� ��������� ������� ��������� �� 1 ��� ��� ������� ������������� �������
  define("ChatConstPurgeMessMaxDelCount",20); // Note:PurgeProb >= 1/(value)

  define("ChatConstProbMaxValue",100); // ����������� �������� � ���������

  // ����������� ������ ������� ������� ��� ������
  // �������� ������� 
  // (��� �������� � 80 ������� 100 �������� �������� �� 10 ������
  //  �.� 1 ������� AnyReq ����� �������� 10 �������� �������)
  define("ChatConstProbPurgeTimeOutUsersAnyReq"     ,  0.1);
  define("ChatConstProbPurgeTimeOutUsersSendPvtMess",  2.5);
  define("ChatConstProbPurgeTimeOutUsersSendStdMess",  2.5);
  define("ChatConstProbPurgeTimeOutUsersSetsLogIn"  ,  1.0);
  define("ChatConstProbPurgeTimeOutUsersSetsLogOut" ,  1.0);
  define("ChatConstProbPurgeTimeOutUsersChatLogIn"  ,100.0);
  define("ChatConstProbPurgeTimeOutUsersChatLogOut" ,100.0);

  define("ChatConstProbPurgeTimeOutMessAnyReq"      ,  0.1);
  define("ChatConstProbPurgeTimeOutMessSendPvtMess" ,  5.5); //!Every 20+...th
  define("ChatConstProbPurgeTimeOutMessSendStdMess" ,  1.0);
  define("ChatConstProbPurgeTimeOutMessSetsLogIn"   ,  1.0);
  define("ChatConstProbPurgeTimeOutMessSetsLogOut"  ,  1.0);
  define("ChatConstProbPurgeTimeOutMessChatLogIn"   , 10.0);
  define("ChatConstProbPurgeTimeOutMessChatLogOut"  , 10.0);

  define("ChatConstProbPurgeMessAdmLogAtLogWrite"   ,  4.0); //every 25 fires
  define("ChatConstProbPurgeTimeOutBanChatLogIn"    ,  1.0); //������ 100 �������

  // ����� �������

  define("ChatConstSessionFlagPreparing",1); // Waits for first update request
  define("ChatConstSessionFlagActive",2);

  // ���� Ban_Model
  define("ChatConstBanModelNoRegIn"  ,"R"); // ������ �����������
  define("ChatConstBanModelNoLogIn"  ,"L"); // ������ ������
  define("ChatConstBanModelNoStdMess","P"); // ������ ����� ��������� (������ ������)
  define("ChatConstBanModelNoAnyMess","V"); // ������ ����� ��������� (������ ��������)
  define("ChatConstBanModelNoAnyReq" ,"F"); // ������ ����� �������� (������ ����)

  // ����������� Server-Side FloodProtection
  // ������ ���� "�����" (�.� �������� ������ ���� ������ ��� �����)
  // ��� �� ���������� ������� (��. /chat/c_input.js)
  // ��� ������� �� 20% 
  // (��� ��� � ���������� �������� � ���� ��������� ����� ��������� "�������")
  define("ChatFloodProtectMaxMessCount",15);
  define("ChatFloodProtectMinTimeInSec",30);
  // ���� ���� ���� ��������� - �� ������� ������������ �������� �����
  define("ChatFloodProtectAtServerActiveFlag",1);

  define('ChatMinRatingToDisplayAtLogin',50);
  define('ChatMinRatingToUserInfoShow',50);
  define('ChatMinRatingMyInfoShow',-999999); // Show me virtual any my rating

  // ������ ������ ���������� ��������� (������� ������������)
  define('ChatMessTextAdminCommandPrefixChar','/');

?>
