<?php

declare( strict_types = 1 );

namespace App\Model\Helper\Factory;

use App\Model\Helper\IdentityHelper;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class IdentityHelperFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $C
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return IdentityHelper
	 */
	public function __invoke( ContainerInterface $C, $requestedName, ?array $options = null ): IdentityHelper {
		return new IdentityHelper( $C->get( AdapterInterface::class ) );
	}
}