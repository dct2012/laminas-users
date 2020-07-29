<?php

namespace User\Factory;

use User\Model\User;
use User\Command\UserCommand;
use User\Enum\UserFields as UFs;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter;

class AuthenticationServiceFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return AuthenticationService|object
	 */
	public function __invoke( ContainerInterface $container, $requestedName, ?array $options = null ) {
		return new AuthenticationService( null, new CallbackCheckAdapter(
			$container->get( AdapterInterface::class ),
			UserCommand::getTableName(),
			UFs::USERNAME,
			UFs::PASSWORD,
			fn( $hash, $password ) => User::verifyPassword( $hash, $password )
		) );
	}
}