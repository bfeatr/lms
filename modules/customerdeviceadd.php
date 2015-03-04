<?php

/*
 *  $Id: customerdeviceadd.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

if(isset($_POST['customerdeviceid']))
{
        if ($LMS->DeviceExists($_POST['customerdeviceid']))
                $LMS->CustomerDeviceAdd($_GET['id'], $_POST['customerdeviceid']);
        $SESSION->redirect('?m=customerinfo&id='.$_GET['id']);
}
?>
