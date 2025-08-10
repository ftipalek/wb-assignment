<?php declare(strict_types=1);

require_once __DIR__ . '/../../../../src/vendor/autoload.php';


/**
 * @dataProvider dataProviders/Creator.php
 */
[$owner] = Tester\Environment::loadData();


$database = require __DIR__ . '/../../../../tester-databasePreparation.php';


$creator = new WbAssignment\Core\Owners\Creator(
	database: $database,
);


$uuid = $creator->create(
	$owner['firstname'],
	$owner['lastname'],
);


$row = $database->query('
	SELECT `firstname`, `lastname`
	FROM `owners`
	WHERE `uuid` = %s', $uuid->toString(), '
')->fetch();


Tester\Assert::same(
	$owner,
	$row->toArray(),
);
