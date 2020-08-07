<?php

declare( strict_types = 1 );

namespace App\Model\Helper\Factory;

use App\Model\Helper\AdminLoginHelper;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AdminLoginHelperFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $C
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return AdminLoginHelper
	 */
	public function __invoke( ContainerInterface $C, $requestedName, ?array $options = null ): AdminLoginHelper {
		return new AdminLoginHelper( $C->get( AdapterInterface::class ) );
	}
}