<?php

declare( strict_types = 1 );

namespace App\Model\Helper\Factory;

use App\Model\Helper\AdminPageVisitHelper;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AdminPageVisitHelperFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $C
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return AdminPageVisitHelper
	 */
	public function __invoke( ContainerInterface $C, $requestedName, ?array $options = null ): AdminPageVisitHelper {
		return new AdminPageVisitHelper( $C->get( AdapterInterface::class ) );
	}
}