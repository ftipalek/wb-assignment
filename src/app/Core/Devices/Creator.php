<?php declare(strict_types=1);

namespace WbAssignment\Core\Devices;

use Dibi;
use Ramsey;
use WbAssignment;


class Creator
{

	public function __construct(
		private readonly Dibi\Connection $database,
	) {}



	/**
	 * @throws Exceptions\OperationFailedException
	 */
	public function create(
		string $hostname,
		OperatingSystem $operatingSystem,
		WbAssignment\Core\Owners\Owner $owner,
		Type $type,
	): Ramsey\Uuid\UuidInterface
	{
		$uuid = Ramsey\Uuid\Uuid::uuid4();

		try {
			$this->database->query('
				INSERT INTO `devices`', [
					'uuid%s' => $uuid->toString(),
					'owner_uuid%s' => $owner->uuid->toString(),
					'hostname%s' => $hostname,
					'operating_system%s' => $operatingSystem,
					'type%s' => $type,
				],
			);
		} catch (Dibi\ConstraintViolationException) {
			throw new Exceptions\OperationFailedException();
		}

		return $uuid;
	}

}
