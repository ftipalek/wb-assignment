<?php declare(strict_types=1);

namespace WbAssignment\Core\Commands;

use GuzzleHttp;
use Symfony;
use WbAssignment;


class AddDeviceCommand extends Symfony\Component\Console\Command\Command
{

	public function __construct(
		private readonly GuzzleHttp\Client $guzzleClient,
		private readonly WbAssignment\Core\Owners\Repository $repository,
	) {
		parent::__construct();
	}



	#[\Override]
	protected function configure(): void
	{
		$this->setDescription('CLI command to add device');
		$this->setName('devices:add');
	}



	#[\Override]
	protected function execute(
		Symfony\Component\Console\Input\InputInterface $input,
		Symfony\Component\Console\Output\OutputInterface $output,
	): int
	{
		$questionsHelper = new Symfony\Component\Console\Helper\QuestionHelper();

		$hostname = $questionsHelper->ask(
			$input,
			$output,
			new Symfony\Component\Console\Question\Question('Hostname: '),
		);

		$typeQuestion = new Symfony\Component\Console\Question\ChoiceQuestion(
			'Type:',
			array_map(
				static fn ($type) => $type->value,
				WbAssignment\Core\Devices\Type::cases(),
			),
		);
		$typeQuestion->setErrorMessage('Type is invalid.');
		$type = $questionsHelper->ask($input, $output, $typeQuestion);

		$operatingSystemQuestion = new Symfony\Component\Console\Question\ChoiceQuestion(
			'Operating System:',
			array_map(
				static fn ($type) => $type->value,
				WbAssignment\Core\Devices\OperatingSystem::cases(),
			),
		);
		$operatingSystemQuestion->setErrorMessage('Operating system is invalid.');
		$operatingSystem = $questionsHelper->ask($input, $output, $operatingSystemQuestion);

		$ownerQuestion = new Symfony\Component\Console\Question\ChoiceQuestion(
			'Owner:',
			array_map(
				static fn (WbAssignment\Core\Owners\Owner $owner) => $owner->uuid->toString(),
				$this->repository->loadOwners(),
			),
		);
		$ownerQuestion->setErrorMessage('Owner is invalid.');
		$ownerUuid = $questionsHelper->ask($input, $output, $ownerQuestion);

		$response = $this->guzzleClient->post('http://localhost/api/devices', [
			GuzzleHttp\RequestOptions::JSON => [
				'hostname' => $hostname,
				'operating_system' => $operatingSystem,
				'owner_uuid' => $ownerUuid,
				'type' => $type,
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
