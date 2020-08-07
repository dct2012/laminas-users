<?php

declare( strict_types = 1 );

namespace App\Model\Values;

use App\Enum\AbstractEnum;

class AdminPageVisitFields extends AbstractEnum {
	const ID            = 'id';
	const ADMIN_ID      = 'admin_id';
	const PAGE_VISIT_ID = 'page_visit_id';
}