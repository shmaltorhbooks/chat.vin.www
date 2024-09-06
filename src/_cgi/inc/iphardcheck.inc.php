<?

// "HARDWARE" IP BAN

// 1st step - fast&simple IP ban rules
// This IP address are blocked allways

$ChatHardBanIPArray = 
 array
 (
  // Nibble proxy switcher (list of anonimous proxy servers)
  '211.20.131.242','203.197.196.163','200.38.84.4','196.28.50.2','61.129.121.27','212.26.129.58','211.20.131.241','61.9.106.65','210.187.38.96','12.227.172.29','66.147.9.181','62.3.8.51','61.219.237.197','4.63.7.140','68.37.242.215','62.43.33.156','202.68.143.122','200.40.181.14','194.16.20.228','68.194.129.156','24.91.82.31','203.199.36.210','203.197.158.179','24.214.105.82','207.6.191.11','61.43.229.19','62.162.71.66','210.95.86.2','216.115.91.2','213.42.2.6','216.125.73.116','207.126.68.2','195.61.192.3','209.88.128.163','202.190.29.28','64.115.112.3','148.233.47.243','213.42.2.8','203.121.0.10','200.161.137.60','213.150.189.42','210.249.120.210','217.172.76.229','216.72.155.74','210.0.143.101','192.187.16.162','64.44.105.241','61.215.87.196','4.2.169.121','202.129.12.8','203.121.0.8','202.149.208.73','12.32.88.30','167.7.9.198','210.96.165.5','203.151.63.130','212.234.117.211','66.49.60.82','203.111.194.11','203.121.0.11','204.186.217.33','61.107.12.207','194.208.13.3','64.233.218.157',
  '68.86.2.228','68.47.232.101','67.112.113.131','66.30.170.149','65.49.139.76','64.229.225.226','61.59.45.108','61.221.15.114','61.218.141.226','61.159.155.40','4.65.25.215','218.13.50.212','217.46.204.121','216.164.229.44','211.89.186.121','210.3.251.237','203.229.240.110','203.192.201.250','202.149.208.72','200.223.149.170','196.40.9.146','196.40.75.69','192.71.137.21','163.17.64.123','148.207.179.114','12.206.108.189','12.16.40.66','67.41.128.105','64.230.79.162','61.97.138.4','4.62.70.42','24.214.247.27','218.66.15.9','216.20.10.3','203.177.63.185','202.168.193.66','200.207.77.47','164.8.14.6','66.9.124.194','62.81.236.243','24.199.19.93','194.73.151.154','164.8.14.4','217.109.159.113',
  ''
 );


// 2nd step - complex IP ban rules
// Block rules are looked from begin to end
// '[A|D]' sign show Allow/Denny rule for this IP (or range) [Defalt-Denny]
// If you want to low accres from some subnet of bigger network
// just simple add 'A' rule for subnet BEFORE rule for bigger network

$ChatHardBanIPRangeArray = // format 'XXX.XXX.XXX.XXX{-XXX.XXX.XXX.XXX}{:[A|D]}'
 array
 (
//  '192.168.4.0-192.168.4.255:A',
//  '192.168.0.0-192.168.255.255:D'
    '212.38.122.1-212.38.122.255:D' // BAKU
 );

function ChatIPHardIPEncode($dotquad_ip)
 {
  // returns IP address in form of comparable string (Hex, MBF)
  // borrowed from phpBB
  $ip_sep = explode('.', $dotquad_ip);
  return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
 }

function ChatIPHardIPDecode($int_ip)
 {
  // parses IP address from form of comparable string (Hex, MBF)
  // borrowed from phpBB
  $hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
  return hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
 }

function ChatIPHardAllowed($IP)
 {
  global $ChatHardBanIPArray;
  global $ChatHardBanIPRangeArray;

  if (in_array($IP,$ChatHardBanIPArray))
   {
    return(FALSE);
   }
  else
   {
    // Check Up complex array

    foreach ($ChatHardBanIPRangeArray as $RangeStr)
     {
      $RangeStr = trim($RangeStr);
      $IPRange  = split('-',$RangeStr);

      if      (count($IPRange) == 1)
       {
        $IPToRangeAndRule = split(':',trim($IPRange[0]));

        if      (count($IPToRangeAndRule) == 1)
         {
          // '-' or ':' not found
          $BanFromIP = trim($RangeStr);
          $BanToIP   = trim($RangeStr);
          $BanSign   = 'D';
         }
        else if (count($IPToRangeAndRule) == 2)
         {
          // '-' not found, but ':' found
          $BanFromIP = trim($IPToRangeAndRule[0]);
          $BanToIP   = trim($IPToRangeAndRule[0]);
          $BanSign   = strtoupper(trim($IPToRangeAndRule[1]));
         }
        else
         {
          // Invalid rule
          $BanFromIP = "";
          $BanToIP   = "";
          $BanSign   = "";
         }
       }
      else if (count($IPRange) == 2)
       {
        $IPToRangeAndRule = split(':',trim($IPRange[1]));

        if      (count($IPToRangeAndRule) == 1)
         {
          // '-' found, ':' not found
          $BanFromIP = trim($IPRange[0]);
          $BanToIP   = trim($IPRange[1]);
          $BanSign   = 'D';
         }
        else if (count($IPToRangeAndRule) == 2)
         {
          // '-' found, ':' found
          $BanFromIP = trim($IPRange[0]);
          $BanToIP   = trim($IPToRangeAndRule[0]);
          $BanSign   = strtoupper(trim($IPToRangeAndRule[1]));
         }
        else
         {
          $BanFromIP = "";
          $BanToIP   = "";
          $BanSign   = "";
         }
       }

      if (($BanSign != 'A') && ($BanSign != 'D'))
       {
        // invalid rule
       }
      else
       {
        $CmpIP        = ChatIPHardIPEncode($IP);
        $CmpBanFromIP = ChatIPHardIPEncode($BanFromIP);
        $CmpBanToIP   = ChatIPHardIPEncode($BanToIP);

        if ($CmpBanFromIP > $CmpBanToIP)
         {
          // swap addresses
          $CmpTmpIP     = $CmpBanFromIP;
          $CmpBanFromIP = $CmpBanToIP;
          $CmpBanToIP   = $CmpTmpIP;
         }

        /*
        // DEBUG
        ChatErrorLog
         ("IP HARD CHECK: "
         ."IP=[$IP]".","
         ."BanFromIP=[$BanFromIP],BanToIP=[$BanToIP],BanSign=[$BanSign]"
         ."CmpIP=[$CmpIP]".","
         ."CmpBanFromIP=[$CmpBanFromIP],CmpBanToIP=[$CmpBanToIP]");
        */

        if (($CmpIP >= $CmpBanFromIP) && ($CmpIP <= $CmpBanToIP))
         {
          // Match
          if      ($BanSign == 'A')
           {
            return(TRUE);
           }
          else if ($BanSign == 'D')
           {
            return(FALSE);
           }
          else
           {
            // ignore rule
           }
         }
       }
     }

    return(TRUE);
   }
 }


?>
