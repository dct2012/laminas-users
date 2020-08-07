<?php

declare( strict_types = 1 );

namespace App\Model\Values;

use App\Enum\AbstractEnum;

class UserPageVisitFields extends AbstractEnum {
	const ID            = 'id';
	const USER_ID       = 'user_id';
	const PAGE_VISIT_ID = 'page_visit_id';
}