<?php

/*
 *  $Id: deviceadd.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

if(isset($_POST['device']))
{
	$devicedata = $_POST['device'];

	$devicedata['serialnumber'] = trim(strtoupper($devicedata['serialnumber']));

	if($devicedata['price'] == '')
		$devicedata['price'] = 0;
	if($devicedata['model'] == '')
		if($devicedata['models'] != 0)
		{
		    $model = $LMS->GetModel($devicedata['models']);
		    $devicedata['model'] = $model['model'];
		} else
		    $error['model'] = trans('Device name is required!');
	elseif(strlen($devicedata['model'])>255)
		$error['model'] = trans('Device name is too long (max.255 characters)!');
	
	if($LMS->GetDeviceBySN($devicedata['serialnumber']))
            $error['serialnumber'] = trans('Serial number exists');

	if($devicedata['price'] == '')
	    $error['price'] = trans('Value is required!');

	if($devicedata['vendor'] == '')
	    if($devicedata['vendors'] != 0)
	    {
		$vendor = $LMS->GetVendor($devicedata['vendors']);
		$devicedata['vendor'] = $vendor['vendor'];
	    }

	if(empty($devicedata['customerid']))
		$devicedata['customerid'] = 0;

	if(empty($devicedata['info']))
		$devicedata['info'] = NULL;

//	var_dump($devicedata);
        if(!$error)
        {	
	    $deviceid = $LMS->DeviceAdd($devicedata);
	    if(!isset($devicedata['reuse']))
	    {
		$SESSION->redirect('?m=deviceinfo&id='.$deviceid);
	    }
	    $devicedata['reuse'] = '1';
	    $devicedata['serialnumber'] = substr($devicedata['serialnumber'], 0, -5);
	    $devicedata['info'] = substr($devicedata['infp'], 0, -4);
        }
	

	                                                        
}
$SMARTY->assign('error', $error);
$SMARTY->assign('device', $devicedata);
$customers = $LMS->GetCustomerNames();
$customer = $LMS->GetCustomer($_GET['customerid']);
$vendors = $LMS->GetVendors();
$models = $LMS->GetModels();
$SMARTY->assign('vendors', $vendors);
$SMARTY->assign('models', $models);
$SMARTY->assign('customers', $customers);
$SMARTY->assign('customer', $customer);

$layout['pagetitle'] = trans('New Device');

$SMARTY->display('euro/deviceadd.html');

?>
