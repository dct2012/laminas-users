<?php

namespace User\Factory;

use User\Model\User;
use Laminas\Db\Adapter\AdapterInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter;

class AuthenticationServiceFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string $requestedName
	 * @param array|null $options
	 * @return AuthenticationService|object
	 */
	public function __invoke( ContainerInterface $container, $requestedName, array $options = null ) {
		$authAdapter = new CallbackCheckAdapter(
			$container->get( AdapterInterface::class ),
			'users',
			'username',
			'password',
			fn( $hash, $password ) => User::verifyPassword( $hash, $password )
		);

		return new AuthenticationService( null, $authAdapter );
	}
}