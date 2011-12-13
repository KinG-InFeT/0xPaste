<?php
/*
 *
 * @project 0xPaste
 * @author KinG-InFeT
 * @licence GNU/GPL
 *
 * @file index.php
 *
 * @link http://0xproject.netsons.org#0xPaste
 *
 */
ob_start();
session_start();

include("lib/core.class.php");

$template = new Core();

if(@$_GET['action'] == 'paste') {
	$template->inserit($_POST['title'], $_POST['author'], $_POST['lang'], $_POST['code'], $_POST['captcha']);
	exit;
}

//patch for compatible 0xPaste old Version
if((@$_GET['mode'] == 'view') && !empty($_GET['id'])) {
	header('Location: view.php?id='.$_GET['id']);
}
	

$template->PrintHeader();
$template->PrintBody();
$template->PrintFooter();
?>

