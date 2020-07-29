<?php

namespace User\Factory;

use User\Command\UserCommand;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserCommandFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return UserCommand
	 */
	public function __invoke( ContainerInterface $container, $requestedName, ?array $options = null ) {
		return new UserCommand( $container->get( AdapterInterface::class ) );
	}
}