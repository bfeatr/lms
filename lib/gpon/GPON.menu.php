<?php
$menu_gpon = array(
		'GPON' => array(
					'name' => 'GPON DASAN',
					'img' =>'gponolt.gif',
					'link' =>'?m=gponoltlist',
					'tip' => 'Zarządzanie GPON',
					'accesskey' =>'k',
					'prio' => 11,
					'submenu' => array(
						array(
							'name' => 'Lista <b>OLT</b>',
							'link' => '?m=gponoltlist',
							'tip' => 'Lista OLT',
							'prio' => 10,
						),
						array(
							'name' => 'Nowy <b>OLT</b>',
							'link' => '?m=gponoltadd',
							'tip' => 'Dodaj OLT',
							'prio' => 20,
						),
						array(
							'name' => trans('Search').' <b>OLT</b>',
							'link' => '?m=gponoltsearch',
							'tip' => 'Szukaj OLT',
							'prio' => 30,
						),
						array(
							'name' => '------------',
							'prio' => 35,
						),
						array(
							'name' => 'Wykryj <b>ONU</b>',
							'link' => '?m=gpononucheck',
							'tip' => 'Wykryj ONU',
							'prio' => 37,
						),
						array(
							'name' => 'Lista <b>ONU</b>',
							'link' => '?m=gpononulist',
							'tip' => 'Lista ONU',
							'prio' => 40,
						),
						array(
							'name' => 'Nowy <b>ONU</b>',
							'link' => '?m=gpononuadd',
							'tip' => 'Dodaj ONU',
							'prio' => 50,
						),
						array(
							'name' => trans('Search').' <b>ONU</b>',
							'link' => '?m=gpononusearch',
							'tip' => 'Szukaj ONU',
							'prio' => 60,
						),
						array(
							'name' => '------------',
							'prio' => 65,
						),
						array(
							'name' => 'Lista modeli <b>ONU</b>',
							'link' => '?m=gpononumodelslist',
							'tip' => 'Lista modeli ONU',
							'prio' => 70,
						),
						array(
							'name' => 'Nowy model <b>ONU</b>',
							'link' => '?m=gpononumodelsadd',
							'tip' => 'Dodaj model ONU',
							'prio' => 80,
						),
						array(
							'name' => '------------',
							'prio' => 85,
						),
						array(
							'name' => 'Lista kanałów TV',
							'link' => '?m=gpononutvlist',
							'tip' => 'Lista kanałów TV',
							'prio' => 90,
						),
						array(
							'name' => 'Nowy kanał TV',
							'link' => '?m=gpononutvadd',
							'tip' => 'Dodaj kanał TV',
							'prio' => 100,
						),
					),
				),
	);

if(!ConfigHelper::getConfig('phpui.gpon_use_radius'))
{
	array_push($menu_gpon['GPON']['submenu'], array(
							'name' => 'Auto podłączanie <b>ONU</b>',
							'link' => '?m=gpononuscript',
							'tip' => 'Auto podłączanie ONU',
							'prio' => 38,
						)
		    );
}

$menu=array_merge($menu,$menu_gpon);
?>
