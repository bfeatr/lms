<?php
passthru("/usr/bin/perl -T /etc/lms/lms-sendinvoices2015 --fakeid=".$_GET['id']);
$SESSION->redirect('?m=customerinfo&id='.$_GET['id']);
?>
