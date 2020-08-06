<?php

declare( strict_types = 1 );

namespace App\Model\Traits;

trait TraitComposition {
	/** @var ?int */
	protected ?int $id;

	/** @return ?int */
	public function getId(): ?int {
		return $this->id;
	}

	/**
	 * @param ?int $id
	 *
	 * @return self
	 */
	public function setId( ?int $id ): self {
		$this->id = $id;

		return $this;
	}
}