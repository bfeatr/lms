<?php
passthru("/usr/bin/perl -T /etc/lms/aktywne_dodatki/bonus --id=".$_GET['id']);
$SESSION->redirect('?m=customerinfo&id='.$_GET['id']);
?>
