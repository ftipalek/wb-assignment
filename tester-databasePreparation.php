<?php declare(strict_types=1);

$database = new Dibi\Connection([
	'driver' => 'sqlite',
	'database' => ':memory:',
]);

$database->query('
	CREATE TABLE `devices` (
		`uuid` TEXT NOT NULL,
		`owner_uuid` TEXT NOT NULL,
		`type` TEXT NOT NULL,
		`operating_system` TEXT NOT NULL,
		`hostname` TEXT NOT NULL,
		PRIMARY KEY (`uuid`)
	)
');

$database->query('
	CREATE TABLE `owners` (
		`uuid` TEXT NOT NULL,
		`firstname` TEXT NOT NULL,
		`lastname` TEXT NOT NULL,
		PRIMARY KEY (`uuid`)
	)
');

return $database;
