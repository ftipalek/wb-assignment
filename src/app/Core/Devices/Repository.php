<?php declare(strict_types=1);

namespace WbAssignment\Core\Devices;

use Dibi;
use Ramsey;
use WbAssignment;


class Repository
{

	public function __construct(
		private readonly Dibi\Connection $database,
	) {}



	/**
	 * @return list<Device>
	 */
	public function loadDevices(): array
	{
		return array_map(
			fn (Dibi\Row $row) => $this->createDevice($row),
			$this->database->query('
				SELECT
					`devices`.*,
					`owners`.`uuid` AS `owner_uuid`,
					`owners`.`firstname` AS `owner_firstname`,
					`owners`.`lastname` AS `owner_lastname`
				FROM `devices`
				LEFT JOIN `owners` ON `devices`.`owner_uuid` = `owners`.`uuid`
			')->fetchAll(),
		);
	}



	private function createDevice(Dibi\Row $row): Device
	{
		return new Device(
			uuid: Ramsey\Uuid\Uuid::fromString($row->uuid),
			hostname: $row->hostname,
			operatingSystem: OperatingSystem::from($row->operating_system),
			owner: new WbAssignment\Core\Owners\Owner(
				uuid: Ramsey\Uuid\Uuid::fromString($row->owner_uuid),
				firstname: $row->owner_firstname,
				lastname: $row->owner_lastname,
			),
			type: Type::from($row->type),
		);
	}

}
