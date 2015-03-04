<?php

/*
 *  $Id: sprzetdel.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

if(! $LMS->SprzetExists($_GET['id']))
{
	$SESSION->redirect('?m=sprzetlist');
}		


$SMARTY->assign('sprzetid',$_GET['id']);

if($_GET['is_sure']!=1)
{
        $body .= '<P>'.trans('Are you sure, you want to delete that device?').'</P>'; 
    $body .= '<P><A HREF="?m=sprzetdel&id='.$_GET['id'].'&is_sure=1">'.trans('Yes, I am sure.').'</A></P>';
}else{
    header('Location: ?m=sprzetlista');
    $body .= '<P>'.trans('Device has been deleted.').'</P>';
    $LMS->DeleteSprzet($_GET['id']);
}
	


$SMARTY->assign('body',$body);
$SMARTY->display('dialog.html');


?>
