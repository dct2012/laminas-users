<?php

declare( strict_types = 1 );

namespace App;

use Laminas\Router\Http\Literal;
use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Authentication\Storage\StorageInterface as AuthStorageInterface;
use App\Enum\Routes;
use App\Controller\{AdminController, IndexController, UserController};
use App\Factory\{AuthenticationServiceFactory, AuthenticationStorageFactory};
use App\Controller\Factory\{AdminControllerFactory, IndexControllerFactory, UserControllerFactory};
use App\Model\Helper\{AdminLoginHelper, AdminHelper, AdminPageVisitHelper, IdentityHelper, LoginHelper, PageVisitHelper, UserHelper, UserLoginHelper, UserPageVisitHelper};
use App\Model\Helper\Factory\{AdminLoginHelperFactory, AdminHelperFactory, AdminPageVisitHelperFactory, IdentityHelperFactory, LoginHelperFactory, PageVisitHelperFactory, UserHelperFactory, UserLoginHelperFactory, UserPageVisitHelperFactory};

return [
	'router'          => [
		'routes' => [
			'home'  => [
				'type'    => Literal::class,
				'options' => [
					'route'    => '/',
					'defaults' => [
						'controller' => IndexController::class,
						'action'     => 'index',
					],
				],
			],
			'admin' => [
				'type'          => Literal::class,
				'options'       => [
					'route'    => '/admin',
					'defaults' => [
						'controller' => AdminController::class,
						'action'     => 'info',
					],
				],
				'may_terminate' => true,
				'child_routes'  => [
					'login'   => [
						'type'    => Literal::class,
						'options' => [
							'route'    => '/login',
							'defaults' => [
								'controller' => AdminController::class,
								'action'     => 'login',
							],
						],
					],
					'logout'  => [
						'type'    => Literal::class,
						'options' => [
							'route'    => '/logout',
							'defaults' => [
								'controller' => AdminController::class,
								'action'     => 'logout',
							],
						],
					],
					'sitemap' => [
						'type'    => Literal::class,
						'options' => [
							'route'    => '/sitemap',
							'defaults' => [
								'controller' => AdminController::class,
								'action'     => 'sitemap',
							],
						],
					],
				],
			],
			'user'  => [
				'type'          => Literal::class,
				'options'       => [
					'route'    => '/user',
					'defaults' => [
						'controller' => UserController::class,
						'action'     => 'info',
					],
				],
				'may_terminate' => true,
				'child_routes'  => [
					'update' => [
						'type'    => Literal::class,
						'options' => [
							'route'    => '/update',
							'defaults' => [
								'controller' => UserController::class,
								'action'     => 'update',
							],
						],
					],
					'delete' => [
						'type'    => Literal::class,
						'options' => [
							'route'    => '/delete',
							'defaults' => [
								'controller' => UserController::class,
								'action'     => 'delete',
							],
						],
					],
					'login'  => [
						'type'    => Literal::class,
						'options' => [
							'route'    => '/login',
							'defaults' => [
								'controller' => UserController::class,
								'action'     => 'login',
							],
						],
					],
					'logout' => [
						'type'    => Literal::class,
						'options' => [
							'route'    => '/logout',
							'defaults' => [
								'controller' => UserController::class,
								'action'     => 'logout',
							],
						],
					],
					'signup' => [
						'type'    => Literal::class,
						'options' => [
							'route'    => '/signup',
							'defaults' => [
								'controller' => UserController::class,
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
			UserHelper::class                     => UserHelperFactory::class,
			AdminHelper::class                    => AdminHelperFactory::class,
			LoginHelper::class                    => LoginHelperFactory::class,
			IdentityHelper::class                 => IdentityHelperFactory::class,
			PageVisitHelper::class                => PageVisitHelperFactory::class,
			UserLoginHelper::class                => UserLoginHelperFactory::class,
			AdminLoginHelper::class               => AdminLoginHelperFactory::class,
			UserPageVisitHelper::class            => UserPageVisitHelperFactory::class,
			AdminPageVisitHelper::class           => AdminPageVisitHelperFactory::class,
			AuthStorageInterface::class           => AuthenticationStorageFactory::class,
			AuthenticationServiceInterface::class => AuthenticationServiceFactory::class,
		],
	],
	'controllers'     => [
		'factories' => [
			IndexController::class => IndexControllerFactory::class,
			UserController::class  => UserControllerFactory::class,
			AdminController::class => AdminControllerFactory::class,
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
				'label' => 'Admin',
				'route' => Routes::ADMIN,
				'pages' => [
					[
						'label'  => 'Login',
						'route'  => Routes::ADMIN_LOGIN,
						'action' => 'login',
					],
					[
						'label'  => 'Sitemap',
						'route'  => Routes::ADMIN_SITEMAP,
						'action' => 'sitemap',
					],
				],
			],
			[
				'label' => 'User',
				'route' => Routes::USER,
				'pages' => [
					[
						'label'  => 'Update Password',
						'route'  => Routes::USER_UPDATE,
						'action' => 'update',
					],
					[
						'label'  => 'Delete',
						'route'  => Routes::USER_DELETE,
						'action' => 'delete',
					],
					[
						'label'  => 'Login',
						'route'  => Routes::USER_LOGIN,
						'action' => 'login',
					],
					[
						'label'  => 'Sign Up',
						'route'  => Routes::USER_SIGNUP,
						'action' => 'signup',
					],
				],
			],
		],
	],
];
