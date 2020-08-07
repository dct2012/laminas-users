<?php

declare( strict_types = 1 );

namespace App\Model\Values;

use App\Enum\AbstractEnum;

class PageVisitFields extends AbstractEnum {
	const ID         = 'id';
	const PAGE       = 'page';
	const IP_ADDRESS = 'ip_address';
	const USER_AGENT = 'user_agent';
	const VISIT_TIME = 'visit_time';
}