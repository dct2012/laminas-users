<?php

declare( strict_types = 1 );

namespace App\Model\Values;

use App\Enum\AbstractEnum;

class LoginFields extends AbstractEnum {
	const ID          = 'id';
	const IP_ADDRESS  = 'ip_address';
	const USER_AGENT  = 'user_agent';
	const LOGIN_TIME  = 'login_time';
	const LOGOUT_TIME = 'logout_time';
}