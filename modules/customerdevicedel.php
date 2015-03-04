<?php

/*
 *  $Id: customerdevicedel.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

if(isset($_GET['is_sure']))
{
        $LMS->CustomerDeviceDelete($_GET['customerdeviceid']);
        $SESSION->redirect('?m=customerinfo&id='.$_GET['id']);

}
?>
