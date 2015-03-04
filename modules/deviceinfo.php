<?php

/*
 *  $Id: deviceinfo.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

if(! $LMS->DeviceExists($_GET['id']))
{
	$SESSION->redirect('?m=devicelist');
}

$deviceinfo = $LMS->GetDevice($_GET['id']);
$customer = $LMS->GetCustomer($deviceinfo['customerid']);
$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$layout['pagetitle'] = trans('Device Info');

$deviceinfo['id'] = $_GET['id'];

$SMARTY->assign('deviceinfo',$deviceinfo);
$SMARTY->assign('customer',$customer);
$SMARTY->display('euro/deviceinfo.html');

?>
