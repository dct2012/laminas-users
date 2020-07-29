<?php

namespace User\Factory;

use User\Command\UserCommand;
use User\Controller\UserController as Controller;
use UserLogin\Command\UserLoginCommand;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserControllerFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return object|Controller
	 */
	public function __invoke( ContainerInterface $container, $requestedName, ?array $options = null ) {
		return new Controller( $container->get( UserCommand::class ), $container->get( UserLoginCommand::class ), $container->get( 'FormElementManager' ) );
	}
}