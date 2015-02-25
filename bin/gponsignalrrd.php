<?php

ini_set('error_reporting', E_ALL&~E_NOTICE);

$parameters = array(
	'C:' => 'config-file:',
);

foreach ($parameters as $key => $val) {
	$val = preg_replace('/:/', '', $val);
	$newkey = preg_replace('/:/', '', $key);
	$short_to_longs[$newkey] = $val;
}
$options = getopt(implode('', array_keys($parameters)), $parameters);
foreach($short_to_longs as $short => $long)
	if (array_key_exists($short, $options))
	{
		$options[$long] = $options[$short];
		unset($options[$short]);
	}

if (array_key_exists('config-file', $options))
	$CONFIG_FILE = $options['config-file'];
else
	$CONFIG_FILE = '/etc/lms/lms.ini';

if (!$quiet)
	echo "Using file ".$CONFIG_FILE." as config.\n";

if (!is_readable($CONFIG_FILE))
	die('Unable to read configuration file ['.$CONFIG_FILE.']!'); 

define('CONFIG_FILE', $CONFIG_FILE);

$CONFIG = (array) parse_ini_file($CONFIG_FILE, true);

// Check for configuration vars and set default values
$CONFIG['directories']['sys_dir'] = (!isset($CONFIG['directories']['sys_dir']) ? getcwd() : $CONFIG['directories']['sys_dir']);
$CONFIG['directories']['lib_dir'] = (!isset($CONFIG['directories']['lib_dir']) ? $CONFIG['directories']['sys_dir'].'/lib' : $CONFIG['directories']['lib_dir']);
$CONFIG['directories']['rrd_dir'] = (!isset($CONFIG['directories']['rrd_dir']) ? $CONFIG['directories']['sys_dir'].'/rrd' : $CONFIG['directories']['rrd_dir']);

define('SYS_DIR', $CONFIG['directories']['sys_dir']);
define('LIB_DIR', $CONFIG['directories']['lib_dir']);
define('RRD_DIR', $CONFIG['directories']['rrd_dir']);

// Load autloader
require_once(LIB_DIR.'/autoloader.php');

// Do some checks and load config defaults

require_once(LIB_DIR.'/config.php');

// Init database

$DB = null;

try {

    $DB = LMSDB::getInstance();

} catch (Exception $ex) {
    
    trigger_error($ex->getMessage(), E_USER_WARNING);
    
    // can't working without database
    die("Fatal error: cannot connect to database!\n");
    
}

// Include required files (including sequence is important)

//require_once(LIB_DIR.'/definitions.php');
require_once(LIB_DIR.'/common.php');
require_once(LIB_DIR.'/language.php');


require_once(LIB_DIR.'/gpon/GPON.class.php');
$GPON = new GPON($DB, $AUTH, $CONFIG);

if($cfg = $DB->GetAll('SELECT section, var, value FROM uiconfig WHERE disabled=0'))
	foreach($cfg as $row)
		$CONFIG[$row['section']][$row['var']] = $row['value'];

$rrdtool = (!isset($CONFIG['phpui']['rrdtool']) ?  '/usr/bin/rrdtool' : $CONFIG['phpui']['rrdtool']);

if(!file_exists($rrdtool))
	die ("No rrdtool binary found on path $rrdtool");
//-----------------------------------------//

$olts=$GPON->GetGponAllOlt();

if(is_array($olts) && count($olts)>0)
{
    foreach($olts as $k=>$v)
    {
	$GPON->snmp->clear_options();
	if(is_array($v) && count($v)>0)
        {
               $GPON->snmp->set_options($v);
	       $olt_name=$v['name'];
	       $gponoltid=$v['id'];
        }
        $error_snmp=$GPON->snmp->get_correct_connect_snmp();
        if(strlen($error_snmp)>0)
        {
                                die('Error in snmp at olt: '.$olt_name);
        }
 
	$signals = $GPON->snmp->OLT_ONU_walk_signal();

	foreach($signals as $snmpid => $signal)
	{
	    preg_match ("/sleGponOnuRxPower\.(\d+)\.(\d+)/", $snmpid, $matchids);
	    $onuid = $DB->GetOne("SELECT o.id  FROM gpononu o 
			JOIN gpononu2olt p ON p.gpononuid=o.id
			WHERE netdevicesid = ? AND numport =? AND onuid = ?", array($v['netdevicesid'], $matchids[1], $matchids[2]));
	    if($onuid)
	    {
		$signal = $GPON->snmp->clean_snmp_value($signal);
		$signal = str_replace('dBm','',$signal);

		//olx rx signal
		$OLT_id = $matchids[1];
		$ONU_id = $matchids[2];
	        $GPON->snmp->set('sleGponOnuControlRequest','i',20); //updateOltRxPower(20)
		$GPON->snmp->set('sleGponOnuControlOltId','i',$OLT_id);
		$GPON->snmp->set('sleGponOnuControlId','i',$ONU_id);
		$GPON->snmp->set('sleGponOnuControlTimer','u',0);
		$oltrx = $GPON->snmp->get('sleGponOnuOltRxPower.'.$OLT_id.'.'.$ONU_id);
//drugi przebieg - jakis problem z odczytywaniem olt rx-power
                $GPON->snmp->set('sleGponOnuControlRequest','i',20); //updateOltRxPower(20)
		$GPON->snmp->set('sleGponOnuControlOltId','i',$OLT_id);
		$GPON->snmp->set('sleGponOnuControlId','i',$ONU_id);
		$GPON->snmp->set('sleGponOnuControlTimer','u',0);
		$oltrx = $GPON->snmp->get('sleGponOnuOltRxPower.'.$OLT_id.'.'.$ONU_id);

		$oltrx = str_replace('dBm','',$oltrx);

		//zadrza sie ze ktos ma inne locale ???  , -> .
		$signal = str_replace(',','.',$signal);
		$oltrx = str_replace(',','.',$oltrx);

		update_signal_onu_rrd($onuid, $signal, $oltrx);
	    }
	}

    //exit (0);
    }
}

//-----------------------------------------//
$DB->Destroy();

function update_signal_onu_rrd($onuid, $signal, $oltrx)
{
        global $rrdtool;
        if ((strlen($onuid) == 0) || (strlen($signal) == 0))
                return;

        $fname = RRD_DIR."/signal_onu_$onuid.rrd";
        if (!file_exists($fname))
        {//create rrd
                $cmd  = $rrdtool." create $fname --step 3600 ";
                $cmd .= "DS:Signal:GAUGE:7200:-50:10 ";
                $cmd .= "DS:oltrx:GAUGE:7200:-50:10 ";
                $cmd .= "RRA:AVERAGE:0.5:1:288 "; //12 dni co godzine
                $cmd .= "RRA:AVERAGE:0.7:6:268 "; //cwierc dnia ~ 2mce
                $cmd .= "RRA:AVERAGE:0.8:24:1095 "; //3year - 1day 
                $cmd .= "RRA:MIN:0.5:1:288 "; 
                $cmd .= "RRA:MIN:0.7:6:268 "; 
                $cmd .= "RRA:MIN:0.8:24:1095 ";
                $cmd .= "RRA:MAX:0.5:1:288 "; 
                $cmd .= "RRA:MAX:0.7:6:268 "; 
                $cmd .= "RRA:MAX:0.8:24:1095 ";
                exec($cmd);
        }
//update rrd file
	$cmd  = $rrdtool." update $fname N:$signal:$oltrx";
//update via rrdcached deamon, tylko ze jesli to dziala raz na godzine to nie ma to sensu ;)
//	$cmd  = "/usr/bin/rrdupdate $fname --daemon /var/run/rrdcached.sock N:$signal:$oltrx";
        exec($cmd);
}

?>
