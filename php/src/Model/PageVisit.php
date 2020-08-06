<?php

declare( strict_types = 1 );

namespace App\Model;

use App\Model\Traits\{TraitComposition, TraitTrackingComposite};

class PageVisit implements ModelInterface {
	use TraitComposition, TraitTrackingComposite;

	/** @var string */
	protected string  $page;
	/** @var ?string */
	protected ?string $visit_time;

	/**
	 * @param string  $page
	 * @param string  $ipAddress
	 * @param string  $userAgent
	 * @param ?int    $id
	 * @param ?string $visitTime
	 */
	public function __construct( string $page, string $ipAddress, string $userAgent, ?int $id = null, ?string $visitTime = null ) {
		$this->setId( $id );
		$this->setPage( $page );
		$this->setIpAddress( $ipAddress );
		$this->setUserAgent( $userAgent );
		$this->setVisitTime( $visitTime );
	}

	/** @return string */
	public function getPage(): string {
		return $this->page;
	}

	/** @return ?string */
	public function getVisitTime(): ?string {
		return $this->visit_time;
	}

	/**
	 * @param string $page
	 *
	 * @return self
	 */
	public function setPage( string $page ): self {
		$this->page = $page;

		return $this;
	}

	/**
	 * @param string $visitTime
	 *
	 * @return self
	 */
	public function setVisitTime( string $visitTime ): self {
		$this->visit_time = $visitTime;

		return $this;
	}
}