<?php declare(strict_types=1);

namespace WbAssignment\Presentation\Error\Error5xx;

use Nette;
use Tracy;


/**
 * Handles uncaught exceptions and errors, and logs them.
 */
#[Nette\Application\Attributes\Requires(forward: TRUE)]
final class Error5xxPresenter implements Nette\Application\IPresenter
{

	public function __construct(
		private Tracy\ILogger $logger,
	) {}



	#[\Override]
	public function run(Nette\Application\Request $request): Nette\Application\Response
	{
		// Log the exception
		$exception = $request->getParameter('exception');
		$this->logger->log($exception, Tracy\ILogger::EXCEPTION);

		// Display a generic error message to the user
		return new Nette\Application\Responses\CallbackResponse(function (Nette\Http\IRequest $httpRequest, Nette\Http\IResponse $httpResponse): void {
			if (preg_match('#^text/html(?:;|$)#', (string) $httpResponse->getHeader('Content-Type'))) {
				require __DIR__ . '/500.phtml';
			}
		});
	}

}
