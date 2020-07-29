<?php

namespace User\Factory;

use User\Command\UserCommand as Command;
use User\Controller\UserController as Controller;
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
		return new Controller( $container->get( Command::class ), $container->get( 'FormElementManager' ) );
	}
}