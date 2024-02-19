<?php
function get_pdo_connection(){
	if(!isset($GLOBALS['pdo'])) {
		$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
		include $GLOBALS['rootpath']."/inc/auth.inc.php";
		$charset = 'utf8';

		$dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";
		$options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
		];
		try {
			 $pdo = new PDO($dsn, $username, $password, $options);
		} catch (\PDOException $e) {
			 throw new \PDOException($e->getMessage(), (int)$e->getCode());
		}
		
		$GLOBALS['pdo']=$pdo;
	}
	
	return $GLOBALS['pdo'];

}

function get_table_monster() {
	if(!isset($GLOBALS['table']['monster'])) {
		$pdo=get_pdo_connection();
		
		$stmt = $pdo->query('Select * from `d20_monster`');
		while ($row = $stmt->fetch())
		{
			$GLOBALS['table']['monster'][$row['monsterid']]=$row;
		}
	}
	
	return $GLOBALS['table']['monster'];
}

function get_table_size() {
	if(!isset($GLOBALS['table']['size'])) {
		$pdo=get_pdo_connection();
		
		$stmt = $pdo->query('Select * from `d20_size`');
		while ($row = $stmt->fetch())
		{
			$GLOBALS['table']['size'][$row['sizeid']]=$row;
		}
	}
	
	return $GLOBALS['table']['size'];
}

function get_table_type() {
	if(!isset($GLOBALS['table']['type'])) {
		$pdo=get_pdo_connection();
		
		$stmt = $pdo->query('Select * from `d20_monstertype`');
		while ($row = $stmt->fetch())
		{
			$GLOBALS['table']['type'][$row['monstertypeid']]=$row;
		}
	}
	
	return $GLOBALS['table']['type'];
}

function get_table_speed() {
	if(!isset($GLOBALS['table']['speed'])) {
		$pdo=get_pdo_connection();
		
		$stmt = $pdo->query('Select * from `d20_monspeed`');
		while ($row = $stmt->fetch())
		{
			$GLOBALS['table']['speed'][$row['monspeedid']]=$row;
		}
	}
	
	return $GLOBALS['table']['speed'];
}

function get_table_skill() {
	if(!isset($GLOBALS['table']['skill'])) {
		$pdo=get_pdo_connection();
		
		$stmt = $pdo->query('Select * from `d20_skill`');
		while ($row = $stmt->fetch())
		{
			$GLOBALS['table']['skill'][$row['skillid']]=$row;
		}
	}
	
	return $GLOBALS['table']['skill'];
}

function get_table_monsterskill() {
	if(!isset($GLOBALS['table']['monsterskill'])) {
		$pdo=get_pdo_connection();
		
		$stmt = $pdo->query('Select * from `d20_monsterskill`');
		while ($row = $stmt->fetch())
		{
			$GLOBALS['table']['monsterskill'][$row['monsterskillid']]=$row;
		}
	}
	
	return $GLOBALS['table']['monsterskill'];
}

function get_table_skillsynergy() {
	if(!isset($GLOBALS['table']['skillsynergy'])) {
		$pdo=get_pdo_connection();
		
		$stmt = $pdo->query('Select * from `d20_skillsynergy`');
		while ($row = $stmt->fetch())
		{
			$GLOBALS['table']['skillsynergy'][$row['skillsynergyid']]=$row;
		}
	}
	
	return $GLOBALS['table']['monsterskill'];
}?>