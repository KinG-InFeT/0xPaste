<?php
/*
 *
 * @project 0xPaste
 * @author KinG-InFeT
 * @licence GNU/GPL
 *
 * @file core.class.php
 *
 * @link http://0xproject.hellospace.net#0xPaste
 *
 */

include ("config.php");
include ("lib/security.class.php");
		
if(!defined("__INSTALLED__"))
	die("Run <a href=\"install.php\">./install.php</a> for Installation 0xPaste!");

class Core extends Security {
	
	const VERSION = '2.1.0';

	public function __construct () {
	
			include ("config.php");				
			include_once ("mysql.class.php");
			
			$this->sql = new MySQL ($db_host, $db_user, $db_pass, $db_name);
	}
	
	public function check_id_exists($ID) {
	
		$this->id = (int) $ID;
		
		$this->past = $this->sql->sendQuery("SELECT * FROM `".__PREFIX__."pastes` WHERE id = '".$this->id."'");
		
		if(mysql_num_rows($this->past) < 1) {
			$this->Printheader();
			
			die("<div id=\"error\"><h2>Source does not exists!</h2></div>");
			
			$this->PrintFooter();
		}
	
	}
	
	/*
	 * Thanks Stoner (sPaste)
	 */
	private function GetLang() {
		$langList = array(			
			//default language PHP, C, C++, Python, Perl, Bash, SQL, Text, CSS, HTML
			"php" => "PHP", "perl" => "Perl", "sql" => "SQL", "python" => "Python", "c" => "C", "cpp" => "C++", "html4strict" => "HTML",
			"css" => "CSS", "text" => "Text",
			
			//The end default language
			
			//upgrade Geshi 1.0.8.9
			"4cs" => "GADV 4CS", "6502acme" => "MOS 6502 (6510) ACME Cross Assembler format", "6502kickass" => "MOS 6502 (6510) Kick Assembler format",
			"6502tasm" => "MOS 6502 (6510) TASM/64TASS 1.46 Assembler format", "68000devpac" => "Motorola 68000 - HiSoft Devpac ST 2 Assembler format",
			"autoconf" => "Autoconf", "autohotkey" => "Autohotkey", "awk" => "Awk", "bibtex" => "BibTex", "chaiscript" => "Chaiscript",
			"clojure" => "Clojure", "cmake" => "Cmake", "cuesheet" => "Cuesheet", "d" => "D", "e" => "E", "ecmascript" => "ECMAScript",
			"f1" => "Formula One", "fo" => "FO (abas-ERP)", "gambas" => "GAMBAS", "gdb" => "GDB", "genie" => "Genie", "go" => "Go",
			"gwbasic" => "GwBasic", "hicest" => "HicEst", "icon" => "Icon", "j" => "J", "jquery" => "jGuery", "lb" => "Liberty BASIC",
			"logtalk" => "Logtalk", "magiksf" => "MagikSF", "mapbasic" => "MapBasic", "mmix" => "MMIX", "modula2" => "Modula-2",
			"newlisp" => "newlisp", "objeck" => "Objeck Programming Language", "oxygene" => "Oxygene (Delphi Prism)", "oz" => "OZ",
			"pcre" => "PCRE", "perl6" => "Perl 6", "pf" => "OpenBSD Packet Filter", "pike" => "Pike", "powerbuilder" => "PowerBuilder",
			"properties" => "PROPERTIES", "purebasic" => "Purebasic", "q" => "q/kdb+", "rpmspec" => "RPM Specification File",
			"rsplus" => "R / S+", "unicon" => "Unicon (Unified Extended Dialect of Icon)", "vala" => "Vala", "xbasic" => "xBasic",
			"zxbasic" => "ZXBasic", "systemverilog" => "SystemVerilog", "postgresql" => "PostgreSQL", "fsharp" => "F#", "email" => "eMail (mbox)",
			"algol68" => "ALGOL 68", "erlang" => "Erlang",
			//The end upgrade Geshi 1.0.8.9
			
			//start language of geshi 1.0.8.3
			"abap" => "ABAP" , "actionscript" => "ActionScript", "actionscript3"=> "ActionScript 3", "ada"=> "Ada",
			"apache"=> "Apache configuration", "applescript"=> "AppleScript", "apt_sources"=> "Apt sources", "asm"=> "ASM",
			"asp"=> "ASP", "autoit"=> "AutoIt", "avisynth"=> "AviSynth", "bash"=> "Bash", "basic4gl"=> "Basic4GL", "bf"=> "Brainfuck", 
			"blitzbasic"=> "BlitzBasic", "bnf"=> "bnf","boo"=> "Boo", "c_mac"=> "C (Mac)","caddcl"=> "CAD DCL","cadlisp"=> "CAD Lisp",
			"cfdg"=> "CFDG", "cfm"=> "ColdFusion", "cil"=> "CIL","cobol"=> "COBOL", "cpp-qt" => "C++ (QT)", "csharp"=> "C#",
			"d"=> "D", "dcs"=> "DCS", "delphi"=> "Delphi", "diff"=> "Diff", "div"=> "DIV", "dos"=> "DOS", "dot"=> "dot", "eiffel"=> "Eiffel",
			"email"=> "eMail (mbox)", "fortran"=> "Fortran", "freebasic"=> "FreeBasic", "genero"=> "genero", "gettext"=> "GNU Gettext", "glsl"=> "glSlang",
			"gml"=> "GML", "gnuplot"=> "Gnuplot", "groovy"=> "Groovy", "haskell"=> "Haskell", "hq9plus"=> "HQ9+", "idl"=> "Uno Idl",
			"ini"=> "INI", "inno"=> "Inno", "intercal"=> "INTERCAL", "io"=> "Io", "java"=> "Java", "java5"=> "Java(TM) 2 Platform Standard Edition 5.0",
			"javascript"=> "Javascript", "kixtart"=> "KiXtart", "klonec"=> "KLone C", "klonecpp"=> "KLone C++", "latex"=> "LaTeX", "lisp"=> "Lisp",
			"lolcode"=> "LOLcode", "lotusformulas"=> "Lotus Notes @Formulas", "lotusscript"=> "LotusScript", "lscript"=> "LScript", "lua"=> "Lua",
			"m68k"=> "Motorola 68000 Assembler", "make"=> "GNU make", "matlab"=> "Matlab M", "mirc"=> "mIRC Scripting", "modula3"=> "Modula-3",
			"mpasm"=> "Microchip Assembler", "mxml"=> "MXML", "mysql"=> "MySQL", "nsis"=> "NSIS", "objc"=> "Objective-C", "ocaml"=> "OCaml",
			"ocaml-brief" => "OCaml (brief)", "oobas"=> "OpenOffice.org Basic", "oracle11"=> "Oracle 11 SQL", "oracle8"=> "Oracle 8 SQL",
			"pascal"=> "Pascal", "per"=> "per", "php-brief" => "PHP (brief)", "pic16"=> "PIC16",
			"pixelbender"=> "Pixel Bender 1.0", "plsql"=> "PL/SQL", "povray"=> "POVRAY", "powershell"=> "Power Shell", "progress"=> "Progress",
			"prolog"=> "Prolog", "providex"=> "ProvideX", "qbasic"=> "QBasic/QuickBASIC", "rails"=> "Rails", "rebol"=> "REBOL",
			"reg"=> "Microsoft Registry", "robots"=> "robots.txt", "ruby"=> "Ruby", "sas"=> "SAS", "scala"=> "Scala", "scheme"=> "Scheme", "scilab"=> "SciLab",
			"sdlbasic"=> "sdlBasic", "smalltalk"=> "Smalltalk", "smarty"=> "Smarty", "tcl"=> "TCL", "teraterm"=> "Tera Term Macro",
			"thinbasic"=> "thinBasic", "tsql"=> "T-SQL", "typoscript"=> "TypoScript", "vb"=> "Visual Basic", "vbnet"=> "Visual Basic .NET",
			"verilog"=> "Verilog", "vhdl"=> "VHDL", "vim"=> "Vim Script", "visualfoxpro"=> "Visual Fox Pro", "visualprolog"=> "Visual Prolog",
			"whitespace"=> "Whitespace", "whois"=> "Whois Response", "winbatch"=> "Winbatch", "xml"=> "XML", "xorg_conf" => "Xorg configuration",
			"xpp" => "X++", "z80" => "ZiLOG Z80 Assembler", "locobasic" => "Loco Basic", "lsl2" => "LSL 2", "oberon2" => "Oberon"
			//the end geshi language 1.0.8.3
		);
		
		$d = opendir("lib/geshi/geshi/");
		$aLang = array();
		
		if($d) {
			$out = "<select id=\"lang\" name=\"lang\">";
			while(($file = readdir($d)) !== FALSE) 
				if($file != "." && $file != "..") 
					$aLang[substr($file,0,strlen($file)-4)] = $langList[substr($file,0,strlen($file)-4)];
					
			ksort($aLang);
			
			//default language PHP, C, C++, Python, Perl, Bash, SQL, Text, CSS, HTML, Text
			$out .= "\n\t<option value=\"error\" selected>Select a Language</option>"
					. "\n\t<option value=\"text\">Text</option>"
					. "\n\t<option value=\"php\">PHP</option>"
					. "\n\t<option value=\"c\">C</option>"
					. "\n\t<option value=\"cpp\">Cpp</option>"
					. "\n\t<option value=\"perl\">Perl</option>"
					. "\n\t<option value=\"bash\">Bash</option>"
					. "\n\t<option value=\"sql\">SQL</option>"
					. "\n\t<option value=\"python\">Python</option>"
					. "\n\t<option value=\"html4strict\">HTML</option>"
					. "\n\t<option value=\"error\">--------------------------</option>";
								
			//ksort($aLang);
			foreach($aLang as $k => $v)
				$out .= "\n\t<option value=\"".$k."\">".$v."</option>";
				
			
			$out.="</select>";
			
			closedir($d);
			
			return $out;
			
		}else{
			print "Error! Geshi directory not Open!";
		}
	}
	
	public function PrintHeader() {
	
	$this->config = mysql_fetch_array($this->sql->sendQuery("SELECT title FROM `".__PREFIX__."config`"));
	
	$this->title = (preg_match("/admin/i",$_SERVER['PHP_SELF'])) ? "Administration - 0xPaste" : $this->config['title'];
	
		print "\n<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">"
			. "\n<html xmlns=\"http://www.w3.org/1999/xhtml\">"
			. "\n<head>"
			. "\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />"
			. "\n<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />"
			. "\n<title>".$this->title."</title>"
			. "\n<script language=\"JavaScript\">"
			. "\nfunction copia(area) {"
			. "\n\tvar appoggio=eval(\"document.\"+area)"
			. "\n\tappoggio.focus()"
			. "\n\tappoggio.select()"
			. "\n\tintervallo=appoggio.createTextRange()"
			. "\n\tintervallo.execCommand(\"Copy\")"
			. "\n}"
			. "\nfunction check()  {"
			. "\n\tif(document.nopaste.captcha.value == 0) {"
			. "\n\t\talert(\"Captcha Code not inserit!\");"
			. "\n\t\tdocument.nopaste.captcha.focus();"
			. "\n\t\treturn FALSE;"
			. "\n\t}"
			. "\n}"
			. "\n</script>"									  
			. "\n</head>"
			. "\n<body>"
			. "\n<h2 align=\"center\">".$this->title."</h2>";
	}	
	public function PrintBody() {
	
	$this->config = mysql_fetch_array($this->sql->sendQuery("SELECT title FROM `".__PREFIX__."config`"));
	
	$this->lang = @$this->GetLang();
	
		print "\n<table align=\"center\">"
			. "\n<tr>"
			. "\n<td>"
			. "\n<form  onSubmit=\"return check();\" method=\"POST\" action=\"index.php?action=paste\">"
			. "\nTitle:<br /><input type=\"text\" name=\"title\"><br /><br />"
			. "\nAuthor:<br /><input type=\"text\" name=\"author\"><br /><br />"
			. "\nLanguage:<br />"
			. "\n".$this->lang."\n"
			. "\n<br /><br />"
			. "\nCode:<br /><textarea cols=\"100\" rows=\"23\" name=\"code\"></textarea><br /><br />"
			. "\n<img src=\"lib/captcha.php\"><br />"
			. "\nCaptcha Code (Case-Sensitive):<br />"
			. "\n<input name=\"captcha\" type=\"text\" id=\"captcha\"><br /><br />"
			. "\n<input type=\"submit\" value=\"Inserit\">&nbsp;&nbsp;<input type=\"reset\" value=\"Reset\">"
			. "\n</form>\n</tr>\n</td>\n</table>";
	
	}
	
	public function PrintAdminMenu() {
		print "\n <style media=\"all\"></style>"
			. "\n<div>"
			. "\n<ul class=\"menu\">"
			. "\n<li class=\"top\"><a href=\"admin.php\" target=\"_self\" class=\"top_link\"><span>List of Pastes</span></a>"
			. "\n</li>"
			. "\n<li class=\"top\"><a href=\"admin.php?action=settings\" target=\"_self\" class=\"top_link\"><span>Settings</span></a>"
			. "\n</li>"
			. "\n<li class=\"top\"><a href=\"admin.php?action=edit_paste\" target=\"_self\" class=\"top_link\"><span>Edit Paste</span></a>"
			. "\n</li>"
			. "\n<li class=\"top\"><a href=\"admin.php?action=change_pass_admin\" target=\"_self\" class=\"top_link\"><span>Change Admin Pass</span></a>"
			. "\n</li>"
			. "\n<li class=\"top\"><a href=\"admin.php?action=themes\" target=\"_self\" class=\"top_link\"> <span>Theme</span></a>"
			. "\n</li>"
			. "\n<li class=\"top\"><a href=\"admin.php?action=updates\" target=\"_self\" class=\"top_link\"><span>Upgrade</span></a>"
			. "\n</li>"
			. "\n<li class=\"top\"><a href=\"admin.php?action=logout&security=".$_SESSION['token']."\" target=\"_self\" class=\"top_link\"><span>Logout</span></a>"
			. "\n</li>"
			. "\n</ul>"
			. "\n</div>"
			."";
	}
	
	public function PrintFooter() {
		
		$this->footer_link = (preg_match("/(admin|view)/i",$_SERVER['PHP_SELF'])) ? "\n<p align=\"center\"><a href=\"index.php\" >[-Home Page-]</a></p>" : "\n<p align=\"center\"><a href=\"view.php?mode=all\" >[-View all Pastes-]</a>\n<br />\n<a href=\"admin.php\" >[-Administration Panel-]</a></p>";
		
		$this->sources = mysql_num_rows($this->sql->sendQuery("SELECT * FROM ".__PREFIX__."pastes"));
		
		print "\n<br /><br />"
			. "\n<p style=\"float: left;\"><i>Powered By <a href=\"http://0xproject.hellospace.net/#0xPaste\">0xPaste</a> v".Core::VERSION."</p></i>\n"
			. "\n<p style=\"float: right;\">Sources in Database: ".$this->sources."</p>\n"
			. $this->footer_link
			. "\n</body>"
			. "\n</html>";
	}
	
	public function inserit($title, $author, $lang, $code, $captcha) {
		
		if($captcha != $_SESSION['captcha'])
			die("<script>alert(\"Error! Captcha is NOT correct!\"); window.location=\"index.php\";</script>");
		
		if($title == NULL)
			die("<script>alert(\"Error! Title NOT include!\"); window.location=\"index.php\";</script>");
		
		if($author == NULL)
			die("<script>alert(\"Error! Author NOT include!\"); window.location=\"index.php\";</script>");
		
		if(($lang == NULL) || ($lang == 'error'))
			die("<script>alert(\"Error! Language unselected!\"); window.location=\"index.php\";</script>");
			
		if($code == NULL)
			die("<script>alert(\"Error! Source NOT include!\"); window.location=\"index.php\";</script>");
		
		//security parser
		$this->title  = $this->VarProtect( $title  );
		$this->author = $this->VarProtect( $author );
		$this->lang   = $this->VarProtect( $lang   );
		
		$this->text   = mysql_real_escape_string( stripslashes( $code ));
		
		//vari dati
		$this->date   = @date("d/m/y");
		$this->ip     = $_SERVER['REMOTE_ADDR'];
		$this->id     = $this->random_id();
		
		$this->sql->sendQuery("INSERT INTO `".__PREFIX__."pastes` (`id`, `author`, `title`, `ip`, `language`, `text`, `data`
								) VALUES (
							  '".$this->id."', '".$this->author."', '".$this->title."', '".$this->ip."', '".$this->lang."', '".$this->text."',  '".$this->date."');");

		$this->PrintHeader();
		
		//Thanks sPaste and Stoner
		$this->url = "http://".$_SERVER["HTTP_HOST"].substr($_SERVER["PHP_SELF"],0,strpos($_SERVER["PHP_SELF"],
					 "/",strlen($_SERVER["PHP_SELF"])-strlen(basename($_SERVER["PHP_SELF"]))-1));
		
		print "<br /><br /><br /><br />\n<div align=\"center\">"
			. "\nSource added with success, here's your URL to view:<br />"
			. "\n<form name=\"code\" />"
			. "\n<input type=\"text\" size=\"50\" name=\"url\" value=\"".$this->url."/view.php?id=".$this->id."\"><br />"
			. "\n<input type=\"button\" onclick=\"copia('code.url')\" value=\"Select\" name=\"sele\">"
			. "\n</form><br /><br />"
			. "\n<a href=\"".$this->url."/view.php?id=".$this->id."\">View Source</a>\n"
			. "</div>";
		
		$this->PrintFooter();
	}
	
	public function View($ID, $line) {
	
		$this->id = intval($ID);
	
		if(empty($this->id))
			die("<div id=\"error\"><h2>ID does not exists!</h2></div>");
	
		$this->my_is_numeric($this->id);
		
		$this->check_id_exists($this->id);
		
		//Thanks sPaste and Stoner
		$this->url = "http://".$_SERVER["HTTP_HOST"].substr($_SERVER["PHP_SELF"],0,strpos($_SERVER["PHP_SELF"],
					 "/",strlen($_SERVER["PHP_SELF"])-strlen(basename($_SERVER["PHP_SELF"]))-1));
		
		if ($line == 'no')
			$this->line = "<input type=\"button\" value=\"Visual Lines\" onClick=\"location.href='".$this->url."/view.php?id=".$this->id."&line=yes'\">\n";
		else if (($line == NULL) || ($line == 'yes'))
			$this->line = "<input type=\"button\" value=\"No Visual Lines\" onClick=\"location.href='".$this->url."/view.php?id=".$this->id."&line=no'\">\n";
		else if(($line != 'yes') && ($line != 'no') && ($line != NULL))
			die("Hacking Attemp!");
		
		require_once("lib/geshi/geshi.php");
		
		$this->info = mysql_fetch_array($this->sql->sendQuery("SELECT * FROM `".__PREFIX__."pastes` WHERE id = '".$this->id."' LIMIT 1;"));
		
		$geshi = new GeSHi($this->info['text'], $this->info['language']);
		
		$geshi->set_header_type(GESHI_HEADER_PRE_VALID);
		
		if($line == 'yes' || $line == NULL)
			$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS,2);
		
		
		$geshi->set_line_style('font: normal normal 95% \'Courier New\', Courier, monospace; color: #003030;', 'font-weight: bold; color: #006060;', true);
		$geshi->set_line_style('background: #fcfcfc;', 'background: #f0f0f0;',true);
		
		$geshi->set_header_content("<font size=\"3\" >\n
									Title: <b>".$this->info['title']."</b><br />\n
									Author: <b>".$this->info['author']."</b><br />\n
									Date: <b>".$this->info['data']."</b> <br />\n
									Language: <b>".$this->info['language']."</b>\n
									<br />\n
									<p style=\"float: left;\">Line: <b>".$this->line."</b>\n
									<p style=\"float: right;\"><b><a href=\"view.php?mode=raw&id=".$this->id."\" target=\"_blank\" >RAW</a> | <a href=\"view.php?mode=embed&id=".$this->id."\">EMBED</a></p></b><br /><br />\n
									</font>\n");
									
		$geshi->set_header_content_style('font-family: Verdana, Arial, sans-serif;  
										 font-size: 90%; 
										 border-bottom: 2px dotted black;
										 padding: 5px;');
		
		$this->PrintHeader();
		
		print "<br />\n".$geshi->parse_code();
		
		$this->PrintFooter();
	}
	
	public function view_raw($ID) {
		
		$this->id = intval($ID);
	
		if(empty($this->id))
			die("<div id=\"error\"><h2>ID does not exists!</h2></div>");
	
		$this->my_is_numeric($this->id);
		
		$this->check_id_exists($this->id);
	
		require_once("lib/geshi/geshi.php");
		
		$this->info = mysql_fetch_array($this->sql->sendQuery("SELECT * FROM `".__PREFIX__."pastes` WHERE id = '".$this->id."' LIMIT 1;"));
		
		$geshi = new GeSHi($this->info['text'], 'text');//text mode
		
		print $geshi->parse_code();
		
	}
	
	public function embed($ID) {
		
		$this->id = intval($ID);
	
		if(empty($this->id))
			die("<div id=\"error\"><h2>ID does not exists!</h2></div>");
	
		$this->my_is_numeric($this->id);
		
		$this->check_id_exists($this->id);
		
		$this->info = mysql_fetch_array($this->sql->sendQuery("SELECT * FROM `".__PREFIX__."pastes` WHERE id = '".$this->id."' LIMIT 1;"));
		
		//Thanks sPaste and Stoner
		$this->url = "http://".$_SERVER["HTTP_HOST"].substr($_SERVER["PHP_SELF"],0,strpos($_SERVER["PHP_SELF"],
					 "/",strlen($_SERVER["PHP_SELF"])-strlen(basename($_SERVER["PHP_SELF"]))-1));
		
		print "\n<div id=\"embed\">"
			. "\nIn order to embed this content into your website or blog, simply cut and paste one of the following options and you're done! <br />"
			. "\n<i>".$this->url."</i> will take care of hosting, formatting and providing the paste to your users.<br /><br />"
			. "\n<b>Link Embed</b>:<br />"
			. "\n<div id=\"code_box\">".htmlspecialchars("Source of: <b><a href=\"".$this->url."/view.php?id=".$this->info['id']."\">".$this->info['title']."</a></b>")."</div>"
			. "\n<p align=\"center\"><a href=\"".$this->url."/view.php?id=".$this->id."\">Close</a></p>"
			. "\n</div>";
	}
	
	//Thanks for the help DoMiNo :D my brother
	public function all_pastes() {
	
		$this->PrintHeader();
		
		print "<h3 align=\"center\">List of Pastes</h3>\n";
		
		$this->check_view_all = mysql_fetch_array($this->sql->sendQuery("SELECT view_all FROM `".__PREFIX__."config`"));
		
		if($this->check_view_all['view_all'] == 0)
			die("<div id=\"error\"><h2>Sorry, but the section was taken private!</h2></div>");
		
		$language = array();
		
		$this->lang = $this->sql->sendQuery("SELECT language FROM `".__PREFIX__."pastes`");
		
		while ($this->langs = mysql_fetch_array($this->lang)) {
		
			if (!in_array ($this->langs['language'], $language)) {
			
				print "\nSources for the language: <b>".htmlspecialchars($this->langs['language'])."</b>\n<br />\n";
			
				$this->pastes = $this->sql->sendQuery("SELECT * FROM `".__PREFIX__."pastes` WHERE language = '".mysql_real_escape_string($this->langs['language'])."'");
				
				print "\n<ul>";
				
				while ($this->paste = mysql_fetch_array($this->pastes))
					print "\n<li>\n<a href=\"view.php?id=".$this->paste['id']."\" target=\"_blank\">".htmlspecialchars($this->paste['title'])."</a>\n</li>\n<br />";
			}
			print "\n</ul>\n";
			$language[] = $this->langs['language'];
		}
		$this->PrintFooter();
	}
}
?>
