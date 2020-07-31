<?php

declare( strict_types = 1 );

namespace App\Model\Helper\Factory;

use App\Model\Helper\PageVisitModelHelper;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class PageVisitModelHelperFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return PageVisitModelHelper
	 */
	public function __invoke( ContainerInterface $container, $requestedName, ?array $options = null ): PageVisitModelHelper {
		return new PageVisitModelHelper( $container->get( AdapterInterface::class ) );
	}
}