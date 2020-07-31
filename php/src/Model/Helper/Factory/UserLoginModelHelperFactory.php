<?php

declare( strict_types = 1 );

namespace App\Model\Helper\Factory;

use App\Model\Helper\UserLoginModelHelper;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserLoginModelHelperFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return UserLoginModelHelper
	 */
	public function __invoke( ContainerInterface $container, $requestedName, ?array $options = null ): UserLoginModelHelper {
		return new UserLoginModelHelper( $container->get( AdapterInterface::class ) );
	}
}