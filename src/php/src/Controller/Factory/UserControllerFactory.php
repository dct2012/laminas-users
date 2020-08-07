<?php

declare( strict_types = 1 );

namespace App\Controller\Factory;

use App\Controller\UserController;
use Interop\Container\ContainerInterface;
use Laminas\Session\SessionManager;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserControllerFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $C
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return UserController
	 */
	public function __invoke( ContainerInterface $C, $requestedName, ?array $options = null ): UserController {
		return new UserController( $C->get( AdapterInterface::class ), $C->get( 'FormElementManager' ), $C->get( SessionManager::class ) );
	}
}