<?php
/*
 * Creates an HTML & CSS Header for each page to ensure uniform look & Feel 
 * Some code is still required to set up a new page, see example below.
 * 
 * include 'inc/functions.inc.php';
 * $title="Settings";
 * echo Get_Header($title);
 */
 
 
 /*<?php 
 // Turn on output buffering 
 // There will be no output until you "flush" or echo the buffer's contents 
 ob_start(); 
 ?>
<!-- Remember, none of this HTML will be sent to the browser, yet! -->
<h1>Hi</h1>
<p>I like PHP.</p>
<?php 
// Put all of the above ouptut into a variable 
// This has to be before you "clean" the buffer 
$content = ob_get_clean(); 
// All of the data that was in the buffer is now in $content 
echo $content; 
?>
*/
 
function Get_Header($title="",$WIP=""){
	header('Content-Type: text/html; charset=utf-8');
	 
	$default_title="D&Decompiled";
	
	if($title=="") {
		$title=$default_title;
	} else {
		$title = $title . " - " . $default_title;
	}
	
	$Template_Header="<HTML>
	<HEAD>
	<title>$title</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
	<link rel=\"shortcut icon\" href=\"/gl6/img/favicon.ico\"/>";
	$Template_Header .= "\n	<link rel=\"stylesheet\" type=\"text/css\" href=\"/gl6/css/style.css\">";
	
	//Needed to support the dynamic navigation menu. 
	//There is a conflict with something in style.css that makes it act weird if style.css is loaded after this one.
	$Template_Header .= "\n	<link rel=\"stylesheet\" type=\"text/css\" href=\"/gl6/css/menu_style2.css\">";

	//Needed to support dynamic lookups
	$Template_Header .= "\n	<link rel=\"stylesheet\" href=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css\" type=\"text/css\" /> ";
	/* 
	//These script links are for Ajax lookup prompts. Not needed in header.
	$Template_Header .= "
	<script type=\"text/javascript\" src=\"http://code.jquery.com/jquery-1.9.1.min.js\"></script>
	<script type=\"text/javascript\" src=\"http://code.jquery.com/ui/1.10.1/jquery-ui.min.js\"></script>";
	*/
	
	$Template_Header .= "
	<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js\"></script>";
	/* Used for floating table header script * /
	$Template_Header .= "
	<script src=\"/js/jquery.floatThead.js\"></script>
	<script type=\"text/javascript\">
		$(function(){
			$('table').floatThead();
		});
	</script>";
	/* */
	$Template_Header .= "
	</HEAD>
	<BODY>";
	
	/* START NAVIGATION */
	/* Multi Level CSS https://www.cssscript.com/create-a-multi-level-drop-down-menu-with-pure-css/ */
	$Template_Header .= get_navmenu(true);
	$Template_Header .= "<div class='main'>";
	
	/* END NAVIGATION */
	
	if($WIP=="WIP"){
		$Template_Header .= "\r\n<div style='background:yellow;color:black'>WORK IN PROGRESS</div>\r\n";
	}
	
	return $Template_Header;
}


/*
 * Creates an HTML & CSS Footer for each page to ensure uniform look & Feel 
 */
function Get_Footer($WIP=""){
	if($WIP=="WIP"){
		$WIP="\r\n<div style='background:yellow;color:black'>WORK IN PROGRESS</div>\r\n";
	}
	$time = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],3);
	$Template_Footer=$WIP."<div class='foot'><span class='execTime'>Loaded in ".$time." Seconds</class><br/>
	Memory Used: ".read_memory_usage()."</div>
	
	</div>
	</BODY>
	</HTML>";

	
	return($Template_Footer);
}

function get_navmenu($dropbar=true){
	if($dropbar) {
		$navmenu  = "\r\n\r\n<div id='main_nav' class='top-nav'>\r\n";
	} else {
		$navmenu  = "<div>\r\n";
	}
		$navmenu .= "\t<ul class='main-navigation'>\r\n";
		$navmenu .= "\t\t<li><a href=\"/dnd\"><img src=\"/gl6/img/favicon.ico\" height=15 />Control</a>\r\n";
		$navmenu .= "\t<ul>\r\n";
			//$navmenu .= "\t\t<li><a href=\"/phpinfo.php\" target='_blank'>PHP Info<img src='/gl6/img/new_window-512.png' height=15 /></a></li>\r\n";
		$navmenu .= "\t</ul></li>\r\n";
		$navmenu .= "\t<li><a>Manage Database</a>\r\n";
		$navmenu .= "\t<ul>\r\n";
			if(($_SERVER['SERVER_NAME'] ?? "localhost") == "localhost") {
				//$navmenu .= "\t\t<li><a href=\"/us_opt1/\" target=\"_blank\">uniserver phpMyAdmin<img src=\"".$GLOBALS['rootpath']."/img/new_window-512.png\" height=15 /></a></li>\r\n";
				$navmenu .= "\t\t<li><a href=\"/phpmyadmin/\" target=\"_blank\">local phpMyAdmin<img src=\"".$GLOBALS['rootpath']."/img/new_window-512.png\" height=15 /></a></li>\r\n";
			} else {
				//https://west1-phpmyadmin.dreamhost.com/index.php
				$navmenu .= "\t\t<li><a href=\"http://data.stuffiknowabout.com\" target=\"_blank\">dreamhost phpMyAdmin<img src=\"".$GLOBALS['rootpath']."/img/new_window-512.png\" height=15 /></a></li>\r\n";
			}
			$navmenu .= "\t\t<li><a href=\"https://www.dropbox.com/home/web/uniserverz/www/gl6\" target=\"_blank\">Dropbox<img src=\"/gl6/img/new_window-512.png\" height=15 /><img src=\"/gl6/img/caret-right.png\" height=15 /></a>\r\n";
				$navmenu .= "\t\t\t<ul><li><a href=\"https://www.dropbox.com/home/web/uniserverz/etc/phpmyadmin\" target=\"_blank\">Database Backups<img src=\"/gl6/img/new_window-512.png\" height=15 /></a></li></ul>\r\n";
			$navmenu .= "\t\t</li>\r\n";
		$navmenu .= "\t</ul></li>\r\n";
		$navmenu .= "\t\t<li><a ><img src=\"/gl6/img/favicon.ico\" height=15 />Tools</a>\r\n";
		$navmenu .= "\t<ul>\r\n";
			$navmenu .= "\t\t<li><a href=\"/dnd/monster.php\"><img src=\"/gl6/img/favicon.ico\" height=15 />Monster</a></li>\r\n";
		$navmenu .= "\t</ul></li>\r\n";
		$navmenu .= "\t</ul>\r\n";
	$navmenu .= "</div>";
	
	return $navmenu;
}

?>
