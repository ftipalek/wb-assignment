<?php declare(strict_types=1);

namespace WbAssignment\Core\Devices;

use Ramsey;
use WbAssignment;


class Device
{

	public function __construct(
		public readonly Ramsey\Uuid\UuidInterface $uuid,
		public readonly string $hostname,
		public readonly OperatingSystem $operatingSystem,
		public readonly WbAssignment\Core\Owners\Owner $owner,
		public readonly Type $type,
	) {}

}
