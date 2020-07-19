<?php

namespace User\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use User\Command\UserCommand;

class UserCommandFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string $requestedName
	 * @param array|null $options
	 * @return object|UserCommand
	 */
	public function __invoke( ContainerInterface $container, $requestedName, array $options = null ) {
		return new UserCommand( $container->get( AdapterInterface::class ) );
	}
}