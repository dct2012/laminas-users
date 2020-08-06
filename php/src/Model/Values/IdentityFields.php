<?php

declare( strict_types = 1 );

namespace App\Model\Values;

use App\Enum\AbstractEnum;

class IdentityFields extends AbstractEnum {
	const ID         = 'id';
	const NAME       = 'name';
	const PASSWORD   = 'password';
	const UPDATED_ON = 'updated_on';
	const CREATED_ON = 'created_on';
}