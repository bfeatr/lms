<?php

/*
 *  $Id: devicelist.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

$layout['pagetitle'] = trans('Customer devices');

if(!isset($_GET['o']))
	$SESSION->restore('ndlo', $o);
else
	$o = $_GET['o'];
$SESSION->save('ndlo', $o);

$devicelist = $LMS->GetDeviceList($o);
$listdata['total'] = $devicelist['total'];
$listdata['order'] = $devicelist['order'];
$listdata['direction'] = $devicelist['direction'];
unset($devicelist['total']);
unset($devicelist['order']);
unset($devicelist['direction']);

if ($SESSION->is_set('ndlp') && !isset($_GET['page']))
        $SESSION->restore('ndlp', $_GET['page']);
	
$page = (! $_GET['page'] ? 1 : $_GET['page']);
$pagelimit = (! $LMS->CONFIG['phpui']['nodelist_pagelimit'] ? $listdata['total'] : $LMS->CONFIG['phpui']['nodelist_pagelimit']);
$start = ($page - 1) * $pagelimit;

$SESSION->save('ndlp', $page);

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$SMARTY->assign('page',$page);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('start',$start);
$SMARTY->assign('devicelist',$devicelist);
$SMARTY->assign('listdata',$listdata);
$SMARTY->display('euro/devicelist.html');

?>
