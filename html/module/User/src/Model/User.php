<?php

namespace User\Model;

use Application\Model\ModelInterface;

class User implements ModelInterface {
	/** @var mixed */
	private $id;
	/** @var string */
	private string $username;
	/** @var string */
	private string $password;
	/** @var ?string */
	private ?string $updated_on;
	/** @var ?string */
	private ?string $created_on;

	/**
	 * @param string  $username
	 * @param string  $password
	 * @param ?int    $id
	 * @param ?string $updated_on
	 * @param ?string $created_on
	 */
	public function __construct( string $username, string $password, ?int $id = null, ?string $updated_on = null, ?string $created_on = null ) {
		$this->username   = $username;
		$this->password   = $password;
		$this->updated_on = $updated_on;
		$this->created_on = $created_on;
		$this->id         = $id;
	}

	/** @return ?int */
	public function getId(): ?int {
		return $this->id;
	}

	/** @return string */
	public function getUserName(): string {
		return $this->username;
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
	 * @param int $id
	 *
	 * @return User
	 */
	public function setId( int $id ): self {
		$this->id = $id;

		return $this;
	}

	/**
	 * @param string $username
	 *
	 * @return User
	 */
	public function setUsername( string $username ): self {
		$this->username = $username;

		return $this;
	}

	/**
	 * @param string $password
	 *
	 * @return User
	 */
	public function setPassword( string $password ): self {
		$this->password = $this::hashPassword( $password );

		return $this;
	}

	/**
	 * @param string $updated_on
	 *
	 * @return User
	 */
	public function setUpdatedOn( string $updated_on ): self {
		$this->updated_on = $updated_on;


		return $this;
	}

	/**
	 * @param string $created_on
	 *
	 * @return User
	 */
	public function setCreatedOn( string $created_on ): self {
		$this->created_on = $created_on;

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