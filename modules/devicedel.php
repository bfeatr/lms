<?php

/*
 *  $Id: devicedel.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

if(! $LMS->DeviceExists($_GET['id']))
{
	$SESSION->redirect('?m=devicelist');
}		

$layout['pagetitle'] = trans('Deletion of Device with ID: $0',sprintf('%04d',$_GET['id']));
$SMARTY->assign('deviceid',$_GET['id']);

if($_GET['is_sure']!=1)
{
    $body = '<H1>'.$layout['pagetitle'].'</H1>';
    $body .= '<P>'.trans('Are you sure, you want to delete that device?').'</P>'; 
    $body .= '<P><A HREF="?m=devicedel&id='.$_GET['id'].'&is_sure=1">'.trans('Yes, I am sure.').'</A></P>';
}else{
    header('Location: ?m=devicelist');
    $body = '<H1>'.$layout['pagetitle'].'</H1>';
    $body .= '<P>'.trans('Device has been deleted.').'</P>';
    $LMS->DeleteDevice($_GET['id']);
}
	

$SMARTY->display('header.html');
$SMARTY->assign('body',$body);
$SMARTY->display('dialog.html');
$SMARTY->display('footer.html');

?>
