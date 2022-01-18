<?php
/*
 * Use this variable to enable or disable debug textdomain
 * All debug text should be wrapped in IFs like below
 * if($Debug_Enabled) {}
 */
$GLOBALS['Debug_Enabled']=false;
$GLOBALS['Debug_Enabled']=true;

date_default_timezone_set("America/Los_Angeles");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//ini_set('memory_limit' , '512M')
//ini_set('memory_limit' , '-1')

$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
include_once $GLOBALS['rootpath']."/inc/template.inc.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/gamefunctions.inc.php";
include_once $GLOBALS['rootpath']."/inc/monster.class.php";
include_once $GLOBALS['rootpath']."/inc/monsterlist.class.php";
include_once $GLOBALS['rootpath']."/inc/globals.inc.php";


?>
