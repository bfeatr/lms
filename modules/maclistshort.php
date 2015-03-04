<?php

/*
 *  $Id: maclistshort.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

$macsearch['mac'] = $_GET['mac'];
$macsearch['ip'] = $_GET['ip'];
$macsearch['port'] = $_GET['port'];

$maclist = $LMS->GetMacList('mac,asc', 1, $macsearch, 'AND', 0, 10);

$listdata['all'] = $maclist['all'];
$listdata['total'] = $maclist['total'];
$listdata['direction'] = $maclist['direction'];
$listdata['order'] = $maclist['order'];
$listdata['state'] = $maclist['state'];
$listdata['below'] = $maclist['below'];
$listdata['over'] = $maclist['over'];

unset($maclist['all']);
unset($maclist['total']);
unset($maclist['state']);
unset($maclist['direction']);
unset($maclist['order']);
unset($maclist['below']);
unset($maclist['over']);
                                                                                                
$SMARTY->assign('maclist',$maclist);
$SMARTY->assign('listdata',$listdata);
$SMARTY->display('euro/maclistshort.html');

?>
