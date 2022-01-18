<?php
class abilityscore {
	public $stat;
	
	function statbonus($stat,$print=false) {
		$bonus=floor(($stat/2)-5);
		if($print) {
			$bonus=formatbonus($bonus);
		}
		return $bonus;
	}
	
}

class dndmonster {
	// Properties
	public $id;
	public $family;
	public $name;
	public $altname;

	public $size;
		public $sizeid;
		public $size_mod;
		public $grapple_mod;
		public $minlen;
		public $maxlen;
		public $minlb;
		public $maxlb;
		public $space;
		public $reach;

	public $type;
		public $typeid;
		public $cr_rate;
		public $hdsize;
		public $atkbonus;
		public $skillpoints;
		public $goodsaves;
		public $typedescription;
  
	public $subtypes;
	public $speed;
	public $speedtext;

	public $hitdice;
		private $hpbonus;
		private $avghp;
		public  $printhitdice;

	public $natural_armor;
	public $shape;
	public $str;
	public $dex;
	public $con;
	public $int;
	public $wis;
	public $cha;
	public $climate;
	public $environment;
	public $challengerating;
	public $treasure;
	public $align_frequency;
	public $align_culture;
	public $align_moral;
	public $level_adjust;
	public $description;
	public $combat;
	public $source;
	
	public $skills;
	

	// Methods
	function __construct($monsterid) {
		$monster_table=get_table_monster();
		
		$this->id = $monsterid;
		$this->family = $monster_table[$monsterid]["family"];
		$this->name = $monster_table[$monsterid]["name"];
		$this->sizeid = $monster_table[$monsterid]["sizeid"];
		$this->typeid = $monster_table[$monsterid]["typeid"];
		$this->hitdice = $monster_table[$monsterid]["hitdice"];
		$this->natural_armor = $monster_table[$monsterid]["natural_armor"];
		$this->shape = $monster_table[$monsterid]["shape"];
		$this->str = $monster_table[$monsterid]["str"];
		$this->dex = $monster_table[$monsterid]["dex"];
		$this->con = $monster_table[$monsterid]["con"];
		$this->int = $monster_table[$monsterid]["intx"];
		$this->wis = $monster_table[$monsterid]["wis"];
		$this->cha = $monster_table[$monsterid]["cha"];
		$this->climate = $monster_table[$monsterid]["climate"];
		$this->environment = $monster_table[$monsterid]["environment"];
		$this->challengerating = $monster_table[$monsterid]["challengerating"];
		$this->treasure = $monster_table[$monsterid]["treasure"];
		$this->align_frequency = $monster_table[$monsterid]["align_frequency"];
		$this->align_culture = $monster_table[$monsterid]["align_culture"];
		$this->align_moral = $monster_table[$monsterid]["align_moral"];
		$this->level_adjust = $monster_table[$monsterid]["level_adjust"];
		$this->description = $monster_table[$monsterid]["description"];
		$this->combat = $monster_table[$monsterid]["combat"];
		$this->source = $monster_table[$monsterid]["source"];
		
		$this->set_size($this->sizeid);
		$this->set_type($this->typeid);
		$this->set_speed();
		$this->set_skills();
		
		//$this->subtypes
		
		$this->hpbonus=statbonus($this->con)*$this->hitdice;
		$this->avghp=floor((($this->hdsize+1)/2)*$this->hitdice)+$this->hpbonus;
		$this->printhitdice=$this->hitdice."d".$this->hdsize."+".$this->hpbonus;
		
		var_dump($monster_table[$monsterid]);
	}
	
	function get_statblock() {
		$html_table  = "";
		$html_table .= "<div topic=" . $this->family . " level='2'><p><h2>" . $this->family . "</h2></p>";
		$html_table .= "<table width='100%' border='1' cellpadding='2' cellspacing='2' frame='VOID' rules='ROWS'>";
		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td/>";
		$html_table .= "<td><b>". $this->name . "</b></td> ";
		$html_table .= "</tr><tr maxcol='3' curcol='3'>";
		$html_table .= "<td/>";
		$html_table .= "<td>" . $this->size . " " . $this->type; 

		if(is_array($this->subtypes)){
			echo " (";
			echo implode(', ', $this->subtypes);
			echo ")";
		}
		$html_table .= "</td>";
		$html_table .= "</tr>";
		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Hit Dice:</b></td>";
		$html_table .= "<td>" . $this->printhitdice . " (".$this->avghp." hp)</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Initiative:</b></td>";
		$html_table .= "<td>". statbonus($this->dex,true) . "</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Speed:</b></td>";
		$html_table .= "<td>" . $this->speed_text . "</td>";
		$html_table .= "</tr>";
		
		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Armor Class:</b></td>";
		$html_table .= "<td>";
		$html_table .= 10+$this->size_mod+statbonus($this->dex)+$this->natural_armor;
		$html_table .= " (";
		if($this->size_mod<>0) {
			$html_table .= formatbonus($this->size_mod)." size, "; 
		}
		$html_table .= statbonus($this->dex,true)." Dex, "; 
		$html_table .= formatbonus($this->natural_armor)." natural"; 
		$html_table .= ")"; 
		$html_table .= " [[EQUIPMENT]]</td>";
		$html_table .= "</tr>";


		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Base Attack/Grapple:</b></td>";
		$html_table .= "<td>";
		//=rounddown(D8*INDEX(Reference!E29:E45,match(B13,Reference!B29:B45,0)))
		$baseattack=floor($this->hitdice*$this->atkbonus/4);
		$html_table .= formatbonus($baseattack);
		$html_table .= "/";
		$html_table .= formatbonus($baseattack+statbonus($this->str)+$this->grapple_mod);
		$html_table .= "</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Attack:</b></td>";
		$html_table .= "<td>[[ATTACKS]]</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Full Attack:</b></td>";
		$html_table .= "<td>[[ATTACKS]]</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Space/Reach:</b></td>";
		$html_table .= "<td>";
		$html_table .= ($this->space*1)." ft./";
		$html_table .= $this->reach . " ft.";
		//$this->size_mod
		$html_table .= "</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Special Attacks:</b></td>";
		$html_table .= "<td>[[SPECIAL ATTACKS]]</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Special Qualities:</b></td>";
		$html_table .= "<td>[[QUALITIES]]</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Saves:</b></td>";
		$html_table .= "<td>";
		$html_table .= "Fort ";
		$html_table .= "Ref ";
		$html_table .= "Will ";
		$html_table .= "</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Abilities:</b></td>";
		$html_table .= "<td>";
		$html_table .= "Str ".$this->str.", "; 
		$html_table .= "Dex ".$this->dex.", "; 
		$html_table .= "Con ".$this->con.", "; 
		$html_table .= "Int ".$this->int.", "; 
		$html_table .= "Wis ".$this->wis.", "; 
		$html_table .= "Cha ".$this->cha; 
		$html_table .= "</td>";
		
		$html_table .= "<td>";
		$html_table .= "<details>";
		$html_table .= "<summary><b>Statblock</b></summary>";
		$html_table .= "<table>";
		$html_table .= "<tr><th>Stat</th><th>Score</th><th>Bonus</th><th>Base Stat</th><th>Racial adjustment</th><th>Size Adjustment</th><th>Racial adjustment<br>(As Medium)</th></tr>";
		$stat="str";
		$html_table .= "<tr><td>" . ucfirst($stat) . "</td>";
		$html_table .= "<td>" . $this->$stat . "</td>";
		$html_table .= "<td>" . statbonus($this->$stat,true) . "</td>";
		$html_table .= "<td>" . getbasestat($this->$stat) . "</td>";
		$html_table .= "<td>" . getadjustment($this->$stat,true) . "</td>";
		$html_table .= "<td>" . formatbonus(getsizemod($stat,$this->size)) . "</td>";
		$html_table .= "<td>" . formatbonus(getadjustment($this->$stat)-getsizemod($stat,$this->size)) . "</td>";
		$html_table .= "</tr>";
		$stat="dex";
		$html_table .= "<tr><td>" . ucfirst($stat) . "</td>";
		$html_table .= "<td>" . $this->$stat . "</td>";
		$html_table .= "<td>" . statbonus($this->$stat,true) . "</td>";
		$html_table .= "<td>" . getbasestat($this->$stat) . "</td>";
		$html_table .= "<td>" . getadjustment($this->$stat,true) . "</td>";
		$html_table .= "<td>" . formatbonus(getsizemod($stat,$this->size)) . "</td>";
		$html_table .= "<td>" . formatbonus(getadjustment($this->$stat)-getsizemod($stat,$this->size)) . "</td>";
		$html_table .= "</tr>";
		$stat="con";
		$html_table .= "<tr><td>" . ucfirst($stat) . "</td>";
		$html_table .= "<td>" . $this->$stat . "</td>";
		$html_table .= "<td>" . statbonus($this->$stat,true) . "</td>";
		$html_table .= "<td>" . getbasestat($this->$stat) . "</td>";
		$html_table .= "<td>" . getadjustment($this->$stat,true) . "</td>";
		$html_table .= "<td>" . formatbonus(getsizemod($stat,$this->size)) . "</td>";
		$html_table .= "<td>" . formatbonus(getadjustment($this->$stat)-getsizemod($stat,$this->size)) . "</td>";
		$html_table .= "</tr>";
		$stat="int";
		$html_table .= "<tr><td>" . ucfirst($stat) . "</td>";
		$html_table .= "<td>" . $this->$stat . "</td>";
		$html_table .= "<td>" . statbonus($this->$stat,true) . "</td>";
		$html_table .= "<td>" . getbasestat($this->$stat) . "</td>";
		$html_table .= "<td>" . getadjustment($this->$stat,true) . "</td>";
		$html_table .= "<td></td>";
		$html_table .= "<td>" . getadjustment($this->$stat,true) . "</td>";
		$html_table .= "</tr>";
		$stat="wis";
		$html_table .= "<tr><td>" . ucfirst($stat) . "</td>";
		$html_table .= "<td>" . $this->$stat . "</td>";
		$html_table .= "<td>" . statbonus($this->$stat,true) . "</td>";
		$html_table .= "<td>" . getbasestat($this->$stat) . "</td>";
		$html_table .= "<td>" . getadjustment($this->$stat,true) . "</td>";
		$html_table .= "<td></td>";
		$html_table .= "<td>" . getadjustment($this->$stat,true) . "</td>";
		$html_table .= "</tr>";
		$stat="cha";
		$html_table .= "<tr><td>" . ucfirst($stat) . "</td>";
		$html_table .= "<td>" . $this->$stat . "</td>";
		$html_table .= "<td>" . statbonus($this->$stat,true) . "</td>";
		$html_table .= "<td>" . getbasestat($this->$stat) . "</td>";
		$html_table .= "<td>" . getadjustment($this->$stat,true) . "</td>";
		$html_table .= "<td></td>";
		$html_table .= "<td>" . getadjustment($this->$stat,true) . "</td>";
		$html_table .= "</tr>";

		$html_table .= "</table>";
		$html_table .= "</details>";
		$html_table .= "</td>";
		$html_table .= "</tr>";
		
		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Skills:</b></td>";
		$html_table .= "<td>";
		$html_table .= $this->get_skilllist();
		$html_table .= "</td>";
		$html_table .= "<td>";
		$html_table .= $this->get_skillblock();
		$html_table .= "</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Feats:</b></td>";
		$html_table .= "<td>[[FEATS]]</td>";
		$html_table .= "<td>Feats Available: ";
		$html_table .= (1+floor($this->hitdice/3));
		$html_table .= "</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Environment:</b></td>";
		$html_table .= "<td>" .$this->climate . " " .$this->environment . "</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Organization:</b></td>";
		$html_table .= "<td>[[ORGANIZATION]]</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Challenge Rating:</b></td>";
		$html_table .= "<td>" .$this->challengerating . "</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Treasure:</b></td>";
		$html_table .= "<td>" .$this->treasure . "</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Alignment:</b></td>";
		$html_table .= "<td>" .$this->align_frequency ." ".$this->align_culture." ".$this->align_moral . "</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Advancement:</b></td>";
		$html_table .= "<td>[[ADVANCEMENT]]</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Level Adjustment:</b></td><td>";
		if($this->level_adjust=="") {
			$html_table .= "&mdash;";
		} else {
			$html_table .= $this->level_adjust; 
		}
		$html_table .= "</td></tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Description:</b></td>";
		$html_table .= "<td>" .$this->description . "</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Combat:</b></td>";
		$html_table .= "<td>" .$this->combat . "</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Abilities:</b></td>";
		$html_table .= "<td>[[ABILITIES]]</td>";
		$html_table .= "</tr>";

		$html_table .= "<tr maxcol='3' curcol='3'>";
		$html_table .= "<td><b>Source:</b></td>";
		$html_table .= "<td>" .$this->source . "</td>";
		$html_table .= "</tr>";

		$html_table .= "</table>";
		$html_table .= "</div>";
		
		return $html_table;
	}
	
	function set_size($sizeid) {
		$size_table=get_table_size();
		
		$this->size = $size_table[$sizeid]["size"];
		$this->sizeid = $sizeid;
		$this->size_mod = $size_table[$sizeid]["size_mod"];
		$this->grapple_mod = $size_table[$sizeid]["grapple_mod"];
		$this->minlen = $size_table[$sizeid]["minlen"];
		$this->maxlen = $size_table[$sizeid]["maxlen"];
		$this->minlb = $size_table[$sizeid]["minlb"];
		$this->maxlb = $size_table[$sizeid]["maxlb"];
		$this->space = $size_table[$sizeid]["space"];
		if($this->shape == "tall") {
			$this->reach = $size_table[$sizeid]["tallreach"];
			//$this->tallreach = $size_table[$sizeid]["tallreach"];
		} else if ($this->shape == "long") {
			$this->reach = $size_table[$sizeid]["longreach"];
			//$this->longreach = $size_table[$sizeid]["longreach"];
		}
		
		//var_dump($size_table[$sizeid]); echo "<hr>";
	}
	
	function set_type($typeid) {
		$type_table=get_table_type();
		
		$this->type = $type_table[$typeid]["monstertype"];
		$this->typeid = $typeid;
		$this->cr_rate = $type_table[$typeid]["cr_rate"];
		$this->hdsize = $type_table[$typeid]["hdsize"];
		$this->atkbonus = $type_table[$typeid]["atkbonus"];
		$this->skillpoints = $type_table[$typeid]["skillpoints"];
		$this->goodsaves = $type_table[$typeid]["goodsaves"];
		$this->typedescription = $type_table[$typeid]["description"];
		
		//var_dump($type_table[$typeid]); echo "<hr>";
	}
	
	function set_speed(){
		$speed_table=get_table_speed();
		
		$this->speed_text="";
		
		foreach($speed_table as $speedrow) {
			if($speedrow['monsterid']==$this->id){
				if($this->speed_text<>""){$this->speed_text .= ", ";}
				
				$key=$speedrow['terrain'];
				if($speedrow['terrain']==""){
					$key="land";
					$this->speed_text .= $speedrow['speed'] . " ft. (".($speedrow['speed']/5)." squares)";
				} else {
					$this->speed_text .= $speedrow['terrain'] . " " . $speedrow['speed'] . " ft.";
					if($speedrow['manuverability'] <> "") {
						$this->speed_text .= " (".$speedrow['manuverability'].")";
					}
				}
				
				$this->speed[$key]=array(
				"terrain" => $speedrow['terrain'],
				"speed" => $speedrow['speed'],
				"manuverability" => $speedrow['manuverability']
				);
			}
		}
	}

	function set_skills(){
		$skill_table=get_table_skill();
		$monsterskill_table=get_table_monsterskill();
		$skillsynergy_table=get_table_skillsynergy();
		
		//echo "<hr>";var_dump($monsterskill_table);echo "<hr>";
		//echo "<hr>";var_dump($skill_table);echo "<hr>";
		$skillset=array();
		
		foreach($skill_table as $skillrow){
			//echo "<hr>";var_dump($skillrow);echo "<hr>";
			
			$skillset[$skillrow['skillid']]['skillid']=$skillrow['skillid'];
			$skillset[$skillrow['skillid']]['skill']=$skillrow['skill'];
			$skillset[$skillrow['skillid']]['subskill']=$skillrow['subskill'];
			$skillset[$skillrow['skillid']]['ability']=$skillrow['ability'];
			$skillset[$skillrow['skillid']]['psionic']=$skillrow['psionic'];
			$skillset[$skillrow['skillid']]['trained']=$skillrow['trained'];
			$skillset[$skillrow['skillid']]['armorcheck']=$skillrow['armorcheck'];
			
			//echo "<hr>";var_dump($skillset[$skillrow['skillid']]);echo "<hr>";
		}

		foreach($monsterskill_table as $monsterskillrow){
			if($monsterskillrow['monsterid']==$this->id){
				//echo "<hr>";var_dump($monsterskillrow);echo "<hr>";
				$skillset[$monsterskillrow['skillid']][$monsterskillrow['bonustype']]=$monsterskillrow['ranks'];
			}
		}
		
		$this->skills=$skillset;
		
		//echo "<hr>Skillset:";var_dump($skillset);echo "<hr>";
		//echo "<hr>";
		
	}
	
	function get_skilllist() {
		//placeholder for armorcheck modifier from equipment.
		$armorcheck=0;
		if($this->id==1){$armorcheck=-1;}
		$skillranksused=0;
		$skilltext="";
		$skilltextdetail="";
		
		//echo "<hr>";var_dump($this->skills);echo "<hr>";
		
		foreach($this->skills as $skillrow){
			if($skillrow['ability']<>"") {
				if($skillrow['trained']==0 OR isset($skillrow['ranks']) OR isset($skillrow['race'])) {
					$totalskillbonus=0;
					$showskill=false;
					$useability=$skillrow['ability'];
					$totalskillbonus+=statbonus($this->$useability);
					if($skillrow['armorcheck']==1) {
						$totalskillbonus+=$armorcheck;
					}
					if(isset($skillrow['race'])){
						$totalskillbonus+=$skillrow['race'];
						$showskill=true;
					}
					if(isset($skillrow['ranks'])){
						$totalskillbonus+=$skillrow['ranks'];
						$skillranksused+=$skillrow['ranks'];
						$showskill=true;
					}
					if($showskill==true) {
						if ($skilltext<>""){$skilltext.=", ";}
						$skilltext.=$skillrow['skill'] . " " . formatbonus($totalskillbonus);
					}
				}
			}
		}
		
		return $skilltext;
	}
	function get_skillblock() {
		//placeholder for armorcheck modifier from equipment.
		$armorcheck=0;
		if($this->id==1){$armorcheck=-1;}
		$skillranksused=0;
		$skilltextdetail="";
		$html_table="";
		
		//echo "<hr>";var_dump($this->skills);echo "<hr>";
		
		foreach($this->skills as $skillrow){
			if($skillrow['ability']<>"") {
				if($skillrow['trained']==0 OR isset($skillrow['ranks']) OR isset($skillrow['race'])) {
					$totalskillbonus=0;
					$showskill=false;
					$skilltextdetail .= "<tr>";
					$skilltextdetail .= "<td>".$skillrow['skill']."</td>";
					$skilltextdetail .= "<td>".$skillrow['ability']."</td>";
					$useability=$skillrow['ability'];
					$skilltextdetail .= "<td>".statbonus($this->$useability,true)."</td>";
					$totalskillbonus+=statbonus($this->$useability);
					$skilltextdetail .= "<td>";
					if($skillrow['armorcheck']==1) {
						$skilltextdetail .= formatbonus($armorcheck);
						$totalskillbonus+=$armorcheck;
					}
					$skilltextdetail .= "</td>";
					$skilltextdetail .= "<td>";
					if(isset($skillrow['race'])){
						$skilltextdetail .= formatbonus($skillrow['race']);
						$totalskillbonus+=$skillrow['race'];
					}
					$skilltextdetail .= "</td>";
					$skilltextdetail .= "<td>";
					if(isset($skillrow['ranks'])){
						$skilltextdetail .= formatbonus($skillrow['ranks']);
						$totalskillbonus+=$skillrow['ranks'];
						$skillranksused+=$skillrow['ranks'];
					}
					$skilltextdetail .= "</td>";
					$skilltextdetail .= "<td>";
					$skilltextdetail .= "</td>";
					$skilltextdetail .= "<td>";
					$skilltextdetail .= formatbonus($totalskillbonus);
					
					$skilltextdetail .= "</td>";
					$skilltextdetail .= "<td>".$skillrow['skillid']."</td>";
					$skilltextdetail .= "<tr>";
				}
			}
		}

		$html_table .= "Skill Points Available: ";
		$totalskillpoints=(($this->hitdice+3)*($this->skillpoints+statbonus($this->int)));
		//As long as a creature has an Intelligence of at least 1, it gains a minimum of 1 skill point per Hit Die.
		if ($this->int>=1 && $totalskillpoints<$this->hitdice){
			$totalskillpoints=$this->hitdice+3;
		}
		$html_table .= $totalskillpoints;
		$html_table .= " ((".$this->hitdice."+3)*(".$this->skillpoints."+".statbonus($this->int)."))";
		$html_table .= "<details>";
		$html_table .= "<Summary>All Skills</Summary>";
		$html_table .= "<table>";
		$html_table .= "<tr><th>Skill</th><th>Ability</th><th>Bonus</th><th>Armorcheck</th><th>Race bonus</th><th>Ranks</th><th>Synergy</th><th>Total</th></tr>";
		$html_table .= $skilltextdetail;
		$html_table .= "</table>";
		$html_table .= "</details>";
		$html_table .= "Total skill ranks used: " . $skillranksused; 
		
		return $html_table;
	}
}
?>