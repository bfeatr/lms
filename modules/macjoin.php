<?php

/*
 *  $Id: macjoin.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */


$SMARTY->assign('macid',$_GET['id']);

if (!$LMS->MacExists($_GET['id'],0))
{
	//$body = '<H1>'.$layout['pagetitle'].'</H1><P>'.trans('Incorrect MAC ID.').'</P>';
}else{

	if($_GET['is_sure']!=1)
	{
		$body = '<P>'.trans('Do you want accept this MAC?').'</P>'; 
		$body .= '<P><A HREF="?m=macjoin&id='.$_GET['id'].'&is_sure=1">'.trans('Yes, I do.').'</A></P>';
	}else{
		header("Location: ?".$SESSION->get('backto'));
		$body = '<P>'.trans('MAC address has been accepted.').'</P>';
		$oldmac = $LMS->GetMac($_GET['id'],0);
		$LMS->UpdateMac($oldmac['mac'],$oldmac['ip'],$oldmac['port'],$oldmac['vlan'],$oldmac['lastonline']);
	}
}

$SMARTY->assign('body',$body);
$SMARTY->display('dialog.html');
?>
