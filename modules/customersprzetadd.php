<?php

/*
 *  $Id: customersprzetadd.php,v 1.0 2010/02/12 00:00:00 emers Exp $
 */

if(isset($_POST['customersprzetid']))
{
        if ($LMS->SprzetExists($_POST['customersprzetid']))
                $LMS->CustomerSprzetAdd($_GET['id'], $_POST['customersprzetid']);
        $SESSION->redirect('?m=customerinfo&id='.$_GET['id']);
}
?>
