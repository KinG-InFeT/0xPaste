<?php
/*
 *
 * @project 0xPaste
 * @author KinG-InFeT
 * @licence GNU/GPL
 *
 * @file view.php
 *
 * @link http://0xproject.netsons.org#0xPaste
 *
 */

if(@$_GET['mode'] == 'terminal') {

    include("lib/terminal.class.php");
    
    $terminal = new Terminal();
    
    if(!empty($_GET['lang']))
        die($terminal->View($_GET['id'], $_GET['lang']));
    else
        die($terminal->RowView($_GET['id']));

}else{

    include("lib/core.class.php");

    $core = new Core();

    if(@$_GET['mode'] == 'raw')
    	die($core->view_raw(@$_GET['id']));
    else if (@$_GET['mode'] == 'embed')
    	$core->embed(@$_GET['id']);
    else if (@$_GET['mode'] == 'all')
    	die($core->all_pastes());
	
    $core->View(@$_GET['id'], @$_GET['line'], @$_GET['lang']);
}
?>
