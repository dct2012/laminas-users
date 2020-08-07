<?php

declare( strict_types = 1 );

namespace App\Model\Values;

use App\Enum\AbstractEnum;

class AdminLoginFields extends AbstractEnum {
	const ID       = 'id';
	const ADMIN_ID = 'admin_id';
	const LOGIN_ID = 'login_id';
}