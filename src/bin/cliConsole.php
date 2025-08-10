<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$bootstrap = new WbAssignment\Bootstrap();

$result = $bootstrap
	->bootWebApplication()
	->getByType(Symfony\Component\Console\Application::class)
	->run();

exit($result);
