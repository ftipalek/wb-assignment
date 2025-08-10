<?php declare(strict_types=1);

namespace WbAssignment\Presentation\Api\Owners;

use Nette;
use WbAssignment;


final class OwnersPresenter extends WbAssignment\Presentation\Api\BaseApiPresenter
{

	public function __construct(
		private readonly WbAssignment\Core\Owners\Creator $ownersCreator,
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
		} elseif ($request->method === 'POST') {
			$requestData = $this->getRequestData();

			if ($requestData === NULL) {
				$this->getHttpResponse()->setCode(Nette\Http\IResponse::S400_BadRequest);
				return new Nette\Application\Responses\JsonResponse([
					'error' => 'Invalid or missing request input',
				]);
			}

			try {
				$data = $this->validateAndNormalizeInput($requestData);
			} catch (Nette\Schema\ValidationException $e) {
				$this->getHttpResponse()->setCode(Nette\Http\IResponse::S400_BadRequest);
				return new Nette\Application\Responses\JsonResponse([
					'error' => $e->getMessage(),
				]);
			}

			try {
				$uuid = $this->ownersCreator->create(
					$data['firstname'],
					$data['lastname'],
				);
			} catch (WbAssignment\Core\Owners\Exceptions\OperationFailedException) {
				$this->getHttpResponse()->setCode(Nette\Http\IResponse::S500_InternalServerError);
				return new Nette\Application\Responses\JsonResponse([
					'error' => 'Some error has occurred',
				]);
			}

			return new Nette\Application\Responses\JsonResponse([
				'uuid' => $uuid,
			]);
		}

		$this->getHttpResponse()->setCode(Nette\Http\IResponse::S405_MethodNotAllowed);
		return new Nette\Application\Responses\JsonResponse([
			'error' => sprintf('Method %s is allowed', $request->method),
		]);
	}



	private function formatOwner(WbAssignment\Core\Owners\Owner $owner): array
	{
		return [
			'uuid' => $owner->uuid,
			'firstname' => $owner->firstname,
			'lastname' => $owner->lastname,
		];
	}



	/**
	 * @param array<string, mixed> $input
	 * @return array{
	 *   firstname: string,
	 *   lastname: string,
	 * }
	 */
	private function validateAndNormalizeInput(array $input): array
	{
		$schemaProcessor = new Nette\Schema\Processor();

		return $schemaProcessor->process(
			(new Nette\Schema\Elements\Structure([
				'firstname' => Nette\Schema\Expect::string()
					->max(50)
					->required(),
				'lastname' => Nette\Schema\Expect::string()
					->max(50)
					->required(),
			]))->castTo('array'),
			$input,
		);
	}

}
