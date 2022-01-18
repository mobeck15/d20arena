<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
include $GLOBALS['rootpath']."/inc/php.ini.inc.php";
include $GLOBALS['rootpath']."/inc/functions.inc.php";

$title="Monster";
echo Get_Header($title);

$conn=get_db_connection();

/*
Queries in progress

Full Monster
SELECT 
`name`,`size`,`monstertype`
FROM `d20_monster`
JOIN `d20_size` on `d20_monster`.`sizeid`=`d20_size`.`sizeid`
join `d20_monstertype` on `d20_monster`.`typeid`=`d20_monstertype`.`monstertypeid`

*/


$sql="Select * from `d20_monster` ";
if($result = $conn->query($sql)){
	while($row = $result->fetch_assoc()) {
		$monsters[$row['monsterid']]=$row;
	}
} else {
	trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
}

$sql="Select * from `d20_monstertype` ";
if($result = $conn->query($sql)){
	while($row = $result->fetch_assoc()) {
		$monstertype[$row['monstertypeid']]=$row;
	}
} else {
	trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
}

$sql="Select * from `d20_size` ";
if($result = $conn->query($sql)){
	while($row = $result->fetch_assoc()) {
		$size[$row['sizeid']]=$row;
	}
} else {
	trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
}

$sql="Select `skillid`,`skill`,`ability`,`trained`,`armorcheck` from `d20_skill` ";
if($result = $conn->query($sql)){
	while($row = $result->fetch_assoc()) {
		$skill[$row['skillid']]=$row;
	}
} else {
	trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
}

$sql="SELECT `d20_monsterskill`.* , `skill`,`ability` FROM `d20_monsterskill` 
join `d20_skill` on `d20_monsterskill`.`skillid`=`d20_skill`.`skillid`";
if($result = $conn->query($sql)){
	while($row = $result->fetch_assoc()) {
		$monsters[$row['monsterid']]['skill'.$row['bonustype']][$row['skillid']]=$row;
	}
} else {
	trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
}

$sql="SELECT `monsterid`, `d20_subtype`.`subtype` FROM `d20_monster_subtype` 
join `d20_subtype` on `d20_subtype`.`subtypeid`=`d20_monster_subtype`.`subtypeid`";
if($result = $conn->query($sql)){
	while($row = $result->fetch_assoc()) {
		$monsters[$row['monsterid']]['subtype'][]=$row['subtype'];
	}
} else {
	trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
}

$sql="Select * from `d20_monspeed` ";
if($result = $conn->query($sql)){
	while($row = $result->fetch_assoc()) {
		$key=$row['terrain'];
		if($row['terrain']==""){
			$key="land";
		}
		$monsters[$row['monsterid']]['speed'][$key]=array(
		"terrain" => $row['terrain'],
		"speed" => $row['speed'],
		"manuverability" => $row['manuverability']
		);
	}
} else {
	trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
}

$conn -> close();

//var_dump($monsters);

$monster_row=$_GET['id'];

//if($_GET['hd']<
?>

<a href="<?php echo $_SERVER['PHP_SELF']."?id=".($_GET['id']-1); ?>"><-- prev</a> | <a href="<?php echo $_SERVER['PHP_SELF']."?id=".($_GET['id']+1); ?>">next --></a>

<div topic="<?php echo $monsters[$monster_row]['family']; ?>" level="2"><p><h2><?php echo $monsters[$monster_row]['family']; ?></h2></p>
<table width="100%" border="1" cellpadding="2" cellspacing="2" frame="VOID" rules="ROWS">

<tr maxcol="3" curcol="3">
<td/>
<td><b><?php echo $monsters[$monster_row]['name']; ?></b></td> 
</tr><tr maxcol="3" curcol="3">
<td/>
<td><?php echo $size[$monsters[$monster_row]['sizeid']]['size']; ?> <?php echo $monstertype[$monsters[$monster_row]['typeid']]['monstertype']; 

if(isset($monsters[$monster_row]['subtype'])){
	echo " (";
	echo implode(', ', $monsters[$monster_row]['subtype']);
	echo ")";
}
?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Hit Dice:</b></td> 
<td><?php 

$hpbonus=statbonus($monsters[$monster_row]['con'])*$monsters[$monster_row]['hitdice'];
echo $monsters[$monster_row]['hitdice']."d".$monstertype[$monsters[$monster_row]['typeid']]['hdsize']."+".$hpbonus;

$avghpbase=floor((($monstertype[$monsters[$monster_row]['typeid']]['hdsize']+1)/2)*$monsters[$monster_row]['hitdice']);
$avghp=$avghpbase+$hpbonus;

echo " (".$avghp." hp)"; ?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Initiative:</b></td> 
<td><?php echo statbonus($monsters[$monster_row]['dex'],true); ?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Speed:</b></td> 
<td><?php
$speed_text="";
foreach($monsters[$monster_row]['speed'] as $speedrow) {
	if($speed_text<>""){$speed_text .= ", ";}
	if($speedrow['terrain'] == "") {
		$speed_text .= $speedrow['speed'] . " ft. (".($speedrow['speed']/5)." squares)";
	} else {
		$speed_text .= $speedrow['terrain'] . " " . $speedrow['speed'] . " ft.";
		if($speedrow['manuverability'] <> "") {
			$speed_text .= " (".$speedrow['manuverability'].")";
		}
	}
}
echo $speed_text;
?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Armor Class:</b></td> 
<td><?php 
echo 10+$size[$monsters[$monster_row]['sizeid']]['size_mod']+statbonus($monsters[$monster_row]['dex'])+$monsters[$monster_row]['natural_armor'];
echo " (";
if($size[$monsters[$monster_row]['sizeid']]['size_mod']<>0) {
	echo formatbonus($size[$monsters[$monster_row]['sizeid']]['size_mod'])." size, "; 
}
echo statbonus($monsters[$monster_row]['dex'],true)." Dex, "; 
echo formatbonus($monsters[$monster_row]['natural_armor'])." natural"; 
echo ")"; 
?> [[EQUIPMENT]]</td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Base Attack/Grapple:</b></td> 
<td><?php 
//=rounddown(D8*INDEX(Reference!E29:E45,match(B13,Reference!B29:B45,0)))
$baseattack=floor($monsters[$monster_row]['hitdice']*$monstertype[$monsters[$monster_row]['typeid']]['atkbonus']/4);
echo formatbonus($baseattack);
echo "/";
echo formatbonus($baseattack+statbonus($monsters[$monster_row]['str'])+$size[$monsters[$monster_row]['sizeid']]['grapple_mod']);

 ?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Attack:</b></td> 
<td>[[ATTACKS]]</td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Full Attack:</b></td> 
<td>[[ATTACKS]]</td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Space/Reach:</b></td> 
<td><?php 
echo ($size[$monsters[$monster_row]['sizeid']]['space']*1)." ft./";
echo $size[$monsters[$monster_row]['sizeid']][$monsters[$monster_row]['shape']."reach"]." ft.";
//$size[$monsters[$monster_row]['sizeid']]['size_mod']
?></td> 
</tr>

</tr>
<tr maxcol="3" curcol="3">
<td><b>Special Attacks:</b></td> 
<td>[[SPECIAL ATTACKS]]</td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Special Qualities:</b></td> 
<td>[[QUALITIES]]</td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Saves:</b></td> 
<td><?php 
echo "Fort ";
echo "Ref ";
echo "Will ";
?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Abilities:</b></td> 
<td><?php 
echo "Str ".$monsters[$monster_row]['str'].", "; 
echo "Dex ".$monsters[$monster_row]['dex'].", "; 
echo "Con ".$monsters[$monster_row]['con'].", "; 
echo "Int ".$monsters[$monster_row]['intx'].", "; 
echo "Wis ".$monsters[$monster_row]['wis'].", "; 
echo "Cha ".$monsters[$monster_row]['cha']; 
?></td>
<td>
<details>
<summary><b>Statblock</b></summary>
<table>
<tr><th>Stat</th><th>Score</th><th>Bonus</th><th>Base Stat</th><th>Racial adjustment</th><th>Size Adjustment</th><th>Racial adjustment<br>(As Medium)</th></tr>
<?php $stat="str"; ?>
<tr><td><?php echo ucfirst($stat); ?></td>
<td><?php echo $monsters[$monster_row][$stat]; ?></td>
<td><?php echo statbonus($monsters[$monster_row][$stat],true); ?></td>
<td><?php echo getbasestat($monsters[$monster_row][$stat]); ?></td>
<td><?php echo getadjustment($monsters[$monster_row][$stat],true);?></td>
</tr>
<?php $stat="dex"; ?>
<tr><td><?php echo ucfirst($stat); ?></td>
<td><?php echo $monsters[$monster_row][$stat]; ?></td>
<td><?php echo statbonus($monsters[$monster_row][$stat],true); ?></td>
<td><?php echo getbasestat($monsters[$monster_row][$stat]); ?></td>
<td><?php echo getadjustment($monsters[$monster_row][$stat],true);?></td>
</tr>
<?php $stat="con"; ?>
<tr><td><?php echo ucfirst($stat); ?></td>
<td><?php echo $monsters[$monster_row][$stat]; ?></td>
<td><?php echo statbonus($monsters[$monster_row][$stat],true); ?></td>
<td><?php echo getbasestat($monsters[$monster_row][$stat]); ?></td>
<td><?php echo getadjustment($monsters[$monster_row][$stat],true);?></td>
</tr>
<?php $stat="intx"; ?>
<tr><td><?php echo ucfirst("Int"); ?></td>
<td><?php echo $monsters[$monster_row][$stat]; ?></td>
<td><?php echo statbonus($monsters[$monster_row][$stat],true); ?></td>
<td><?php echo getbasestat($monsters[$monster_row][$stat]); ?></td>
<td><?php echo getadjustment($monsters[$monster_row][$stat],true);?></td>
</tr>
<?php $stat="wis"; ?>
<tr><td><?php echo ucfirst($stat); ?></td>
<td><?php echo $monsters[$monster_row][$stat]; ?></td>
<td><?php echo statbonus($monsters[$monster_row][$stat],true); ?></td>
<td><?php echo getbasestat($monsters[$monster_row][$stat]); ?></td>
<td><?php echo getadjustment($monsters[$monster_row][$stat],true);?></td>
</tr>
<?php $stat="cha"; ?>
<tr><td><?php echo ucfirst($stat); ?></td>
<td><?php echo $monsters[$monster_row][$stat]; ?></td>
<td><?php echo statbonus($monsters[$monster_row][$stat],true); ?></td>
<td><?php echo getbasestat($monsters[$monster_row][$stat]); ?></td>
<td><?php echo getadjustment($monsters[$monster_row][$stat],true);?></td>
</tr>
</table>
</details>
</td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Skills:</b></td> 
<?php

//placeholder for armorcheck modifier from equipment.
$armorcheck=0;
if($monster_row==1){$armorcheck=-1;}

$skillranksused=0;
$skilltext="";
$skilltextdetail="";
foreach($skill as $skillrow){
	if($skillrow['ability']<>"") { 
		if($skillrow['trained']==0 OR isset($monsters[$monster_row]['skillranks'][$skillrow['skillid']])) {
			$totalskillbonus=0;
			$showskill=false;
			$skillrow['useability']=$skillrow['ability'];
			if($skillrow['ability']=="int"){
				$skillrow['useability']="intx";
			}
			$skilltextdetail .= "<tr>";
			$skilltextdetail .= "<td>".$skillrow['skill']."</td>";
			$skilltextdetail .= "<td>".$skillrow['ability']."</td>";
			$skilltextdetail .= "<td>".statbonus($monsters[$monster_row][$skillrow['useability']],true)."</td>";
			$totalskillbonus+=statbonus($monsters[$monster_row][$skillrow['useability']]);
			$skilltextdetail .= "<td>";
			if($skillrow['armorcheck']==1) {
				$skilltextdetail .= formatbonus($armorcheck);
				$totalskillbonus+=$armorcheck;
			}
			$skilltextdetail .= "</td>";
			$skilltextdetail .= "<td>";
			if(isset($monsters[$monster_row]['skillrace'][$skillrow['skillid']])){
				$skilltextdetail .= formatbonus($monsters[$monster_row]['skillrace'][$skillrow['skillid']]['ranks']);
				$totalskillbonus+=$monsters[$monster_row]['skillrace'][$skillrow['skillid']]['ranks'];
				$showskill=true;
			}
			$skilltextdetail .= "</td>";
			$skilltextdetail .= "<td>";
			if(isset($monsters[$monster_row]['skillranks'][$skillrow['skillid']])){
				$skilltextdetail .= formatbonus($monsters[$monster_row]['skillranks'][$skillrow['skillid']]['ranks']);
				$totalskillbonus+=$monsters[$monster_row]['skillranks'][$skillrow['skillid']]['ranks'];
				$skillranksused+=$monsters[$monster_row]['skillranks'][$skillrow['skillid']]['ranks'];
				$showskill=true;
			}
			$skilltextdetail .= "</td>";
			$skilltextdetail .= "<td>";
			$skilltextdetail .= "</td>";
			$skilltextdetail .= "<td>";
			$skilltextdetail .= formatbonus($totalskillbonus);
			if($showskill==true) {
				if ($skilltext<>""){$skilltext.=", ";}
				$skilltext.=$skillrow['skill'] . " " . formatbonus($totalskillbonus);
			}
			
			$skilltextdetail .= "</td>";
			$skilltextdetail .= "<td>".$skillrow['skillid']."</td>";
			$skilltextdetail .= "<tr>";
		}
	}
}


?>
<td><?php echo $skilltext; ?></td> 
<td>Skill Points Available: 
<?php
$totalskillpoints=(($monsters[$monster_row]['hitdice']+3)*($monstertype[$monsters[$monster_row]['typeid']]['skillpoints']+statbonus($monsters[$monster_row]['intx'])));
//As long as a creature has an Intelligence of at least 1, it gains a minimum of 1 skill point per Hit Die.
if ($monsters[$monster_row]['intx']>=1 && $totalskillpoints<$monsters[$monster_row]['hitdice']){
	$totalskillpoints=$monsters[$monster_row]['hitdice']+3;
}
echo $totalskillpoints;
echo " ((".$monsters[$monster_row]['hitdice']."+3)*(".$monstertype[$monsters[$monster_row]['typeid']]['skillpoints']."+".statbonus($monsters[$monster_row]['intx'])."))";
?>
<details>
<Summary>All Skills</Summary>
<table>
<tr><th>Skill</th><th>Ability</th><th>Bonus</th><th>Armorcheck</th><th>Race bonus</th><th>Ranks</th><th>Synergy</th><th>Total</th></tr>
<?php


echo $skilltextdetail;
?>
</table>
</details>
Total skill ranks used: <?php echo $skillranksused; ?>
</td>

</tr>
<tr maxcol="3" curcol="3">
<td><b>Feats:</b></td> 
<td>[[FEATS]]</td> 
<td>Feats Available: 
<?php
echo (1+floor($monsters[$monster_row]['hitdice']/3));
?>
</td>
</tr>

<tr maxcol="3" curcol="3">
<td><b>Environment:</b></td> 
<td><?php
echo $monsters[$monster_row]['climate'] . " " . $monsters[$monster_row]['environment'];
 ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Organization:</b></td> 
<td>[[ORGANIZATION]]</td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Challenge Rating:</b></td> 
<td><?php echo $monsters[$monster_row]['challengerating']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Treasure:</b></td> 
<td><?php echo $monsters[$monster_row]['treasure']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Alignment:</b></td> 
<td><?php
echo $monsters[$monster_row]['align_frequency'] . " " . $monsters[$monster_row]['align_culture'] . " " . $monsters[$monster_row]['align_moral'];
 ?></td>  

</tr>
<tr maxcol="3" curcol="3">
<td><b>Advancement:</b></td> 
<td>[[ADVANCEMENT]]</td> 

</tr>

<tr maxcol="3" curcol="3">
<td><b>Level Adjustment:</b></td> 
<td><?php 
if($monsters[$monster_row]['level_adjust']=="") {
	echo "&mdash;";
} else {
	echo $monsters[$monster_row]['level_adjust']; 
}
?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Description:</b></td> 
<td><?php echo nl2br($monsters[$monster_row]['description']); ?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Combat:</b></td> 
<td><?php echo nl2br($monsters[$monster_row]['combat']); ?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Abilities:</b></td> 
<td>[[ABILITIES]]</td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Source:</b></td> 
<td><?php echo $monsters[$monster_row]['source']; ?></td> 
</tr>

</table>
</div>


<?php echo Get_Footer(); ?>
