<?php

namespace UserLogin\Model;

use Application\Model\ModelInterface;

class UserLogin implements ModelInterface {
	/* @var ?int */
	protected ?int $id;
	/* @var int */
	protected int $user_id;
	/* @var string */
	protected string $ip_address;
	/* @var string */
	protected string $device;
	/* @var ?string */
	protected ?string $login_time;

	/**
	 * @param int     $userID
	 * @param string  $ipAddress
	 * @param string  $device
	 * @param ?int    $id
	 * @param ?string $loginTime
	 */
	public function __construct( int $userID, string $ipAddress, string $device, ?int $id = null, ?string $loginTime = null ) {
		$this->id         = $id;
		$this->user_id    = $userID;
		$this->device     = $device;
		$this->ip_address = $ipAddress;
		$this->login_time = $loginTime;
	}

	/*  @return ?int */
	public function getId(): ?int {
		return $this->id;
	}

	/* @return int */
	public function getUserId(): int {
		return $this->user_id;
	}

	/* @return string */
	public function getIpAddress(): string {
		return $this->ip_address;
	}

	/* @return string */
	public function getDevice(): string {
		return $this->device;
	}

	/* @return ?string */
	public function getLoginTime(): ?string {
		return $this->login_time;
	}

	/**
	 * @param int $id
	 *
	 * @return UserLogin
	 */
	public function setId( int $id ): self {
		$this->id = $id;

		return $this;
	}

	/**
	 * @param int $userID
	 *
	 * @return UserLogin
	 */
	public function setUserId( int $userID ): self {
		$this->user_id = $userID;

		return $this;
	}

	/**
	 * @param string $ipAddress
	 *
	 * @return UserLogin
	 */
	public function setIpAddress( string $ipAddress ): self {
		$this->ip_address = $ipAddress;

		return $this;
	}

	/**
	 * @param string $device
	 *
	 * @return UserLogin
	 */
	public function setDevice( string $device ): self {
		$this->device = $device;

		return $this;
	}

	/**
	 * @param string $loginTime
	 *
	 * @return UserLogin
	 */
	public function setLoginTime( string $loginTime ): self {
		$this->login_time = $loginTime;

		return $this;
	}
}