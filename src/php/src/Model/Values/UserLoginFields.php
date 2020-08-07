<?php

declare( strict_types = 1 );

namespace App\Model\Values;

use App\Enum\AbstractEnum;

class UserLoginFields extends AbstractEnum {
	const ID       = 'id';
	const USER_ID  = 'user_id';
	const LOGIN_ID = 'login_id';
}