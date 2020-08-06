<?php

declare( strict_types = 1 );

namespace App\Model\Helper\Factory;

use App\Model\Helper\UserLoginHelper;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserLoginHelperFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $C
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return UserLoginHelper
	 */
	public function __invoke( ContainerInterface $C, $requestedName, ?array $options = null ): UserLoginHelper {
		return new UserLoginHelper( $C->get( AdapterInterface::class ) );
	}
}