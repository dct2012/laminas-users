<?php

namespace User\Factory;

use User\Command\UserCommand;
use User\Controller\UserController;
use UserLogin\Command\UserLoginCommand;
use PageVisit\Command\PageVisitCommand;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserControllerFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return UserController
	 */
	public function __invoke( ContainerInterface $container, $requestedName, ?array $options = null ) {
		return new UserController( $container->get( UserCommand::class ), $container->get( UserLoginCommand::class ), $container->get( 'FormElementManager' ), $container->get( PageVisitCommand::class ) );
	}
}