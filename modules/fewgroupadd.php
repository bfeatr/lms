<?php
if(isset($_POST['submit'])){
	if(!empty($_POST['checkbox'])) {

	foreach($_POST['checkbox'] as $selected) {

	$LMS->CustomerAssignmentAdd(array('customerid' => $_GET['id'], 'customergroupid' => $selected));
   
   
						}
					}
				}
$SESSION->redirect('?m=customerinfo&id=' . $_GET['id']);

?>
