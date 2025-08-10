<?php declare(strict_types=1);

namespace WbAssignment\Core\Commands;

use GuzzleHttp;
use Symfony;


class ListOwnersCommand extends Symfony\Component\Console\Command\Command
{

	public function __construct(
		private readonly GuzzleHttp\Client $guzzleClient,
	) {
		parent::__construct();
	}



	#[\Override]
	protected function configure(): void
	{
		$this->setDescription('CLI command to list owners');
		$this->setName('owners:list');
	}



	#[\Override]
	protected function execute(
		Symfony\Component\Console\Input\InputInterface $input,
		Symfony\Component\Console\Output\OutputInterface $output,
	): int
	{
		$questionsHelper = $this->guzzleClient->get('http://localhost/api/owners');

		if ($questionsHelper->getStatusCode() !== 200) {
			$output->writeln(sprintf('Invalid status code: %s', $questionsHelper->getStatusCode()));
			return Symfony\Component\Console\Command\Command::FAILURE;
		}

		$output->writeln($questionsHelper->getBody()->getContents());

		return Symfony\Component\Console\Command\Command::SUCCESS;
	}

}
