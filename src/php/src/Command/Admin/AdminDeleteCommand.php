<?php

declare( strict_types = 1 );

namespace App\Command\Admin;

use Exception, RuntimeException;
use App\Functions as F;
use App\Model\{Admin, Identity};
use App\Model\Values\IdentityFields as IFs;
use App\Model\Helper\{AdminHelper, IdentityHelper};
use Laminas\Db\Adapter\AdapterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};

class AdminDeleteCommand extends Command {
	/** @var AdapterInterface */
	protected AdapterInterface $db;
	/** @var AdminHelper */
	protected AdminHelper $AdminHelper;
	/** @var IdentityHelper */
	protected IdentityHelper $IdentityHelper;

	/** @param AdapterInterface $db */
	public function __construct( AdapterInterface $db ) {
		parent::__construct( 'admin:delete' );
		$this->db             = $db;
		$this->AdminHelper    = new AdminHelper( $this->db );
		$this->IdentityHelper = new IdentityHelper( $this->db );
	}

	protected function configure() {
		$this->setDescription( 'Deletes a admin.' )
		     ->setHelp( 'Use this command to delete a admin...' )
		     ->addArgument( IFs::NAME, InputArgument::REQUIRED, 'Admin Name' )
		     ->addArgument( IFs::PASSWORD, InputArgument::REQUIRED, 'Admin Password' );
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
			throw new Exception( "Password '{$password}' is invalid!" );
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
			$name     = $input->getArgument( IFs::NAME );
			$password = $input->getArgument( IFs::PASSWORD );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Deleting admin with name '{$name}'.</comment>" );
			$Identity = new Identity( $name, $password );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Checking if a identity with name '{$name}' already exists.</comment> " );
			$Identity = $this->assertIdentityExist( $Identity );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Verifying that password '{$password}' is valid.</comment> " );
			$this->assertVerifyPassword( $password, $Identity );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|INFO|<comment>Deleting admin '{$name}'.</comment>" );
			$Admin = $this->AdminHelper->read( new Admin( $Identity ) );
			$this->AdminHelper->delete( $Admin, [ IFs::ID => $Admin->getIdentityId() ] );

			$output->writeln( "<info>".F::createTimeStamp()."</info>|SUCCESS|<question>Successfully deleted admin '{$name}'.</question>" );
		} catch( Exception $e ) {
			$output->writeln( "<info>".F::createTimeStamp()."</info>|ERROR|<error>{$e->getMessage()}</error>" );

			return Command::FAILURE;
		}

		return Command::SUCCESS;
	}
}