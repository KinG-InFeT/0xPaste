<?php
/*
 *
 * @project 0xPaste
 * @author KinG-InFeT
 * @licence GNU/GPL
 *
 * @file core.class.php
 *
 * @link http://0xproject.netsons.org#0xPaste
 *
 */

include ("config.php");
include ("lib/security.class.php");
		
if(!defined("__INSTALLED__"))
	die("Run <a href=\"install.php\">./install.php</a> for Installation 0xPaste!");

class Terminal extends Security {

	public function __construct () {
	
			include ("config.php");				
			include_once ("mysql.class.php");
			
			$this->sql = new MySQL ($db_host, $db_user, $db_pass, $db_name);
	}
	
	public function check_id_exists($ID) {
	
		$this->id = (int) $ID;
		
		$this->past = $this->sql->sendQuery("SELECT * FROM `".__PREFIX__."pastes` WHERE id = '".$this->id."'");
		
		if(mysql_num_rows($this->past) < 1)
			die("Source does not exists!");	
	}
	
	public function PrintHeader() {
		    $this->config = mysql_fetch_array($this->sql->sendQuery("SELECT title FROM `".__PREFIX__."config`"));
	
    	$this->title = (preg_match("/admin/i",$_SERVER['PHP_SELF'])) ? "Administration - 0xPaste" : $this->config['title'];
	
		print "\n<!DOCTYPE html>"
			. "\n<html xmlns=\"http://www.w3.org/1999/xhtml\">"
			. "\n<head>"
			. "\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />"
			. "\n<title>".$this->title."</title>"
			. "\n</script>"									  
			. "\n</head>"
			. "\n<body>";
    }
	
	public function PrintBody() {
	
	    //Thanks sPaste and Stoner
		$this->url = "http://".$_SERVER["HTTP_HOST"].substr($_SERVER["PHP_SELF"],0,strpos($_SERVER["PHP_SELF"],
					 "/",strlen($_SERVER["PHP_SELF"])-strlen(basename($_SERVER["PHP_SELF"]))-1))."/";
					 
	    print '
<pre>
                        .::Terminal Mode of 0xPaste::.

NAME
    0xPaste: command line pastebin (Mode):

SYNOPSIS
    &lt;command&gt; | curl -F \'code=&lt;-\' '.$this->url.'

DESCRIPTION
    add &lang=&lt;lang&gt; to resulting url for line numbers and syntax highlighting (GeShi Core <a target="_blank" href="?mode=terminal&list_lang=1">List Lang</a>)

EXAMPLES
    ~$ cat /etc/passwd | curl -F \'code=&lt;-\' '.$this->url.'
       '.$this->url.'view.php?mode=terminal&id=9999999999
    ~$ firefox '.$this->url.'view.php?mode=terminal&id=9999999999

BACK
    <a href="?set_mode=0">Normal Mode</a>
    
POWERED BY
    <a href="http://www.0xproject.netsons.org/#0xPaste">0xPaste</a>
</pre>';
	    
	}
	
	public function inserit($code) {
			
		if($code == NULL || empty($code))
			die("[ERROR] No code insert!");
		
		//security parser
		$this->title  = 'Terminal Mode';
		$this->author = 'Anonymous';
		$this->lang   = 'text';
		
		$this->text   = mysql_real_escape_string( stripslashes( $code ));
		
		//vari dati
		$this->date   = @date("d/m/y");
		$this->ip     = '127.0.0.1';
		$this->id     = $this->random_id();
		
        $this->expire = 0;
		
		$this->sql->sendQuery(
		  "INSERT INTO `".__PREFIX__."pastes` (`id`, `author`, `title`, `ip`, `language`, `text`, `data`, `expire_date`
			) VALUES (
    	  '".$this->id."', '".$this->author."', '".$this->title."', '".$this->ip."', '".$this->lang."', '".$this->text."', '".$this->date."', '".$this->expire."');"
    	);
		
		//Thanks sPaste and Stoner
		$this->url = "http://".$_SERVER["HTTP_HOST"].substr($_SERVER["PHP_SELF"],0,strpos($_SERVER["PHP_SELF"],
					 "/",strlen($_SERVER["PHP_SELF"])-strlen(basename($_SERVER["PHP_SELF"]))-1))."/";
		
		print $this->url."view.php?mode=terminal&id=".$this->id. "\n";
	}
	
	public function View($ID, $lang) {
	
		$this->id = intval($ID);
	
		if(empty($this->id))
			die("ID not valid!");
	    
	    //Elimino tutti i sorgenti che hanno una durata inferiore a time();
	    $this->sql->sendQuery("DELETE FROM ".__PREFIX__."pastes WHERE expire_date > 0 AND expire_date < " . time());
	
		$this->my_is_numeric($this->id);
		
		$this->check_id_exists($this->id);
		
		require_once("lib/geshi/geshi.php");
		
		$this->info = mysql_fetch_array($this->sql->sendQuery("SELECT * FROM `".__PREFIX__."pastes` WHERE id = '".$this->id."' LIMIT 1;"));
		
		$geshi = new GeSHi($this->info['text'], $lang);
		
		$geshi->set_header_type(GESHI_HEADER_PRE_VALID);
		
		if(!empty($lang))
			$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS,2);
		
		$geshi->set_line_style('font: normal normal 95% \'Courier New\', Courier, monospace; color: #003030;', 'font-weight: bold; color: #006060;', true);
		$geshi->set_line_style('background: #fcfcfc;', 'background: #f0f0f0;',true);
									
		$geshi->set_header_content_style('font-family: Verdana, Arial, sans-serif;  
										 font-size: 90%; 
										 border-bottom: 2px dotted black;
										 padding: 5px;');
										 
		$this->PrintHeader();
		
		print "<br />\n".$geshi->parse_code();
	}
	
    public function RowView($ID) {
		
		$this->id = intval($ID);
	
		if(empty($this->id))
			die("ID does not exists!");
		
		//Elimino tutti i sorgenti che hanno una durata inferiore a time();
	    $this->sql->sendQuery("DELETE FROM ".__PREFIX__."pastes WHERE expire_date > 0 AND expire_date < " . time());
	
		$this->my_is_numeric($this->id);
		
		$this->check_id_exists($this->id);
	
		require_once("lib/geshi/geshi.php");
		
		$this->info = mysql_fetch_array($this->sql->sendQuery("SELECT * FROM `".__PREFIX__."pastes` WHERE id = '".$this->id."' LIMIT 1;"));
		
		$geshi = new GeSHi($this->info['text'], 'text');//text mode
		
		print $geshi->parse_code();
		
	}	
	
    /*
	 * Thanks Stoner (sPaste)
	 */
	public function list_lang() {
	
		$langList = array(	
				
			//default language PHP, C, C++, Python, Perl, Bash, SQL, Text, CSS, HTML
			
			"php" => "PHP", "perl" => "Perl", "sql" => "SQL", "python" => "Python", "c" => "C", "cpp" => "C++", "html5" => "HTML 5",
			"css" => "CSS", "text" => "Text",
			
			//The end default language	
			
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
			"xpp" => "X++", "z80" => "ZiLOG Z80 Assembler", "locobasic" => "Loco Basic", "lsl2" => "LSL 2", "oberon2" => "Oberon",
			//the end geshi language 1.0.8.3
			
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
			
			
			//Upgrade Geshi 1.0.8.10
			"c_loadrunner" => "C (for LoadRunner)", "epc" => "Enerscript", "falcon" => "Falcon", "llvm" => "LLVM", "pli" => "PL/I", "proftpd" => "ProFTPd", 
			"pycon" => "Python (console mode)", "uscript" => "Unreal Script", "bascomavr" => "BASCOM AVR", "coffeescript" => "CoffeeScript",
			"euphoria" => "Euphoria", "html4strict" => "HTML4", "yaml" => "YAML"
			//The end upgrade Geshi 1.0.8.10
		);
		
		$d = opendir("lib/geshi/geshi/");
		$aLang = array();
		
		if($d) {
			while(($file = readdir($d)) !== FALSE) 
				if($file != "." && $file != "..") 
					$aLang[substr($file,0,strlen($file)-4)] = $langList[substr($file,0,strlen($file)-4)];
					
			ksort($aLang);
			
			$out = '';
			    		
			foreach($aLang as $k => $v)
				$out .= "<b>". $k ."</b> -> ".$v. "\n<br />";
			
			closedir($d);
			
			return $out;
			
		}else{
			print "Error! Geshi directory not Open!";
		}
	}
}
?>
