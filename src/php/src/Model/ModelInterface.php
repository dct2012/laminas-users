<?php

declare( strict_types = 1 );

namespace App\Model;

interface ModelInterface {
	public function getId(): ?int;

	public function setId( int $id ): self;
}