<?php
ob_start();

if(!file_exists("config.php"))
	die("File config.php does not exist!");
else
	include("config.php");

?>
<html>
<head><title>Upgrade File</title></head>
<body>		  
		<h1 align="center">Upgrade System of 0xPaste</h1><br />
		<br />
		Upgrade v2.1.1 to 2.2.0
		<br />
		<br />
		  
<form method="POST" action="?send=1" />
<input type="submit" value="Upgrade" />
</form>
<?php

if(@$_GET['send'] == 1) {
	
	  mysql_connect($db_host, $db_user, $db_pass) or die(mysql_error());
	mysql_select_db($db_name) or die(mysql_error());

	//aggiungo colonna perl'eliminazione temporanea dei sorgenti
	mysql_query("ALTER TABLE `".$db_name."`.`".__PREFIX__."pastes` ADD expire_date TEXT;") or die(mysql_error());
	
	
	print "\n<script>"
	    . "\nalert(\"Upgrade System with success\");"
	    . "\nalert(\"Please, DELETE upgrade file!\");"
	    . "\nwindow.location=\"index.php\";"
	    . "\n</script>";
}
	
?>
</body>
</html>
