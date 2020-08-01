<?php

declare( strict_types = 1 );

namespace App\Factory;

use App\Model\User;
use App\Model\Helper\UserModelHelper;
use App\Model\Values\UserFields as UFs;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\StorageInterface;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter;

class AuthenticationServiceFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return AuthenticationService
	 */
	public function __invoke( ContainerInterface $container, $requestedName, ?array $options = null ): AuthenticationService {
		return new AuthenticationService(
			$container->get( StorageInterface::class ),
			new CallbackCheckAdapter(
				$container->get( AdapterInterface::class ),
				UserModelHelper::getTableName(),
				UFs::USERNAME,
				UFs::PASSWORD,
				fn( $hash, $password ) => User::verifyPassword( $hash, $password )
			)
		);
	}
}