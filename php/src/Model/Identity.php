<?php

declare( strict_types = 1 );

namespace App\Model;

use App\Model\Traits\TraitComposition;

class Identity implements ModelInterface {
	use TraitComposition;

	/** @var string */
	protected string $name, $password;
	/** @var ?string */
	protected ?string $updated_on, $created_on;

	/**
	 * @param string  $name
	 * @param string  $password
	 * @param ?int    $id
	 * @param ?string $updatedOn
	 * @param ?string $createdOn
	 */
	public function __construct( string $name, string $password, ?int $id = null, ?string $updatedOn = null, ?string $createdOn = null ) {
		$this->setId( $id );
		$this->setName( $name );
		$this->setPassword( $password );
		$this->setUpdatedOn( $updatedOn );
		$this->setCreatedOn( $createdOn );
	}

	/** @return string */
	public function getName(): string {
		return $this->name;
	}

	/** @return string */
	public function getPassword(): string {
		return $this->password;
	}

	/** @return ?string */
	public function getUpdatedOn(): ?string {
		return $this->updated_on;
	}

	/** @return ?string */
	public function getCreatedOn(): ?string {
		return $this->created_on;
	}

	/**
	 * @param string $name
	 *
	 * @return Identity
	 */
	public function setName( string $name ): self {
		$this->name = $name;

		return $this;
	}

	/**
	 * @param string $password
	 *
	 * @return self
	 */
	public function setPassword( string $password ): self {
		$this->password = $this::hashPassword( $password );

		return $this;
	}

	/**
	 * @param string $updatedOn
	 *
	 * @return Identity
	 */
	public function setUpdatedOn( string $updatedOn ): self {
		$this->updated_on = $updatedOn;


		return $this;
	}

	/**
	 * @param string $createdOn
	 *
	 * @return self
	 */
	public function setCreatedOn( string $createdOn ): self {
		$this->created_on = $createdOn;

		return $this;
	}

	/**
	 * @param string $password
	 *
	 * @return string
	 */
	static public function hashPassword( string $password ): string {
		return password_hash( $password, PASSWORD_DEFAULT );
	}

	/**
	 * @param string $hash
	 * @param string $password
	 *
	 * @return bool
	 */
	static public function verifyPassword( string $hash, string $password ): bool {
		return password_verify( $password, $hash );
	}
}