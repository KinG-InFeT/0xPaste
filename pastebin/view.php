<?php
/*
 *
 * @project 0xPaste
 * @author KinG-InFeT
 * @licence GNU/GPL
 *
 * @file view.php
 *
 * @link http://0xproject.hellospace.net#0xPaste
 *
 */

include("lib/core.class.php");

$template = new Core();

if(@$_GET['mode'] == 'raw')
	die($template->view_raw(@$_GET['id']));
else if (@$_GET['mode'] == 'embed')
	$template->embed(@$_GET['id']);
else if (@$_GET['mode'] == 'all')
	die($template->all_pastes());
	
$template->View(@$_GET['id'], @$_GET['line']);
?>
