<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<body>
<? 
include_once(dirname(__FILE__)."/"."../inc/constant.inc.php"); 
include_once(dirname(__FILE__)."/"."../inc/functions.inc.php"); 
include_once(dirname(__FILE__)."/"."../inc/support.inc.php");
?>

<script language="JavaScript">
<!-- hide

function ChatAdminMess(Text)
 {
  document.writeln(Text+"<br>");
 }

function ChatAdminACmd(Level,Command,Desc,ArgDesc)
 {
  document.writeln("(������� "+Level+") "+Command+" - "+ArgDesc+" "+Desc+"<br>");
 }

function ChatAdminHelp(User_AdminFlag)
 {
  if (User_AdminFlag > 0)
   {
    ChatAdminMess(" = ������� = ");
    ChatAdminACmd(1,"HELP","�������� ��������� ������","");
    ChatAdminACmd(1,"LADM","������� ������ ���� � ���� ���� ���������� ��������������","");

    ChatAdminMess(" = �������� � ������ = ");
    ChatAdminACmd(1,"OUT ","������������� ���� �� ���� [+/MLF]","");

    ChatAdminMess(" = ����������� �������������� = ");
    ChatAdminACmd(1,"WARN","������������ ��� (����������� ���������)","");
    ChatAdminACmd(1,"WHAT","������������ ��� (�����������)","");
    ChatAdminACmd(1,"WFLD","������������ ��� (����)","");

    ChatAdminMess(" = ������ � ������ = ");
    ChatAdminACmd(1,"MLM" ,"�������� ��� ������ ���� ����","");
    ChatAdminACmd(1,"MLF" ,"�������� ��� ������ ���� ���� + ������ �����<->������� ���","");
    ChatAdminACmd(1,"MCAT","������� ������� (�-��) �����","{DD/MM/YYYY} {[+/-]�-�� ����}");
    ChatAdminACmd(1,"MLST","������� ���� �� ���� (+/-���)","{DD/MM/YYYY} {[+/-]�-�� ����}");
    ChatAdminACmd(1,"MVIEW","������� ��������� ��� �� ������� LogId ��� � ������","[LogId]");
    ChatAdminMess("*** ����������: ��� ��������� �������� ���� ������� �� ������ "+User_AdminFlag+" ������������");
   }

  if (User_AdminFlag >= 2)
   {
    ChatAdminMess(" = ���������� � ����� = ");
    ChatAdminACmd(2,"IP  ","����� IP ����","");
    ChatAdminACmd(2,"STAT","����� ���������� ����","");

    ChatAdminMess(" = �������� ������� ���������� = ");
    ChatAdminACmd(2,"LBAN","�������� /BAN ������� ������������ �����","");
    ChatAdminACmd(2,"LBIP","�������� /BIP ������� ������������ IP","");
   }

  if (User_AdminFlag >= 3)
   {
    ChatAdminMess(" = ������ � ������������ = ");
    ChatAdminACmd(3,"BAN","���������� ���� [+/MLF]","{Type} {Time}");
    ChatAdminACmd(3,"BIP","���������� IP ���� [+/MLF]","{Type} {Time}");
    ChatAdminACmd(3,"UNBAN","������ ������� �� ��� [*]","");
    ChatAdminACmd(3,"UNBIP","������ ������� �� IP ���� [*]","");
    ChatAdminMess(" Type:");
    ChatAdminMess(" '"+'<?=ChatConstBanModelNoRegIn    ?>'+"' ������ �����������");
    ChatAdminMess(" '"+'<?=ChatConstBanModelNoLogIn    ?>'+"' ������ ������");
    ChatAdminMess(" '"+'<?=ChatConstBanModelNoStdMess  ?>'+"' ������ ����� ��������� (������ ������)");

    if (User_AdminFlag >= 4)
     {
      ChatAdminMess(" '"+'<?=ChatConstBanModelNoAnyMess?>'+ "' ������ ����� ��������� (������ ��������)");
      ChatAdminMess(" '"+'<?=ChatConstBanModelNoAnyReq?>'+  "' ������ ����� �������� (������ ����)");
     }

    ChatAdminMess(" '"+"*"+"' (��� �� �������) = �� ���������:"
                            +'<?=ChatConstBanModelNoRegIn?>'+" ��� /BIP ,"
                            +'<?=ChatConstBanModelNoStdMess?>'+" ��� /BAN");
    ChatAdminMess(" Time: ����� �� ������� ����������� ���/IP � �������. '*'(��� �� �������) = �� ���������:10 �����");
    ChatAdminMess(" �������:"
                 +" '/BAN' = '/BAN * *' = '/BAN "+'<?=ChatConstBanModelNoStdMess?>'+" 10'"
                 +" ��� "
                 +" '/BIP' = '/BIP * *' = '/BIP "+'<?=ChatConstBanModelNoRegIn?>'+" 10'");

    ChatAdminMess(" = ���������� �������� � ������������ ������ = ");
    ChatAdminACmd(3,"LIP"  ,"�������� ���� ����� ���� IP (����������� �� IP) [*]","");
    ChatAdminACmd(3,"LMD5" ,"�������� ���� ����� ���� ������� ������� �� MD5 (��������.�� MD5) [*]","");
  //ChatAdminACmd(3,"LMAIL","�������� ���� ����� ���� EMail ������� (��������.�� EMail) [*]","");
   }

  if (User_AdminFlag >= 4)
   {
    ChatAdminMess(" = ����������� �������� � ������(+) = ");
    ChatAdminACmd(4,"BANJS","������� ������� JavaScript �������������� [+/MLF]","");
    ChatAdminACmd(4,"MUTE" ,"������������ ���� �� ���� (������ ��������) ��� ������ ��������� [+/MLF]","");
    ChatAdminACmd(4,"INFO" ,"����� Info ������������ [*]","");

    ChatAdminMess(" = �������� � ����� ������������� = ");
    ChatAdminACmd(4,"KILL" ,"������������� ���� �� ���� � �������� �� ���� ������ [*]","");
    ChatAdminACmd(4,"LOCK" ,"���������� ����������� ���� (��������� ������� ������) [*]","");
    ChatAdminACmd(4,"REPSW","��������� ���� ���c.������ '"+'<?=$CmdAdminRemortPassword?>'+"' (��� �������� ������) [*]","");

    ChatAdminMess(" = ��������� ������� = ");
    ChatAdminACmd(4,"SIP"  ,"����� ���� � ����� �� IP ��� � ���������� ���� (�� ����� "+'<?=$CmdAdminDefaultLimit?>'+")","");
    ChatAdminACmd(4,"SMAIL","����� ���� � ����� �� EMail ��� � ���������� ���� (�� ����� "+'<?=$CmdAdminDefaultLimit?>'+")","");
    ChatAdminACmd(4,"SMD5" ,"����� ���� � ����� �� MD5 ��� � ���������� ���� (�� ����� "+'<?=$CmdAdminDefaultLimit?>'+")","");
    ChatAdminACmd(4,"SMIP" ,"����� ���� � ����� �� MD5 � IP ��� � ���������� ���� (�� ����� "+'<?=$CmdAdminDefaultLimit?>'+")","");

    ChatAdminMess(" = ����������/���������������� ���������� = ");
    ChatAdminACmd(4,"DINF" ,"������� ������������ ������ ����","");
    ChatAdminACmd(4,"DPLS" ,"������� � ������� mySql","");
   }

  if (User_AdminFlag >= 5)
   {
    ChatAdminMess(" = ���������� ������������ = ");
    ChatAdminACmd(5,"ADM?","���� ���������� ��������������. ������ /ADM{LEVEL} [*]","");
    ChatAdminACmd(5,"ADM-","������ ���������� �������������� (���������� /ADM0) [*]","");
   }
 }

// -->
</script>

<b>������ ������</b><br>

<script language="JavaScript">
ChatAdminHelp(5);
</script>

</body>
</html>
