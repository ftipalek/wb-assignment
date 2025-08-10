<?php declare(strict_types=1);

namespace WbAssignment\Presentation\Api\Owners;

use Nette;
use WbAssignment;


final class OwnersPresenter extends Nette\Application\UI\Presenter
{

	public function __construct(
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
					[$this, 'formatOwner'],
					$this->ownersRepository->loadOwners(),
				),
			);
		}

		throw new Nette\NotImplementedException();
	}



	private function formatOwner(WbAssignment\Core\Owners\Owner $owner): array
	{
		return [
			'uuid' => $owner->uuid,
			'firstname' => $owner->firstname,
			'lastname' => $owner->lastname,
		];
	}

}
