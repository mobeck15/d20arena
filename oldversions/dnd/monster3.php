<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
include $GLOBALS['rootpath']."/inc/php.ini.inc.php";
include $GLOBALS['rootpath']."/inc/functions.inc.php";

$title="Monster";
echo Get_Header($title);

if(isset($_GET['id'])){
	$monster_row=$_GET['id'];
} else {
	$monster_row=1;
}

$monster = new dndmonster($monster_row);

?>
<br>
<a href="<?php echo $_SERVER['PHP_SELF']."?id=".($monster_row-1); ?>"><-- prev</a> | <a href="<?php echo $_SERVER['PHP_SELF']."?id=".($monster_row+1); ?>">next --></a>

<?php echo $monster->get_statblock(); ?>

<?php echo Get_Footer(); ?>
