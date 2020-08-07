<?php

declare( strict_types = 1 );

namespace App\Model\Helper\Factory;

use App\Model\Helper\UserPageVisitHelper;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UserPageVisitHelperFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $C
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return UserPageVisitHelper
	 */
	public function __invoke( ContainerInterface $C, $requestedName, ?array $options = null ): UserPageVisitHelper {
		return new UserPageVisitHelper( $C->get( AdapterInterface::class ) );
	}
}