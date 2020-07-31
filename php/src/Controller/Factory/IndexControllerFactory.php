<?php

declare( strict_types = 1 );

namespace App\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use App\Controller\IndexController;
use App\Model\Helper\{PageVisitModelHelper, UserModelHelper, UserLoginModelHelper};

class IndexControllerFactory implements FactoryInterface {
	/**
	 * @param ContainerInterface $container
	 * @param string             $requestedName
	 * @param ?array             $options
	 *
	 * @return IndexController
	 */
	public function __invoke( ContainerInterface $container, $requestedName, ?array $options = null ): IndexController {
		return new IndexController(
			$container->get( 'FormElementManager' ),
			$container->get( UserModelHelper::class ),
			$container->get( UserLoginModelHelper::class ),
			$container->get( PageVisitModelHelper::class )
		);
	}
}