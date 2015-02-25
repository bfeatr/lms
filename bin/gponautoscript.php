<?php
$CONFIG_FILE = '/etc/lms/lms.ini';

define('START_TIME', microtime());
ini_set('error_reporting', E_ALL&~E_NOTICE);

// find alternative config files:
if(is_readable('lms.ini'))
	$CONFIG_FILE = 'lms.ini';
elseif(is_readable('/etc/lms/lms-'.$_SERVER['HTTP_HOST'].'.ini'))
	$CONFIG_FILE = '/etc/lms/lms-'.$_SERVER['HTTP_HOST'].'.ini';
elseif(!is_readable($CONFIG_FILE))
	die('Unable to read configuration file ['.$CONFIG_FILE.']!'); 

$CONFIG = (array) parse_ini_file($CONFIG_FILE, true);

$CONFIG['directories']['sys_dir'] = (!isset($CONFIG['directories']['sys_dir']) ? getcwd() : $CONFIG['directories']['sys_dir']);
$CONFIG['directories']['lib_dir'] = (!isset($CONFIG['directories']['lib_dir']) ? $CONFIG['directories']['sys_dir'].'/lib' : $CONFIG['directories']['lib_dir']);

define('SYS_DIR', $CONFIG['directories']['sys_dir']);
define('LIB_DIR', $CONFIG['directories']['lib_dir']);

// Init database
$_DBTYPE = $CONFIG['database']['type'];
$_DBHOST = $CONFIG['database']['host'];
$_DBUSER = $CONFIG['database']['user'];
$_DBPASS = $CONFIG['database']['password'];
$_DBNAME = $CONFIG['database']['database'];

require(LIB_DIR.'/LMSDB.php');

$DB = DBInit($_DBTYPE, $_DBHOST, $_DBUSER, $_DBPASS, $_DBNAME);

if(!$DB)
{
	// can't working without database
	die();
}

require_once(LIB_DIR.'/gpon/GPON.class.php');
$GPON1 = new GPON($DB, $AUTH, $CONFIG);

if($cfg = $DB->GetAll('SELECT section, var, value FROM uiconfig WHERE disabled=0'))
	foreach($cfg as $row)
		$CONFIG[$row['section']][$row['var']] = $row['value'];
//-----------------------------------------//
$gpon_onu_autoscript_debug = (isset($CONFIG['phpui']['gpon_onu_autoscript_debug']) ? chkconfig($CONFIG['phpui']['gpon_onu_autoscript_debug']) : 0);
$output=$GPON1->GetGponAutoScript($gpon_onu_autoscript_debug);
echo $output;


//-----------------------------------------//
$DB->Destroy();
?>
