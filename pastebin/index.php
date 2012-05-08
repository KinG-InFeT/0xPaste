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

$_SESSION['terminal_mode'] = (isset($_GET['set_mode'])) ? $_GET['set_mode'] : 0;

if(!empty($_REQUEST['code']) || @$_SESSION['terminal_mode'] == 1 || @$_GET['mode'] == 'terminal') {

    include("lib/terminal.class.php");
    
    $terminal = new Terminal();
    
    if(@$_GET['list_lang'] == 1)
        die($terminal->list_lang());
     
    
    if(!empty($_REQUEST['code'])) {
        $terminal->inserit($_REQUEST['code']);
        exit;
    }
    
    $terminal->PrintHeader();
    $terminal->PrintBody();

}else{

    include("lib/core.class.php");


    $core = new Core();

    if(@$_GET['action'] == 'paste') {
    	$core->inserit($_POST['title'], $_POST['author'], $_POST['lang'], $_POST['normal_code'], $_POST['captcha'], $_POST['autodelete']);
    	exit;
    }

    //patch for compatible 0xPaste old Version
    if((@$_GET['mode'] == 'view') && !empty($_GET['id'])) {
    	header('Location: view.php?id='.$_GET['id']);
    }
	
    $core->PrintHeader();
    $core->PrintBody();
    $core->PrintFooter();
}
?>

