<?php

/*
 *  $Id: macdel.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */


$SMARTY->assign('macid',$_GET['id']);

if (!$LMS->MacExists($_GET['id'],$_GET['t']))
{
	$body = '<H1>'.$layout['pagetitle'].'</H1><P>'.trans('Incorrect MAC ID.').'</P>';
}else{

	if($_GET['is_sure']!=1)
	{
		switch($_GET['t'])
		{
		    case 0:
			$table='{t}correct{/t}';
			break;
		    case 2:
			$table='{t}archived{/t}';
			break;
		    case 1:
		    default:
			$table='{t}suspect{/t}';
		}
		
		$body = '<H1>'.$layout['pagetitle'].'</H1>';
		$body .= '<P>'.trans('Do you want to remove this MAC').'</P>'; 
		$body .= '<P><A HREF="?m=macdel&id='.$_GET['id'].'t='.$_GET['t'].'&is_sure=1">'.trans('Yes, I do.').'</A></P>';
	}else{
		header("Location: ?".$SESSION->get('backto'));
		$body = '<H1>'.$layout['pagetitle'].'</H1>';
		$body .= '<P>'.trans('MAC address has been removed.').'</P>';
		$LMS->DeleteMac($_GET['id'],$_GET['t']);
	}
}

//$SMARTY->display('header.html');
$SMARTY->assign('body',$body);
$SMARTY->display('dialog.html');
//$SMARTY->display('footer.html');

?>
