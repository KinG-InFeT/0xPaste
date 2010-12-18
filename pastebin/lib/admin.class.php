<?php
/*
 *
 * @project 0xPaste
 * @author KinG-InFeT
 * @licence GNU/GPL
 *
 * @file admin.class.php
 *
 * @link http://0xproject.hellospace.net#0xPaste
 *
 */
 
class Admin extends Security  {

	public function __construct () {
	
			include ("config.php");
			include_once ("mysql.class.php");
			
			$this->sql = new MySQL ($db_host, $db_user, $db_pass, $db_name);
	}
	
	public function show_administration() {
		
		print "<h3 align=\"center\">List of Pastes</h3>\n";	
		
		print "\n<table style=\"border-collapse: collapse;\" border=\"2\" align=\"center\" cellpadding=\"10\" cellspacing=\"1\">"
			. "\n<tbody>"
			. "\n	<tr align=\"center\">"
			. "\n	  <td><b>ID</b></td>"
			. "\n	  <td><b>Title</b></td>"
			. "\n	  <td><b>Author</b></td>"
			. "\n	  <td><b>IP</b></td>"
			. "\n	  <td><b>Date</b></td>"
			. "\n	  <td><b>[Manage]</b></td>"
			. "\n	</tr>";
		
		$this->pastes = $this->sql->sendQuery("SELECT * FROM ".__PREFIX__."pastes ORDER by id DESC");
		
		while($this->paste = mysql_fetch_array($this->pastes)) {
			
			print "\n\t<tr>"
				. "\n	  <td>".$this->paste['id']."</td>"
				. "\n	  <td>".$this->paste['title']."</td>"
				. "\n	  <td>".$this->paste['author']."</td>"
				. "\n	  <td>".$this->paste['ip']."</td>"
				. "\n	  <td>".$this->paste['data']."</td>"
				. "\n	  <td><a href=\"admin.php?action=del_paste&id=".$this->paste['id']."&security=".$_SESSION['token']."\">[X]</a> ~ 
							  <a href=\"admin.php?action=edit_paste&id=".$this->paste['id']."\">[EDIT]</a> ~
							  <a href=\"view.php?id=".$this->paste['id']."\" target=\"_blank\">[VIEW]</a>
						</td>"
				. "\n	</tr>";
		}
		print " </tbody>\n"
			. "</table>\n"
			. "</div>\n";
	}
	
	public function del_paste($id) {
	
		$this->id = intval($id);
		
		$this->my_is_numeric($this->id);
		
		if(empty($this->id)) {
			die("Hacking Attemp!");
		}else{
			$this->security_token($_GET['security'], $_SESSION['token']);
			
			$this->sql->sendQuery("DELETE FROM ".__PREFIX__."pastes WHERE id = '".$this->id."'");

			die(header('Location: admin.php'));
		}
	}
	
	public function settings() {
	
		print "<h2 align=\"center\">Settings</h2><br />\n";

		if(!empty($_POST['title']) && is_numeric($_POST['view_all'])) {
			
			$this->security_token($_POST['security'], $_SESSION['token']);
			
			$this->title    = $this->VarProtect( $_POST['title']  );
			$this->view_all = (int) $_POST['view_all'];
						 			
			$this->sql->sendQuery("UPDATE `".__PREFIX__."config` SET 
									`title` = '".$this->title."',
									`view_all` = ".$this->view_all."");
			
			print "<script>alert(\"Upgrade Settings.\"); window.location=\"admin.php\";</script>";
		
		}else{
			$this->config = mysql_fetch_array($this->sql->sendQuery("SELECT * FROM ".__PREFIX__."config"));
			
			print "\n<br /><br />"
				. "\n<form method=\"POST\" action=\"admin.php?action=settings\" />"
				. "\n<table align=\"center\" style=\"text-align: center;\" border=\"0\" cellpadding=\"2\" width=\"50%\" cellspacing=\"2\">"
				. "\n<tbody>"
				. "\n<tr>"
				. "\n	<td>Title of 0xPaste:</td>"
				. "\n	<td><input type=\"text\" name=\"title\" value=\"".$this->config['title']."\" /></td>"
				. "\n</tr>"
				. "\n<tr>"
				. "\n	<td>View_all the option to make private?:</td>"
				. "\n<td>"
				. "\n<select name=\"view_all\">"
				. "\n<option value=\"1\">No</option>"
				. "\n<option value=\"0\">Yes</option>"
				. "\n</select>"
				. "\n</td>"
				. "\n</tr>"
				. "\n</tbody>"
				. "\n</table>"
				. "\n<br /><p align=\"center\"><input type=\"submit\" value=\"Send\" /></p>"
				. "\n<input type=\"hidden\" name=\"security\" value=\"".$_SESSION['token']."\" />"
				. "\n</form>"
				."";
		}	
	}
		
	public function updates($version) {

		print "<h2 align=\"center\">Upgrade System</h2><br />\n";	
		
		$update = NULL;
		
		if ($fsock = @fsockopen('www.0xproject.hellospace.net', 80, $errno, $errstr, 10)) {
			@fputs($fsock, "GET /versions/0xPaste.txt HTTP/1.1\r\n");
			@fputs($fsock, "HOST: www.0xproject.hellospace.net\r\n");
			@fputs($fsock, "Connection: close\r\n\r\n");
	
			$get_info = FALSE;
			
			while (!@feof($fsock)) {
				if ($get_info)
					$update .= @fread($fsock, 1024);
				else
					if (@fgets($fsock, 1024) == "\r\n")
						$get_info = TRUE;
			}
			
			@fclose($fsock);
			
			$update = htmlspecialchars($update);
	
			if ($version == $update)
				$version_info = "<p style=\"color:green\">There are no updates for your system.</p><br />";
			else
				$version_info = "\n<p style=\"color:red\">Updates are available for the system.<br />\nUpgrade to the latest version:". $update."\n"
							  . "<br /><br />Link Download: <a href=\"http://0xproject.hellospace.net/#0xPaste\">Download Recent Version</a><br /></p>\n";
		}else{
			if ($errstr)
				$version_info = '<p style="color:red">' . sprintf("Unable to open connection to 0xProject Server, reported error is:<br />%s", $errstr) . '</p>';
			else
				$version_info = '<p>Unable to use socket functions.</p>';
		}
		
		return ("<br /><br /><big><big>".$version_info."</big></big>");
	}
	
	public function themes() {
		
		print "<h2 align=\"center\">Theme Management</h2><br />\n";	
		
		if (!empty($_POST['send']) && ($_POST['send'] == 1) && !empty($_POST['theme_file'])) {

			$this->security_token($_POST['security'], $_SESSION['token']);

			$scrivi_file = fopen("style.css","w");
			fwrite($scrivi_file,stripslashes($_POST['theme_file'])) or die("Error writing file style.css");
			fclose($scrivi_file);
				
			print "<script>alert(\"Theme Changed!\"); window.location.href = 'admin.php?action=themes';</script>";

		}else{

			$leggi_file  = fopen("style.css","r");
			$dim_file    = filesize("style.css");
			$this->theme = fread($leggi_file,$dim_file);
			fclose($leggi_file);

			print "\n<form method=\"POST\" action=\"admin.php?action=themes\" />"
				. "\n<p align=\"center\">Edit Themes File:<br />"
				. "\n<textarea name=\"theme_file\" rows=\"25\" cols=\"160\">".htmlspecialchars($this->theme)."</textarea><br />"
				. "\n<input type=\"hidden\" name=\"security\" value=\"".$_SESSION['token']."\" />"
				. "\n<input type=\"hidden\" name=\"send\" value=\"1\" />"
				. "\n<input type=\"submit\" value=\"Edit Theme\" /></p>"
				. "\n</form>"
				. "";
		}
	}
	
	public function change_pass_admin() {
		
		print "<h2 align=\"center\">Change Admin Password</h2><br />\n";
					
		if(!empty($_POST['new_pass'])) {
			$this->security_token($_POST['security'], $_SESSION['token']);
				
			$this->sql->sendQuery("UPDATE ".__PREFIX__."users SET password = '".md5($_POST['new_pass'])."' WHERE id = 1");
			print "<script>alert('Password Changed'); location.href = 'admin.php?action=change_pass_admin';</script>";
		}else{
			print "\n<form method = \"POST\" action=\"admin.php?action=change_pass_admin\" />"
				. "\n<p>New Password: <input type=\"password\" name=\"new_pass\" /><br />"
				. "\n<input type=\"hidden\" name=\"security\" value=\"".$_SESSION['token']."\" />"
				. "\n<input type=\"submit\" value=\"Edit Password\" />"
				. "\n</form></p>"
				. "";	
		}
	}
	
	public function edit_paste($id) {
		
		$this->id = intval($id);
	
		$this->my_is_numeric($this->id);
		
		print "<h2 align=\"center\">Edit Paste</h2><br />\n";
		
		if(empty($this->id)) {
			print "\n<form method = \"POST\" action=\"admin.php?action=edit_paste\" />\n"
				. "\nInserit ID Paste per Editing: <input type=\"text\" name=\"id\" /><br />"
				. "\n<input type=\"submit\" value=\"Send ID\" /></form>";
		}else{
		
	    	if(mysql_num_rows($this->sql->sendQuery("SELECT * FROM ".__PREFIX__."pastes WHERE id = '".$this->id."'")) < 1)
				die("<script>alert(\"Paste does NOT exists!\");location.href = 'admin.php?action=edit_paste';</script>");
	    		
			if (!empty($_POST['author']) && !empty($_POST['title']) && !empty($_POST['code'])) {
					
					$this->security_token($_POST['security'], $_SESSION['token']);
					
			        $this->data    = @date('d/m/y');
			        $this->title   = $this->VarProtect( $_POST['title']  );
			        $this->author  = $this->VarProtect( $_POST['author'] );
			        
			        $this->code    = mysql_real_escape_string( stripslashes( stripslashes($_POST['code'] )));
			
			        $this->sql->sendQuery("UPDATE ".__PREFIX__."pastes SET title = '".$this->title."', author = '".$this->author."', data = '".$this->data."', text = '".$this->code."' WHERE id = '".$this->id."'");
			
			        print "<script>alert(\"Paste edited whit success!\");</script>";
			        print '<script>window.location="admin.php";</script>';
			    }else{
			    	$this->data = mysql_fetch_array($this->sql->sendQuery("SELECT * FROM ".__PREFIX__."pastes WHERE id = '".$this->id."'"));
			    	
					print '<div align="center"><form action="admin.php?action=edit_paste&id='.$this->id.'" method="POST">
						    Title:<br />
    			            <input type="text" name="title" size="50" value="'.htmlspecialchars($this->data['title']).'"/><br /><br />
    			            Author:<br />
    			            <input type="text" name="author" size="50" value="'.htmlspecialchars($this->data['author']).'" /><br /><br />
							<br />
    			            Code:<br />
    			            <textarea name="code" cols="170" rows="25">'.htmlspecialchars($this->data['text']).'</textarea><br /><br />
    			            <input type="submit" value="Edit Paste" />
							<input type="hidden" name="security" value="'.$_SESSION['token'].'" />
    			            </form></div>';
			}
		}
	}
				
}	
?>		

