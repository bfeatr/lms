<?php

/*
 *  $Id: netdevinfoshort.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

$ipaddr = $_GET['ip'];

$nodeid = $LMS->GetNodeIDByIP($ipaddr);
$node = $LMS->GetNode($nodeid);
$netdevinfo = $LMS->GetNetDev($node['netdev']);
$SMARTY->assign('netdevinfo',$netdevinfo);
$SMARTY->display('netdevinfoshort.html');

?>
