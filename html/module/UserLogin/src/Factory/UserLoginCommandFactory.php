<?php

namespace UserLogin\Factory;

use UserLogin\Command\UserLoginCommand;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserLoginCommandFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return UserLoginCommand
	 */
	public function __invoke( ContainerInterface $container, $requestedName, ?array $options = null ): UserLoginCommand {
		return new UserLoginCommand( $container->get( AdapterInterface::class ) );
	}
}