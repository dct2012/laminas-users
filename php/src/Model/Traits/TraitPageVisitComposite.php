<?php

declare( strict_types = 1 );

namespace App\Model\Traits;

use App\Model\PageVisit;

trait TraitPageVisitComposite {
	/** @var PageVisit */
	protected PageVisit $PageVisit;
	/** @var ?int */
	protected ?int $page_visit_id;

	/** @return PageVisit */
	public function getPageVisit(): PageVisit {
		return $this->PageVisit;
	}

	/** @return int */
	public function getPageVisitId(): int {
		if( empty( $this->page_visit_id ) ) {
			$this->page_visit_id = $this->PageVisit->getId();
		}

		return $this->page_visit_id;
	}

	/** @return string */
	public function getIpAddress(): string {
		return $this->PageVisit->getIpAddress();
	}

	/** @return string */
	public function getUserAgent(): string {
		return $this->PageVisit->getUserAgent();
	}

	/** @return string */
	public function getVisitTime(): string {
		return $this->PageVisit->getVisitTime();
	}

	/**
	 * @param PageVisit $PageVisit
	 *
	 * @return self
	 */
	public function setPageVisit( PageVisit $PageVisit ): self {
		$this->PageVisit = $PageVisit;

		return $this;
	}

	/**
	 * @param int $id
	 *
	 * @return self
	 */
	public function setPageVisitId( int $id ): self {
		$this->PageVisit->setId( $id );
		$this->page_visit_id = $id;

		return $this;
	}

	/**
	 * @param string $ipAddress
	 *
	 * @return self
	 */
	public function setIpAddress( string $ipAddress ): self {
		$this->PageVisit->setIpAddress( $ipAddress );

		return $this;
	}

	/**
	 * @param string $userAgent
	 *
	 * @return self
	 */
	public function setUserAgent( string $userAgent ): self {
		$this->PageVisit->setUserAgent( $userAgent );

		return $this;
	}

	/**
	 * @param string $time
	 *
	 * @return self
	 */
	public function setVisitTime( string $time ): self {
		$this->PageVisit->setVisitTime( $time );

		return $this;
	}
}