<?php

/*
 * Checks if this file has already been loaded in a previous include statement and throws an warning if true.
 */
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ",E_USER_WARNING  );
}
$GLOBALS[__FILE__]=1;


/* 
 * takes an integer and an inputunit as an input and returns a duration formatted string hh:mm:ss
 * inputunit can be "hours" (default), "minutes", or "seconds"
 */
function timeduration($time,$inputunit="hours"){
	$positive=true;
	
	if($time<0){
		$time=(abs($time));
		$positive=false;
	}
	switch ($inputunit){
		case "hours":
			$time=$time*60;
		case "minutes":
			$time=$time*60;
		case "seconds":
	}

	$s=$time % 60;
    $m=(($time-$s) / 60) % 60;
    $h=floor($time / 3600);
	if($positive){
		return $h.":".substr("0".$m,-2).":".substr("0".$s,-2);
	} else {
		return "-".$h.":".substr("0".$m,-2).":".substr("0".$s,-2);

	}
}

/* 
 * takes input boolean variables and returns text TRUE or FALSE.
 */
function boolText($boolValue){
	if($boolValue) {
		$return="TRUE";
	} else {
		$return="FALSE";
	}
	return $return;
}

/* 
 * Reads PHP memory usage and returns a string formatted in KB or MB.
 */
function read_memory_usage() {
	$mem_usage = memory_get_usage(true);
   
	if ($mem_usage < 1024)
		return $mem_usage." b";
	elseif ($mem_usage < 1048576)
		return round($mem_usage/1024,2)." kb";
	else
		return round($mem_usage/1048576,2)." mb";
}

function get_db_connection(){
	$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
	//trigger_error("Memory Used: ".read_memory_usage(), E_USER_NOTICE);
	include $GLOBALS['rootpath']."/inc/auth.inc.php";
	//trigger_error("Memory Used: ".read_memory_usage(), E_USER_NOTICE);
	
	$conn = new mysqli($servername, $username, $password, $dbname);

	//echo $dbname;
	
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* change character set to utf8 */
	if (!$conn->set_charset("utf8")) {
		printf("Error loading character set utf8: %s\n", $conn->error);
	} else {
		//printf("Current character set: %s\n", $conn->character_set_name());
	}
	
	return $conn;

}

/*
 *  GL5 Version - Need to re-work for GL6
 */


function makeIndex($array,$indexKey){
	$errorlist=array();
	foreach ($array as $key => $value) {
		if(isset($index[$value[$indexKey]]) && !in_array($value[$indexKey],$errorlist)){
			$errorlist[]= $value[$indexKey];
			trigger_error($indexKey . " '" . $value[$indexKey]. "' is not a unique key, some data may be lost.");
		}
		if($value[$indexKey]<>''){
			$index[$value[$indexKey]]=$key;
		}
	}	

	return $index;
}

function regroupArray($array,$indexKey){
	foreach ($array as $key => $value) {
		$index[$value[$indexKey]][]=$value;
	}	
	return $index;
}

function getPriceperhour($price,$time){
	$hours=$time/60/60;
	if($hours<1){
		$priceperhour=$price;
	} else {
		$priceperhour=$price/$hours;
	}
	//$priceperhour=sprintf("%.2f",$priceperhour);
	return $priceperhour;
}

function getLessXhour($price,$time,$xhour=1){
	$hours=$time/60/60;
	if($hours<1){
		$priceperhour=$price;
	} else {
		$priceperhour=$price/$hours;
	}
	
	$LessXhour=$priceperhour-($price/(max($xhour,$hours)+$xhour));
	
	//$LessXhour=sprintf("%.2f",$LessXhour);
	return $LessXhour;
}

function getHourstoXless($price,$time,$xless=.01){
	$priceperhour=getPriceperhour($price,$time);
	$hoursxless=getHrsToTarget($price,$time,$priceperhour-$xless);
	
	return $hoursxless;
}

function getHrsToTarget($CalcValue,$time,$target){
	if($target>0){
		$hourstotarget= $CalcValue/$target-$time/60/60;
	} else {
		$hourstotarget=0;
	}
	
	return $hourstotarget;
}

function getSortArray($SourceArray,$SortField){
	foreach ($SourceArray as $key => $row){
		$SortArray[$key]  = $row[$SortField];
	}
	
	return $SortArray;
}

function getActiveSortArray($SourceArray,$SortField){
	foreach ($SourceArray as $key => $row){
		if($row['Active']==true){
			$SortArray[$key]  = $row[$SortField];
		}
	}
	
	return $SortArray;
}

function getHrsNextPosition($SortValue,$SortArray,$time){
	$Marker=0;
	if($SortValue<>0){
		$calculated=getPriceperhour($SortValue,$time);
		//echo "Search for: " . $calculated ." in ";
		foreach ($SortArray as $value){
			//echo "Value: " . $value ;
			//echo " " . ($value < $calculated ? "True" : "False") .  ", ";
			if($value<$calculated){
				//echo " FOUND ";
				$Marker=$value;
				//echo "Marker: ". $Marker;
				break;
			}
		}
	}
	
	$hrsToTarget=getHrsToTarget($SortValue,$time,$Marker);
	//echo "Price per hr (".$calculated.') | (Price (' . $SortValue . ") / Target (" . $Marker . ")=".timeduration($SortValue/$Marker,"hours").") - Hours " . timeduration($time,"seconds") . " = " . timeduration($hrsToTarget,"hours")."<br>";
	
	//var_dump($SortArray);
	
	return $hrsToTarget;
}

function reIndexArray($array,$indexKey){
	$iserror=false;
	foreach ($array as $key => $value) {
		if(isset($newArray[$value[$indexKey]])){
			$iserror=true;
		}
		$newArray[$value[$indexKey]]=$value;
	}	
	if($iserror==true) { 
		trigger_error($indexKey . " is not a unique key, some data may be lost");
	}
	
	return $newArray;
}

function combinedate($date,$time,$sequence){
	//Not owned has no sequence - causes problems
	
	//If ($date=="") {$date="5/12/2010";}
	If ($sequence=="") {$sequence=0;}
	
	//echo "\$newDate=strtotime(".var_export($date,true)." . \" \" . ".var_export($time,true).")+".var_export($sequence,true).";<br>";
	//echo "Date: "; var_dump($date); echo "<br>";
	//echo "Time: "; var_dump($time); echo "<br>";
	//echo "Sequence: "; var_dump($sequence); echo "<br>";
	
	$newDate=strtotime($date . " " . $time)+$sequence;
	
	if(date("H:i:s",$newDate) == "00:00:00") {
		$newDate= date("n/j/Y",$newDate);
	} else {
		$newDate= date("n/j/Y H:i:s",$newDate) ;
	}

	return $newDate;
}

?>
