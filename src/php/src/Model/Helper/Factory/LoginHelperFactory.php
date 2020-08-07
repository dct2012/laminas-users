<?php

declare( strict_types = 1 );

namespace App\Model\Helper\Factory;

use App\Model\Helper\LoginHelper;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class LoginHelperFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $C
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return LoginHelper
	 */
	public function __invoke( ContainerInterface $C, $requestedName, ?array $options = null ): LoginHelper {
		return new LoginHelper( $C->get( AdapterInterface::class ) );
	}
}