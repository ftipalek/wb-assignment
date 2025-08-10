<?php declare(strict_types=1);

namespace WbAssignment\Core\Commands;

use GuzzleHttp;
use Symfony;


class AddOwnerCommand extends Symfony\Component\Console\Command\Command
{

	public function __construct(
		private readonly GuzzleHttp\Client $guzzleClient,
	) {
		parent::__construct();
	}



	#[\Override]
	protected function configure(): void
	{
		$this->setDescription('CLI command to add owner');
		$this->setName('owners:add');
	}



	#[\Override]
	protected function execute(
		Symfony\Component\Console\Input\InputInterface $input,
		Symfony\Component\Console\Output\OutputInterface $output,
	): int
	{
		$questionsHelper = new Symfony\Component\Console\Helper\QuestionHelper();

		$firstname = $questionsHelper->ask(
			$input,
			$output,
			new Symfony\Component\Console\Question\Question('Firstname: '),
		);

		$lastname = $questionsHelper->ask(
			$input,
			$output,
			new Symfony\Component\Console\Question\Question('Lastname: '),
		);

		$response = $this->guzzleClient->post('http://localhost/api/owners', [
			GuzzleHttp\RequestOptions::JSON => [
				'firstname' => $firstname,
				'lastname' => $lastname,
			],
			GuzzleHttp\RequestOptions::HEADERS => [
				'Content-Type' => 'application/json',
			],
		]);

		if ($response->getStatusCode() !== 200) {
			$output->writeln(sprintf('Invalid status code: %s', $response->getStatusCode()));
			return Symfony\Component\Console\Command\Command::FAILURE;
		}

		$output->writeln($response->getBody()->getContents());

		return Symfony\Component\Console\Command\Command::SUCCESS;
	}

}
