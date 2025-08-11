<?php declare(strict_types=1);

require_once __DIR__ . '/../../../../src/vendor/autoload.php';


$database = require __DIR__ . '/../../../../tester-databasePreparation.php';


$database->query('INSERT INTO `owners`', [
	'uuid' => ($uuidOwner1 = Ramsey\Uuid\Uuid::uuid4())->toString(),
	'firstname' => 'Firstname 1',
	'lastname' => 'Lastname 1',
]);

$database->query('INSERT INTO `owners`', [
	'uuid' => ($uuidOwner2 = Ramsey\Uuid\Uuid::uuid4())->toString(),
	'firstname' => 'Firstname 2',
	'lastname' => 'Lastname 2',
]);


$repository = new WbAssignment\Core\Owners\Repository(
	database: $database,
);


Tester\Assert::equal(
	new WbAssignment\Core\Owners\Owner(
		uuid: $uuidOwner1,
		firstname: 'Firstname 1',
		lastname: 'Lastname 1',
	),
	$repository->getOwner($uuidOwner1),
);

Tester\Assert::equal(
	new WbAssignment\Core\Owners\Owner(
		uuid: $uuidOwner2,
		firstname: 'Firstname 2',
		lastname: 'Lastname 2',
	),
	$repository->getOwner($uuidOwner2),
);

Tester\Assert::exception(
	fn () => $repository->getOwner(Ramsey\Uuid\Uuid::uuid4()),
	WbAssignment\Core\Owners\Exceptions\NotFoundException::class,
);
