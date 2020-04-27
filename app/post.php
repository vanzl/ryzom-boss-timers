<?php

$redirect = $_SERVER['PHP_SELF'];

if($isIngame){
	$redirect .= '?' . SID;
}

if(isset($_POST['kill'])){
	updateBossTime($db, (int)$_POST['kill']);
}

if (isset($_POST['minLevel'])){

	if($config['checkUser'] !== false){
	
		updateUserSettings($db, $_SESSION['app.user']['id'], ['minLevel' => $_POST['minLevel']]);
		
	}
	
	$_SESSION['minLevel'] = (int)$_POST['minLevel'];
}

if(isset($_POST['setSort'])){

	updateUserSettings($db, $_SESSION['app.user']['id'], ['sort' => $_POST['sort']]);
	$_SESSION['sort'] = $_POST['sort'];
}

header("Location: " . $redirect);


