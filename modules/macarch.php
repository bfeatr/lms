<?php

/*
 *  $Id: macarch.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */


$SMARTY->assign('macid',$_GET['id']);
$SMARTY->assign('table',$_GET['t']);

if (!$LMS->MacExists($_GET['id'],0))
{
	$body = '<H1>'.$layout['pagetitle'].'</H1><P>'.trans('Incorrect MAC ID.').'</P>';
}else{

	if($_GET['is_sure']!=1)
	{
		$body = '<H1>'.$layout['pagetitle'].'</H1>';
		$body .= '<P>'.trans('Do you want archive this MAC?').'</P>'; 
		$body .= '<P><A HREF="?m=macarch&id='.$_GET['id'].'&t='.$_GET['t'].'&is_sure=1">'.trans('Yes, I do.').'</A></P>';
	}else{
		header("Location: ?".$SESSION->get('backto'));
		$body = '<H1>'.$layout['pagetitle'].'</H1>';
		$body .= '<P>'.trans('MAC address has been archived.').'</P>';
		$LMS->ArchMac($_GET['id'],$_GET['t']);
	}
}

//$SMARTY->display('header.html');
$SMARTY->assign('body',$body);
$SMARTY->display('dialog.html');
//$SMARTY->display('footer.html');
?>
