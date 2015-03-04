<?php

/*
 *  $Id: macinfo.php,v 1.0 2010/02/12 00:00:00 emers Exp $
*/

$macinfo = $LMS->GetMac($_GET['id'],$_GET['t']);
$nodeid = $LMS->GetNodeIDByMAC($macinfo['mac']);
$node = $LMS->GetNode($nodeid);
$netdevinfo = $LMS->GetNetDev($node['netdev']);

if( $node['ownerid'])
{
    $customer = $LMS->GetCustomer($node['ownerid']);
    $macinfo['customerid'] = $customer['id'];
    $linkname = $customer['customername'];
    $linkto = 0;
} else if( $node['netdev'])
{
    $netdev = $LMS->GetNetDev($node['netdev']);
    $macinfo['nodeid'] = $netdev['id'];
    $linkname = $netdev['name'].' ('.$netdev['producer'].')';
    $linkto = 1;
} else
{
    $linkname = '';
    $linkto = 2;
}

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$layout['pagetitle'] = trans('MAC address info');

$macinfo['t'] = $_GET['t'];

$SMARTY->assign('macinfo',$macinfo);
$SMARTY->assign('linkname',$linkname);
$SMARTY->assign('linkto',$linkto);
$SMARTY->assign('t',$_GET['t']);
$SMARTY->assign('netdevinfo',$netdevinfo);
$SMARTY->display('euro/macinfo.html');

?>
                              