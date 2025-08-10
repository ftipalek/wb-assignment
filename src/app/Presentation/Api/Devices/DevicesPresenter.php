<?php declare(strict_types=1);

namespace WbAssignment\Presentation\Api\Devices;

use Nette;
use WbAssignment;


final class DevicesPresenter extends Nette\Application\UI\Presenter
{

	public function __construct(
		private readonly WbAssignment\Core\Devices\Repository $devicesRepository,
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

}
