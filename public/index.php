<?php

require '../lib/ryzomapi_lite.php';
require '../app/functions.php';

$user = null;
$config = include('../app/config.php');

if(!file_exists($config['sqlite'])){

	$db = getDb($config['sqlite']);
	
	$tablesSql = file_get_contents($config['tablesSql']);
	
	if($db->exec($tablesSql) === false){
	
		die('Failed to execute sql in ' . $config['tablesSql']);
		
	}
	
	$bossesSql = file_get_contents($config['bossesSql']);
	
	if($db->exec($bossesSql) === false){
	
		die('Failed to execute sql in ' . $config['bossesSql']);
		
	}
	
	$db->exec($bossesSql);

}

$db = getDb($config['sqlite']);
$isIngame = isIngame();

initSession();

$sid = $isIngame ? '&' . htmlspecialchars(SID) : '';

if(!isset($_SESSION['app.user']) && $config['checkUser'] !== false){	
	$user = loadUser($db);
	
	if($user == null){
	
		include('../view/invalid-user.php');
		die();
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
	include('../app/post.php');

}
else if(isset($_GET['spawn'])){
  
	include('../app/spawn.php');
	
}
else {

	include('../app/main.php');
	
}

$db->close();
