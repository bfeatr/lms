<?php

/*
 *  $Id: maclistinfo.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

if(!isset($_GET['o']))
	$SESSION->restore('uslo', $o);
else
	$o = $_GET['o'];
$SESSION->save('uslo', $o);

$macsearch['mac'] = $_GET['mac'];
$macsearch['ip'] = $_GET['ip'];
$macsearch['port'] = $_GET['port'];
$macsearch['vlan'] = $_GET['vlan'];

$layout['pagetitle'] = trans('MAC Search Results in correct table');
$maclist = $LMS->GetMacList('mac,asc', 1, $macsearch, 'AND', 1, 10);

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

if (! isset($_GET['page']))
    $SESSION->restore('uslp', $_GET['page']);

$page = (! $_GET['page'] ? 1 : $_GET['page']); 
$pagelimit = (!isset($LMS->CONFIG['phpui']['customerlist_pagelimit']) ? $listdata['total'] : $LMS->CONFIG['phpui']['customerlist_pagelimit']);
$start = ($page - 1) * $pagelimit;

$SESSION->save('uslp', $page);
		
$SMARTY->assign('maclist',$maclist);
$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('t', 1);
$SMARTY->assign('details', 1);
$SMARTY->assign('page',$page);
$SMARTY->assign('start',$start);
$SMARTY->display('euro/macsearchresults.html');
?>


