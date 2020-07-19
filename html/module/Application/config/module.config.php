<?php

declare( strict_types = 1 );

namespace Application;

use Application\Controller\IndexController;
use Laminas\Router\Http\Literal;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
	'router'       => [
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
		],
	],
	'controllers'  => [
		'factories' => [
			IndexController::class => InvokableFactory::class,
		],
	],
	'view_manager' => [
		'display_not_found_reason' => true,
		'display_exceptions'       => true,
		'doctype'                  => 'HTML5',
		'not_found_template'       => 'error/404',
		'exception_template'       => 'error/index',
		'template_map'             => [
			'layout/layout'           => __DIR__.'/../view/layout/layout.phtml',
			'application/index/index' => __DIR__.'/../view/application/index/index.phtml',
			'error/404'               => __DIR__.'/../view/error/404.phtml',
			'error/index'             => __DIR__.'/../view/error/index.phtml',
		],
		'template_path_stack'      => [
			__DIR__.'/../view',
		],
	],
	'navigation'   => [
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
