<?php

declare( strict_types = 1 );

namespace App\Model\Values;

abstract class AbstractHasIdentityFields extends AbstractHasIdFields {
	const IDENTITY_ID = 'identity_id';
}