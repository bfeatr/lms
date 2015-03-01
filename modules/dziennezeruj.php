<?php
$DB->Execute('UPDATE customers SET bonus = 0 WHERE id=?',array($_GET['id']));
$SESSION->redirect('?m=customerinfo&id='.$_GET['id']);
?>
