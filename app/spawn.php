<?php

$src = '/img/spawn/' . rawurlencode($config['spawnLocations'][$_GET['spawn']]);

$title = $_GET['spawn'];

include ('../view/header.php');
include ('../view/spawn.php');
include ('../view/footer.php');
