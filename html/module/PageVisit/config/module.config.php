<?php

namespace PageVisit;

use PageVisit\Command\PageVisitCommand;
use PageVisit\Factory\PageVisitCommandFactory;

return [
	'service_manager' => [
		'factories' => [
			PageVisitCommand::class => PageVisitCommandFactory::class,
		],
	],
];