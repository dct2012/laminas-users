<?php

declare( strict_types = 1 );

namespace App\Model\Values;

use App\Enum\AbstractEnum;

abstract class AbstractHasIdFields extends AbstractEnum {
	const ID = 'id';
}