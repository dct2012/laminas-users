<?php

declare( strict_types = 1 );

namespace App;

use Laminas\Router\Http\Literal;
use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Authentication\Storage\StorageInterface as AuthStorageInterface;
use App\Controller\IndexController;
use App\Controller\Factory\IndexControllerFactory;
use App\Factory\{AuthenticationServiceFactory, AuthenticationStorageFactory};
use App\Model\Helper\{PageVisitModelHelper, UserModelHelper, UserLoginModelHelper};
use App\Model\Helper\Factory\{PageVisitModelHelperFactory, UserModelHelperFactory, UserLoginModelHelperFactory};

return [
	'router'          => [
		'routes' => [
			'home' => [
				'type'    => Literal::class,
				'options' => [
					'route'    => '/',
					'defaults' => [
						'controller' => IndexController::class,
						'action'     => 'index',
					],
				],
			],
			'user' => [
				'type'          => Literal::class,
				'options'       => [
					'route'    => '/user',
					'defaults' => [
						'controller' => IndexController::class,
						'action'     => 'user',
					],
				],
				'may_terminate' => true,
				'child_routes'  => [
					'update' => [
						'type'    => Literal::class,
						'options' => [
							'route'    => '/update',
							'defaults' => [
								'controller' => IndexController::class,
								'action'     => 'update',
							],
						],
					],
					'delete' => [
						'type'    => Literal::class,
						'options' => [
							'route'    => '/delete',
							'defaults' => [
								'controller' => IndexController::class,
								'action'     => 'delete',
							],
						],
					],
					'login'  => [
						'type'    => Literal::class,
						'options' => [
							'route'    => '/login',
							'defaults' => [
								'controller' => IndexController::class,
								'action'     => 'login',
							],
						],
					],
					'logout' => [
						'type'    => Literal::class,
						'options' => [
							'route'    => '/logout',
							'defaults' => [
								'controller' => IndexController::class,
								'action'     => 'logout',
							],
						],
					],
					'signup' => [
						'type'    => Literal::class,
						'options' => [
							'route'    => '/signup',
							'defaults' => [
								'controller' => IndexController::class,
								'action'     => 'signup',
							],
						],
					],
				],
			],
		],
	],
	'service_manager' => [
		'factories' => [
			AuthenticationServiceInterface::class => AuthenticationServiceFactory::class,
			AuthStorageInterface::class           => AuthenticationStorageFactory::class,
			PageVisitModelHelper::class           => PageVisitModelHelperFactory::class,
			UserLoginModelHelper::class           => UserLoginModelHelperFactory::class,
			UserModelHelper::class                => UserModelHelperFactory::class,
		],
	],
	'controllers'     => [
		'factories' => [
			IndexController::class => IndexControllerFactory::class,
		],
	],
	'view_manager'    => [
		'display_not_found_reason' => true,
		'display_exceptions'       => true,
		'doctype'                  => 'HTML5',
		'not_found_template'       => 'error/404',
		'exception_template'       => 'error/index',
		'template_map'             => [
			'layout/layout'   => __DIR__.'/../view/layout/layout.phtml',
			'app/index/index' => __DIR__.'/../view/app/index/index.phtml',
			'error/404'       => __DIR__.'/../view/error/404.phtml',
			'error/index'     => __DIR__.'/../view/error/index.phtml',
		],
		'template_path_stack'      => [
			__DIR__.'/../view',
		],
	],
	'navigation'      => [
		'default' => [
			[
				'label' => 'Home',
				'route' => 'home',
			],
			[
				'label' => 'User',
				'route' => 'user',
				'pages' => [
					[
						'label'  => 'Update Password',
						'route'  => 'user/update',
						'action' => 'update',
					],
					[
						'label'  => 'Delete User',
						'route'  => 'user/delete',
						'action' => 'delete',
					],
					[
						'label'  => 'Login',
						'route'  => 'user/login',
						'action' => 'login',
					],
					[
						'label'  => 'Sign Up',
						'route'  => 'user/signup',
						'action' => 'signup',
					],
				],
			],
		],
	],
];
