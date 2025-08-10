<?php declare(strict_types=1);

namespace WbAssignemnt\Core\SymfonyConsole\DI;

use Nette;
use Symfony;


class Extension extends Nette\DI\CompilerExtension
{

	#[\Override]
	public function loadConfiguration(): void
	{
		$containerBuilder = $this->getContainerBuilder();

		$containerBuilder->addDefinition($this->prefix('symfonyApplication'))
			->setFactory(Symfony\Component\Console\Application::class);

		$this->compiler->addExportedType(Symfony\Component\Console\Application::class);
	}



	#[\Override]
	public function beforeCompile(): void
	{
		$containerBuilder = $this->getContainerBuilder();

		/** @var Nette\DI\Definitions\ServiceDefinition $applicationDefinition */
		$applicationDefinition = $containerBuilder->getDefinition($this->prefix('symfonyApplication'));

		foreach ($containerBuilder->findByType(Symfony\Component\Console\Command\Command::class) as $service) {
			$applicationDefinition->addSetup('add', [$service]);
		}
	}

}
