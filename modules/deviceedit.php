<?php

/*
 *  $Id: deviceedit.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

if(! $LMS->DeviceExists($_GET['id']))
{
	$SESSION->redirect('?m=devicelist');
}		

if(isset($_POST['device']))
{
	$devicedata = $_POST['device'];
	$devicedata['id'] = $_GET['id'];
	if(empty($devicedata['customerid'])) 
		$devicedata['customerid'] = 0;
	if($devicedata['model'] == '')
		$error['model'] = trans('Device name is required!');
	elseif(strlen($devicedata['model']) > 255)
		$error['model'] =  trans('Specified name is too long (max.$0 characters)!','255');
	
	if(!$error)
	{
		$LMS->DeviceUpdate($devicedata);
		$SESSION->redirect('?m=deviceinfo&id='.$_GET['id']);
	}
}
else
{
	$devicedata = $LMS->GetDevice($_GET['id']);
	$customers = $LMS->GetCustomerNames();
	$customer = $LMS->GetCustomer($devicedata['customerid']);
	$SMARTY->assign('deviceinfo',$devicedata);
	$SMARTY->assign('customers',$customers);
	$SMARTY->assign('customer',$customer);
}
$devicedata['id'] = $_GET['id'];

$layout['pagetitle'] = trans('Device Edit');

$SMARTY->assign('error',$error);
$SMARTY->display('euro/deviceedit.html');
?>
