<?php

/*
 *  $Id: portstatusshort.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

$portstatus['ip'] = $_GET['ip'];
$portstatus['port'] = $_GET['port'];

$port = $portstatus['port'];

//vlany
$walk=snmpget($portstatus['ip'],"public","1.3.6.1.2.1.17.7.1.4.5.1.1.$port");
$counter=explode(":",$walk);
$portstatus['uvlan'] = $counter[1];

$walk=snmpwalkoid($portstatus['ip'],"public","1.3.6.1.2.1.31.1.2.1.3");
foreach ($walk as $key => $val) {
    if(preg_match("/^.*\.(\d{6})\.$port$/", $key, $vlany)) {
	$vlantmp = $vlany[1]-100000+1;
	if ($vlantmp != $portstatus['uvlan'])
	    $portstatus['tvlan'] = $portstatus['tvlan'].' '.$vlantmp;
    }
}

//in/out stats
$walk=snmpget($portstatus['ip'],"public","ifHCInOctets.$port");
$counter=explode(":",$walk);
$portstatus['iucast'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","ifInBroadcastPkts.$port");
$counter=explode(":",$walk);
$portstatus['ibcast'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","ifInMulticastPkts.$port");
$counter=explode(":",$walk);
$portstatus['imcast'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","ifHCOutOctets.$port");
$counter=explode(":",$walk);
$portstatus['oucast'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","ifOutBroadcastPkts.$port");
$counter=explode(":",$walk);
$portstatus['obcast'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","ifOutMulticastPkts.$port");
$counter=explode(":",$walk);
$portstatus['omcast'] = $counter[1];

// RMON
$walk=snmpget($portstatus['ip'],"public","mib-2.16.1.1.1.8.$port");
$counter=explode(":",$walk);
$portstatus['crc'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","mib-2.16.1.1.1.9.$port");
$counter=explode(":",$walk);
$portstatus['undersize'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","mib-2.16.1.1.1.10.$port");
$counter=explode(":",$walk);
$portstatus['oversize'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","mib-2.16.1.1.1.11.$port");
$counter=explode(":",$walk);
$portstatus['fragments'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","mib-2.16.1.1.1.12.$port");
$counter=explode(":",$walk);
$portstatus['colisions'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","mib-2.16.1.1.1.13.$port");
$counter=explode(":",$walk);
$portstatus['jabbers'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","transmission.7.2.1.16.$port");
$counter=explode(":",$walk);
$portstatus['macrcv'] = $counter[1];

//etherlike
$walk=snmpget($portstatus['ip'],"public","transmission.7.2.1.3.$port");
$counter=explode(":",$walk);
$portstatus['fcs'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","transmission.7.2.1.4.$port");
$counter=explode(":",$walk);
$portstatus['singlecolisions'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","transmission.7.2.1.8.$port");
$counter=explode(":",$walk);
$portstatus['latecolisions'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","transmission.7.2.1.9.$port");
$counter=explode(":",$walk);
$portstatus['excessivecolisions'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","transmission.7.2.1.10.$port");
$counter=explode(":",$walk);
$portstatus['oversize'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","transmission.7.10.1.3.$port");
$counter=explode(":",$walk);
$portstatus['inpauseframes'] = $counter[1];
$walk=snmpget($portstatus['ip'],"public","transmission.7.10.1.4.$port");
$counter=explode(":",$walk);
$portstatus['outpauseframes'] = $counter[1];

//status
$walk=snmpget($portstatus['ip'],"public","ifAlias.$port");
$aliastmp=explode(":",$walk);
$portstatus['alias']=trim($aliastmp[1]);
$walk=snmpget($portstatus['ip'],"public","sysName.0");
$nametmp=explode(":",$walk);
$portstatus['sysname']=trim($nametmp[1]);
$walk=snmpget($portstatus['ip'],"public","sysDescr.0");
$desctmp=explode(":",$walk);
$portstatus['sysdesc']=trim($desctmp[1]);
$walk=snmpget($portstatus['ip'],"public","ifOperStatus.$port");
$statustmp=explode(":",$walk);
$status=explode("(",$statustmp[1]);
$walk=snmpget($portstatus['ip'],"public","ifAdminStatus.$port");
$statustmp=explode(":",$walk);
$astatus=explode("(",$statustmp[1]);
$portstatus['warning']=0;
$portstatus['admindown']=0;
if (!strcmp(trim($status[0]),"up"))
{
    $walk=snmpget($portstatus['ip'],"public","ifSpeed.$port");
    $speed=explode(":",$walk);
    $walk=snmpget($portstatus['ip'],"public","transmission.7.2.1.19.$port");
    $duplextmp=explode(":",$walk);
    if($duplextmp[1]==3) $duplex="MB FullDuplex";
    if($duplextmp[1]==2) { $duplex="MB HalfDuplex"; $portstatus['warning']=1;}
    $speedtmp = $speed[1]/1000000;
    if($speedtmp == 10) $portstatus['warning']=1;
    $portstatus['speed'] = ($speed[1]/1000000).$duplex;
} else
    $portstatus['speed'] = "---";

$portstatus['status'] = trim($status[0]);
$portstatus['adminstatus'] = trim($astatus[0]);
if($portstatus['adminstatus']=="down") $portstatus['admindown']=1;

$SMARTY->assign('portstatus', $portstatus);

$SMARTY->display('portstatusshort.html');

?>
