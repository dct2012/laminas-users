<?php

declare( strict_types = 1 );

namespace App\Model\Values;

use App\Enum\AbstractEnum;

class UserFields extends AbstractEnum {
	const ID          = 'id';
	const IDENTITY_ID = 'identity_id';
}