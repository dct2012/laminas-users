<?php

declare( strict_types = 1 );

namespace App\Controller\Factory;

use App\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Laminas\Session\SessionManager;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $C
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return IndexController
	 */
	public function __invoke( ContainerInterface $C, $requestedName, ?array $options = null ): IndexController {
		return new IndexController( $C->get( AdapterInterface::class ), $C->get( SessionManager::class ), $C->get( 'FormElementManager' ) );
	}
}