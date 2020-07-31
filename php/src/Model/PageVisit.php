<?php

declare( strict_types = 1 );

namespace App\Model;

class PageVisit implements ModelInterface {
	protected ?int    $id;
	protected string  $page;
	protected ?int    $user_id;
	protected string  $ip_address;
	protected string  $device;
	protected ?string $visit_time;

	/**
	 * @param string  $page
	 * @param string  $ip_address
	 * @param string  $device
	 * @param ?int    $user_id
	 * @param ?int    $id
	 * @param ?string $visit_time
	 */
	public function __construct( string $page, string $ip_address, string $device, ?int $user_id = null, ?int $id = null, ?string $visit_time = null ) {
		$this->id         = $id;
		$this->page       = $page;
		$this->user_id    = $user_id;
		$this->ip_address = $ip_address;
		$this->device     = $device;
		$this->visit_time = $visit_time;
	}

	/**
	 * @return ?int
	 */
	public function getId(): ?int {
		return $this->id;
	}

	/** @return string */
	public function getPage(): string {
		return $this->page;
	}

	/** @return ?int */
	public function getUserId(): ?int {
		return $this->user_id;
	}

	/** @return string */
	public function getIpAddress(): string {
		return $this->ip_address;
	}

	/** @return string */
	public function getDevice(): string {
		return $this->device;
	}

	/** @return ?string */
	public function getVisitTime(): ?string {
		return $this->visit_time;
	}

	/**
	 * @param int $id
	 *
	 * @return PageVisit
	 */
	public function setId( int $id ): self {
		$this->id = $id;

		return $this;
	}

	/**
	 * @param string $page
	 *
	 * @return PageVisit
	 */
	public function setPage( string $page ): self {
		$this->page = $page;

		return $this;
	}

	/**
	 * @param int $user_id
	 *
	 * @return PageVisit
	 */
	public function setUserId( int $user_id ): self {
		$this->user_id = $user_id;

		return $this;
	}

	/**
	 * @param string $ip_address
	 *
	 * @return PageVisit
	 */
	public function setIpAddress( string $ip_address ): self {
		$this->ip_address = $ip_address;

		return $this;
	}

	/**
	 * @param string $device
	 *
	 * @return PageVisit
	 */
	public function setDevice( string $device ): self {
		$this->device = $device;

		return $this;
	}

	/**
	 * @param string $visit_time
	 *
	 * @return PageVisit
	 */
	public function setVisitTime( string $visit_time ): self {
		$this->visit_time = $visit_time;

		return $this;
	}
}