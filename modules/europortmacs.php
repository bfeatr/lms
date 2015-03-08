<?php

/*
 *  $Id: portmacs.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

$portmacs['ip'] = $_GET['ip'];
$portmacs['port'] = $_GET['port'];

$port = $portmacs['port'];

// sprawdzam, z czym mamy do czynienia
$walk=system("snmpwalk -v2c -Cc -c public $portmacs[ip] sysDescr.0 >/dev/null");
$counter=explode(":",$walk);
$portmacs['type'] = trim($counter[1]);

if(strstr($portmacs['type'],"ISCOM"))
{
    $walk=system("snmpwalk -v2c -Cc -c public $portmacs[ip] mib-2.17.7.1.2.2.1.2");
    $klucz=array_keys($walk,"INTEGER: $port");
    $idx=0;

    foreach($klucz as $linia)
    {
	$mib = explode("1.3.6.1.2.1.17.7.1.2.2.1.3.",$linia);

	$vlantmp = explode(".",$mib[1]);
	$vlan[$idx] = $vlantmp[0];
	$macs[$idx] = strtoupper(str_pad(dechex($vlantmp[1]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($vlantmp[2]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($vlantmp[3]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($vlantmp[4]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($vlantmp[5]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($vlantmp[6]),2,"0",STR_PAD_LEFT));
	$idx++;
    }
    $portmacs['total'] = count($macs);


} else
{
    $walk=snmpwalkoid($portmacs['ip'],"public","mib-2.17.7.1.2.2.1.2");
    $klucz=array_keys($walk,"INTEGER: $port");
    $idx=0;
    foreach($klucz as $linia)
    {
	$mib = explode("mib-2.17.7.1.2.2.1.2.",$linia);
	$vlantmp = explode(".",$mib[1]);
	$vlan[$idx] = $vlantmp[0];
	$macs[$idx] = strtoupper(str_pad(dechex($vlantmp[1]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($vlantmp[2]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($vlantmp[3]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($vlantmp[4]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($vlantmp[5]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($vlantmp[6]),2,"0",STR_PAD_LEFT));
	$idx++;
    }
    $portmacs['total'] = count($macs);
}
/* sposób na same maki
$walk=snmpwalkoid($portmacs['ip'],"public","mib-2.17.4.3.1.2");
$klucz=array_keys($walk,"INTEGER: $port");
$idx=0;
foreach($klucz as $linia)
{
    $mib=explode("mib-2.17.4.3.1.2.",$linia);
    $mactab=explode(".",$mib[1]);
    var_dump( $mactab);
    $macs[$idx] = strtoupper(str_pad(dechex($mactab[0]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($mactab[1]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($mactab[2]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($mactab[3]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($mactab[4]),2,"0",STR_PAD_LEFT)).':'.
			strtoupper(str_pad(dechex($mactab[5]),2,"0",STR_PAD_LEFT));
    $idx++;
}
$portmacs['total'] = count($macs);
*/

$SMARTY->assign('portmacs', $portmacs);
$SMARTY->assign('macs', $macs);
$SMARTY->assign('vlan', $vlan);

$SMARTY->display('euro/portmacsshort.html');
?>
