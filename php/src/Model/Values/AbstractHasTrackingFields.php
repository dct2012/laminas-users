<?php

declare( strict_types = 1 );

namespace App\Model\Values;

abstract class AbstractHasTrackingFields extends AbstractHasIdFields {
	const IP_ADDRESS = 'ip_address';
	const USER_AGENT = 'user_agent';
}