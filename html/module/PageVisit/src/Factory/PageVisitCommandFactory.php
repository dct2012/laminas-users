<?php

namespace PageVisit\Factory;

use PageVisit\Command\PageVisitCommand;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class PageVisitCommandFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return PageVisitCommand
	 */
	public function __invoke( ContainerInterface $container, $requestedName, ?array $options = null ) {
		return new PageVisitCommand( $container->get( AdapterInterface::class ) );
	}
}