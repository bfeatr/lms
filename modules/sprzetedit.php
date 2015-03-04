<?php

/*
 *  $Id: sprzetedit.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

if(! $LMS->SprzetExists($_GET['id']))
{
	$SESSION->redirect('?m=sprzetlista');
}		

if(isset($_POST['sprzet']))
{
	$sprzetdata = $_POST['sprzet'];
	$sprzetdata['id'] = $_GET['id'];

	if($sprzetdata['nazwa'] == '')
		$error['nazwa'] = trans('Device name is required!');
	elseif(strlen($sprzetdata['nazwa']) > 255)
		$error['nazwa'] =  trans('Specified name is too long (max.$0 characters)!','255');
	
	if(!$error)
	{
		$LMS->SprzetUpdate($sprzetdata);
		$SESSION->redirect('?m=sprzetinfo&id='.$_GET['id']);
	}
}
else
{
	$sprzetdata = $LMS->GetSprzet($_GET['id']);
	$customers = $LMS->GetCustomerNames();
	$customer = $LMS->GetCustomer($sprzetdata['customerid']);
	$SMARTY->assign('sprzetinfo',$sprzetdata);
	$SMARTY->assign('customers',$customers);
	$SMARTY->assign('customer',$customer);
}
$sprzetdata['id'] = $_GET['id'];

$layout['pagetitle'] = trans('Device Edit');

$SMARTY->assign('error',$error);
$SMARTY->display('sprzetedit.html');
?>
