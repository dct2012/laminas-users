<?php

declare( strict_types = 1 );

namespace App\Model;

use App\Model\Traits\{TraitComposition, TraitTrackingComposite, TraitLoginLogoutComposite};

class Login implements ModelInterface {
	use TraitComposition, TraitTrackingComposite, TraitLoginLogoutComposite;

	/**
	 * @param string      $ipAddress
	 * @param string      $userAgent
	 * @param ?int        $id
	 * @param ?string     $loginTime
	 * @param string|null $logoutTime
	 */
	public function __construct( string $ipAddress, string $userAgent, ?int $id = null, ?string $loginTime = null, ?string $logoutTime = null ) {
		$this->setId( $id );
		$this->setIpAddress( $ipAddress );
		$this->setUserAgent( $userAgent );
		$this->setLoginTime( $loginTime );
		$this->setLogoutTime( $logoutTime );
	}
}