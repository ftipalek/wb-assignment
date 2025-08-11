<?php declare(strict_types=1);

namespace WbAssignment\Presentation\Api;

use Nette;


class BaseApiPresenter extends Nette\Application\UI\Presenter
{

	/**
	 * @return array<string, mixed>|NULL
	 */
	protected function getRequestData(): ?array
	{
		$jsonData = $this->getHttpRequest()->getRawBody();

		if ($jsonData === NULL) {
			return NULL;
		}

		try {
			return Nette\Utils\Json::decode($jsonData, TRUE);
		} catch (Nette\Utils\JsonException) {
			return NULL;
		}
	}

}
