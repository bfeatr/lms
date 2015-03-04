<?php

/*
 * LMS version 1.8.13 Tessa
 *
 *  (C) Copyright 2001-2006 LMS Developers
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
 *  $Id: sprzetsearch.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

if(isset($_POST['search']))
	$sprzetsearch = $_POST['search'];

if(!isset($sprzetsearch))
	$SESSION->restore('sprzetsearch', $sprzetsearch);
else
	$SESSION->save('sprzetsearch', $sprzetsearch);
if(!isset($_GET['o']))
	$SESSION->restore('sslo', $o);
else
	$o = $_GET['o'];
$SESSION->save('sslo', $o);

if(!isset($_POST['k']))
	$SESSION->restore('sslk', $k);
else
	$k = $_POST['k'];
$SESSION->save('sslk', $k);

if(isset($_GET['search'])) 
{
	$layout['pagetitle'] = trans('wynik wyszukiwania sprzetu');

	$sprzetlist = $LMS->GetSprzetListS($o, $sprzetsearch, $k);

	$listdata['total'] = $sprzetlist['total'];
	$listdata['order'] = $sprzetlist['order'];
	$listdata['direction'] = $sprzetlist['direction'];

	unset($sprzetlist['total']);
	unset($sprzetlist['order']);
	unset($sprzetlist['direction']);
	
	if ($SESSION->is_set('sslp') && !isset($_GET['page']))
		$SESSION->restore('sslp', $_GET['page']);
		
	$page = (!isset($_GET['page']) ? 1 : $_GET['page']);
	
	$pagelimit = (! $LMS->CONFIG['phpui']['nodelist_pagelimit'] ? $listdata['total'] : $LMS->CONFIG['phpui']['nodelist_pagelimit']);
	$start = ($page - 1) * $pagelimit;
	$SESSION->save('sslp', $page);
	
	$SMARTY->assign('page',$page);
	$SMARTY->assign('pagelimit',$pagelimit);
	$SMARTY->assign('start',$start);
	$SMARTY->assign('sprzetlist',$sprzetlist);
	$SMARTY->assign('listdata',$listdata);
	
	if(isset($_GET['print']))
		$SMARTY->display('printsprzetlist.html');
	elseif($listdata['total']==1)
		$SESSION->redirect('?m=sprzetinfo&id='.$sprzetlist[0]['id']);
	else
		$SMARTY->display('sprzetsearchresults.html');
}
else
{
	$layout['pagetitle'] = trans('Nodes Search');

	$SESSION->remove('sslp');

	$SMARTY->assign('k',$k);
	$SMARTY->display('sprzetsearch.html');
}

?>
