<?php

namespace User;

use User\Command\UserCommand;
use User\Controller\UserController;
use User\Factory\{UserCommandFactory, UserControllerFactory, AuthenticationServiceFactory};
use Laminas\Router\Http\Literal;
use Laminas\Authentication\AuthenticationServiceInterface;

return [
	'router'          => [
		'routes' => [
			'user' => [
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
	'view_manager'    => [
		'template_path_stack' => [
			'user' => __DIR__.'/../view',
		],
	],
	'controllers'     => [
		'factories' => [
			UserController::class => UserControllerFactory::class,
		],
	],
	'service_manager' => [
		'factories' => [
			UserCommand::class                    => UserCommandFactory::class,
			AuthenticationServiceInterface::class => AuthenticationServiceFactory::class,
		],
	],
];