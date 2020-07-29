<?php

namespace UserLogin;

use UserLogin\Command\UserLoginCommand;
use UserLogin\Factory\UserLoginCommandFactory;

return [
	'service_manager' => [
		'factories' => [
			UserLoginCommand::class => UserLoginCommandFactory::class,
		],
	],
];