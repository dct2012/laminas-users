<?php

declare( strict_types = 1 );

namespace App\Controller\Factory;

use App\Controller\AdminController;
use Interop\Container\ContainerInterface;
use Laminas\Session\SessionManager;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AdminControllerFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $C
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return AdminController
	 */
	public function __invoke( ContainerInterface $C, $requestedName, ?array $options = null ): AdminController {
		return new AdminController( $C->get( AdapterInterface::class ), $C->get( 'FormElementManager' ), $C->get( SessionManager::class ) );
	}
}