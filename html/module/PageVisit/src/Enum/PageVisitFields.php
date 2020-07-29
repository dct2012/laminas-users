<?php

namespace PageVisit\Enum;

use Application\Enum\AbstractEnum;

class PageVisitFields extends AbstractEnum {
	const ID         = 'id';
	const PAGE       = 'page';
	const USER_ID    = 'user_id';
	const IP_ADDRESS = 'ip_address';
	const DEVICE     = 'device';
	const VISIT_TIME = 'visit_time';
}