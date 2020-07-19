<?php

namespace User\Factory;

use User\Command\UserCommand;
use User\Controller\UserController;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserControllerFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string $requestedName
	 * @param array|null $options
	 * @return object|UserController
	 */
	public function __invoke( ContainerInterface $container, $requestedName, array $options = null ) {
		return new UserController(
			$container->get( UserCommand::class ),
			$container->get( 'FormElementManager' )
		);
	}
}