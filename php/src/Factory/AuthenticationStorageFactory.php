<?php

declare( strict_types = 1 );

namespace App\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Authentication\Storage\Session;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AuthenticationStorageFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return Session
	 */
	public function __invoke( ContainerInterface $container, $requestedName, ?array $options = null ) {
		return new Session();
	}
}