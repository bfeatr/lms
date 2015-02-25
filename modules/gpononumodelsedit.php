<?php

/*
 * LMS version 1.11.10 Kri
 *
 *  (C) Copyright 2001-2010 LMS Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 *  $Id: gpoonumodelsedit.php,v 1.69 2012/04/18 13:07:47 DK Exp $
 */
if (!chkconfig(ConfigHelper::getConfig('phpui.gpon')))
{
	$SESSION->redirect('?m=gponoffline');
}
if(! $GPON->GponOnuModelsExists($_GET['id']))
{
	$SESSION->redirect('m=gpononumodelslist');
}		

$action = !empty($_GET['action']) ? $_GET['action'] : '';
$edit = '';
$subtitle = '';

switch($action)
{

default:
	$edit = 'data';
	break;
}

if(isset($_POST['netdev']))
{
	$netdevdata = $_POST['netdev'];
	$netdevdata['id'] = $_GET['id'];

	if($netdevdata['name'] == '')
		$error['name'] = trans('Device name is required!');
	elseif(strlen($netdevdata['name']) > 32)
		$error['name'] =  trans('Specified name is too long (max.$a characters)!','32');

	

	if(!$error)
	{

		$GPON->GponOnuModelsUpdate($netdevdata);
		$GPON->SetGponOnuPortsType2Models($_GET['id'],$_POST['portstype']);
		
		$SESSION->redirect('?m=gpononumodelsinfo&id='.$_GET['id']);
	}
}
else 
{
	$netdevdata = $GPON->GetGponOnuModels($_GET['id']);
	
}


$portstype = $GPON->GetGponOnuPortsType();
$portstype2models = $GPON->FlatArrayFromDB($GPON->GetGponOnuPortsType2Models($_GET['id']),'gpononuportstypeid','portscount');


unset($netdevlist['total']);
unset($netdevlist['order']);
unset($netdevlist['direction']);



$layout['pagetitle'] = 'GPON-ONU-MODEL: '.trans('$a ($b)', $netdevdata['name'], $netdevdata['producer']);

if($subtitle) $layout['pagetitle'] .= ' - '.$subtitle;

$SMARTY->assign('error',$error);
$SMARTY->assign('netdevinfo',$netdevdata);
$SMARTY->assign('portstype',$portstype);
$SMARTY->assign('portstype2models',$portstype2models);

switch($edit)
{
    case 'data':
	
	$SMARTY->display('gpon/gpononumodelsedit.html');
    break;
    default:
	$SMARTY->display('gpon/gpononumodelsinfo.html');
    break;
}
?>
