<?php
 /*
 *  $Id: devicesearch.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

if(isset($_POST['search']))
	$devicesearch = $_POST['search'];

if(!isset($devicesearch))
	$SESSION->restore('devicesearch', $devicesearch);
else
	$SESSION->save('devicesearch', $devicesearch);
if(!isset($_GET['o']))
	$SESSION->restore('sslo', $o);
else
	$o = $_GET['o'];
$SESSION->save('sslo', $o);

if(!isset($_POST['k']))
	$SESSION->restore('sslk', $k);
else
	$k = $_POST['k'];
$SESSION->save('sslk', $k);

if(isset($_GET['search'])) 
{
	$layout['pagetitle'] = trans('Device search result');

	$devicelist = $LMS->GetDeviceListS($o, $devicesearch, $k);

	$listdata['total'] = $devicelist['total'];
	$listdata['order'] = $devicelist['order'];
	$listdata['direction'] = $devicelist['direction'];

	unset($devicelist['total']);
	unset($devicelist['order']);
	unset($devicelist['direction']);
	
	if ($SESSION->is_set('sslp') && !isset($_GET['page']))
		$SESSION->restore('sslp', $_GET['page']);
		
	$page = (!isset($_GET['page']) ? 1 : $_GET['page']);
	
	$pagelimit = (! $LMS->CONFIG['phpui']['nodelist_pagelimit'] ? $listdata['total'] : $LMS->CONFIG['phpui']['nodelist_pagelimit']);
	$start = ($page - 1) * $pagelimit;
	$SESSION->save('sslp', $page);
	
	$SMARTY->assign('page',$page);
	$SMARTY->assign('pagelimit',$pagelimit);
	$SMARTY->assign('start',$start);
	$SMARTY->assign('devicelist',$devicelist);
	$SMARTY->assign('listdata',$listdata);
	
	if(isset($_GET['print']))
		$SMARTY->display('printdevicelist.html');
	elseif($listdata['total']==1)
		$SESSION->redirect('?m=deviceinfo&id='.$devicelist[0]['id']);
	else
		$SMARTY->display('devicesearchresults.html');
}
else
{
	$layout['pagetitle'] = trans('Nodes Search');

	$SESSION->remove('sslp');

	$SMARTY->assign('k',$k);
	$SMARTY->display('devicesearch.html');
}

?>
