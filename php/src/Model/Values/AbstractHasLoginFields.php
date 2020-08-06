<?php

declare( strict_types = 1 );

namespace App\Model\Values;

abstract class AbstractHasLoginFields extends AbstractHasIdFields {
	const LOGIN_ID = 'login_id';
}