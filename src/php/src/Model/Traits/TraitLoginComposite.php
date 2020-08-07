<?php

declare( strict_types = 1 );

namespace App\Model\Traits;

use App\Model\Login;

trait TraitLoginComposite {
	/** @var Login */
	protected Login $Login;
	/** @var ?int */
	protected ?int $login_id;

	/** @return Login */
	public function getLogin(): Login {
		return $this->Login;
	}

	/** @return int */
	public function getLoginId(): int {
		if( empty( $this->login_id ) ) {
			$this->login_id = $this->Login->getId();
		}

		return $this->login_id;
	}

	/** @return string */
	public function getIpAddress(): string {
		return $this->Login->getIpAddress();
	}

	/** @return string */
	public function getUserAgent(): string {
		return $this->Login->getUserAgent();
	}

	/** @return string */
	public function getLoginTime(): string {
		return $this->Login->getLoginTime();
	}

	/** @return string */
	public function getLogoutTime(): string {
		return $this->Login->getLogoutTime();
	}

	/**
	 * @param Login $Login
	 *
	 * @return self
	 */
	public function setLogin( Login $Login ): self {
		$this->Login = $Login;

		return $this;
	}

	/**
	 * @param int $id
	 *
	 * @return self
	 */
	public function setLoginId( int $id ): self {
		$this->Login->setId( $id );
		$this->login_id = $id;

		return $this;
	}

	/**
	 * @param string $ipAddress
	 *
	 * @return self
	 */
	public function setIpAddress( string $ipAddress ): self {
		$this->Login->setIpAddress( $ipAddress );

		return $this;
	}

	/**
	 * @param string $userAgent
	 *
	 * @return self
	 */
	public function setUserAgent( string $userAgent ): self {
		$this->Login->setUserAgent( $userAgent );

		return $this;
	}

	/**
	 * @param string $time
	 *
	 * @return self
	 */
	public function setLoginTime( string $time ): self {
		$this->Login->setLoginTime( $time );

		return $this;
	}

	/**
	 * @param string $time
	 *
	 * @return self
	 */
	public function setLogoutTime( string $time ): self {
		$this->Login->setLogoutTime( $time );

		return $this;
	}
}