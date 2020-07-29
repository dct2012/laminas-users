<?php

namespace User\Enum;

use Application\Enum\AbstractEnum;

class UserFields extends AbstractEnum {
	const ID         = 'id';
	const USERNAME   = 'username';
	const PASSWORD   = 'password';
	const UPDATED_ON = 'updated_on';
	const CREATED_ON = 'created_on';
}