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
 *  $Id: gpononutvadd.php,v 1.30 2012/09/13 23:07:47 DK Exp $
 */
if (!chkconfig(ConfigHelper::getConfig('phpui.gpon')))
{
	$SESSION->redirect('?m=gponoffline');
}
if(isset($_POST['netdev']))
{
	$netdevdata = $_POST['netdev'];
	$netdevdata['ipaddr']=trim($netdevdata['ipaddr']);
if(strlen($netdevdata['ipaddr'])==0)
		$error['ipaddr'] = trans('Node IP address is required!');
	elseif(!check_ip($netdevdata['ipaddr']))
		$error['ipaddr'] = trans('Incorrect node IP address!');
	elseif (!$GPON->IsGponOnuTvMulticast($netdevdata['ipaddr']))
		$error['ipaddr'] = 'Wpisany adres nie jest typu multicast';
	elseif ($GPON->GponOnuTvIpExists($netdevdata['ipaddr']))
		$error['ipaddr'] = 'Ten adres jest już używany przez inny kanał TV';
	
	$netdevdata['canal']=trim($netdevdata['canal']);
	if(strlen($netdevdata['canal'])==0)
		$error['canal'] = 'Podaj nazwę kanału TV';

        if(!$error)
        {
			$netdevid = $GPON->GponOnuTvAdd($netdevdata);
			$SESSION->redirect('?m=gpononutvinfo&id='.$netdevid);
        }
	
	$SMARTY->assign('error', $error);
	$SMARTY->assign('netdev', $netdevdata);
	
}
$layout['pagetitle'] = 'GPON-ONU-TV';
$SMARTY->display('gpon/gpononutvadd.html');
?>
