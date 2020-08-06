<?php

declare( strict_types = 1 );

namespace App\Model\Values;

use App\Enum\AbstractEnum;

class LoginFields extends AbstractHasTrackingFields {
	const LOGIN_TIME  = 'login_time';
	const LOGOUT_TIME = 'logout_time';
}