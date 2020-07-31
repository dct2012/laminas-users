<?php

declare( strict_types = 1 );

namespace App\Model\Helper\Factory;

use App\Model\Helper\UserModelHelper;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserModelHelperFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return UserModelHelper
	 */
	public function __invoke( ContainerInterface $container, $requestedName, ?array $options = null ): UserModelHelper {
		return new UserModelHelper( $container->get( AdapterInterface::class ) );
	}
}