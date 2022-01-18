<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
include $GLOBALS['rootpath']."/inc/php.ini.inc.php";
include $GLOBALS['rootpath']."/inc/functions.inc.php";

$title="Monster";
echo Get_Header($title);

$conn=get_db_connection();

//$sql="Select * from `dd35_monster` where `name` like '%manticore%'";
$sql="Select * from `dd35_monster` ";
	$monsters=array();
	if($result = $conn->query($sql)){
		
		while($row = $result->fetch_assoc()) {
			//echo "<hr>";var_dump($row);
		$monsters[$row['id']]=$row;
		}
	} else {
		trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
	}
	
	//$monster_row=486;
	$monster_row=$_GET['id'];
	
	//if($_GET['hd']<
?>

<a href="<?php echo $_SERVER['PHP_SELF']."?id=".($_GET['id']-1); ?>"><-- prev</a> | <a href="<?php echo $_SERVER['PHP_SELF']."?id=".($_GET['id']+1); ?>">next --></a>
<table>
<tr>
<th class="hidden">Full Text</th>
<th>Formatted Text</th>
<th>Dynamic Text</th>
</tr>
<tr>
<td class="hidden" valign=top><?php echo $monsters[$monster_row]['full_text'] ?></td>
<td valign=top>

<div topic="<?php echo $monsters[$monster_row]['family']; ?>" level="2"><p><h2><?php echo $monsters[$monster_row]['family']; ?></h2></p>
<table width="100%" border="1" cellpadding="2" cellspacing="2" frame="VOID" rules="ROWS">

<tr maxcol="3" curcol="3">
<td/>
<td><b><?php echo $monsters[$monster_row]['name']; ?></b></td> 
</tr><tr maxcol="3" curcol="3">
<td/>
<td><?php echo $monsters[$monster_row]['size']; ?> <?php echo $monsters[$monster_row]['type']; 

if($monsters[$monster_row]['descriptor']<>""){
	echo " (".$monsters[$monster_row]['descriptor'].")";
} ?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Hit Dice:</b></td> 
<td><?php echo $monsters[$monster_row]['hit_dice']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Initiative:</b></td> 
<td><?php echo $monsters[$monster_row]['initiative']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Speed:</b></td> 
<td><?php echo $monsters[$monster_row]['speed']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Armor Class:</b></td> 
<td><?php echo $monsters[$monster_row]['armor_class']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Base Attack/Grapple:</b></td> 
<td><?php echo $monsters[$monster_row]['base_attack']; ?>/<?php echo $monsters[$monster_row]['grapple']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Attack:</b></td> 
<td><?php echo $monsters[$monster_row]['attack']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Full Attack:</b></td> 
<td><?php echo $monsters[$monster_row]['full_attack']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Space/Reach:</b></td> 
<td><?php echo $monsters[$monster_row]['space']; ?>/<?php echo $monsters[$monster_row]['reach']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Special Attacks:</b></td> 
<td><?php echo $monsters[$monster_row]['special_attacks']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Special Qualities:</b></td> 
<td><?php echo $monsters[$monster_row]['special_qualities']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Saves:</b></td> 
<td><?php echo $monsters[$monster_row]['saves']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Abilities:</b></td> 
<td><?php echo $monsters[$monster_row]['abilities']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Skills:</b></td> 
<td><?php echo $monsters[$monster_row]['skills']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Feats:</b></td> 
<td><?php if($monsters[$monster_row]['bonus_feats']<>""){
	echo $monsters[$monster_row]['bonus_feats']; ?><sup>B</sup>, <?php 
	}
	echo $monsters[$monster_row]['feats'];  ?></td> 

</tr>
<?php 	if($monsters[$monster_row]['epic_feats']<>""){ ?>
<tr maxcol="3" curcol="3">
<td><b>Epic Feats:</b></td> 
<td><?php echo $monsters[$monster_row]['epic_feats']; ?></td> 
</tr><?php } ?>

<tr maxcol="3" curcol="3">
<td><b>Environment:</b></td> 
<td><?php echo $monsters[$monster_row]['environment']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Organization:</b></td> 
<td><?php echo $monsters[$monster_row]['organization']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Challenge Rating:</b></td> 
<td><?php echo $monsters[$monster_row]['challenge_rating']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Treasure:</b></td> 
<td><?php echo $monsters[$monster_row]['treasure']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Alignment:</b></td> 
<td><?php echo $monsters[$monster_row]['alignment']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Advancement:</b></td> 
<td><?php echo $monsters[$monster_row]['advancement']; ?></td> 

</tr>

<tr maxcol="3" curcol="3">
<td><b>Level Adjustment:</b></td> 
<td><?php echo $monsters[$monster_row]['level_adjustment']; ?></td> 

</tr>
<tr maxcol="3" curcol="3">
<td><b>Special abilities:</b></td> 
<td><?php echo $monsters[$monster_row]['special_abilities']; ?></td> 

</tr>
</table>
</div>

</td>
<td valign=top>

<div topic="<?php echo $monsters[$monster_row]['family']; ?>" level="2"><p><h2><?php echo $monsters[$monster_row]['family']; ?></h2></p>
<table width="100%" border="1" cellpadding="2" cellspacing="2" frame="VOID" rules="ROWS">

<tr maxcol="3" curcol="3">
<td/>
<td><b><?php echo $monsters[$monster_row]['name']; ?></b></td> 
</tr><tr maxcol="3" curcol="3">
<td/>
<td><?php echo $monsters[$monster_row]['size']; ?> <?php echo $monsters[$monster_row]['type']; 

if($monsters[$monster_row]['descriptor']<>""){
	echo " (".$monsters[$monster_row]['descriptor'].")";
} ?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Hit Dice:</b></td> 
<td><?php 
unset($matches);
preg_match('/(?<hdcount>[0-9]+)d(?<hdsize>[0-9]+)(?:\+(?<hdbonus>[0-9]+))* \((?<hdavghp>[0-9]+) hp\)/', $monsters[$monster_row]['hit_dice'], $matches);
//var_dump($matches); echo "<hr>";
$monster_calc['hdcount']=$matches['hdcount'];
$monster_calc['hdsize'] =$matches['hdsize'];
$monster_calc['hdbonus']=$matches['hdbonus'];
$monster_calc['hdbonus']=$matches['hdbonus'];
$monster_calc['hdavghp']=$matches['hdavghp'];
/*
if(isset($monster_calc) && is_array($monster_calc)) {
	$monster_calc = array_merge($monster_calc, $matches);
} else {
	$monster_calc=$matches;
}
*/
echo $monster_calc['hdcount']."d".$monster_calc['hdsize'];
if ($monster_calc['hdbonus']<>"") {
	echo "+".$monster_calc['hdbonus'];
}
echo " (".$monster_calc['hdavghp']." hp)"; ?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Initiative:</b></td> 
<td><?php echo $monsters[$monster_row]['initiative']; ?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Speed:</b></td> 
<td><?php 
//([, ]*[a-zA-Z]* *[0-9]+ ft.(?: \((\w+)\))*(?: \([0-9]+ squares\))*)
//[, ]*([a-zA-Z]*) *([0-9]+) ft.(?: \((\w+)\))*(?: \([0-9]+ squares\))*
unset($matches);
preg_match_all('/[, ]*(?<spdtype>[a-zA-Z]*) *(?<speed>[0-9]+) ft.(?: \((?<spdskill>\w+)\))*(?: \([0-9]+ squares\))*/', $monsters[$monster_row]['speed'], $matches,PREG_SET_ORDER);
//var_dump($matches); echo "<hr>";

$speedtext="";
foreach ($matches as $value) {
    //var_dump($value); echo "<hr>";
	if($value['spdtype']==""){
		$value['spdtype']="Land";
	}
	$monster_calc[$value['spdtype']]['spdtype']=$value['spdtype'];
	$monster_calc[$value['spdtype']]['speed']=$value['speed'];
	if(isset($value['spdskill'])) {
		$monster_calc[$value['spdtype']]['spdskill']=$value['spdskill'];
	}
	
	if($speedtext<>"") {
		$speedtext.=", ";
	}
	
	if($value['spdtype']<>"Land") {
		$speedtext .=$value['spdtype']." ";
	}
	$speedtext .=$value['speed']." ft.";
	if(isset($value['spdskill'])) {
		$speedtext .=" (".$value['spdskill'].")";
	}
	$speedtext .=" (".($value['speed']/5)." squares)";
}
echo $speedtext;


//echo $monsters[$monster_row]['speed']; 
?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Armor Class:</b></td> 
<td><?php 
//([0-9]+) \(((?:[+|-][0-9]+ [a-zA-Z ]+[, ]*)+)\), touch ([0-9]+), flat-footed ([0-9]+)
preg_match('/(?<ac>[0-9]+) \((?<acmods>(?:[+|-][0-9]+ [a-zA-Z ]+[, ]*)+)\), touch (?<touchac>[0-9]+), flat-footed (?<ffac>[0-9]+)/', $monsters[$monster_row]['armor_class'], $matches);
//preg_match_all('/(?<ac>[0-9]+) \((?<acmods>(?<acmod>[+|-][0-9]+ [a-zA-Z ]+[, ]*)+)\), touch (?<touchac>[0-9]+), flat-footed (?<ffac>[0-9]+)/', $monsters[$monster_row]['armor_class'], $matches,PREG_SET_ORDER);
//([\+|\-][0-9]+ [a-z A-Z]+)[,|)]
//var_dump($matches); echo "<hr>";

preg_match_all('/(?<acmod>[\+|\-][0-9]+) (?<actype>[a-z A-Z]+)/', $matches['acmods'], $matches2,PREG_SET_ORDER);
//echo "<pre>";var_dump($matches2); echo "</pre><hr>";

echo $matches['ac'] . " (";
//echo $matches['acmods'];

foreach($matches2 as $key => $value) {
	echo $value['acmod'] . " " . $value['actype'];
	if($key+1<count($matches2)) {
		echo ", ";
	}
}
echo "), touch " . $matches['touchac'] . ", flat-footed ".$matches['ffac'];
//echo "<hr>"; echo $monsters[$monster_row]['armor_class']; 
?>
</td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Base Attack/Grapple:</b></td> 
<td><?php echo $monsters[$monster_row]['base_attack']; ?>/<?php echo $monsters[$monster_row]['grapple']; ?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Attack:</b></td> 
<td><?php 
//(?<attack>[a-z A-Z]+) (?<atkbonus>[\+|\-][0-9]+) (?<atktype>[a-z A-Z]+) \((?<atkdmg>[0-9]+d[0-9]+[\+\-][0-9]+)\)( or )*
$atkregex="(?<atkqty>[0-9]*) ?(?<attack>[a-z A-Z]+) (?<atkbonus>[\/\+\-0-9]+) (?<atktype>[a-z A-Z]+) \((?<atkdmg>(?<atkdmgdice>[0-9]+)d(?<atkdmgdie>[0-9]+)(?<atkdmgbonus>[\+\-][0-9]+)?)\/?(?<critrange>[0-9]+-[0-9]+)?x?(?<dmgqty>[0-9]+)?(?: plus (?<dmgelemental>(?<dmgelementaldice>[0-9]+)(?:d(?<dmgelementaldie>[0-9]+))?(?<dmgelementalbonus>[\+\-][0-9]+)?)? ?(?<dmgelement>[a-zA-Z ]+))?\)(?: or | and)?";
preg_match_all('/'.$atkregex.'/', $monsters[$monster_row]['attack'], $matches,PREG_SET_ORDER);
//var_dump($matches); echo "<hr>";

foreach($matches as $key => $value) {
	echo $value['attack'] . " " . $value['atkbonus']. " " . $value['atktype']. " (" . $value['atkdmg'] ;
	if (isset($value['critrange']) && $value['critrange']<>""){
		echo "/" . $value['critrange'];
	}
	if (isset($value['dmgelement']) && $value['dmgelement']<>""){
		echo " plus " . $value['dmgelement'];
	}
	echo ")";
	if($key+1<count($matches)) {
		echo " or ";
	}
}
//echo "<hr>";

//echo $monsters[$monster_row]['attack']; 
/* 
https://regex101.com/

(?<atkqty>[0-9]*) ?(?<attack>[a-z A-Z]+) (?<atkbonus>[\+|\-][0-9]+) ?(?<atktype_A>[a-z A-Z]+)* \((?<atkdmg>(?<atkdmgdice>[0-9]+)d(?<atkdmgdie>[0-9]+)(?<atkdmgbonus>[\+\-][0-9]+)?)(?:\/x)?(?<dmgcrit>[0-9]+)?(?: plus (?<dmgelemental>(?<dmgelementaldice>[0-9]+)(?:d(?<dmgelementaldie>[0-9]+))?(?<dmgelementalbonus>[\+\-][0-9]+)?) (?<dmgelement>[a-zA-Z]+))?\) ?(?<atktype_B>(?!or)[a-z A-Z]+)*(?<seperator> or | and )?

Sample attack strings
Greatclub +16 melee (2d8+10) or slam +15 melee (1d4+7) or rock +8 ranged (2d6+7)
Slam +7 melee (1d6+7)
2 claws +13 melee (2d6+2) or 2 wings +13 melee (2d8+2)
Warhammer +3 melee (1d8+1/x3 plus 1 fire) or shortspear +3 ranged (1d6+1 plus 1d4+1 fire)
Spear +1 melee (1d6-1/x3) or sling +3 ranged (1d3)
Bite +37 (2d8+10) melee, 2 Claws +37 (2d6+5) melee, 2 Wings +36 (1d8+5) melee, Tail Slap +36 (2d6+15) melee
Glaive +9/+4 melee (1d10+3 plus infernal wound) or 2 claws +8 melee (1d6+2)

Marilith (171) has attacks with 'and' and 'or' multiple times. 
Primary longsword +25/+20/+15/+10 melee (2d6+9/19-20) and 5 longswords +25 melee (2d6+4/19-20) and tail slap +22 melee (4d6+4); or 6 slams +24 melee (1d8+9) and tail slap +22 melee (4d6+4)


*/


?></td> 
</tr>

<tr maxcol="3" curcol="3">
<td><b>Full Attack:</b></td> 
<td><?php 
preg_match_all('/'.$atkregex.'/', $monsters[$monster_row]['full_attack'], $matches,PREG_SET_ORDER);
foreach($matches as $key => $value) {
	if($value['atkqty']<>"") { 
		echo $value['atkqty'] . " ";
	}
	echo $value['attack'] . " " . $value['atkbonus']. " " . $value['atktype']. " (" . $value['atkdmg'];
	if (isset($value['critrange']) && $value['critrange']<>""){
		echo "/" . $value['critrange'];
	}
	if (isset($value['dmgqty']) && $value['dmgqty']<>""){
		echo "x" . $value['dmgqty'];
	}

	if (isset($value['dmgelement']) && $value['dmgelement']<>""){
		echo " plus " . $value['dmgelement'];
	}


	echo ")";
	if($key+1<count($matches)) {
		echo " or ";
	}
}
echo "<hr>";
echo $monsters[$monster_row]['full_attack']; ?></td> 
</tr>



</table>

</td>
</tr>
</table>


<?php echo Get_Footer(); ?>
