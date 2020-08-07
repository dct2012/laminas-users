<?php

declare( strict_types = 1 );

namespace App\Model\Helper\Factory;

use App\Model\Helper\PageVisitHelper;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class PageVisitHelperFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $C
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return PageVisitHelper
	 */
	public function __invoke( ContainerInterface $C, $requestedName, ?array $options = null ): PageVisitHelper {
		return new PageVisitHelper( $C->get( AdapterInterface::class ) );
	}
}