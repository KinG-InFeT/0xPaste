<?php
/*
 *
 * @project 0xPaste
 * @author KinG-InFeT
 * @licence GNU/GPL
 *
 * @file login.class.php
 *
 * @link http://0xproject.hellospace.net#0xPaste
 *
 */

class Login extends Security {

	public function __construct () {
	
			include ("config.php");
			include_once ("mysql.class.php");
			
			$this->sql = new MySQL ($db_host, $db_user, $db_pass, $db_name);
	}
	
	public function is_admin($pass) {
		
		$this->password = $this->VarProtect ($pass);
		
		$query = $this->sql->sendQuery("SELECT * FROM ".__PREFIX__."users WHERE password='".$this->password."'");
		
		while ($ris = mysql_fetch_array ($query)) {
		
			if ($this->password == $ris['password'])
				return TRUE;
			else
				return FALSE;
		}
	}
	
	public function send_login ($password) {
	
		$this->password = md5($password);
		
		$this->login = $this->sql->sendQuery ("SELECT * FROM ".__PREFIX__."users WHERE password = '".$this->password."' LIMIT 1");
		
		while ($this->user = mysql_fetch_array ($this->login)) {
		
			if ($this->password == $this->user['password']) {			
			
				setcookie ("password", $this->password, time () + (3600 * 24), "/");
				
				$this->token = $this->generate_token();
				
				$_SESSION['token'] = $this->token;	
	
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}
	
	public function logout($pass) {
		
		$this->password = $this->VarProtect($pass);		
		
		if($this->is_admin($this->password) == TRUE) {
			setcookie ("password", $this->password, time () - (3600 * 24), "/");
			
			print "\n<script>window.location=\"index.php\";</script>";
		}else{
			die("<script>window.location=\"index.php\";</script>");
		}
	}
	
	public function form_login($pass) {
	
		if($this->is_admin($pass) == FALSE) 
		{
			if(!empty($_POST['pass'])) 
			{	
				if($this->send_login($_POST['pass']) == FALSE)
					die("<div id=\"error\"><b>Error! username or Password does not correct!</b><br /><br />\n<a href=\"admin.php\">Back</a></div>");
			}else{
			
				die(  "\n<fieldset>"
		   			. "\n<legend>Login</legend>"
					. "\n     <br /><p align=\"center\">"
					. "\n     <FORM action=\"admin.php\" method=\"POST\">"
					. "\n     Password :"
					. "\n     <INPUT type=\"password\" name=\"pass\" Style=\"Color: #0044FF; Font-Size: 11\"><br /><br />"
					. "\n     <INPUT type=\"submit\" value=\"Login\"> </p>"
					. "\n     </FORM><br />"
					. "\n </fieldset>");
			}
		}
	}
}
?>
