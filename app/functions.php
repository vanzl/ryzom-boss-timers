<?php

function loadUser($db){
	$user = null;

	$auth = ryzom_app_authenticate($user); //user is set by reference

	if($auth){
		
		unset($_GET['checksum'], $_GET['user']);

		//add user settings to session
		$userSettings = getUserSettings($db, $user);
		
		//add user if it doesnt exist
		if($userSettings == false){
			addUser($db, $user);
		}
		else {
			foreach($userSettings as $key => $val){
				$_SESSION[$key] = $val;
				$_SESSION[$key] = $val;
			}
		}
	}
	else {
	    return null;
	}

	return $user;
}

function getDb($file){

	static $db = null;

	if($db == null){
		$db = new \SQLite3($file);
	}

	return $db;
}


function getUserSettings($db, $user){


	$stmt = $db->prepare("SELECT settings FROM users where ryzom_id = :ryzom_id");

	$stmt->bindValue(':ryzom_id', $user['id'], SQLITE3_INTEGER);

	$result = $stmt->execute()->fetchArray();

	if($result !== false){
	
		return json_decode($result['settings'], true);
		
	}

	return false;
	
}

function getBosses($db, $sort, $minLevel){

	$validSort = ['id', 'name', 'region', 'level', 'time'];

	$sort = in_array($sort, $validSort) ? $sort : 'name';
	
	if($sort == 'time') $sort = 'time DESC';

	$result = array();

	$query = "SELECT * from bosses WHERE level >= $minLevel ORDER BY $sort";
	
	$stmt = $db->prepare($query);
	
	$dbResult = $stmt->execute();

	while($row = $dbResult->fetchArray(SQLITE3_ASSOC)){
		$result[] = $row;
	}
	
	return $result;
}

function updateBossTime($db, $id){
	$query = "UPDATE bosses SET time=:time WHERE id=:id";
	
	$stmt = $db->prepare($query);
	
	$stmt->bindValue(':time', time());
	$stmt->bindValue(':id', $id);
	
	$result = $stmt->execute();
}

function addUser($db, $user){
	$query = 'INSERT INTO users (name, ryzom_id, settings) VALUES(:name, :ryzom_id, :settings)';
	
	$stmt = $db->prepare($query);

	$stmt->bindValue(':name', $user['char_name']);
	$stmt->bindValue(':ryzom_id', $user['id']);
	$stmt->bindValue(':settings', '{"defaultSort" : "time"}');

	$result = $stmt->execute();

	return $result;
	
}

function updateUserSettings($db, $ryzomId, $settings){
	$id = (int) $ryzomId;
	
	$userSettings = $db->querySingle('SELECT settings from users where ryzom_id=' . $id);
		
	if($userSettings !== null){
	
		$userSettings = json_decode($userSettings, true);

		$userSettings = array_merge($userSettings, $settings);

		$userSettings = json_encode($userSettings);
		
		$query = "UPDATE users SET settings = '" . $userSettings . "' WHERE ryzom_id = " . $id;
		
		$db->query($query);
	}
}

function getPath(){

	if (!isset($_SERVER['PATH_INFO'])){
		return [];
	}

	$pathArray = explode('/', $_SERVER['PATH_INFO']);

	return array_slice($pathArray, 1, count($pathArray) - 2);
}

function isIngame(){
	
	return strpos($_SERVER['HTTP_USER_AGENT'], 'Ryzom') !== false;
	
}

function initSession(){
	//enable $_GET session for ig browser
	if (isIngame()){
		ini_set('session.use_cookies', 0);
		ini_set('session.use_only_cookies', 0);
		ini_set('session.use_trans_sid', 1);		
	}
	
	session_start();
	
}

function timeDiff($time){
    $diff = time() - $time;
    
    $hoursTotal = floor(abs($diff) / 3600);
    
    $days = floor($hoursTotal / 24);
    
    $hours = $hoursTotal - $days * 24; 
    
    if ($days > 0){
        $result = $days . 'd ' . $hours . 'h';
    }
    
    else {
       $result =  $hours . 'h';
    }

    return $result;
}


function print_pre($var){
	echo '<br><pre style="color: white; background: black">';
	var_dump($var);
	echo '</pre>';
}
