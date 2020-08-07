<?php

declare( strict_types = 1 );

namespace App\Model\Helper\Factory;

use App\Model\Helper\UserHelper;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserHelperFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $C
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return UserHelper
	 */
	public function __invoke( ContainerInterface $C, $requestedName, ?array $options = null ): UserHelper {
		return new UserHelper( $C->get( AdapterInterface::class ) );
	}
}