<?php declare(strict_types=1);

namespace WbAssignment\Core;

use Nette;


final class RouterFactory
{

	use Nette\StaticClass;



	public static function createRouter(): Nette\Application\Routers\RouteList
	{
		$router = new Nette\Application\Routers\RouteList();
		$router->addRoute('<presenter>/<action>[/<id>]', 'Home:default');
		return $router;
	}

}
