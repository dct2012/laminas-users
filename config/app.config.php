<?php

/**
 * If you need an environment-specific system or app configuration, there is an example in the documentation
 * @see https://docs.laminas.dev/tutorials/advanced-config/#environment-specific-system-configuration
 * @see https://docs.laminas.dev/tutorials/advanced-config/#environment-specific-application-configuration
 */

use Laminas\Session\SessionManager;
use Laminas\Session\Config\ConfigInterface;
use Laminas\Session\Storage\StorageInterface;
use Laminas\Session\Service\{SessionConfigFactory, SessionManagerFactory, StorageFactory};

return [
	// Retrieve list of modules used in this app.
	'modules'                 => require __DIR__.'/modules.config.php',
	// These are various options for the listeners attached to the ModuleManager
	'module_listener_options' => [
		// use composer autoloader instead of laminas-loader
		'use_laminas_loader'       => false,

		// An array of paths from which to glob configuration files after
		// modules are loaded. These effectively override configuration
		// provided by modules themselves. Paths may use GLOB_BRACE notation.
		'config_glob_paths'        => [
			realpath( __DIR__ ).'/autoload/{{,*.}global,{,*.}local}.php',
		],

		// Whether or not to enable a configuration cache.
		// If enabled, the merged configuration will be cached and used in
		// subsequent requests.
		'config_cache_enabled'     => true,

		// The key used to create the configuration cache file name.
		'config_cache_key'         => 'app.config.cache',

		// Whether or not to enable a module class map cache.
		// If enabled, creates a module class map cache which will be used
		// by in future requests, to reduce the autoloading process.
		'module_map_cache_enabled' => true,

		// The key used to create the class map cache file name.
		'module_map_cache_key'     => 'app.module.cache',

		// The path in which to cache merged configuration.
		'cache_dir'                => 'data/cache/',

		// Whether or not to enable modules dependency checking.
		// Enabled by default, prevents usage of modules that depend on other modules
		// that weren't loaded.
		'check_dependencies'       => true,
	],

	// Used to create an own service manager. May contain one or more child arrays.
	// 'service_listener_options' => [
	//     [
	//         'service_manager' => $stringServiceManagerName,
	//         'config_key'      => $stringConfigKey,
	//         'interface'       => $stringOptionalInterface,
	//         'method'          => $stringRequiredMethodName,
	//     ],
	// ],

	// Initial configuration with which to seed the ServiceManager.
	'service_manager'         => [
		'abstract_factories' => [],
		'aliases'            => [],
		'delegators'         => [],
		'factories'          => [
			ConfigInterface::class  => SessionConfigFactory::class,
			StorageInterface::class => StorageFactory::class,
			SessionManager::class   => SessionManagerFactory::class,
		],
		'initializers'       => [],
		'invokables'         => [],
		'lazy_services'      => [],
		'services'           => [],
		'shared'             => [],
	],
];
