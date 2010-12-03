<?php
/*
 *
 * @project 0xPaste
 * @author KinG-InFeT
 * @licence GNU/GPL
 *
 * @file index.php
 *
 * @link http://0xproject.hellospace.net#0xPaste
 *
 */

session_start();

include("lib/core.class.php");

$template = new Core();

if(@$_GET['action'] == 'paste') {
	$template->inserit($_POST['title'], $_POST['author'], $_POST['lang'], $_POST['code'], $_POST['captcha']);
	exit;
}
	

$template->PrintHeader();
$template->PrintBody();
$template->PrintFooter();
?>

