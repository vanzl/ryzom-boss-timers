<?php

$sort = $config['defaultSort'];
$sort = isset($_SESSION['sort']) ? $_SESSION['sort'] : $sort;
$sort = isset($_GET['sort']) ? $_GET['sort'] : $sort;

$_SESSION['sort'] = $sort;

$minLevel = (int)$config['minLevel'];
$minLevel = isset($_SESSION['minLevel']) ? (int)$_SESSION['minLevel']: $minLevel;

$bosses = getBosses($db, $sort, $minLevel);
$spawns = $config['spawnLocations'];


include ('../view/header.php');
include ('../view/main.php');
include ('../view/footer.php');
