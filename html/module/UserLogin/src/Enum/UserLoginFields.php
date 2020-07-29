<?php

namespace UserLogin\Enum;

use Application\Enum\AbstractEnum;

class UserLoginFields extends AbstractEnum {
	const ID          = 'id';
	const USER_ID     = 'user_id';
	const IP_ADDRESS  = 'ip_address';
	const DEVICE      = 'device';
	const LOGIN_TIME  = 'login_time';
	const LOGOUT_TIME = 'logout_time';
}