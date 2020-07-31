<?php

declare( strict_types = 1 );

namespace App\Model\Values;

use App\Enum\AbstractEnum;

class PageVisitFields extends AbstractEnum {
	const ID         = 'id';
	const PAGE       = 'page';
	const USER_ID    = 'user_id';
	const IP_ADDRESS = 'ip_address';
	const DEVICE     = 'device';
	const VISIT_TIME = 'visit_time';
}