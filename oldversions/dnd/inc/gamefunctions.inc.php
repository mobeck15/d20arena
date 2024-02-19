<?php
function statbonus($stat,$print=false) {
	$bonus=floor(($stat/2)-5);
	if($print) {
		$bonus=formatbonus($bonus);
	}
	return $bonus;
}

function getbasestat($stat) {
	$basestat=($stat/2 == floor($stat/2) ? 10 : 11);
	return $basestat;
}

function getadjustment($stat,$print=false) {
	$adjustment=$stat-getbasestat($stat);
	if($print) {
		$adjustment=formatbonus($adjustment);
	}
	return $adjustment;
}

function formatbonus($bonusvalue) {
	$output="";
	if($bonusvalue>0){
		$output="+".$bonusvalue;
	}
	if($bonusvalue<0){
		$output=$bonusvalue;
	}
	if($bonusvalue==0){
		$output="";
	}
	return $output;
}

function getsizemod($stat,$currsize,$newsize="Medium") { 
	if($currsize == $newsize) {
		return 0;
	}
	
	$sizemod_table=array(
	//0=>array("id"=>"0",                         "newsize"=>"Fine"),
	1=>array("id"=>"1", "oldsize"=>"Fine",      "newsize"=>"Diminutive","str"=>0,"dex"=>-2,"con"=>0,"Armor"=>0,"AC"=>-4,"Attack"=>-4),
	2=>array("id"=>"2", "oldsize"=>"Diminutive","newsize"=>"Tiny",      "str"=>2,"dex"=>-2,"con"=>0,"Armor"=>0,"AC"=>-2,"Attack"=>-2),
	3=>array("id"=>"3", "oldsize"=>"Tiny",      "newsize"=>"Small",     "str"=>4,"dex"=>-2,"con"=>0,"Armor"=>0,"AC"=>-1,"Attack"=>-1),
	4=>array("id"=>"4", "oldsize"=>"Small",     "newsize"=>"Medium",    "str"=>4,"dex"=>-2,"con"=>2,"Armor"=>0,"AC"=>-1,"Attack"=>-1),
	5=>array("id"=>"5", "oldsize"=>"Medium",    "newsize"=>"Large",     "str"=>8,"dex"=>-2,"con"=>4,"Armor"=>2,"AC"=>-1,"Attack"=>-1),
	6=>array("id"=>"6", "oldsize"=>"Large",     "newsize"=>"Huge",      "str"=>8,"dex"=>-2,"con"=>4,"Armor"=>3,"AC"=>-1,"Attack"=>-1),
	7=>array("id"=>"7", "oldsize"=>"Huge",      "newsize"=>"Gargantuan","str"=>8,"dex"=> 0,"con"=>4,"Armor"=>4,"AC"=>-2,"Attack"=>-2),
	8=>array("id"=>"8", "oldsize"=>"Gargantuan","newsize"=>"Colossal",  "str"=>8,"dex"=> 0,"con"=>4,"Armor"=>5,"AC"=>-4,"Attack"=>-4),
	9=>array("id"=>"9", "oldsize"=>"Colossal")
	);
	
	$oldsizeindex=makeIndex($sizemod_table,"oldsize");
	//$newsizeindex=makeIndex($sizemod_table,"newsize");
	
	//var_dump($sizemod_table);	
	
	$newsizeid=$oldsizeindex[$newsize];
	$currsizeid=$oldsizeindex[$currsize];
	
	//echo $currsize . "(".$currsizeid.")<br>";
	//echo $newsize . "(".$newsizeid.")<br>";

	$finalstat=0;
	//echo "Before loop "; var_dump($finalstat);echo "<br>";
	
	if($newsizeid>$currsizeid) {
		//$direction = 1;
		//echo "DIR +<br>";
		for ($x = $currsizeid; $x < $newsizeid; $x++) {
			//var_dump($finalstat);echo "<br>";
			$finalstat += $sizemod_table[$x][$stat];
		}
	} else {
		//$direction = -1;
		//echo "DIR -<br>";
		for ($x = $currsizeid; $x > $newsizeid; $x--) {
			//var_dump($finalstat);echo "<br>";
			$finalstat += $sizemod_table[$x][$stat];
		}
	}
	//echo "After loop "; var_dump($finalstat);echo "<br>";
	
	//$steps = $newsizeid - $currsizeid;
	
	//$startrow = $currsizeid + int(($direction -1)/2)
	
	return $finalstat;
}
?>