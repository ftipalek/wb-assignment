<?php declare(strict_types=1);

namespace WbAssignment\Core\Owners;

use Ramsey;


class Owner
{

	public function __construct(
		public readonly Ramsey\Uuid\UuidInterface $uuid,
		public readonly string $firstname,
		public readonly string $lastname,
	) {}

}
