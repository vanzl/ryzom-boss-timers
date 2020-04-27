<?php


define ('RYAPI_APP_URL', '');
define ('RYAPI_APP_KEY', '');
define ('RYAPI_APP_MAXAGE', 300);

return array(
	'sqlite'	 => 	'../db/sqlite.db',
	'tablesSql' => '../db/tables.sql',
	'bossesSql' => '../db/bosses.sql',
	'checkUser' => true,
	'defaultSort'	 =>	'time',
	'minLevel' => 220,
	'spawnLocations' => [
		'Fyros'				=>	'Fyros.jpg',
		'Zorai'				=>	'Zorai.jpg',
		'Matis'				=> 'Matis.jpg',
		'Tryker'				=> 'Tryker.jpg',
		'Nexus'				=> 'Nexus.jpg',
		'Abyss of Ichor'	=> 'Abyss of Ichor.jpg',
		'Lands of Umbra'	=> 'Lands of Umbra.jpg',
		'Under Spring'		=>	'Under Spring.jpg',
		'Wasteland'			=> 'Wasteland.jpg'
  ]
);
