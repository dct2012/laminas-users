<?php

declare( strict_types = 1 );

namespace App\Factory;

use App\Model\Identity;
use App\Model\Helper\IdentityHelper;
use App\Model\Values\IdentityFields as IFs;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\StorageInterface;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter;

class AuthenticationServiceFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $C
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return AuthenticationService
	 */
	public function __invoke( ContainerInterface $C, $requestedName, ?array $options = null ): AuthenticationService {
		return new AuthenticationService( $C->get( StorageInterface::class ),
			new CallbackCheckAdapter(
				$C->get( AdapterInterface::class ),
				IdentityHelper::getTableName(),
				IFs::NAME,
				IFs::PASSWORD,
				fn( $hash, $password ) => Identity::verifyPassword( $hash, $password )
			)
		);
	}
}