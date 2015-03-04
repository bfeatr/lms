<?php

/*
 *  $Id: sprzetlista.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

$layout['pagetitle'] = trans('sprzetklientow');

if(!isset($_GET['o']))
	$SESSION->restore('ndlo', $o);
else
	$o = $_GET['o'];
$SESSION->save('ndlo', $o);

$sprzetlist = $LMS->GetSprzetList($o);
$listdata['total'] = $sprzetlist['total'];
$listdata['order'] = $sprzetlist['order'];
$listdata['direction'] = $sprzetlist['direction'];
unset($sprzetlist['total']);
unset($sprzetlist['order']);
unset($sprzetlist['direction']);

if ($SESSION->is_set('ndlp') && !isset($_GET['page']))
        $SESSION->restore('ndlp', $_GET['page']);
	
$page = (! $_GET['page'] ? 1 : $_GET['page']);
$pagelimit = (! $LMS->CONFIG['phpui']['nodelist_pagelimit'] ? $listdata['total'] : $LMS->CONFIG['phpui']['nodelist_pagelimit']);
$start = ($page - 1) * $pagelimit;

$SESSION->save('ndlp', $page);

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$SMARTY->assign('page',$page);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('start',$start);
$SMARTY->assign('sprzetlist',$sprzetlist);
$SMARTY->assign('listdata',$listdata);
$SMARTY->display('sprzetlista.html');

?>
