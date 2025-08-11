<?php declare(strict_types=1);

namespace WbAssignment\Presentation\Api\Devices;

use Nette;
use Ramsey;
use WbAssignment;


final class DevicesPresenter extends WbAssignment\Presentation\Api\BaseApiPresenter
{

	public function __construct(
		private readonly WbAssignment\Core\Devices\Creator $devicesCreator,
		private readonly WbAssignment\Core\Devices\Repository $devicesRepository,
		private readonly WbAssignment\Core\Owners\Repository $ownersRepository,
	)
	{
		parent::__construct();
	}



	#[\Override]
	public function run(Nette\Application\Request $request): Nette\Application\Responses\JsonResponse
	{
		if ($request->method === 'GET') {
			return new Nette\Application\Responses\JsonResponse(
				array_map(
					[$this, 'formatDevice'],
					$this->devicesRepository->loadDevices(),
				),
			);
		} elseif ($request->method === 'POST') {
			$input = $this->getRequestData();

			if ($input === NULL) {
				$this->getHttpResponse()->setCode(Nette\Http\IResponse::S400_BadRequest);
				return new Nette\Application\Responses\JsonResponse([
					'error' => 'Invalid or missing request input',
				]);
			}

			try {
				$data = $this->validateAndNormalizeInput($input);
			} catch (
				Nette\Schema\ValidationException
				| Ramsey\Uuid\Exception\InvalidUuidStringException $e
			) {
				$this->getHttpResponse()->setCode(Nette\Http\IResponse::S400_BadRequest);
				return new Nette\Application\Responses\JsonResponse([
					'error' => $e->getMessage(),
				]);
			}

			try {
				$owner = $this->ownersRepository->getOwner($data['owner_uuid']);
			} catch (WbAssignment\Core\Owners\Exceptions\NotFoundException) {
				$this->getHttpResponse()->setCode(Nette\Http\IResponse::S400_BadRequest);
				return new Nette\Application\Responses\JsonResponse([
					'error' => sprintf("Owner '%s' does not exist", $data['owner_uuid']->toString()),
				]);
			}

			try {
				$uuid = $this->devicesCreator->create(
					hostname: $data['hostname'],
					operatingSystem: $data['operating_system'],
					owner: $owner,
					type: $data['type'],
				);
			} catch (WbAssignment\Core\Devices\Exceptions\OperationFailedException) {
				$this->getHttpResponse()->setCode(Nette\Http\IResponse::S500_InternalServerError);
				return new Nette\Application\Responses\JsonResponse([
					'error' => 'Some error has occurred',
				]);
			}

			return new Nette\Application\Responses\JsonResponse([
				'uuid' => $uuid,
			]);
		}

		throw new Nette\NotImplementedException();
	}



	private function formatDevice(WbAssignment\Core\Devices\Device $device): array
	{
		return [
			'uuid' => $device->uuid,
			'hostname' => $device->hostname,
			'operating_system' => $device->operatingSystem,
			'owner' => [
				'uuid' => $device->owner->uuid,
				'firstname' => $device->owner->firstname,
				'lastname' => $device->owner->lastname,
			],
			'type' => $device->type,
		];
	}



	/**
	 * @param array<string, mixed> $input
	 * @return array{
	 *   hostname: string,
	 *   operating_system: WbAssignment\Core\Devices\OperatingSystem,
	 *   owner_uuid: Ramsey\Uuid\UuidInterface,
	 *   type: WbAssignment\Core\Devices\Type,
	 * }
	 */
	private function validateAndNormalizeInput(array $input): array
	{
		$schemaProcessor = new Nette\Schema\Processor();

		return $schemaProcessor->process(
			(new Nette\Schema\Elements\Structure([
				'hostname' => Nette\Schema\Expect::string()
					->max(255)
					->required(),
				'operating_system' => Nette\Schema\Expect::anyOf(...WbAssignment\Core\Devices\OperatingSystem::cases())
					->before(static fn ($operatingSystem) => WbAssignment\Core\Devices\OperatingSystem::tryFrom($operatingSystem))
					->required(),
				'owner_uuid' => Nette\Schema\Expect::type(Ramsey\Uuid\UuidInterface::class)
					->before(static fn ($ownerUuid) => Ramsey\Uuid\Uuid::fromString($ownerUuid))
					->required(),
				'type' => Nette\Schema\Expect::anyOf(...WbAssignment\Core\Devices\Type::cases())
					->before(static fn ($type) => WbAssignment\Core\Devices\Type::tryFrom($type))
					->required(),
			]))->castTo('array'),
			$input,
		);
	}

}
