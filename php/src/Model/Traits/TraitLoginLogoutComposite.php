<?php

declare( strict_types = 1 );

namespace App\Model\Traits;

trait TraitLoginLogoutComposite {
	/** @var ?string */
	protected ?string $login_time, $logout_time;

	/** @return ?string */
	public function getLoginTime(): ?string {
		return $this->login_time;
	}

	/** @return ?string */
	public function getLogoutTime(): ?string {
		return $this->logout_time;
	}

	/**
	 * @param ?string $loginTime
	 *
	 * @return self
	 */
	public function setLoginTime( ?string $loginTime ): self {
		$this->login_time = $loginTime;

		return $this;
	}

	/**
	 * @param ?string $logoutTime
	 *
	 * @return self
	 */
	public function setLogoutTime( ?string $logoutTime ): self {
		$this->logout_time = $logoutTime;

		return $this;
	}
}