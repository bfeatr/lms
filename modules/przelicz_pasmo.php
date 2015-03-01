<?php
$username = strtolower($_GET['id']);
passthru("/etc/lms/aktywne_dodatki/pasmo --id ".$username);
$SESSION->redirect('?m=customerinfo&id=' . $_GET['id']);
?> 