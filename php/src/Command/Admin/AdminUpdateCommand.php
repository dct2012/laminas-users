<?php

declare( strict_types = 1 );

namespace App\Command\Admin;

use Exception, RuntimeException;
use App\Model\Identity;
use App\Functions as F;
use App\Model\Helper\IdentityHelper;
use App\Model\Values\IdentityFields as IFs;
use Laminas\Validator\Identical;
use Laminas\Db\Adapter\AdapterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};

class AdminUpdateCommand extends Command {
	const CURRENT_PASSWORD = 'current_password';
	const NEW_PASSWORD     = 'new_password';

	/** @var AdapterInterface */
	protected AdapterInterface $db;
	/** @var IdentityHelper */
	protected IdentityHelper $IdentityHelper;

	/** @param AdapterInterface $db */
	public function __construct( AdapterInterface $db ) {
		parent::__construct( 'admin:update' );
		$this->db             = $db;
		$this->IdentityHelper = new IdentityHelper( $this->db );
	}

	protected function configure() {
		$this->setDescription( "Update admin password." )
		     ->setHelp( "This command allows you to update an admin's password..." )
		     ->addArgument( IFs::NAME, InputArgument::REQUIRED, 'Admin Name' )
		     ->addArgument( self::CURRENT_PASSWORD, InputArgument::REQUIRED, 'Current Admin Password' )
		     ->addArgument( self::NEW_PASSWORD, InputArgument::REQUIRED, 'New Admin Password' );
	}

	/**
	 * @param Identity $Identity
	 *
	 * @return Identity
	 */
	protected function assertIdentityExist( Identity $Identity ): Identity {
		$name     = $Identity->getName();
		$Identity = $this->IdentityHelper->read( $Identity, [ IFs::NAME => $name ] );
		if( empty( $Identity ) || empty( $Identity->getId() ) ) {
			throw new RuntimeException( "Identity with name '{$name}' doesn't exists!" );
		}

		return $Identity;
	}

	/**
	 * @param string   $password
	 * @param Identity $Identity
	 *
	 * @throws Exception
	 */
	protected function assertVerifyPassword( string $password, Identity $Identity ) {
		if( !Identity::verifyPassword( $Identity->getPassword(), $password ) ) {
			throw new Exception( "Current password '{$password}' is invalid!" );
		}
	}

	/**
	 * @param string $currentPassword
	 * @param string $newPassword
	 *
	 * @throws Exception
	 */
	protected function assertPasswordsNotIdentical( string $currentPassword, string $newPassword ) {
		if( ( new Identical( $currentPassword ) )->isValid( $newPassword ) ) {
			throw new Exception( "Current password '{$currentPassword}' and new password '{$newPassword}' are identical!" );
		}
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @return int
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		try {
			$name            = $input->getArgument( IFs::NAME );
			$currentPassword = $input->getArgument( self::CURRENT_PASSWORD );
			$newPassword     = $input->getArgument( self::NEW_PASSWORD );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Updating admin {$name}'s password from '{$currentPassword}' to '{$newPassword}'.</comment>" );
			$Identity = new Identity( $name, $currentPassword );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Checking that the current password ({$currentPassword}) and new password ({$newPassword}) are not the same.</comment> " );
			$this->assertPasswordsNotIdentical( $currentPassword, $newPassword );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Checking that a identity with name '{$name}' already exists.</comment> " );
			$Identity = $this->assertIdentityExist( $Identity );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Verifying that the current password ({$currentPassword}) is correct.</comment> " );
			$this->assertVerifyPassword( $currentPassword, $Identity );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Checking that new password ({$newPassword}) meets requirements.</comment> " );
			F::assertPasswordLength( $newPassword );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Updating Password.</comment>" );
			$this->IdentityHelper->update( $Identity->setPassword( $newPassword ) );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|SUCCESS|<question>Successfully updated admin {$name}'s password to '{$newPassword}'.</question>" );
		} catch( Exception $e ) {
			$output->writeln( "<info>".F::createTimeStamp()."</info>|ERROR|<error>{$e->getMessage()}</error>" );

			return Command::FAILURE;
		}

		return Command::SUCCESS;
	}
}