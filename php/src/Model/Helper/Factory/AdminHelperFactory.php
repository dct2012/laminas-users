<?php

declare( strict_types = 1 );

namespace App\Model\Helper\Factory;

use App\Model\Helper\AdminHelper;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AdminHelperFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $C
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return AdminHelper
	 */
	public function __invoke( ContainerInterface $C, $requestedName, ?array $options = null ): AdminHelper {
		return new AdminHelper( $C->get( AdapterInterface::class ) );
	}
}