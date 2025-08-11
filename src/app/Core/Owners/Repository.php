<?php declare(strict_types=1);

namespace WbAssignment\Core\Owners;

use Dibi;
use Ramsey;


class Repository
{

	public function __construct(
		private readonly Dibi\Connection $database,
	) {}



	public function getOwner(Ramsey\Uuid\UuidInterface $uuid): Owner
	{
		$row = $this->database->query('
			SELECT *
			FROM `owners`
			WHERE `uuid` = %s', $uuid->toString(), '
		')->fetch();

		if ($row === NULL) {
			throw new Exceptions\NotFoundException();
		}

		return $this->createOwner($row);
	}



	/**
	 * @return list<Owner>
	 */
	public function loadOwners(): array
	{
		return array_map(
			fn (Dibi\Row $row) => $this->createOwner($row),
			$this->database->query('
				SELECT *
				FROM `owners`
			')->fetchAll(),
		);
	}



	private function createOwner(Dibi\Row $row): Owner
	{
		return new Owner(
			uuid: Ramsey\Uuid\Uuid::fromString($row->uuid),
			firstname: $row->firstname,
			lastname: $row->lastname,
		);
	}

}
