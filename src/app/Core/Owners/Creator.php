<?php declare(strict_types=1);

namespace WbAssignment\Core\Owners;

use Dibi;
use Ramsey;


class Creator
{

	public function __construct(
		private readonly Dibi\Connection $database,
	) {}



	/**
	 * @throws Exceptions\OperationFailedException
	 */
	public function create(
		string $firstname,
		string $lastname,
	): Ramsey\Uuid\UuidInterface
	{
		$uuid = Ramsey\Uuid\Uuid::uuid4();

		try {
			$this->database->query('
				INSERT INTO `owners`', [
					'uuid%s' => $uuid->toString(),
					'firstname%s' => $firstname,
					'lastname%s' => $lastname,
				],
			);
		} catch (Dibi\ConstraintViolationException) {
			throw new Exceptions\OperationFailedException();
		}

		return $uuid;
	}

}
