<?php

declare( strict_types = 1 );

namespace App\Model\Traits;

trait TraitTrackingComposite {
	/** @var string */
	protected string $ip_address, $user_agent;

	/** @return string */
	public function getIpAddress(): string {
		return $this->ip_address;
	}

	/** @return string */
	public function getUserAgent(): string {
		return $this->user_agent;
	}

	/**
	 * @param string $ipAddress
	 *
	 * @return self
	 */
	public function setIpAddress( string $ipAddress ): self {
		$this->ip_address = $ipAddress;

		return $this;
	}

	/**
	 * @param string $userAgent
	 *
	 * @return self
	 */
	public function setUserAgent( string $userAgent ): self {
		$this->user_agent = $userAgent;

		return $this;
	}


}