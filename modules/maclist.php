<?php

/*
 *  $Id: maclist.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

if(!isset($_GET['o']))
	$SESSION->restore('uslo', $o);
else
	$o = $_GET['o'];
$SESSION->save('uslo', $o);

if(!isset($_POST['k']))
	$SESSION->restore('uslk', $k);
else
	$k = $_POST['k'];
$SESSION->save('uslk', $k);

if(!isset($_POST['t']))
	if(isset($_POST['change']))
	    $SESSION->restore('uslt', $t);
	else
	$t=0;
else
	$t = $_POST['t'];
$SESSION->save('uslt', $t);

if(!isset($_POST['details']))
	if(!isset($_POST['change']))
	    $SESSION->restore('usld', $details);
	else
	    $details=$details;
else
    $details = (int)$_POST['details'];
$SESSION->save('usld', $details);

switch($t)
{
    case 0:
	$layout['pagetitle'] = trans('MAC Search Results in suspect table');
	break;
    case 2:
	$layout['pagetitle'] = trans('MAC Search Results in archive table');
	break;
    case 1:
    default:
	$layout['pagetitle'] = trans('MAC Search Results in correct table');
}

$maclist = $LMS->GetMacList($o, $t, $macsearch, $k, $details, 0);

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
$pagelimit=50;
$start = ($page - 1) * $pagelimit;

$SESSION->save('uslp', $page);
		
$SMARTY->assign('maclist',$maclist);
$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('t', $t);
$SMARTY->assign('details', $details);
$SMARTY->assign('page',$page);
$SMARTY->assign('start',$start);
$SMARTY->display('euro/maclist.html');
?>


